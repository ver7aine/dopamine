-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2025 at 06:38 AM
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
-- Database: `cafe_dopamine`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `full_name`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$123456789012345678901234567890', 'Administrator', 'admin@cafedopamine.com', '2025-12-04 20:15:56');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `participants` int(11) DEFAULT 0,
  `is_upcoming` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `date`, `time`, `location`, `participants`, `is_upcoming`, `created_at`) VALUES
(1, 'Latte Art Workshop', 'Pelajari seni membuat latte art dari barista profesional', '2025-12-12', '14:00:00', 'Area Workshop Cafe Dopamine', 15, 1, '2025-12-04 20:15:57'),
(2, 'Coffee Cupping Session', 'Pengalaman mencicipi berbagai jenis kopi dari berbagai daerah', '2025-12-08', '19:00:00', 'Ruang Private Cafe Dopamine', 20, 1, '2025-12-04 20:15:57'),
(3, 'Live Music Acoustic Night', 'Nikmati malam dengan iringan musik akustik live', '2025-12-06', '20:00:00', 'Area Lounge Cafe Dopamine', 50, 1, '2025-12-04 20:15:57'),
(4, 'Book Club Meeting', 'Diskusi buku bulanan dengan suasana nyaman', '2025-12-01', '16:00:00', 'Ruang Baca Cafe Dopamine', 25, 0, '2025-12-04 20:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` enum('coffee','non-coffee','food','dessert') NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_popular` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `category`, `image`, `is_popular`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Espresso', 'Kopi murni dengan rasa kuat dan aroma khas', 25000.00, 'coffee', NULL, 1, 1, '2025-12-04 20:15:56', '2025-12-04 20:15:56'),
(2, 'Cappuccino', 'Espresso dengan susu steamed dan busa susu', 32000.00, 'coffee', NULL, 1, 1, '2025-12-04 20:15:56', '2025-12-04 20:15:56'),
(3, 'Latte Art', 'Espresso dengan susu steamed dan gambar seni di atasnya', 35000.00, 'coffee', NULL, 0, 0, '2025-12-04 20:15:56', '2025-12-05 01:32:36'),
(4, 'Matcha Latte', 'Teh matcha premium dengan susu steamed', 30000.00, 'non-coffee', NULL, 1, 1, '2025-12-04 20:15:56', '2025-12-04 20:15:56'),
(6, 'Croissant', 'Pastry renyah dengan mentiga berkualitas', 22000.00, 'food', NULL, 1, 1, '2025-12-04 20:15:56', '2025-12-04 20:15:56'),
(7, 'Sandwich Avocado', 'Roti gandum dengan alpukat, telur, dan sayuran', 38000.00, 'food', NULL, 1, 1, '2025-12-04 20:15:56', '2025-12-04 20:15:56'),
(8, 'Tiramisu', 'Dessert Italia dengan kopi dan mascarpone', 35000.00, 'dessert', NULL, 1, 1, '2025-12-04 20:15:56', '2025-12-04 20:15:56'),
(9, 'Red Velvet Cake', 'Kue lembut dengan cream cheese frosting', 32000.00, 'dessert', NULL, 0, 1, '2025-12-04 20:15:56', '2025-12-04 20:15:56'),
(10, 'Affogato', 'Gelato vanilla dengan espresso panas', 40000.00, 'dessert', NULL, 1, 1, '2025-12-04 20:15:56', '2025-12-04 20:15:56'),
(11, 'Blue Curacao', 'Minuman soda yang menyegarkan dahaga', 22000.00, 'non-coffee', NULL, 0, 1, '2025-12-04 20:26:24', '2025-12-05 01:50:20');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `comment` text NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `avatar` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `role`, `comment`, `rating`, `avatar`, `is_approved`, `created_at`) VALUES
(1, 'Budi Santoso', 'Pecinta Kopi', 'Kopi di Cafe Dopamine selalu konsisten kualitasnya. Suasana yang nyaman membuat betah berlama-lama di sini.', 5, NULL, 1, '2025-12-04 20:15:57'),
(2, 'Sari Dewi', 'Freelancer', 'Tempat favorit saya untuk bekerja. WiFi cepat, kopi enak, dan suasana tenang. Highly recommended!', 5, NULL, 1, '2025-12-04 20:15:57'),
(3, 'Ahmad Fauzi', 'Mahasiswa', 'Latte art workshop-nya sangat informatif. Sekarang saya bisa membuat gambar sederhana di kopi saya sendiri!', 4, NULL, 1, '2025-12-04 20:15:57'),
(4, 'Maya Indah', 'Blogger Kuliner', 'Dessert tiramisu di sini adalah yang terbaik di kota! Tidak terlalu manis dan teksturnya sempurna.', 5, NULL, 1, '2025-12-04 20:15:57'),
(5, 'Rizky Pratama', 'Wiraswasta', 'Tempat meeting yang sempurna. Ruangannya privat dan pelayanannya excellent.', 4, NULL, 1, '2025-12-04 20:15:57'),
(6, 'Kuple', 'Mahasiswa', 'Tempatnya sangat nyaman, betah berlama-lama disini', 5, NULL, 1, '2025-12-05 01:28:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
