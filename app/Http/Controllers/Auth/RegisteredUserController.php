<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Verifica se o usuário autenticado é um administrador
        if (!Auth::user()->is_admin) {
            abort(403, 'Acesso não autorizado.');
        }

        // Renderiza a view correta
        return view('usuarios.create');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Verifica se o usuário autenticado é um administrador
        if (!Auth::user()->is_admin) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate([
            'name' => ['required', 'string', 'maxlength:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'maxlength:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect(RouteServiceProvider::HOME);
    }
}