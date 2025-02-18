<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    // Mostrar o formulário de registro
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Registrar o novo usuário
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Criar o usuário
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Autenticar o usuário automaticamente
        Auth::login($user);

        // Redirecionar após o registro
        return redirect()->route('dashboard')->with('success', 'Usuário registrado com sucesso!');
    }
}

