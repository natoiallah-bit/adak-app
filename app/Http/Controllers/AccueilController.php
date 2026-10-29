<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\Membre;

class AccueilController extends Controller
{
    public function __invoke()
    {
        $annee = now()->year;
        $membres = Membre::with('categorie')->get();
        $definis = $membres->filter(fn ($m) => $m->estAJour() !== null);

        return view('accueil', [
            'total' => $membres->where('statut', 'actif')->count(),
            'tauxAJour' => $definis->isEmpty() ? null : round($definis->filter->estAJour()->count() / $definis->count() * 100),
            'aImprimer' => $membres->where('carte_statut', 'a_imprimer')->count(),
            'encaisse' => Cotisation::where('annee', $annee)->sum('montant'),
            'recents' => Membre::latest()->take(5)->get(),
            'annee' => $annee,
        ]);
    }
}
