<?php

// Inclui configuração, conexão com banco e funções auxiliares
require_once __DIR__ . '/../src/config.php';

// Verifica se usuário está logado
require_login();

// Recupera informações do usuário da sessão
$user_name = $_SESSION['name'] ?? 'Visitante';
$user_id   = $_SESSION['id'] ?? null;

// ===================== CONSULTAS AO BANCO =====================

// Últimos livros adicionados (para o carrossel)
$new_books = $pdo->query("
    SELECT id, title, author, avg_price, cover_image
    FROM books
    ORDER BY created_at DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Livros mais populares (com nome da categoria)
$popular_books = $pdo->query("
    SELECT b.id, b.title, b.author, b.avg_price, b.cover_image, c.name AS category_name
    FROM books b
    LEFT JOIN categories c ON b.category_id = c.id
    WHERE b.views > 0
    ORDER BY b.views DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Categorias mais populares (total de views)
$stmt = $pdo->query("
    SELECT c.id, c.name, SUM(b.views) AS total_views
    FROM categories c
    LEFT JOIN books b ON b.category_id = c.id
    GROUP BY c.id, c.name
    ORDER BY total_views DESC
    LIMIT 6
");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Banners do carrossel (usando os últimos livros)
$banners = $new_books;

// ===================== FUNÇÃO AUXILIAR =====================

// Retorna URL da capa do livro (local ou OpenLibrary)
function getCoverUrl($cover_name)
{
    if (!$cover_name)
        return '../public/covers/default.jpg';

    $local_path = "../public/covers/$cover_name";
    if (file_exists($local_path)) {
        return $local_path;
    }

    $key = pathinfo($cover_name, PATHINFO_FILENAME);
    return "https://covers.openlibrary.org/b/olid/{$key}-M.jpg";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> BookNest - Página Principal </title>

    <!-- CSS principal -->
    <link rel="stylesheet" href="../css/main.css">

    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="icon" href="favicon/favicon.png" type="image/png">
</head>

<body>

    <!-- ===================== HEADER ===================== -->
    <header class="site-header">

        <div class="logo"><a href="site.php"> BookNest </a></div>

        <!-- Busca rápida -->
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="search-input" placeholder="Buscar livros...">
        </div>

        <!-- Menu de navegação -->
        <nav class="nav-menu">

            <a href="sobre_nos.php"> Sobre Nós </a>
            <?php if ($user_id): ?>
                <a href="logout.php"> Logout </a>
            <?php else: ?>
                <a href="index.php"> Login </a>
            <?php endif; ?>

        </nav>
    </header>

    <main>
        
        <!-- ===================== SEÇÃO DE BOAS-VINDAS ===================== -->
        <section id="welcome-section" class="welcome">

            <h1> Olá, <?= htmlspecialchars($user_name) ?>! </h1>

            <div class="buttons">
                <a href="perfil.php" class="btn"> Minha Conta </a>
                <a href="library.php" class="btn"> Biblioteca </a>
            </div>

        </section>

        <!-- ===================== RESULTADOS DA BUSCA ===================== -->
        <section id="search-results" class="search-results" style="display:none;">
            <h2> Resultados da busca </h2>
            <div id="books-container" class="book-grid"></div>
        </section>

        <!-- ===================== CARROSSEL DE NOVIDADES ===================== -->
        <section class="carousel">
            <h2 class="carousel-title"><i class="fas fa-book"></i> Novidades </h2>
            <div class="carousel-track">

                <?php foreach ($banners as $banner): ?>
                    <a href="product.php?id=<?= $banner['id'] ?>">
                        <img src="<?= getCoverUrl($banner['cover_image']) ?>"
                            alt="<?= htmlspecialchars($banner['title']) ?>">
                    </a>
                <?php endforeach; ?>

            </div>
            <div class="carousel-dots"></div>

            <button class="prev">‹</button>
            <button class="next">›</button>

        </section>

        <!-- ===================== LIVROS MAIS POPULARES ===================== -->
        <section class="popular-books">

            <h2> Mais Populares </h2>

            <div class="book-grid">
                
                <?php foreach ($popular_books as $book): ?>

                    <div class="book-card">
                        <img src="<?= getCoverUrl($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                        
                        <div class="book-info">

                            <h3><?= htmlspecialchars($book['title']) ?></h3>
                            <p class="author"><?= htmlspecialchars($book['author']) ?></p>
                            <p class="category">
                                <a> Categoria: </a> <?= htmlspecialchars($book['category_name'] ?? 'Sem categoria') ?>
                            </p>

                            <div class="bottom-info">
                                <p class="price"> R$ <?= number_format($book['avg_price'], 2, ',', '.') ?></p>
                                <a href="product.php?id=<?= $book['id'] ?>" class="btn-buy"> Ver mais </a>
                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </section>

        <!-- ===================== CATEGORIAS ===================== -->
        <section class="categories">

            <h2> Categorias </h2>

            <div class="category-grid">
                <?php foreach ($categories as $cat): ?>
                    <a href="page_categories.php?category_id=<?= $cat['id'] ?>" class="category-card">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

        </section>
    </main>

    <!-- ===================== FOOTER ===================== -->
    <footer class="support">
        <div class="footer-container">

            <div class="footer-links">
                <p class="footer-title"> Links </p>
                <p class="clickable"> Suporte </p>
                <p class="clickable"> Dúvidas </p>
                <p class="clickable"> Contato </p>
                <p class="clickable"> Política de Privacidade </p>
            </div>

            <div class="footer-map">

                <p class="map-title"> Localização </p>

                <div class="map-placeholder">
                    <p> Mapa aqui </p>
                </div>

            </div>

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

    <!-- ===================== SCRIPT DE BUSCA ===================== -->
    <script>

        const searchInput = document.getElementById('search-input');
        const welcomeSection = document.getElementById('welcome-section');
        const searchResultsSection = document.getElementById('search-results');
        const booksContainer = document.getElementById('books-container');

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            // Se input vazio, mostra boas-vindas e esconde resultados
            if (query.length === 0) {
                welcomeSection.style.display = 'block';
                searchResultsSection.style.display = 'none';
                booksContainer.innerHTML = '';
                return;
            }

            welcomeSection.style.display = 'none';
            searchResultsSection.style.display = 'block';

            // Busca via AJAX (fetch) para retornar resultados
            fetch('../src/search.php?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    booksContainer.innerHTML = '';
                    if (data.length === 0) {
                        booksContainer.innerHTML = '<p>Nenhum livro encontrado.</p>';
                        return;
                    }

                    data.forEach(book => {
                        const card = document.createElement('div');
                        card.classList.add('book-card');
                        card.innerHTML = `
                            <img src="${book.cover_image}" alt="${book.title}">
                            <div class="book-info">
                                <h3>${book.title}</h3>
                                <p class="author">${book.author}</p>
                                <div class="bottom-info">
                                    <p class="price">R$ ${parseFloat(book.avg_price).toFixed(2).replace('.', ',')}</p>
                                    <a href="product.php?id=${book.id}" class="btn-buy">Ver mais</a>
                                </div>
                            </div>
                        `;
                        booksContainer.appendChild(card);
                    });
                });
        });

    </script>

    <!-- Scripts auxiliares -->
    <script src="../js/carousel.js"></script>
    <script src="eventsde.js"></script>

</body>

</html>