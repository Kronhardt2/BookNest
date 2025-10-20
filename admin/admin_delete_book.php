<?php

// Importa/Inclui outro arquivo (config, modelos, funções). Mantém dependências e configurações.
require_once __DIR__ . '/../src/config.php';

require_admin(); // apenas admin pode excluir

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$book_id = $_GET['id'] ?? null;

if (!$book_id) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_admin'] = "ID do livro inválido!";
    header('Location: admin.php');
    exit;
}

// Buscar livro antes de excluir
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt->execute([$book_id]);

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$book = $stmt->fetch();

if (!$book) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_admin'] = "Livro não encontrado!";
    header('Location: admin.php');
    exit;
}

// Apagar capa se existir
if (!empty($book['cover_image'])) {
    $filePath = __DIR__ . '/../public/images/' . $book['cover_image'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// Excluir atividades relacionadas ao livro
$pdo->prepare("DELETE FROM user_activity WHERE book_id = ?")->execute([$book_id]);

// Excluir livro do banco
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
if ($stmt->execute([$book_id])) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['sucesso_admin'] = "Livro excluído com sucesso!";

} else {
    
    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $_SESSION['erro_admin'] = "Erro ao excluir o livro!";
}

header('Location: admin.php');
exit;
