<?php

// ======================= CONFIGURAÇÃO =======================

// Importa configurações principais (conexão com banco, sessões, funções auxiliares)
require_once __DIR__ . '/../src/config.php';

// ======================= RECEBER DADOS DO FORMULÁRIO =======================

// Pega email, token e nova senha enviados via POST
$email = $_POST['email'] ?? '';
$token = $_POST['token'] ?? '';
$nova_senha = $_POST['nova_senha'] ?? '';

// ======================= VALIDAÇÕES =======================

// Verifica campos obrigatórios
if (!$email || !$token || !$nova_senha) {
    header("Location: index.php?reset=error&msg=Campos obrigatórios faltando");
    exit;
}

// Valida comprimento mínimo da senha
if (strlen($nova_senha) < 6) {
    header("Location: index.php?reset=error&msg=Senha deve ter pelo menos 6 caracteres");
    exit;
}

// ======================= BUSCAR USUÁRIO =======================

// Busca usuário pelo email no banco de dados (consulta preparada)
$stmt = $pdo->prepare("SELECT id, reset_token_hash, reset_expires FROM users WHERE email=?");
$stmt->execute([$email]);
$user = $stmt->fetch();

// Verifica se usuário existe
if (!$user) {
    header("Location: index.php?reset=error&msg=Usuário não encontrado");
    exit;
}

// ======================= VERIFICAR TOKEN =======================

// Checa se o token é válido e se não expirou
if (!password_verify($token, $user['reset_token_hash']) || strtotime($user['reset_expires']) < time()) {
    header("Location: index.php?reset=error&msg=Token inválido ou expirado");
    exit;
}

// ======================= ATUALIZAR SENHA =======================

// Cria hash da nova senha
$senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

// Atualiza senha do usuário e remove token e validade (consulta preparada)
$stmt = $pdo->prepare("UPDATE users SET password=?, reset_token_hash=NULL, reset_expires=NULL WHERE id=?");
$stmt->execute([$senha_hash, $user['id']]);

// ======================= REDIRECIONAMENTO =======================

// Redireciona para a página de login com mensagem de sucesso
header("Location: index.php?reset=success");
exit;

?>
