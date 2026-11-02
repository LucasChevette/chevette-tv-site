<?php
declare(strict_types=1);
require __DIR__.'/lib.php';
send_headers();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
function out(int $c, array $a): void { http_response_code($c); echo json_encode($a, JSON_UNESCAPED_UNICODE); exit; }
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') out(405, ['ok'=>false,'error'=>'Método inválido']);
if (!is_admin()) out(403, ['ok'=>false,'error'=>'Sessão expirada. Entre de novo em admin.php']);
if (!hash_equals(csrf(), (string)($_SERVER['HTTP_X_CSRF'] ?? ''))) out(403, ['ok'=>false,'error'=>'Token inválido. Recarregue a página.']);
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '') {
  $oh = (string)parse_url($origin, PHP_URL_HOST); $hh = explode(':', (string)($_SERVER['HTTP_HOST'] ?? ''))[0];
  if (strcasecmp($oh, $hh) !== 0) out(403, ['ok'=>false,'error'=>'Origem não permitida']);
}
$raw = (string)file_get_contents('php://input');
if (strlen($raw) > 200000) out(413, ['ok'=>false,'error'=>'Dados muito grandes']);
$in = json_decode($raw, true);
if (!is_array($in)) out(400, ['ok'=>false,'error'=>'Dados inválidos']);
$old = load_data();
if ($old) write_store('backup', $old);          // guarda a versão anterior
if (!write_store('data', sanitize($in))) out(500, ['ok'=>false,'error'=>'Não consegui gravar. Veja a permissão da pasta _data.']);
out(200, ['ok'=>true]);
