-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-08-2024 a las 17:48:15
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
-- Estructura de tabla para la tabla `tbareaconocimiento`
--

CREATE TABLE `tbareaconocimiento` (
  `tbareaconocimientoid` int(11) NOT NULL,
  `tbareaconocimientonombre` varchar(255) NOT NULL,
  `tbareaconocimientodescripcion` varchar(255) NOT NULL,
  `tbareaconocimientoestado` tinyint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbareaconocimiento`
--

INSERT INTO `tbareaconocimiento` (`tbareaconocimientoid`, `tbareaconocimientonombre`, `tbareaconocimientodescripcion`, `tbareaconocimientoestado`) VALUES
(1, 'Matemáticas', 'Estudio de los números, las formas y los patrones.', 1),
(2, 'Física', 'Ciencia que estudia las propiedades y el comportamiento de la materia y la energía.', 1),
(3, 'Biología', 'Ciencia que estudia a los seres vivos y sus procesos vitales.', 1),
(4, 'Ingeniería Informática', 'Disciplina que se encarga del diseño y desarrollo de sistemas y aplicaciones informáticas.', 1),
(5, 'Psicología', 'Ciencia que estudia el comportamiento y los procesos mentales de los individuos.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbcampus`
--

CREATE TABLE `tbcampus` (
  `tbuniversidadcampusid` int(11) NOT NULL,
  `tbuniversidadid` int(11) NOT NULL,
  `tbuniversidadcampusnombre` varchar(191) NOT NULL,
  `tbuniversidadcampusdireccion` varchar(191) NOT NULL,
  `tbuniversidadcampusestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbcampus`
--

INSERT INTO `tbcampus` (`tbuniversidadcampusid`, `tbuniversidadid`, `tbuniversidadcampusnombre`, `tbuniversidadcampusdireccion`, `tbuniversidadcampusestado`) VALUES
(1, 2, 'Rodrigo Facio Brenes', 'San José 300 mts este', 1),
(2, 2, 'Campus Sarapiquí', 'Universidad Nacional de Costa Rica UNA- Sede Región Huetar Norte, Campus Sarapiquí, Heredia Province, La Victoria, Costa Rica', 1),
(3, 5, 'Campus Nicoya', 'Universidad Nacional de Costa Rica Campus Nicoya, 150, Guanacaste Province, Nicoya, Costa Rica', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbcampusespecializacion`
--

CREATE TABLE `tbcampusespecializacion` (
  `tbcampusespecializacionid` int(11) NOT NULL,
  `tbcampusespecializacionnombre` varchar(63) NOT NULL,
  `tbcampusespecializaciondescripcion` varchar(255) NOT NULL,
  `tbcampusespecializacionestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbcampusespecializacion`
--

INSERT INTO `tbcampusespecializacion` (`tbcampusespecializacionid`, `tbcampusespecializacionnombre`, `tbcampusespecializaciondescripcion`, `tbcampusespecializacionestado`) VALUES
(1, 'claro claro', 'si pa', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbgenero`
--

CREATE TABLE `tbgenero` (
  `tbgeneroid` int(11) NOT NULL,
  `tbgeneronombre` varchar(63) NOT NULL,
  `tbgenerodescripcion` varchar(255) NOT NULL,
  `tbgeneroestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbgenero`
--

INSERT INTO `tbgenero` (`tbgeneroid`, `tbgeneronombre`, `tbgenerodescripcion`, `tbgeneroestado`) VALUES
(1, 'Masculino', 'Generalmente se refiere a personas que se identifican con el género masculino, lo que suele estar asociado a hombres cisgénero (aquellos cuya identidad de género coincide con el sexo asignado al nacer).', 1),
(2, 'Femenino', 'Se refiere a personas que se identifican con el género femenino, lo que usualmente incluye a mujeres cisgénero (aquellas cuya identidad de género coincide con el sexo asignado al nacer).', 1),
(3, 'No binario', 'Este término abarca a personas cuya identidad de género no encaja estrictamente en las categorías de masculino o femenino. Puede incluir identidades como género fluido, agénero, bigénero, entre otros.', 1),
(4, 'Género fluido', 'Se refiere a personas cuya identidad de género puede cambiar o variar con el tiempo entre diferentes géneros.', 1),
(5, 'Agénero', 'Describe a personas que no se identifican con ningún género o que tienen una ausencia de identidad de género.', 1),
(6, 'Bigénero', 'Se refiere a personas que experimentan dos géneros, ya sea de manera simultánea o alternante. Estos géneros pueden ser masculinos, femeninos, o una combinación con otras identidades de género.', 1),
(7, 'Genero queer', 'Un término general que algunas personas usan para describir una identidad de género que no se ajusta a las normas convencionales de género. Es un término flexible y puede tener diferentes significados para diferentes personas.', 1),
(8, 'Demigénero', 'Incluye identidades como demi-hombre o demi-mujer, donde la persona se identifica parcialmente con un género (masculino o femenino) pero no completamente.', 1),
(9, 'Intergénero', 'Se refiere a personas que tienen una identidad de género que está entre las categorías de masculino y femenino, o es una combinación de ambos.', 1),
(10, 'Dos espíritus', 'Un término utilizado por algunas culturas indígenas en Norteamérica para describir a una persona que encarna tanto el espíritu masculino como el femenino. Es un término culturalmente específico y tiene significados únicos en diferentes comunidades indígen', 1),
(11, 'ya', 'ya', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbimagen`
--

CREATE TABLE `tbimagen` (
  `tbimagenid` int(11) NOT NULL,
  `tbimagencrudid` int(11) NOT NULL,
  `tbimagenregistroid` int(11) NOT NULL,
  `tbimagendirectorio` varchar(255) NOT NULL,
  `tbimagennombre` varchar(255) NOT NULL,
  `tbimagenestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbimagen`
--

INSERT INTO `tbimagen` (`tbimagenid`, `tbimagencrudid`, `tbimagenregistroid`, `tbimagendirectorio`, `tbimagennombre`, `tbimagenestado`) VALUES
(1, 0, 3, '0', '../resources/img/genero/', 1),
(2, 3, 0, 'no-binario', '../resources/img/genero/', 1),
(3, 3, 0, '../resources/img/genero/', 'no-binario', 1),
(4, 3, 0, '../resources/img/genero/', 'no-binario.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tborientacionsexual`
--

CREATE TABLE `tborientacionsexual` (
  `tborientacionsexualid` int(11) NOT NULL,
  `tborientacionsexualnombre` varchar(63) NOT NULL,
  `tborientacionsexualdescripcion` varchar(255) NOT NULL,
  `tborientacionsexualestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tborientacionsexual`
--

INSERT INTO `tborientacionsexual` (`tborientacionsexualid`, `tborientacionsexualnombre`, `tborientacionsexualdescripcion`, `tborientacionsexualestado`) VALUES
(1, 'Heterosexual', 'Atracción sexual hacia personas del sexo opuesto', 1),
(2, 'Gay', 'Hombre que se siente atraído por otros hombres.', 1),
(3, 'Lesbiana', 'Mujer que se siente atraída por otras mujeres.', 1),
(4, 'Bisexual', 'Atracción sexual hacia personas de más de un género, típicamente hacia hombres y mujeres.', 1),
(5, 'Pansexual', 'Atracción sexual hacia personas independientemente de su género o identidad de género.', 1),
(6, 'Asexual', 'Falta de atracción sexual hacia otros. Pueden existir niveles de atracción romántica o emocional, pero no sexual.', 1),
(7, 'Demisexual', 'Atracción sexual que se desarrolla solo después de establecer una conexión emocional fuerte con alguien.', 1),
(8, 'Sapiosexual', 'Atracción sexual hacia la inteligencia de una persona, más que hacia su género o apariencia física.', 1),
(9, 'Autosexual', 'Atracción sexual hacia uno mismo.', 1),
(10, 'Androsexual', 'Atracción sexual hacia personas masculinas o aquellos que exhiben características masculinas.', 1),
(11, 'Ginesexual', 'Atracción sexual hacia personas femeninas o aquellos que exhiben características femeninas.', 1),
(12, 'Polisexual', 'Atracción sexual hacia varios géneros, pero no necesariamente todos.', 1),
(13, 'Skoliosexual', 'Atracción sexual hacia personas no binarias o de género no conformista.', 1),
(14, 'Omnisexual', 'Similar a la pansexualidad, pero con un énfasis en la atracción hacia todos los géneros, reconociendo las diferencias entre ellos.', 1),
(15, 'Grisexual', 'Ubicada entre la sexualidad y la asexualidad; las personas que se identifican como grisexuales experimentan atracción sexual en muy pocas ocasiones o en circunstancias específicas.', 1),
(16, 'Fraysexual', 'Atracción sexual que disminuye una vez que se forma un vínculo emocional o romántico con alguien.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbsolicitudcampusuniversidad`
--

CREATE TABLE `tbsolicitudcampusuniversidad` (
  `tbsolicitudcampusuniversidadid` int(11) NOT NULL,
  `tbsolicitudcampusuniversidadnombre` varchar(63) NOT NULL,
  `tbsolicituduniversidadid` int(11) NOT NULL,
  `tbsolicitudcampusuniversidadestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbsolicitudgenero`
--

CREATE TABLE `tbsolicitudgenero` (
  `tbsolicitudgeneroid` int(11) NOT NULL,
  `tbsolicitudgeneronombre` varchar(63) NOT NULL,
  `tbsolicitudgeneroestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbsolicitudgenero`
--

INSERT INTO `tbsolicitudgenero` (`tbsolicitudgeneroid`, `tbsolicitudgeneronombre`, `tbsolicitudgeneroestado`) VALUES
(1, 'kiul', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbsolicitudorientacionsexual`
--

CREATE TABLE `tbsolicitudorientacionsexual` (
  `tbsolicitudorientacionsexualid` int(11) NOT NULL,
  `tbsolicitudorientacionsexualnombre` varchar(63) NOT NULL,
  `tbsolicitudorientacionsexualestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbsolicitudorientacionsexual`
--

INSERT INTO `tbsolicitudorientacionsexual` (`tbsolicitudorientacionsexualid`, `tbsolicitudorientacionsexualnombre`, `tbsolicitudorientacionsexualestado`) VALUES
(1, 'solo eso?', 0),
(2, 'aca', 0),
(3, 'aca', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbsolicituduniversidad`
--

CREATE TABLE `tbsolicituduniversidad` (
  `tbsolicituduniversidadid` int(11) NOT NULL,
  `tbsolicituduniversidadnombre` varchar(255) NOT NULL,
  `tbsolicituduniversidadestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbsolicituduniversidad`
--

INSERT INTO `tbsolicituduniversidad` (`tbsolicituduniversidadid`, `tbsolicituduniversidadnombre`, `tbsolicituduniversidadestado`) VALUES
(1, 'solo eso no', 0),
(2, 'ife', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtipousuario`
--

CREATE TABLE `tbtipousuario` (
  `tbtipousuarioid` int(11) NOT NULL,
  `tbtipousuarionombre` varchar(255) NOT NULL,
  `tbtipousuarioestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbtipousuario`
--

INSERT INTO `tbtipousuario` (`tbtipousuarioid`, `tbtipousuarionombre`, `tbtipousuarioestado`) VALUES
(1, 'Administrador', 1),
(2, 'Usuario', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuniversidad`
--

CREATE TABLE `tbuniversidad` (
  `tbuniversidadid` int(11) NOT NULL,
  `tbuniversidadnombre` varchar(255) NOT NULL,
  `tbuniversidadestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbuniversidad`
--

INSERT INTO `tbuniversidad` (`tbuniversidadid`, `tbuniversidadnombre`, `tbuniversidadestado`) VALUES
(1, 'Universidad Nacional', 0),
(2, 'Universidad de Costa Rica', 1),
(3, 'Tecnológico de Costa Rica', 0),
(4, 'Universidad Técnica Nacional', 1),
(5, 'Probanding', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbusuario`
--

CREATE TABLE `tbusuario` (
  `tbusuarioid` int(11) NOT NULL,
  `tbusuarionombre` varchar(255) NOT NULL,
  `tbusuariocontrasena` varchar(63) NOT NULL,
  `tbusuarioestado` tinyint(1) NOT NULL,
  `tbtipousuarioid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbusuario`
--

INSERT INTO `tbusuario` (`tbusuarioid`, `tbusuarionombre`, `tbusuariocontrasena`, `tbusuarioestado`, `tbtipousuarioid`) VALUES
(1, 'jamyrg', '123', 1, 1),
(1, 'jeycobbg', '123', 1, 1),
(3, 'profe', '123', 1, 1),
(4, 'gerald', '123', 1, 1),
(5, 'kevin', '123', 1, 1),
(6, 'jamel', '123', 1, 1),
(7, 'pruebaUsuario', '123', 1, 2),
(7, 'pruebaUsuario', '123', 1, 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
