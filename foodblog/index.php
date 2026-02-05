<?php

declare(strict_types=1);

require __DIR__ . '/config/database.php';
require __DIR__ . '/config/session.php';

// Query basis
$sql = "
    SELECT
        p.id,
        p.title,
        p.created_at,
        u.username,
        GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR ', ') AS tags
    FROM posts p
    JOIN users u ON u.id = p.user_id
    LEFT JOIN post_tags pt ON pt.post_id = p.id
    LEFT JOIN tags t ON t.id = pt.tag_id
";

// Filters
$params = [];
$conditions = [];

$q = trim($_GET['q'] ?? '');
$tagFilter = (int)($_GET['tag'] ?? 0);

if ($q !== '') {
    $conditions[] = '(p.title LIKE :q OR p.content LIKE :q)';
    $params['q'] = "%$q%";
}

if ($tagFilter > 0) {
    $conditions[] = 'p.id IN (SELECT post_id FROM post_tags WHERE tag_id = :tag)';
    $params['tag'] = $tagFilter;
}

if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$sql .= '
    GROUP BY p.id
    ORDER BY p.created_at DESC
';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Foodblog</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="index-page">

    <header>
        <h1>🍝 Foodblog</h1>

        <?php if (isset($_SESSION['user_id'])): ?>
            <p>
                Ingelogd als <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                |
                <a href="/foodblog/posts/create.php">➕ Nieuwe post</a>
                |
                <a href="/foodblog/auth/logout.php">Uitloggen</a>
            </p>
        <?php else: ?>
            <p>
                <a href="/foodblog/auth/login.php">Inloggen</a>
                |
                <a href="/foodblog/auth/signup.php">Account maken</a>
            </p>
        <?php endif; ?>
    </header>
    <form method="get">
        <label>
            Zoek:
            <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        </label>

        <label>
            Filter op tags:
            <select name="tag">
                <option value="">Alle tags</option>
                <?php
                // Tags ophalen
                $allTagsStmt = $pdo->query('SELECT id, name FROM tags ORDER BY name');
                $allTags = $allTagsStmt->fetchAll();
                $selectedTag = $_GET['tag'] ?? '';
                ?>
                <?php foreach ($allTags as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= $selectedTag == $t['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <button type="submit">Filteren</button>
    </form>
    <hr>

    <hr>

    <main>
        <?php if (!$posts): ?>
            <p>Er zijn nog geen posts.</p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <article>
                <h2><?= htmlspecialchars($post['title']) ?></h2>

                <p>
                    Door <strong><?= htmlspecialchars($post['username']) ?></strong>
                    op <?= htmlspecialchars($post['created_at']) ?>
                </p>

                <?php if ($post['tags']): ?>
                    <p>
                        <em>Tags:</em>
                        <?= htmlspecialchars($post['tags']) ?>
                    </p>
                <?php endif; ?>

                <a href="/foodblog/posts/show.php?id=<?= $post['id'] ?>">
                    Lees meer →
                </a>
            </article>

            <hr>
        <?php endforeach; ?>
    </main>

</body>

</html>