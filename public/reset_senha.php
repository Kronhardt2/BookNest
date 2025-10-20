<?php require_once __DIR__ . '/../src/config.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/responsive.css">

    <title >Redefinir senha </title>
    <link rel="icon" href="favicon/favicon.png" type="image/png">
</head>

<body>

    <h2> Redefinir senha </h2>

    <form method="POST" action="process_reset.php">

        <!-- Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário.-->
        <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">

        <!-- Manipulação de superglobais ($_POST/$_GET/$_SESSION): entrada ou estado do usuário -->
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
        
        <input type="password" name="nova_senha" placeholder="Nova senha" required>

        <button type="submit"> Redefinir </button>

    </form>
</body>

</html>