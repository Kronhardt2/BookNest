<?php

// Importa/Inclui arquivo de configuração (PDO, sessão, etc.)
require_once __DIR__ . '/../src/config.php';

// Manipulação de superglobais ($_GET): captura ID do livro
$id = intval($_GET['id'] ?? 0);

if ($id > 0) {

    // Operação com banco de dados: incrementa views
    // Consulta preparada para evitar SQL Injection
    $stmt = $pdo->prepare("UPDATE books SET views = views + 1 WHERE id = ?");
    $stmt->execute([$id]);

    // Retorna JSON de sucesso
    echo json_encode(["success" => true]);
} else {

    // Retorna JSON de erro se ID inválido
    echo json_encode(["success" => false]);
}

?>
