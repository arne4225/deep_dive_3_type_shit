<?php

declare(strict_types=1);
require __DIR__ . '/../config/database.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validatie (geen beginner-ifs)
    if ($username === '') {
        $errors[] = 'Gebruikersnaam is verplicht.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Wachtwoord moet minimaal 8 tekens zijn.';
    }

    if (empty($errors)) {
        // Check of username al bestaat
        $stmt = $pdo->prepare(
            'SELECT id FROM users WHERE username = :username'
        );
        $stmt->execute(['username' => $username]);

        if ($stmt->fetch()) {
            $errors[] = 'Gebruikersnaam bestaat al.';
        } else {
            // Wachtwoord veilig hashen
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // User opslaan
            $stmt = $pdo->prepare(
                'INSERT INTO users (username, password_hash)
                 VALUES (:username, :password_hash)'
            );

            $stmt->execute([
                'username' => $username,
                'password_hash' => $passwordHash
            ]);

            $success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Account aanmaken</h1>

    <?php if ($success): ?>
        <p>Account succesvol aangemaakt. Je kunt nu inloggen.</p>
    <?php endif; ?>

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

        <button type="submit">Account aanmaken</button>
    </form>

</body>

</html>