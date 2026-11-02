<?php
declare(strict_types=1);
require __DIR__.'/lib.php';
send_headers();
$IS_ADMIN=is_admin();
$DATA=load_data();
$CSRF=$IS_ADMIN?csrf():'';
?>
<!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="color-scheme" content="dark">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
<link rel="icon" type="image/png" href="logo.png">
<title>Chevette TV – Filmes, Séries e Canais</title>
<style>
:root{box-sizing:border-box;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px);color-scheme:dark;--bg:#080506;--card:#150d10;--line:#3b2a22;--tx:#fbf6ea;--mut:#b9a99a;--a:#e50914;--b:#7a1020;--gold:#f5c542;--g:linear-gradient(120deg,#e50914,#ff6a3d 40%,#f5c542)}
html{scroll-behavior:smooth;scroll-padding-top:70px}*{box-sizing:border-box}
body{margin:0;background:radial-gradient(900px 520px at 50% -12%,#f5c54226 0,transparent 62%),radial-gradient(1100px 600px at 100% 0,#5a0f1a 0,transparent 60%),radial-gradient(900px 500px at -10% 30%,#3a0a10 0,transparent 55%),var(--bg);color:var(--tx);font:16px/1.55 system-ui,-apple-system,Segoe UI,Roboto,sans-serif}
a{color:inherit;text-decoration:none}.w{max-width:1150px;margin:auto;padding:0 20px}
nav{position:sticky;top:env(safe-area-inset-top,0px);z-index:20;background:#080506d9;backdrop-filter:blur(14px);border-bottom:1px solid var(--line)}
nav .w{display:flex;align-items:center;justify-content:space-between;height:62px;gap:12px}
.logo{display:flex;align-items:center;gap:10px;font-weight:900;font-size:20px;letter-spacing:.5px}.logo img{height:38px;max-width:150px;object-fit:contain}
.logo b{background:var(--g);-webkit-background-clip:text;background-clip:text;color:transparent}
nav .l{display:flex;gap:22px;color:var(--mut);font-size:14px}nav .l a:hover{color:#fff}
.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 24px;border-radius:12px;font-weight:800;border:0;cursor:pointer;color:#fff;background:var(--g);box-shadow:0 8px 30px #e5091455;transition:.2s;font-size:15px}
.btn:hover{transform:translateY(-2px);box-shadow:0 12px 40px #e5091499}.btn.o{background:#ffffff10;box-shadow:none;border:1px solid var(--line)}.sm{padding:9px 16px;font-size:13px}
.hero{padding:70px 0 50px;display:grid;grid-template-columns:1.1fr .9fr;gap:40px;align-items:center}
.tag{display:inline-block;padding:6px 14px;border:1px solid #e5091477;border-radius:99px;color:#f5c542;font-size:13px;font-weight:700;background:#e5091414}
h1{font-size:clamp(36px,6vw,64px);line-height:1.04;margin:18px 0;font-weight:900;letter-spacing:-1.5px}h1 em{font-style:normal;background:var(--g);-webkit-background-clip:text;background-clip:text;color:transparent}
.hero p{color:var(--mut);font-size:18px;max-width:520px}.cta{display:flex;gap:12px;flex-wrap:wrap;margin:26px 0}
.stats{display:flex;gap:28px;flex-wrap:wrap}.stats b{display:block;font-size:26px}.stats span{color:var(--mut);font-size:13px}
.posters{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;transform:rotate(-4deg)}
.p{aspect-ratio:2/3;border-radius:16px;display:flex;align-items:flex-end;padding:12px;font-weight:800;font-size:13px;border:1px solid #ffffff22;box-shadow:0 18px 40px #0008;animation:fl 6s ease-in-out infinite}
.p:nth-child(2n){margin-top:30px;animation-delay:-2s}.p:nth-child(3n){animation-delay:-4s}
@keyframes fl{50%{translate:0 -10px}}
section{padding:60px 0}h2{font-size:clamp(28px,4vw,42px);margin:0 0 8px;letter-spacing:-1px;font-weight:900}.sub{color:var(--mut);margin:0 0 32px}
.grid{display:grid;gap:18px;grid-template-columns:repeat(auto-fit,minmax(240px,1fr))}
.card{background:linear-gradient(180deg,#1d1215,var(--card));border:1px solid var(--line);border-radius:20px;padding:24px;transition:.25s}.card:hover{border-color:#e5091488;transform:translateY(-4px)}
.ic{font-size:34px}.card h3{margin:10px 0 4px}.card p{color:var(--mut);margin:0;font-size:14px}
.apps{display:grid;grid-template-columns:1fr 1fr;gap:22px}
.app{position:relative;overflow:hidden;border-radius:26px;padding:34px;border:1px solid #ffffff2a;text-align:center}
.app.an{background:linear-gradient(145deg,#0a2540,#10151f 75%);border-color:#f5c54255}.app.pc{background:linear-gradient(145deg,#5a0f1a,#10151f 75%);border-color:#f5c54255}
.app .big{font-size:64px}.app h3{font-size:26px;margin:8px 0}.app p{color:#cfd3ee;margin:0 0 20px}
.chips{display:flex;flex-wrap:wrap;gap:10px}.chip{padding:10px 16px;border-radius:99px;background:#ffffff0d;border:1px solid var(--line);font-weight:600;font-size:14px}
.cp{display:flex;flex-direction:column;gap:10px;border:2px dashed #f5c542aa;background:#f5c54210;border-radius:18px;padding:20px}
.cp b{font-size:24px;letter-spacing:2px;color:#ffb347}.cp small{color:var(--mut)}
.card input,.card select{width:100%;padding:12px;border-radius:10px;border:1px solid var(--line);background:#0f090b;color:#fff;font:inherit;margin:4px 0}.card label{font-size:13px;color:var(--mut)}
.cuw{display:flex;gap:8px;margin-top:12px}.cuw input{margin:0;flex:1;min-width:0}.plan small{display:block;min-height:18px}.sv{color:#2dff9a;font-size:13px;min-height:20px}
.plan{position:relative}.plan.hot{border-color:var(--a);box-shadow:0 0 50px #e5091440}.plan .pr{font-size:38px;font-weight:900;margin:8px 0}
.plan ul{list-style:none;padding:0;margin:14px 0 20px;color:var(--mut)}.plan li:before{content:"✓ ";color:#f5c542;font-weight:900}
details{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:16px 20px;margin-bottom:10px}summary{cursor:pointer;font-weight:700}details p{color:var(--mut);margin:10px 0 0}
footer{border-top:1px solid var(--line);padding:30px 0;color:var(--mut);font-size:14px;text-align:center}
.wa{position:fixed;right:18px;bottom:calc(18px + env(safe-area-inset-bottom,0px));z-index:30;width:56px;height:56px;border-radius:50%;background:#25d366;display:grid;place-items:center;font-size:28px;box-shadow:0 8px 25px #25d36688}
#adm .fab{position:fixed;left:18px;bottom:calc(18px + env(safe-area-inset-bottom,0px));z-index:40}
#pn{position:fixed;inset:0 0 0 auto;width:min(440px,100%);background:#0d0809;z-index:50;overflow:auto;padding:calc(18px + env(safe-area-inset-top,0px)) 18px 90px;border-left:1px solid var(--line)}
#pn h4{margin:22px 0 8px;color:#ffb347}#pn label{display:block;font-size:12px;color:var(--mut);margin:8px 0 3px}
#pn input,#pn textarea{width:100%;padding:10px;border-radius:10px;border:1px solid var(--line);background:#1a1012;color:#fff;font:inherit}
.it{border:1px solid var(--line);border-radius:12px;padding:10px;margin-bottom:10px;background:#120b0d}
.bar{position:sticky;bottom:0;display:flex;gap:10px;background:#0d0809;padding:12px 0}
#toast{position:fixed;left:50%;bottom:90px;translate:-50% 0;background:#000;border:1px solid var(--a);padding:10px 18px;border-radius:10px;z-index:99;display:none}
@media(max-width:820px){.hero,.apps{grid-template-columns:1fr}nav .w{flex-wrap:wrap;height:auto;min-height:58px;padding-top:6px}nav .l{order:3;width:100%;overflow-x:auto;white-space:nowrap;padding-bottom:10px}.stats{gap:20px}}
@media(prefers-reduced-motion:reduce){.p{animation:none}}

h1,h2,h3,.pr{font-family:'Bebas Neue',Impact,'Arial Narrow',sans-serif;font-weight:400;letter-spacing:1.5px}
h1{font-size:clamp(48px,8.5vw,92px);line-height:.98;letter-spacing:1px}h2{font-size:clamp(36px,5vw,56px);letter-spacing:2px}.card h3{font-size:28px}.app h3{font-size:34px}.plan .pr{font-size:52px}
h2:after{content:"";display:block;width:70px;height:4px;margin-top:8px;border-radius:4px;background:var(--g)}
.logo img{height:46px;max-width:190px;filter:drop-shadow(0 2px 10px #0009)}.hl{width:min(300px,70%);display:block;margin:0 0 14px;filter:drop-shadow(0 10px 30px #e5091466)}
.strip{height:22px;background:repeating-linear-gradient(90deg,#f5c542 0 10px,transparent 10px 22px) 0 4px/100% 5px no-repeat,repeating-linear-gradient(90deg,#f5c542 0 10px,transparent 10px 22px) 0 13px/100% 5px no-repeat,#000;opacity:.85}
.p{border:2px solid #f5c54277}.btn{background:linear-gradient(120deg,#e50914,#b20710);box-shadow:0 8px 30px #e5091466;border:1px solid #f5c54255}.btn.o{background:#ffffff0d;border:1px solid var(--line)}
.plan.hot{border-color:var(--gold);box-shadow:0 0 60px #f5c54233}.card:hover{border-color:#f5c54299}
.fl{width:150px;margin:0 auto 10px;display:block}
.cat{padding:0;overflow:hidden}.cat .art{aspect-ratio:16/9;overflow:hidden;border-bottom:1px solid var(--line)}.cat .art svg{width:100%;height:100%;display:block;transition:transform .5s}.cat:hover .art svg{transform:scale(1.06)}.cat h3,.cat p{padding:0 22px}.cat h3{margin:16px 0 4px}.cat p{padding-bottom:24px}
.scr{position:relative;display:grid;place-items:center;padding:30px 12px}
.tv{position:relative;width:100%;max-width:520px;aspect-ratio:16/10;border-radius:22px;border:8px solid #1c1214;outline:2px solid #f5c54255;background:radial-gradient(circle at 50% 35%,#7a1020,#12060a 75%);display:grid;place-items:center;box-shadow:0 30px 80px #e5091440}
.tv img{width:62%;filter:drop-shadow(0 8px 24px #000a)}
.pl{position:absolute;right:18px;bottom:36px;width:46px;height:46px;border-radius:50%;background:#e50914;display:grid;place-items:center;animation:pu 2s infinite}
.pg{position:absolute;left:18px;right:18px;bottom:16px;height:5px;background:#ffffff22;border-radius:5px;overflow:hidden}.pg i{display:block;width:38%;height:100%;background:var(--g)}
.fb{position:absolute;padding:9px 14px;border-radius:12px;background:#150d10ee;border:1px solid #f5c54266;font-size:13px;font-weight:700;box-shadow:0 10px 30px #0008;animation:fl 6s ease-in-out infinite}
.f1{left:0;top:8px}.f2{right:0;top:44%;animation-delay:-2s}.f3{left:18px;bottom:2px;animation-delay:-4s}
@keyframes pu{50%{box-shadow:0 0 0 14px #e5091400}}main{overflow-x:clip}
</style></head><body>
<div id="root"></div><div id="adm"></div><div id="toast"></div>
<script id="state" type="application/json"><?= json_encode($DATA,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP) ?></script>
<script>window.ADMIN=<?= $IS_ADMIN?'true':'false' ?>;window.CSRF=<?= json_encode($CSRF) ?>;</script>
<script>
const LOGO='logo.png';
let S=JSON.parse(document.getElementById('state').textContent);
const $=s=>document.querySelector(s),esc=t=>String(t??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const LS=()=>/^https?:\/\//i.test(S.logo)?S.logo:LOGO;
const ART={"movie": "<svg viewBox=\"0 0 320 180\" xmlns=\"http://www.w3.org/2000/svg\" role=\"img\" aria-label=\"Filmes\" preserveAspectRatio=\"xMidYMid slice\"><defs><radialGradient id=\"g1\" cx=\"50%\" cy=\"30%\" r=\"85%\"><stop offset=\"0\" stop-color=\"#8a1224\"/><stop offset=\"1\" stop-color=\"#12060a\"/></radialGradient></defs><rect width=\"320\" height=\"180\" fill=\"url(#g1)\"/><g transform=\"rotate(-8 160 100)\"><rect x=\"82\" y=\"78\" width=\"156\" height=\"84\" rx=\"6\" fill=\"#1b1b22\" stroke=\"#f5c542\" stroke-width=\"3\"/><rect x=\"82\" y=\"50\" width=\"156\" height=\"28\" rx=\"4\" fill=\"#f5c542\"/><path d=\"M98 50l18 28M128 50l18 28M158 50l18 28M188 50l18 28M218 50l18 28\" stroke=\"#12060a\" stroke-width=\"9\"/><rect x=\"98\" y=\"95\" width=\"122\" height=\"6\" fill=\"#f5c54266\"/><rect x=\"98\" y=\"110\" width=\"84\" height=\"6\" fill=\"#f5c54244\"/><rect x=\"98\" y=\"125\" width=\"100\" height=\"6\" fill=\"#f5c54233\"/></g><g stroke=\"#f5c542\" stroke-width=\"3\" fill=\"none\"><circle cx=\"274\" cy=\"46\" r=\"24\"/></g><g fill=\"#f5c542\"><circle cx=\"274\" cy=\"46\" r=\"5\"/><circle cx=\"274\" cy=\"28\" r=\"5\"/><circle cx=\"292\" cy=\"46\" r=\"5\"/><circle cx=\"274\" cy=\"64\" r=\"5\"/><circle cx=\"256\" cy=\"46\" r=\"5\"/><circle cx=\"40\" cy=\"150\" r=\"3\"/><circle cx=\"52\" cy=\"30\" r=\"2.5\"/><circle cx=\"300\" cy=\"150\" r=\"2.5\"/></g></svg>", "series": "<svg viewBox=\"0 0 320 180\" xmlns=\"http://www.w3.org/2000/svg\" role=\"img\" aria-label=\"Séries\" preserveAspectRatio=\"xMidYMid slice\"><defs><radialGradient id=\"g2\" cx=\"50%\" cy=\"30%\" r=\"85%\"><stop offset=\"0\" stop-color=\"#0f3a63\"/><stop offset=\"1\" stop-color=\"#060a12\"/></radialGradient></defs><rect width=\"320\" height=\"180\" fill=\"url(#g2)\"/><rect x=\"58\" y=\"26\" width=\"204\" height=\"108\" rx=\"10\" fill=\"#10233a\" stroke=\"#f5c54244\" transform=\"translate(-12 -8)\"/><rect x=\"58\" y=\"26\" width=\"204\" height=\"108\" rx=\"10\" fill=\"#14304f\" stroke=\"#f5c54277\" transform=\"translate(-4 0)\"/><rect x=\"58\" y=\"26\" width=\"204\" height=\"108\" rx=\"10\" fill=\"#e50914\" stroke=\"#f5c542\" stroke-width=\"3\" transform=\"translate(6 12)\"/><circle cx=\"168\" cy=\"92\" r=\"30\" fill=\"#00000055\"/><path d=\"M158 74l30 18-30 18z\" fill=\"#fff\"/><g fill=\"#f5c542\"><rect x=\"64\" y=\"154\" width=\"40\" height=\"8\" rx=\"4\"/><rect x=\"112\" y=\"154\" width=\"40\" height=\"8\" rx=\"4\" opacity=\".6\"/><rect x=\"160\" y=\"154\" width=\"40\" height=\"8\" rx=\"4\" opacity=\".4\"/><rect x=\"208\" y=\"154\" width=\"40\" height=\"8\" rx=\"4\" opacity=\".25\"/></g></svg>", "live": "<svg viewBox=\"0 0 320 180\" xmlns=\"http://www.w3.org/2000/svg\" role=\"img\" aria-label=\"Canais ao vivo\" preserveAspectRatio=\"xMidYMid slice\"><defs><radialGradient id=\"g3\" cx=\"50%\" cy=\"30%\" r=\"85%\"><stop offset=\"0\" stop-color=\"#6b0f1a\"/><stop offset=\"1\" stop-color=\"#0b0506\"/></radialGradient></defs><rect width=\"320\" height=\"180\" fill=\"url(#g3)\"/><g stroke=\"#f5c542\" stroke-width=\"3\" stroke-linecap=\"round\"><path d=\"M160 46l-34-28M160 46l34-28\"/></g><rect x=\"78\" y=\"46\" width=\"164\" height=\"104\" rx=\"14\" fill=\"#1a1a22\" stroke=\"#f5c542\" stroke-width=\"3\"/><rect x=\"90\" y=\"58\" width=\"140\" height=\"80\" rx=\"8\" fill=\"#0a2540\"/><g opacity=\".9\"><rect x=\"90\" y=\"58\" width=\"20\" height=\"80\" fill=\"#e50914\"/><rect x=\"110\" y=\"58\" width=\"20\" height=\"80\" fill=\"#f5c542\"/><rect x=\"130\" y=\"58\" width=\"20\" height=\"80\" fill=\"#2dd4bf\"/><rect x=\"150\" y=\"58\" width=\"20\" height=\"80\" fill=\"#3b82f6\"/><rect x=\"170\" y=\"58\" width=\"20\" height=\"80\" fill=\"#a855f7\"/><rect x=\"190\" y=\"58\" width=\"20\" height=\"80\" fill=\"#fff\"/><rect x=\"210\" y=\"58\" width=\"20\" height=\"80\" fill=\"#111\"/></g><rect x=\"104\" y=\"28\" width=\"84\" height=\"24\" rx=\"12\" fill=\"#e50914\" stroke=\"#fff\" stroke-width=\"2\" transform=\"translate(40 76)\"/><circle cx=\"160\" cy=\"116\" r=\"6\" fill=\"#fff\"/><text x=\"215\" y=\"124\" text-anchor=\"middle\" font-family=\"Bebas Neue,Impact,sans-serif\" font-size=\"20\" fill=\"#fff\" letter-spacing=\"1\">AO VIVO</text></svg>", "sports": "<svg viewBox=\"0 0 320 180\" xmlns=\"http://www.w3.org/2000/svg\" role=\"img\" aria-label=\"Esportes\" preserveAspectRatio=\"xMidYMid slice\"><defs><radialGradient id=\"g4\" cx=\"50%\" cy=\"30%\" r=\"85%\"><stop offset=\"0\" stop-color=\"#1d7a3a\"/><stop offset=\"1\" stop-color=\"#06150b\"/></radialGradient></defs><rect width=\"320\" height=\"180\" fill=\"url(#g4)\"/><g stroke=\"#ffffff55\" stroke-width=\"2\" fill=\"none\"><rect x=\"14\" y=\"14\" width=\"292\" height=\"152\" rx=\"4\"/><path d=\"M160 14v152\"/><circle cx=\"160\" cy=\"90\" r=\"32\"/></g><ellipse cx=\"160\" cy=\"146\" rx=\"46\" ry=\"7\" fill=\"#00000066\"/><circle cx=\"160\" cy=\"92\" r=\"46\" fill=\"#fff\" stroke=\"#111\" stroke-width=\"3\"/><polygon points=\"160,76 175.2,87 169.4,104.9 150.6,104.9 144.8,87\" fill=\"#111\"/><path d=\"M160 76L160 52M175.2 87L198 79.6M169.4 104.9L183.5 124.4M150.6 104.9L136.5 124.4M144.8 87L122 79.6\" stroke=\"#111\" stroke-width=\"3\" fill=\"none\"/><g fill=\"#111\"><circle cx=\"160\" cy=\"56\" r=\"7\"/><circle cx=\"196\" cy=\"84\" r=\"7\"/><circle cx=\"122\" cy=\"84\" r=\"7\"/><circle cx=\"182\" cy=\"128\" r=\"7\"/><circle cx=\"138\" cy=\"128\" r=\"7\"/></g></svg>", "kids": "<svg viewBox=\"0 0 320 180\" xmlns=\"http://www.w3.org/2000/svg\" role=\"img\" aria-label=\"Infantil\" preserveAspectRatio=\"xMidYMid slice\"><defs><radialGradient id=\"g5\" cx=\"50%\" cy=\"30%\" r=\"85%\"><stop offset=\"0\" stop-color=\"#7a3cff\"/><stop offset=\"1\" stop-color=\"#1a0b3d\"/></radialGradient></defs><rect width=\"320\" height=\"180\" fill=\"url(#g5)\"/><path d=\"M68 138a92 92 0 0 1 184 0\" stroke=\"#e50914\" stroke-width=\"12\" fill=\"none\"/><path d=\"M80 138a80 80 0 0 1 160 0\" stroke=\"#ff9f1c\" stroke-width=\"12\" fill=\"none\"/><path d=\"M92 138a68 68 0 0 1 136 0\" stroke=\"#f5c542\" stroke-width=\"12\" fill=\"none\"/><path d=\"M104 138a56 56 0 0 1 112 0\" stroke=\"#2dd4bf\" stroke-width=\"12\" fill=\"none\"/><path d=\"M116 138a44 44 0 0 1 88 0\" stroke=\"#3b82f6\" stroke-width=\"12\" fill=\"none\"/><g fill=\"#fff\"><ellipse cx=\"78\" cy=\"142\" rx=\"26\" ry=\"12\"/><circle cx=\"68\" cy=\"134\" r=\"11\"/><circle cx=\"86\" cy=\"132\" r=\"13\"/></g><g fill=\"#fff\"><ellipse cx=\"242\" cy=\"142\" rx=\"26\" ry=\"12\"/><circle cx=\"232\" cy=\"134\" r=\"11\"/><circle cx=\"250\" cy=\"132\" r=\"13\"/></g><polygon points=\"50.0,28.0 53.2,35.6 61.4,36.3 55.1,41.7 57.1,49.7 50.0,45.4 42.9,49.7 44.9,41.7 38.6,36.3 46.8,35.6\" fill=\"#f5c542\"/><polygon points=\"270.0,22.0 273.7,30.9 283.3,31.7 276.0,37.9 278.2,47.3 270.0,42.3 261.8,47.3 264.0,37.9 256.7,31.7 266.3,30.9\" fill=\"#f5c542\"/><polygon points=\"236.0,62.0 238.1,67.1 243.6,67.5 239.4,71.1 240.7,76.5 236.0,73.6 231.3,76.5 232.6,71.1 228.4,67.5 233.9,67.1\" fill=\"#f5c542\"/><polygon points=\"96.0,57.0 97.9,61.5 102.7,61.8 99.0,65.0 100.1,69.7 96.0,67.2 91.9,69.7 93.0,65.0 89.3,61.8 94.1,61.5\" fill=\"#f5c542\"/></svg>", "anime": "<svg viewBox=\"0 0 320 180\" xmlns=\"http://www.w3.org/2000/svg\" role=\"img\" aria-label=\"Animes\" preserveAspectRatio=\"xMidYMid slice\"><defs><radialGradient id=\"g6\" cx=\"50%\" cy=\"30%\" r=\"85%\"><stop offset=\"0\" stop-color=\"#c2185b\"/><stop offset=\"1\" stop-color=\"#1e0a3c\"/></radialGradient></defs><rect width=\"320\" height=\"180\" fill=\"url(#g6)\"/><path d=\"M160 90L420 90M160 90L413 148M160 90L394 203M160 90L363 252M160 90L322 293M160 90L273 324M160 90L218 343M160 90L160 350M160 90L102 343M160 90L47 324M160 90L-2 293M160 90L-43 252M160 90L-74 203M160 90L-93 148M160 90L-100 90M160 90L-93 32M160 90L-74 -23M160 90L-43 -72M160 90L-2 -113M160 90L47 -144M160 90L102 -163M160 90L160 -170M160 90L218 -163M160 90L273 -144M160 90L322 -113M160 90L363 -72M160 90L394 -23M160 90L413 32\" stroke=\"#fff\" stroke-opacity=\".16\" stroke-width=\"3\"/><defs><linearGradient id=\"ir\" x1=\"0\" y1=\"0\" x2=\"0\" y2=\"1\"><stop offset=\"0\" stop-color=\"#f5c542\"/><stop offset=\"1\" stop-color=\"#e50914\"/></linearGradient></defs><ellipse cx=\"112\" cy=\"96\" rx=\"30\" ry=\"40\" fill=\"#14081f\"/><ellipse cx=\"112\" cy=\"98\" rx=\"23\" ry=\"32\" fill=\"url(#ir)\"/><ellipse cx=\"112\" cy=\"102\" rx=\"10\" ry=\"15\" fill=\"#14081f\"/><circle cx=\"103\" cy=\"84\" r=\"9\" fill=\"#fff\"/><circle cx=\"121\" cy=\"115\" r=\"4.5\" fill=\"#fff\"/><ellipse cx=\"208\" cy=\"96\" rx=\"30\" ry=\"40\" fill=\"#14081f\"/><ellipse cx=\"208\" cy=\"98\" rx=\"23\" ry=\"32\" fill=\"url(#ir)\"/><ellipse cx=\"208\" cy=\"102\" rx=\"10\" ry=\"15\" fill=\"#14081f\"/><circle cx=\"199\" cy=\"84\" r=\"9\" fill=\"#fff\"/><circle cx=\"217\" cy=\"115\" r=\"4.5\" fill=\"#fff\"/><path d=\"M80 52Q112 36 142 54M178 54Q208 36 240 52\" stroke=\"#14081f\" stroke-width=\"6\" fill=\"none\" stroke-linecap=\"round\"/><path d=\"M52 26Q52 40 66 40Q52 40 52 54Q52 40 38 40Q52 40 52 26z\" fill=\"#fff\"/><path d=\"M272 126Q272 138 284 138Q272 138 272 150Q272 138 260 138Q272 138 272 126z\" fill=\"#fff\"/><path d=\"M274 32Q274 40 282 40Q274 40 274 48Q274 40 266 40Q274 40 274 32z\" fill=\"#fff\"/><path d=\"M48 134Q48 142 56 142Q48 142 48 150Q48 142 40 142Q48 142 48 134z\" fill=\"#fff\"/></svg>"};
const art=k=>ART[k]||'';
const url=u=>/^https?:\/\//i.test(u)?esc(u):'#';
const cols=['#e50914,#2a0509','#f5c542,#7a1020','#0a2540,#e50914','#7a1020,#f5c542','#e50914,#0a2540','#f5c542,#3a0a10'];
function toast(m){const t=$('#toast');t.textContent=m;t.style.display='block';setTimeout(()=>t.style.display='none',2500)}
function render(){
const wa=`https://wa.me/${esc(S.wa.replace(/\D/g,''))}`;
$('#root').innerHTML=`
<nav><div class="w"><a class="logo" href="#top"><img src="${esc(LS())}" alt="${esc(S.name)}"></a>
<div class="l"><a href="#apps">Apps</a><a href="#conteudo">Conteúdo</a><a href="#devices">Aparelhos</a><a href="#planos">Planos</a><a href="#teste">Teste grátis</a><a href="#faq">Dúvidas</a></div>
<a class="btn sm" href="#apps">Baixar app</a></div></nav>
<main id="top"><div class="w hero"><div><span class="tag">🔥 Tem cupom? Aplique ao assinar e ganhe desconto</span>
<h1>${esc(S.title).replace(/(séries|canais|filmes)/i,'<em>$1</em>')}</h1><p>${esc(S.sub)}</p>
<div class="cta"><a class="btn" href="#apps">⬇ Baixar agora</a><a class="btn o" href="#teste">🎁 Pedir teste grátis</a></div>
<div class="stats"><div><b>2 telas</b><span>simultâneas</span></div><div><b>${new Set(S.devices.map(d=>d.g)).size} tipos</b><span>de aparelho</span></div><div><b>Teste</b><span>grátis</span></div></div></div>
<div class="scr"><div class="tv"><img src="${esc(LS())}" alt="${esc(S.name)}"><div class="pl">▶</div><div class="pg"><i></i></div></div><span class="fb f1">📱 Android · 💻 PC · 📺 TV</span><span class="fb f2">👥 2 telas simultâneas</span><span class="fb f3">🔴 Ao vivo</span></div></div>
<div class="strip"></div>
<section id="apps"><div class="w"><h2>Apps disponíveis</h2><p class="sub">Escolha sua plataforma, baixe e instale. É rápido e fácil.</p>
<div class="apps"><div class="app an"><div class="big">🤖</div><h3>App Android</h3><p>Celular, tablet, TV Box e Android TV.</p><a class="btn" href="${url(S.android)}" target="_blank" rel="noopener noreferrer">⬇ Baixar para Android</a></div>
<div class="app pc"><div class="big">💻</div><h3>App para PC</h3><p>Windows – assista na tela grande do computador.</p><a class="btn" href="${url(S.pc)}" target="_blank" rel="noopener noreferrer">⬇ Baixar para PC</a></div></div></div></section>
<section id="conteudo"><div class="w"><h2>Tudo em um só lugar</h2><p class="sub">Filmes, séries e canais para toda a família.</p>
<div class="grid">${S.cats.map(c=>`<div class="card cat"><div class="art">${art(c.a)||`<div class="ic" style="padding:30px 22px 0">${esc(c.i)}</div>`}</div><h3>${esc(c.i)} ${esc(c.n)}</h3><p>${esc(c.d)}</p></div>`).join('')}</div></div></section>
<section id="como"><div class="w"><h2>Como funciona</h2><p class="sub">Em 3 passos você já está assistindo.</p>
<div class="grid"><div class="card"><div class="ic">1️⃣</div><h3>Escolha seu plano</h3><p>Mensal, trimestral, semestral ou anual. Tem cupom? Aplique antes de assinar.</p></div><div class="card"><div class="ic">2️⃣</div><h3>Receba seu acesso</h3><p>Ao clicar em assinar, você fala direto comigo no WhatsApp e recebe seu acesso.</p></div><div class="card"><div class="ic">3️⃣</div><h3>Baixe e assista</h3><p>Instale o app no seu aparelho, entre com seu acesso e aproveite.</p></div></div></div></section>
<section id="devices"><div class="w"><h2>Aparelhos disponíveis</h2><p class="sub">Funciona nos aparelhos que você já tem em casa.</p>
<div class="grid">${[...new Set(S.devices.map(d=>d.g))].map(g=>`<div class="card"><h3>${esc(g)}</h3><div class="chips" style="margin-top:14px">${S.devices.filter(d=>d.g===g).map(d=>`<span class="chip">${esc(d.i)} ${esc(d.n)}</span>`).join('')}</div></div>`).join('')}</div>
<p class="sub" style="margin:22px 0 0">Não viu seu aparelho na lista? <a href="${wa}" target="_blank" rel="noopener noreferrer" style="color:var(--gold);font-weight:700">Chame no WhatsApp</a> que a gente te ajuda.</p></div></section>
<section id="planos"><div class="w"><h2>Escolha seu plano</h2><p class="sub">Todos os planos com 2 telas simultâneas. Quanto maior o plano, maior a economia.</p>
<div class="grid">${S.plans.map((p,i)=>`<div class="card plan ${i==2?'hot':''}">${i==2?'<span class="tag">⭐ Melhor custo-benefício</span>':''}<h3>${esc(p.n)}</h3><small style="color:var(--mut)">${esc(p.t)}</small><div class="pr" id="pr${i}">R$ ${esc(p.p)}</div><div class="sv" id="sv${i}"></div>
<ul>${((S.perks||'')+','+(p.f||'')).split(',').filter(x=>x.trim()).map(x=>`<li>${esc(x.trim())}</li>`).join('')}</ul><button class="btn" style="width:100%;justify-content:center" data-sub="${i}">Assinar no WhatsApp</button>
<div class="cuw"><input id="cu${i}" placeholder="🎟 Tem cupom? Digite aqui" maxlength="30" autocomplete="off"><button class="btn sm o" data-ap="${i}">Aplicar</button></div><small id="cm${i}"></small></div>`).join('')}</div></div></section>
<section id="teste"><div class="w"><div class="card" style="max-width:640px;margin:auto"><h2>🎁 Peça seu teste grátis</h2><p class="sub">${esc(S.testo)}. Preencha e receba no WhatsApp.</p>
<label>Seu nome</label><input id="tn" maxlength="60"><label>Seu aparelho</label><select id="td">${S.devices.map(d=>`<option>${esc(d.n)}</option>`).join('')}</select>
<button class="btn" style="width:100%;justify-content:center;margin-top:14px" id="tb">Solicitar teste no WhatsApp</button></div></div></section>
<section id="faq"><div class="w"><h2>Dúvidas frequentes</h2><p class="sub">&nbsp;</p>
<details><summary>Como instalo o app?</summary><p>Baixe o arquivo no botão acima, abra e permita a instalação de fontes desconhecidas se o Android pedir.</p></details>
<details><summary>Funciona em quais aparelhos?</summary><p>Veja a seção “Aparelhos disponíveis”. Se tiver dúvida, chame no WhatsApp.</p></details>
<details><summary>Como uso um cupom?</summary><p>Digite o código no campo embaixo do plano e clique em Aplicar. O preço já aparece com desconto e vai junto na mensagem do WhatsApp.</p></details>
<details><summary>Posso assistir em mais de um aparelho?</summary><p>Sim! Todos os planos têm 2 telas simultâneas.</p></details>
<details><summary>Como peço um teste grátis?</summary><p>Preencha o formulário da seção “Teste grátis” e envie. Você vai direto para o meu WhatsApp.</p></details>
<details><summary>Como recebo meu acesso?</summary><p>Depois de assinar pelo WhatsApp, eu envio seu acesso e um passo a passo de instalação.</p></details></div></section></main>
<div class="strip"></div><footer><div class="w"><img class="fl" src="${esc(LS())}" alt="">© ${new Date().getFullYear()} ${esc(S.name)} · Todos os direitos reservados</div></footer>
<a class="wa" href="${wa}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">💬</a>`;
const num=s=>parseFloat(String(s).replace(/\./g,'').replace(',','.'))||0,br=n=>n.toFixed(2).replace('.',',');
const find=i=>{const v=($('#cu'+i).value||'').trim().toUpperCase();return v?S.coupons.find(c=>String(c.code).trim().toUpperCase()===v):null};
document.querySelectorAll('[data-ap]').forEach(b=>b.onclick=()=>{const i=b.dataset.ap,p=S.plans[i],c=find(i),m=$('#cm'+i);
if(!c){$('#pr'+i).textContent='R$ '+p.p;$('#sv'+i).textContent='';m.textContent=$('#cu'+i).value?'❌ Cupom inválido':'';return}
$('#pr'+i).textContent='R$ '+br(num(p.p)*(1-num(c.pct)/100));$('#sv'+i).textContent='De R$ '+p.p+' · '+c.off;m.textContent='✅ Cupom '+c.code+' aplicado!'});
document.querySelectorAll('[data-sub]').forEach(b=>b.onclick=()=>{const i=b.dataset.sub,p=S.plans[i],c=find(i);let t='Olá! Quero assinar o plano *'+p.n+'* ('+p.t+') por R$ '+(c?br(num(p.p)*(1-num(c.pct)/100)):p.p);if(c)t+='\nCupom: '+c.code;window.open(wa+'?text='+encodeURIComponent(t),'_blank','noopener')});
$('#tb').onclick=()=>{const t='Olá! Quero um *teste grátis*.\nNome: '+($('#tn').value||'-')+'\nAparelho: '+$('#td').value;window.open(wa+'?text='+encodeURIComponent(t),'_blank','noopener')}}
render();
/* ---------- Painel ADM (só dono/editor) ---------- */
const G=[['name','Nome do site'],['logo','URL de outra logo (opcional, https://…)'],['wa','WhatsApp (DDI+DDD+número)'],['title','Título principal'],['sub','Subtítulo'],['android','Link do app Android (Drive)'],['pc','Link do app PC (Drive)'],['perks','Benefícios de todos os planos (vírgula)'],['testo','Texto do teste grátis']];
const L={devices:['Aparelhos',[['g','Categoria (agrupa o aparelho)'],['i','Emoji'],['n','Nome']]],coupons:['Cupons (código secreto, só vale se digitado)',[['code','Código'],['off','Texto (ex: 10% OFF)'],['pct','% de desconto (só número)'],['d','Anotação']]],plans:['Planos',[['n','Nome'],['t','Duração'],['p','Preço (ex: 24,90)'],['f','Benefícios extras (vírgula)']]],cats:['Categorias de conteúdo',[['a','Arte (movie, series, live, sports, kids, anime)'],['i','Emoji'],['n','Nome'],['d','Descrição']]]};
function panel(){
let h='<h3 style="margin:0">⚙ Painel ADM</h3><h4>Geral</h4>'+G.map(([k,l])=>`<label>${l}</label><input data-g="${k}" value="${esc(S[k])}">`).join('');
for(const k in L){h+=`<h4>${L[k][0]}</h4>`+S[k].map((it,i)=>`<div class="it">${L[k][1].map(([f,l])=>`<label>${l}</label><input data-l="${k}" data-i="${i}" data-f="${f}" value="${esc(it[f])}">`).join('')}<button class="btn sm o" style="margin-top:8px" data-del="${k}:${i}">Remover</button></div>`).join('')+`<button class="btn sm o" data-add="${k}">+ Adicionar</button>`}
h+='<div class="bar"><button class="btn" id="sv">💾 Salvar</button><button class="btn o" id="cl">Fechar</button><a class="btn o" href="logout.php">Sair</a></div>';
const p=$('#pn')||Object.assign(document.createElement('div'),{id:'pn'});p.innerHTML=h;if(!p.parentNode)$('#adm').appendChild(p);
p.oninput=e=>{const d=e.target.dataset;if(d.g)S[d.g]=e.target.value;else if(d.l)S[d.l][d.i][d.f]=e.target.value;render()};
p.onclick=e=>{const d=e.target.dataset;if(d.add){const o={};L[d.add][1].forEach(([f])=>o[f]='');S[d.add].push(o);panel();render()}
if(d.del){const[k,i]=d.del.split(':');S[k].splice(i,1);panel();render()}
if(e.target.id==='cl')p.remove();if(e.target.id==='sv')save()}}
async function save(){try{toast('Salvando…');const r=await fetch('save.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-CSRF':window.CSRF},body:JSON.stringify(S)});const d=await r.json().catch(()=>({}));
if(!r.ok||!d.ok)return toast('Erro: '+(d.error||r.status));toast('✅ Salvo! Atualizando…');setTimeout(()=>location.reload(),900)}catch(e){toast('Erro de conexão ao salvar')}}
if(window.ADMIN){const b=document.createElement('button');b.className='btn fab';b.textContent='⚙ ADM';b.onclick=panel;$('#adm').appendChild(b)}
</script></body></html>
