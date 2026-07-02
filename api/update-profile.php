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

if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized.'], 401);
}

$data = getJsonInput();

if (!verifyCsrfToken($data['csrf_token'] ?? null)) {
    jsonResponse(['success' => false, 'message' => 'Invalid security token. Please refresh the page.'], 403);
}

$fullName = sanitizeInput($data['full_name'] ?? '');
$contactNumber = sanitizeInput($data['contact_number'] ?? '');
$email = sanitizeInput(strtolower($data['email'] ?? ''));
$currentPassword = $data['current_password'] ?? '';
$newPassword = $data['new_password'] ?? '';
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

$changingPassword = $newPassword !== '' || $confirmPassword !== '' || $currentPassword !== '';

if ($changingPassword) {
    if ($currentPassword === '') {
        $errors[] = 'Current password is required to change your password.';
    }
    if ($newPassword === '') {
        $errors[] = 'New password is required.';
    } else {
        $errors = array_merge($errors, validatePassword($newPassword));
    }
    if ($confirmPassword === '') {
        $errors[] = 'Please confirm your new password.';
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = 'New passwords do not match.';
    }
}

if (!empty($errors)) {
    jsonResponse(['success' => false, 'message' => 'Validation failed.', 'errors' => $errors], 422);
}

try {
    $pdo = getDatabaseConnection();
    $studentId = getLoggedInStudentId();

    $stmt = $pdo->prepare('SELECT email, password_hash FROM students WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $studentId]);
    $student = $stmt->fetch();

    if (!$student) {
        jsonResponse(['success' => false, 'message' => 'Account not found.'], 404);
    }

    if ($changingPassword && !password_verify($currentPassword, $student['password_hash'])) {
        jsonResponse(['success' => false, 'message' => 'Current password is incorrect.'], 401);
    }

    if ($email !== $student['email']) {
        $check = $pdo->prepare('SELECT id FROM students WHERE email = :email AND id != :id LIMIT 1');
        $check->execute(['email' => $email, 'id' => $studentId]);
        if ($check->fetch()) {
            jsonResponse(['success' => false, 'message' => 'An account with this email already exists.'], 409);
        }
    }

    if ($changingPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        $update = $pdo->prepare(
            'UPDATE students
             SET full_name = :full_name, contact_number = :contact_number,
                 email = :email, password_hash = :password_hash
             WHERE id = :id'
        );
        $update->execute([
            'full_name'       => $fullName,
            'contact_number'  => $contactNumber,
            'email'           => $email,
            'password_hash'   => $passwordHash,
            'id'              => $studentId,
        ]);
    } else {
        $update = $pdo->prepare(
            'UPDATE students
             SET full_name = :full_name, contact_number = :contact_number, email = :email
             WHERE id = :id'
        );
        $update->execute([
            'full_name'      => $fullName,
            'contact_number' => $contactNumber,
            'email'          => $email,
            'id'             => $studentId,
        ]);
    }

    $_SESSION['student_email'] = $email;
    $_SESSION['student_name'] = $fullName;

    jsonResponse([
        'success' => true,
        'message' => 'Profile updated successfully.',
    ]);
} catch (PDOException $e) {
    error_log('Profile update error: ' . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'Failed to update profile. Please try again later.'], 500);
}
