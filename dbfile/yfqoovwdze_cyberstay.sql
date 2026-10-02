-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 01, 2026 at 08:31 PM
-- Server version: 11.4.13-MariaDB-cll-lve
-- PHP Version: 8.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yfqoovwdze_cyberstay`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `nights` int(10) UNSIGNED NOT NULL,
  `price_per_night` decimal(12,2) NOT NULL,
  `room_amount` decimal(12,2) NOT NULL,
  `extras_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'reserved',
  `notes` text DEFAULT NULL,
  `checkout_note` text DEFAULT NULL,
  `room_paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `extras_paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `balance_due` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `checked_out_at` timestamp NULL DEFAULT NULL,
  `vehicle_number` varchar(255) DEFAULT NULL,
  `males` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `females` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `children` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `id_card_front` varchar(255) DEFAULT NULL,
  `id_card_back` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `tenant_id`, `room_id`, `customer_id`, `created_by`, `check_in`, `check_out`, `nights`, `price_per_night`, `room_amount`, `extras_amount`, `grand_total`, `status`, `notes`, `checkout_note`, `room_paid_amount`, `extras_paid_amount`, `balance_due`, `payment_status`, `checked_in_at`, `checked_out_at`, `vehicle_number`, `males`, `females`, `children`, `id_card_front`, `id_card_back`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 1, 2, '2026-09-23', '2026-09-26', 3, 1200.00, 3600.00, 0.00, 3600.00, 'checked_out', NULL, 'No', 1200.00, 0.00, 2400.00, 'partial', '2026-09-23 19:12:25', '2026-09-25 18:17:23', 'AYA-986', 2, 0, 0, NULL, NULL, '2026-09-23 19:12:25', '2026-09-25 18:17:23'),
(2, 1, 3, 2, 2, '2026-09-23', '2026-09-24', 1, 1000.00, 1000.00, 0.00, 1000.00, 'checked_out', NULL, 'No', 1000.00, 0.00, 0.00, 'paid', '2026-09-23 20:33:27', '2026-09-25 19:37:19', NULL, 1, 0, 0, NULL, NULL, '2026-09-23 20:33:27', '2026-09-25 19:37:19'),
(3, 1, 1, 3, 2, '2026-09-19', '2026-09-26', 7, 1000.00, 7000.00, 0.00, 7000.00, 'checked_out', NULL, '2000 unpaid', 5000.00, 0.00, 2000.00, 'partial', '2026-09-23 22:58:32', '2026-09-25 17:41:17', NULL, 1, 0, 0, NULL, NULL, '2026-09-23 22:58:32', '2026-09-25 17:41:17'),
(4, 1, 5, 4, 2, '2026-09-23', '2026-09-24', 8, 600.00, 4800.00, 300.00, 5100.00, 'checked_in', NULL, NULL, 4200.00, 0.00, 900.00, 'partial', '2026-09-23 23:01:05', NULL, NULL, 1, 0, 0, NULL, NULL, '2026-09-23 23:01:05', '2026-10-01 04:36:22'),
(5, 1, 1, 3, 2, '2026-09-24', '2026-09-25', 1, 1500.00, 1500.00, 0.00, 1500.00, 'checked_out', 'hgjhghj', 'hhh', 0.00, 0.00, 1500.00, 'unpaid', '2026-09-24 18:09:01', '2026-09-27 22:57:04', NULL, 1, 0, 0, 'id-cards/SJ9ffMrIX60Hjhn4fTN9yJqR1lgrorRwZUbiLcAE.jpg', NULL, '2026-09-24 18:09:01', '2026-09-27 22:57:04'),
(6, 1, 4, 5, 2, '2026-09-25', '2026-09-27', 2, 1000.00, 2000.00, 0.00, 2000.00, 'checked_out', NULL, 'No', 1000.00, 0.00, 1000.00, 'partial', '2026-09-25 17:56:15', '2026-09-26 16:22:36', NULL, 1, 0, 0, 'id-cards/gajOJFurkVJVKICfEeXzpog1HaBVX4oLcgXC69yb.jpg', 'id-cards/t9Vubrcq1lN1KfKnXtLJaDLCCAKT4lLvYyZ8Ullw.jpg', '2026-09-25 17:56:09', '2026-09-26 16:22:36'),
(7, 1, 1, 3, 2, '2026-09-25', '2026-09-26', 1, 1000.00, 1000.00, 0.00, 1000.00, 'checked_in', NULL, NULL, 0.00, 0.00, 1000.00, 'unpaid', '2026-09-25 18:13:46', NULL, NULL, 1, 0, 0, NULL, NULL, '2026-09-25 18:10:14', '2026-09-28 23:20:53'),
(8, 1, 24, 6, 2, '2026-09-25', '2026-09-27', 2, 800.00, 1600.00, 0.00, 1600.00, 'checked_out', NULL, 'No', 800.00, 0.00, 800.00, 'partial', '2026-09-25 19:26:14', '2026-09-26 16:23:14', NULL, 1, 0, 0, 'id-cards/9NZMlMU1RrLWCDXfSawxAVtokkxVkxVo5SiQm38L.jpg', 'id-cards/452hTwE24OhktyCdn1okllqBwF6zE0Qw49EgClgu.jpg', '2026-09-25 19:26:10', '2026-09-26 16:23:14'),
(9, 1, 35, 7, 2, '2026-09-28', '2026-09-29', 1, 1000.00, 1000.00, 0.00, 1000.00, 'checked_out', 'Next time jo khyen gy wo don ga', 'Next jasy kahy gy Wasa rent dy gy', 1000.00, 0.00, 0.00, 'paid', '2026-09-28 20:24:38', '2026-09-29 09:36:25', NULL, 2, 0, 0, NULL, NULL, '2026-09-28 20:24:32', '2026-09-29 09:36:25'),
(10, 1, 6, 8, 2, '2026-09-28', '2026-09-29', 2, 1000.00, 2000.00, 0.00, 2000.00, 'checked_in', NULL, NULL, 0.00, 0.00, 2000.00, 'unpaid', '2026-09-28 21:20:00', NULL, NULL, 1, 0, 0, 'id-cards/ZTwLgjkMC37MBHnCsGtrt4EpwrIaTNgzYAVvRX4z.jpg', 'id-cards/lQX0gvSg7p7xr1mlhlJGc9HVOjYwzLZIVw3cGZ6w.jpg', '2026-09-28 21:19:27', '2026-09-30 05:55:20'),
(11, 1, 36, 9, 2, '2026-09-28', '2026-09-29', 2, 1500.00, 3000.00, 0.00, 3000.00, 'checked_in', NULL, NULL, 3000.00, 0.00, 0.00, 'paid', '2026-09-28 21:53:13', NULL, NULL, 1, 0, 0, 'id-cards/39UiOWpTzg86A3ATXHUrSQk4CoPG393aXvdGv7BG.jpg', 'id-cards/dueuUFxgWqe28C8Y4E2tBbx7DFC1mHaLQCsF2G5N.jpg', '2026-09-28 21:52:56', '2026-09-29 18:49:06'),
(12, 1, 24, 10, 2, '2026-09-28', '2026-09-30', 2, 1500.00, 3000.00, 0.00, 3000.00, 'checked_in', NULL, NULL, 3000.00, 0.00, 0.00, 'paid', '2026-09-28 21:58:38', NULL, NULL, 1, 0, 0, 'id-cards/qqLISNPApRd5xWuPu96hp8muA4n7lbRSRB7B9GvZ.jpg', 'id-cards/V1cdDdLHYmy6v4IhgHajxWeMHWaSx9yqJiU2chSr.jpg', '2026-09-28 21:58:21', '2026-09-29 18:49:51'),
(13, 1, 3, 11, 3, '2026-09-29', '2026-10-02', 3, 1200.00, 3600.00, 0.00, 3600.00, 'checked_out', NULL, '300', 3600.00, 0.00, 0.00, 'paid', '2026-09-29 19:06:42', '2026-10-02 01:29:26', NULL, 2, 0, 0, NULL, NULL, '2026-09-29 19:06:00', '2026-10-02 01:29:26'),
(14, 1, 4, 12, 3, '2026-09-29', '2026-09-30', 1, 700.00, 700.00, 0.00, 700.00, 'checked_in', NULL, NULL, 700.00, 0.00, 0.00, 'paid', '2026-09-29 22:15:49', NULL, NULL, 1, 0, 0, 'id-cards/2MwyHCpArC4SszFsIJ7n4pAxDmrdquLj4T4gUvfk.jpg', 'id-cards/3H5UBSuyq93dbzJ2RnqZkUXAytFg9BZ0rLeNnOzA.jpg', '2026-09-29 22:15:46', '2026-09-29 22:15:49'),
(15, 1, 23, 13, 3, '2026-09-29', '2026-10-02', 3, 1000.00, 3000.00, 0.00, 3000.00, 'checked_out', NULL, '2days stay', 2000.00, 0.00, 1000.00, 'partial', '2026-09-30 00:52:54', '2026-10-01 21:58:29', NULL, 1, 0, 0, 'id-cards/NRiq7utyTcd4rGcv6pZIDpQ7N3UusStEP52qSCZx.jpg', 'id-cards/hxSIVzuMsPvcqzPzNl9JbLa1gAAhS5mn33mHcZUZ.jpg', '2026-09-30 00:52:48', '2026-10-01 21:58:29'),
(16, 1, 26, 14, 3, '2026-10-01', '2026-10-02', 1, 2000.00, 2000.00, 0.00, 2000.00, 'checked_in', 'Non Ac', NULL, 2000.00, 0.00, 0.00, 'paid', '2026-10-01 21:57:34', NULL, NULL, 2, 0, 0, 'id-cards/hziKPwqZNtWuJZDnYHJb0y5YMR5zQoaxdKrM1wPx.jpg', 'id-cards/MJcIzHnxmJJT2ahKap0vK2oxzqGDAIVzKWSDk7HV.jpg', '2026-10-01 21:53:06', '2026-10-01 21:57:34');

-- --------------------------------------------------------

--
-- Table structure for table `booking_companions`
--

CREATE TABLE `booking_companions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `cnic` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `id_card_front` varchar(255) DEFAULT NULL,
  `id_card_back` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_companions`
--

INSERT INTO `booking_companions` (`id`, `tenant_id`, `booking_id`, `name`, `father_name`, `cnic`, `address`, `id_card_front`, `id_card_back`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Awais shoaib S/O Muhammad shoaib', NULL, '3410476585173', NULL, NULL, NULL, '2026-09-23 19:12:25', '2026-09-23 19:12:25'),
(2, 1, 11, 'Muhammad ramzan Ali', 'Zakar Ali', '42401-3699503-1', 'House num 24 muhala orangi town secter 5-f-2 karachi', 'id-cards/iaJl4MeaUkWJO7rynTegj4mu8j97yzxcCCJX1hcF.jpg', 'id-cards/WiwrHYhQk1tRDEJJUwv9fSK9gnf9mwLiXqIHXDcR.jpg', '2026-09-28 21:52:56', '2026-09-28 21:52:56'),
(3, 1, 13, 'Khawar shabeer', 'Muhammad shabeer', '3410194401131', 'Dak Khana khas basi wala', 'id-cards/1loVChqFrvs6kQi3to1o1wXyBv4g0ira24jtVCUx.jpg', 'id-cards/ueRuPSpVYwnenO0UG6NZdTW4hyaAKZTZBJtqA929.jpg', '2026-09-29 19:06:00', '2026-09-29 19:06:00'),
(4, 1, 16, 'Roman', 'Shahbaz Saeed', '3660104763831', 'Burewala', NULL, NULL, '2026-10-01 21:53:06', '2026-10-01 21:53:06');

-- --------------------------------------------------------

--
-- Table structure for table `booking_items`
--

CREATE TABLE `booking_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_items`
--

INSERT INTO `booking_items` (`id`, `tenant_id`, `booking_id`, `name`, `qty`, `unit_price`, `total`, `created_at`, `updated_at`) VALUES
(2, 1, 4, 'Tea', 2, 150.00, 300.00, '2026-09-29 14:28:34', '2026-09-29 14:28:34');

-- --------------------------------------------------------

--
-- Table structure for table `booking_payments`
--

CREATE TABLE `booking_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `booking_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rent_date` date DEFAULT NULL,
  `received_by` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `method` varchar(255) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_payments`
--

INSERT INTO `booking_payments` (`id`, `tenant_id`, `booking_id`, `booking_item_id`, `rent_date`, `received_by`, `type`, `amount`, `method`, `note`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, NULL, 'room', 1200.00, 'cash', 'Imported from previous paid rent', '2026-09-25 18:17:23', '2026-09-28 21:39:58', '2026-09-28 21:39:58'),
(2, 1, 2, NULL, NULL, NULL, 'room', 1000.00, 'cash', 'Imported from previous paid rent', '2026-09-25 19:37:19', '2026-09-28 21:39:58', '2026-09-28 21:39:58'),
(3, 1, 3, NULL, NULL, NULL, 'room', 5000.00, 'cash', 'Imported from previous paid rent', '2026-09-25 17:41:17', '2026-09-28 21:39:58', '2026-09-28 21:39:58'),
(4, 1, 6, NULL, NULL, NULL, 'room', 1000.00, 'cash', 'Imported from previous paid rent', '2026-09-26 16:22:36', '2026-09-28 21:39:58', '2026-09-28 21:39:58'),
(5, 1, 8, NULL, NULL, NULL, 'room', 800.00, 'cash', 'Imported from previous paid rent', '2026-09-26 16:23:14', '2026-09-28 21:39:58', '2026-09-28 21:39:58'),
(6, 1, 12, NULL, NULL, 2, 'room', 2000.00, 'cash', 'Paid at booking', '2026-09-28 21:58:21', '2026-09-28 21:58:21', '2026-09-28 21:58:21'),
(7, 1, 9, NULL, NULL, 3, 'room', 1000.00, 'cash', 'Next jasy kahy gy Wasa rent dy gy', '2026-09-29 09:35:51', '2026-09-29 09:35:51', '2026-09-29 09:35:51'),
(8, 1, 11, NULL, '2026-09-28', 3, 'room', 1500.00, 'online', 'Room rent 28 Sep 2026', '2026-09-29 18:49:06', '2026-09-29 18:49:06', '2026-09-29 18:49:06'),
(9, 1, 11, NULL, '2026-09-29', 3, 'room', 1500.00, 'online', 'Room rent 29 Sep 2026', '2026-09-29 18:49:06', '2026-09-29 18:49:06', '2026-09-29 18:49:06'),
(10, 1, 12, NULL, '2026-09-29', 3, 'room', 1000.00, 'cash', 'Room rent 29 Sep 2026', '2026-09-29 18:49:51', '2026-09-29 18:49:51', '2026-09-29 18:49:51'),
(11, 1, 4, NULL, '2026-09-23', 3, 'room', 600.00, 'cash', 'Room rent 23 Sep 2026', '2026-09-29 18:50:08', '2026-09-29 18:50:08', '2026-09-29 18:50:08'),
(12, 1, 4, NULL, '2026-09-24', 3, 'room', 600.00, 'cash', 'Room rent 24 Sep 2026', '2026-09-29 18:50:09', '2026-09-29 18:50:09', '2026-09-29 18:50:09'),
(13, 1, 4, NULL, '2026-09-29', 3, 'room', 600.00, 'cash', 'Room rent 29 Sep 2026', '2026-09-29 18:50:14', '2026-09-29 18:50:14', '2026-09-29 18:50:14'),
(14, 1, 4, NULL, '2026-09-28', 3, 'room', 600.00, 'cash', 'Room rent 28 Sep 2026', '2026-09-29 18:50:18', '2026-09-29 18:50:18', '2026-09-29 18:50:18'),
(15, 1, 4, NULL, '2026-09-27', 3, 'room', 600.00, 'cash', 'Room rent 27 Sep 2026', '2026-09-29 18:50:21', '2026-09-29 18:50:21', '2026-09-29 18:50:21'),
(16, 1, 4, NULL, '2026-09-26', 3, 'room', 600.00, 'cash', 'Room rent 26 Sep 2026', '2026-09-29 18:50:25', '2026-09-29 18:50:25', '2026-09-29 18:50:25'),
(17, 1, 4, NULL, '2026-09-25', 3, 'room', 600.00, 'cash', 'Room rent 25 Sep 2026', '2026-09-29 18:50:29', '2026-09-29 18:50:29', '2026-09-29 18:50:29'),
(18, 1, 14, NULL, NULL, 3, 'room', 700.00, 'cash', 'Paid at booking', '2026-09-29 22:15:46', '2026-09-29 22:15:46', '2026-09-29 22:15:46'),
(19, 1, 15, NULL, NULL, 3, 'room', 1000.00, 'cash', 'Paid at booking', '2026-09-30 00:52:48', '2026-09-30 00:52:48', '2026-09-30 00:52:48'),
(20, 1, 15, NULL, '2026-09-30', 2, 'room', 1000.00, 'online', 'Room rent 30 Sep 2026', '2026-10-01 04:34:25', '2026-10-01 04:34:25', '2026-10-01 04:34:25'),
(21, 1, 16, NULL, NULL, 3, 'room', 2000.00, 'cash', 'Paid at booking', '2026-10-01 21:53:06', '2026-10-01 21:53:06', '2026-10-01 21:53:06'),
(22, 1, 13, NULL, NULL, 2, 'room', 3600.00, 'cash', 'Paid at checkout', '2026-10-02 01:29:26', '2026-10-02 01:29:26', '2026-10-02 01:29:26');

-- --------------------------------------------------------

--
-- Table structure for table `booking_room_histories`
--

CREATE TABLE `booking_room_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `switched_by` bigint(20) UNSIGNED DEFAULT NULL,
  `started_at` timestamp NOT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_room_histories`
--

INSERT INTO `booking_room_histories` (`id`, `tenant_id`, `booking_id`, `room_id`, `switched_by`, `started_at`, `ended_at`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 1, 2, '2026-09-28 21:12:12', '2026-09-28 21:12:12', '2026-09-28 21:12:12', '2026-09-28 21:12:12'),
(2, 1, 7, 30, 2, '2026-09-28 21:12:12', '2026-09-28 21:12:56', '2026-09-28 21:12:12', '2026-09-28 21:12:56'),
(3, 1, 7, 1, 2, '2026-09-28 21:12:56', NULL, '2026-09-28 21:12:56', '2026-09-28 21:12:56'),
(4, 1, 10, 6, 2, '2026-09-28 21:19:27', NULL, '2026-09-28 21:19:27', '2026-09-28 21:19:27'),
(5, 1, 11, 36, 2, '2026-09-28 21:52:56', NULL, '2026-09-28 21:52:56', '2026-09-28 21:52:56'),
(6, 1, 12, 24, 2, '2026-09-28 21:58:21', NULL, '2026-09-28 21:58:21', '2026-09-28 21:58:21'),
(7, 1, 13, 3, 3, '2026-09-29 19:06:00', '2026-10-02 01:29:26', '2026-09-29 19:06:00', '2026-10-02 01:29:26'),
(8, 1, 14, 4, 3, '2026-09-29 22:15:46', NULL, '2026-09-29 22:15:46', '2026-09-29 22:15:46'),
(9, 1, 15, 23, 3, '2026-09-30 00:52:48', '2026-10-01 21:58:29', '2026-09-30 00:52:48', '2026-10-01 21:58:29'),
(10, 1, 16, 26, 3, '2026-10-01 21:53:06', NULL, '2026-10-01 21:53:06', '2026-10-01 21:53:06');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `cnic` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `tenant_id`, `name`, `father_name`, `phone`, `cnic`, `address`, `created_at`, `updated_at`) VALUES
(1, 1, 'muhammad younas', 'muhammad inayat', '03042347779', '3410442742573', 'Nazd civil hospital main gate mohallah malik town gakhar tehsil wazirabad zilah gujranwala', '2026-09-23 19:12:25', '2026-09-23 19:12:25'),
(2, 1, 'Javed Akhtar khokhar', 'Abdul Rauf', '03000000000', '38403-0935895-5', 'Makan num 1 Street num 1 mahala dargahi gull jaded rana town farooz wala zila Sheikhupura', '2026-09-23 20:33:27', '2026-09-23 20:33:27'),
(3, 1, 'Ansar hanif abbasi', 'Muhammad hanif abbasi', '0300', '82102-8463542-5', 'Dak khana chaman cot sahiyan tasil dahir kot zila bhag', '2026-09-23 22:58:32', '2026-09-23 22:58:32'),
(4, 1, 'Yasmeen gull', 'Rana Muhammad yusaf', '0300', '35202-2353162-4', 'Lahore', '2026-09-23 23:01:05', '2026-09-23 23:01:05'),
(5, 1, 'Muskher abbas', 'Zafar iqbal', '03027636494', '36303-8175623-5', 'Bosan road dakhana latif abas bi bi pur multan', '2026-09-25 17:56:09', '2026-09-25 17:56:09'),
(6, 1, 'Muhammad israr', 'عظیم خان', '03059699282', '16101-0696885-7', 'Nazd taj sanama mahala darab house jaj Bazar mardan', '2026-09-25 19:26:10', '2026-09-25 19:26:10'),
(7, 1, 'Usman Ali', 'Zulfiqar Ali', '03037311222', '3630212717041', 'Nawab pur Road Street No 8 Basti Khyr Shah Multan', '2026-09-28 20:24:32', '2026-09-28 20:24:32'),
(8, 1, 'Adnan yunas', 'Muhammad yunas shah', '03006454415', '3120202830943', 'Satllite town shalimar colony multan', '2026-09-28 21:19:27', '2026-09-28 21:19:27'),
(9, 1, 'Muhammad asghar Bhatti', 'Muhammad nazar Bhatti', '0300', '35101-7161669-7', 'Dhak khana quwatar ahda kishan Chak num 16 tasil chunia zila Kasur', '2026-09-28 21:52:56', '2026-09-28 21:52:56'),
(10, 1, 'Muhammad asif', 'Ashiq Hussain', '03038600568', '36101-7854260-3', 'Makan num 168 Street num 5 block num 6 muhala greeb Abad jahania zilla khanewal', '2026-09-28 21:58:21', '2026-09-28 21:58:21'),
(11, 1, 'Khalil ur rehman', 'Nazeer Muhammad', '03228405369', '37405-9957308-5', 'Lahore', '2026-09-29 19:06:00', '2026-09-29 19:06:00'),
(12, 1, 'Muhammad Kashif aslam', 'Muhammad aslam', '0300262002', '31203-5089023-5', 'Munir chowk word num 8 muhala gareeb hasil pur zila Bahawalpur', '2026-09-29 22:15:46', '2026-09-29 22:15:46'),
(13, 1, 'Muhammad qasim nazeer', 'Nazeer Ahmad Khan', '03007640067', '3310074911621', 'Chak num 367 wb dak khana makhdkom Ali lodhran', '2026-09-30 00:52:48', '2026-09-30 00:52:48'),
(14, 1, 'Muhammad umair rafeeq', 'Muhammad rafeeq', '03136177063', '3110198590469', 'Madni colony nazd faizan madina makan num 109 gali num 10 Bahawalnagar', '2026-10-01 21:53:06', '2026-10-01 21:53:06');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `hire_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'general',
  `amount` decimal(12,2) NOT NULL,
  `expense_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
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
  `attempts` smallint(5) UNSIGNED NOT NULL,
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
(4, '2026_09_21_000001_create_hotel_management_tables', 1),
(5, '2026_09_21_000010_create_rooms_table', 1),
(6, '2026_09_21_000011_create_customers_table', 1),
(7, '2026_09_21_000012_create_bookings_table', 1),
(8, '2026_09_21_000013_create_pos_items_table', 1),
(9, '2026_09_21_000014_create_booking_items_table', 1),
(10, '2026_09_21_000015_create_expenses_table', 1),
(11, '2026_09_21_000016_create_employees_table', 1),
(12, '2026_09_21_000017_create_salary_payments_table', 1),
(13, '2026_09_21_000018_create_booking_companions_table', 1),
(14, '2026_09_21_120000_add_guest_details_to_bookings', 1),
(15, '2026_09_22_000001_add_expense_id_to_salary_payments', 1),
(16, '2026_09_22_000002_replace_phone_with_cnic_on_booking_companions', 1),
(17, '2026_09_22_000003_add_checkout_fields_to_bookings', 1),
(18, '2026_09_23_122218_add_hotel_settings_to_tenants', 1),
(19, '2026_09_23_add_father_name_and_address_to_booking_companions', 2),
(20, '2026_09_24_140101_add_bed_type_and_max_capacity_to_rooms_table', 3),
(21, '2026_09_24_141535_add_checked_in_at_to_bookings_table', 4),
(22, '2026_09_28_170715_create_booking_room_histories_table', 5),
(23, '2026_09_28_172747_create_booking_payments_table', 6),
(24, '2026_09_29_090000_add_booking_item_id_to_booking_payments_table', 7),
(25, '2026_09_29_120000_add_rent_date_to_booking_payments_table', 8);

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
-- Table structure for table `pos_items`
--

CREATE TABLE `pos_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'standard',
  `bed_type` varchar(255) NOT NULL DEFAULT 'single',
  `floor` varchar(255) DEFAULT NULL,
  `max_capacity` tinyint(3) UNSIGNED NOT NULL DEFAULT 2,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `tenant_id`, `number`, `type`, `bed_type`, `floor`, `max_capacity`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '1', 'standard', 'double', 'Ground floor', 2, 'available', NULL, '2026-09-23 18:56:37', '2026-09-25 16:53:52'),
(3, 1, '3', 'standard', 'double', 'Ground floor', 2, 'available', NULL, '2026-09-23 18:57:35', '2026-09-25 16:54:02'),
(4, 1, '5', 'standard', 'double', 'Ground floor', 2, 'available', NULL, '2026-09-23 18:57:42', '2026-09-25 16:54:24'),
(5, 1, '6', 'standard', 'single', 'Ground floor', 2, 'available', NULL, '2026-09-23 18:57:49', '2026-09-23 18:57:49'),
(6, 1, '7', 'standard', 'single', 'Ground floor', 2, 'available', NULL, '2026-09-23 18:57:56', '2026-09-23 18:57:56'),
(7, 1, '4', 'standard', 'double', 'Ground floor', 2, 'available', NULL, '2026-09-23 19:09:05', '2026-09-25 16:54:14'),
(23, 1, '2', 'standard', 'double', 'Ground floor', 2, 'available', NULL, '2026-09-25 16:48:22', '2026-09-25 16:48:22'),
(24, 1, '109', 'standard', 'double', '1st Floor', 2, 'available', NULL, '2026-09-25 16:54:56', '2026-09-25 16:54:56'),
(25, 1, '110', 'standard', 'double', '1st Floor', 2, 'available', NULL, '2026-09-25 16:55:14', '2026-09-25 16:55:14'),
(26, 1, '112', 'standard', 'double', '1st Floor', 2, 'available', NULL, '2026-09-25 16:56:20', '2026-09-25 16:56:20'),
(27, 1, '113', 'standard', 'double', '1st Floor', 2, 'available', NULL, '2026-09-25 17:25:44', '2026-09-25 17:25:44'),
(28, 1, '114', 'standard', 'double', '1st Floor', 2, 'available', NULL, '2026-09-25 17:25:59', '2026-09-25 17:25:59'),
(29, 1, '115', 'standard', 'double', '1st Floor', 2, 'available', NULL, '2026-09-25 17:26:14', '2026-09-25 17:26:14'),
(30, 1, '216', 'standard', 'double', '2nd floor', 2, 'available', NULL, '2026-09-25 17:26:37', '2026-09-25 17:26:37'),
(31, 1, '217', 'standard', 'triple', '2nd floor', 3, 'available', NULL, '2026-09-25 17:26:52', '2026-09-25 17:26:52'),
(32, 1, '218', 'standard', 'single', '2nd floor', 2, 'available', NULL, '2026-09-25 17:27:16', '2026-09-25 17:27:16'),
(33, 1, '219', 'standard', 'single', '2nd floor', 2, 'available', NULL, '2026-09-25 17:27:39', '2026-09-25 17:27:39'),
(34, 1, '220', 'standard', 'single', '2nd floor', 2, 'available', NULL, '2026-09-25 17:27:57', '2026-09-25 17:27:57'),
(35, 1, '221', 'standard', 'double', '2nd floor', 2, 'available', NULL, '2026-09-25 17:28:13', '2026-09-25 17:28:13'),
(36, 1, '222', 'standard', 'double', '2nd floor', 2, 'available', NULL, '2026-09-25 17:28:24', '2026-09-25 17:28:24'),
(37, 1, '223', 'standard', 'double', '2nd floor', 2, 'available', NULL, '2026-09-25 17:28:34', '2026-09-25 17:28:34');

-- --------------------------------------------------------

--
-- Table structure for table `salary_payments`
--

CREATE TABLE `salary_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `expense_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `paid_on` date NOT NULL,
  `for_month` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
('61vJtO4e0I2UZlRt5piik1HYEfw1wZeej5CN2Qmo', NULL, '142.250.32.33', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'eyJfdG9rZW4iOiJVaUZsZmR3aHVqZWhpekVCT3ZHblJMVVh6U0M5bUVIQ3VSa2d0aDNkIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5ncyJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5ncyIsInJvdXRlIjoiYm9va2luZ3MuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1790885397),
('6vLS6o3gS4ZvtLxOl7fK0nn16Oj2WPon8BdAWBP2', NULL, '142.250.32.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'eyJfdG9rZW4iOiJMTXFyVUFLQzNXRTI4VFBiSWo5U1UwWGpVdzlMY2hNUUNTSG1ZQWxwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790885445),
('8xVDiNkDhgzQ6bg7KXAm6UGk5qPPMSO4rr6UvV2V', NULL, '35.196.132.85', 'Mozilla/5.0 (compatible; Discordbot/2.0; +https://discordapp.com)', 'eyJfdG9rZW4iOiJUamExVzhIc1phZGpYVlE4ckNiSW9HR2lKYURxbGR6aFNhc0l5NExiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790844411),
('aBfcXXCCNBRmMEoIezg6r7ByTuqaWchfsBX03vNE', NULL, '142.250.32.33', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'eyJfdG9rZW4iOiJSbDZuT2p0eVNjMHZKdXdQR0tXRmZobGpzOXZJRnZQZkZ2T2I1Qm1YIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5nc1wvMTMifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvYm9va2luZ3NcLzEzIiwicm91dGUiOiJib29raW5ncy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790877399),
('byu0PFzI3poc1OL2z0X9cWplOGS1e239kmhphkoq', NULL, '167.86.89.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/116.0', 'eyJfdG9rZW4iOiJpdFhoN3JuN0xQTWU3NUJQblRQN09PZlM0d29ITktrM1JZNTZlYjdWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC93d3cuY3liZXJzdGF5LmN5YnJpbGxjb2RleC5jb21cL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1790830941),
('c8KRByjxkG5Ubxgme251w36XcOibUiPkn9WPbtZi', NULL, '35.196.132.85', 'Mozilla/5.0 (compatible; Discordbot/2.0; +https://discordapp.com)', 'eyJfdG9rZW4iOiIzdG52RDV4bzJDNlJrUGZXRWNrYVpJOUdyWmFlTnpvU1E0b3ZjY29pIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5nc1wvMTUifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvYm9va2luZ3NcLzE1Iiwicm91dGUiOiJib29raW5ncy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790844410),
('d1g1jtaksSqUsGx7miuMT4igG7zrzERuNd7hMDe5', NULL, '142.250.32.33', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'eyJfdG9rZW4iOiJHU3EzdTQ4Z1FGRVZycGF2VkR3T2dZeTlnbUVJTWExSXA1UmZSVDN4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790877400),
('gBuIVr0EK1HvAXivYuA4OzrkHx0elDbM4xpYwsnd', NULL, '142.250.32.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'eyJfdG9rZW4iOiJva0VhZGl3czBDaExPTmJBeTZiT1NJZHhqdmpVY2l3U1BkS0RUekptIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790877189),
('glNSLDGKAsP5EFJheOPa3mSVCwD5ImnxTjZYTz9Q', 2, '103.31.106.34', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiIxWjNkV1BuY0ZENnN4QWY2eTF2dlF2Q3VyR3l4bGR0NHExbURGcDE2IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5nc1wvMTMiLCJyb3V0ZSI6ImJvb2tpbmdzLnNob3cifSwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9yZXBvcnRzIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==', 1790890200),
('hvw0L5J8ZN5D1BNNoj9vvmWGbsKDING4EJyne3Pg', 2, '103.31.106.34', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJVcWVzaHA0WWljZ2JJdEtMbW9KV0NCN3R6Y3JsOUdXQ2p3N2k0Zno2IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5nc1wvMTUiLCJyb3V0ZSI6ImJvb2tpbmdzLnNob3cifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9', 1790877515),
('jXPPdz3vcgRLe2iHKsNfhAWrfrWsnoYS5hU2izL0', NULL, '182.188.245.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 OPR/136.0.0.0', 'eyJfdG9rZW4iOiJrQVNEcGlVblg5VjBKZENGMFV0NGtaVTVRZ1o3MnR5Q1dYVGlTdmdDIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5nc1wvMTUifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790844461),
('m7YcJDECAzJcAu7Wr5ROrHKYuiWTtjP5bxUUvzRA', NULL, '142.250.32.34', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'eyJfdG9rZW4iOiJKRFBwRk5XSzlLV0FlT1FqOFV6eVlPT25TdnF1QzR1WVA3Q3RNbzVFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790885397),
('OFrqRTHvX15T13lVMreAlFGz3SQQR9qZcjZodlum', NULL, '167.86.89.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/116.0', 'eyJfdG9rZW4iOiJuM050ZDJCaFBCMWhqQVNadkppZGd1eHlCRzk2MWRZWnFuVjdiaHByIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC93d3cuY3liZXJzdGF5LmN5YnJpbGxjb2RleC5jb20iLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790830940),
('oiHl5l9W6ylfVlzroRMFdb78ztIIgjXe2Wy4CUxl', NULL, '192.178.11.199', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'eyJfdG9rZW4iOiJRcENvVG04UmtjZDd5SWgzc2JvcmtlbUdNd2taelZWczhDWHp6NHpoIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5nc1wvMTMifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790890138),
('owxRfSzd8PHdPNy6WLvtrpmlszm9acceHQKSytUe', NULL, '167.86.89.81', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 12_3_1) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.3 Safari/605.1.15', 'eyJfdG9rZW4iOiJMMU1HRG81b3h6ZDlRSW5QYUc1QXZERlhvenI4NU9sSUc5MTVkR0d2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbSIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1790830911),
('PkK1fUCiU9l3iTQaByCfWo0HaufsKhfVJDrJP7cl', 2, '182.188.245.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJRMTdmc3FoZU1VSHZXZmdWdTU4RUp5UlhNQlhrMW4xVlhxWWc3ZXo5IiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwczpcL1wvY3liZXJzdGF5LmN5YnJpbGxjb2RleC5jb21cL2Jvb2tpbmdzXC8xNSIsInJvdXRlIjoiYm9va2luZ3Muc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1790844398),
('qNwOiGasWwrCFGy26NfL4s82lGilSX9NmCpmrB7R', 2, '163.61.227.183', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIwVjZ1WEFsdlBaVWd3WW9tZlV4a2dSV2U1bktieW85OHhrWTNTOVdBIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5nc1wvMTYifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvYm9va2luZ3MiLCJyb3V0ZSI6ImJvb2tpbmdzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9', 1790890475),
('sRBHTVXD4vHL94pBpMEZ74ZQ11CXP2WtxGLExi0C', NULL, '167.86.89.81', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 12_3_1) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.3 Safari/605.1.15', 'eyJfdG9rZW4iOiJtRE11RVl3RGJzRkJPU2ttNlZGUE9Tb3dxd3l4S1lPYWtmemJ1UHFYIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790830911),
('UFr1Zv72go5zcw8PGTAq1wij6DBoYMIjgrajF1RL', NULL, '142.250.32.34', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'eyJfdG9rZW4iOiJXaUJLRUFIWWRaU2RwRGJPVWtVMGxXRmJnNmhJNHBrT2h0dFdrS3NIIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5nc1wvY3JlYXRlIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwczpcL1wvY3liZXJzdGF5LmN5YnJpbGxjb2RleC5jb21cL2Jvb2tpbmdzXC9jcmVhdGUiLCJyb3V0ZSI6ImJvb2tpbmdzLmNyZWF0ZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1790885445),
('v3mseQplMenmxI0xrM13sooYzlN8tJsgEdRVJ57h', 2, '182.188.245.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', 'eyJfdG9rZW4iOiJQRUdiN0JmSHV1eVpBRlJNYjdXVVVyc3lmVk9jNkZsTThKS2FGcmRiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvYm9va2luZ3NcLzE1Iiwicm91dGUiOiJib29raW5ncy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9', 1790845693),
('w7zsF3sUKlP2HIaXgzX0BSXOlkWRUW6LhPNC4oKL', NULL, '142.250.32.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'eyJfdG9rZW4iOiJYZTFVU2ZUa0tMUXdZcHBoTHNnWXRoTHJITE9sT3RuZkFrZG5xbDJBIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5nc1wvY3JlYXRlP3Jvb21faWQ9MjYifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvYm9va2luZ3NcL2NyZWF0ZT9yb29tX2lkPTI2Iiwicm91dGUiOiJib29raW5ncy5jcmVhdGUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1790876957),
('WRDAdykaOuXYuV2Y69smfitmF1z4NlrUM5FSYBcG', 2, '160.191.208.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ1eFBXZ1BsdkVtUkR4MEdqVUhWTERCRE9BRW91YXZpYjVnU2x6aG5tIiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwczpcL1wvY3liZXJzdGF5LmN5YnJpbGxjb2RleC5jb21cL2Jvb2tpbmdzXC8xNiIsInJvdXRlIjoiYm9va2luZ3Muc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1790883087),
('Z2UthvhXgFmMcTpjiKUXqJzy1b79eW0dj3gz3faM', NULL, '142.250.32.34', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'eyJfdG9rZW4iOiJ1N2tyNzNRUXZkSjRsNGNRM1VVVUk4b0gxVmFadFZJblp1cWJMTDU1IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2N5YmVyc3RheS5jeWJyaWxsY29kZXguY29tXC9ib29raW5nc1wvMTYifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvYm9va2luZ3NcLzE2Iiwicm91dGUiOiJib29raW5ncy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790877189),
('zt5uBUYBKMgpoWCZ56EOkv8ehD3ZMvyHHHpV09Yq', NULL, '142.250.32.33', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'eyJfdG9rZW4iOiJ4RmU3c2ozdlZHbWk3Wkh0bDVGUDkzek1wd0xVRHlMS2k2NXd0WEhQIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jeWJlcnN0YXkuY3licmlsbGNvZGV4LmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790876958);

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `tax_id` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `name`, `slug`, `phone`, `email`, `city`, `address`, `logo`, `website`, `tax_id`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Indus Hotel Sahiwal', 'cyberstay-main', '0300-0000000', 'frontdesk@cyberstay.local', 'Lahore', 'Main Road', NULL, NULL, NULL, NULL, 1, '2026-09-23 17:53:43', '2026-09-25 01:07:59'),
(2, 'Demo Hotel', 'demo-hotel', '03000000000', 'xyz@gmail.com', 'Sahiwal', 'Pakistan', NULL, NULL, NULL, NULL, 1, '2026-09-25 17:03:05', '2026-09-25 17:03:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'receptionist',
  `phone` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `tenant_id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Super Admin', 'superadmin@cyberstay.local', NULL, '$2y$12$CC4p1E43vYBrNMMj8sixpueAWY8au/5p5h1DWiCWxOKPMbdCyrbEG', 'super_admin', NULL, 1, 'sl9ZDzy3L7qNvQK6TMjS8K10JiyqilJAd5R3iyx806aufggOXaKQ7jCMG4UD', '2026-09-23 17:53:43', '2026-09-23 17:53:43'),
(2, 1, 'Admin User', 'admin@cyberstay.local', NULL, '$2y$12$EOp08qfx97.k7ZhBZa97Fe4ZbWWoVZcQC2Lz/iTjtjJYx88Rx9wTK', 'owner', NULL, 1, 'ZTYb1Paok1Cjusd6WM3T8veHXYOLxMsfL8IdTisS4s1Sx85clVHfUVPaNDve', '2026-09-23 17:53:44', '2026-09-23 17:53:44'),
(3, 1, 'Receptionist User', 'receptionist@cyberstay.local', NULL, '$2y$12$M0h9tnlSNWoP0bdEgrncSOzgDPebYVHyilYKAfK9Lam120B8rn9Wa', 'receptionist', NULL, 1, 'bFZMiBydJLUWTcs7y8cVDFp8E1Btq1sN9ozbwcGr49SzgGCpVADoqgAs4Gi2', '2026-09-23 17:53:44', '2026-09-23 17:53:44'),
(4, 2, 'CybrillCodeX', 'demo@gmail.com', NULL, '$2y$12$7HUqiDtFYgENZOzh5x10luCncRUOfCpaUJ9Ei3vgIl3Wd1.ljaTYi', 'owner', NULL, 1, '37Jt00eFkX44djp9tOrPGdw5klC0xbzZsv2Xg2i4oQ7wKCAp9jMR7XEL8zIT', '2026-09-25 17:03:05', '2026-09-25 17:03:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_customer_id_foreign` (`customer_id`),
  ADD KEY `bookings_created_by_foreign` (`created_by`),
  ADD KEY `bookings_tenant_id_status_index` (`tenant_id`,`status`),
  ADD KEY `bookings_room_id_check_in_check_out_index` (`room_id`,`check_in`,`check_out`);

--
-- Indexes for table `booking_companions`
--
ALTER TABLE `booking_companions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_companions_tenant_id_foreign` (`tenant_id`),
  ADD KEY `booking_companions_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `booking_items`
--
ALTER TABLE `booking_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_items_tenant_id_foreign` (`tenant_id`),
  ADD KEY `booking_items_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `booking_payments`
--
ALTER TABLE `booking_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_payments_received_by_foreign` (`received_by`),
  ADD KEY `booking_payments_booking_id_paid_at_index` (`booking_id`,`paid_at`),
  ADD KEY `booking_payments_tenant_id_paid_at_index` (`tenant_id`,`paid_at`),
  ADD KEY `booking_payments_booking_item_id_foreign` (`booking_item_id`),
  ADD KEY `booking_payments_booking_id_rent_date_index` (`booking_id`,`rent_date`);

--
-- Indexes for table `booking_room_histories`
--
ALTER TABLE `booking_room_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_room_histories_tenant_id_foreign` (`tenant_id`),
  ADD KEY `booking_room_histories_switched_by_foreign` (`switched_by`),
  ADD KEY `booking_room_histories_booking_id_started_at_index` (`booking_id`,`started_at`),
  ADD KEY `booking_room_histories_room_id_started_at_index` (`room_id`,`started_at`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_tenant_id_phone_index` (`tenant_id`,`phone`),
  ADD KEY `customers_tenant_id_name_index` (`tenant_id`,`name`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employees_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_tenant_id_foreign` (`tenant_id`),
  ADD KEY `expenses_created_by_foreign` (`created_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pos_items`
--
ALTER TABLE `pos_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pos_items_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rooms_tenant_id_number_unique` (`tenant_id`,`number`);

--
-- Indexes for table `salary_payments`
--
ALTER TABLE `salary_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_payments_tenant_id_foreign` (`tenant_id`),
  ADD KEY `salary_payments_employee_id_foreign` (`employee_id`),
  ADD KEY `salary_payments_expense_id_foreign` (`expense_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenants_slug_unique` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_tenant_id_foreign` (`tenant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `booking_companions`
--
ALTER TABLE `booking_companions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `booking_items`
--
ALTER TABLE `booking_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking_payments`
--
ALTER TABLE `booking_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `booking_room_histories`
--
ALTER TABLE `booking_room_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `pos_items`
--
ALTER TABLE `pos_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `salary_payments`
--
ALTER TABLE `salary_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `bookings_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_companions`
--
ALTER TABLE `booking_companions`
  ADD CONSTRAINT `booking_companions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_companions_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_items`
--
ALTER TABLE `booking_items`
  ADD CONSTRAINT `booking_items_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_items_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_payments`
--
ALTER TABLE `booking_payments`
  ADD CONSTRAINT `booking_payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_payments_booking_item_id_foreign` FOREIGN KEY (`booking_item_id`) REFERENCES `booking_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_payments_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_room_histories`
--
ALTER TABLE `booking_room_histories`
  ADD CONSTRAINT `booking_room_histories_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_room_histories_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `booking_room_histories_switched_by_foreign` FOREIGN KEY (`switched_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_room_histories_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pos_items`
--
ALTER TABLE `pos_items`
  ADD CONSTRAINT `pos_items_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salary_payments`
--
ALTER TABLE `salary_payments`
  ADD CONSTRAINT `salary_payments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_payments_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salary_payments_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
