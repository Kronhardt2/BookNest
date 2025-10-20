<?php

// ===================== INÍCIO =====================

// Inicia a sessão PHP — usado para gerenciar sessões de usuário (login, etc).
session_start();

// ===================== INCLUIR DEPENDÊNCIAS =====================

// Importa configurações principais do site (conexão com banco, funções, modelos).
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/product_model.php';
require_once __DIR__ . '/../src/activity.php';

// ===================== PEGAR ID DO LIVRO =====================

// Manipulação de superglobais ($_GET): captura o ID do livro passado na URL
$id = intval($_GET['id'] ?? 0);

// Busca informações do livro usando função no modelo
$book = getBookById($pdo, $id);

// Se livro não encontrado, exibe mensagem e termina
if (!$book) {
    echo '<p>Livro não encontrado.</p>';
    exit;
}

// ===================== CONTAGEM DE VIEWS =====================

// Inicializa array de livros visualizados na sessão se não existir
if (!isset($_SESSION['viewed_books'])) {
    $_SESSION['viewed_books'] = [];
}

// Incrementa contador de views apenas se usuário ainda não visualizou este livro na sessão
if (!in_array($id, $_SESSION['viewed_books'])) {
    $updateViews = $pdo->prepare("UPDATE books SET views = views + 1 WHERE id = ?");
    $updateViews->execute([$id]);
    $_SESSION['viewed_books'][] = $id;
}

// ===================== USUÁRIO LOGADO =====================

// Captura ID do usuário logado (se houver)
$user_id = $_SESSION['id'] ?? null;

// ===================== ADICIONAR/REMOVER DA BIBLIOTECA =====================
// Manipulação de formulário POST para adicionar ou remover livro da biblioteca
if ($user_id && isset($_POST['library_action'])) {

    if ($_POST['library_action'] === 'add') {
        addToLibrary($pdo, $user_id, $id);        // Adiciona livro à biblioteca
        addActivity($pdo, $user_id, 'added', $id); // Adiciona atividade
    } elseif ($_POST['library_action'] === 'remove') {
        removeFromLibrary($pdo, $user_id, $id);     // Remove livro da biblioteca
        addActivity($pdo, $user_id, 'removed', $id); // Adiciona atividade
    }

    // Redireciona para a mesma página para evitar reenvio de formulário
    header("Location: product.php?id=$id");
    exit;
}

// ===================== VERIFICAR SE ESTÁ NA BIBLIOTECA =====================

$isInLibrary = $user_id ? isBookInLibrary($pdo, $user_id, $id) : false;

// ===================== PEGAR LINKS COM PREÇOS =====================

$links = getLinksByBookId($pdo, $id) ?? [];

// ===================== PEGAR LINK DA AMAZON =====================

$stmtAmazon = $pdo->prepare("SELECT link FROM book_links WHERE book_id = ? LIMIT 1");
$stmtAmazon->execute([$id]);
$amazonLink = $stmtAmazon->fetchColumn();

?>

<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($book['title'] ?? 'Livro') ?> — BookNest </title>
    <link rel="stylesheet" href="../css/produto.css">
    <link rel="icon" href="favicon/favicon.png" type="image/png">
</head>

<body>

    <!-- ===================== HEADER ===================== -->
    <header class="site-header">

        <div class="logo"><a href="site.php"> BookNest </a></div>

        <nav class="nav-menu">
            <?php if ($user_id): ?>
                <a href="perfil.php"> Minha Conta </a>
                <a href="logout.php"> Logout </a>
            <?php else: ?>
                <a href="index.php"> Login </a>
            <?php endif; ?>
        </nav>

    </header>

    <!-- ===================== CONTEÚDO DO LIVRO ===================== -->
    <main class="product-container">
        <div class="product-card">

            <!-- CAPA DO LIVRO -->
            <div class="cover">
                <img src="../public/covers/<?= htmlspecialchars($book['cover_image'] ?? 'default.jpg') ?>"
                    alt="<?= htmlspecialchars($book['title'] ?? '') ?>">
            </div>

            <!-- INFORMAÇÕES -->
            <div class="meta">

                <h1><?= htmlspecialchars($book['title'] ?? '') ?></h1>

                <p><strong> Autor: </strong> <?= htmlspecialchars($book['author'] ?? 'Desconhecido') ?></p>
                <p><strong> Categoria: </strong> <?= htmlspecialchars($book['category'] ?? 'N/A') ?></p>

                <!-- Páginas -->
                <?php if (!empty($book['total_pages'])): ?>
                    <p><strong> Páginas: </strong> <?= (int) $book['total_pages'] ?></p>
                <?php endif; ?>

                <p class="desc"> <?= nl2br(htmlspecialchars($book['description'] ?? 'Sem descrição.')) ?> </p>
                <p><strong> Preço médio: </strong> R$ <?= number_format($book['avg_price'] ?? 0, 2, ',', '.') ?></p>
                <p><strong> Visualizações: </strong> <?= $book['views'] ?> vezes </p>

                <!-- ===================== LINKS ===================== -->
                <h3> Amazon </h3>

                <?php if (!empty($amazonLink)): ?>
                    <ul>
                        <li>
                            <a href="<?= htmlspecialchars($amazonLink) ?>" target="_blank">
                                <?= htmlspecialchars($amazonLink['store_name'] ?? 'Comprar') ?>
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>

                <?php if (empty($amazonLink) && empty($links)): ?>
                    <!-- Nenhum link disponível -->
                    <p> Nenhum link disponível. </p>
                <?php endif; ?>

                <!-- BOTÃO ADICIONAR/REMOVER DA BIBLIOTECA -->
                <?php if ($user_id): ?>

                    <form method="post" class="library-form">
                        <button type="submit" name="library_action" value="<?= $isInLibrary ? 'remove' : 'add' ?>"
                            class="btn-add">
                            <?= $isInLibrary ? '📖 Remover da Biblioteca' : '📚 Adicionar à Biblioteca' ?>
                        </button>
                    </form>

                <?php else: ?>
                    <p class="login-warning">🔒 Faça <a href="index.php
"> login</a> para adicionar este livro à sua
                        biblioteca. </p>
                <?php endif; ?>

            </div>
        </div>
    </main>

</body>

</html>