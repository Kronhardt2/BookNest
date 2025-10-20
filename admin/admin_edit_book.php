<?php

// Importa/Inclui outro arquivo (config, modelos, funções). Mantém dependências e configurações.
require_once __DIR__ . '/../src/config.php';

// Checa se o usuário é admin
// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit;
}

// Busca categorias
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$erro = $_SESSION['erro_edit_book'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$sucesso = $_SESSION['sucesso_edit_book'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
unset($_SESSION['erro_edit_book'], $_SESSION['sucesso_edit_book']);

// Pega ID do livro
// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$book_id = $_GET['id'] ?? null;

if (!$book_id) {
    header("Location: admin.php");
    exit;
}

// Busca dados do livro
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt->execute([$book_id]);

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    header("Location: admin.php");
    exit;
}

// Busca link atual na tabela book_link
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt_link = $pdo->prepare("SELECT link FROM book_links WHERE book_id = ?");

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt_link->execute([$book_id]);

$current_link = $stmt_link->fetchColumn();

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

    // Normaliza preço
    $avg_price_norm = str_replace(',', '.', $avg_price_raw);
    $avg_price = (strlen($avg_price_norm) > 0 && is_numeric($avg_price_norm)) ? (float) $avg_price_norm : null;

    //campo link da Amazon
    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $amazon_link = trim($_POST['amazon_link'] ?? '');

    // campo total_pages
    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $total_pages = !empty($_POST['total_pages']) ? (int) $_POST['total_pages'] : null;

    // Upload de imagem
    // mantém a atual se não trocar
    $cover_filename = $book['cover_image'];

    if (!empty($_FILES['cover_image']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
        $cover_filename = uniqid('cover_') . '.' . $ext;
        move_uploaded_file($_FILES['cover_image']['tmp_name'], __DIR__ . '/../public/covers/' . $cover_filename);
    }

    // Validação
    if (!$title || !$category_id || $avg_price === null) {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['erro_edit_book'] = "Preencha os campos obrigatórios!";
        header("Location: admin_edit_book.php?id=$book_id");
        exit;
    }

    // Update no banco (tabela books)
    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("UPDATE books SET title=?, author=?, category_id=?, description=?, avg_price=?, cover_image=?, total_pages=? WHERE id=?");
    
    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    if ($stmt->execute([$title, $author, $category_id, $description, $avg_price, $cover_filename, $total_pages, $book_id])) {

        // Atualiza ou insere link da Amazon
        if ($amazon_link) {

            // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
            $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM book_links WHERE book_id=?");

            // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
            $stmt2->execute([$book_id]);
            $exists = $stmt2->fetchColumn();

            if ($exists) {

                // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
                $stmt2 = $pdo->prepare("UPDATE book_links SET link=? WHERE book_id=?");

                // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
                $stmt2->execute([$amazon_link, $book_id]);

            } else {

                // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
                $stmt2 = $pdo->prepare("INSERT INTO book_links (book_id, link) VALUES (?, ?)");

                // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
                $stmt2->execute([$book_id, $amazon_link]);
            }
        }

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['sucesso_edit_book'] = "Livro atualizado com sucesso!";

    } else {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['erro_edit_book'] = "Erro ao atualizar o livro!";
    }

    header("Location: admin_edit_book.php?id=$book_id");
    exit;
}
?>

<!-- Define que este documento é HTML5 e define o idioma como português do Brasil -->
<!doctype html>
<html lang="pt-BR">

<head>
    <!-- Define a codificação de caracteres do documento como UTF-8 -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/responsive.css">

    
    <!-- Título da página que aparece na aba do navegador -->
    <title> Editar Livro — Admin </title>
    
    <!-- Link para o arquivo CSS que estiliza esta página -->
    <link rel="stylesheet" href="../css/admin/admin_edit_book.css">
    
    <link rel="icon" href="../public/favicon/favicon.png" type="image/png">
</head>

<body>

    <!-- Exibe mensagem de erro flutuante caso a variável $erro não esteja vazia -->
    <?php if (!empty($erro)): ?>
        <div class="floating-alert error">
            <?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
            <!-- htmlspecialchars previne execução de código malicioso -->
        </div>
    <?php endif; ?>

    <!-- Exibe mensagem de sucesso flutuante caso a variável $sucesso não esteja vazia -->
    <?php if (!empty($sucesso)): ?>
        <div class="floating-alert success">
            <?php echo htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- Cabeçalho do painel administrativo -->
    <div class="admin-header">
        
        <!-- Título da página -->
        <div class="title"> Editar Livro </div>
        
        <!-- Links de navegação do admin -->
        <div class="nav-links">
            <a href="admin.php"> Livros </a>
            <a href="admin_usuarios.php"> Usuários </a>
            <a href="../public/logout.php"> Logout </a>
        </div>

    </div>

    <!-- Container principal do formulário de edição de livro -->
    <div class="add-book-container">

        <!-- Título principal -->
        <div class="title">
            <h1> Editar Livro </h1>
        </div>

        <!-- Formulário para edição do livro, método POST e suporte a upload de arquivos -->
        <form class="add-book-form" method="POST" enctype="multipart/form-data">

            <!-- Campo de título do livro, obrigatório, preenchido com valor atual -->
            <div>
                <label for="title"> Título* </label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
            </div>

            <!-- Campo do autor do livro, preenchido com valor atual -->
            <div>
                <label for="author"> Autor </label>
                <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>">
            </div>

            <!-- Campo de seleção de categoria, obrigatório -->
            <div>
                <label for="category_id"> Categoria* </label>
                <select id="category_id" name="category_id" required>
                    <option value=""> Selecione... </option>

                    <!-- Loop para preencher o select com categorias e marcar a categoria atual -->
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $book['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <!-- Campo de preço, obrigatório, preenchido com valor atual formatado -->
            <div>
                <label for="avg_price"> Preço* </label>
                <input type="number" step="0.01" id="avg_price" name="avg_price"
                    value="<?= htmlspecialchars(number_format($book['avg_price'], 2, ',', '')) ?>" required>
            </div>

            <!-- Campo de número de páginas, opcional, preenchido com valor atual -->
            <div>
                <label for="total_pages"> Número de páginas </label>
                <input type="number" id="total_pages" name="total_pages" min="1"
                    value="<?= htmlspecialchars($book['total_pages']) ?>">
            </div>

            <!-- Campo de link da Amazon, opcional, preenchido com link atual -->
            <div class="full-width">
                <label for="amazon_link"> Link da Amazon </label>
                <input type="url" id="amazon_link" name="amazon_link" value="<?= htmlspecialchars($current_link) ?>"
                    placeholder="https://www.amazon.com/..." />
            </div>

            <!-- Campo de descrição do livro, opcional, preenchido com valor atual -->
            <div class="full-width">
                <label for="description"> Descrição </label>
                <textarea id="description" name="description"
                    rows="4"><?= htmlspecialchars($book['description']) ?></textarea>
            </div>

            <!-- Seção de capa do livro -->
            <div class="cover-section">
                <div class="cover-preview">

                    <!-- Mostra a capa atual, se existir -->
                    <label> Capa Atual </label>

                    <?php if (!empty($book['cover_image'])): ?>
                        <img src="../public/covers/<?= htmlspecialchars($book['cover_image']) ?>" alt="Capa do livro">
                    <?php else: ?>
                        <p> Sem capa </p>
                    <?php endif; ?>

                </div>

                <!-- Campo para substituir a capa atual -->
                <div class="cover-actions">
                    <label for="cover_image"> Substituir Capa </label>
                    <input type="file" id="cover_image" name="cover_image">
                </div>
            </div>

            <!-- Botão para salvar alterações do livro -->
            <button type="submit"> Salvar Alterações </button>
        </form>

        <!-- Script JavaScript que esconde automaticamente mensagens de alerta após 3 segundos -->
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
 