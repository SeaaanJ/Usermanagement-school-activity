-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2026 at 02:41 PM
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
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `usermanagement`
--

CREATE TABLE `usermanagement` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `gender` enum('male','female','other') DEFAULT 'other',
  `nationality` varchar(80) DEFAULT NULL,
  `contact_number` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usermanagement`
--

INSERT INTO `usermanagement` (`id`, `username`, `email`, `password_hash`, `role`, `firstname`, `lastname`, `gender`, `nationality`, `contact_number`, `created_at`) VALUES
(1, 'mcruz_admin', 'maria.cruz@systemmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9fH0eYl9F8Z0kC5Q8K1Y2K', 'admin', 'Maria', 'Cruz', 'female', 'Filipino', '09171234501', '2026-02-16 08:11:58'),
(2, 'jreyes_admin', 'juan.reyes@systemmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9fH0eYl9F8Z0kC5Q8K1Y2K', 'admin', 'Juan', 'Reyes', 'male', 'Filipino', '09181234502', '2026-02-16 08:11:58'),
(3, 'lgarcia_admin', 'leo.garcia@systemmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9fH0eYl9F8Z0kC5Q8K1Y2K', 'admin', 'Leo', 'Garcia', 'male', 'Filipino', '09191234503', '2026-02-16 08:11:58'),
(4, 'aramos', 'anna.ramos@gmail.com', '$2y$10$e0NR0LYKX2F6Y8C5tG1c2eY1z0uFqO8cG7hL9kM2pQ4rT6uV8wXy', 'user', 'Anna', 'Ramos', 'female', 'Filipino', '09170010001', '2026-02-16 08:11:58'),
(5, 'pjimenez', 'paolo.jimenez@yahoo.com', '$2y$10$e0NR0LYKX2F6Y8C5tG1c2eY1z0uFqO8cG7hL9kM2pQ4rT6uV8wXy', 'user', 'Paolo', 'Jimenez', 'male', 'Filipino', '09170010002', '2026-02-16 08:11:58'),
(6, 'ksantos', 'karen.santos@gmail.com', '$2y$10$e0NR0LYKX2F6Y8C5tG1c2eY1z0uFqO8cG7hL9kM2pQ4rT6uV8wXy', 'user', 'Karen', 'Santos', 'female', 'Filipino', '09170010003', '2026-02-16 08:11:58'),
(7, 'mdizon', 'miguel.dizon@gmail.com', '$2y$10$e0NR0LYKX2F6Y8C5tG1c2eY1z0uFqO8cG7hL9kM2pQ4rT6uV8wXy', 'user', 'Miguel', 'Dizon', 'male', 'Filipino', '09170010004', '2026-02-16 08:11:58'),
(8, 'lfernandez', 'lara.fernandez@gmail.com', '$2y$10$e0NR0LYKX2F6Y8C5tG1c2eY1z0uFqO8cG7hL9kM2pQ4rT6uV8wXy', 'user', 'Lara', 'Fernandez', 'female', 'Filipino', '09170010005', '2026-02-16 08:11:58'),
(9, 'jtorres', 'john.torres@gmail.com', '$2y$10$e0NR0LYKX2F6Y8C5tG1c2eY1z0uFqO8cG7hL9kM2pQ4rT6uV8wXy', 'user', 'John', 'Torres', 'male', 'Filipino', '09170010006', '2026-02-16 08:11:58'),
(10, 'asantiago', 'alyssa.santiago@gmail.com', '$2y$10$e0NR0LYKX2F6Y8C5tG1c2eY1z0uFqO8cG7hL9kM2pQ4rT6uV8wXy', 'user', 'Alyssa', 'Santiago', 'female', 'Filipino', '09170010007', '2026-02-16 08:11:58'),
(11, 'rlopez', 'ryan.lopez@gmail.com', '$2y$10$e0NR0LYKX2F6Y8C5tG1c2eY1z0uFqO8cG7hL9kM2pQ4rT6uV8wXy', 'user', 'Ryan', 'Lopez', 'male', 'Filipino', '09170010008', '2026-02-16 08:11:58'),
(12, 'bvelasco', 'bianca.velasco@gmail.com', '$2y$10$e0NR0LYKX2F6Y8C5tG1c2eY1z0uFqO8cG7hL9kM2pQ4rT6uV8wXy', 'user', 'Bianca', 'Velasco', 'female', 'Filipino', '09170010009', '2026-02-16 08:11:58'),
(13, 'jnavarro', 'james.navarro@gmail.com', '$2y$10$e0NR0LYKX2F6Y8C5tG1c2eY1z0uFqO8cG7hL9kM2pQ4rT6uV8wXy', 'user', 'James', 'Navarro', 'male', 'Filipino', '09170010010', '2026-02-16 08:11:58'),
(14, 'ajacinto', 'angelo.jacinto@gmail.com', '$2y$10$e0NR0LYKX2F6Y8C5tG1c2eY1z0uFqO8cG7hL9kM2pQ4rT6uV8wXy', 'user', 'Angelo', 'Jacinto', 'male', 'Filipino', '09170010050', '2026-02-16 08:11:58'),
(15, 'sad123', 'asd@email.com', '$2y$10$.eyPcyjLoSNa6jriAbPTu.4ZKw9uzS9c4pXCnNcaToFG29HuGMYwq', 'admin', 'Sean', 'Jared', 'male', 'Filipino', '123123', '2026-02-23 15:20:07'),
(17, 'sean11', 'sean@email.com', '$2y$10$MZoNJ34ZUZS1jcF3LGrPdORJK1Ozz0Mss1rBBs/eLp0h9HTQP1BEi', 'user', 'Sean', 'Gastar', 'male', 'Filipino', '2312312123', '2026-02-23 16:45:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `usermanagement`
--
ALTER TABLE `usermanagement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `usermanagement`
--
ALTER TABLE `usermanagement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
