-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2024 at 01:43 PM
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
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('Unpaid','Paid') DEFAULT 'Unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `patient_id`, `date`, `amount`, `status`) VALUES
(9, 10, '2024-08-31', 400.00, 'Unpaid'),
(10, 1, '2024-08-31', 1000.00, 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `names` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `telNo` varchar(255) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `age` int(11) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `names`, `email`, `address`, `telNo`, `gender`, `age`, `password`) VALUES
(1, 'germain', '', '123456789', 'gihozondahayogermain@gmail.com', 'male', 0, ''),
(2, 'Kanyana', '', 'Kigali', '0784873039', 'female', 20, ''),
(4, 'keza mignone', '', 'Kigali', '0784873039', 'female', 21, ''),
(6, 'keza nicole', '', 'Kigali', '0784873033', 'female', 22, ''),
(9, 'ndahayo', 'ndahayo@gmail.com', 'byumba', '0784873039', 'male', 29, '$2y$10$o3tR3BkOr9oXkLn6RpC7Q.qdbQxE8EeoXcyZgwAS9SWflHAJFHr4i'),
(10, 'irasubiza confiance', 'irasubiza@gmail.com', 'byumba', '0784873039', 'male', 20, '$2y$10$LbzuM/AU9YsUq0EgxTD2iuIrUBj3ynTJlcBgMbstjh7hw0aZD0ufe');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `names` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Doctor','Nurse','Staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `names`, `username`, `password`, `role`) VALUES
(1, 'Administrator', 'admin', '$2y$10$0y0Jwd6/5cQ4LiMJMwzAE.8ygH1fWcaRyNehltvjsA2DZhK.Jj4CG', 'Nurse'),
(18, 'Ndahayo', 'gihozondahayogermain@gmail.com', '$2y$10$1UY7LoJi/G02GfjlXIYjCuBvBhQP37FwLNaXPZ/drwAamTKw/4Ava', 'Admin'),
(19, 'keza', 'yvette', '$2y$10$4y1TQ8RuFt4aToT306MXSeJm.jGslHC2KScf0CeCqVRzdpgqCETJ2', 'Nurse'),
(21, 'Uwineza', 'keza', '$2y$10$Pz6Zirrv2nYWmfGf/PeuDOxQDw9DWG9.SnmLKf2B18OdD5KjhKVKu', 'Nurse'),
(25, 'adeline', 'adeline', '$2y$10$mdv2PqWouCzUcyOFcRKdTev3V6Tjush5bGm2ShXZdIF1gJLAP2236', 'Nurse'),
(27, 'Germain', 'Germain', '$2y$10$cpYtKdKalTJ2nHzXTXYmO.EuIIzP7XNZ.oJ3YKC1ePvDrEp9lTmIK', 'Nurse');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`names`);

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
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
