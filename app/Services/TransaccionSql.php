<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class TransaccionSql
{
    public static function ejecutar(array $tablas, callable $operacion): mixed
    {
        $db = DB::connection();
        if ($db->transactionLevel() !== 0 || $db->getPdo()->inTransaction()) {
            throw new \LogicException('No se permiten transacciones anidadas.');
        }
        $iniciada = false;
        try {
            if (in_array($db->getDriverName(), ['mysql','mariadb'], true)) {
                $motores = $db->table('information_schema.TABLES')->where('TABLE_SCHEMA', $db->getDatabaseName())->whereIn('TABLE_NAME', $tablas)->pluck('ENGINE', 'TABLE_NAME');
                foreach ($tablas as $tabla) {
                    if (strtoupper($motores[$tabla] ?? '') !== 'INNODB') {
                        Log::error('Motor no transaccional', ['tabla' => $tabla]);
                        throw ValidationException::withMessages(['operacion' => 'La operación requiere tablas InnoDB. Contacta al administrador.']);
                    }
                }
            } elseif (!app()->environment('testing') || $db->getDriverName() !== 'sqlite') {
                throw new \LogicException('Esta operación requiere MySQL/InnoDB.');
            }
            // SQLite solo se usa en la suite aislada; producción ejecuta el SQL académico.
            $db->unprepared(in_array($db->getDriverName(), ['mysql','mariadb'], true) ? 'START TRANSACTION' : 'BEGIN TRANSACTION');
            $iniciada = true;
            // Una reconexión no debe continuar la operación con autocommit en otra sesión.
            $db->setReconnector(static function () { throw new \RuntimeException('Conexión perdida durante la transacción manual.'); });
            $resultado = $operacion();
            $db->unprepared('COMMIT');
            return $resultado;
        } catch (Throwable $error) {
            try { if ($iniciada) $db->unprepared('ROLLBACK'); }
            catch (Throwable $rollback) { Log::critical('Error en ROLLBACK', ['exception' => $rollback]); }
            if ($error instanceof ValidationException) throw $error;
            if ($error instanceof HttpExceptionInterface) {
                if ($error->getStatusCode() === 422) throw ValidationException::withMessages(['operacion'=>$error->getMessage()]);
                throw $error;
            }
            Log::error('Operación transaccional revertida', ['tablas' => $tablas, 'exception' => $error]);
            throw ValidationException::withMessages(['operacion' => 'No se pudo guardar la operación. Se deshicieron todos los cambios. Intenta nuevamente.']);
        } finally {
            $db->setReconnector(static fn ($connection) => app('db')->reconnect($connection->getNameWithReadWriteType()));
        }
    }
}
