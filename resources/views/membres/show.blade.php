@extends('layouts.app')
@section('titre', $membre->numero)
@section('actions')
<a class="btn gris" href="{{ route('membres.edit', $membre) }}">Modifier</a>
<form method="post" action="{{ route('cartes.imprimer') }}" style="display:inline" target="_blank">@csrf<input type="hidden" name="ids[]" value="{{ $membre->id }}"><button class="btn rouge" @disabled($membre->carteBloquee())>Imprimer la carte</button></form>
@endsection
@section('contenu')
<div class="fiche">
 <div><div class="ph" style="{{ $membre->photo ? "background-image:url('".asset('storage/'.$membre->photo)."')" : '' }}"></div></div>
 <div>
  <div class="bloc"><div class="champs">
   <div><label>Nom</label><span>{{ mb_strtoupper($membre->nom) }}</span></div><div><label>Prénom</label><span>{{ $membre->prenom }}</span></div>
   <div><label>Catégorie</label><span>{{ $membre->categorie?->nom ?? '—' }}</span></div><div><label>Statut</label><span>{{ \App\Models\Membre::STATUTS[$membre->statut] }}</span></div>
   <div><label>Ville</label><span>{{ $membre->ville ?: '—' }}</span></div><div><label>Téléphone</label><span>{{ $membre->telephone ?: '—' }}</span></div>
   <div><label>Promotion</label><span>{{ $membre->promotion ?: '—' }}</span></div><div><label>Adhésion</label><span>{{ $membre->date_adhesion?->format('d/m/Y') ?: '—' }}</span></div>
   <div><label>Carte</label><span>{{ \App\Models\Membre::CARTES[$membre->carte_statut] }}@if($membre->carte_duplicata) (duplicata n° {{ $membre->carte_duplicata }})@endif</span></div>
  </div>
  @if($membre->carteBloquee())<div class="erreur" style="margin-top:14px">Carte bloquée : cotisation en retard.</div>@endif</div>

  @php $annee = now()->year; $cat = $membre->categorie?->montant_cotisation; $paye = $membre->totalPaye($annee); @endphp
  <div class="bloc"><h3>Cotisation {{ $annee }}</h3>
   @if($cat === null)<p style="color:var(--gris)">Montant non défini pour cette catégorie : le bureau national doit le fixer dans Paramètres. Les paiements peuvent déjà être enregistrés.</p>
   @else<p><b>{{ number_format($paye,0,',',' ') }} F</b> payés sur {{ number_format($cat,0,',',' ') }} F · reste <b style="color:var(--rouge)">{{ number_format($membre->resteAPayer($annee),0,',',' ') }} F</b></p>
   <div class="prog"><i style="width:{{ $cat>0 ? min(100, round($paye/$cat*100)) : 100 }}%"></i></div>@endif
   <table><tr><th>Date</th><th>Année</th><th>Mode</th><th>Montant</th><th>Reçu</th></tr>
   @forelse($membre->cotisations->sortByDesc('paye_le') as $c)<tr><td>{{ $c->paye_le->format('d/m/Y') }}</td><td>{{ $c->annee }}</td><td>{{ str_replace('_',' ',$c->mode) }}</td><td>{{ number_format($c->montant,0,',',' ') }} F</td><td>{{ $c->recu_numero }}</td></tr>
   @empty<tr><td colspan="5">Aucun paiement.</td></tr>@endforelse</table>
   <form method="post" action="{{ route('cotisations.store', $membre) }}" class="barre" style="margin-top:14px">@csrf
    <input type="number" name="annee" value="{{ $annee }}" style="width:90px"><input type="number" name="montant" min="1" placeholder="Montant (F)" required>
    <select name="mode"><option value="especes">Espèces</option><option value="mobile_money">Mobile money</option><option value="virement">Virement</option></select>
    <input type="date" name="paye_le" value="{{ now()->format('Y-m-d') }}"><button class="btn or">Enregistrer le paiement</button></form>
  </div>
 </div>
</div>
@endsection
