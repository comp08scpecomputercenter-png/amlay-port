<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed.'], 405);
}

$data = getJsonInput();

if (!verifyCsrfToken($data['csrf_token'] ?? null)) {
    jsonResponse(['success' => false, 'message' => 'Invalid security token. Please refresh the page.'], 403);
}

$email = sanitizeInput(strtolower($data['email'] ?? ''));
$password = $data['password'] ?? '';

$errors = [];

if ($email === '') {
    $errors[] = 'Email address is required.';
} elseif (!validateEmail($email)) {
    $errors[] = 'Please enter a valid email address.';
}

if ($password === '') {
    $errors[] = 'Password is required.';
}

if (!empty($errors)) {
    jsonResponse(['success' => false, 'message' => 'Validation failed.', 'errors' => $errors], 422);
}

try {
    $pdo = getDatabaseConnection();

    $stmt = $pdo->prepare(
        'SELECT id, full_name, email, password_hash FROM students WHERE email = :email LIMIT 1'
    );
    $stmt->execute(['email' => $email]);
    $student = $stmt->fetch();

    if (!$student || !password_verify($password, $student['password_hash'])) {
        jsonResponse(['success' => false, 'message' => 'Invalid email or password.'], 401);
    }

    loginStudent((int) $student['id'], $student['email'], $student['full_name']);

    jsonResponse([
        'success' => true,
        'message' => 'Login successful!',
        'redirect' => 'dashboard.php',
    ]);
} catch (PDOException $e) {
    error_log('Login error: ' . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'Login failed. Please try again later.'], 500);
}
