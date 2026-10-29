@extends('layouts.app')
@section('titre', $membre->exists ? 'Modifier '.$membre->nom_complet : 'Nouveau membre')
@section('contenu')
<form class="bloc" method="post" enctype="multipart/form-data" action="{{ $membre->exists ? route('membres.update', $membre) : route('membres.store') }}">
@csrf @if($membre->exists) @method('PUT') @endif
<div class="grille2">
  <div><label>Nom *</label><input name="nom" value="{{ old('nom', $membre->nom) }}" required></div>
  <div><label>Prénom *</label><input name="prenom" value="{{ old('prenom', $membre->prenom) }}" required></div>
  <div><label>Sexe</label><select name="sexe"><option value="">—</option><option value="M" @selected(old('sexe',$membre->sexe)=='M')>Masculin</option><option value="F" @selected(old('sexe',$membre->sexe)=='F')>Féminin</option></select></div>
  <div><label>Date de naissance</label><input type="date" name="date_naissance" value="{{ old('date_naissance', $membre->date_naissance?->format('Y-m-d')) }}"></div>
  <div><label>Téléphone</label><input name="telephone" value="{{ old('telephone', $membre->telephone) }}"></div>
  <div><label>E-mail</label><input type="email" name="email" value="{{ old('email', $membre->email) }}"></div>
  <div><label>Ville</label><input name="ville" value="{{ old('ville', $membre->ville) }}"></div>
  <div><label>Profession</label><input name="profession" value="{{ old('profession', $membre->profession) }}"></div>
  <div><label>Promotion / année</label><input name="promotion" value="{{ old('promotion', $membre->promotion) }}"></div>
  <div><label>Catégorie</label><select name="categorie_id"><option value="">—</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('categorie_id',$membre->categorie_id)==$c->id)>{{ $c->nom }}</option>@endforeach</select></div>
  <div><label>Date d'adhésion</label><input type="date" name="date_adhesion" value="{{ old('date_adhesion', $membre->date_adhesion?->format('Y-m-d')) }}"></div>
  <div><label>Statut *</label><select name="statut">@foreach(\App\Models\Membre::STATUTS as $k=>$v)<option value="{{ $k }}" @selected(old('statut',$membre->statut)==$k)>{{ $v }}</option>@endforeach</select></div>
  <div><label>Photo (portrait, 4 Mo max)</label><input type="file" name="photo" accept="image/*"></div>
</div>
<div style="margin-top:18px;display:flex;gap:10px"><button class="btn rouge">Enregistrer</button><a class="btn gris" href="{{ $membre->exists ? route('membres.show',$membre) : route('membres.index') }}">Annuler</a></div>
</form>
@endsection
