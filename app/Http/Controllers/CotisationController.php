<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\Membre;
use Illuminate\Http\Request;

class CotisationController extends Controller
{
    public function store(Request $request, Membre $membre)
    {
        $data = $request->validate([
            'annee' => 'required|integer|min:2000|max:2100',
            'montant' => 'required|integer|min:1',
            'mode' => 'required|in:especes,mobile_money,virement',
            'paye_le' => 'required|date',
        ]);

        $cotisation = Cotisation::create($data + ['membre_id' => $membre->id, 'recu_numero' => $this->numeroRecu()]);

        return redirect()->route('membres.show', $membre)->with('ok', 'Paiement enregistré, reçu '.$cotisation->recu_numero.'.');
    }

    private function numeroRecu(): string
    {
        $suite = (int) Cotisation::max('id') + 1;
        do {
            $numero = sprintf('R-%d-%04d', now()->year, $suite++);
        } while (Cotisation::where('recu_numero', $numero)->exists());

        return $numero;
    }
}
