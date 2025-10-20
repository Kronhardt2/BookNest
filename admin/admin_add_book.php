<?php

// Importa/Inclui outro arquivo (config, modelos, funções). Mantém dependências e configurações.
require_once __DIR__ . '/../src/config.php';

// Checa se o usuário é admin
// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Busca categorias para o select
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$erro = $_SESSION['erro_add_book'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$sucesso = $_SESSION['sucesso_add_book'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
unset($_SESSION['erro_add_book'], $_SESSION['sucesso_add_book']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $title = trim($_POST['title'] ?? '');

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $author = trim($_POST['author'] ?? '');

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $category_id = $_POST['category_id'] ?? null;

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $description = trim($_POST['description'] ?? '');

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $avg_price_raw = $_POST['avg_price'] ?? '';

    // ✅ Novo campo: link da Amazon
    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $amazon_link = trim($_POST['amazon_link'] ?? '');

    // Normaliza preço: aceita 10,00 ou 10.00
    $avg_price_norm = str_replace(',', '.', $avg_price_raw);
    $avg_price = (strlen($avg_price_norm) > 0 && is_numeric($avg_price_norm)) ? (float) $avg_price_norm : null;

    // Upload da imagem
    $cover_image = $_FILES['cover_image'] ?? null;

    // Validação
    if (!$title || !$category_id || $avg_price === null) {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['erro_add_book'] = "Preencha os campos obrigatórios!";
        header("Location: admin_add_book.php");
        exit;
    }

    // Upload da capa
    $cover_filename = null;
    if ($cover_image && $cover_image['tmp_name']) {
        $ext = strtolower(pathinfo($cover_image['name'], PATHINFO_EXTENSION));
        $cover_filename = uniqid('cover_') . '.' . $ext;
        move_uploaded_file($cover_image['tmp_name'], __DIR__ . '/../public/covers/' . $cover_filename);
    }

    // Insere no banco (tabela books)
    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("INSERT INTO books (title, author, category_id, description, avg_price, cover_image, total_pages) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    if ($stmt->execute([$title, $author, $category_id, $description, $avg_price, $cover_filename, $total_pages])) {

        $book_id = $pdo->lastInsertId(); // ✅ pega o ID do livro inserido

        // ✅ Insere link da Amazon na tabela book_link
        if ($amazon_link) {

            // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
            $stmt2 = $pdo->prepare("INSERT INTO book_links (book_id, link) VALUES (?, ?)");

            // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
            $stmt2->execute([$book_id, $amazon_link]);
        }

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['sucesso_add_book'] = "Livro adicionado com sucesso!";
    } else {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['erro_add_book'] = "Erro ao adicionar o livro!";
    }

    header("Location: admin_add_book.php");
    exit;
}
?>

<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/responsive.css">

    <title> Adicionar Livro — Admin </title>
    <link rel="stylesheet" href="../css/admin/admin_add_book.css">
    
    <link rel="icon" href="../public/favicon/favicon.png" type="image/png">
</head>

<body>

    <!-- Exibe uma mensagem de erro flutuante se a variável $erro estiver definida e não for vazia-->
    <?php if (!empty($erro)): ?>
        <div class="floating-alert error">
            <?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- Exibe uma mensagem de sucesso flutuante se a variável $sucesso estiver definida e não for vazia-->
    <?php if (!empty($sucesso)): ?>
        <div class="floating-alert success">
            <?php echo htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="admin-header">
        <div class="title"> Adicionar Livros </div>
        <div class="nav-links">
            <a href="admin.php"> Livros </a>
            <a href="admin_usuarios.php"> Usuários </a>
            <a href="../public/logout.php"> Logout </a>
        </div>
    </div>

    <div class="add-book-container">

        <div class="title">
            <h1> Adicionar Livro </h1>
        </div>

        <form class="add-book-form" method="POST" enctype="multipart/form-data">

            <!-- Campo do título -->
            <div>
                <label for="title"> Título* </label>
                <input type="text" id="title" name="title" required>
            </div>

            <!-- Campo do autor -->
            <div>
                <label for="author"> Autor </label>
                <input type="text" id="author" name="author">
            </div>

            <!-- Campo de categoria -->
            <div>
                <label for="category_id"> Categoria* </label>
                <select id="category_id" name="category_id" required>
                    <option value=""> Selecione... </option>

                    <!-- Trecho para selecionar categoria, com busca com base em php -->
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"> <?= htmlspecialchars($cat['name']) ?> </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <!-- Capa no topo -->
            <div class="cover-upload">
                <label for="cover"> Capa do livro </label>
                <input type="file" id="cover" name="cover">
            </div>

            <!-- Campo de preço -->
            <div>
                <label for="avg_price"> Preço* </label>
                <input type="number" step="0.01" id="avg_price" name="avg_price" required>
            </div>

            <!-- Campo de número de páginas -->
            <div>
                <label for="total_pages"> Número de páginas </label>
                <input type="number" id="total_pages" name="total_pages" min="1">
            </div>

            <!-- Campo de link da Amazon -->
            <div class="full-width">
                <label for="amazon_link"> Link da Amazon </label>
                <input type="url" id="amazon_link" name="amazon_link" placeholder="https://www.amazon.com/..." />
            </div>

            <!-- Campo de descrição -->
            <div class="full-width">
                <label for="description"> Descrição </label>
                <textarea id="description" name="description" rows="4"> </textarea>
            </div>

            <button type="submit"> Adicionar Livro </button>

        </form>

        <!-- Script de tempo limite mensagem fluante -->
        <script>
            setTimeout(() => {
                document.querySelectorAll('.floating-alert').forEach(el => {
                    el.style.display = 'none';
                });
            }, 3000);
        </script>

    </div>

</body>

</html>