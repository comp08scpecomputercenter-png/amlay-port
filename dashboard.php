<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

$csrfToken = generateCsrfToken();
$studentName = sanitizeOutput($_SESSION['student_name'] ?? 'Student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Student Dashboard - Sulu State University">
    <title>Dashboard | Sulu State University</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <header class="dashboard-header">
            <div class="header-content">
                <div class="logo logo-sm">
                    <div class="logo-icon">SSU</div>
                    <div>
                        <h1>Sulu State University</h1>
                        <p>Student Portal</p>
                    </div>
                </div>
                <div class="header-actions">
                    <span class="welcome-text">Welcome, <strong id="header-name"><?= $studentName ?></strong></span>
                    <button type="button" class="btn btn-outline btn-sm" id="logout-btn">Logout</button>
                </div>
            </div>
        </header>

        <main class="dashboard-main">
            <div class="container">
                <div id="alert-container" class="alert-container" role="alert" aria-live="polite"></div>

                <div class="dashboard-grid">
                    <section class="profile-card" id="profile-view">
                        <div class="card-header">
                            <h2>My Profile</h2>
                            <button type="button" class="btn btn-secondary btn-sm" id="edit-profile-btn">Edit Profile</button>
                        </div>

                        <div class="profile-content" id="profile-content">
                            <div class="profile-avatar">
                                <span id="avatar-initials">--</span>
                            </div>
                            <dl class="profile-details">
                                <div class="detail-item">
                                    <dt>Full Name</dt>
                                    <dd id="view-full-name">Loading...</dd>
                                </div>
                                <div class="detail-item">
                                    <dt>Contact Number</dt>
                                    <dd id="view-contact-number">Loading...</dd>
                                </div>
                                <div class="detail-item">
                                    <dt>Email Address</dt>
                                    <dd id="view-email">Loading...</dd>
                                </div>
                                <div class="detail-item">
                                    <dt>Member Since</dt>
                                    <dd id="view-created-at">Loading...</dd>
                                </div>
                            </dl>
                        </div>
                    </section>

                    <section class="profile-card" id="profile-edit" hidden>
                        <div class="card-header">
                            <h2>Edit Profile</h2>
                            <button type="button" class="btn btn-outline btn-sm" id="cancel-edit-btn">Cancel</button>
                        </div>

                        <form id="edit-profile-form" novalidate>
                            <input type="hidden" name="csrf_token" value="<?= sanitizeOutput($csrfToken) ?>">

                            <div class="form-group">
                                <label for="edit_full_name">Full Name</label>
                                <input type="text" id="edit_full_name" name="full_name" required>
                                <span class="field-error" id="edit_full_name-error"></span>
                            </div>

                            <div class="form-group">
                                <label for="edit_contact_number">Contact Number</label>
                                <input type="tel" id="edit_contact_number" name="contact_number" required>
                                <span class="field-error" id="edit_contact_number-error"></span>
                            </div>

                            <div class="form-group">
                                <label for="edit_email">Email Address</label>
                                <input type="email" id="edit_email" name="email" required>
                                <span class="field-error" id="edit_email-error"></span>
                            </div>

                            <hr class="section-divider">

                            <h3 class="section-title">Change Password <span class="optional">(optional)</span></h3>

                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <div class="password-wrapper">
                                    <input type="password" id="current_password" name="current_password" autocomplete="current-password">
                                    <button type="button" class="toggle-password" aria-label="Toggle password visibility" data-target="current_password">
                                        <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                </div>
                                <span class="field-error" id="current_password-error"></span>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" id="new_password" name="new_password" autocomplete="new-password">
                                        <button type="button" class="toggle-password" aria-label="Toggle password visibility" data-target="new_password">
                                            <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="field-error" id="new_password-error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password">
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

                            <button type="submit" class="btn btn-primary" id="save-profile-btn">
                                <span class="btn-text">Save Changes</span>
                                <span class="btn-loader" hidden></span>
                            </button>
                        </form>
                    </section>
                </div>
            </div>
        </main>

        <footer class="dashboard-footer">
            <p>&copy; <?= date('Y') ?> Sulu State University. All rights reserved.</p>
        </footer>
    </div>

    <input type="hidden" id="csrf-token" value="<?= sanitizeOutput($csrfToken) ?>">

    <script src="assets/js/app.js"></script>
    <script src="assets/js/dashboard.js"></script>
</body>
</html>
