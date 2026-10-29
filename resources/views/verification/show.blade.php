<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Vérification · ADAK</title><link rel="stylesheet" href="/css/adak.css"></head>
<body><div class="connexion"><div class="bloc">
<div class="logo"></div>
@if(!$membre)
  <h1 style="color:var(--rouge)">Carte non reconnue</h1><p style="margin-top:8px;color:var(--gris)">Ce code ne correspond à aucun membre de l'ADAK.</p>
@else
  @php $valide = in_array($membre->statut, ['actif','sympathisant']) && ! $membre->carteBloquee() && $membre->carte_statut !== 'perdue'; @endphp
  <span class="badge {{ $valide ? 'ok' : 'non' }}" style="font-size:15px">{{ $valide ? 'Carte valide' : 'Carte non valide' }}</span>
  <h1 style="color:var(--bleu);font-size:22px;margin-top:14px">{{ $membre->nom_complet }}</h1>
  <p style="color:var(--gris);margin-top:4px">{{ $membre->numero }} · {{ $membre->categorie?->nom }}</p>
  @if($membre->estAJour() !== null)<p style="margin-top:10px">Cotisation {{ now()->year }} : <b>{{ $membre->estAJour() ? 'à jour' : 'en retard' }}</b></p>@endif
@endif
<p style="margin-top:18px;font-size:12px;color:var(--gris)">Association des Anciens Kemkogi · « Tous Solidaires »</p>
</div></div></body></html>
