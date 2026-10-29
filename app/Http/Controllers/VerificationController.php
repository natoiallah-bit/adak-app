<?php

namespace App\Http\Controllers;

use App\Models\Membre;

class VerificationController extends Controller
{
    /** Page publique ouverte par le QR code : ne révèle que le strict nécessaire. */
    public function __invoke(string $token)
    {
        $membre = Membre::with('categorie')->where('verification_token', $token)->first();

        return view('verification.show', ['membre' => $membre]);
    }
}
