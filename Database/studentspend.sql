-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 05:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `studentspend`
--

-- --------------------------------------------------------

--
-- Table structure for table `budget`
--

CREATE TABLE `budget` (
  `budget_id` int(11) NOT NULL,
  `budget_category` varchar(250) NOT NULL,
  `amount` int(100) NOT NULL,
  `user_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budget`
--

INSERT INTO `budget` (`budget_id`, `budget_category`, `amount`, `user_id`) VALUES
(59, 'personal', 300, 11),
(60, 'Dwqe', 120, 11),
(68, 'eqweqwe', 1230, 11),
(72, 'eqwe', 3000, 6),
(74, '312312', 123, 6),
(75, '312312e1e', 123, 6),
(76, '123123', 2, 6),
(77, '123123wqeqwe', 231, 6),
(78, 'eqweq', 3, 6),
(79, 'ewqewq', 4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `expense_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `amount` int(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `user_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`expense_id`, `name`, `amount`, `category`, `date`, `user_id`) VALUES
(72, 'eqwewqe', 1233123, 'personal', '2025-05-07', 11),
(78, 'eqwek', 2000, 'eqwe', '2025-05-21', 6),
(79, 'e12312', 3, '312312', '2025-05-22', 6),
(80, '3123', 2, 'eqweq', '2025-05-22', 6);

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `photo` varchar(150) NOT NULL,
  `user_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`id`, `photo`, `user_id`) VALUES
(20, 'images.jpg', 6),
(21, 'balloon-7102509__340.jpg', 11);

-- --------------------------------------------------------

--
-- Table structure for table `total_allowance`
--

CREATE TABLE `total_allowance` (
  `total_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `total_allowance`
--

INSERT INTO `total_allowance` (`total_id`, `user_id`, `amount`) VALUES
(42, 6, '4000'),
(43, 11, '3400'),
(44, 11, '3400');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(250) NOT NULL,
  `username` varchar(250) NOT NULL,
  `fullname` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `fullname`, `email`, `password`) VALUES
(6, 'Jay154421', 'Jay Bodiongan', 'jaybodiongan0@gmail.com', '$2y$10$mcNxuQUAPNvO/SL/9wvpsuE94gB5DhHTKcKzvqWEli1bWwWXc0/2.'),
(7, '123456', '123123', '123456@gmail.com', '$2y$10$SzH4U9LHF/jOCZyiJqnIDel47kP9SCEo7IPdq8pck/oY0g6za4jZS'),
(9, 'jay963', 'Jay Bodiongan', 'jaybodiongan12@gmail.com', '$2y$10$O8/jnGDQqTj.JsP7cQUU2OfbKrIzxa5NsI3Jbaclv81Wv2zYgxWeO'),
(10, 'john154421', 'John Y. Klaro', 'john.klaro@gmail.com', '$2y$10$pjrD4NmPhabsps2i0tSsR.EFlA/oJO/nLtln4lliIYplY8fH1DdUa'),
(11, 'onii123', 'eqwewqe', 'onii@gmail.com', '$2y$10$1Qw9O9tTAtkBUWCZKWot0Oqy9MCMccU6vwTRa/d/TMrqrNsow1JQa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budget`
--
ALTER TABLE `budget`
  ADD PRIMARY KEY (`budget_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `total_allowance`
--
ALTER TABLE `total_allowance`
  ADD PRIMARY KEY (`total_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `budget`
--
ALTER TABLE `budget`
  MODIFY `budget_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `total_allowance`
--
ALTER TABLE `total_allowance`
  MODIFY `total_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budget`
--
ALTER TABLE `budget`
  ADD CONSTRAINT `budget_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `total_allowance`
--
ALTER TABLE `total_allowance`
  ADD CONSTRAINT `total_allowance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
