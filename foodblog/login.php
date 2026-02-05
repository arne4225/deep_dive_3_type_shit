<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../config/session.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Gebruikersnaam en wachtwoord zijn verplicht.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT id, password_hash FROM users WHERE username = :username'
        );
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors[] = 'Ongeldige inloggegevens.';
        } else {
            // Login succesvol → sessie vastleggen
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

            header('Location: /foodblog/index.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="login-page">

    <h1>Inloggen</h1>

    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <label>
            Gebruikersnaam<br>
            <input type="text" name="username" required>
        </label>
        <br><br>

        <label>
            Wachtwoord<br>
            <input type="password" name="password" required>
        </label>
        <br><br>

        <button type="submit">Inloggen</button>
    </form>

</body>

</html>