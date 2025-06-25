-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 01:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_food`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_07_113547_create_products_table', 1),
(5, '2025_06_07_113558_create_orders_table', 1),
(6, '2025_06_07_113611_create_order_items_table', 1),
(7, '2025_06_07_142656_add_is_best_seller_to_products_table', 1),
(8, '2025_06_07_150829_add_slug_to_products_table', 1),
(9, '2025_06_09_084743_create_product_variants_table', 1),
(10, '2025_06_09_110545_add_time_estimation_to_products_table', 1),
(11, '2025_06_10_184011_add_customer_name_to_orders_table', 1),
(12, '2025_06_11_092527_create_saved_cards_table', 1),
(13, '2025_06_12_005116_add_role_to_users_table', 1),
(14, '2025_06_13_184305_add_rating_to_products_table', 1),
(15, '2025_06_14_083545_add_order_type_to_orders_table', 1),
(16, '2025_06_14_085321_create_tables_table', 1),
(17, '2025_06_14_085418_modify_orders_for_table_relation', 1),
(18, '2025_06_14_110853_create_promotions_table', 1),
(19, '2025_06_19_201132_create_promotions_table', 2),
(20, '2025_06_20_105549_add_promotion_fields_to_order_items_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `order_type` enum('dine_in','take_away') NOT NULL DEFAULT 'dine_in',
  `table_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','completed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `customer_name`, `order_type`, `table_id`, `total_amount`, `status`, `payment_method`, `created_at`, `updated_at`) VALUES
(1, 'POS-ODOOOB', 'Sekar Arum Rolla Destiani', 'dine_in', NULL, 22200.00, 'pending', 'ewallet', '2025-06-18 17:22:12', '2025-06-18 17:22:12'),
(4, 'POS-7ME0FV', 'Sekar Arum Rolla Destiani', 'dine_in', 1, 22200.00, 'paid', 'epayment', '2025-06-18 17:24:58', '2025-06-18 17:25:19'),
(5, 'POS-VJQN3Z', 'Sekar Arum Rolla Destiani', 'dine_in', 1, 48840.00, 'paid', 'ewallet', '2025-06-18 17:26:04', '2025-06-18 17:30:24'),
(6, 'POS-BFAJ6D', 'Sekar Arum Rolla Destiani', 'take_away', NULL, 16650.00, 'completed', 'cashier', '2025-06-18 17:41:29', '2025-06-19 12:48:26'),
(7, 'POS-DABWVH', 'Sekar Arum Rolla Destiani', 'dine_in', NULL, 48840.00, 'completed', 'ewallet', '2025-06-19 12:20:15', '2025-06-19 12:47:21'),
(8, 'POS-75YFXQ', 'Sekar Arum Rolla Destiani', 'dine_in', NULL, 16650.00, 'completed', 'ewallet', '2025-06-19 12:22:19', '2025-06-19 12:47:05'),
(27, 'POS-IINZXC', 'Putra Aditya Priyono', 'dine_in', NULL, 31080.00, 'completed', 'ewallet', '2025-06-20 06:55:38', '2025-06-20 07:02:41'),
(28, 'POS-LNYFV1', 'Putra Aditya Priyono', 'dine_in', NULL, 31080.00, 'paid', 'ewallet', '2025-06-20 07:35:11', '2025-06-20 07:35:14'),
(29, 'POS-2MBIXQ', 'Sekar Arum Rolla Destiani', 'dine_in', 1, 31080.00, 'paid', 'ewallet', '2025-06-20 07:43:02', '2025-06-20 07:43:05'),
(30, 'POS-SHU3OB', 'Putra Aditya Priyono', 'dine_in', 1, 31080.00, 'paid', 'epayment', '2025-06-20 07:43:25', '2025-06-20 07:43:41'),
(31, 'POS-2XW3CT', 'Sekar Arum Rolla Destiani', 'dine_in', 1, 31080.00, 'paid', 'ewallet', '2025-06-20 07:46:41', '2025-06-20 07:46:44'),
(32, 'POS-NV9C48', 'Sekar Arum Rolla Destiani', 'dine_in', 1, 31080.00, 'completed', 'ewallet', '2025-06-20 07:52:56', '2025-06-20 07:54:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `promotion_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_item` decimal(10,2) NOT NULL,
  `item_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`item_details`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `promotion_id`, `quantity`, `price_per_item`, `item_details`, `created_at`, `updated_at`) VALUES
(1, 1, 4, NULL, 1, 20000.00, NULL, '2025-06-18 17:22:12', '2025-06-18 17:22:12'),
(4, 4, 4, NULL, 1, 20000.00, NULL, '2025-06-18 17:24:58', '2025-06-18 17:24:58'),
(5, 5, 4, NULL, 2, 22000.00, NULL, '2025-06-18 17:26:05', '2025-06-18 17:26:05'),
(6, 6, 4, NULL, 1, 15000.00, NULL, '2025-06-18 17:41:29', '2025-06-18 17:41:29'),
(7, 7, 4, NULL, 2, 22000.00, NULL, '2025-06-19 12:20:15', '2025-06-19 12:20:15'),
(8, 8, 4, NULL, 1, 15000.00, NULL, '2025-06-19 12:22:19', '2025-06-19 12:22:19'),
(10, 27, NULL, NULL, 1, 20000.00, NULL, '2025-06-20 06:55:38', '2025-06-20 06:55:38'),
(11, 27, 5, NULL, 1, 8000.00, NULL, '2025-06-20 06:55:38', '2025-06-20 06:55:38'),
(12, 28, NULL, NULL, 1, 20000.00, NULL, '2025-06-20 07:35:11', '2025-06-20 07:35:11'),
(13, 28, 5, NULL, 1, 8000.00, NULL, '2025-06-20 07:35:11', '2025-06-20 07:35:11'),
(14, 29, NULL, 4, 1, 20000.00, '{\"name\":\"promo\",\"products\":[\"Burger Ipsum\",\"Ice Lemon\"]}', '2025-06-20 07:43:02', '2025-06-20 07:43:02'),
(15, 29, 5, NULL, 1, 8000.00, '{\"name\":\"Ice Lemon\"}', '2025-06-20 07:43:02', '2025-06-20 07:43:02'),
(16, 30, NULL, 4, 1, 20000.00, '{\"name\":\"promo\",\"products\":[\"Burger Ipsum\",\"Ice Lemon\"]}', '2025-06-20 07:43:25', '2025-06-20 07:43:25'),
(17, 30, 5, NULL, 1, 8000.00, '{\"name\":\"Ice Lemon\"}', '2025-06-20 07:43:25', '2025-06-20 07:43:25'),
(18, 31, NULL, 4, 1, 20000.00, '{\"name\":\"promo\",\"products\":[\"Burger Ipsum\",\"Ice Lemon\"]}', '2025-06-20 07:46:41', '2025-06-20 07:46:41'),
(19, 31, 5, NULL, 1, 8000.00, '{\"name\":\"Ice Lemon\"}', '2025-06-20 07:46:41', '2025-06-20 07:46:41'),
(20, 32, NULL, 4, 1, 20000.00, '{\"name\":\"promo\",\"products\":[\"Burger Ipsum\",\"Ice Lemon\"]}', '2025-06-20 07:52:56', '2025-06-20 07:52:56'),
(21, 32, 5, NULL, 1, 8000.00, '{\"name\":\"Ice Lemon\"}', '2025-06-20 07:52:56', '2025-06-20 07:52:56');

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `time_estimation` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` enum('makanan','minuman') NOT NULL,
  `is_best_seller` tinyint(1) NOT NULL DEFAULT 0,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `time_estimation`, `price`, `rating`, `image`, `category`, `is_best_seller`, `is_available`, `created_at`, `updated_at`) VALUES
(4, 'Burger Ipsum', 'burger-ipsum', 'Burger dengan toping ipsum', 15, 15000.00, 4.7, 'products/Mb7xnwCErEVaXRxPOKcdu51raHyk5bTJxioPqVll.png', 'makanan', 1, 1, '2025-06-18 14:06:57', '2025-06-18 14:06:57'),
(5, 'Ice Lemon', 'ice-lemon', 'ice', 5, 8000.00, 4.3, 'products/BWKvGqUy6h4faSe86ND5df1mewlIL5AjWlZf1rWN.png', 'minuman', 1, 1, '2025-06-19 13:38:45', '2025-06-19 13:38:45');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `size` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `size`, `price`, `created_at`, `updated_at`) VALUES
(5, 4, 'Small', 15000.00, '2025-06-18 14:06:57', '2025-06-18 14:06:57'),
(6, 4, 'Regular', 20000.00, '2025-06-18 14:06:57', '2025-06-18 14:06:57'),
(7, 4, 'Large', 22000.00, '2025-06-18 14:06:57', '2025-06-18 14:06:57');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `type` varchar(255) NOT NULL DEFAULT 'special_price',
  `promo_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`promo_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `title`, `description`, `image_path`, `is_active`, `type`, `promo_data`, `created_at`, `updated_at`) VALUES
(4, 'promo', 'promo', 'promotions/hRV4VO0AoxW0WQ1ecq2Pdbvpt3oFyKX6vnu0rQKj.png', 1, 'special_price', '{\"product_ids\":[\"4\",\"5\"],\"package_price\":\"20000\"}', '2025-06-19 14:06:37', '2025-06-19 14:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `saved_cards`
--

CREATE TABLE `saved_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `card_holder_name` varchar(255) NOT NULL,
  `last_four_digits` varchar(255) NOT NULL,
  `expiry_date` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `saved_cards`
--

INSERT INTO `saved_cards` (`id`, `customer_name`, `bank_name`, `card_holder_name`, `last_four_digits`, `expiry_date`, `created_at`, `updated_at`) VALUES
(1, 'Sekar Arum Rolla Destiani', 'BRI', 'Sekar Arum Rolla Destiani', '7566', '10/35', '2025-06-18 17:25:16', '2025-06-18 17:25:16'),
(2, 'Putra Aditya Priyono', 'Mandiri', 'Putra Aditya Priyono', '5345', '10/35', '2025-06-20 07:43:38', '2025-06-20 07:43:38');

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
('1usIaRM5ERYyZSSYrTFUBKMtKL0VJ0xRW4qTVfqM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiSmpZY2RXYVBZV29qcDFGQVNiZWFrcHJuelpDdHBUY2JIdnVPeElkcSI7czo4OiJtZW51X3VybCI7czo0MzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwLz9vcmRlcl90eXBlPXRha2VfYXdheSI7czoxMDoib3JkZXJfdHlwZSI7czo5OiJ0YWtlX2F3YXkiO3M6MTU6Imhhc19zZWVuX3NwbGFzaCI7YjoxO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvP29yZGVyX3R5cGU9dGFrZV9hd2F5Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1750608167),
('ai23VJNlMpoualMhtEt5Ow51yDc0NGplknNU72Sc', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiYzNxemxnbFV6eGVrWlIzdG9CNEJoMGFlYzNzNkQ4Wk14emlpNEtXaSI7czo4OiJtZW51X3VybCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjtzOjE1OiJoYXNfc2Vlbl9zcGxhc2giO2I6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMDoib3JkZXJfdHlwZSI7czo5OiJ0YWtlX2F3YXkiO3M6NDoiY2FydCI7YTo0OntpOjU7YTo1OntzOjEwOiJwcm9kdWN0X2lkIjtpOjU7czo0OiJuYW1lIjtzOjk6IkljZSBMZW1vbiI7czo4OiJxdWFudGl0eSI7aTo1NTtzOjU6InByaWNlIjtzOjc6IjgwMDAuMDAiO3M6MTA6ImltYWdlX3BhdGgiO3M6NTM6InByb2R1Y3RzL0JXS3ZHcVV5Nmg0ZmFTZTg2TkQ1ZGYxbWV3bElMNUFqV2xaZjFyV04ucG5nIjt9czo4OiJidW5kbGVfNCI7YTo2OntzOjQ6Im5hbWUiO3M6NToicHJvbW8iO3M6ODoicXVhbnRpdHkiO2k6MTtzOjU6InByaWNlIjtzOjU6IjIwMDAwIjtzOjEwOiJpbWFnZV9wYXRoIjtzOjU1OiJwcm9tb3Rpb25zL2hSVjRWTzBBb3hXMFdRMWVjcTJQZGJ2cHQzb0Z5S1g2dm51MHJRS2oucG5nIjtzOjk6ImlzX2J1bmRsZSI7YjoxO3M6NToiaXRlbXMiO2E6Mjp7aTowO3M6MTI6IkJ1cmdlciBJcHN1bSI7aToxO3M6OToiSWNlIExlbW9uIjt9fXM6MzoiNC03IjthOjU6e3M6MTA6InByb2R1Y3RfaWQiO2k6NDtzOjQ6Im5hbWUiO3M6MjA6IkJ1cmdlciBJcHN1bSAoTGFyZ2UpIjtzOjg6InF1YW50aXR5IjtzOjE6IjEiO3M6NToicHJpY2UiO3M6ODoiMjIwMDAuMDAiO3M6MTA6ImltYWdlX3BhdGgiO3M6NTM6InByb2R1Y3RzL01iN3hud0NFckVWYVhSeFBPS2NkdTUxcmFIeWs1YlRKeGlvUHFWbGwucG5nIjt9czozOiI0LTUiO2E6NTp7czoxMDoicHJvZHVjdF9pZCI7aTo0O3M6NDoibmFtZSI7czoyMDoiQnVyZ2VyIElwc3VtIChTbWFsbCkiO3M6ODoicXVhbnRpdHkiO2k6MTI7czo1OiJwcmljZSI7czo4OiIxNTAwMC4wMCI7czoxMDoiaW1hZ2VfcGF0aCI7czo1MzoicHJvZHVjdHMvTWI3eG53Q0VyRVZhWFJ4UE9LY2R1NTFyYUh5azViVEp4aW9QcVZsbC5wbmciO319fQ==', 1750607528),
('BmDFqXKxDDXCuKjJvLGg6mu5Tc2a807GmcqAlsjY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiYUtzdlNrM2xnTklrOFE1SmxIVGx1WnlsWXdDazBVdHY1cm9NT2tZbiI7czo4OiJtZW51X3VybCI7czo0MzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwLz9vcmRlcl90eXBlPXRha2VfYXdheSI7czoxNToiaGFzX3NlZW5fc3BsYXNoIjtiOjE7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/b3JkZXJfdHlwZT10YWtlX2F3YXkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjEwOiJvcmRlcl90eXBlIjtzOjk6InRha2VfYXdheSI7czo0OiJjYXJ0IjthOjA6e319', 1750608160),
('f4aTDLwEILSiEFWMBpB8iinKSGVVaTWGttRD7rHB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoic01aOWwwdFgzb0tKdmdFb1NucGo4U1RkUzZURGhDM0MwWlJlWlkwdiI7czo4OiJtZW51X3VybCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjtzOjE1OiJoYXNfc2Vlbl9zcGxhc2giO2I6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMDoib3JkZXJfdHlwZSI7czo5OiJ0YWtlX2F3YXkiO3M6NDoiY2FydCI7YToyOntzOjg6ImJ1bmRsZV80IjthOjY6e3M6NDoibmFtZSI7czo1OiJwcm9tbyI7czo4OiJxdWFudGl0eSI7aToxO3M6NToicHJpY2UiO3M6NToiMjAwMDAiO3M6MTA6ImltYWdlX3BhdGgiO3M6NTU6InByb21vdGlvbnMvaFJWNFZPMEFveFcwV1ExZWNxMlBkYnZwdDNvRnlLWDZ2bnUwclFLai5wbmciO3M6OToiaXNfYnVuZGxlIjtiOjE7czo1OiJpdGVtcyI7YToyOntpOjA7czoxMjoiQnVyZ2VyIElwc3VtIjtpOjE7czo5OiJJY2UgTGVtb24iO319aTo1O2E6NTp7czoxMDoicHJvZHVjdF9pZCI7aTo1O3M6NDoibmFtZSI7czo5OiJJY2UgTGVtb24iO3M6ODoicXVhbnRpdHkiO3M6MToiMSI7czo1OiJwcmljZSI7czo3OiI4MDAwLjAwIjtzOjEwOiJpbWFnZV9wYXRoIjtzOjUzOiJwcm9kdWN0cy9CV0t2R3FVeTZoNGZhU2U4Nk5ENWRmMW1ld2xJTDVBaldsWmYxcldOLnBuZyI7fX19', 1750611637),
('qtL1LKW6TdLbQpWM2ztRBFg5aDobgDxigPmVb4J7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiZnRRQTIzN0s3ejBUV2Z6ZUU1OVhtQm1zVHJBdlZoenRYU0ZGd1ZXQSI7czo4OiJtZW51X3VybCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjtzOjEwOiJvcmRlcl90eXBlIjtzOjk6InRha2VfYXdheSI7czoxNToiaGFzX3NlZW5fc3BsYXNoIjtiOjE7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750608392);

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('available','occupied') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, '01A', 'available', '2025-06-18 14:07:17', '2025-06-20 07:54:17'),
(2, '02A', 'available', '2025-06-18 17:42:26', '2025-06-18 17:42:26');

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
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2025-06-14 05:48:57', '$2y$12$9Qf5lPOR4r/.YY5XS6VVh.kZYjSa0/brbLG2CNEZ.i0zlL8K/A.Qm', 'user', 'MGWHXK7AJp', '2025-06-14 05:48:57', '2025-06-14 05:48:57');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_table_id_foreign` (`table_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_promotion_id_foreign` (`promotion_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_cards`
--
ALTER TABLE `saved_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tables_name_unique` (`name`);

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `saved_cards`
--
ALTER TABLE `saved_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
