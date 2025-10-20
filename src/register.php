<?php

// Importa/Inclui outro arquivo (config, modelos, funções). Mantém dependências e configurações.
require_once __DIR__ . "/../src/config.php";

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$name = trim($_POST["name"] ?? '');

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$email = trim($_POST["email"] ?? '');

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$password = $_POST["password"] ?? '';

if (strlen($name) < 3 || strlen($name) > 100) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_cadastro'] = "Nome inválido!";
    header("Location: ../public/login.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_cadastro'] = "Email inválido!";
    header("Location: ../public/login.php");
    exit;
}

if (strlen($password) < 8) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_cadastro'] = "Senha deve ter no mínimo 8 caracteres!";
    header("Location: ../public/login.php");
    exit;
}

// Verifica letra maiúscula
if (!preg_match('/[A-Z]/', $password)) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_cadastro'] = "Senha deve conter pelo menos uma letra maiúscula!";
    header("Location: ../public/login.php");
    exit;
}

// Verifica letra minúscula
if (!preg_match('/[a-z]/', $password)) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_cadastro'] = "Senha deve conter pelo menos uma letra minúscula!";
    header("Location: ../public/login.php");
    exit;
}

// Verifica número
if (!preg_match('/[0-9]/', $password)) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_cadastro'] = "Senha deve conter pelo menos um número!";
    header("Location: ../public/login.php");
    exit;
}

// Verifica caractere especial
if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_cadastro'] = "Senha deve conter pelo menos um caractere especial (!@#$...)!";
    header("Location: ../public/login.php");
    exit;
}


// Checa se email já existe
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt->execute([$email]);

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
if ($stmt->fetch()) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_cadastro'] = "Email já cadastrado!";
    header("Location: ../public/login.php");
    exit;
}

// Cria hash seguro da senha
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insere usuário no banco
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
if ($stmt->execute([$name, $email, $hashed_password])) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça login.";
    header("Location: ../public/login.php");
    exit;

} else {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_cadastro'] = "Ocorreu um erro! Tente novamente.";
    header("Location: ../public/login.php");
    exit;
    
}
