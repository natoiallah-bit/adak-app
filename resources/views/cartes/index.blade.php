@extends('layouts.app')
@section('titre', 'Cartes de membre')
@section('contenu')
<form class="barre" method="get">
  <input name="q" value="{{ request('q') }}" placeholder="Rechercher…" style="flex:1;min-width:200px">
  <select name="carte">@foreach(\App\Models\Membre::CARTES as $k=>$v)<option value="{{ $k }}" @selected(request('carte','a_imprimer')==$k)>{{ $v }}</option>@endforeach</select>
  <button class="btn or">Filtrer</button>
</form>
<form method="post" id="lot" target="_blank">@csrf
<table><tr><th><input type="checkbox" onclick="document.querySelectorAll('.ck').forEach(c=>c.checked=this.checked)"></th><th>N°</th><th>Nom</th><th>Catégorie</th><th>Photo</th><th>Cotisation</th><th>Carte</th></tr>
@forelse($membres as $m)
<tr><td><input class="ck" type="checkbox" name="ids[]" value="{{ $m->id }}" @disabled($m->carteBloquee())></td><td>{{ $m->numero }}</td><td><a href="{{ route('membres.show',$m) }}">{{ $m->nom_complet }}</a></td><td>{{ $m->categorie?->nom }}</td>
<td>@if($m->photo)<span class="badge ok">Oui</span>@else<span class="badge att">Manquante</span>@endif</td>
<td>@php $a=$m->estAJour(); @endphp<span class="badge {{ $a===null?'neutre':($a?'ok':'non') }}">{{ $a===null?'Non défini':($a?'À jour':'En retard') }}</span></td>
<td><span class="badge {{ $m->carteBloquee()?'non':($m->carte_statut=='a_imprimer'?'att':'ok') }}">{{ $m->carteBloquee()?'Bloquée':\App\Models\Membre::CARTES[$m->carte_statut] }}</span></td></tr>
@empty<tr><td colspan="7">Aucune carte dans cette liste.</td></tr>@endforelse</table>
<div class="barre" style="margin-top:14px">
  <button class="btn rouge" formaction="{{ route('cartes.imprimer') }}" name="mode" value="carte">Imprimer (une carte par page)</button>
  <button class="btn or" formaction="{{ route('cartes.imprimer') }}" name="mode" value="planche">Planche A4 (10 cartes)</button>
  <select name="etat"><option value="imprimee">Marquer : imprimée</option><option value="remise">Marquer : remise</option><option value="perdue">Marquer : perdue</option><option value="a_imprimer">Marquer : à réimprimer (duplicata)</option></select>
  <button class="btn gris" formaction="{{ route('cartes.marquer') }}" formtarget="_self">Appliquer</button>
</div>
</form>
@endsection
