-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-10-2024 a las 05:17:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

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
-- Estructura de tabla para la tabla `tbactividad`
--

CREATE TABLE `tbactividad` (
  `tbactividadid` int(11) NOT NULL,
  `tbactividadtitulo` varchar(63) NOT NULL,
  `tbactividaddescripcion` varchar(255) NOT NULL,
  `tbactividadfechainicio` datetime NOT NULL,
  `tbactividadfechatermina` datetime NOT NULL,
  `tbactividaddireccion` varchar(255) NOT NULL,
  `tbactividadlatitud` int(11) NOT NULL,
  `tbactividadlongitud` int(11) NOT NULL,
  `tbactividadestado` tinyint(1) NOT NULL,
  `tbactividadanonimo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbactividad`
--

INSERT INTO `tbactividad` (`tbactividadid`, `tbactividadtitulo`, `tbactividaddescripcion`, `tbactividadfechainicio`, `tbactividadfechatermina`, `tbactividaddireccion`, `tbactividadlatitud`, `tbactividadlongitud`, `tbactividadestado`, `tbactividadanonimo`) VALUES
(1, 'Torneo Interuniversitario Volleyball', 'Competencia de volleyball contra otros equipos universitarios', '2024-10-15 10:00:00', '2024-10-15 18:00:00', 'Coliseo Deportivo, San José', 0, 0, 1, 0),
(2, 'Partido Amistoso Fútbol', 'Partido amistoso contra otro equipo universitario', '2024-10-20 14:00:00', '2024-10-20 16:00:00', 'Estadio Universitario', 0, 0, 1, 0),
(3, 'Torneo Regional Basketball', 'Torneo regional con equipos universitarios de la zona', '2024-10-18 09:00:00', '2024-10-18 19:00:00', 'Coliseo de Basketball, Heredia', 0, 0, 1, 0),
(4, 'Torneo Ping Pong', 'Competencia amistosa de ping pong entre miembros del colectivo', '2024-10-10 14:00:00', '2024-10-10 15:00:00', 'Sala de Deportes, Campus Norte', 0, 0, 1, 1),
(5, 'Sesión de Práctica Ping Pong', 'Práctica general para todos los miembros del club', '2024-10-24 16:00:00', '2024-10-24 17:30:00', 'Sala de Deportes, Campus Norte', 0, 0, 1, 0),
(6, 'Presentación de Danza', 'Presentación artística durante el evento cultural del campus', '2024-10-30 19:00:00', '2024-10-30 22:00:00', 'Auditorio Principal, Campus Central', 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbactividaduniversidadcampuscolectivo`
--

CREATE TABLE `tbactividaduniversidadcampuscolectivo` (
  `tbactividaduniversidadcampuscolectivoid` int(11) NOT NULL,
  `tbactividadid` int(11) NOT NULL,
  `tbcampuscolectivoid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbactividaduniversidadcampuscolectivo`
--

INSERT INTO `tbactividaduniversidadcampuscolectivo` (`tbactividaduniversidadcampuscolectivoid`, `tbactividadid`, `tbcampuscolectivoid`) VALUES
(1, 1, 5),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbafinidadusuario`
--

CREATE TABLE `tbafinidadusuario` (
  `tbafinidadusuarioid` int(11) NOT NULL,
  `tbusuarioid` int(11) NOT NULL,
  `tbafinidadusuarioimagenurl` varchar(255) NOT NULL,
  `tbafinidadusuarioregion` varchar(255) NOT NULL,
  `tbafinidadusuarioduracion` varchar(255) NOT NULL,
  `tbafinidadusuariozoomscale` varchar(255) NOT NULL,
  `tbafinidadusuariocriterio` varchar(255) NOT NULL,
  `tbafinidadusuarioafinidad` varchar(255) NOT NULL,
  `tbafinidadusuarioestado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'Física', 'Ciencia que las propiedades y el comportamiento de la materia y la energía.', 1),
(3, 'Biología', 'Ciencia que estudia a los seres vivos y sus procesos vitales.', 1),
(4, 'Ingeniería Informática', 'Disciplina que se encarga del diseño y desarrollo de sistemas y aplicaciones informáticas.', 1),
(5, 'Psicología', 'Ciencia que estudia el comportamiento y los procesos mentales de los individuos.', 1);

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
(1, 'Masculino', 'Se refiere a personas que se identifican con el género masculino, lo que suele estar asociado a hombres cisgénero (aquellos cuya identidad de género coincide con el sexo asignado al nacer).', 1),
(2, 'Femenino', 'Se refiere a personas que se identifican con el género femenino, lo que usualmente incluye a mujeres cisgénero (aquellas cuya identidad de género coincide con el sexo asignado al nacer).', 1),
(3, 'No binario', 'Este término abarca a personas cuya identidad de género no encaja estrictamente en las categorías de masculino o femenino. Puede incluir identidades como género fluido, agénero, bigénero, entre otros.', 1),
(4, 'Género fluido', 'Se refiere a personas cuya identidad de género puede cambiar o variar con el tiempo entre diferentes géneros.', 1),
(5, 'Agénero', 'Describe a personas que no se identifican con ningún género o que tienen una ausencia de identidad de género.', 1),
(6, 'Bigénero', 'Se refiere a personas que experimentan dos géneros, ya sea de manera simultánea o alternante. Estos géneros pueden ser masculinos, femeninos, o una combinación con otras identidades de género.', 1),
(7, 'Genero queer', 'Un término general que algunas personas usan para describir una identidad de género que no se ajusta a las normas convencionales de género. Es un término flexible y puede tener diferentes significados para diferentes personas.', 1),
(8, 'Demigénero', 'Incluye identidades como demi-hombre o demi-mujer, donde la persona se identifica parcialmente con un género (masculino o femenino) pero no completamente.', 1),
(9, 'Intergénero', 'Se refiere a personas que tienen una identidad de género que está entre las categorías de masculino y femenino, o es una combinación de ambos.', 1),
(10, 'Dos espíritus', 'Un término utilizado por algunas culturas indígenas en Norteamérica para describir a una persona que encarna tanto el espíritu masculino como el femenino. Es un término culturalmente específico y tiene significados únicos en diferentes comunidades indígen', 1);

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
(1, 1, 0, '../resources/img/universidad/', 'universidad-nacional-de-costa-rica.png', 1),
(2, 5, 0, '../resources/img/campus/', '.jpeg', 0),
(3, 5, 0, '../resources/img/campus/', 'campus-sarapiquí.jpeg', 1);

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
-- Estructura de tabla para la tabla `tbperfilusuariodeseado`
--

CREATE TABLE `tbperfilusuariodeseado` (
  `tbperfilusuariodeseadoid` int(11) NOT NULL,
  `tbperfilusuariodeseadocriterio` varchar(1024) NOT NULL,
  `tbperfilusuariodeseadovalor` varchar(1024) NOT NULL,
  `tbperfilusuariodeseadoporcentaje` varchar(1024) NOT NULL,
  `tbusuarioid` int(11) NOT NULL,
  `tbperfilusuariodeseadoestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbperfilusuariodeseado`
--

INSERT INTO `tbperfilusuariodeseado` (`tbperfilusuariodeseadoid`, `tbperfilusuariodeseadocriterio`, `tbperfilusuariodeseadovalor`, `tbperfilusuariodeseadoporcentaje`, `tbusuarioid`, `tbperfilusuariodeseadoestado`) VALUES
(1, 'Empleos,Gustos Musicales,Comida rápida', 'médico,jazz,tacos', '30', 1, 1),
(2, 'Gustos Musicales', 'samba', '50', 2, 1),
(11, 'Gustos Musicales,Empleos,Mascotas', 'samba,ingeniero,perros', '50', 11, 1),
(12, 'Empleos,Gustos Musicales,Empleos,Estilo de Vida,Mascotas', 'médico,jazz,ingeniero,saludable,gatos', '50', 12, 1);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tbperfilusuariopersonal`
--

CREATE TABLE `tbperfilusuariopersonal` (
  `tbperfilusuariopersonalid` int(11) NOT NULL,
  `tbperfilusuariopersonalcriterio` varchar(1024) NOT NULL,
  `tbperfilusuariopersonalvalor` varchar(1024) NOT NULL,
  `tbareaconocimiento` varchar(50) DEFAULT NULL,
  `tbgenero` varchar(50) NOT NULL,
  `tborientacionsexual` varchar(50) NOT NULL,
  `tbuniversidad` varchar(50) NOT NULL,
  `tbuniversidadcampus` varchar(100) NOT NULL,
  `tbuniversidadcampuscolectivo` varchar(100) DEFAULT NULL,
  `tbusuarioid` int(11) NOT NULL,
  `tbperfilusuariopersonalestado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbperfilusuariopersonal`
--

INSERT INTO `tbperfilusuariopersonal` (`tbperfilusuariopersonalid`, `tbperfilusuariopersonalcriterio`, `tbperfilusuariopersonalvalor`, `tbareaconocimiento`, `tbgenero`, `tborientacionsexual`, `tbuniversidad`, `tbuniversidadcampus`, `tbuniversidadcampuscolectivo`, `tbusuarioid`, `tbperfilusuariopersonalestado`) VALUES
(1, 'Gustos Musicales', 'samba', 'Ingeniería', 'Masculino', 'Heterosexual', 'Universidad 1', 'Campus 1', 'Colectivo 1', 1, 1),
(2, 'Empleos,Gustos Musicales,Comida rápida', 'médico,samba,tacos', 'Artes', 'No binario', 'Heterosexual', 'Universidad 3', 'Campus 3', 'Colectivo 2', 2, 1),
(3, 'Empleos,Gustos Musicales,Comida rápida', 'médico,reggae,pizzas', 'Artes', 'No binario', 'Heterosexual', 'Universidad 3', 'Campus 3', 'Colectivo 3', 3, 1),
(4, 'Empleos,Gustos Musicales', 'médico,jazz', 'Artes', 'No binario', 'Heterosexual', 'Universidad 3', 'Campus 3', 'Colectivo 1', 4, 1),
(5, 'Empleos,Gustos Musicales', 'médico,samba', 'Artes', 'No binario', 'Heterosexual', 'Universidad 3', 'Campus 3', 'Colectivo 2', 5, 1),
(12, 'Empleos,Gustos Musicales,Comida rápida,Estilo de Vida,Mascotas', 'médico,samba,papas fritas,saludable,gatos', 'Artes', 'No binario', 'Heterosexual', 'Universidad 3', 'Campus 3', 'Colectivo 3', 12, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbpersona`
--

CREATE TABLE `tbpersona` (
  `tbpersonaid` int(11) NOT NULL,
  `tbpersonacedula` varchar(24) NOT NULL,
  `tbpersonaprimernombre` varchar(255) NOT NULL,
  `tbpersonaprimerapellido` varchar(255) NOT NULL,
  `tbpersonaestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbpersona`
--

INSERT INTO `tbpersona` (`tbpersonaid`, `tbpersonacedula`, `tbpersonaprimernombre`, `tbpersonaprimerapellido`, `tbpersonaestado`) VALUES
(1, '123456789', 'Jamyr', 'Gonzalez', 1),
(2, '987654321', 'Jeycob', 'Brenes', 1),
(3, '111111111', 'Christian', 'Mora', 1),
(4, '222222222', 'Gerald', 'Vargas', 1),
(5, '703070997', 'Kevin', 'Venegas', 1),
(6, '444444444', 'Jamel', 'Hernandez', 1),
(7, '777777777', 'Josue', 'Perez', 1),
(8, '121212121', 'Lucia', 'Mendez', 1),
(9, '131313131', 'Fernando', 'Rojas', 1),
(10, '141414141', 'Andres', 'Gutierrez', 1),
(11, '000000001', 'admin', 'admin', 1),
(12, '000000002', 'client', 'client', 1),
(13, '000000003', 'user', 'user', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbsolicitudgenero`
--

CREATE TABLE `tbsolicitudgenero` (
  `tbsolicitudgeneroid` int(11) NOT NULL,
  `tbsolicitudgeneronombre` varchar(63) NOT NULL,
  `tbsolicitudgeneroestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbsolicitudorientacionsexual`
--

CREATE TABLE `tbsolicitudorientacionsexual` (
  `tbsolicitudorientacionsexualid` int(11) NOT NULL,
  `tbsolicitudorientacionsexualnombre` varchar(63) NOT NULL,
  `tbsolicitudorientacionsexualestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Universidad Fidélitas', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbsolicituduniversidadcampus`
--

CREATE TABLE `tbsolicituduniversidadcampus` (
  `tbsolicituduniversidadcampusid` int(11) NOT NULL,
  `tbsolicituduniversidadcampusnombre` varchar(63) NOT NULL,
  `tbsolicituduniversidadid` int(11) NOT NULL,
  `tbsolicituduniversidadcampusestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Universidad Nacional de Costa Rica', 1),
(2, 'Universidad de Costa Rica', 1),
(3, 'Tecnológico de Costa Rica', 1),
(4, 'Universidad Técnica Nacional de Costa Rica', 1),
(5, 'Universidad Nacional de Educación A Distancia', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuniversidadcampus`
--

CREATE TABLE `tbuniversidadcampus` (
  `tbuniversidadcampusid` int(11) NOT NULL,
  `tbuniversidadid` int(11) NOT NULL,
  `tbuniversidadcampusnombre` varchar(191) NOT NULL,
  `tbuniversidadcampusdireccion` varchar(191) NOT NULL,
  `tbuniversidadcampusestado` tinyint(1) NOT NULL,
  `tbuniversidadcampuslatitud` varchar(30) DEFAULT NULL,
  `tbuniversidadcampuslongitud` varchar(30) DEFAULT NULL,
  `tbuniversidadcampusregionid` int(11) DEFAULT NULL,
  `tbuniversidadcampusespecializacionid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampus`
--

INSERT INTO `tbuniversidadcampus` (`tbuniversidadcampusid`, `tbuniversidadid`, `tbuniversidadcampusnombre`, `tbuniversidadcampusdireccion`, `tbuniversidadcampusestado`, `tbuniversidadcampuslatitud`, `tbuniversidadcampuslongitud`, `tbuniversidadcampusregionid`, `tbuniversidadcampusespecializacionid`) VALUES
(1, 1, 'Campus Sarapiquí', 'Sarapiquí, La Victoria', 1, '', '', 1, 1),
(2, 1, 'Campus Nicoya', 'Carretera a Sámara, 150, Provincia de Guanacaste, Nicoya', 1, '', '', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuniversidadcampuscolectivo`
--

CREATE TABLE `tbuniversidadcampuscolectivo` (
  `tbuniversidadcampuscolectivoid` int(30) NOT NULL,
  `tbuniversidadcampuscolectivonombre` varchar(255) NOT NULL,
  `tbuniversidadcampuscolectivodescripcion` varchar(255) NOT NULL,
  `tbuniversidadcampuscolectivoestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampuscolectivo`
--

INSERT INTO `tbuniversidadcampuscolectivo` (`tbuniversidadcampuscolectivoid`, `tbuniversidadcampuscolectivonombre`, `tbuniversidadcampuscolectivodescripcion`, `tbuniversidadcampuscolectivoestado`) VALUES
(1, 'Volleyball', 'Equipo representativo de volleyball', 1),
(2, 'Fútbol', 'Equipo representativo de fútbol', 1),
(3, 'Basketball', 'Equipo representativo de basketball', 1),
(4, 'Ping pong ', 'Comunidad estudiantil grande interesada en el ping pong', 1),
(5, 'Danza', 'Equipo representativo de baile', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuniversidadcampusespecializacion`
--

CREATE TABLE `tbuniversidadcampusespecializacion` (
  `tbuniversidadcampusespecializacionid` int(11) NOT NULL,
  `tbuniversidadcampusespecializacionnombre` varchar(255) NOT NULL,
  `tbuniversidadcampusespecializaciondescripcion` varchar(255) NOT NULL,
  `tbuniversidadcampusespecializacionestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampusespecializacion`
--

INSERT INTO `tbuniversidadcampusespecializacion` (`tbuniversidadcampusespecializacionid`, `tbuniversidadcampusespecializacionnombre`, `tbuniversidadcampusespecializaciondescripcion`, `tbuniversidadcampusespecializacionestado`) VALUES
(1, 'Ingeniería de Software', 'Enfocada en el desarrollo, diseño, implementación y mantenimiento de sistemas de software. Incluye programación, pruebas, y gestión de proyectos.', 1),
(2, 'Medicina Interna', 'Rama de la medicina que se especializa en el diagnóstico y tratamiento de enfermedades en adultos, con un enfoque en problemas complejos de múltiples órganos.', 1),
(3, 'Derecho Penal', 'Especialización en el estudio y aplicación de las leyes penales, abarcando delitos, procedimientos penales, y el sistema de justicia criminal.', 1),
(4, 'Marketing Digital', 'Centrada en la promoción de productos o servicios a través de canales digitales como redes sociales, SEO, SEM, y publicidad en línea.', 1),
(5, 'Ingeniería Ambiental', 'Se enfoca en desarrollar soluciones técnicas para proteger el medio ambiente, como el tratamiento de aguas, gestión de residuos y control de la contaminación.', 1),
(6, 'Neurología', 'Rama de la medicina que trata trastornos del sistema nervioso, incluyendo enfermedades del cerebro, la médula espinal y los nervios periféricos.', 1),
(7, 'Finanzas Corporativas', 'Área de finanzas que se enfoca en la gestión financiera dentro de las empresas, incluyendo la planificación, gestión de riesgos, y estrategias de inversión.', 1),
(8, 'Bioinformática', 'Intersección entre biología y tecnología de la información, utilizada para analizar datos biológicos como secuencias genéticas mediante herramientas computacionales.', 1),
(9, 'Ingeniería Civil', 'Especialización en el diseño, construcción y mantenimiento de infraestructuras como carreteras, puentes, edificios y sistemas de agua y alcantarillado.', 1),
(10, 'Psiquiatría', 'Rama de la medicina que se especializa en el diagnóstico, tratamiento y prevención de trastornos mentales, emocionales y del comportamiento.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuniversidadcampusregion`
--

CREATE TABLE `tbuniversidadcampusregion` (
  `tbuniversidadcampusregionid` int(11) NOT NULL,
  `tbuniversidadcampusregionnombre` varchar(255) NOT NULL,
  `tbuniversidadcampusregiondescripcion` varchar(255) NOT NULL,
  `tbuniversidadcampusregionestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampusregion`
--

INSERT INTO `tbuniversidadcampusregion` (`tbuniversidadcampusregionid`, `tbuniversidadcampusregionnombre`, `tbuniversidadcampusregiondescripcion`, `tbuniversidadcampusregionestado`) VALUES
(1, 'Norte', 'La conforman los cantones de Upala, Guatuso, Los Chiles, Ciudad Quesada, San Ramón y Sarapiquí, que son atendidos por cuatro oficinas subregionales, para una cobertura de más de 722 mil hectáreas.', 1),
(2, 'Sur', 'La Región Brunca está formado por los cantones de Osa, Golfito, Corredores, Coto Brus, Buenos Aires y Puerto Jiménez en la provincia de Puntarenas, y Pérez Zeledón en la provincia de San José.', 1),
(3, 'Este', ' La región este de Costa Rica la conforman los cantones de Cartago, Paraíso, Alvarado, Jiménez, Turrialba, El Guarco, Limón, Pococí, Siquirres, Talamanca y Matina. Estos cantones cubren una diversidad de paisajes y comunidades, desde montañas hasta costas', 1),
(4, 'Oeste', 'La región oeste de Costa Rica la conforman los cantones de Santa Cruz, Nicoya, Hojancha, Nandayure, Cañas, Tilarán, Abangares, Puntarenas, Esparza, Montes de Oro, Osa, Golfito, Corredores, y Buenos Aires. Estos cantones abarcan desde las playas del Pacífi', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuniversidadcampusuniversidadcolectivo`
--

CREATE TABLE `tbuniversidadcampusuniversidadcolectivo` (
  `tbuniversidadcampusuniversidadcolectivoid` int(11) NOT NULL,
  `tbuniversidadcampusid` int(11) NOT NULL,
  `tbuniversidadcolectivoid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampusuniversidadcolectivo`
--

INSERT INTO `tbuniversidadcampusuniversidadcolectivo` (`tbuniversidadcampusuniversidadcolectivoid`, `tbuniversidadcampusid`, `tbuniversidadcolectivoid`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 1),
(6, 2, 2),
(7, 2, 3),
(8, 2, 4),
(9, 3, 2),
(10, 3, 3),
(11, 3, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbusuario`
--

CREATE TABLE `tbusuario` (
  `tbusuarioid` int(11) NOT NULL,
  `tbpersonaid` int(11) NOT NULL,
  `tbusuarionombre` varchar(255) NOT NULL,
  `tbusuariocontrasena` varchar(63) NOT NULL,
  `tbusuarioestado` tinyint(1) NOT NULL,
  `tbtipousuarioid` int(11) NOT NULL,
  `tbusuarioimagen` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbusuario`
--

INSERT INTO `tbusuario` (`tbusuarioid`, `tbpersonaid`, `tbusuarionombre`, `tbusuariocontrasena`, `tbusuarioestado`, `tbtipousuarioid`, `tbusuarioimagen`) VALUES
(1, 1, 'jamyrg', '123', 1, 1, ''),
(2, 2, 'jeycobbg', '123', 1, 1, ''),
(3, 3, 'profe', '123', 1, 1, ''),
(4, 4, 'gerald', '123', 1, 1, ''),
(5, 5, 'kevin', '123', 1, 1, ''),
(6, 6, 'jamel', '123', 1, 1, ''),
(7, 7, 'josue', '123', 1, 2, ''),
(8, 8, 'lucia', '123', 1, 2, ''),
(9, 9, 'fernanda', '123', 1, 2, ''),
(10, 10, 'andres', '123', 1, 2, ''),
(11, 11, 'admin', 'admin', 1, 1, ''),
(12, 12, 'client', 'client', 1, 2, '../resources/img/profile/client.webp'),
(13, 13, 'user', 'user', 1, 2, '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
