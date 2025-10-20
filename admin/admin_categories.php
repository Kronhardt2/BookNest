<?php

// Importa/Inclui outro arquivo (config, modelos, funções). Mantém dependências e configurações.
require_once __DIR__ . '/../src/config.php';

// Apenas admin tem acesso
require_admin();

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$erro = $_SESSION['erro_admin'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$sucesso = $_SESSION['sucesso_admin'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
unset($_SESSION['erro_admin'], $_SESSION['sucesso_admin']);

// ==============================
// CRUD de Categorias
// CRUD = Create, Read, Update, Delete
// ==============================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $name = trim($_POST['name'] ?? '');

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $id = $_POST['id'] ?? null;

    if (!$name) {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['erro_admin'] = "Preencha o nome da categoria!";
        header("Location: admin_categories.php");
        exit;
    }

    if ($id) {

        // Editar categoria
        // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
        $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");

        // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
        if ($stmt->execute([$name, $id])) {

            // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
            $_SESSION['sucesso_admin'] = "Categoria atualizada com sucesso!";
        } else {

            // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
            $_SESSION['erro_admin'] = "Erro ao atualizar categoria!";
        }
    } else {

        // Adicionar nova categoria
        // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");

        // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
        if ($stmt->execute([$name])) {

            // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
            $_SESSION['sucesso_admin'] = "Categoria adicionada com sucesso!";
        } else {

            // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
            $_SESSION['erro_admin'] = "Erro ao adicionar categoria!";
        }
    }

    header("Location: admin_categories.php");
    exit;
}

// Captura ID para editar
// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$edit_id = $_GET['edit'] ?? null;
$edit_category = null;

if ($edit_id) {

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt->execute([$edit_id]);

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $edit_category = $stmt->fetch();
}

// Excluir categoria
// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$delete_id = $_GET['delete'] ?? null;

if ($delete_id) {

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    if ($stmt->execute([$delete_id])) {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['sucesso_admin'] = "Categoria excluída com sucesso!";
    } else {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['erro_admin'] = "Erro ao excluir categoria!";
    }
    header("Location: admin_categories.php");
    exit;
}

// Pega todas categorias
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<!-- Define que este documento é HTML5 e define o idioma como português do Brasil -->
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <!-- Define a codificação de caracteres do documento como UTF-8 -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/responsive.css">


    <!-- Título que aparece na aba do navegador -->
    <title> Admin Dashboard - Categorias </title>

    <!-- Link para o arquivo CSS que estiliza esta página administrativa -->
    <link rel="stylesheet" href="../css/admin/admin_categories.css">

    <link rel="icon" href="../public/favicon/favicon.png" type="image/png">
</head>

<body>

    <!-- Exibe uma mensagem de erro flutuante se a variável $erro estiver definida e não for vazia -->
    <?php if ($erro): ?>
        <div class="floating-alert error"> <?= htmlspecialchars($erro) ?> </div>
        <!-- htmlspecialchars previne execução de HTML/JS malicioso, mostrando o texto puro -->
    <?php endif; ?>

    <!-- Exibe uma mensagem de sucesso flutuante se a variável $sucesso estiver definida -->
    <?php if ($sucesso): ?>
        <div class="floating-alert success"> <?= htmlspecialchars($sucesso) ?> </div>
    <?php endif; ?>

    <!-- Cabeçalho da área administrativa -->
    <div class="admin-header">

        <!-- Título da seção atual -->
        <div class="title"> Categorias </div>

        <!-- Links de navegação dentro do painel administrativo -->
        <div class="nav-links">
            <a href="admin.php"> Livros </a>
            <a href="admin_usuarios.php"> Usuários </a>
            <a href="../public/logout.php"> Logout </a>
        </div>

    </div>

    <!-- Seção de formulário para adicionar ou editar categorias -->
    <div class="form-add-categories">

        <!-- Título do formulário muda dependendo se estamos editando ou adicionando -->
        <h2 class="add-categories"> <?= $edit_category ? "Editar Categoria" : "Adicionar Categoria" ?> </h2>

        <!-- Formulário enviado via POST para processar adição ou edição de categoria -->
        <form method="post">

            <!-- Campo oculto usado para armazenar o ID da categoria ao editar -->
            <input type="hidden" name="id" value="<?= $edit_category['id'] ?? '' ?>">

            <!-- Campo de texto para o nome da categoria, obrigatório -->
            <input type="text" name="name" placeholder="Nome da Categoria"
                value="<?= htmlspecialchars($edit_category['name'] ?? '') ?>" required>

            <!-- Botão de envio muda o texto dependendo se é edição ou adição -->
            <button type="submit"><?= $edit_category ? "Atualizar" : "Adicionar" ?> </button>

            <!-- Botão de cancelar aparece apenas se estivermos editando -->
            <?php if ($edit_category): ?>
                <!-- Redireciona de volta para a página de categorias sem salvar alterações -->
                <button type="button" onclick="window.location.href='admin_categories.php'"> Cancelar </button>
            <?php endif; ?>

        </form>
    </div>

    <!-- Título da tabela que lista todas as categorias existentes -->
    <h2 class="categories"> Categorias Existentes </h2>

    <!-- Tabela que mostra todas as categorias com ID, nome e ações -->
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th> ID </th>
                <th> Nome </th>
                <th> Ações </th>
            </tr>
        </thead>

        <tbody>

            <!-- Loop em PHP para percorrer todas as categorias e exibi-las -->
            <?php foreach ($categories as $cat): ?>

                <tr>
                    <!-- Mostra o ID da categoria -->
                    <td> <?= $cat['id'] ?> </td>

                    <!-- Mostra o nome da categoria, protegido contra HTML malicioso -->
                    <td> <?= htmlspecialchars($cat['name']) ?> </td>

                    <!-- Coluna com links de ação: editar ou excluir a categoria -->
                    <td class="edit-exclude">
                        <!-- Link para editar a categoria, envia o ID via GET -->
                        <a href="?edit=<?= $cat['id'] ?>"> Editar </a> |

                        <!-- Link para excluir a categoria, com confirmação antes de executar -->
                        <a href="?delete=<?= $cat['id'] ?>"
                            onclick="return confirm('Deseja realmente excluir esta categoria?')"> Excluir </a>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

    <!-- Script JavaScript para sumir automaticamente com mensagens de alerta após 3 segundos -->
    <script>
        setTimeout(() => {
            // Seleciona todos os elementos com a classe 'floating-alert' e esconde-os
            document.querySelectorAll('.floating-alert').forEach(el => {
                el.style.display = 'none';
            });
        }, 3000);
    </script>

</body>

</html>