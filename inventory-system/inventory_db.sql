-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2025 at 10:03 PM
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
-- Database: `inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `delivered_at` datetime NOT NULL DEFAULT current_timestamp(),
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id`, `product_id`, `quantity`, `delivered_at`, `note`) VALUES
(8, 13, 2, '2025-08-07 20:18:40', ''),
(9, 15, 4, '2025-08-07 20:19:25', 'customer address'),
(10, 19, 5, '2025-08-07 20:30:33', 'Harry Potter ');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `stock`, `created_at`, `updated_at`, `image`) VALUES
(13, 'rice', 'Groceries', 5.00, 9, '2025-08-07 21:18:49', NULL, '1754601529_08a7e9bdcc7179ef3f08.jpg'),
(15, 'Phones', 'Electronics', 200.00, 23, '2025-08-08 03:01:22', NULL, '1754622082_1514873e8413db53f7e4.jpg'),
(16, 'Home & Kitchen', 'cookware', 100.00, 3, '2025-08-08 03:02:21', NULL, '1754622141_9857ebae5315e4f6a296.jpeg'),
(17, 'makeup', 'Health & Beauty', 300.00, 100, '2025-08-08 03:03:14', NULL, '1754622194_38501986df2ee977db2d.jpg'),
(18, 'Kids\' toys', 'Toys & Games', 300.00, 95, '2025-08-08 03:04:33', NULL, '1754622273_ed3000cbb8b877c778d7.jpg'),
(19, 'Books', 'Books & Stationery', 50.00, 35, '2025-08-08 03:05:20', NULL, '1754622320_50d47ae9d57420c57dcc.jpg'),
(20, 'tables', 'Furniture', 400.00, 6, '2025-08-08 03:07:19', NULL, '1754622439_d1f50c5e231f6db20a2d.png'),
(21, 'Shoes', 'Footwear', 10.00, 4, '2025-08-08 03:08:11', NULL, '1754622491_48e1b144b2464fde0f4f.jpeg'),
(22, 'jewelry', 'Accessories', 500.00, 6, '2025-08-08 03:09:14', NULL, '1754622554_893702dff2a751aff0cb.jpg'),
(23, 'watches', 'Accessories', 500.00, 7, '2025-08-08 03:11:16', NULL, '1754622676_0abac19664deac949f40.jpeg'),
(24, 'Pen', 'Books & Stationery', 5.00, 50, '2025-08-08 03:13:13', NULL, '1754622793_11d44551c69be8182938.jpeg'),
(25, 'Color Pencils', 'Books & Stationery', 10.00, 3, '2025-08-08 03:20:05', NULL, '1754623205_464a775b4115705bb2df.jpg'),
(27, 'Vegetable', 'Groceries', 5.00, 3, '2025-08-08 03:29:09', NULL, '1754623749_f6dc9ba1846e192d3a39.jpg'),
(30, 'Pressure Cooker', 'Home & Kitchen', 100.00, 3, '2025-08-20 19:49:56', NULL, '1754622141_9857ebae5315e4f6a296.jpeg'),
(54, 'shoes', 'Footwear', 20.00, 5, '2025-08-23 19:29:00', NULL, '1755977338_8a59a476201068dc1feb.png'),
(55, 'Phones', 'Electronics', 200.00, 5, '2025-08-23 19:31:39', NULL, '1755977498_d7825f203874e4c4ce68.jpg'),
(57, 'shoes', 'Footwear', 50.00, 6, '2025-08-23 19:54:09', NULL, '1755978847_77e117c006b3dc7f303b.jpeg'),
(58, 'table', 'Furniture', 50.00, 6, '2025-08-23 20:00:05', NULL, '1755979204_fe8a3abf931005202047.png'),
(71, 'table', 'Furniture', 50.00, 6, '2025-08-24 04:02:40', NULL, '1756008159_8d9ca73a2f05eda7372b.png'),
(72, 'Vegetable', 'Groceries', 50.00, 6, '2025-08-24 04:23:02', NULL, '1756009381_055c6230f18753ee78b5.jpg'),
(73, 'Vegetable', 'Groceries', 50.00, 6, '2025-08-24 04:23:36', NULL, '1756009415_d4ce8b7a51be6941b3bd.jpeg'),
(74, 'fruits', 'Groceries', 5.00, 5, '2025-08-24 04:24:17', NULL, '1756009455_5c0f7af1d53c820ce64f.jpg'),
(76, 'Vegetable', 'Groceries', 50.00, 5, '2025-08-27 20:44:42', NULL, '1756327481_4e9fbd488457e246aa3b.jpg'),
(77, 'jewelry', 'Accessories', 200.00, 6, '2025-08-27 20:45:29', NULL, '1756327527_8f7ffb89c5129f7bd8e4.jpg'),
(80, 'table', 'Furniture', 200.00, 5, '2025-08-28 20:42:19', NULL, '1756413732_09ba520fc33b006fe25c.png');

-- --------------------------------------------------------

--
-- Table structure for table `stock_logs`
--

CREATE TABLE `stock_logs` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` enum('in','out') NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_logs`
--

INSERT INTO `stock_logs` (`id`, `product_id`, `type`, `quantity`, `note`, `created_at`) VALUES
(10, 15, 'in', 5, '', '2025-08-08 03:16:05'),
(11, 16, 'in', 3, 'great product', '2025-08-08 03:16:31'),
(12, 15, 'out', 4, '', '2025-08-08 03:17:12'),
(13, 15, 'in', 5, '', '2025-08-08 03:21:35'),
(14, 13, 'in', 5, '', '2025-08-08 03:21:58'),
(15, 18, 'out', 5, '', '2025-08-08 03:22:12'),
(16, 16, 'out', 5, '', '2025-08-08 03:22:45'),
(17, 16, 'out', 3, '', '2025-08-08 03:23:12');

-- --------------------------------------------------------

--
-- Table structure for table `usages`
--

CREATE TABLE `usages` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `used_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(2, 'admin', '$2y$10$d.U3nDi9pIo/ce9gA5yaC.Vc8ZWsSl9njv/A5.Xo3xPQHQyCJC0rK', 'admin'),
(5, 'viewer1', '$2y$10$IaQvqn5EkTf8cBDiMmBUs.QHV7GyfwXOPhCGNOXLm0yWdhKeB59w6', 'viewer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `usages`
--
ALTER TABLE `usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `stock_logs`
--
ALTER TABLE `stock_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `usages`
--
ALTER TABLE `usages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD CONSTRAINT `stock_logs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `usages`
--
ALTER TABLE `usages`
  ADD CONSTRAINT `usages_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
