<?php

declare(strict_types=1);

require __DIR__ . '/database.php';
require __DIR__ . '/auth.php';

requireLogin(); // 🔒 alleen ingelogde users

// Alle tags ophalen voor het formulier
$tagStmt = $pdo->query('SELECT id, name FROM tags ORDER BY name');
$tags = $tagStmt->fetchAll();


$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '') {
        $errors[] = 'Titel is verplicht.';
    }

    if ($content === '') {
        $errors[] = 'Inhoud is verplicht.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'INSERT INTO posts (user_id, title, content)
     VALUES (:user_id, :title, :content)'
        );

        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'title'   => $title,
            'content' => $content,
        ]);

        $postId = (int) $pdo->lastInsertId();

        if ($selectedTags) {
            $tagStmt = $pdo->prepare(
                'INSERT INTO post_tags (post_id, tag_id)
         VALUES (:post_id, :tag_id)'
            );

            foreach ($selectedTags as $tagId) {
                $tagStmt->execute([
                    'post_id' => $postId,
                    'tag_id'  => (int) $tagId,
                ]);
            }
        }



        header('Location: index.php');
        exit;
    }
    $selectedTags = $_POST['tags'] ?? [];

    if (!is_array($selectedTags)) {
        $selectedTags = [];
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Nieuwe post</title>
    <link rel="stylesheet" href="style.css">

</head>

<body class="create-post-page">

    <h1>Nieuwe post aanmaken</h1>

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
            <input type="text" name="title" required>
        </label>
        <br><br>

        <label>
            Inhoud<br>
            <textarea name="content" rows="8" required></textarea>
        </label>
        <br><br>
        <fieldset>
            <legend>Tags</legend>

            <?php foreach ($tags as $tag): ?>
                <label>
                    <input
                        type="checkbox"
                        name="tags[]"
                        value="<?= $tag['id'] ?>">
                    <?= htmlspecialchars($tag['name']) ?>
                </label><br>
            <?php endforeach; ?>
        </fieldset>
        <br>


        <button type="submit">Post opslaan</button>
    </form>

    <p><a href="index.php">Terug naar overzicht</a></p>

</body>

</html>