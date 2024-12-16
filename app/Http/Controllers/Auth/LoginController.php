<?php

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Este método redireciona o usuário após o login
    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('dashboard');
    }
}
