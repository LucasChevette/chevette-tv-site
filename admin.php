<?php
declare(strict_types=1);
require __DIR__.'/lib.php';
send_headers();
secure_session();
if (is_admin()) { header('Location: index.php'); exit; }
$auth = read_store('auth', []);
$setup = empty($auth['hash']);
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals(csrf(), (string)($_POST['csrf'] ?? ''))) { http_response_code(400); exit('Sessão expirada. Volte e recarregue a página.'); }
  $lock = throttle_lock();
  if ($lock > 0) {
    $msg = 'Muitas tentativas. Aguarde '.ceil($lock/60).' min.';
  } elseif ($setup) {
    $p1 = (string)($_POST['p1'] ?? ''); $p2 = (string)($_POST['p2'] ?? '');
    if (!hash_equals(SETUP_KEY, (string)($_POST['key'] ?? ''))) { throttle_fail(); $msg = 'Chave de instalação incorreta.'; }
    elseif (strlen($p1) < 10) $msg = 'A senha precisa ter pelo menos 10 caracteres.';
    elseif ($p1 !== $p2) $msg = 'As senhas não são iguais.';
    elseif (!write_store('auth', ['hash'=>password_hash($p1, PASSWORD_DEFAULT)])) $msg = 'Não consegui gravar na pasta _data. Veja a permissão da pasta.';
    else { throttle_clear(); login_ok(); header('Location: index.php'); exit; }
  } else {
    if (password_verify((string)($_POST['password'] ?? ''), (string)$auth['hash'])) { throttle_clear(); login_ok(); header('Location: index.php'); exit; }
    throttle_fail(); usleep(500000); $msg = 'Senha incorreta.';
  }
}
$e = fn($t) => htmlspecialchars((string)$t, ENT_QUOTES, 'UTF-8');
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Painel ADM</title>
<style>body{margin:0;min-height:100vh;display:grid;place-items:center;background:#080506;color:#fbf6ea;font:16px system-ui,sans-serif}form{width:min(380px,92vw);background:#150d10;border:1px solid #3b2a22;border-radius:18px;padding:28px}img{display:block;width:180px;margin:0 auto 14px}h1{font-size:20px;text-align:center;margin:0 0 18px}label{display:block;font-size:13px;color:#b9a99a;margin:12px 0 4px}input{width:100%;padding:12px;border-radius:10px;border:1px solid #3b2a22;background:#0f090b;color:#fff;font:inherit;box-sizing:border-box}button{width:100%;margin-top:18px;padding:13px;border:0;border-radius:12px;background:linear-gradient(120deg,#e50914,#b20710);color:#fff;font-weight:800;font-size:15px;cursor:pointer}.m{background:#e5091422;border:1px solid #e50914;padding:10px;border-radius:10px;margin-bottom:10px;font-size:14px}</style></head><body>
<form method="post" autocomplete="off"><img src="logo.png" alt="">
<h1><?= $setup ? 'Primeiro acesso: crie sua senha' : 'Painel ADM' ?></h1>
<?php if ($msg): ?><div class="m"><?= $e($msg) ?></div><?php endif; ?>
<input type="hidden" name="csrf" value="<?= $e(csrf()) ?>">
<?php if ($setup): ?>
<label>Chave de instalação (está no arquivo config.php)</label><input name="key" type="password" required>
<label>Nova senha (mín. 10 caracteres)</label><input name="p1" type="password" minlength="10" required>
<label>Repita a senha</label><input name="p2" type="password" minlength="10" required>
<button>Criar senha e entrar</button>
<?php else: ?>
<label>Senha</label><input name="password" type="password" required autofocus>
<button>Entrar</button>
<?php endif; ?>
</form></body></html>
