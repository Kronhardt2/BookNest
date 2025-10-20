<?php

// Conexão e configuração inicial
require_once __DIR__ . '/../src/config.php'; // inicia sessão e conecta ao banco

// Mensagens de erro ou sucesso
$erro = $_SESSION['erro_login'] ?? '';
$erroCadastro = $_SESSION['erro_cadastro'] ?? '';
$sucesso = $_SESSION['sucesso'] ?? '';

// Limpa mensagens antigas da sessão
unset($_SESSION['erro_login'], $_SESSION['erro_cadastro'], $_SESSION['sucesso']);

// ===================== LOGIN =====================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Captura entrada do usuário
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    // Validação básica
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !$password) {
        $_SESSION['erro_login'] = "Dados inválidos!";
        header("Location: login.php");
        exit;
    }

    // Consulta segura ao banco de dados para verificar usuário
    $stmt = $pdo->prepare("SELECT id, name, password, role, status FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verifica senha usando hash seguro
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            // Usuário bloqueado
            if ($user['status'] == 0) {
                $_SESSION['erro_login'] = "Usuário bloqueado!";
                header("Location: login.php");
                exit;
            }

            // Armazena dados do usuário na sessão
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];

            // Redirecionamento por tipo de usuário
            if ($user['role'] === 'admin') {
                header("Location: ../admin/admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $_SESSION['erro_login'] = "Senha incorreta!";
        }
    } else {
        $_SESSION['erro_login'] = "Email não encontrado!";
    }

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login </title>

    <!-- Estilos -->
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="icon" href="favicon/favicon.png" type="image/png">
</head>

<body>

    <!-- ===================== ALERTAS ===================== -->
    <?php if (!empty($erro)): ?>
        <div class="floating-alert error">
            <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($erroCadastro)): ?>
        <div class="floating-alert error">
            <?= htmlspecialchars($erroCadastro, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($sucesso)): ?>
        <div class="floating-alert success">
            <?= htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
        <div class="floating-alert success">Senha redefinida com sucesso! Faça login.</div>
    <?php elseif (isset($_GET['reset']) && $_GET['reset'] === 'error'): ?>
        <div class="floating-alert error"><?= htmlspecialchars($_GET['msg'] ?? 'Erro ao redefinir a senha') ?></div>
    <?php endif; ?>

    <!-- ===================== CONTAINER PRINCIPAL ===================== -->
    <div class="container">

        <!-- Conteúdo 1: Criar conta -->
        <div class="content first-content">
            <div class="first-column">

                <h2 class="title title-primary"> Bem-vindo de volta! </h2>

                <p class="description description-primary"> Para continuar conectado conosco </p>
                <p class="description description-primary"> faça login com suas informações pessoais </p>
                <button id="signin" class="btn btn-primary"> Entrar </button>

            </div>

            <div class="second-column">

                <h2 class="title title-second"> Criar conta </h2>

                <div class="social-media">
                    <ul class="list-social-media">

                        <a class="link-social-media" href="#">
                            <li class="item-social-media"><i class="fab fa-facebook-f"></i></li>
                        </a>

                        <a class="link-social-media" href="#">
                            <li class="item-social-media"><i class="fab fa-google"></i></li>
                        </a>

                    </ul>
                </div>

                <p class="description description-second"> ou use seu e-mail para se cadastrar: </p>

                <form class="form" method="post" action="../src/register.php">

                    <label class="label-input"><i class="far fa-user icon-modify"></i>
                        <input class="input" type="text" placeholder="Nome" name="name" required="">
                    </label>

                    <label class="label-input"><i class="far fa-envelope icon-modify"></i>
                        <input class="input" type="email" placeholder="E-mail" name="email" required="">
                    </label>

                    <div class="containera weak">

                        <label class="label-input" for="password">
                            <i class="fas fa-lock icon-modify"> </i>
                            <input class="input" type="password" placeholder="Senha" name="password" id="YourPassword" required="">
                            <div class="show"></div>
                            <div class="strengthMeter"></div>
                        </label>

                    </div>

                    <input class="btn btn-second" type="submit" value="criar">
                </form>
            </div>
        </div>

        <!-- Conteúdo 2: Login e Recuperação -->
        <div class="content second-content">
            <div class="first-column">

                <h2 class="title title-primary"> Olá, vamos começar? </h2>

                <p class="description description-primary"> Crie sua conta agora </p>
                <p class="description description-primary"> e venha se junte a nós </p>

                <button id="signup" class="btn btn-primary"> Criar </button>

            </div>

            <div class="second-column">

                <!-- ===================== LOGIN FORM ===================== -->
                <div class="formlogin" id="FormLogin">

                    <h2 class="title title-second"> Faça Login </h2>

                    <div class="social-media">
                        <ul class="list-social-media">

                            <a class="link-social-media" href="#">
                                <li class="item-social-media"><i class="fab fa-facebook-f"></i></li>
                            </a>

                            <a class="link-social-media" href="#">
                                <li class="item-social-media"><i class="fab fa-google"></i></li>
                            </a>

                        </ul>
                    </div>

                    <p class="description description-second"> ou entre com sua conta: </p>

                    <form id="loginForm" class="form" method="post" action="">
                        <label class="label-input">
                            <i class="far fa-envelope icon-modify"></i>
                            <input class="input" type="email" placeholder="E-mail" name="email" required autocomplete="username">
                        </label>

                        <label class="label-input">
                            <i class="fas fa-lock icon-modify"></i>
                            <input class="input" type="password" placeholder="Senha" name="password" id="LoginPassword" required autocomplete="current-password">
                            <div class="show"></div>
                        </label>

                        <a href="#" class="password" id="forgotPassword"> Esqueceu sua senha? </a>

                        <input type="hidden" name="login_submit" value="1">
                        <input class="btn btn-second" type="submit" value="Entrar">

                    </form>

                </div>

                <!-- ===================== RECUPERAR SENHA ===================== -->
                <form id="recoverForm" class="form" style="display:none;">

                    <h2 class="title title-second"> Recurpe sua Senha </h2>

                    <div class="social-media">

                        <ul class="list-social-media">

                            <a class="link-social-media" href="#">
                                <li class="item-social-media"><i class="fab fa-facebook-f"></i></li>
                            </a>

                            <a class="link-social-media" href="#">
                                <li class="item-social-media"><i class="fab fa-google"></i></li>
                            </a>

                        </ul>
                    </div>

                    <p class="description description-second"> Informe seu e-mail abaixo: </p>

                    <label class="label-input">
                        <i class="far fa-envelope icon-modify"></i>
                        <input class="input" type="email" name="email" placeholder="E-mail" required>
                    </label>

                    <div id="recoverMessage" style="margin-top:10px;"></div>

                    <?php
                    // Exibe link de redefinição de teste se existir token na sessão
                    if (!empty($_SESSION['reset_token']) && !empty($_SESSION['reset_email'])):
                        $resetLink = "login.php?action=reset&email=" . urlencode($_SESSION['reset_email']) . "&token=" . urlencode($_SESSION['reset_token']);
                    ?>

                        <div class="meta">
                            <ul>
                                <li>
                                    <a href="<?= htmlspecialchars($resetLink) ?>" class="btn-add reset-link">
                                        🔑 Link para redefinição
                                    </a>
                                </li>
                            </ul>
                        </div>

                    <?php endif; ?>

                    <div class="recover-password">
                        <button class="btn btn-link btn-second" type="submit"> Enviar link de recuperação </button>
                        <button class="btn btn-login btn-second" type="button" id="backToLogin"> Voltar ao login </button>
                    </div>

                </form>

                <!-- ===================== REDEFINIR SENHA ===================== -->
                <form id="resetForm" class="form" style="display:none;" method="POST" action="process_reset.php">

                    <h2 class="title title-second"> Redefinir senha </h2>

                    <div class="social-media">

                        <ul class="list-social-media">

                            <a class="link-social-media" href="#">
                                <li class="item-social-media"><i class="fab fa-facebook-f"></i></li>
                            </a>

                            <a class="link-social-media" href="#">
                                <li class="item-social-media"><i class="fab fa-google"></i></li>
                            </a>

                        </ul>
                    </div>

                    <p class="description description-second"> Coloque sua nova senha: </p>

                    <input type="hidden" name="email">
                    <input type="hidden" name="token">

                    <label class="label-input">
                        <i class="fas fa-lock icon-modify"></i>
                        <input class="input" type="password" placeholder="Nova senha" name="nova_senha" required autocomplete="new-password">
                        <div class="show"></div>
                    </label>

                    <input class="btn btn-second" type="submit" value="Redefinir senha">

                </form>

            </div>
        </div>
    </div>

    <!-- ===================== SCRIPTS ===================== -->
    <script src="../js/eventsenha.js"></script>
    <script src="../js/eventsde.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const loginForm = document.querySelector("#loginForm");
            const recoverForm = document.querySelector("#recoverForm");
            const resetForm = document.querySelector("#resetForm");
            const formlogin = document.querySelector('#FormLogin');

            // Mostrar formulário de recuperação
            document.querySelector("#forgotPassword").addEventListener("click", (e) => {
                e.preventDefault();
                loginForm.style.display = "none";
                formlogin.style.display = "none";
                recoverForm.style.display = "block";
            });

            // Voltar ao login
            document.querySelector("#backToLogin").addEventListener("click", () => {
                recoverForm.style.display = "none";
                formlogin.style.display = "block";
                loginForm.style.display = "block";
            });

            // Envio assíncrono do formulário de recuperação
            recoverForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const formData = new FormData(recoverForm);
                const res = await fetch("request_reset.php", {
                    method: "POST",
                    body: formData
                });
                const text = await res.text();
                document.querySelector("#recoverMessage").innerHTML = text;
            });

            // Exibe resetForm ao clicar no link de redefinição
            document.body.addEventListener("click", (e) => {
                if (e.target.matches(".reset-link")) {
                    e.preventDefault();
                    recoverForm.style.display = "none";
                    resetForm.style.display = "block";
                    const urlParams = new URLSearchParams(e.target.href.split("?")[1]);
                    resetForm.querySelector("input[name=email]").value = urlParams.get("email");
                    resetForm.querySelector("input[name=token]").value = urlParams.get("token");
                }
            });
        });
    </script>
</body>

</html>