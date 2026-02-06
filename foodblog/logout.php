<?php
declare(strict_types=1);

require __DIR__ . '/session.php';

$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;
