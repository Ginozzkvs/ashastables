-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 01:39 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asha_membership`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` enum('minutes','times') NOT NULL DEFAULT 'minutes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `name`, `unit`, `created_at`, `updated_at`) VALUES
(1, 'Horse riding', 'times', '2026-01-16 03:43:51', '2026-01-16 03:43:51'),
(2, 'entry ticket', 'times', '2026-01-16 03:44:24', '2026-01-16 03:44:24'),
(3, 'Free drink', 'times', '2026-01-16 03:44:49', '2026-01-16 03:44:49'),
(4, 'Discount 5% (Room)', 'times', '2026-01-16 03:46:12', '2026-01-16 03:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_role` varchar(255) NOT NULL,
  `member_id` varchar(255) DEFAULT NULL,
  `card_uid` varchar(255) NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `minutes` int(11) NOT NULL DEFAULT 0,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `success` tinyint(1) NOT NULL DEFAULT 1,
  `message` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_role`, `member_id`, `card_uid`, `activity_id`, `minutes`, `used_at`, `success`, `message`, `created_at`, `updated_at`) VALUES
(6, 1, 'admin', 'AS0001', '3896727231', 1, 0, '2026-01-16 13:31:45', 1, 'Activity used successfully', '2026-01-16 06:31:45', '2026-01-16 06:31:45'),
(7, 1, 'admin', 'AS0001', '3896727231', 2, 0, '2026-01-16 13:48:12', 1, 'Activity used successfully', '2026-01-16 06:48:12', '2026-01-16 06:48:12'),
(8, 1, 'admin', 'AS0001', '3896727231', 3, 0, '2026-01-16 13:48:37', 1, 'Activity used successfully', '2026-01-16 06:48:37', '2026-01-16 06:48:37'),
(9, 1, 'admin', 'AS0001', '3896727231', 4, 0, '2026-01-16 13:50:59', 1, 'Activity used successfully', '2026-01-16 06:50:59', '2026-01-16 06:50:59'),
(10, 2, 'staff', 'AS0001', '3896727231', 1, 0, '2026-01-20 11:07:45', 1, 'Activity used successfully', '2026-01-20 04:07:45', '2026-01-20 04:07:45'),
(11, 2, 'staff', 'AS0001', '3896727231', 2, 0, '2026-01-20 11:16:28', 1, 'Activity used successfully', '2026-01-20 04:16:28', '2026-01-20 04:16:28'),
(12, 2, 'staff', 'AS0001', '3896727231', 3, 0, '2026-01-20 11:18:53', 1, 'Activity used successfully', '2026-01-20 04:18:53', '2026-01-20 04:18:53'),
(13, 2, 'staff', 'AS0001', '3896727231', 4, 0, '2026-01-20 11:25:54', 1, 'Activity used successfully', '2026-01-20 04:25:54', '2026-01-20 04:25:54');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-admin@test|127.0.0.1', 'i:1;', 1768897819),
('laravel-cache-admin@test|127.0.0.1:timer', 'i:1768897819;', 1768897819);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `card_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `card_uid` varchar(255) DEFAULT NULL,
  `membership_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL COMMENT 'Membership expiry date',
  `renewed_at` timestamp NULL DEFAULT NULL COMMENT 'Last renewal date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`card_id`, `name`, `phone`, `card_uid`, `membership_id`, `start_date`, `active`, `created_at`, `updated_at`, `email`, `expiry_date`, `renewed_at`) VALUES
('AS0001', 'souphakone khamvongsa', '55555555', '3896727231', 1, '2026-01-16', 1, '2026-01-16 06:31:31', '2026-01-20 02:44:14', NULL, '2027-01-20', '2026-01-20 02:44:14');

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `name`, `price`, `duration_days`, `created_at`, `updated_at`) VALUES
(1, 'Premium Annual', 120.00, 365, '2026-01-16 03:47:05', '2026-01-16 03:47:05');

-- --------------------------------------------------------

--
-- Table structure for table `membership_activity_limits`
--

CREATE TABLE `membership_activity_limits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `membership_id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `max_per_year` int(11) NOT NULL,
  `max_per_day` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `membership_activity_limits`
--

INSERT INTO `membership_activity_limits` (`id`, `membership_id`, `activity_id`, `max_per_year`, `max_per_day`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 12, 1, '2026-01-16 04:28:02', '2026-01-16 04:28:02'),
(2, 1, 2, 365, 1, '2026-01-16 04:28:02', '2026-01-16 04:28:02'),
(3, 1, 3, 365, 1, '2026-01-16 04:28:02', '2026-01-16 04:28:02'),
(4, 1, 4, 365, 1, '2026-01-16 04:28:02', '2026-01-16 04:28:02');

-- --------------------------------------------------------

--
-- Table structure for table `members_new`
--

CREATE TABLE `members_new` (
  `card_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `card_uid` varchar(255) DEFAULT NULL,
  `membership_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_activity_balances`
--

CREATE TABLE `member_activity_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` varchar(255) DEFAULT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `remaining_count` int(11) NOT NULL,
  `used_today` int(11) NOT NULL DEFAULT 0,
  `last_used_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_activity_balances`
--

INSERT INTO `member_activity_balances` (`id`, `member_id`, `activity_id`, `remaining_count`, `used_today`, `last_used_date`, `created_at`, `updated_at`) VALUES
(13, 'AS0001', 1, 11, 1, '2026-01-20', '2026-01-16 06:31:31', '2026-01-20 04:07:45'),
(14, 'AS0001', 2, 364, 1, '2026-01-20', '2026-01-16 06:31:31', '2026-01-20 04:16:28'),
(15, 'AS0001', 3, 364, 1, '2026-01-20', '2026-01-16 06:31:31', '2026-01-20 04:18:53'),
(16, 'AS0001', 4, 364, 1, '2026-01-20', '2026-01-16 06:31:31', '2026-01-20 04:25:54');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(11, '2025_12_19_072422_create_activity_logs_table', 2),
(12, '2025_12_23_123335_create_activity_logs_table', 3),
(14, '0001_01_01_000000_create_users_table', 4),
(15, '0001_01_01_000001_create_cache_table', 4),
(16, '0001_01_01_000002_create_jobs_table', 4),
(17, '2025_12_17_091652_create_memberships_table', 4),
(18, '2025_12_17_093532_create_activities_table', 4),
(19, '2025_12_17_093834_create_members_table', 4),
(20, '2025_12_17_094012_create_membership_activity_limits_table', 4),
(21, '2025_12_17_094118_create_member_activity_balances_table', 4),
(22, '2025_12_17_094156_create_activity_logs_table', 4),
(23, '2025_12_17_162541_create_activity_usages_table', 4),
(24, '2026_01_04_112316_add_email_to_members_table', 4),
(25, '2026_01_06_155112_change_daily_minutes_to_max_per_day_in_membership_activity_limits', 4),
(26, '2026_01_07_062702_fix_member_activity_balances_times', 4),
(27, '2026_01_07_143219_add_card_uid_to_members_table', 4),
(28, '2026_01_08_150109_update_activity_logs_table', 4),
(29, '2026_01_08_173910_fix_daily_limit_default', 4),
(30, '2026_01_08_175959_fix_daily_limit_default', 4),
(31, '2026_01_16_add_card_id_to_members_table', 4),
(32, '2026_01_16_fix_members_table_schema', 5),
(33, '2026_01_16_add_card_id_to_members', 6),
(35, '2026_01_16_drop_card_id_column', 7),
(36, '2026_01_16_make_card_id_primary_key', 7),
(37, '2026_01_16_rename_id_to_card_id', 8),
(38, '2026_01_16_update_member_references_to_card_id', 9),
(40, '2026_01_16_add_activity_limits_to_memberships', 11),
(41, '2026_01_16_create_activity_balances_for_existing_members', 12),
(42, '2026_01_16_update_activity_limits', 13),
(43, '2026_01_16_update_member_activity_balances', 14),
(44, '2026_01_16_drop_daily_limit_from_balances', 15),
(45, '2026_01_16_add_updated_at_to_activity_logs', 16),
(46, '2026_01_16_make_user_id_nullable_in_activity_logs', 17),
(47, '2026_01_20_082200_add_expiry_date_to_members_table', 18),
(48, '2026_01_20_093312_drop_end_date_from_members_table', 19);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('gL2j46fgMwgKDRrjS1b3mijgQRWae8digJ9QdBie', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiM2NXWGZ1NllyOHI3bndha1FGREQ5dDhnSHEzS2RWT3FWYTRsQk5jYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdGFmZi9zY2FuIjtzOjU6InJvdXRlIjtzOjEwOiJzdGFmZi5zY2FuIjt9czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1768908468);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'staff',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@test.com', NULL, '$2y$12$hKEh03EwjqEBDEioTvlsDObeLG3Yv5bP9cATuOeT7yp3vE2LPcRTO', 'admin', 1, NULL, '2026-01-16 02:51:10', '2026-01-16 02:51:10'),
(2, 'Staff User', 'staff@test.com', NULL, '$2y$12$TleZ1jprfm/SWNT1wYQLQ.X6sZq7GIrZ5MciWupo4n/IcrfpJ7B0a', 'staff', 1, NULL, '2026-01-19 08:32:17', '2026-01-19 08:32:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_member_id_foreign` (`member_id`),
  ADD KEY `activity_logs_activity_id_foreign` (`activity_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`card_id`),
  ADD UNIQUE KEY `members_card_id_unique` (`card_id`),
  ADD UNIQUE KEY `members_email_unique` (`email`),
  ADD UNIQUE KEY `members_card_uid_unique` (`card_uid`),
  ADD KEY `members_membership_id_foreign` (`membership_id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership_activity_limits`
--
ALTER TABLE `membership_activity_limits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membership_activity_limits_membership_id_foreign` (`membership_id`),
  ADD KEY `membership_activity_limits_activity_id_foreign` (`activity_id`);

--
-- Indexes for table `members_new`
--
ALTER TABLE `members_new`
  ADD PRIMARY KEY (`card_id`),
  ADD UNIQUE KEY `members_new_email_unique` (`email`),
  ADD UNIQUE KEY `members_new_card_uid_unique` (`card_uid`),
  ADD KEY `members_new_membership_id_foreign` (`membership_id`);

--
-- Indexes for table `member_activity_balances`
--
ALTER TABLE `member_activity_balances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_activity_balances_member_id_foreign` (`member_id`),
  ADD KEY `member_activity_balances_activity_id_foreign` (`activity_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `membership_activity_limits`
--
ALTER TABLE `membership_activity_limits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `member_activity_balances`
--
ALTER TABLE `member_activity_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `membership_activity_limits`
--
ALTER TABLE `membership_activity_limits`
  ADD CONSTRAINT `membership_activity_limits_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `membership_activity_limits_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `members_new`
--
ALTER TABLE `members_new`
  ADD CONSTRAINT `members_new_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_activity_balances`
--
ALTER TABLE `member_activity_balances`
  ADD CONSTRAINT `member_activity_balances_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
