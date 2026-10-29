<div class="carte recto">
 <div class="band"><div class="s">A.D.A.K.<span>TCHAD</span></div><div class="n">Association des Anciens Kemkogi</div></div><div class="filet"></div>
 <div class="lg"></div><div class="dev">« Tous Solidaires »</div><div class="tc">CARTE DE MEMBRE</div>
 <div class="info"><div class="nm">{{ $m->nom }}</div><div class="pn">{{ $m->prenom }}</div>
  <div class="l"><b>Catégorie</b><span>{{ $m->categorie?->nom ?? '—' }}</span></div><div class="l"><b>Ville</b><span>{{ $m->ville ?: '—' }}</span></div></div>
 <div class="ph" @if($m->photo) style="background-image:url('{{ asset('storage/'.$m->photo) }}')" @endif></div>
 <div class="num">N° {{ $m->numero }}@if($m->carte_duplicata) · D{{ $m->carte_duplicata }}@endif</div>
 <div class="val">Valable jusqu'au<span>31/12/{{ $validite }}</span></div><div class="pied"></div>
</div>
