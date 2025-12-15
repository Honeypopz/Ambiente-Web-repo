-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 06:19 PM
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
-- Database: `villasbrenes`
--

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `reservation_date` datetime NOT NULL,
  `status` enum('Pendiente','Confirmado','Completado','Cancelado') DEFAULT 'Pendiente',
  `email_sent` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `reservation_date`, `status`, `email_sent`) VALUES
(1, 10, '2025-12-15 00:00:00', '', 0),
(2, 10, '2025-12-15 00:00:00', '', 1),
(3, 10, '2025-12-15 00:00:00', '', 1),
(4, 10, '2025-12-16 00:00:00', '', 1),
(5, 10, '2025-12-16 00:00:00', '', 1),
(6, 10, '2025-12-16 00:00:00', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('administrador','cliente') DEFAULT 'cliente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contraseña`, `rol`, `fecha_registro`) VALUES
(2, 'Felipe', 'felipe2000@gmail.com', '$2y$10$1OP.UuLfT1GxYqShq5ctxOk43AgGRXhCTibAUXDrLEwDU5lkIxnFO', 'cliente', '2025-11-09 23:52:08'),
(3, 'Antonio Soto', 'antonio123@gmail.com', '$2y$10$BDYDhAYybXZxpdidDZBF2uuzwQYFgvjkx/kXZoJZeDczgjwV2stH6', 'cliente', '2025-11-10 00:28:42'),
(4, 'Sofia Perez', 'sorez@gmail.com', '$2y$10$l.uWv9ufkGXJCaaa6VgEG.RHvNpJDNavvpR4eNJg17LihLCkFY7LW', 'cliente', '2025-11-10 00:38:14'),
(6, 'Esteban Montes', 'esteban11@gmail.com', '$2y$10$CED6dt1yuEtrLp4E7ziT3.SIiKpCBN.8rYuf4o01hWQqH5NNyiH0e', 'cliente', '2025-11-10 01:01:03'),
(7, 'Emanuel Pereira', 'emanuel@gmail.com', '$2y$10$ZhoAtxdI0lpsJhMSfkT1kODb7dV33DShB/zyuwRJfit3haT6Wjof.', 'cliente', '2025-11-10 02:54:10'),
(8, 'Administrador', 'admin@gmail.com', '$2y$10$0VNemef2CbKsiM57ajxaKetchlium9xGj.EX.IO0/GQv4cUV0jZ7u', 'administrador', '2025-11-10 03:02:54'),
(9, 'jose', '123@gmail.com', '$2y$10$MWlcQZKbn0BuczETTQe0DOmgUerZUZrgJxQ3jeRDkxE9uEWxU9ONu', 'cliente', '2025-11-26 01:40:11'),
(10, 'Jason Arias', 'jasonariasm1999@gmail.com', '$2y$10$8kXGPKvVzGy2O2WDJWL/auXlRhZbeYOuBWc.9Df6OXxeGB7aMKCB.', 'cliente', '2025-12-15 13:41:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservas_usuarios` (`usuario_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reservas_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
