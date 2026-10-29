<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MembreController extends Controller
{
    public function index(Request $request)
    {
        $membres = Membre::with('categorie')->recherche($request->q)
            ->when($request->statut, fn ($q, $s) => $q->where('statut', $s))
            ->when($request->carte, fn ($q, $c) => $q->where('carte_statut', $c))
            ->orderBy('nom')->orderBy('prenom')->paginate(25)->withQueryString();

        return view('membres.index', compact('membres'));
    }

    public function create()
    {
        return view('membres.form', ['membre' => new Membre(['statut' => 'actif', 'date_adhesion' => now()]), 'categories' => Categorie::where('actif', true)->orderBy('nom')->get()]);
    }

    public function store(Request $request)
    {
        $membre = Membre::create($this->donnees($request));

        return redirect()->route('membres.show', $membre)->with('ok', 'Membre créé : '.$membre->numero);
    }

    public function show(Membre $membre)
    {
        $membre->load('categorie', 'cotisations');

        return view('membres.show', compact('membre'));
    }

    public function edit(Membre $membre)
    {
        return view('membres.form', ['membre' => $membre, 'categories' => Categorie::where('actif', true)->orderBy('nom')->get()]);
    }

    public function update(Request $request, Membre $membre)
    {
        $membre->update($this->donnees($request, $membre));

        return redirect()->route('membres.show', $membre)->with('ok', 'Fiche mise à jour.');
    }

    public function destroy(Membre $membre)
    {
        if ($membre->photo) {
            Storage::disk('public')->delete($membre->photo);
        }
        $membre->delete();

        return redirect()->route('membres.index')->with('ok', 'Membre supprimé.');
    }

    private function donnees(Request $request, ?Membre $membre = null): array
    {
        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'sexe' => 'nullable|in:M,F',
            'date_naissance' => 'nullable|date',
            'telephone' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:150',
            'ville' => 'nullable|string|max:100',
            'profession' => 'nullable|string|max:120',
            'promotion' => 'nullable|string|max:60',
            'categorie_id' => 'nullable|exists:categories,id',
            'date_adhesion' => 'nullable|date',
            'statut' => 'required|in:'.implode(',', array_keys(Membre::STATUTS)),
            'photo' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('photo')) {
            if ($membre?->photo) {
                Storage::disk('public')->delete($membre->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        } else {
            unset($data['photo']);
        }

        return $data;
    }
}
