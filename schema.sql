-- ============================================================================
-- Melu'e Foundation E-Library System
-- Database schema (structure only - no data, no credentials)
-- ----------------------------------------------------------------------------
-- Compatible with: MySQL 5.7+ / MariaDB 10.4+
-- Charset:        utf8mb4 / utf8mb4_general_ci
--
-- Import with: mysql -u root -p elibrary < schema.sql
-- or via phpMyAdmin: Import -> select schema.sql
-- ============================================================================

-- Optional: create the database first and select it.
-- CREATE DATABASE IF NOT EXISTS `elibrary`
--   DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `elibrary`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ----------------------------------------------------------------------------
-- Table: admin
-- Administrator accounts used by the admin dashboard login.
-- Password should be handled with password_hash()/password_verify().
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- Table: users
-- Registered Telegram members / library patrons.
-- registration_status: 0 = pending approval, 1 = approved.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `telegram_id` bigint(20) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `valid_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `registration_status` tinyint(1) NOT NULL,
  `preferred_language` varchar(20) DEFAULT 'English'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- Table: books
-- E-book catalog; `id` is a short generated voucher code (e.g. 'BplN08s').
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `books` (
  `id` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `year_published` int(11) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- Table: issued_books
-- Physical book circulation records + member waitlist.
-- wait_list: 1 = waiting, 0 = no longer waiting.
-- rented_book: 1 = currently rented by the member.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `issued_books` (
  `id` int(11) NOT NULL,
  `issuer_name` varchar(255) NOT NULL,
  `user_id` bigint(11) NOT NULL,
  `wait_list` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `year_published` int(11) NOT NULL,
  `unique_id` varchar(255) NOT NULL,
  `due_date` date NOT NULL,
  `issued_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `rented_book` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- Table: messages
-- Notification queue consumed by the Telegram bot.
-- message_type: 'user_msg' (general notice) or 'rented_book_msg' (rental info).
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL,
  `user_id` bigint(11) NOT NULL,
  `issuer_name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `message_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- Table: announcement
-- Announcements published from the dashboard and broadcast by the bot.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `announcement` (
  `id` int(11) NOT NULL,
  `Author_name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================================
-- Primary keys & indexes
-- ============================================================================

ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`telegram_id`),
  ADD UNIQUE KEY `telegram_id_2` (`telegram_id`),
  ADD KEY `id` (`id`),
  ADD KEY `telegram_id` (`telegram_id`);

ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `issued_books`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

ALTER TABLE `messages`
  ADD PRIMARY KEY (`user_id`);

ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`);

-- ============================================================================
-- AUTO_INCREMENT
-- ============================================================================

ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `issued_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- ============================================================================
-- OPTIONAL: bootstrap an initial admin account.
-- Replace 'CHANGE_ME' with a strong password BEFORE first login, then run:
--     mysql -u root -p elibrary -e "UPDATE admin SET password='yourhash' WHERE username='admin';"
-- The dashboard compares passwords directly today; migrate to
-- password_hash()/password_verify() before any public deployment.
-- ============================================================================

-- INSERT INTO `admin` (`id`, `username`, `password`) VALUES
-- (1, 'admin', 'CHANGE_ME');

COMMIT;