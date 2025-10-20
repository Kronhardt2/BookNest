<?php

// Declaração de função — encapsula comportamento reutilizável.
function getAllBooks($pdo){
    
    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->query("SELECT b.*, c.name AS category FROM books b LEFT JOIN categories c ON b.category_id = c.id ORDER BY b.id DESC");
    return $stmt->fetchAll();
}

// Declaração de função — encapsula comportamento reutilizável.
function getBookById($pdo, $id){

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("SELECT b.*, c.name AS category FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.id = ? LIMIT 1");
    
    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt->execute([$id]);

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    return $stmt->fetch();
}

// Declaração de função — encapsula comportamento reutilizável.
function getLinksByBookId($pdo, $book_id){

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("SELECT * FROM book_links WHERE book_id = ? ORDER BY id");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt->execute([$book_id]);
    return $stmt->fetchAll();
}

// Verifica se o usuário favoritou um livro
// Declaração de função — encapsula comportamento reutilizável.
function isBookFavorited($pdo, $user_id, $book_id){

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("SELECT 1 FROM favorites WHERE user_id = ? AND book_id = ? LIMIT 1");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt->execute([$user_id, $book_id]);
    return (bool) $stmt->fetchColumn();
}

// Adicionar livro aos favoritos
// Declaração de função — encapsula comportamento reutilizável.
function addFavorite($pdo, $user_id, $book_id){

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("INSERT IGNORE INTO favorites (user_id, book_id) VALUES (?, ?)");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    return $stmt->execute([$user_id, $book_id]);
}

// Remover livro dos favoritos
// Declaração de função — encapsula comportamento reutilizável.
function removeFavorite($pdo, $user_id, $book_id){

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND book_id = ?");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    return $stmt->execute([$user_id, $book_id]);
}

// Buscar todos os favoritos de um usuário
// Declaração de função — encapsula comportamento reutilizável.
function getFavoritesByUser($pdo, $user_id){

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("
        SELECT b.* 
        FROM books b 
        INNER JOIN favorites f ON b.id = f.book_id 
        WHERE f.user_id = ? 
        ORDER BY f.created_at DESC
    ");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Declaração de função — encapsula comportamento reutilizável.
function addToLibrary($pdo, $user_id, $book_id){

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("INSERT IGNORE INTO user_library (user_id, book_id) VALUES (?, ?)");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    return $stmt->execute([$user_id, $book_id]);
}

// Declaração de função — encapsula comportamento reutilizável.
function removeFromLibrary($pdo, $user_id, $book_id){

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("DELETE FROM user_library WHERE user_id = ? AND book_id = ?");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    return $stmt->execute([$user_id, $book_id]);
}

// Declaração de função — encapsula comportamento reutilizável.
function isBookInLibrary($pdo, $user_id, $book_id){

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("SELECT 1 FROM user_library WHERE user_id = ? AND book_id = ?");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt->execute([$user_id, $book_id]);
    return $stmt->fetchColumn() ? true : false;
}
