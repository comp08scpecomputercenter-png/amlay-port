<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed.'], 405);
}

if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized.'], 401);
}

try {
    $pdo = getDatabaseConnection();
    $studentId = getLoggedInStudentId();

    $stmt = $pdo->prepare(
        'SELECT id, full_name, contact_number, email, created_at, updated_at
         FROM students WHERE id = :id LIMIT 1'
    );
    $stmt->execute(['id' => $studentId]);
    $student = $stmt->fetch();

    if (!$student) {
        logoutStudent();
        jsonResponse(['success' => false, 'message' => 'Account not found.'], 404);
    }

    jsonResponse([
        'success' => true,
        'student' => [
            'id'             => (int) $student['id'],
            'full_name'      => $student['full_name'],
            'contact_number' => $student['contact_number'],
            'email'          => $student['email'],
            'created_at'     => $student['created_at'],
            'updated_at'     => $student['updated_at'],
        ],
    ]);
} catch (PDOException $e) {
    error_log('Profile fetch error: ' . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'Failed to load profile.'], 500);
}
