-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 11:07 AM
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
-- Database: `sentracoffee_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id_customer` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id_customer`, `nama`, `email`, `password`, `no_hp`, `points`) VALUES
(1, 'Nama Pengguna Anda', 'email.baru@example.com', 'passwordamananda', '081234567890', 0),
(2, 'faris', 'faris123@example.com', 'faris123', '08123456897', 10200),
(3, 'faris3', 'faris1234@example.com', 'faris12334', '081234568978', 0),
(4, 'Faris', 'Faris@gmail.com', 'fars123', '239942', 0),
(5, 'David', 'David@gmail.com', '1234567', '13982', 74400),
(6, 'Adi', 'Adi123@gmail.com', '1234567', '2232424', 0),
(7, 'Raja', 'raja@gmail.com', '123456', '12345678910', 56100);

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_points_history`
--

CREATE TABLE `loyalty_points_history` (
  `id_point_history` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_transaction` int(11) DEFAULT NULL,
  `points_change` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `history_date` datetime DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loyalty_points_history`
--

INSERT INTO `loyalty_points_history` (`id_point_history`, `id_customer`, `id_transaction`, `points_change`, `type`, `history_date`, `description`) VALUES
(1, 2, 5, 5200, 'earn', '2025-07-01 16:37:28', 'Earned from purchase'),
(2, 7, 6, 13000, 'earn', '2025-07-01 16:38:25', 'Earned from purchase'),
(3, 7, 7, 2600, 'earn', '2025-07-01 17:28:38', 'Earned from purchase'),
(4, 7, 8, 7800, 'earn', '2025-07-01 19:51:02', 'Earned from purchase'),
(5, 7, 9, 18200, 'earn', '2025-07-01 20:04:32', 'Earned from purchase'),
(6, 7, 10, 15600, 'earn', '2025-07-01 20:21:50', 'Earned from purchase'),
(7, 7, 11, 10200, 'earn', '2025-07-01 21:08:03', 'Earned from purchase'),
(8, 7, 12, 10200, 'earn', '2025-07-01 21:11:06', 'Earned from purchase'),
(9, 5, 13, 27200, 'earn', '2025-07-01 21:16:31', 'Earned from purchase'),
(10, 5, 14, 17000, 'earn', '2025-07-01 21:29:10', 'Earned from purchase'),
(11, 5, 15, 10200, 'earn', '2025-07-01 21:35:54', 'Earned from purchase'),
(12, 5, 16, 9000, 'earn', '2025-07-01 21:46:05', 'Earned from purchase'),
(13, 7, 17, -1000, 'redeem', '2025-07-01 23:00:25', 'Redeemed with points'),
(14, 7, 18, 3400, 'earn', '2025-07-01 23:54:59', 'Earned from purchase'),
(15, 7, 19, 3400, 'earn', '2025-07-02 00:27:23', 'Earned from purchase'),
(16, 7, 20, -1000, 'redeem', '2025-07-02 00:29:09', 'Redeemed with points'),
(17, 7, 21, -1000, 'redeem', '2025-07-02 00:30:25', 'Redeemed with points'),
(18, 7, 22, 1800, 'earn', '2025-07-02 00:37:06', 'Earned from purchase'),
(19, 7, 23, -26000, 'redeem', '2025-07-02 00:37:29', 'Redeemed with points'),
(20, 7, 24, 3400, 'earn', '2025-07-02 02:44:51', 'Earned from purchase'),
(21, 7, 25, 1700, 'earn', '2025-07-02 02:45:19', 'Earned from purchase'),
(22, 7, 26, 1600, 'earn', '2025-07-02 02:46:11', 'Earned from purchase'),
(23, 7, 27, 1800, 'earn', '2025-07-02 02:54:33', 'Earned from purchase'),
(24, 7, 28, -34000, 'redeem', '2025-07-02 02:55:02', 'Redeemed with points'),
(25, 7, 29, -25000, 'redeem', '2025-07-02 03:09:15', 'Redeemed with points'),
(26, 7, 30, 3400, 'earn', '2025-07-02 11:21:10', 'Earned from purchase'),
(27, 7, 31, 1600, 'earn', '2025-07-02 11:21:54', 'Earned from purchase'),
(28, 7, 32, 3400, 'earn', '2025-07-02 11:31:11', 'Earned from purchase'),
(29, 7, 33, 100, 'earn', '2025-07-02 11:31:50', 'Earned from purchase'),
(30, 7, 34, -1000, 'redeem', '2025-07-02 11:40:00', 'Redeemed with points'),
(31, 7, 35, 5000, 'earn', '2025-07-02 11:54:00', 'Earned from purchase'),
(32, 7, 36, 5000, 'earn', '2025-07-02 12:14:47', 'Earned from purchase'),
(33, 7, 37, 5000, 'earn', '2025-07-02 12:16:06', 'Earned from purchase'),
(34, 7, 38, 25000, 'earn', '2025-07-02 12:17:22', 'Earned from purchase'),
(35, 7, 39, 5000, 'earn', '2025-07-02 12:32:12', 'Earned from purchase'),
(36, 7, 40, -25000, 'redeem', '2025-07-02 12:47:33', 'Redeemed with points'),
(37, 7, 41, 3300, 'earn', '2025-07-02 12:55:36', 'Earned from purchase'),
(38, 2, 42, 5000, 'earn', '2025-07-02 13:57:57', 'Earned from purchase'),
(39, 7, 43, 5000, 'earn', '2025-07-02 14:00:01', 'Earned from purchase'),
(40, 5, 44, 5000, 'earn', '2025-07-02 14:13:21', 'Earned from purchase'),
(41, 5, 45, 6000, 'earn', '2025-07-02 14:13:41', 'Earned from purchase'),
(42, 7, 46, 8100, 'earn', '2025-07-02 14:16:01', 'Earned from purchase'),
(43, 7, 47, 2100, 'earn', '2025-07-02 14:29:09', 'Earned from purchase'),
(44, 7, 48, 3400, 'earn', '2025-07-02 22:02:10', 'Earned from purchase');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id_menu`, `nama_menu`, `kategori`, `harga`, `is_available`, `gambar`) VALUES
(2, 'Flat White', 'Coffee', 26000.00, 1, 'flatwhite.png'),
(3, 'Espresso', 'Coffee', 18000.00, 1, 'espresso.png'),
(6, 'Cappucino', 'coffee', 34000.00, 1, '6863bfc0245bf-cappucino.png'),
(7, 'sulekopi', 'coffee', 50000.00, 0, '6864b69b621ef-image-removebg-preview (14).png'),
(8, 'lawakwhitecoffee', 'coffee', 40000.00, 0, '68653ad087b51-image-removebg-preview (14).png'),
(9, 'Cinnamon', 'coffee', 30000.00, 1, '686556361fccf-pngwing.com (19).png'),
(10, 'a', 'a', 1.00, 0, '68655ba27c780-download (3).jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `menu_compositions`
--

CREATE TABLE `menu_compositions` (
  `id_menu_composition` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_raw_material` int(11) NOT NULL,
  `quantity_needed` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_compositions`
--

INSERT INTO `menu_compositions` (`id_menu_composition`, `id_menu`, `id_raw_material`, `quantity_needed`) VALUES
(1, 3, 1, 18.00),
(2, 2, 1, 18.00),
(3, 2, 2, 150.00),
(4, 6, 1, 20.00),
(5, 6, 2, 100.00),
(6, 9, 5, 50.00),
(7, 9, 1, 20.00),
(8, 9, 2, 80.00);

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `id_owner` int(11) NOT NULL,
  `nama_owner` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`id_owner`, `nama_owner`, `email`, `password`) VALUES
(1, 'Zidane', 'zidan@gmail.com', '1234567');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id_promotion` int(11) NOT NULL,
  `promo_code` varchar(50) DEFAULT NULL,
  `promo_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` varchar(50) NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id_promotion`, `promo_code`, `promo_name`, `description`, `discount_type`, `discount_value`, `start_date`, `end_date`, `is_active`) VALUES
(1, NULL, '7.7', 'tanggal cantik', 'persen', 10.00, '2025-07-01 18:18:56', '2025-07-01 18:18:56', 0),
(2, NULL, 'merdeka', 'memperingati hut indonesia', 'potongan', 17000.00, '2025-07-01 18:19:55', '2025-07-01 18:19:55', 1),
(3, NULL, 'MURAH', 'tulis MURAH di kolom promo', 'persen', 50.00, '2025-07-02 15:48:15', '2025-07-02 15:48:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `raw_materials`
--

CREATE TABLE `raw_materials` (
  `id_raw_material` int(11) NOT NULL,
  `nama_bahan` varchar(100) NOT NULL,
  `current_stock` decimal(10,2) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `min_stock_level` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raw_materials`
--

INSERT INTO `raw_materials` (`id_raw_material`, `nama_bahan`, `current_stock`, `unit`, `min_stock_level`) VALUES
(1, 'Biji Kopi Espresso Blend', 5960.00, 'gram', 500.00),
(2, 'Susu UHT Full Cream', 9820.00, 'ml', 1000.00),
(3, 'Sirup Gula Aren', 2000.00, 'ml', 200.00),
(4, 'Bubuk Cokelat', 1000.00, 'gram', 100.00),
(5, 'cinnamon syrup', 1900.00, 'ml', 1000.00);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id_report` int(11) NOT NULL,
  `id_owner` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `report_type` varchar(50) NOT NULL,
  `total_revenue` decimal(12,2) NOT NULL,
  `total_expense` decimal(12,2) NOT NULL,
  `report_data_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`report_data_json`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `id_staff` int(11) NOT NULL,
  `id_owner` int(11) NOT NULL,
  `nama_staff` varchar(100) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`id_staff`, `id_owner`, `nama_staff`, `role`, `email`, `password`, `no_hp`) VALUES
(1, 1, 'radja', 'Barista', 'radja@gmail.com', '1234567890', '1235467890'),
(6, 1, 'Budi', 'Cleaner', 'budi@sentra.com', 'password123', '081234567891'),
(7, 1, 'Irham', 'Barista', 'irham@gmail.com', '123456', '08954673281');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id_transaction` int(11) NOT NULL,
  `id_customer` int(11) DEFAULT NULL,
  `id_staff` int(11) DEFAULT NULL,
  `transaction_date` datetime DEFAULT current_timestamp(),
  `payment_method` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `points_earned` int(11) DEFAULT 0,
  `status` varchar(50) NOT NULL,
  `id_promotion` int(11) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id_transaction`, `id_customer`, `id_staff`, `transaction_date`, `payment_method`, `total_amount`, `points_earned`, `status`, `id_promotion`, `discount_amount`) VALUES
(1, 2, 1, '2025-06-10 21:29:38', 'Dana', 42000.00, 4200, 'Completed', NULL, 0.00),
(2, 6, 1, '2025-06-10 21:55:39', 'dana', 52000.00, 5200, 'Completed', NULL, 0.00),
(3, 6, 1, '2025-06-20 21:01:14', 'dana', 78000.00, 7800, 'Completed', NULL, 0.00),
(4, 6, 1, '2025-06-23 13:46:30', 'dana', 26000.00, 2600, 'Completed', NULL, 0.00),
(5, 2, 1, '2025-07-01 16:37:28', 'Cash', 52000.00, 5200, 'Completed', NULL, 0.00),
(6, 7, 1, '2025-07-01 16:38:25', 'QRIS', 130000.00, 13000, 'Completed', NULL, 0.00),
(7, 7, 1, '2025-07-01 17:28:38', 'credit_card', 26000.00, 2600, 'Completed', NULL, 0.00),
(8, 7, 1, '2025-07-01 19:51:02', 'dana', 78000.00, 7800, 'Completed', NULL, 0.00),
(9, 7, 1, '2025-07-01 20:04:32', 'dana', 182000.00, 18200, 'Completed', NULL, 0.00),
(10, 7, 1, '2025-07-01 20:21:50', 'dana', 156000.00, 15600, 'Completed', NULL, 0.00),
(11, 7, 1, '2025-07-01 21:08:03', 'dana', 102000.00, 10200, 'Completed', NULL, 0.00),
(12, 7, 1, '2025-07-01 21:11:06', 'dana', 102000.00, 10200, 'Completed', NULL, 0.00),
(13, 5, 1, '2025-07-01 21:16:31', 'dana', 272000.00, 27200, 'Completed', NULL, 0.00),
(14, 5, 1, '2025-07-01 21:29:10', 'dana', 170000.00, 17000, 'Completed', NULL, 0.00),
(15, 5, 1, '2025-07-01 21:35:54', 'dana', 102000.00, 10200, 'Completed', NULL, 0.00),
(16, 5, 1, '2025-07-01 21:46:05', 'Cash', 90000.00, 9000, 'Completed', NULL, 0.00),
(17, 7, 1, '2025-07-01 23:00:25', 'Points', 0.00, 0, 'Completed', NULL, 0.00),
(18, 7, 1, '2025-07-01 23:54:59', 'credit_card', 34000.00, 3400, 'Completed', NULL, 0.00),
(19, 7, 1, '2025-07-02 00:27:23', 'credit_card', 34000.00, 3400, 'Completed', NULL, 0.00),
(20, 7, 1, '2025-07-02 00:29:09', 'Points', 0.00, 0, 'Completed', NULL, 0.00),
(21, 7, 1, '2025-07-02 00:30:25', 'Points', 0.00, 0, 'Completed', NULL, 0.00),
(22, 7, 1, '2025-07-02 00:37:06', 'credit_card', 18000.00, 1800, 'Completed', NULL, 0.00),
(23, 7, 1, '2025-07-02 00:37:29', 'Points', 0.00, 0, 'Completed', NULL, 0.00),
(24, 7, 1, '2025-07-02 02:44:51', 'dana', 34000.00, 3400, 'Completed', NULL, 0.00),
(25, 7, 1, '2025-07-02 02:45:19', 'dana', 17000.00, 1700, 'Completed', 2, 17000.00),
(26, 7, 1, '2025-07-02 02:46:11', 'Points', 16200.00, 1600, 'Completed', 1, 1800.00),
(27, 7, 1, '2025-07-02 02:54:33', 'dana', 18000.00, 1800, 'Completed', NULL, 0.00),
(28, 7, 1, '2025-07-02 02:55:02', 'Points', 0.00, 0, 'Completed', NULL, 0.00),
(29, 7, 1, '2025-07-02 03:09:15', 'Points', 0.00, 0, 'Completed', NULL, 0.00),
(30, 7, 1, '2025-07-02 11:21:10', 'dana', 34000.00, 3400, 'Completed', NULL, 0.00),
(31, 7, 1, '2025-07-02 11:21:54', 'credit_card', 16200.00, 1600, 'Completed', 1, 1800.00),
(32, 7, 1, '2025-07-02 11:31:11', 'dana', 34000.00, 3400, 'Completed', NULL, 0.00),
(33, 7, 1, '2025-07-02 11:31:50', 'dana', 1000.00, 100, 'Completed', 2, 17000.00),
(34, 7, 1, '2025-07-02 11:40:00', 'Points', 0.00, 0, 'Completed', NULL, 0.00),
(35, 7, 1, '2025-07-02 11:54:00', 'Cash', 50000.00, 5000, 'Completed', NULL, 0.00),
(36, 7, NULL, '2025-07-02 12:14:47', 'Cash', 50000.00, 5000, 'Completed', NULL, 0.00),
(37, 7, NULL, '2025-07-02 12:16:06', 'Cash', 50000.00, 5000, 'Completed', NULL, 0.00),
(38, 7, NULL, '2025-07-02 12:17:22', 'Cash', 250000.00, 25000, 'Completed', NULL, 0.00),
(39, 7, NULL, '2025-07-02 12:32:12', 'Cash', 50000.00, 5000, 'Completed', NULL, 0.00),
(40, 7, NULL, '2025-07-02 12:47:33', 'Points', 0.00, 0, 'Completed', NULL, 0.00),
(41, 7, NULL, '2025-07-02 12:55:36', 'dana', 33000.00, 3300, 'Completed', 2, 17000.00),
(42, 2, NULL, '2025-07-02 13:57:57', 'QRIS', 50000.00, 5000, 'Completed', NULL, 0.00),
(43, 7, NULL, '2025-07-02 14:00:01', 'QRIS', 50000.00, 5000, 'Completed', NULL, 0.00),
(44, 5, NULL, '2025-07-02 14:13:21', 'QRIS', 50000.00, 5000, 'Completed', NULL, 0.00),
(45, 5, NULL, '2025-07-02 14:13:41', 'Cash', 60000.00, 6000, 'Completed', NULL, 0.00),
(46, 7, NULL, '2025-07-02 14:16:01', 'dana', 81600.00, 8100, 'Completed', NULL, 0.00),
(47, 7, NULL, '2025-07-02 14:29:09', 'dana', 21600.00, 2100, 'Completed', NULL, 0.00),
(48, 7, NULL, '2025-07-02 22:02:10', 'Cash', 34000.00, 3400, 'Completed', NULL, 0.00),
(49, 7, NULL, '2025-07-02 22:50:21', 'Cash', 20400.00, 2000, 'Completed', 3, 20400.00),
(50, 7, NULL, '2025-07-02 23:18:38', 'Cash', 24000.00, 2400, 'Completed', NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id_transaction_detail` int(11) NOT NULL,
  `id_transaction` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id_transaction_detail`, `id_transaction`, `id_menu`, `quantity`, `subtotal`) VALUES
(1, 1, 2, 2, 50000.00),
(2, 1, 2, 1, 25000.00),
(3, 2, 2, 2, 52000.00),
(4, 3, 2, 3, 78000.00),
(5, 4, 2, 1, 26000.00),
(6, 5, 2, 2, 52000.00),
(7, 6, 2, 5, 130000.00),
(8, 7, 2, 1, 26000.00),
(9, 8, 2, 3, 78000.00),
(10, 9, 2, 7, 182000.00),
(11, 10, 2, 6, 156000.00),
(12, 11, 6, 3, 102000.00),
(13, 12, 6, 3, 102000.00),
(14, 13, 6, 8, 272000.00),
(15, 14, 6, 5, 170000.00),
(16, 15, 6, 3, 102000.00),
(17, 16, 3, 5, 90000.00),
(18, 17, 6, 2, 68000.00),
(19, 18, 6, 1, 34000.00),
(20, 19, 6, 1, 34000.00),
(21, 20, 3, 1, 18000.00),
(22, 21, 2, 1, 26000.00),
(23, 22, 3, 1, 18000.00),
(24, 23, 2, 1, 26000.00),
(25, 24, 6, 1, 34000.00),
(26, 25, 6, 1, 34000.00),
(27, 26, 3, 1, 18000.00),
(28, 27, 3, 1, 18000.00),
(29, 28, 6, 1, 34000.00),
(30, 29, 6, 1, 34000.00),
(31, 30, 6, 1, 34000.00),
(32, 31, 3, 1, 18000.00),
(33, 32, 6, 1, 34000.00),
(34, 33, 3, 1, 18000.00),
(35, 34, 7, 1, 50000.00),
(36, 35, 7, 1, 50000.00),
(37, 36, 7, 1, 50000.00),
(38, 37, 7, 1, 50000.00),
(39, 38, 7, 5, 250000.00),
(40, 39, 7, 1, 50000.00),
(41, 40, 2, 1, 26000.00),
(42, 41, 7, 1, 50000.00),
(43, 42, 7, 1, 50000.00),
(44, 43, 7, 1, 50000.00),
(45, 44, 7, 1, 50000.00),
(46, 45, 7, 1, 60000.00),
(47, 46, 6, 2, 81600.00),
(48, 47, 3, 1, 21600.00),
(49, 48, 6, 1, 34000.00),
(50, 49, 6, 1, 40800.00),
(51, 50, 9, 1, 24000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id_customer`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `loyalty_points_history`
--
ALTER TABLE `loyalty_points_history`
  ADD PRIMARY KEY (`id_point_history`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_transaction` (`id_transaction`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `menu_compositions`
--
ALTER TABLE `menu_compositions`
  ADD PRIMARY KEY (`id_menu_composition`),
  ADD KEY `id_menu` (`id_menu`),
  ADD KEY `id_raw_material` (`id_raw_material`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`id_owner`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id_promotion`),
  ADD UNIQUE KEY `promo_code` (`promo_code`);

--
-- Indexes for table `raw_materials`
--
ALTER TABLE `raw_materials`
  ADD PRIMARY KEY (`id_raw_material`),
  ADD UNIQUE KEY `nama_bahan` (`nama_bahan`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id_report`),
  ADD KEY `id_owner` (`id_owner`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`id_staff`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_owner` (`id_owner`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id_transaction`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_staff` (`id_staff`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id_transaction_detail`),
  ADD KEY `id_transaction` (`id_transaction`),
  ADD KEY `id_menu` (`id_menu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `loyalty_points_history`
--
ALTER TABLE `loyalty_points_history`
  MODIFY `id_point_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `menu_compositions`
--
ALTER TABLE `menu_compositions`
  MODIFY `id_menu_composition` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `id_owner` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id_promotion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `id_raw_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id_report` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `id_staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id_transaction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id_transaction_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loyalty_points_history`
--
ALTER TABLE `loyalty_points_history`
  ADD CONSTRAINT `loyalty_points_history_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`),
  ADD CONSTRAINT `loyalty_points_history_ibfk_2` FOREIGN KEY (`id_transaction`) REFERENCES `transactions` (`id_transaction`);

--
-- Constraints for table `menu_compositions`
--
ALTER TABLE `menu_compositions`
  ADD CONSTRAINT `menu_compositions_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id_menu`),
  ADD CONSTRAINT `menu_compositions_ibfk_2` FOREIGN KEY (`id_raw_material`) REFERENCES `raw_materials` (`id_raw_material`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`id_owner`) REFERENCES `owners` (`id_owner`);

--
-- Constraints for table `staffs`
--
ALTER TABLE `staffs`
  ADD CONSTRAINT `staffs_ibfk_1` FOREIGN KEY (`id_owner`) REFERENCES `owners` (`id_owner`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`id_staff`) REFERENCES `staffs` (`id_staff`);

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`id_transaction`) REFERENCES `transactions` (`id_transaction`),
  ADD CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id_menu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
