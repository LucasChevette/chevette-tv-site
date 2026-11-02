<?php
declare(strict_types=1);

// Configuração pública: não armazene segredos neste arquivo.
const SESSION_NAME = 'chv_sess';

// A chave de instalação deve ficar em config.local.php (não versionado)
// ou na variável de ambiente CHEVETTE_SETUP_KEY.
$privateConfig = __DIR__ . '/config.local.php';
if (is_file($privateConfig)) {
    require_once $privateConfig;
}
if (!defined('SETUP_KEY')) {
    $envKey = getenv('CHEVETTE_SETUP_KEY');
    define('SETUP_KEY', is_string($envKey) ? $envKey : '');
}
