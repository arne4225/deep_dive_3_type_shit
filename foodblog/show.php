<?php

declare(strict_types=1);

require __DIR__ . '/database.php';
require __DIR__ . '/auth.php';

$postId = (int)($_GET['id'] ?? 0);
if ($postId <= 0) {
    http_response_code(404);
    exit('Post niet gevonden.');
}

// Post ophalen + auteur + tags
$sql = "
    SELECT
        p.id,
        p.user_id,
        p.title,
        p.content,
        p.created_at,
        u.username,
        GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR ', ') AS tags
    FROM posts p
    JOIN users u ON u.id = p.user_id
    LEFT JOIN post_tags pt ON pt.post_id = p.id
    LEFT JOIN tags t ON t.id = pt.tag_id
    WHERE p.id = :id
    GROUP BY p.id
    LIMIT 1
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $postId]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    exit('Post niet gevonden.');
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?> - Foodblog</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="show-post-page">

    <header>
        <h1>Foodblog</h1>
        <p>
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                |
                <a href="logout.php">Uitloggen</a>
            <?php else: ?>
                |
                <a href="login.php">Inloggen</a>
            <?php endif; ?>
        </p>
    </header>

    <main>
        <article>
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            <p>
                Door <strong><?= htmlspecialchars($post['username']) ?></strong>
                op <?= htmlspecialchars($post['created_at']) ?>
            </p>

            <div class="post-content">
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            </div>

            <?php if ($post['tags']): ?>
                <div class="show-post-tags">
                    <?php
                    $tagList = explode(',', $post['tags']);
                    foreach ($tagList as $tag): ?>
                        <span><?= htmlspecialchars(trim($tag)) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <?php if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$post['user_id']): ?>
                <p>
                    <a href="edit.php?id=<?= $post['id'] ?>">
                        ✏️ Bewerken
                    </a>
                    |
                    <a href="delete.php?id=<?= $post['id'] ?>">
                        🗑️ Verwijderen
                    </a>
                </p>
            <?php endif; ?>
        </article>
    </main>

</body>

</html>