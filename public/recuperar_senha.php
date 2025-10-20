<?php require_once __DIR__ . '/../src/config.php'; ?>
/*
 * Bloco de comentários explicativos (adicionado automaticamente):
 * - Abaixo estão descritas intenções gerais do arquivo e partes importantes.
 * - Procure por comentários adicionais (//) antes de linhas-chave (includes, session, SQL, funções).
 */
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/responsive.css">

    <title>Recuperar senha</title>
</head>

<body>
    <h2>Recuperar senha</h2>
    <form method="POST" action="request_reset.php">
        <input type="email" name="email" placeholder="Digite seu e-mail" required>
        <button type="submit">Enviar link de recuperação</button>
    </form>
</body>

</html>