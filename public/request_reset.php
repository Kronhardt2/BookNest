<?php

// ===================== INÍCIO =====================

// Importa/Inclui outro arquivo (config, conexão PDO, funções). Mantém dependências e configurações.
require_once __DIR__ . '/../src/config.php';

// ===================== CAPTURA DO E-MAIL =====================

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada do usuário via POST.
$email = trim($_POST['email'] ?? '');

// Validação básica do e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    // Retorna mensagem caso e-mail seja inválido
    echo "Email inválido!";
    exit;
}

// ===================== BUSCA USUÁRIO =====================

// Operação com banco de dados (consulta preparada, execução, fetch). Evita SQL Injection.
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

// ===================== SE USUÁRIO EXISTE =====================

if ($user) {

    // Gera token aleatório seguro
    $token = bin2hex(random_bytes(16));

    // Cria hash do token para armazenar no banco (não armazenar token em texto plano)
    $tokenHash = password_hash($token, PASSWORD_DEFAULT);

    // Define validade do token (1 hora à frente)
    $expires = date("Y-m-d H:i:s", time() + 3600);

    // Atualiza hash e validade no banco
    $stmt = $pdo->prepare("UPDATE users SET reset_token_hash=?, reset_expires=? WHERE id=?");
    $stmt->execute([$tokenHash, $expires, $user['id']]);

    // ===================== MONTAGEM DO LINK =====================

    // Cria link relativo para abrir no index.php e permitir redefinição
    $resetLink = "index.php?action=reset&email=" . urlencode($email) . "&token=" . urlencode($token);

    // Retorna HTML com botão de redefinição (classe btn-add e reset-link para JS)
    echo '<div class="meta"><ul><li>';
    echo '<a href="' . htmlspecialchars($resetLink) . '" class="btn-add reset-link">🔑 Link para redefinição</a>';
    echo '</li></ul></div>';

} else {
    // Caso e-mail não exista, retorna mensagem genérica (não expõe existência de conta)
    echo "Se o e-mail existir, você receberá instruções.";
}

?>