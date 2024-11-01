-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-11-2024 a las 22:10:45
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
-- Base de datos: `bdluv`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbusuario`
--

CREATE TABLE IF NOT EXISTS `tbusuario` (
  `tbusuarioid` int(11) NOT NULL,
  `tbpersonaid` int(11) NOT NULL,
  `tbtipousuarioid` int(11) NOT NULL,
  `tbusuarionombre` varchar(255) NOT NULL,
  `tbusuariocontrasena` varchar(63) NOT NULL,
  `tbusuarioestado` tinyint(1) NOT NULL,
  `tbusuarioimagen` varchar(200) NOT NULL,
  `tbusuariocondicion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbusuario`
--

INSERT INTO `tbusuario` (`tbusuarioid`, `tbpersonaid`, `tbtipousuarioid`, `tbusuarionombre`, `tbusuariocontrasena`, `tbusuarioestado`, `tbusuarioimagen`, `tbusuariocondicion`) VALUES
(1, 1, 1, 'jamyrg', '123', 1, '', ''),
(2, 2, 1, 'jeycobbg', '123', 1, '', ''),
(3, 3, 1, 'profe', '123', 1, '', ''),
(4, 4, 1, 'gerald', '123', 1, '', ''),
(5, 5, 1, 'kevin', '123', 1, '', ''),
(6, 6, 1, 'jamel', '123', 1, '', ''),
(7, 7, 2, 'josue', '123', 1, '', ''),
(8, 8, 2, 'lucia', '123', 1, '../resources/img/profile/lucia.webp', ''),
(9, 9, 2, 'fernanda', '123', 1, '../resources/img/profile/fernanda.webp', ''),
(10, 10, 2, 'andres', '123', 1, '', ''),
(11, 11, 1, 'admin', 'admin', 1, '', ''),
(12, 12, 2, 'client', 'client', 1, '../resources/img/profile/client.webp', 'Disponible'),
(13, 13, 2, 'user', 'user', 1, '', 'Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbusuariomensaje`
--

CREATE TABLE IF NOT EXISTS `tbusuariomensaje` (
  `tbusuariomensajeid` int(11) NOT NULL,
  `tbusuariomensajeentradaid` int(11) NOT NULL,
  `tbusuariomensajesalidaid` int(11) NOT NULL,
  `tbusuariomensajedescripcion` varchar(255) NOT NULL,
  `tbusuariomensajefecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbusuariomensaje`
--

INSERT INTO `tbusuariomensaje` (`tbusuariomensajeid`, `tbusuariomensajeentradaid`, `tbusuariomensajesalidaid`, `tbusuariomensajedescripcion`, `tbusuariomensajefecha`) VALUES
(5, 13, 12, 'Hola..', '2024-10-31 21:45:26'),
(6, 13, 12, 'hi..\n', '2024-10-31 21:58:16'),
(7, 12, 13, 'que pasa perre?\n', '2024-10-31 22:38:00'),
(9, 12, 13, 'todo tranqui', '2024-10-31 22:43:24'),
(11, 12, 13, 'jajaa', '2024-11-01 03:19:52'),
(12, 12, 13, 'si pa\n', '2024-11-01 05:36:56'),
(13, 13, 12, 'todo bien?\n', '2024-11-01 05:39:00'),
(14, 12, 13, 'bien bien', '2024-11-01 05:39:22');

--
-- Índices para tablas volcadas
--

ALTER TABLE `tbusuario`
  ADD PRIMARY KEY (`tbusuarioid`);

ALTER TABLE `tbusuariomensaje`
  ADD PRIMARY KEY (`tbusuariomensajeid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

ALTER TABLE `tbusuario`
  MODIFY `tbusuarioid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

ALTER TABLE `tbusuariomensaje`
  MODIFY `tbusuariomensajeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
