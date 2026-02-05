<?php
declare(strict_types=1);

require __DIR__ . '/../config/session.php';

function requireLogin(): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /foodblog/auth/login.php');
        exit;
    }
}
