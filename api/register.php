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

$fullName = sanitizeInput($data['full_name'] ?? '');
$contactNumber = sanitizeInput($data['contact_number'] ?? '');
$email = sanitizeInput(strtolower($data['email'] ?? ''));
$password = $data['password'] ?? '';
$confirmPassword = $data['confirm_password'] ?? '';

$errors = [];

if ($fullName === '') {
    $errors[] = 'Full name is required.';
} elseif (!validateFullName($fullName)) {
    $errors[] = 'Full name contains invalid characters.';
}

if ($contactNumber === '') {
    $errors[] = 'Contact number is required.';
} elseif (!validateContactNumber($contactNumber)) {
    $errors[] = 'Please enter a valid Philippine contact number.';
}

if ($email === '') {
    $errors[] = 'Email address is required.';
} elseif (!validateEmail($email)) {
    $errors[] = 'Please enter a valid email address.';
}

if ($password === '') {
    $errors[] = 'Password is required.';
} else {
    $errors = array_merge($errors, validatePassword($password));
}

if ($confirmPassword === '') {
    $errors[] = 'Please confirm your password.';
} elseif ($password !== $confirmPassword) {
    $errors[] = 'Passwords do not match.';
}

if (!empty($errors)) {
    jsonResponse(['success' => false, 'message' => 'Validation failed.', 'errors' => $errors], 422);
}

try {
    $pdo = getDatabaseConnection();

    $stmt = $pdo->prepare('SELECT id FROM students WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);

    if ($stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'An account with this email already exists.'], 409);
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

    $insert = $pdo->prepare(
        'INSERT INTO students (full_name, contact_number, email, password_hash)
         VALUES (:full_name, :contact_number, :email, :password_hash)'
    );

    $insert->execute([
        'full_name'       => $fullName,
        'contact_number'  => $contactNumber,
        'email'           => $email,
        'password_hash'   => $passwordHash,
    ]);

    jsonResponse([
        'success' => true,
        'message' => 'Registration successful! You can now log in.',
    ]);
} catch (PDOException $e) {
    error_log('Registration error: ' . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'Registration failed. Please try again later.'], 500);
}
