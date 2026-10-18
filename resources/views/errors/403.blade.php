<!DOCTYPE html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Acceso no autorizado | San Juan</title><link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}"></head>
<body class="bg-light"><main class="container py-5"><div class="card card-body mx-auto" style="max-width:640px"><h1 class="h3">Acceso no autorizado</h1><p>{{ $exception->getMessage() ?: 'No tienes permiso para realizar esta operación.' }}</p>
@auth
<p>Si tu cuenta fue desactivada o está pendiente de aprobación, solicita la revisión de un SuperAdmin.</p>
<form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-dark">Cerrar sesión</button></form>
@else<a class="btn btn-dark" href="{{ route('login') }}">Iniciar sesión</a>@endauth
</div></main></body></html>
