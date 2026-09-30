<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class login extends Controller
{
    // Mostrar el formulario de inicio de sesión.
    public function showLogin()
    {
        return view('auth.login');
    }

    // Validar credenciales e iniciar sesión.
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Escribe tu correo electrónico.',
            'email.string' => 'El correo debe ser un texto.',
            'email.email' => 'Escribe un correo electrónico válido.',
            'email.max' => 'El correo es demasiado largo.',

            'password.required' => 'Escribe tu contraseña.',
            'password.string' => 'La contraseña debe ser un texto.',
        ]);

        $credentials['email'] = strtolower($credentials['email']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('panel'));
        }

        return back()
            ->withErrors([
                'email' => 'El correo o la contraseña son incorrectos.',
            ])
            ->onlyInput('email');
    }

    // Mostrar el formulario de registro.
    public function showRegister()
    {
        return view('auth.register');
    }

    // Validar y guardar al nuevo usuario.
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'bail',
                'required',
                'string',
                'min:2',
                'max:100',
                "regex:/^[\p{L}\p{M}]+(?:[ '’\-][\p{L}\p{M}]+)*$/u",
            ],

            'email' => [
                'bail',
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'bail',
                'required',
                'string',
                'min:12',
                'max:64',
                'confirmed',

                // Evitar superar el límite de bytes de bcrypt.
                function ($attribute, $value, $fail) {
                    if (strlen($value) > 72) {
                        $fail(
                            'La contraseña es demasiado larga en bytes. '
                            . 'Reduce los caracteres especiales o emojis.'
                        );
                    }
                },
            ],

            'password_confirmation' => [
                'required',
                'string',
            ],
        ], [
            'name.required' => 'Escribe tu nombre.',
            'name.string' => 'El nombre debe ser un texto.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'name.max' => 'El nombre no debe superar 100 caracteres.',
            'name.regex' => 'Usa letras, espacios, apóstrofos o guiones en el nombre.',

            'email.required' => 'Escribe tu correo electrónico.',
            'email.string' => 'El correo debe ser un texto.',
            'email.email' => 'Escribe un correo electrónico válido.',
            'email.max' => 'El correo no debe superar 255 caracteres.',
            'email.unique' => 'Este correo ya tiene una cuenta registrada.',

            'password.required' => 'Escribe una contraseña.',
            'password.string' => 'La contraseña debe ser un texto.',
            'password.min' => 'La contraseña debe tener al menos 12 caracteres.',
            'password.max' => 'La contraseña no debe superar 64 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',

            'password_confirmation.required' => 'Confirma tu contraseña.',
            'password_confirmation.string' => 'La confirmación debe ser un texto.',
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = strtolower($data['email']);
        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Tu cuenta se creó correctamente. Ya puedes iniciar sesión.'
            );
    }

    // Cerrar sesión.
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}