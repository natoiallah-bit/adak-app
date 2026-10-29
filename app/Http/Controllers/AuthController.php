<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function formulaire()
    {
        return view('auth.login');
    }

    public function connexion(Request $request)
    {
        $identifiants = $request->validate(['email' => 'required|email', 'password' => 'required']);

        if (! Auth::attempt($identifiants, $request->boolean('se_souvenir'))) {
            return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('accueil'));
    }

    public function deconnexion(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('connexion');
    }
}
