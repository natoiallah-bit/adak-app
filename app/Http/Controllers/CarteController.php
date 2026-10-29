<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Parametre;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;

class CarteController extends Controller
{
    public function index(Request $request)
    {
        $membres = Membre::with('categorie')->recherche($request->q)
            ->when($request->carte, fn ($q, $c) => $q->where('carte_statut', $c), fn ($q) => $q->where('carte_statut', 'a_imprimer'))
            ->orderBy('nom')->get();

        return view('cartes.index', compact('membres'));
    }

    /** Aperçu et impression : mode "carte" (une carte par page) ou "planche" (A4, 10 cartes). */
    public function imprimer(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array|min:1', 'ids.*' => 'integer|exists:membres,id', 'mode' => 'in:carte,planche']);
        $membres = Membre::with('categorie')->whereIn('id', $data['ids'])->orderBy('nom')->get();

        $bloques = $membres->filter->carteBloquee();
        $membres = $membres->reject->carteBloquee();
        abort_if($membres->isEmpty(), 422, 'Toutes les cartes sélectionnées sont bloquées (cotisation en retard).');

        $qr = new QRCode(new QROptions(['outputType' => QRCode::OUTPUT_MARKUP_SVG, 'outputBase64' => true, 'eccLevel' => EccLevel::M]));

        return view('cartes.impression', [
            'membres' => $membres,
            'bloques' => $bloques,
            'mode' => $data['mode'] ?? 'carte',
            'qr' => fn (Membre $m) => $qr->render($m->urlVerification()),
            'validite' => Parametre::valeur('validite_carte', (string) now()->year),
            'telephone' => Parametre::valeur('association_telephone', ''),
            'email' => Parametre::valeur('association_email', ''),
        ]);
    }

    /** À appeler après l'impression pour suivre l'état des cartes. */
    public function marquer(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:membres,id', 'etat' => 'required|in:imprimee,remise,perdue,a_imprimer']);
        $q = Membre::whereIn('id', $data['ids']);
        if ($data['etat'] === 'imprimee') {
            $q->update(['carte_statut' => 'imprimee', 'carte_imprimee_le' => now()]);
        } else {
            if ($data['etat'] === 'a_imprimer') {
                $q->increment('carte_duplicata'); // nouvelle carte demandée = duplicata, même numéro
            }
            $q->update(['carte_statut' => $data['etat']]);
        }

        return redirect()->route('cartes.index')->with('ok', 'État des cartes mis à jour.');
    }
}
