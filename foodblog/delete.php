<?php

declare(strict_types=1);

require __DIR__ . '/database.php';
require __DIR__ . '/auth.php';

requireLogin(); // alleen ingelogd

$postId = (int)($_GET['id'] ?? 0);
if ($postId <= 0) {
    http_response_code(404);
    exit('Post niet gevonden.');
}

// Post ophalen
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
$stmt->execute(['id' => $postId]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    exit('Post niet gevonden.');
}

// Alleen eigenaar mag verwijderen
if ((int)$post['user_id'] !== $_SESSION['user_id']) {
    http_response_code(403);
    exit('Geen toegang tot deze post.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete post
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id AND user_id = :user_id');
    $stmt->execute([
        'id' => $postId,
        'user_id' => $_SESSION['user_id']
    ]);

    // post_tags worden automatisch verwijderd door ON DELETE CASCADE
    header('Location: /foodblog/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Post verwijderen</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="delete-post-page">

    <h1>Post verwijderen</h1>

    <p>Weet je zeker dat je de post "<strong><?= htmlspecialchars($post['title']) ?></strong>" wilt verwijderen?</p>

    <form method="post">
        <button type="submit">Ja, verwijderen</button>
        <a href="show.php?id=<?= $postId ?>">Nee, terug</a>
    </form>

</body>

</html>