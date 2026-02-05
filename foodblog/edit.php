<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../auth/auth.php';

requireLogin(); // Alleen ingelogd

$errors = [];

$postId = (int)($_GET['id'] ?? 0);
if ($postId <= 0) {
    http_response_code(404);
    exit('Post niet gevonden.');
}

// Post ophalen + auteur check
$stmt = $pdo->prepare(
    'SELECT * FROM posts WHERE id = :id'
);
$stmt->execute(['id' => $postId]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    exit('Post niet gevonden.');
}

// Alleen eigenaar mag bewerken
if ((int)$post['user_id'] !== $_SESSION['user_id']) {
    http_response_code(403);
    exit('Geen toegang tot deze post.');
}

// Alle tags ophalen
$tagStmt = $pdo->query('SELECT id, name FROM tags ORDER BY name');
$tags = $tagStmt->fetchAll();

// Tags die bij deze post horen ophalen
$selectedTagStmt = $pdo->prepare(
    'SELECT tag_id FROM post_tags WHERE post_id = :post_id'
);
$selectedTagStmt->execute(['post_id' => $postId]);
$selectedTags = $selectedTagStmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $newTags = $_POST['tags'] ?? [];
    if (!is_array($newTags)) $newTags = [];

    if ($title === '') $errors[] = 'Titel is verplicht.';
    if ($content === '') $errors[] = 'Inhoud is verplicht.';

    if (empty($errors)) {
        // Post updaten
        $stmt = $pdo->prepare(
            'UPDATE posts
             SET title = :title, content = :content
             WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'id' => $postId,
            'user_id' => $_SESSION['user_id']
        ]);

        // Tags resetten: DELETE + INSERT
        $pdo->prepare('DELETE FROM post_tags WHERE post_id = :post_id')
            ->execute(['post_id' => $postId]);

        $insertTag = $pdo->prepare(
            'INSERT INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)'
        );

        foreach ($newTags as $tagId) {
            $insertTag->execute([
                'post_id' => $postId,
                'tag_id' => (int)$tagId
            ]);
        }

        header('Location: /foodblog/posts/show.php?id=' . $postId);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Bewerk post</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="edit-post-page">

    <h1>Bewerk post</h1>

    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <label>
            Titel<br>
            <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        </label>
        <br><br>

        <label>
            Inhoud<br>
            <textarea name="content" rows="8" required><?= htmlspecialchars($post['content']) ?></textarea>
        </label>
        <br><br>

        <fieldset>
            <legend>Tags</legend>
            <?php foreach ($tags as $tag): ?>
                <label>
                    <input
                        type="checkbox"
                        name="tags[]"
                        value="<?= $tag['id'] ?>"
                        <?= in_array($tag['id'], $selectedTags) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($tag['name']) ?>
                </label><br>
            <?php endforeach; ?>
        </fieldset>
        <br>

        <button type="submit">Opslaan</button>
    </form>

    <p><a href="/foodblog/posts/show.php?id=<?= $postId ?>">Terug naar post</a></p>

</body>

</html>