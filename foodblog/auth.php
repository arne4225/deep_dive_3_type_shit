<?php
declare(strict_types=1);

require __DIR__ . '/session.php';

function requireLogin(): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
