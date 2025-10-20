<?php

// Inclui arquivo de configuração e inicializa sessão
require_once __DIR__ . '/../src/config.php';

// Limpa todas as variáveis da sessão
$_SESSION = [];

// Verifica se cookies de sessão estão habilitados
if (ini_get("session.use_cookies")) {

    // Obtém parâmetros atuais do cookie
    $params = session_get_cookie_params();

    // Expira o cookie de sessão no navegador
    setcookie(
        session_name(), // nome da sessão
        '',             // valor vazio
        time() - 42000, // tempo no passado para expirar
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]
    );

}

// Destrói a sessão no servidor
session_destroy();

// Redireciona para a página de login
header('Location: index.php');

exit;
?>