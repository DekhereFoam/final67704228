-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2025 at 03:25 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `profile_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `emp_id` int(8) UNSIGNED ZEROFILL NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`emp_id`, `first_name`, `last_name`, `address`, `phone`, `image`) VALUES
(00000001, 'สมชาย', 'ใจดี', '123 ถนนสุขใจ กรุงเทพฯ', '0812345678', '1764292219_1760725544_images.jpg'),
(00000002, 'วิชัย', 'สุขสม', '456 ถนนร่มเย็น เชียงใหม่', '0823456789', '1764292213_1760725994_pngtree-3d-illustration-of-unhappy-office-worker-png-image_11486940.png'),
(00000004, 'อนันต์', 'เพชรแท้', '101 ถนนหอมหวน ภูเก็ต', '0845678901', '1764292207_1761927541_1760725478_images.png'),
(00000005, 'นภาพร', 'บัวงาม', '202 ถนนสายลม นครราชสีมา', '0856789012', '1764292108_1738334132_p1.jpg'),
(00000006, 'ภูปกร', 'ใจเลิศ', 'ศรีปทุม ชลบุรี', '0891667798', '1764292297_c34fe59768cafc9e0196e33aee2b27b8.jpg'),
(00000007, 'นงเยาว์ ', 'สอนจะโปะ', '200 หมู่ 2 ต.หนองไม้แดง อ.เมือง จ.ชลบุรี', '0398743690', '1764292072_1739264711_eq_63726042c7896.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`emp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `emp_id` int(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
