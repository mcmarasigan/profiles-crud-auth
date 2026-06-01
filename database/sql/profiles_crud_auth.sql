-- ============================================================
-- Profiles CRUD Auth — Database Structure
-- Generated for: profiles_crud_auth
-- PHP 8 + Laravel 13 + Tailwind CSS
-- ============================================================

CREATE DATABASE IF NOT EXISTS `profiles_crud_auth`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `profiles_crud_auth`;

-- ============================================================
-- Table: users
-- Stores all user accounts with profile data and roles.
-- Passwords are bcrypt-hashed (never stored in plain text).
-- soft_delete: deleted_at allows restoring deleted records.
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id`                BIGINT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `name`              VARCHAR(100)       NOT NULL,
    `email`             VARCHAR(255)       NOT NULL,
    `email_verified_at` TIMESTAMP          NULL     DEFAULT NULL,
    `password`          VARCHAR(255)       NOT NULL COMMENT 'bcrypt hashed password',
    `role`              ENUM('admin','user') NOT NULL DEFAULT 'user' COMMENT 'Role-based access control',
    `avatar`            VARCHAR(255)       NULL     DEFAULT NULL COMMENT 'Stored filename in public/avatars/',
    `bio`               TEXT               NULL     DEFAULT NULL,
    `phone`             VARCHAR(20)        NULL     DEFAULT NULL,
    `address`           VARCHAR(255)       NULL     DEFAULT NULL,
    `remember_token`    VARCHAR(100)       NULL     DEFAULT NULL,
    `deleted_at`        TIMESTAMP          NULL     DEFAULT NULL COMMENT 'Soft delete timestamp',
    `created_at`        TIMESTAMP          NULL     DEFAULT NULL,
    `updated_at`        TIMESTAMP          NULL     DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: password_reset_tokens
-- Stores hashed tokens for password reset links.
-- Tokens expire after 60 minutes (enforced in PHP).
-- ============================================================
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
    `email`      VARCHAR(255) NOT NULL,
    `token`      VARCHAR(255) NOT NULL COMMENT 'bcrypt hashed token',
    `created_at` TIMESTAMP    NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: sessions
-- Stores PHP session data when using 'database' session driver.
-- (App uses 'file' driver by default, this is kept for reference)
-- ============================================================
CREATE TABLE IF NOT EXISTS `sessions` (
    `id`            VARCHAR(255)     NOT NULL,
    `user_id`       BIGINT UNSIGNED  NULL DEFAULT NULL,
    `ip_address`    VARCHAR(45)      NULL DEFAULT NULL,
    `user_agent`    TEXT             NULL DEFAULT NULL,
    `payload`       LONGTEXT         NOT NULL,
    `last_activity` INT              NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: cache
-- Used by Laravel cache when using 'database' cache driver.
-- ============================================================
CREATE TABLE IF NOT EXISTS `cache` (
    `key`        VARCHAR(255) NOT NULL,
    `value`      MEDIUMTEXT   NOT NULL,
    `expiration` INT          NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
    `key`        VARCHAR(255) NOT NULL,
    `owner`      VARCHAR(255) NOT NULL,
    `expiration` INT          NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Seed Data: Default Admin Account
-- Password: Admin@1234 (bcrypt hashed below)
-- CHANGE THIS PASSWORD immediately after first login!
-- ============================================================
INSERT INTO `users` (`name`, `email`, `password`, `role`, `bio`, `created_at`, `updated_at`)
VALUES (
    'Administrator',
    'admin@profilesauth.local',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- Admin@1234
    'admin',
    'Default system administrator account.',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE `id` = `id`;

-- ============================================================
-- Sample Users (Password: User@1234)
-- ============================================================
INSERT INTO `users` (`name`, `email`, `password`, `role`, `bio`, `created_at`, `updated_at`) VALUES
('Maria Santos',   'maria@example.com',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Web developer from Manila.', NOW(), NOW()),
('Juan dela Cruz', 'juan@example.com',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Full-stack developer.',       NOW(), NOW()),
('Ana Reyes',      'ana@example.com',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'UI/UX Designer.',             NOW(), NOW()),
('Carlos Garcia',  'carlos@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Backend engineer.',           NOW(), NOW())
ON DUPLICATE KEY UPDATE `id` = `id`;
