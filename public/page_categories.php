<?php

// ======================= CONFIGURAÇÕES E LOGIN =======================

// Importa configurações principais (conexão com banco, sessões e funções auxiliares)
require_once __DIR__ . '/../src/config.php';

// Garante que o usuário esteja logado para acessar esta página
require_login();

// Recupera informações do usuário logado da sessão
$user_name = $_SESSION['name'] ?? 'Visitante';
$user_id   = $_SESSION['id'] ?? null;

// ======================= PEGANDO A CATEGORIA =======================

// Recebe o ID da categoria enviado via GET (ex: page_categories.php?category_id=3)
$category_id = $_GET['category_id'] ?? null;

// ======================= BUSCAR LIVROS DA CATEGORIA =======================
if ($category_id) {

    // Prepara consulta SQL para buscar livros que pertencem à categoria selecionada
    // Uso de consulta preparada para evitar injeção de SQL
    $stmt = $pdo->prepare("
        SELECT id, title, author, avg_price, cover_image
        FROM books
        WHERE category_id = :cat
        ORDER BY title ASC
    ");

    // Executa a consulta usando o ID da categoria
    $stmt->execute(['cat' => $category_id]);

    // Armazena todos os livros encontrados na variável $books
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para pegar o nome da categoria pelo ID
    $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = :cat");
    $stmt->execute(['cat' => $category_id]);

    $categoryName = $stmt->fetchColumn(); // Retorna apenas o valor da coluna "name"

} else {

    // Se não foi passado um category_id na URL, inicializa variáveis com valores padrão
    $books = [];
    $categoryName = "Categoria não encontrada";
}

// ======================= FUNÇÃO PARA PEGAR CAPA DO LIVRO =======================

/*
 * Gera a URL da capa do livro.
 * - Se existir a imagem local, retorna ela.
 * - Caso contrário, usa a API do OpenLibrary como fallback.

 * @param string $cover_name Nome do arquivo de capa salvo no banco
 * @return string URL da imagem
 */

function getCoverUrl($cover_name)
{
    if (!$cover_name)
        return '../public/covers/default.jpg'; // capa padrão caso não exista

    // Caminho local da imagem
    $local_path = "../public/covers/$cover_name";
    if (file_exists($local_path))

        return $local_path; // usa imagem local se existir

    // Se não existir localmente, usa OpenLibrary
    $key = pathinfo($cover_name, PATHINFO_FILENAME);
    return "https://covers.openlibrary.org/b/olid/{$key}-M.jpg";

}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">



    <!-- Título da aba do navegador mostra o nome da categoria -->
    <title><?= htmlspecialchars($categoryName) ?> - BookNest</title>

    <!-- CSS principal e CSS específico para page_categories -->
    <link rel="stylesheet" href="../css/page_categories.css">

    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="favicon/favicon.png" type="image/png">

</head>

<body>

    <!-- ======================= CABEÇALHO ======================= -->
    <header class="site-header">

        <!-- Logo do site -->
        <div class="logo"><a href="site.php"> BookNest </a></div>

        <!-- Menu de navegação -->
        <nav class="nav-menu">

            <a href="library.php"> Biblioteca </a>
            <a href="perfil.php"> Minha Conta </a>

            <?php if ($user_id): ?>
                <!-- Mostra logout se usuário estiver logado -->
                <a href="logout.php"> Logout </a>
            <?php else: ?>
                <!-- Caso contrário, mostra link de login -->
                <a href="index.php"> Login </a>
            <?php endif; ?>

        </nav>

    </header>

    <!-- ======================= CONTEÚDO PRINCIPAL ======================= -->
    <main>

        <!-- Seção de cabeçalho da categoria -->
        <section class="category-header">

            <h1> <?= htmlspecialchars($categoryName) ?> </h1>

            <!-- ======================= CONTEÚDO DOS LIVROS ======================= -->
            <?php if (count($books) === 0): ?>

                <!-- Exibe mensagem caso não haja livros na categoria -->
                <p class="no-books"> Nenhum livro encontrado nesta categoria. </p>

            <?php else: ?>

                <!-- Grid de livros -->
                <div class="book-grid">
                    <?php foreach ($books as $book): ?>
                        <div class="book-card">

                            <!-- Imagem da capa -->
                            <img src="<?= getCoverUrl($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">

                            <!-- Informações do livro -->
                            <div class="book-info">

                                <!-- Título do livro -->
                                <h3 ><?= htmlspecialchars($book['title']) ?> </h3>

                                <!-- Autor do livro -->
                                <p class="author"> <?= htmlspecialchars($book['author']) ?> </p>

                                <!-- Nome da categoria -->
                                <p class="category"> <a> Categoria: </a> <?= htmlspecialchars($categoryName) ?> </p>

                                <!-- Preço e botão de ver mais -->
                                <div class="bottom-info">
                                    <p class="price"> R$ <?= number_format($book['avg_price'], 2, ',', '.') ?></p>
                                    <a href="product.php?id=<?= $book['id'] ?>" class="btn-buy"> Ver mais </a>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- ======================= RODAPÉ ======================= -->
    <footer class="support">
        <div class="footer-container">

            <!-- Links rápidos -->
            <div class="footer-links">
                <p class="footer-title"> Links </p>
                <p class="clickable"> Suporte </p>
                <p class="clickable"> Dúvidas </p>
                <p class="clickable"> Contato </p>
                <p class="clickable"> Política de Privacidade </p>
            </div>

            <!-- Mapa do site/localização -->
            <div class="footer-map">

                <p class="map-title"> Localização< /p>

                <div class="map-placeholder">
                    <p> Mapa aqui </p>
                </div>
                
            </div>

            <!-- Redes sociais -->
            <div class="footer-social">

                <p class="footer-title center-text"> Siga-nos </p>

                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
                </div>

            </div>

        </div>

    </footer>

</body>

</html>