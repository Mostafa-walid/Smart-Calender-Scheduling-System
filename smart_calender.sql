-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2024 at 08:57 PM
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
-- Database: `smart_calender`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `ID` int(11) NOT NULL,
  `task_name` text NOT NULL,
  `task_description` text NOT NULL,
  `task_date` date NOT NULL,
  `adderid` int(11) NOT NULL,
  `addertype` text NOT NULL,
  `collab` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `is_admin`) VALUES
(1, 'admin', '$2y$10$BAaF9256gwviisBS4JcyyebAKdQEYONtdogcnQOPebkf8o0lzicqy', 'admin@admin.com', 1),
(5, 'mostafa', '$2y$10$bQMg9Sm2gjXIHY6gt3CtLOGw4Bs3.HkoZ40Y8NWP2QOPQTQHwZ1Qu', 'mostafa2206331@miuegypt.edu.eg', 0),
(6, 'abdelrahma', '$2y$10$4tX2sogXTwe6V8R3CucUre2/ujBg.YmJQBXXpjYbX80GZLdLS.NtG', 'abdelrahman2204989@miuegypt.edu.eg', 0),
(11, 'mohamed', '$2y$10$2RCr4PYAse5H0qM83v0cQuhIGS.n8KGlF4iLbx5jVmK6YLYagbGYy', 'mohamed2203042@miuegypt.edu.eg', 0),
(12, 'hussein', '$2y$10$kaY9TohXYX7zDdelW0EBpOpQrLmUXUanWFXWzkfwylae30UZs5hX6', 'hussein2203920@miuegypt.edu.eg', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
