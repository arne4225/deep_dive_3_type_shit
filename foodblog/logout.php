<?php
declare(strict_types=1);

require __DIR__ . '/../config/session.php';

$_SESSION = [];
session_destroy();

header('Location: /foodblog/auth/login.php');
exit;
