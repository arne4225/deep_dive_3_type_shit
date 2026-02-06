<?php
require __DIR__ . 'database.php';

$stmt = $pdo->query('SELECT COUNT(*) AS total FROM posts');
$result = $stmt->fetch();

echo 'Aantal posts: ' . $result['total'];
