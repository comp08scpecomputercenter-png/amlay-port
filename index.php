<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/session.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
