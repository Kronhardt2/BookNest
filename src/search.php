<?php

// Importa/Inclui outro arquivo (config, modelos, funções). Mantém dependências e configurações.
require_once __DIR__ . '/config.php';
require_login();

// Declaração de função — encapsula comportamento reutilizável.
function getCoverUrl($cover_name){

    if (!$cover_name)
        return '../public/covers/default.jpg';
    $local_path = "../public/covers/$cover_name";
    if (file_exists($local_path)) {
        return $local_path;
    }

    $key = pathinfo($cover_name, PATHINFO_FILENAME);
    return "https://covers.openlibrary.org/b/olid/{$key}-M.jpg";
}

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$search = $_GET['q'] ?? '';
$search_results = [];

if ($search) {

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("
        SELECT id, title, author, avg_price, cover_image
        FROM books
        WHERE title LIKE :title OR author LIKE :author
        ORDER BY title ASC
    ");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt->execute(['title' => "%$search%", 'author' => "%$search%"]);
    $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // substitui o campo pela URL final
    foreach ($search_results as &$book) {
        $book['cover_image'] = getCoverUrl($book['cover_image']);
    }
}

header('Content-Type: application/json');
echo json_encode($search_results);
