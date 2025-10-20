<?php

// ===================== HEADERS DE CACHE =====================
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// ===================== CONFIGURAÇÃO DO PDO =====================
$host = 'localhost';
$db = 'bookshelf';
$user = 'books_user';   // usuário já criado no MySQL
$pass = 'Senha@123';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,          // lança exceções em erros
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // fetch retorna array associativo
    PDO::ATTR_EMULATE_PREPARES => false,                 // desativa emulação de prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

// ===================== SESSÃO SEGURA =====================
if (session_status() === PHP_SESSION_NONE) {

    session_set_cookie_params([
        'lifetime' => 0,         // sessão dura até o fechamento do navegador
        'path' => '/',
        'domain' => 'localhost',
        'secure' => false,       // true em produção com HTTPS
        'httponly' => true,      // impede acesso via JS
        'samesite' => 'Lax'      // evita CSRF básico
    ]);
    session_start();            // inicia a sessão

}

// Reforça headers anti-cache
header('Cache-Control: post-check=0, pre-check=0', false);

// ===================== FUNÇÕES AUXILIARES =====================
/*
 * Verifica se o usuário está logado
 * Redireciona para login caso não esteja
 */

function require_login()
{
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: /yourshop/public/login.php');
        exit;
    }
}

/*
 * Verifica se o usuário é admin
 * Redireciona para index caso não seja
 */

function require_admin()
{
    require_login();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: /yourshop/public/index.php');
        exit;
    }
}