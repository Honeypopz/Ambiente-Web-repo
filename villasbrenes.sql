-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 16-12-2025 a las 00:37:25
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `villasbrenes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductor`
--

CREATE TABLE `conductor` (
  `id_conductor` int(11) NOT NULL,
  `nombre_conductor` varchar(50) DEFAULT NULL,
  `vehiculo` varchar(50) DEFAULT NULL,
  `contacto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_Reservante` varchar(100) NOT NULL,
  `fechaEntrada` date NOT NULL,
  `fechaSalida` date NOT NULL,
  `adultos` int(11) NOT NULL,
  `ninos` int(11) NOT NULL,
  `habitacion` int(11) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_usuario`, `nombre_Reservante`, `fechaEntrada`, `fechaSalida`, `adultos`, `ninos`, `habitacion`, `email`) VALUES
(3, 11, 'Gabriel', '2025-12-19', '2025-12-25', 6, 2, 3, 'jara20042023pc@gmail.com'),
(4, 9, 'jose jara', '2025-12-27', '2025-12-30', 3, 0, 3, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
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
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contraseña`, `rol`, `fecha_registro`) VALUES
(2, 'Felipe', 'felipe2000@gmail.com', '$2y$10$1OP.UuLfT1GxYqShq5ctxOk43AgGRXhCTibAUXDrLEwDU5lkIxnFO', 'cliente', '2025-11-09 23:52:08'),
(3, 'Antonio Soto', 'antonio123@gmail.com', '$2y$10$BDYDhAYybXZxpdidDZBF2uuzwQYFgvjkx/kXZoJZeDczgjwV2stH6', 'cliente', '2025-11-10 00:28:42'),
(4, 'Sofia Perez', 'sorez@gmail.com', '$2y$10$l.uWv9ufkGXJCaaa6VgEG.RHvNpJDNavvpR4eNJg17LihLCkFY7LW', 'cliente', '2025-11-10 00:38:14'),
(6, 'Esteban Montes', 'esteban11@gmail.com', '$2y$10$CED6dt1yuEtrLp4E7ziT3.SIiKpCBN.8rYuf4o01hWQqH5NNyiH0e', 'cliente', '2025-11-10 01:01:03'),
(7, 'Emanuel Pereira', 'emanuel@gmail.com', '$2y$10$ZhoAtxdI0lpsJhMSfkT1kODb7dV33DShB/zyuwRJfit3haT6Wjof.', 'cliente', '2025-11-10 02:54:10'),
(8, 'Administrador', 'admin@gmail.com', '123456', 'administrador', '2025-11-10 03:02:54'),
(9, 'jose', '123@gmail.com', '$2y$10$MWlcQZKbn0BuczETTQe0DOmgUerZUZrgJxQ3jeRDkxE9uEWxU9ONu', 'cliente', '2025-11-26 01:40:11'),
(10, 'Administrador', 'admin2@gmail.com', '$2y$10$xa/iJnWFsxR6A7Xgy0L5HO5/mMszdCIUyaj91ewZ/8GV9lvtCA4Y6', 'administrador', '2025-12-15 16:18:37'),
(11, 'Gabriel', 'jara20042023pc@gmail.com', '$2y$10$ntbpGXWHmhb7ubYMcKQ8o.azxs2NTCNzSRXLzK9W5GM10ARPNGNN.', 'cliente', '2025-12-15 17:20:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viaje`
--

CREATE TABLE `viaje` (
  `id_viaje` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `destino` varchar(100) DEFAULT NULL,
  `vehiculo` varchar(50) NOT NULL,
  `conductor_nombre` varchar(50) DEFAULT NULL,
  `conductor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `conductor`
--
ALTER TABLE `conductor`
  ADD PRIMARY KEY (`id_conductor`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `viaje`
--
ALTER TABLE `viaje`
  ADD PRIMARY KEY (`id_viaje`),
  ADD KEY `conductor_id` (`conductor_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `conductor`
--
ALTER TABLE `conductor`
  MODIFY `id_conductor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `viaje`
--
ALTER TABLE `viaje`
  MODIFY `id_viaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `viaje`
--
ALTER TABLE `viaje`
  ADD CONSTRAINT `viaje_ibfk_1` FOREIGN KEY (`conductor_id`) REFERENCES `conductor` (`id_conductor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
