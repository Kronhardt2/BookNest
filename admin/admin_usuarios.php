<?php

// Importa/Inclui outro arquivo (config, modelos, funções). Mantém dependências e configurações.
require_once __DIR__ . '/../src/config.php';
require_admin(); // só admins podem acessar

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$erro = $_SESSION['erro_admin'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$sucesso = $_SESSION['sucesso_admin'] ?? '';

// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
unset($_SESSION['erro_admin'], $_SESSION['sucesso_admin']);

// ==============================
// Ações
// ==============================

// Bloquear / Desbloquear
if (isset($_GET['toggle'])) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $id = $_GET['toggle'];

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt->execute([$id]);

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $user = $stmt->fetch();

    if ($user) {

        $novo_status = $user['status'] ? 0 : 1;

        // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");

        // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
        if ($stmt->execute([$novo_status, $id])) {

            // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
            $_SESSION['sucesso_admin'] = $novo_status ? "Usuário desbloqueado!" : "Usuário bloqueado!";
        } else {

            // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
            $_SESSION['erro_admin'] = "Erro ao atualizar usuário!";
        }
    }
    header("Location: admin_usuarios.php");
    exit;
}

// Excluir usuário
if (isset($_GET['delete'])) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $id = $_GET['delete'];

    $pdo->prepare("DELETE FROM user_activity WHERE user_id = ?")->execute([$id]);

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("DELETE FROM users WHERE id != ? AND id = ?");

    // impede que o admin exclua ele mesmo
    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    if ($stmt->execute([$_SESSION['id'], $id])) {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['sucesso_admin'] = "Usuário excluído!";
    } else {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['erro_admin'] = "Erro ao excluir usuário!";
    }
    header("Location: admin_usuarios.php");
    exit;
}

// Captura ID para editar
// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
$edit_id = $_GET['edit'] ?? null;
$edit_user = null;

if ($edit_id) {

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt->execute([$edit_id]);

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $edit_user = $stmt->fetch();
}

// Salvar edição
// Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $id = $_POST['id'];

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $name = trim($_POST['name'] ?? '');

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $email = trim($_POST['email'] ?? '');

    // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
    $role = $_POST['role'] ?? 'user';

    if (!$name || !$email) {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['erro_admin'] = "Preencha todos os campos!";
        header("Location: admin_usuarios.php");
        exit;
    }

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");

    // Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
    if ($stmt->execute([$name, $email, $role, $id])) {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['sucesso_admin'] = "Usuário atualizado!";
    } else {

        // Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.
        $_SESSION['erro_admin'] = "Erro ao atualizar usuário!";
    }

    header("Location: admin_usuarios.php");
    exit;
}

// Lista todos usuários
// Operação com banco de dados (consulta preparada, execução, fetch). Verifique injeção de SQL e tratamento de erros.
$stmt = $pdo->query("SELECT id, name, email, role, status FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title> Admin - Usuários </title>

    <!-- Link para o arquivo CSS que estiliza esta página -->
    <link rel="stylesheet" href="../css/admin/admin_usuarios.css">
    
    <link rel="icon" href="../public/favicon/favicon.png" type="image/png">
</head>

<body>

    <!-- Exibe mensagem de erro flutuante caso a variável $erro exista -->
    <?php if ($erro): ?>
        <div class="floating-alert error"> <?= htmlspecialchars($erro) ?> </div>
        <!-- htmlspecialchars previne execução de HTML/JS malicioso -->
    <?php endif; ?>

    <!-- Exibe mensagem de sucesso flutuante caso a variável $sucesso exista -->
    <?php if ($sucesso): ?>
        <div class="floating-alert success"> <?= htmlspecialchars($sucesso) ?> </div>
    <?php endif; ?>

    <!-- Cabeçalho do painel administrativo -->
    <div class="admin-header">

        <!-- Título da seção -->
        <div class="title"> Administração </div>

        <!-- Links de navegação interna do painel -->
        <div class="nav-links">
            <a href="admin.php"> Livros </a>
            <a href="admin_categories.php"> Categorias </a>
            <a href="../public/logout.php"> Logout </a>
        </div>
    </div>

    <!-- Conteúdo principal do admin -->
    <div class="admin-content">

        <!-- Título da página ou formulário dependendo do contexto -->
        <div class="title">
            <h2> <?= $edit_user ? "Editar Usuário" : "Lista de Usuários" ?> </h2>
        </div>

        <!-- Formulário para editar usuário, só aparece se $edit_user estiver definido -->
        <?php if ($edit_user): ?>

            <form method="post">

                <!-- Campo oculto com ID do usuário sendo editado -->
                <input type="hidden" name="id" value="<?= $edit_user['id'] ?>">

                <!-- Campo de nome do usuário, obrigatório -->
                <input type="text" name="name" placeholder="Nome" value="<?= htmlspecialchars($edit_user['name']) ?>"
                    required>

                <!-- Campo de email do usuário, obrigatório -->
                <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($edit_user['email']) ?>"
                    required>

                <!-- Campo de seleção de função do usuário (user ou admin) -->
                <select name="role">
                    <option value="user" <?= $edit_user['role'] == 'user' ? 'selected' : '' ?>> Usuário </option>
                    <option value="admin" <?= $edit_user['role'] == 'admin' ? 'selected' : '' ?>> Admin </option>
                </select>

                <!-- Botão para atualizar dados -->
                <button type="submit"> Atualizar </button>

                <!-- Link para cancelar a edição e voltar para a lista de usuários -->
                <button type="button" onclick="window.location.href='admin_usuarios.php'"> Cancelar </button>

            </form>

        <?php endif; ?>

        <!-- Tabela que lista todos os usuários -->
        <table>
            <thead>
                <tr>
                    <th> ID </th>
                    <th> Nome </th>
                    <th> Email </th>
                    <th> Função </th>
                    <th> Status </th>
                    <th> Ações </th>
                </tr>
            </thead>
            <tbody>

                <!-- Loop em PHP para percorrer todos os usuários e exibir na tabela -->
                <?php foreach ($users as $u): ?>

                    <tr>

                        <!-- ID do usuário -->
                        <td> <?= $u['id'] ?> </td>

                        <!-- Nome do usuário -->
                        <td> <?= htmlspecialchars($u['name']) ?> </td>

                        <!-- Email do usuário -->
                        <td> <?= htmlspecialchars($u['email']) ?> </td>

                        <!-- Função do usuário (user ou admin) -->
                        <td> <?= $u['role'] ?> </td>

                        <!-- Status do usuário: Ativo ou Bloqueado -->
                        <td> <?= $u['status'] ? "Ativo" : "Bloqueado" ?> </td>

                        <!-- Ações que podem ser executadas no usuário -->
                        <td>

                            <!-- Link para editar usuário -->
                            <a href="?edit=<?= $u['id'] ?>"> Editar </a> |

                            <!-- Link para bloquear ou desbloquear usuário -->
                            <a href="?toggle=<?= $u['id'] ?>"> <?= $u['status'] ? "Bloquear" : "Desbloquear" ?> </a> |

                            <!-- Link para deletar usuário, com confirmação -->
                            <a href="?delete=<?= $u['id'] ?>"
                                onclick="return confirm('Deseja realmente excluir este usuário?')"> Excluir </a>

                        </td>
                    </tr>

                <?php endforeach; ?>

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

    </div>
</body>

</html>