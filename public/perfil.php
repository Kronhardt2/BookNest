<?php

// ======================= CONFIGURAÇÕES E LOGIN =======================
// Importa configurações principais (conexão com banco, sessões, funções auxiliares)
require_once __DIR__ . '/../src/config.php';

// Garante que o usuário esteja logado para acessar esta página
require_login();

// ======================= FUNÇÕES DE ATIVIDADE =======================

// Importa funções relacionadas às atividades do usuário
require_once __DIR__ . '/../src/activity.php';

// ======================= PEGAR INFORMAÇÕES DO USUÁRIO =======================

// Pega ID do usuário logado da sessão
$user_id = $_SESSION['id'];

// Busca informações do usuário no banco (consulta preparada para segurança)
$stmt = $pdo->prepare("SELECT id, name, email, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ======================= MENSAGENS DE FEEDBACK =======================

// Mensagens de erro ou sucesso armazenadas em sessão
$erro = $_SESSION['erro_perfil'] ?? '';
$sucesso = $_SESSION['sucesso_perfil'] ?? '';

// Limpa as mensagens da sessão após capturá-las
unset($_SESSION['erro_perfil'], $_SESSION['sucesso_perfil']);

// ======================= BUSCAR ÚLTIMAS ATIVIDADES =======================

// Busca últimas 5 atividades do usuário
$activities = getLastActivities($pdo, $user_id, 5);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/responsive.css">


    <!-- Título da aba do navegador -->
    <title> Minha Conta - BookNest </title>

    <!-- CSS específico para página de conta -->
    <link rel="stylesheet" href="../css/account.css">
    <link rel="icon" href="favicon/favicon.png" type="image/png">

</head>

<body>

    <!-- ======================= MENSAGENS DE ALERTA ======================= -->
    <?php if (!empty($erro)): ?>
        <div class="floating-alert error">
            <?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($sucesso)): ?>
        <div class="floating-alert success">
            <?php echo htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- ======================= CABEÇALHO ======================= -->
    <header class="site-header">

        <div class="logo"><a href="site.php"> BookNest </a></div>

        <nav class="nav-menu">

            <a href="library.php"> Biblioteca </a>

            <?php if ($user_id): ?>
                <!-- Se logado, mostra link de logout -->
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <!-- Caso contrário, link de login -->
                <a href="index.php">Login</a>
            <?php endif; ?>

        </nav>
    </header>

    <!-- ======================= CONTEÚDO DA CONTA ======================= -->
    <div class="account-container">

        <h1> Minha Conta </h1>

        <div class="account-top">

            <!-- Informações do usuário -->
            <div class="account-activity">

                <h2> Informações </h2>

                <ul>
                    <li> <strong> Nome: </strong> <?= htmlspecialchars($user['name']) ?> </li>

                    <?php
                    // Máscara parcial do e-mail para segurança
                    $email = $user['email'];
                    $masked = preg_replace('/(?<=.{3}).(?=.*@)/u', '*', $email);
                    ?>

                    <li><strong> E-mail: </strong> <?= htmlspecialchars($masked) ?></li>
                    <li> <strong> Membro desde: </strong> <?= date('d/m/Y', strtotime($user['created_at'])) ?> </li>
                
                </ul>
            </div>

            <!-- Últimas atividades do usuário -->
            <div class="account-activity">

                <h2> Últimas atividades </h2>

                <ul>
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $act): ?>

                            <li>

                                <?php if ($act['action'] === 'reading'): ?>
                                    📖 Lendo <strong><?= htmlspecialchars($act['title']) ?></strong>
                                <?php elseif ($act['action'] === 'finished'): ?>
                                    ✅ Terminou de ler <strong><?= htmlspecialchars($act['title']) ?></strong>
                                <?php elseif ($act['action'] === 'added'): ?>
                                    ➕ Adicionou <strong><?= htmlspecialchars($act['title']) ?></strong> à sua biblioteca
                                <?php endif; ?>
        
                                <!-- Data da atividade formatada -->
                                <small>(<?= date('d/m/Y H:i', strtotime($act['created_at'])) ?>)</small>

                            </li>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <li> Nenhuma atividade registrada ainda. </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- ======================= FORMULÁRIO DE ATUALIZAÇÃO ======================= -->
        <div class="account-form">

            <h2> Atualizar Informações </h2>

            <form method="post" action="../src/update_profile.php">

                <!-- Nome do usuário -->
                <label for="name"> Nome: </label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

                <!-- E-mail de confirmação -->
                <label for="email"> E-mail (confirmação): </label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail registrado" required>

                <!-- Senha atual -->
                <label for="current_password"> Senha Atual: </label>
                <input type="password" id="current_password" name="current_password" required>

                <!-- Nova senha -->
                <label for="new_password"> Nova Senha (opcional): </label>
                <input type="password" id="new_password" name="new_password">

                <!-- Confirmação da nova senha -->
                <label for="confirm_password"> Confirmar Nova Senha: </label>
                <input type="password" id="confirm_password" name="confirm_password">

                <button type="submit"> Salvar Alterações </button>

            </form>
        </div>

        <!-- ======================= SCRIPT DE MENSAGENS ======================= -->
        <script>

            // Faz as mensagens sumirem depois de 3 segundos
            setTimeout(() => {
                document.querySelectorAll('.floating-alert').forEach(el => {
                    el.style.display = 'none';
                });
            }, 3000);

        </script>

</body>

</html>