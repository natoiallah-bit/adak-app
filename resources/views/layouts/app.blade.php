<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('titre', 'ADAK') · ADAK</title><link rel="stylesheet" href="/css/adak.css"></head>
<body><div class="app">
<nav class="menu">
  <div class="marque"><div class="logo"></div><div><b>ADAK</b><small>TOUS SOLIDAIRES</small></div></div>
  <a href="{{ route('accueil') }}" @class(['on' => request()->routeIs('accueil')])>Tableau de bord</a>
  <a href="{{ route('membres.index') }}" @class(['on' => request()->routeIs('membres.*')])>Membres</a>
  <a href="{{ route('cartes.index') }}" @class(['on' => request()->routeIs('cartes.*')])>Cartes de membre</a>
  <a href="{{ route('parametres.index') }}" @class(['on' => request()->routeIs('parametres.*')])>Paramètres</a>
  <form method="post" action="{{ route('deconnexion') }}">@csrf<button class="btn gris" style="width:100%">Déconnexion</button></form>
</nav>
<main class="corps">
  <div class="haut"><h1>@yield('titre')</h1><div>@yield('actions')</div></div>
  <div class="contenu">
    @if(session('ok'))<div class="alerte">{{ session('ok') }}</div>@endif
    @if($errors->any())<div class="erreur">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
    @yield('contenu')
  </div>
</main></div></body></html>
