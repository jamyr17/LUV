-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-08-2024 a las 19:56:08
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

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
-- Estructura de tabla para la tabla `tborientacionsexual`
--

CREATE TABLE `tborientacionsexual` (
  `tborientacionsexualid` int(11) NOT NULL,
  `tborientacionsexualnombre` varchar(30) NOT NULL,
  `tborientacionsexualdescripcion` varchar(100) NOT NULL,
  `tborientacionsexualestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tborientacionsexual`
--

INSERT INTO `tborientacionsexual` (`tborientacionsexualid`, `tborientacionsexualnombre`, `tborientacionsexualdescripcion`, `tborientacionsexualestado`) VALUES
(1, 'Heterosexual', 'Atracción emocional, romántica o sexual hacia personas del sexo opuesto.', 1),
(2, 'Homosexual', 'Atracción emocional, romántica o sexual hacia personas del mismo sexo.', 1),
(3, 'Bisexual', 'Atracción emocional, romántica o sexual hacia personas de ambos sexos.', 1),
(4, 'Pansexual', 'Atracción emocional, romántica o sexual hacia personas, independientemente de su sexo o identidad de', 1),
(5, 'Asexual', 'Falta de atracción sexual hacia cualquier persona. Pueden o no experimentar atracción romántica.', 1),
(6, 'Demisexual', 'Atracción sexual solo después de haber formado un fuerte vínculo emocional con alguien.', 1),
(7, 'Grisexual', 'Experiencia de atracción sexual rara o infrecuente, o que se encuentra en algún punto entre la asexu', 1),
(8, 'Skoliosexual', 'Atracción hacia personas que no se identifican como cisgénero, es decir, personas que son transgéner', 1),
(9, 'Androsexual', 'Atracción hacia personas masculinas o con características masculinas, independientemente de su sexo ', 1),
(10, 'Ginesexual', 'Atracción hacia personas femeninas o con características femeninas, independientemente de su sexo o ', 1),
(11, 'Autosexual', 'Atracción sexual principalmente hacia uno mismo.', 1),
(12, 'Sapiosexual', 'Atracción hacia la inteligencia de una persona, más allá de su apariencia física.', 1),
(13, 'Lithosexualidad (o Akorisexual', 'Experiencia de atracción sexual pero sin necesidad de que sea recíproca o sin deseo de involucrarse ', 1),
(14, 'Polysexual', 'Atracción hacia múltiples géneros, pero no necesariamente todos.', 1),
(15, 'Omnisexual', 'Similar a la pansexualidad, con atracción hacia todos los géneros, pero puede tener una mayor concie', 1),
(16, 'Bicuriousidad', 'Interés o curiosidad por la bisexualidad o por experimentar con ambos sexos, sin necesariamente iden', 1),
(17, 'Heteroflexibilidad', 'Principalmente heterosexual, pero con apertura ocasional hacia el mismo sexo.', 1),
(18, 'Homoflexibilidad', 'Principalmente homosexual, pero con apertura ocasional hacia el sexo opuesto.', 1),
(19, 'Aroflux', 'La orientación romántica varía entre diferentes intensidades de atracción romántica y ausencia de el', 1),
(20, 'Aceflux', 'La orientación sexual varía entre diferentes intensidades de atracción sexual y ausencia de ella.', 1),
(21, 'Quoisexual', 'Dificultad para distinguir entre atracción sexual y otras formas de atracción o afecto, o falta de s', 1),
(22, 'Fraysexual', 'Atracción sexual que se desvanece después de formar un vínculo emocional con alguien.', 1),
(23, 'Recipsexual', 'Experiencia de atracción sexual solo después de saber que la otra persona se siente atraída por uno ', 1),
(24, 'Placiosexual', 'Disfrute de la satisfacción sexual de otros sin necesidad de recibirla en retorno.', 1),
(25, 'Autorromanticismo', 'Atracción romántica hacia uno mismo, a menudo manifestada como disfrutar de actos románticos hacia s', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
