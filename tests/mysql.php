<?php
// Solo CLI. Crea una base temporal con la estructura REAL, nunca copia registros.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$db = Illuminate\Support\Facades\DB::connection();
if (!in_array($db->getDriverName(), ['mysql','mariadb'],true)) throw new RuntimeException('Se requiere MySQL local.');
$origen = $db->getDatabaseName();
$nombre = 'renta_pruebas_'.bin2hex(random_bytes(6));
$tablas = array_keys(App\Services\RegistroCrud::LLAVES);
$tablas = array_merge($tablas,['permisos','roles','rol_permiso','users']);
$definiciones=[];
foreach($tablas as $tabla) {
    $fila=(array)$db->selectOne('SHOW CREATE TABLE `'.$tabla.'`');
    $definiciones[] = preg_replace('/ AUTO_INCREMENT=\d+/', '', $fila['Create Table']);
}
// CHECKSUM TABLE permite verificar que la ejecución no alteró registros del origen.
$huellas = [];
foreach($tablas as $tabla) $huellas[$tabla] = (array)$db->selectOne('CHECKSUM TABLE `'.$tabla.'`');
$relaciones = $db->table('information_schema.KEY_COLUMN_USAGE')->where('TABLE_SCHEMA',$origen)->whereNotNull('REFERENCED_TABLE_NAME')->get(['TABLE_NAME','COLUMN_NAME','REFERENCED_TABLE_NAME','REFERENCED_COLUMN_NAME']);
file_put_contents(__DIR__.'/../docs/relaciones-verificadas.json', json_encode($relaciones,JSON_PRETTY_PRINT));
$evidencia = __DIR__.'/../docs/evidencia-transacciones.jsonl';
$codigo=1;
try {
    $db->statement('CREATE DATABASE `'.$nombre.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $pdo=$db->getPdo(); $pdo->exec('USE `'.$nombre.'`');
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
    foreach($definiciones as $sql) $pdo->exec($sql);
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
    $motores=$pdo->query('SHOW TABLE STATUS')->fetchAll(PDO::FETCH_ASSOC);
    foreach($motores as $tabla) {
        if($tabla['Engine']!=='InnoDB') throw new RuntimeException('Tabla no InnoDB: '.$tabla['Name']);
    }
    file_put_contents(__DIR__.'/../docs/motores-pruebas.json',json_encode(array_column($motores,'Engine','Name'),JSON_PRETTY_PRINT));
    file_put_contents($evidencia,'');
    foreach(['APP_ENV'=>'testing','DB_CONNECTION'=>'mysql','DB_DATABASE'=>$nombre,'DB_URL'=>'','CACHE_STORE'=>'array','SESSION_DRIVER'=>'array','QUEUE_CONNECTION'=>'sync','BCRYPT_ROUNDS'=>'4','CRUD_EVIDENCIA'=>$evidencia] as $clave=>$valor) putenv($clave.'='.$valor);
    echo 'Base aislada: '.$nombre.PHP_EOL;
    $proceso=proc_open([PHP_BINARY,__DIR__.'/../vendor/phpunit/phpunit/phpunit','--no-configuration',__DIR__.'/Feature/OperacionesCrudTest.php'],[0=>STDIN,1=>STDOUT,2=>STDERR],$pipes,dirname(__DIR__));
    if (!is_resource($proceso)) throw new RuntimeException('No se pudo ejecutar PHPUnit.');
    $codigo=proc_close($proceso);
} finally {
    $db->getPdo()->exec('USE `'.str_replace('`','``',$origen).'`');
    // El nombre se genera aquí; nunca se acepta un destino proporcionado externamente.
    if (!preg_match('/^renta_pruebas_[a-f0-9]{12}$/D',$nombre) || $nombre===$origen) throw new RuntimeException('Destino de limpieza inválido.');
    $db->statement('DROP DATABASE IF EXISTS `'.$nombre.'`');
    foreach($tablas as $tabla) {
        if ($huellas[$tabla] !== (array)$db->selectOne('CHECKSUM TABLE `'.$tabla.'`')) throw new RuntimeException('Cambió la huella del origen: '.$tabla);
    }
    echo 'Base temporal eliminada; CHECKSUM TABLE confirma que el origen sigue intacto.'.PHP_EOL;
}
exit($codigo);
