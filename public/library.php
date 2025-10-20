<?php
// ===================== CONFIGURAÇÕES E SESSÃO =====================
require_once __DIR__ . '/../src/config.php';
require_login();
require_once __DIR__ . '/../src/product_model.php';
require_once __DIR__ . '/../src/activity.php';

$user_id = $_SESSION['id'];

// ===================== ATUALIZAÇÃO DE PROGRESSO =====================
if (isset($_POST['update_page'])) {
    $book_id = intval($_POST['book_id']);
    $current_page = intval($_POST['current_page']);

    $stmt = $pdo->prepare("UPDATE user_library 
                           SET current_page = ?, status = 'reading'
                           WHERE user_id = ? AND book_id = ?");
    $stmt->execute([$current_page, $user_id, $book_id]);

    addActivity($pdo, $user_id, 'reading', $book_id);
    header("Location: library.php");
    exit;
}

// ===================== FINALIZAR LIVRO =====================
if (isset($_POST['finish_book'])) {
    $book_id = intval($_POST['book_id']);

    $stmt = $pdo->prepare("UPDATE user_library 
                           SET status = 'finished', current_page = total_pages
                           WHERE user_id = ? AND book_id = ?");
    $stmt->execute([$user_id, $book_id]);

    addActivity($pdo, $user_id, 'finished', $book_id);
    header("Location: library.php");
    exit;
}

// ===================== COMENTÁRIOS =====================
if (isset($_POST['edit_comment'])) {
    $comment_id = intval($_POST['comment_id']);
    $new_comment = trim($_POST['comment_text'] ?? '');

    if ($comment_id > 0) {
        if ($new_comment === '') {
            $stmt = $pdo->prepare("DELETE FROM user_comments WHERE id = ? AND user_id = ?");
            $stmt->execute([$comment_id, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE user_comments SET comment = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$new_comment, $comment_id, $user_id]);
        }
    }
    header("Location: library.php");
    exit;
}

if (isset($_POST['add_comment'])) {
    $book_id = intval($_POST['book_id']);
    $comment_text = trim($_POST['comment_text'] ?? '');
    if ($comment_text !== '') {
        $stmt = $pdo->prepare("INSERT INTO user_comments (user_id, book_id, comment, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $book_id, $comment_text]);
    }
    header("Location: library.php");
    exit;
}

// ===================== CONSULTA LIVROS =====================
$stmt = $pdo->prepare("SELECT ul.*, b.title, b.author, b.cover_image, b.total_pages
                       FROM user_library ul
                       INNER JOIN books b ON ul.book_id = b.id
                       WHERE ul.user_id = ?
                       ORDER BY ul.added_at DESC");
$stmt->execute([$user_id]);
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Minha Biblioteca - BookNest </title>
    <link rel="stylesheet" href="../css/library.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="favicon/favicon.png" type="image/png">
    <style>
        .editable-comment {
            cursor: pointer;
            margin: 5px 0;
        }

        .editable-comment input {
            font-size: 0.9em;
            padding: 3px;
        }
    </style>
</head>

<body>

    <header class="site-header">
        <div class="logo"><a href="index.php"> BookNest </a></div>
        <nav class="nav-menu">
            <?php if ($user_id): ?>
                <a href="perfil.php"> Minha Conta </a>
                <a href="logout.php"> Logout </a>
            <?php else: ?>
                <a href="login.php"> Login </a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <section class="welcome">
            <h1> Minha Biblioteca </h1>
            <p> Acompanhe seu progresso de leitura e finalize livros quando terminar. </p>
        </section>

        <section class="library-container" style="max-width:1000px; margin: 40px auto 60px;">
            <?php if ($books): ?>
                <?php foreach ($books as $book): ?>
                    <?php
                    $progressPercent = ($book['total_pages'] > 0) ?
                        (($book['status'] === 'finished') ? 100 : round(($book['current_page'] / $book['total_pages']) * 100))
                        : 0;

                    // Busca comentários deste livro
                    $stmt = $pdo->prepare("SELECT uc.*, u.name FROM user_comments uc
                                       INNER JOIN users u ON uc.user_id = u.id
                                       WHERE uc.book_id = ?
                                       ORDER BY uc.created_at ASC");
                    $stmt->execute([$book['book_id']]);
                    $comments = $stmt->fetchAll();
                    ?>
                    <div class="library-card">
                        <img src="../public/covers/<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                        <div class="info">
                            <h3> <?= htmlspecialchars($book['title']) ?> </h3>
                            <p><strong> Autor: </strong> <?= htmlspecialchars($book['author']) ?> </p>
                            <p><strong> Status: </strong> <?= ucfirst($book['status']) ?> </p>

                            <?php if ($book['total_pages']): ?>
                                <div class="progress-bar">
                                    <div class="progress" style="width: <?= $progressPercent ?>%"></div>
                                </div>
                                <p> <?= $book['current_page'] ?> / <?= $book['total_pages'] ?> páginas (<?= $progressPercent ?>%)</p>
                            <?php endif; ?>

                            <?php if ($book['status'] !== 'finished'): ?>
                                <form method="post">
                                    <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                    <input type="number" name="current_page" min="0" max="<?= $book['total_pages'] ?>" value="<?= $book['current_page'] ?>" required>
                                    <button type="submit" name="update_page"> Atualizar Página </button>
                                    <?php if ($book['current_page'] >= $book['total_pages'] && $book['total_pages'] > 0): ?>
                                        <button type="submit" name="finish_book"> Finalizar Leitura </button>
                                    <?php endif; ?>
                                </form>
                            <?php else: ?>
                                <p>✅ Livro finalizado </p>
                            <?php endif; ?>

                            <!-- ===================== COMENTÁRIOS ===================== -->
                            <div class="comments-section">
                                <h4>Comentários</h4>
                                <?php foreach ($comments as $c): ?>
                                    <p class="editable-comment" data-id="<?= $c['id'] ?>">
                                        <strong><?= htmlspecialchars($c['name']) ?>:</strong>
                                        <span class="comment-text"><?= htmlspecialchars($c['comment']) ?></span>
                                    </p>
                                <?php endforeach; ?>
                                <form method="post" style="margin-top:10px;">
                                    <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                    <input type="text" name="comment_text" placeholder="Adicionar comentário..." style="width:100%;" required>
                                    <button type="submit" name="add_comment">Enviar</button>
                                </form>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;"> Você ainda não adicionou livros à sua biblioteca. </p>
            <?php endif; ?>
        </section>
    </main>

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

    <script>
        document.querySelectorAll('.editable-comment').forEach(p => {
            p.addEventListener('click', function() {
                const span = this.querySelector('.comment-text');
                const oldText = span.textContent;
                const commentId = this.dataset.id;

                const input = document.createElement('input');
                input.type = 'text';
                input.value = oldText;
                input.style.width = '100%';

                span.replaceWith(input);
                input.focus();

                function saveComment() {
                    const newText = input.value.trim();
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.innerHTML = `
                <input type="hidden" name="comment_id" value="${commentId}">
                <input type="hidden" name="comment_text" value="${newText}">
                <input type="hidden" name="edit_comment" value="1">
            `;
                    document.body.appendChild(form);
                    form.submit();
                }

                input.addEventListener('blur', saveComment);
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        saveComment();
                    }
                });
            });
        });
    </script>

</body>

</html>