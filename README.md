# Sulu State University - Student Portal

A responsive web application for student registration, login, and profile management at Sulu State University.

## Features

- **Student Registration** — Full name, contact number, email, and password with confirmation
- **Secure Login** — Email and password authentication with bcrypt hashing
- **Student Dashboard** — View and edit profile information
- **Security** — CSRF protection, prepared statements (SQL injection prevention), XSS sanitization, secure sessions

## Requirements

- XAMPP (Apache + MySQL + PHP 8.0+)
- Modern web browser

## Setup Instructions

### 1. Start XAMPP

Open the XAMPP Control Panel and start **Apache** and **MySQL**.

### 2. Create the Database

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Import or run the SQL file at `database/schema.sql`
3. This creates the `ssu_student_portal` database and `students` table

Alternatively, run from the MySQL CLI:

```bash
mysql -u root -p < "database/schema.sql"
```

### 3. Configure Database (if needed)

Edit `config/database.php` if your MySQL credentials differ from the defaults:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ssu_student_portal');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Access the Application

Open your browser and navigate to:

```
http://localhost/Student%20Login%20Form/
```

Or go directly to:

- **Login:** `http://localhost/Student%20Login%20Form/login.php`
- **Register:** `http://localhost/Student%20Login%20Form/register.php`

## Project Structure

```
├── api/
│   ├── login.php           # Login endpoint
│   ├── register.php        # Registration endpoint
│   ├── logout.php          # Logout endpoint
│   ├── profile.php         # Get profile data
│   └── update-profile.php  # Update profile
├── assets/
│   ├── css/style.css       # Styles
│   └── js/                 # Client-side scripts
├── config/
│   └── database.php        # Database connection
├── database/
│   └── schema.sql          # Database schema
├── includes/
│   ├── functions.php       # Validation & utilities
│   └── session.php         # Session management
├── dashboard.php           # Student dashboard
├── index.php               # Entry redirect
├── login.php               # Login page
└── register.php            # Registration page
```

## Security Features

| Feature | Implementation |
|---------|----------------|
| Password hashing | bcrypt via `password_hash()` |
| SQL injection | PDO prepared statements |
| XSS prevention | `htmlspecialchars()` output encoding |
| CSRF protection | Token validation on all POST requests |
| Session security | HttpOnly, SameSite, strict mode cookies |
| Input validation | Server-side and client-side validation |

## Password Requirements

- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number

## License

Built for educational purposes — Sulu State University.
