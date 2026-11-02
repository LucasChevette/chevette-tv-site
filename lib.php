<?php
declare(strict_types=1);
require_once __DIR__.'/config.php';
const DATA_DIR = __DIR__.'/_data';

function send_headers(): void {
  header('X-Content-Type-Options: nosniff');
  header('X-Frame-Options: DENY');
  header('Referrer-Policy: strict-origin-when-cross-origin');
  header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
  header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self'; frame-ancestors 'none'; base-uri 'none'; form-action 'self'");
}
function secure_session(): void {
  if (session_status() === PHP_SESSION_ACTIVE) return;
  $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
  session_name(SESSION_NAME);
  session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>$https,'httponly'=>true,'samesite'=>'Lax']);
  session_start();
}
function is_admin(): bool {
  if (!isset($_COOKIE[SESSION_NAME])) return false;   // visitantes comuns nem criam sessão
  secure_session();
  if (empty($_SESSION['adm'])) return false;
  if (time() - (int)($_SESSION['t'] ?? 0) > 7200) { $_SESSION = []; session_destroy(); return false; }
  $_SESSION['t'] = time();
  return true;
}
function csrf(): string {
  secure_session();
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
  return $_SESSION['csrf'];
}
function login_ok(): void {
  secure_session(); session_regenerate_id(true);
  $_SESSION['adm'] = 1; $_SESSION['t'] = time(); unset($_SESSION['csrf']);
}
/* ---- armazenamento em arquivo .php (se o .htaccess falhar, o conteúdo continua ilegível) ---- */
function read_store(string $name, $default) {
  $f = DATA_DIR."/$name.php";
  if (!is_file($f)) return $default;
  $raw = (string)file_get_contents($f);
  $raw = preg_replace('/^<\?php.*?\?>\R?/s', '', $raw, 1);
  $d = json_decode((string)$raw, true);
  return is_array($d) ? $d : $default;
}
function write_store(string $name, array $d): bool {
  $f = DATA_DIR."/$name.php"; $tmp = $f.'.tmp';
  $body = "<?php http_response_code(404); exit; ?>\n".json_encode($d, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  if (file_put_contents($tmp, $body, LOCK_EX) === false) return false;
  return rename($tmp, $f);
}
function load_data(): array { return read_store('data', []); }
/* ---- limite de tentativas de login ---- */
function throttle_key(): string { return hash('sha256', $_SERVER['REMOTE_ADDR'] ?? 'x'); }
function throttle_lock(): int {
  $a = read_store('attempts', []); $e = $a[throttle_key()] ?? null;
  if ($e && ($e['n'] ?? 0) >= 5) { $left = 900 - (time() - (int)$e['t']); return $left > 0 ? $left : 0; }
  return 0;
}
function throttle_fail(): void {
  $a = read_store('attempts', []); $k = throttle_key(); $now = time();
  foreach ($a as $kk => $v) if (($v['t'] ?? 0) < $now - 86400) unset($a[$kk]);
  $e = $a[$k] ?? ['n'=>0,'t'=>0];
  if ($now - (int)$e['t'] > 900) $e['n'] = 0;
  $e['n']++; $e['t'] = $now; $a[$k] = $e; write_store('attempts', $a);
}
function throttle_clear(): void { $a = read_store('attempts', []); unset($a[throttle_key()]); write_store('attempts', $a); }
/* ---- validação do que o painel envia ---- */
function s($v, int $max): string {
  $v = is_scalar($v) ? (string)$v : '';
  $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $v) ?? '';
  return mb_substr(trim($v), 0, $max);
}
function url_ok($v): string { $v = s($v, 500); return preg_match('~^https?://~i', $v) ? $v : ''; }
function sanitize(array $in): array {
  $o = [
    'name'=>s($in['name'] ?? '', 60) ?: 'Chevette TV',
    'logo'=>url_ok($in['logo'] ?? ''),
    'wa'=>substr(preg_replace('/\D/', '', (string)($in['wa'] ?? '')) ?? '', 0, 15),
    'title'=>s($in['title'] ?? '', 120), 'sub'=>s($in['sub'] ?? '', 400),
    'android'=>url_ok($in['android'] ?? ''), 'pc'=>url_ok($in['pc'] ?? ''),
    'perks'=>s($in['perks'] ?? '', 300), 'testo'=>s($in['testo'] ?? '', 120),
  ];
  $lists = [
    'devices'=>['g'=>40,'i'=>8,'n'=>50],
    'coupons'=>['code'=>30,'off'=>30,'pct'=>6,'d'=>80],
    'plans'=>['n'=>40,'t'=>30,'p'=>12,'f'=>300],
    'cats'=>['a'=>20,'i'=>8,'n'=>40,'d'=>200],
  ];
  foreach ($lists as $k => $fields) {
    $o[$k] = [];
    $items = is_array($in[$k] ?? null) ? array_slice($in[$k], 0, 60) : [];
    foreach ($items as $it) {
      if (!is_array($it)) continue;
      $r = [];
      foreach ($fields as $f => $max) $r[$f] = s($it[$f] ?? '', $max);
      if ($k === 'coupons') { $r['pct'] = (string)max(0, min(100, (float)str_replace(',', '.', $r['pct']))); }
      if ($k === 'plans') { $r['p'] = preg_replace('/[^0-9.,]/', '', $r['p']) ?? ''; }
      $o[$k][] = $r;
    }
  }
  return $o;
}
