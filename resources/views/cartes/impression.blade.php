<!doctype html><html lang="fr"><head><meta charset="utf-8"><title>Impression des cartes ADAK</title>
<style>
:root{--bleu:#2F5F8A;--bleu-logo:#4F7FA3;--or:#F5C531;--or-clair:#FBE38A;--creme:#FFFBEA;--rouge:#BC2206;--noir:#1B1B1B}
*{box-sizing:border-box;margin:0;padding:0;-webkit-print-color-adjust:exact;print-color-adjust:exact}
body{font-family:"Segoe UI",Arial,sans-serif;background:#e9edf1}
.aide{background:#fff;padding:12px 20px;display:flex;gap:14px;align-items:center;border-bottom:1px solid #ccd}
.aide button{background:var(--rouge);color:#fff;border:0;border-radius:8px;padding:9px 16px;font-weight:700;cursor:pointer}
.alerte{background:#fff1c9;padding:8px 20px;font-size:14px}
/* 1 mm = 10 px à l'écran ; à l'impression on utilise de vrais mm via zoom */
.carte{width:85.6mm;height:54mm;position:relative;overflow:hidden;border-radius:3mm;font-size:0;page-break-inside:avoid}
.recto{background:var(--creme)}
.recto::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 12% 55%,rgba(245,197,49,.55) 0,rgba(251,227,138,.35) 28%,rgba(255,251,234,0) 60%)}
.band{position:absolute;top:0;left:0;right:0;height:11.2mm;background:linear-gradient(100deg,var(--bleu),var(--bleu-logo));color:#fff;padding:2mm 4mm 0 25mm}
.band .s{font-size:3.8mm;font-weight:800;letter-spacing:.2em}.band .s span{color:var(--or);font-size:2.2mm;letter-spacing:.12em;margin-left:1mm}
.band .n{font-size:1.7mm;letter-spacing:.14em;text-transform:uppercase;margin-top:.4mm}
.filet{position:absolute;top:11.2mm;left:0;right:0;height:.9mm;background:linear-gradient(90deg,var(--or),var(--or-clair) 55%,var(--rouge))}
.lg{position:absolute;left:2.6mm;top:3.4mm;width:20.4mm;height:20.4mm;border-radius:50%;background:#fff url(/images/logo-adak.jpg) center/100% no-repeat;border:.3mm solid #fff;box-shadow:0 0 0 .5mm var(--or)}
.dev{position:absolute;left:2.6mm;top:26.2mm;width:20.4mm;text-align:center;font-style:italic;color:var(--bleu);font-size:1.7mm;font-weight:600}
.tc{position:absolute;left:2.6mm;top:30mm;width:20.4mm;text-align:center;background:var(--rouge);color:#fff;font-weight:800;letter-spacing:.14em;font-size:1.5mm;padding:.8mm 0;border-radius:.6mm}
.info{position:absolute;left:26.2mm;top:15mm;width:36mm}
.info .nm{font-size:3.2mm;font-weight:800;text-transform:uppercase;line-height:1.05;color:var(--noir)}
.info .pn{font-size:2.7mm;color:var(--bleu);font-weight:600;margin-top:.4mm}
.info .l{margin-top:1.6mm}.info .l b{display:block;font-size:1.1mm;letter-spacing:.14em;text-transform:uppercase;color:var(--bleu-logo)}.info .l span{font-size:2mm;font-weight:600}
.ph{position:absolute;right:4mm;top:15mm;width:19mm;height:23.6mm;border-radius:1.4mm;background:#dfe7ee center/cover;border:.5mm solid #fff;box-shadow:0 0 0 .4mm var(--bleu);overflow:hidden}
.num{position:absolute;left:26.2mm;bottom:3mm;font-size:2.5mm;font-weight:800;letter-spacing:.08em;color:var(--rouge)}
.val{position:absolute;right:4mm;bottom:3mm;text-align:right;font-size:1.2mm;letter-spacing:.14em;color:var(--bleu);text-transform:uppercase;font-weight:700}.val span{display:block;font-size:2.2mm;color:var(--noir)}
.pied{position:absolute;left:0;right:0;bottom:0;height:1.4mm;background:linear-gradient(90deg,var(--bleu) 0 33%,var(--or) 33% 66%,var(--rouge) 66%)}
.verso{background:linear-gradient(135deg,var(--bleu),#3E75A0 60%,var(--bleu-logo));color:#fff}
.haut{position:absolute;top:3.4mm;left:4.4mm;right:4.4mm;display:flex;align-items:center;gap:2mm}
.mini{width:9.2mm;height:9.2mm;border-radius:50%;background:#fff url(/images/logo-adak.jpg) center/100% no-repeat;border:.4mm solid var(--or);flex:none}
.haut h3{font-size:2.1mm;letter-spacing:.1em;text-transform:uppercase;line-height:1.25}.haut h3 small{display:block;color:var(--or);font-size:1.5mm;letter-spacing:.2em}
.qr{position:absolute;left:4.4mm;top:15.8mm;width:21mm;height:21mm;background:#fff;border-radius:1.4mm;padding:1.2mm;border:.5mm solid var(--or)}.qr img{width:100%;height:100%;display:block}
.qt{position:absolute;left:4.4mm;top:38mm;width:21mm;text-align:center;font-size:1.2mm;line-height:1.35}
.men{position:absolute;left:29mm;top:16mm;right:4.4mm;font-size:1.5mm;line-height:1.55}.men p+p{margin-top:1mm}.men strong{color:var(--or-clair)}
.sg{position:absolute;right:4.4mm;bottom:5mm;width:25mm;text-align:center;font-size:1.3mm}.sg .tr{border-bottom:.2mm solid var(--or);height:5mm;margin-bottom:.6mm}
.ct{position:absolute;left:4.4mm;bottom:2.6mm;font-size:1.3mm}
.verso .pied{background:linear-gradient(90deg,var(--or) 0 33%,#fff 33% 66%,var(--rouge) 66%)}
.page{display:flex;flex-direction:column;align-items:center;gap:6mm;padding:8mm}
/* Mode "carte" : une page par carte (recto puis verso) */
@page{size:85.6mm 54mm;margin:0}
body.carte .page{padding:0;gap:0}body.carte .carte{border-radius:0;page-break-after:always}
/* Mode "planche" : A4, 2 colonnes x 5 lignes, recto d'abord puis versos */
body.planche .page{display:block;padding:0}
body.planche .feuille{width:210mm;height:297mm;display:grid;grid-template-columns:repeat(2,85.6mm);grid-auto-rows:54mm;gap:2mm 4mm;justify-content:center;align-content:start;padding:8mm 0;page-break-after:always;background:#fff}
@media screen{body{min-width:900px}}
@media print{.aide,.alerte{display:none}body{background:#fff}body.planche .feuille{margin:0}.page{padding:0}}
@media print{body.planche{--x:0}}
</style></head>
<body class="{{ $mode }}">
@if($mode === 'planche')<style>@page{size:A4;margin:0}</style>@endif
<div class="aide"><button onclick="window.print()">Imprimer / Enregistrer en PDF</button>
<span>Dans la fenêtre d'impression : échelle <b>100 %</b>, marges <b>aucune</b>, graphiques d'arrière-plan <b>activés</b>.
Pensez ensuite à marquer les cartes comme « imprimées » dans la liste.</span></div>
@if($bloques->isNotEmpty())<div class="alerte">Cartes ignorées (cotisation en retard) : {{ $bloques->map->nom_complet->join(', ') }}</div>@endif

<div class="page">
@if($mode === 'carte')
  @foreach($membres as $m)@include('cartes._recto', ['m'=>$m])@include('cartes._verso', ['m'=>$m])@endforeach
@else
  @foreach($membres->chunk(10) as $groupe)
    <div class="feuille">@foreach($groupe as $m)@include('cartes._recto', ['m'=>$m])@endforeach</div>
    <div class="feuille">@foreach($groupe as $m)@include('cartes._verso', ['m'=>$m])@endforeach</div>
  @endforeach
@endif
</div></body></html>
