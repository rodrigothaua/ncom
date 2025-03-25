<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PerfilController extends Controller
{
    // Exibe o perfil do usuário
    public function show()
    {
        $user = Auth::user();
        return view('perfil_usuario_view', compact('user'));
    }

    // Mostra o formulário para editar perfil
    public function edit()
    {
        $user = Auth::user(); // Obtém o usuário autenticado
        return view('editar_perfil', compact('user'));
    }

    // Atualiza as informações do perfil
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|in:admin,user',
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profile_photos'), $filename);
            $validated['profile_photo'] = 'profile_photos/' . $filename;
        }

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('perfil')->with('success', 'Perfil atualizado com sucesso!');
    }
}
