<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Parametre;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    public function index()
    {
        return view('parametres.index', [
            'categories' => Categorie::orderBy('nom')->get(),
            'p' => fn (string $cle, $defaut = '') => Parametre::valeur($cle, $defaut),
        ]);
    }

    public function enregistrer(Request $request)
    {
        $data = $request->validate([
            'validite_carte' => 'required|string|max:20',
            'association_telephone' => 'nullable|string|max:60',
            'association_email' => 'nullable|email|max:150',
        ]);
        foreach ($data as $cle => $valeur) {
            Parametre::definir($cle, $valeur);
        }
        Parametre::definir('blocage_carte_retard', $request->boolean('blocage_carte_retard') ? '1' : '0');

        return back()->with('ok', 'Paramètres enregistrés.');
    }

    public function categorieStore(Request $request)
    {
        $data = $request->validate(['nom' => 'required|string|max:80|unique:categories,nom', 'montant_cotisation' => 'nullable|integer|min:0']);
        Categorie::create($data);

        return back()->with('ok', 'Catégorie ajoutée.');
    }

    public function categorieUpdate(Request $request, Categorie $categorie)
    {
        $data = $request->validate(['nom' => 'required|string|max:80|unique:categories,nom,'.$categorie->id, 'montant_cotisation' => 'nullable|integer|min:0']);
        $categorie->update($data + ['actif' => $request->boolean('actif')]);

        return back()->with('ok', 'Catégorie mise à jour.');
    }
}
