<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Connexion · ADAK</title><link rel="stylesheet" href="/css/adak.css"></head>
<body><div class="connexion"><div class="bloc">
  <div class="logo"></div>
  <h1 style="color:var(--bleu);font-size:20px">Association des Anciens Kemkogi</h1>
  <p style="color:var(--gris);margin:4px 0 18px">« Tous Solidaires »</p>
  @if($errors->any())<div class="erreur">{{ $errors->first() }}</div>@endif
  <form method="post" action="{{ route('connexion') }}">@csrf
    <input type="email" name="email" placeholder="Adresse e-mail" value="{{ old('email') }}" required autofocus>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button class="btn" style="width:100%">Se connecter</button>
  </form>
</div></div></body></html>
