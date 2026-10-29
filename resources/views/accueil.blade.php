@extends('layouts.app')
@section('titre', 'Tableau de bord')
@section('contenu')
<div class="kpis">
  <div class="kpi"><small>Membres actifs</small><b>{{ $total }}</b></div>
  <div class="kpi"><small>Cotisations à jour</small><b>{{ $tauxAJour === null ? '—' : $tauxAJour.' %' }}</b></div>
  <div class="kpi"><small>Cartes à imprimer</small><b>{{ $aImprimer }}</b></div>
  <div class="kpi"><small>Encaissé en {{ $annee }}</small><b>{{ number_format($encaisse, 0, ',', ' ') }} F</b></div>
</div>
<div class="bloc"><h3>Derniers membres ajoutés</h3>
<table><tr><th>N°</th><th>Nom</th><th>Ville</th><th>Carte</th></tr>
@forelse($recents as $m)<tr><td>{{ $m->numero }}</td><td><a href="{{ route('membres.show', $m) }}">{{ $m->nom_complet }}</a></td><td>{{ $m->ville }}</td><td>{{ \App\Models\Membre::CARTES[$m->carte_statut] }}</td></tr>
@empty<tr><td colspan="4">Aucun membre pour le moment.</td></tr>@endforelse</table></div>
@if($tauxAJour === null)<p style="color:var(--gris)">Le taux de cotisation s'affichera quand le bureau national aura fixé les montants dans <a href="{{ route('parametres.index') }}">Paramètres</a>.</p>@endif
@endsection
