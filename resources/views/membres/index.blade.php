@extends('layouts.app')
@section('titre', 'Membres ('.$membres->total().')')
@section('actions')<a class="btn rouge" href="{{ route('membres.create') }}">+ Nouveau membre</a>@endsection
@section('contenu')
<form class="barre" method="get">
  <input name="q" value="{{ request('q') }}" placeholder="Nom, numéro, téléphone…" style="flex:1;min-width:200px">
  <select name="statut"><option value="">Tous statuts</option>@foreach(\App\Models\Membre::STATUTS as $k=>$v)<option value="{{ $k }}" @selected(request('statut')==$k)>{{ $v }}</option>@endforeach</select>
  <select name="carte"><option value="">Toutes cartes</option>@foreach(\App\Models\Membre::CARTES as $k=>$v)<option value="{{ $k }}" @selected(request('carte')==$k)>{{ $v }}</option>@endforeach</select>
  <button class="btn or">Filtrer</button>
</form>
<table><tr><th>N°</th><th>Nom et prénom</th><th>Ville</th><th>Catégorie</th><th>Cotisation</th><th>Carte</th></tr>
@forelse($membres as $m)
<tr><td>{{ $m->numero }}</td><td><a href="{{ route('membres.show', $m) }}">{{ $m->nom_complet }}</a></td><td>{{ $m->ville }}</td><td>{{ $m->categorie?->nom }}</td>
<td>@php $a=$m->estAJour(); @endphp <span class="badge {{ $a===null?'neutre':($a?'ok':'non') }}">{{ $a===null?'Non défini':($a?'À jour':'En retard') }}</span></td>
<td><span class="badge {{ $m->carte_statut=='a_imprimer'?'att':'ok' }}">{{ \App\Models\Membre::CARTES[$m->carte_statut] }}</span></td></tr>
@empty<tr><td colspan="6">Aucun membre.</td></tr>@endforelse</table>
<div style="margin-top:14px">{{ $membres->links() }}</div>
@endsection
