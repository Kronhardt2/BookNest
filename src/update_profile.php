<?php

// Importa/Inclui outro arquivo (config, modelos, funções). Mantém dependências e configurações.
require_once __DIR__ . '/config.php';
require_login();

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$user_id = $_SESSION['id'];

// Dados do formulário
// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$name = trim($_POST['name'] ?? '');

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$email_input = trim($_POST['email'] ?? '');

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$current_password = $_POST['current_password'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$new_password = $_POST['new_password'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$confirm_password = $_POST['confirm_password'] ?? '';

// Busca dados reais do usuário
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt = $pdo->prepare("SELECT email, password FROM users WHERE id = ?");

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt->execute([$user_id]);

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_perfil'] = "Usuário não encontrado.";
    header("Location: ../public/perfil.php");
    exit;
}

// 1. Confere se o email digitado bate com o do banco
if ($email_input !== $user['email']) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_perfil'] = "O e-mail informado não corresponde ao cadastrado.";
    header("Location: ../public/perfil.php");
    exit;
}

// 2. Confere a senha atual
if (!password_verify($current_password, $user['password'])) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_perfil'] = "Senha atual incorreta.";
    header("Location: ../public/perfil.php");
    exit;

}

// 3. Atualização (com ou sem troca de senha)
if (!empty($new_password)) {

    if ($new_password !== $confirm_password) {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['erro_perfil'] = "As senhas não conferem.";
        header("Location: ../public/perfil.php");
        exit;
    }

    $hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $ok = $stmt->execute([$name, $hash, $user_id]);

} else {

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $ok = $stmt->execute([$name, $user_id]);
}

// 4. Retorno
if ($ok) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['name'] = $name;

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['sucesso_perfil'] = "Perfil atualizado com sucesso!";
} else {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_perfil'] = "Erro ao atualizar perfil.";
}

header("Location: ../public/perfil.php");
exit;