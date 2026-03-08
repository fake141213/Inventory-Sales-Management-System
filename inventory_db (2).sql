-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2026 at 04:18 PM
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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `created_at`) VALUES
(1, 'Laptop', '2026-03-05 23:06:27'),
(2, 'Mobile', '2026-03-05 23:06:27'),
(3, 'Audio', '2026-03-05 23:06:27'),
(4, 'Accessories', '2026-03-05 23:06:27'),
(5, 'Monitor', '2026-03-05 23:06:27'),
(6, 'Tablet', '2026-03-05 23:06:27');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `vat` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `note` varchar(255) DEFAULT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_method` enum('cash','qr') DEFAULT 'cash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `status`, `subtotal`, `vat`, `total`, `note`, `order_date`, `updated_at`, `payment_method`) VALUES
(1, 1, 'completed', 45794.39, 3205.61, 49000.00, NULL, '2026-03-05 14:30:00', '2026-03-05 23:06:27', 'cash'),
(2, 2, 'completed', 39252.34, 2747.66, 42000.00, NULL, '2026-03-05 11:15:00', '2026-03-05 23:06:27', 'cash'),
(3, 1, 'completed', 72897.20, 5102.80, 78000.00, NULL, '2026-03-04 16:45:00', '2026-03-05 23:06:27', 'cash'),
(4, 3, 'pending', 9252.34, 647.66, 9900.00, NULL, '2026-03-04 09:20:00', '2026-03-05 23:06:27', 'cash'),
(5, 2, 'completed', 71962.62, 5037.38, 77000.00, NULL, '2026-03-03 15:00:00', '2026-03-05 23:06:27', 'cash'),
(6, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-06 02:23:47', '2026-03-06 02:23:47', 'cash'),
(7, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-06 02:24:40', '2026-03-06 02:24:40', 'cash'),
(8, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-06 02:24:59', '2026-03-06 02:24:59', 'cash'),
(9, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-06 02:28:22', '2026-03-06 02:28:22', 'cash'),
(10, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-06 02:29:29', '2026-03-06 02:29:29', 'cash'),
(11, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-06 02:31:36', '2026-03-06 02:31:36', 'cash'),
(12, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-06 02:35:59', '2026-03-06 02:35:59', 'cash'),
(13, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-06 02:36:02', '2026-03-06 02:36:02', 'cash'),
(14, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-06 02:36:10', '2026-03-06 02:36:10', 'cash'),
(15, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-06 02:52:11', '2026-03-06 02:52:11', 'cash'),
(16, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-08 17:31:25', '2026-03-08 17:31:25', 'cash'),
(17, 1, 'pending', 0.00, 0.00, 0.00, NULL, '2026-03-08 17:31:47', '2026-03-08 17:31:47', 'cash'),
(18, 1, 'pending', 0.00, 0.00, 49000.00, NULL, '2026-03-08 17:39:56', '2026-03-08 17:39:56', 'cash'),
(19, 1, 'pending', 0.00, 0.00, 128000.00, NULL, '2026-03-08 17:40:20', '2026-03-08 17:40:20', 'qr'),
(20, 1, 'pending', 0.00, 0.00, 2580.00, NULL, '2026-03-08 17:40:47', '2026-03-08 17:40:47', 'qr'),
(21, 2, 'pending', 0.00, 0.00, 28500.00, NULL, '2026-03-08 17:44:00', '2026-03-08 17:44:00', 'qr'),
(22, 1, 'pending', 0.00, 0.00, 2980.00, NULL, '2026-03-08 17:54:42', '2026-03-08 17:54:42', 'qr');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_id`, `qty`, `unit_price`, `subtotal`) VALUES
(1, 1, 2, 1, 35000.00, 35000.00),
(2, 1, 3, 1, 14000.00, 14000.00),
(3, 2, 1, 1, 42000.00, 42000.00),
(4, 3, 6, 2, 32000.00, 64000.00),
(5, 3, 3, 1, 14000.00, 14000.00),
(6, 4, 5, 1, 9900.00, 9900.00),
(7, 5, 1, 1, 42000.00, 42000.00),
(8, 5, 2, 1, 35000.00, 35000.00),
(9, 18, 2, 1, 35000.00, 35000.00),
(10, 18, 3, 1, 14000.00, 14000.00),
(11, 19, 6, 4, 32000.00, 128000.00),
(12, 20, 8, 2, 1290.00, 2580.00),
(13, 21, 7, 3, 9500.00, 28500.00),
(14, 22, 13, 2, 1490.00, 2980.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `stock`, `category_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'MacBook Air M2', 42000.00, 10, 1, 1, '2026-03-05 23:06:27', '2026-03-08 17:54:02'),
(2, 'iPhone 15 Pro 256GB', 35000.00, 2, 2, 1, '2026-03-05 23:06:27', '2026-03-08 17:39:56'),
(3, 'Sony WH-1000XM5', 14000.00, 11, 3, 1, '2026-03-05 23:06:27', '2026-03-08 17:39:56'),
(4, 'Logitech G502 X Plus', 3990.00, 0, 4, 1, '2026-03-05 23:06:27', '2026-03-05 23:06:27'),
(5, 'Samsung 27\" 4K Monitor', 9900.00, 6, 5, 1, '2026-03-05 23:06:27', '2026-03-05 23:06:27'),
(6, 'iPad Pro M4 11\"', 32000.00, 1, 6, 1, '2026-03-05 23:06:27', '2026-03-08 17:40:20'),
(7, 'AirPods Pro 2nd Gen', 9500.00, 7, 3, 1, '2026-03-05 23:06:27', '2026-03-08 17:44:00'),
(8, 'USB-C Hub 7-in-1', 1290.00, 0, 4, 1, '2026-03-05 23:06:27', '2026-03-08 17:40:47'),
(9, 'Mechanical Keyboard RGB', 1890.00, 15, NULL, 1, '2026-03-06 01:53:30', '2026-03-06 01:53:30'),
(10, 'Wireless Gaming Mouse', 990.00, 20, NULL, 1, '2026-03-06 01:53:30', '2026-03-06 01:53:30'),
(11, 'Gaming Headset 7.1', 1290.00, 12, NULL, 1, '2026-03-06 01:53:30', '2026-03-06 01:53:30'),
(13, 'BASN BsingerPro หูฟังอินเอียร์มอนิเตอร์', 1490.00, 8, NULL, 1, '2026-03-08 17:54:30', '2026-03-08 17:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `stock_logs`
--

CREATE TABLE `stock_logs` (
  `log_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('add','sell','adjust') NOT NULL,
  `qty_change` int(11) NOT NULL,
  `qty_before` int(11) NOT NULL,
  `qty_after` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_logs`
--

INSERT INTO `stock_logs` (`log_id`, `product_id`, `user_id`, `type`, `qty_change`, `qty_before`, `qty_after`, `note`, `created_at`) VALUES
(1, 1, 1, 'add', 10, 0, 10, 'รับของจากซัพพลายเออร์', '2026-03-05 23:06:27'),
(2, 2, 1, 'add', 10, 0, 10, 'รับของจากซัพพลายเออร์', '2026-03-05 23:06:27'),
(3, 3, 1, 'add', 15, 0, 15, 'รับของจากซัพพลายเออร์', '2026-03-05 23:06:27'),
(4, 4, 1, 'add', 10, 0, 10, 'รับของจากซัพพลายเออร์', '2026-03-05 23:06:27'),
(5, 5, 1, 'add', 8, 0, 8, 'รับของจากซัพพลายเออร์', '2026-03-05 23:06:27'),
(6, 6, 1, 'add', 7, 0, 7, 'รับของจากซัพพลายเออร์', '2026-03-05 23:06:27'),
(7, 2, 2, 'sell', -7, 10, 3, 'Order #1038, #1039, #1042', '2026-03-05 23:06:27'),
(8, 3, 2, 'sell', -3, 15, 12, 'Order #1040, #1042', '2026-03-05 23:06:27'),
(9, 4, 1, 'sell', -10, 10, 0, 'Order #1033-1036', '2026-03-05 23:06:27'),
(10, 1, 2, 'sell', -2, 10, 8, 'Order #1041, #1038', '2026-03-05 23:06:27'),
(11, 2, 1, 'sell', -1, 3, 2, 'Order #18', '2026-03-08 17:39:56'),
(12, 3, 1, 'sell', -1, 12, 11, 'Order #18', '2026-03-08 17:39:56'),
(13, 6, 1, 'sell', -4, 5, 1, 'Order #19', '2026-03-08 17:40:20'),
(14, 8, 1, 'sell', -2, 2, 0, 'Order #20', '2026-03-08 17:40:47'),
(15, 7, 2, 'sell', -3, 10, 7, 'Order #21', '2026-03-08 17:44:00'),
(16, 13, 1, 'sell', -2, 10, 8, 'Order #22', '2026-03-08 17:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `is_active`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'Admin James', 'admin@gmail.com', '$2y$10$HASH_PLACEHOLDER_ADMIN', 'admin', 1, '2026-03-05 23:06:27', '2026-03-08 17:45:31', NULL),
(2, 'umbrella Staff ', 'umbrella@gmail.com', '$2y$10$HASH_PLACEHOLDER_JANE', 'staff', 1, '2026-03-05 23:06:27', '2026-03-08 17:46:24', NULL),
(3, 'Lucas Staff ', 'lucas@gmail.com', '$2y$10$HASH_PLACEHOLDER_JOHN', 'staff', 1, '2026-03-05 23:06:27', '2026-03-08 17:46:04', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_daily_sales`
-- (See below for the actual view)
--
CREATE TABLE `v_daily_sales` (
`sale_date` date
,`order_count` bigint(21)
,`subtotal` decimal(34,2)
,`vat_amount` decimal(34,2)
,`total_sales` decimal(34,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_low_stock`
-- (See below for the actual view)
--
CREATE TABLE `v_low_stock` (
`product_id` int(11)
,`product_name` varchar(150)
,`category_name` varchar(100)
,`price` decimal(12,2)
,`stock` int(11)
,`stock_status` varchar(12)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_staff_sales_today`
-- (See below for the actual view)
--
CREATE TABLE `v_staff_sales_today` (
`user_id` int(11)
,`staff_name` varchar(100)
,`role` enum('admin','staff')
,`order_count` bigint(21)
,`total_sales` decimal(34,2)
,`avg_per_order` decimal(16,6)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_top_products`
-- (See below for the actual view)
--
CREATE TABLE `v_top_products` (
`product_id` int(11)
,`product_name` varchar(150)
,`category_name` varchar(100)
,`total_qty_sold` decimal(32,0)
,`total_revenue` decimal(34,2)
,`current_stock` int(11)
);

-- --------------------------------------------------------

--
-- Structure for view `v_daily_sales`
--
DROP TABLE IF EXISTS `v_daily_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_daily_sales`  AS SELECT cast(`orders`.`order_date` as date) AS `sale_date`, count(`orders`.`order_id`) AS `order_count`, sum(`orders`.`subtotal`) AS `subtotal`, sum(`orders`.`vat`) AS `vat_amount`, sum(`orders`.`total`) AS `total_sales` FROM `orders` WHERE `orders`.`status` = 'completed' GROUP BY cast(`orders`.`order_date` as date) ORDER BY cast(`orders`.`order_date` as date) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `v_low_stock`
--
DROP TABLE IF EXISTS `v_low_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_low_stock`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, `c`.`category_name` AS `category_name`, `p`.`price` AS `price`, `p`.`stock` AS `stock`, CASE WHEN `p`.`stock` = 0 THEN 'Out of Stock' WHEN `p`.`stock` <= 3 THEN 'Critical' ELSE 'Low Stock' END AS `stock_status` FROM (`products` `p` left join `categories` `c` on(`c`.`category_id` = `p`.`category_id`)) WHERE `p`.`stock` <= 5 AND `p`.`is_active` = 1 ORDER BY `p`.`stock` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_staff_sales_today`
--
DROP TABLE IF EXISTS `v_staff_sales_today`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_staff_sales_today`  AS SELECT `u`.`user_id` AS `user_id`, `u`.`name` AS `staff_name`, `u`.`role` AS `role`, count(`o`.`order_id`) AS `order_count`, coalesce(sum(`o`.`total`),0) AS `total_sales`, coalesce(avg(`o`.`total`),0) AS `avg_per_order` FROM (`users` `u` left join `orders` `o` on(`o`.`user_id` = `u`.`user_id` and cast(`o`.`order_date` as date) = curdate() and `o`.`status` = 'completed')) GROUP BY `u`.`user_id`, `u`.`name`, `u`.`role` ORDER BY coalesce(sum(`o`.`total`),0) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `v_top_products`
--
DROP TABLE IF EXISTS `v_top_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_top_products`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, `c`.`category_name` AS `category_name`, sum(`oi`.`qty`) AS `total_qty_sold`, sum(`oi`.`subtotal`) AS `total_revenue`, `p`.`stock` AS `current_stock` FROM (((`order_items` `oi` join `products` `p` on(`p`.`product_id` = `oi`.`product_id`)) join `orders` `o` on(`o`.`order_id` = `oi`.`order_id` and `o`.`status` = 'completed')) left join `categories` `c` on(`c`.`category_id` = `p`.`category_id`)) GROUP BY `p`.`product_id`, `p`.`product_name`, `c`.`category_name`, `p`.`stock` ORDER BY sum(`oi`.`qty`) DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `idx_orders_date` (`order_date`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `idx_order_items_order` (`order_id`),
  ADD KEY `idx_order_items_prod` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_active` (`is_active`);

--
-- Indexes for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_stock_logs_prod` (`product_id`),
  ADD KEY `idx_stock_logs_user` (`user_id`),
  ADD KEY `idx_stock_logs_date` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stock_logs`
--
ALTER TABLE `stock_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_item_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD CONSTRAINT `fk_stocklog_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stocklog_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
