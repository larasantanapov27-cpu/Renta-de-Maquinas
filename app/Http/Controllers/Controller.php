<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function show($id)
    {
        return \App\Services\RegistroCrud::detalle(explode('.', request()->route()->getName())[0], (int)$id);
    }
}
