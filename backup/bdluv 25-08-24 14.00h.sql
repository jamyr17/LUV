-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-08-2024 a las 08:16:04
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
-- Estructura de tabla para la tabla `tbperfilusuariopersonal`
--

CREATE TABLE `tbperfilusuariopersonal` (
  `tbperfilusuariopersonalid` int(11) NOT NULL,
  `tbperfilusuariopersonalcriterio` varchar(1024) NOT NULL,
  `tbperfilusuariopersonalvalor` varchar(1024) NOT NULL,
  `tbusuarioid` int(11) NOT NULL,
  `tbperfilusuariopersonalestado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbperfilusuariopersonal`
--

INSERT INTO `tbperfilusuariopersonal` (`tbperfilusuariopersonalid`, `tbperfilusuariopersonalcriterio`, `tbperfilusuariopersonalvalor`, `tbusuarioid`, `tbperfilusuariopersonalestado`) VALUES
(1, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Rock,Introvertido,Ciencias,Fútbol,Pintura,Mañana,Italiana,Lectura,Activo,Comedia,Ficción,Suspenso,Cristianismo,Conservador,Frecuente,Tecnófilo,Perros,ONGs,Discotecas,Facebook', 10, 1),
(2, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Rock,Introvertido,Ciencias,Fútbol,Pintura,Mañana,Italiana,Lectura,Activo,Comedia,Ficción,Suspenso,Cristianismo,Conservador,Frecuente,Tecnófilo,Perros,ONGs,Discotecas,Facebook', 11, 1),
(3, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'mamamam,Introvertido,Ciencias,Fútbol,Pintura,Mañana,Italiana,Lectura,Activo,Comedia,Ficción,Suspenso,Cristianismo,Conservador,Frecuente,Tecnófilo,Perros,ONGs,Discotecas,Facebook', 12, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
