<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Student Login - Sulu State University">
    <title>Login | Sulu State University</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="page-wrapper">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="logo">
                        <div class="logo-icon">SSU</div>
                        <div>
                            <h1>Sulu State University</h1>
                            <p>Student Portal</p>
                        </div>
                    </div>
                    <h2>Welcome Back</h2>
                    <p class="subtitle">Sign in to access your student dashboard</p>
                </div>

                <div id="alert-container" class="alert-container" role="alert" aria-live="polite"></div>

                <form id="login-form" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= sanitizeOutput($csrfToken) ?>">

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="you@example.com"
                            autocomplete="email"
                            required
                        >
                        <span class="field-error" id="email-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >
                            <button type="button" class="toggle-password" aria-label="Toggle password visibility" data-target="password">
                                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        <span class="field-error" id="password-error"></span>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <span class="btn-text">Sign In</span>
                        <span class="btn-loader" hidden></span>
                    </button>
                </form>

                <p class="auth-footer">
                    Don't have an account?
                    <a href="register.php">Register here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/login.js"></script>
</body>
</html>
