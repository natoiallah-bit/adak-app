@extends('layouts.app')
@section('titre', 'Paramètres')
@section('contenu')
<div class="bloc"><h3>Catégories de membres et cotisations</h3>
<p style="color:var(--gris);margin-bottom:12px">Les montants sont fixés par le bureau national. Tant qu'un montant est vide, la cotisation de la catégorie est « non définie » et aucun membre n'est considéré en retard.</p>
@foreach($categories as $c)
<form method="post" action="{{ route('categories.update', $c) }}" class="barre">@csrf @method('PUT')
 <input name="nom" value="{{ $c->nom }}" style="flex:1;min-width:180px"><input type="number" min="0" name="montant_cotisation" value="{{ $c->montant_cotisation }}" placeholder="Montant annuel (F)" style="width:190px">
 <label style="display:flex;gap:6px;align-items:center;margin:0"><input type="checkbox" name="actif" value="1" @checked($c->actif)> Active</label><button class="btn or">Enregistrer</button></form>
@endforeach
<form method="post" action="{{ route('categories.store') }}" class="barre" style="margin-top:16px;border-top:1px solid var(--ligne);padding-top:14px">@csrf
 <input name="nom" placeholder="Nouvelle catégorie" style="flex:1;min-width:180px" required><input type="number" min="0" name="montant_cotisation" placeholder="Montant annuel (F)" style="width:190px"><button class="btn">Ajouter</button></form></div>
<form class="bloc" method="post" action="{{ route('parametres.enregistrer') }}">@csrf<h3>Cartes et association</h3>
<div class="grille2">
 <div><label>Année de validité des cartes</label><input name="validite_carte" value="{{ $p('validite_carte', now()->year) }}" required></div><div></div>
 <div><label>Téléphone de l'association (verso)</label><input name="association_telephone" value="{{ $p('association_telephone') }}"></div>
 <div><label>E-mail de l'association (verso)</label><input type="email" name="association_email" value="{{ $p('association_email') }}"></div>
</div>
<label style="display:flex;gap:8px;align-items:center;margin:16px 0;text-transform:none;font-size:14px;letter-spacing:0;color:var(--noir)"><input type="checkbox" name="blocage_carte_retard" value="1" @checked($p('blocage_carte_retard','0')==='1')> Bloquer l'impression des cartes des membres dont la cotisation est en retard</label>
<button class="btn rouge">Enregistrer</button></form>
@endsection
