-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 09:42 PM
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
(10, 1, '2024-08-31', 1000.00, 'Paid'),
(11, 11, '2024-10-02', 1000.00, 'Unpaid'),
(12, 11, '2024-10-02', 20000.00, 'Unpaid'),
(13, 13, '2024-10-02', 20000.00, 'Unpaid'),
(14, 13, '2024-10-02', 400.00, 'Unpaid'),
(15, 14, '2024-10-02', 1000.00, 'Unpaid'),
(16, 13, '2024-10-03', 1000.00, 'Unpaid');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `verification_code` varchar(6) DEFAULT NULL,
  `expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `verification_code`, `expiry`) VALUES
(1, 18, '477526', '2024-10-08 20:54:27');

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
(10, 'irasubiza confiance', 'irasubiza@gmail.com', 'byumba', '0784873039', 'male', 20, '$2y$10$LbzuM/AU9YsUq0EgxTD2iuIrUBj3ynTJlcBgMbstjh7hw0aZD0ufe'),
(11, 'makasi confiance', 'gihozondahayogermain@gmail.com', 'byumba', '0784873039', 'male', 23, '$2y$10$o789TgnJI8fNCiljMjkjIe/dhQVOuyLGn/kVRtIUo7ROhUd2kKeYG'),
(12, 'Mukabaziki Gaudelive', '', 'Kigali', '0786543256', 'male', 29, '0'),
(13, 'amizero christopher', 'gihozondahayogermain@gmail.com', 'byumba', '0784873039', 'male', 23, '$2y$10$xcmTBARiaVNvW3UbbsMFNugAvGmZB1TLWd9DtBPMyG18nm6pwAKhe'),
(14, 'kaze Christian', 'gihozondahayogermain@gmail.com', 'byumba', '0786544322', 'male', 23, '$2y$10$CmTGsITkX95tuLt88cs/u./Za2sevTJCLlxSZw1EIgWwGigO6jwxG');

-- --------------------------------------------------------

--
-- Table structure for table `project_settings`
--

CREATE TABLE `project_settings` (
  `id` int(11) NOT NULL,
  `background_image` varchar(255) DEFAULT 'default_background.png',
  `project_title` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_settings`
--

INSERT INTO `project_settings` (`id`, `background_image`, `project_title`, `updated_at`) VALUES
(1, 'cable.png', 'Patient Pay', '2024-10-02 13:58:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `names` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Doctor','Nurse','Staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `names`, `username`, `email`, `password`, `role`) VALUES
(1, 'Administrator', 'admin', '', '$2y$10$0y0Jwd6/5cQ4LiMJMwzAE.8ygH1fWcaRyNehltvjsA2DZhK.Jj4CG', 'Nurse'),
(18, 'Ndahayo', 'mbabazi', 'gihozondahayogermain@gmail.com', '$2y$10$1UY7LoJi/G02GfjlXIYjCuBvBhQP37FwLNaXPZ/drwAamTKw/4Ava', 'Admin'),
(19, 'keza', 'yvette', 'ndahayo@gmail.com', '$2y$10$4y1TQ8RuFt4aToT306MXSeJm.jGslHC2KScf0CeCqVRzdpgqCETJ2', 'Admin'),
(21, 'Uwineza', 'keza', 'keza@gmail.com', '$2y$10$Pz6Zirrv2nYWmfGf/PeuDOxQDw9DWG9.SnmLKf2B18OdD5KjhKVKu', 'Admin'),
(25, 'adeline', 'adeline', 'adeline@gmail.com', '$2y$10$mdv2PqWouCzUcyOFcRKdTev3V6Tjush5bGm2ShXZdIF1gJLAP2236', 'Admin'),
(27, 'Germain', 'Germain', 'germain@gmail.com', '$2y$10$cpYtKdKalTJ2nHzXTXYmO.EuIIzP7XNZ.oJ3YKC1ePvDrEp9lTmIK', 'Admin'),
(29, 'Ndahimana deo', 'ndahimana', 'gihozondahayogermain@gmail.com', '$2y$10$FprRq.tsiuQqdhZ9q9sLZuuj8h.PdydC5HPvHb2eCSKrJX87hNDKm', 'Staff');

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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`names`);

--
-- Indexes for table `project_settings`
--
ALTER TABLE `project_settings`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `project_settings`
--
ALTER TABLE `project_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
