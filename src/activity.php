<?php

function addActivity($pdo, $user_id, $action, $book_id = null)
{
    // ===================== CASO "reading" =====================
    if ($action === 'reading') {

        // Verifica se já existe um "Lendo" para esse usuário
        $stmt = $pdo->prepare("
            SELECT id, book_id 
            FROM user_activity 
            WHERE user_id = ? AND action = 'reading'
        ");

        $stmt->execute([$user_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {

            // Se já está lendo o mesmo livro, não adiciona de novo
            if ($existing['book_id'] == $book_id) return;

            // Se começou outro livro, substitui o anterior
            $stmt = $pdo->prepare("
                UPDATE user_activity 
                SET book_id = ?, created_at = NOW() 
                WHERE id = ?
            ");

            $stmt->execute([$book_id, $existing['id']]);
            return;
        }
    }

    // ===================== CASO "finished" =====================
    if ($action === 'finished') {

        // Remove qualquer registro "reading" do mesmo livro
        $stmt = $pdo->prepare("
            DELETE FROM user_activity 
            WHERE user_id = ? AND action = 'reading' AND book_id = ?
        ");

        $stmt->execute([$user_id, $book_id]);
    }

    // ===================== INSERE A ATIVIDADE =====================
    $stmt = $pdo->prepare("
        INSERT INTO user_activity (user_id, action, book_id) 
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$user_id, $action, $book_id]);

}

/*
 * Retorna as últimas atividades de um usuário
 * @param PDO $pdo Conexão com banco de dados
 * @param int $user_id ID do usuário
 * @return array Array associativo com últimas atividades reading, finished e added
 */

function getLastActivities($pdo, $user_id)
{
    $result = [];

    // ===================== Último "reading" =====================
    $stmt = $pdo->prepare("
        SELECT a.action, a.created_at, b.title
        FROM user_activity a
        LEFT JOIN books b ON a.book_id = b.id
        WHERE a.user_id = ? AND a.action = 'reading'
        ORDER BY a.created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$user_id]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $row;

    // ===================== Último "finished" =====================
    $stmt = $pdo->prepare("
        SELECT a.action, a.created_at, b.title
        FROM user_activity a
        LEFT JOIN books b ON a.book_id = b.id
        WHERE a.user_id = ? AND a.action = 'finished'
        ORDER BY a.created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$user_id]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $row;

    // ===================== Último "added" =====================
    $stmt = $pdo->prepare("
        SELECT a.action, a.created_at, b.title
        FROM user_activity a
        LEFT JOIN books b ON a.book_id = b.id
        WHERE a.user_id = ? AND a.action = 'added'
        ORDER BY a.created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$user_id]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $row;

    return $result;
}