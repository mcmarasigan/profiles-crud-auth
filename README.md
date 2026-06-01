# profiles-crud-auth

A user profile management system built with PHP 8, Laravel 13, and Tailwind CSS. Built as a practical coding assessment.

## Features

| Feature | Details |
|---|---|
| Authentication | Session-based login and registration with CSRF protection |
| Security | bcrypt password hashing, XSS prevention, SQL injection prevention via Eloquent ORM |
| CRUD Dashboard | Create, read, update, and delete user profiles with search and pagination |
| Role-Based Access | Admins manage all profiles; regular users manage only their own |
| Password Reset | Email-based reset with a secure 64-character token, expires in 60 minutes |
| Stats Panel | Live counts of total users, admins, and regular users |
| Avatar Upload | Profile photo upload with live preview |
| Session Timeout | 30-minute session lifetime with secure cookie flags |
| Rate Limiting | Brute-force protection on login: 5 attempts per minute per IP and email |
| Password Eye Toggle | Show/hide password on all password fields |
| Password Strength | Live strength meter on registration and profile forms |

## Tech Stack

- **Backend:** PHP 8.3 + Laravel 13
- **Frontend:** Blade templates + Tailwind CSS v4 + Vanilla JavaScript
- **Database:** MySQL 8
- **Authentication:** Laravel session-based auth (manual, no Breeze/Jetstream)

---

## Setup Instructions

### 1. Prerequisites

- PHP 8.1+ with extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- Composer 2.x
- Node.js 18+ and NPM
- MySQL 8.x

### 2. Clone and Install

```bash
git clone https://github.com/mcmarasigan/profiles-crud-auth.git
cd profiles-crud-auth

composer install
npm install
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update your database and email credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=profiles_crud_auth
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=your_gmail_address@gmail.com
MAIL_PASSWORD=your_16_letter_app_password
MAIL_ENCRYPTION=smtps
MAIL_FROM_ADDRESS=your_gmail_address@gmail.com
```

### 4. Create the Database

```sql
CREATE DATABASE profiles_crud_auth CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Option A — Migrations (recommended):**

```bash
php artisan migrate --seed
```

**Option B — SQL file:**

```bash
mysql -u root -p profiles_crud_auth < database/sql/profiles_crud_auth.sql
```

### 5. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 6. Run

```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## Default Login Credentials

| Role | Email | Password |
|---|---|---|
| Admin | admin@profilesauth.local | `Admin@1234` |
| User | maria@example.com | `User@1234` |

> Change these immediately after first login.

---

## Security Implementation

### Password Hashing
Passwords use bcrypt at 12 rounds via `Hash::make()`. Verification uses `Hash::check()`, which calls PHP's `password_verify()` internally.

### SQL Injection Prevention
All queries go through Laravel Eloquent ORM, which uses PDO prepared statements. Raw queries use parameter binding.

### XSS Prevention
- Blade `{{ }}` auto-escapes output with `htmlspecialchars`
- User input stored to the database is sanitized with `strip_tags()`

### CSRF Protection
All POST, PUT, and DELETE forms include `@csrf`. Laravel verifies the token on every state-changing request.

### Session Security
- Lifetime: 30 minutes
- Session encryption enabled
- HttpOnly cookies enabled
- SameSite: Lax
- Session ID regenerated on login to prevent session fixation
- Session invalidated on logout

### Rate Limiting
Login is limited to 5 attempts per minute per IP and email using Laravel's `RateLimiter` facade.

---

## Project Structure

```
profiles-crud-auth/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   └── PasswordResetController.php
│   │   │   └── ProfileController.php
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   │   └── Requests/
│   │       ├── LoginRequest.php
│   │       ├── RegisterRequest.php
│   │       └── ProfileRequest.php
│   └── Models/
│       └── User.php
├── database/
│   ├── migrations/
│   ├── seeders/DatabaseSeeder.php
│   └── sql/profiles_crud_auth.sql
├── resources/
│   ├── css/app.css
│   └── views/
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── forgot-password.blade.php
│       │   └── reset-password.blade.php
│       ├── dashboard/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── emails/
│       │   └── password-reset.blade.php
│       └── layouts/
│           ├── app.blade.php
│           └── dashboard.blade.php
└── routes/
    └── web.php
```

---

## Password Requirements

- At least 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- At least one special character

---

## Testing the App

1. Register a new account at `/register`
2. Log in with admin credentials to see all profiles
3. Create a new profile (admin only)
4. Edit any profile (admin) or your own (regular user)
5. Delete a profile (admin only, with confirmation modal)
6. Test password reset: click "Forgot password?" and enter your email. A real reset link will be sent to your Gmail inbox!

---

## License

MIT
