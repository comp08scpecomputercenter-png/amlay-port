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
    <meta name="description" content="Student Registration - Sulu State University">
    <title>Register | Sulu State University</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="page-wrapper">
        <div class="auth-container">
            <div class="auth-card auth-card-wide">
                <div class="auth-header">
                    <div class="logo">
                        <div class="logo-icon">SSU</div>
                        <div>
                            <h1>Sulu State University</h1>
                            <p>Student Portal</p>
                        </div>
                    </div>
                    <h2>Create Account</h2>
                    <p class="subtitle">Register to access the student portal</p>
                </div>

                <div id="alert-container" class="alert-container" role="alert" aria-live="polite"></div>

                <form id="register-form" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= sanitizeOutput($csrfToken) ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                placeholder="Juan Dela Cruz"
                                autocomplete="name"
                                required
                            >
                            <span class="field-error" id="full_name-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input
                                type="tel"
                                id="contact_number"
                                name="contact_number"
                                placeholder="09171234567"
                                autocomplete="tel"
                                required
                            >
                            <span class="field-error" id="contact_number-error"></span>
                        </div>
                    </div>

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

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="password-wrapper">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Create a strong password"
                                    autocomplete="new-password"
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
                            <p class="field-hint">Min. 8 characters with uppercase, lowercase, and a number</p>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <div class="password-wrapper">
                                <input
                                    type="password"
                                    id="confirm_password"
                                    name="confirm_password"
                                    placeholder="Re-enter your password"
                                    autocomplete="new-password"
                                    required
                                >
                                <button type="button" class="toggle-password" aria-label="Toggle password visibility" data-target="confirm_password">
                                    <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="field-error" id="confirm_password-error"></span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <span class="btn-text">Create Account</span>
                        <span class="btn-loader" hidden></span>
                    </button>
                </form>

                <p class="auth-footer">
                    Already have an account?
                    <a href="login.php">Sign in here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/register.js"></script>
</body>
</html>
