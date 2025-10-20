<?php

// Importa/Inclui outro arquivo (config, modelos, funções). Mantém dependências e configurações.
require_once __DIR__ . '/../src/config.php';

require_login(); // apenas logados
require_admin(); // apenas admin

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$erro = $_SESSION['erro_admin'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$sucesso = $_SESSION['sucesso_admin'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
unset($_SESSION['erro_admin'], $_SESSION['sucesso_admin']);

// Captura filtros
// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$search = trim($_GET['q'] ?? '');

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$category_id = $_GET['category_id'] ?? '';

// Pega categorias para o select
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// SELECT agora busca também o link da Amazon
$sql = "SELECT b.id, b.title, b.author, c.name AS category, b.avg_price, l.link
        FROM books b
        LEFT JOIN categories c ON b.category_id = c.id
        LEFT JOIN book_links l ON b.id = l.book_id
        WHERE 1=1";

$params = [];

if ($search !== '') {
    $sql .= " AND (b.title LIKE ? OR b.author LIKE ?)";
    $like = "%$search%";
    $params[] = $like;
    $params[] = $like;
}

if ($category_id !== '') {
    $sql .= " AND b.category_id = ?";
    $params[] = $category_id;
}

$sql .= " ORDER BY b.id DESC";

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt = $pdo->prepare($sql);

// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Define que este documento é HTML5 e define o idioma como português do Brasil -->
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <!-- Define a codificação de caracteres do documento como UTF-8 -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/responsive.css">


    <!-- Título da página que aparece na aba do navegador -->
    <title> Admin Dashboard </title>

    <!-- Link para o arquivo CSS que estiliza esta página administrativa -->
    <link rel="stylesheet" href="../css/admin/admin.css">
    
    <link rel="icon" href="../public/favicon/favicon.png" type="image/png">
</head>

<body>

    <!-- Exibe mensagem de erro flutuante caso a variável $erro exista -->
    <?php if ($erro): ?>
        <div class="floating-alert error"><?= htmlspecialchars($erro) ?></div>
        <!-- htmlspecialchars previne execução de HTML/JS malicioso -->
    <?php endif; ?>

    <!-- Exibe mensagem de sucesso flutuante caso a variável $sucesso exista -->
    <?php if ($sucesso): ?>
        <div class="floating-alert success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <!-- Cabeçalho do painel administrativo -->
    <div class="admin-header">

        <!-- Título da seção -->
        <div class="title"> Livros </div>

        <!-- Links de navegação do painel -->
        <div class="nav-links">
            <a href="admin_categories.php"> Categorias </a>
            <a href="admin_usuarios.php"> Usuários </a>
            <a href="../public/logout.php"> Logout </a>
        </div>
    </div>

    <!-- Formulário de busca e filtro -->
    <form class="form-search" method="get">

        <!-- Campo de texto para buscar por título ou autor -->
        <input type="text" name="q" placeholder="Título ou Autor" value="<?= htmlspecialchars($search) ?>">

        <!-- Select para filtrar por categoria -->
        <select class="category" name="category_id">

            <option value=""> Todas as Categorias </option>

            <!-- Loop em PHP para preencher select com categorias existentes -->
            <?php foreach ($categories as $cat): ?>

                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $category_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <!-- Botão para aplicar filtro -->
        <button type="submit"> Filtrar </button>

        <!-- Botão para limpar filtros, redireciona para a página principal -->
        <button type="button" onclick="window.location.href='admin.php'"> Limpar </button>

        <!-- Botão para adicionar novo livro, redireciona para o formulário de adição -->
        <button type="button" onclick="window.location.href='admin_add_book.php'"> Adicionar </button>

    </form>

    <!-- Tabela que lista todos os livros -->
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th> ID </th>
                <th> Título </th>
                <th> Autor </th>
                <th> Categoria </th>
                <th> Preço </th>
                <th> Amazon </th>
                <th> Ações </th>
            </tr>
        </thead>
        <tbody>

            <!-- Verifica se existem livros cadastrados -->
            <?php if (!empty($books)): ?>

                <!-- Loop em PHP para listar cada livro -->
                <?php foreach ($books as $book): ?>

                    <tr>
                        <td> <?= $book['id'] ?> </td>
                        <td> <?= htmlspecialchars($book['title']) ?> </td>
                        <td> <?= htmlspecialchars($book['author']) ?> </td>
                        <td> <?= htmlspecialchars($book['category']) ?> </td>
                        <td> R$ <?= number_format($book['avg_price'], 2, ',', '.') ?> </td>

                        <!-- Mostra link da Amazon se existir, senão exibe traço -->
                        <td>

                            <?php if ($book['link']): ?>
                                <a href="<?= htmlspecialchars($book['link']) ?>" target="_blank"> Ver </a>
                            <?php else: ?>
                                —
                            <?php endif; ?>

                        </td>

                        <!-- Ações: Editar e Excluir -->
                        <td>

                            <a href="admin_edit_book.php?id=<?= $book['id'] ?>"> Editar </a> |
                            <a href="admin_delete_book.php?id=<?= $book['id'] ?>"
                                onclick="return confirm('Tem certeza que deseja excluir?')"> Excluir </a>
                        </td>

                    </tr>

                <?php endforeach; ?>

                <!-- Caso não existam livros -->
            <?php else: ?>

                <tr>
                    <td colspan="7"> Nenhum livro encontrado </td>
                </tr>

            <?php endif; ?>

        </tbody>
    </table>

    <!-- Script JavaScript que esconde automaticamente mensagens de alerta após 3 segundos -->
    <script>
        setTimeout(() => {
            document.querySelectorAll('.floating-alert').forEach(el => {
                el.style.display = 'none';
            });
        }, 3000);
    </script>

</body>

</html>