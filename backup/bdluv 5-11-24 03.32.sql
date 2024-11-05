-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-11-2024 a las 10:31:00
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
-- Estructura de tabla para la tabla `tbactividad`
--

CREATE TABLE `tbactividad` (
  `tbactividadid` int(11) NOT NULL,
  `tbusuarioid` int(11) DEFAULT NULL,
  `tbactividadtitulo` varchar(63) NOT NULL,
  `tbactividaddescripcion` varchar(255) NOT NULL,
  `tbactividadfechainicio` datetime NOT NULL,
  `tbactividadfechatermina` datetime NOT NULL,
  `tbactividadimagen` varchar(255) NOT NULL,
  `tbactividaddireccion` varchar(255) NOT NULL,
  `tbactividadlatitud` int(11) NOT NULL,
  `tbactividadlongitud` int(11) NOT NULL,
  `tbactividadestado` tinyint(1) NOT NULL,
  `tbactividadanonimo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbactividad`
--

INSERT INTO `tbactividad` (`tbactividadid`, `tbusuarioid`, `tbactividadtitulo`, `tbactividaddescripcion`, `tbactividadfechainicio`, `tbactividadfechatermina`, `tbactividadimagen`, `tbactividaddireccion`, `tbactividadlatitud`, `tbactividadlongitud`, `tbactividadestado`, `tbactividadanonimo`) VALUES
(1, 0, 'Fiesta de Halloween', 'Fiesta de Halloween', '2024-10-31 22:00:00', '2024-10-31 23:00:00', '', 'UNA', 0, 0, 0, 0),
(2, 0, 'Act Hallowen', 'Fiesta', '2024-10-31 19:00:00', '2024-10-31 23:00:00', '../resources/img/actividad/act-hallowen.webp', 'UNA', 0, 0, 1, 0),
(3, 0, 'Kevin', 'editado', '2024-11-01 15:56:00', '2024-11-01 17:56:00', '../resources/img/actividad/kevin.webp', 'Kevin', 0, 0, 1, 0),
(4, 12, 'Usuario', 'creado por', '2024-11-09 15:02:00', '2024-11-16 19:59:00', '../resources/img/actividad/usuario.webp', 'aca', 0, 0, 1, 1),
(1, 12, 'Fiesta Halloween', 'Fiesta del equipo de volleyball', '2024-10-31 21:25:00', '2024-10-31 23:33:00', '', 'UNA Campus Sarapiqui', 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbactividadasistencia`
--

CREATE TABLE `tbactividadasistencia` (
  `tbactividadasistenciaid` int(11) NOT NULL,
  `tbactividadid` int(11) NOT NULL,
  `tbusuarioid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(2, 2, 1),
(3, 7, 2),
(4, 7, 3),
(5, 8, 1),
(6, 9, 3),
(7, 10, 2),
(8, 11, 3),
(9, 11, 3),
(10, 12, 4),
(11, 13, 3),
(12, 1, 1);

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
  `tbafinidadusuariogenero` varchar(255) NOT NULL,
  `tbafinidadusuarioorientacionsexual` varchar(255) NOT NULL,
  `tbafinidadusuarioestado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbafinidadusuario`
--

INSERT INTO `tbafinidadusuario` (`tbafinidadusuarioid`, `tbusuarioid`, `tbafinidadusuarioimagenurl`, `tbafinidadusuarioregion`, `tbafinidadusuarioduracion`, `tbafinidadusuariozoomscale`, `tbafinidadusuariocriterio`, `tbafinidadusuarioafinidad`, `tbafinidadusuariogenero`, `tbafinidadusuarioorientacionsexual`, `tbafinidadusuarioestado`) VALUES
(1, 12, 'https://www.travelexcellence.com/wp-content/uploads/2020/09/CANOPY-1.jpg', '1,3;1,2;1,1;2,1;2,2;2,3;3,3;3,2;3,1', '5018,3605,3373,6657,4974,28666,12571,14577,19020,', '1,1,1,1,1,1,1,1,1,', 'Sin criterio,Sin criterio,Sin criterio,Sin criterio,Sin criterio,Sin criterio,Sin criterio,Sin criterio,Sin criterio', '17.51,12.58,11.77,23.22,17.35,100,43.85,50.85,66.35,', 'Femenino', 'Heterosexual', 1),
(0, 26, '', '', '', '', '', '', 'Femenino', 'Heterosexual', 1);

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
  `tbusuarioid` int(11) NOT NULL,
  `tbperfilusuariodeseadocriterio` varchar(1024) NOT NULL,
  `tbperfilusuariodeseadovalor` varchar(1024) NOT NULL,
  `tbperfilusuariodeseadoporcentaje` varchar(1024) NOT NULL,
  `tbperfilusuariodeseadoestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbperfilusuariodeseado`
--

INSERT INTO `tbperfilusuariodeseado` (`tbperfilusuariodeseadoid`, `tbusuarioid`, `tbperfilusuariodeseadocriterio`, `tbperfilusuariodeseadovalor`, `tbperfilusuariodeseadoporcentaje`, `tbperfilusuariodeseadoestado`) VALUES
(1, 1, 'Empleos,Gustos Musicales,Comida rápida', 'médico,jazz,tacos', '30', 1),
(2, 2, 'Gustos Musicales', 'samba', '50', 1),
(3, 3, 'Empleos,Gustos Musicales,Comida rápida', 'ingeniero,rock,pastas', '40', 1),
(4, 4, 'Gustos Musicales,Comida rápida', 'pop,hamburguesas', '60', 1),
(5, 5, 'Empleos,Gustos Musicales', 'arquitecto,salsa', '70', 1),
(6, 6, 'Gustos Musicales,Comida rápida', 'electrónica,shawarma', '50', 1),
(7, 7, 'Gustos Musicales', 'bachata', '80', 1),
(8, 8, 'Empleos,Gustos Musicales,Comida rápida', 'médico,reggae,pizzas', '50', 1),
(9, 9, 'Empleos,Gustos Musicales,Comida rápida', 'médico,reggae,pizzas', '50', 1),
(10, 10, 'Empleos,Gustos Musicales,Comida rápida', 'dentista,clásica,sushi', '40', 1),
(11, 11, 'Gustos Musicales,Empleos,Mascotas', 'samba,ingeniero,perros', '50', 1),
(12, 12, 'Empleos,Mascotas,Comida rápida,Empleos,Redes Sociales,Gustos Musicales', 'médico,gatos,hamburguesas,ingeniero,Instagram,pop', '33.33,26.67,20,13.33,6.67', 1),
(13, 13, 'Gustos Musicales,Comida rápida', 'rock,burgers', '50', 1),
(14, 14, 'Gustos Musicales,Comida rápida', 'pop,hamburguesas', '60', 1),
(15, 15, 'Empleos,Gustos Musicales', 'arquitecto,salsa', '70', 1),
(16, 16, 'Gustos Musicales,Comida rápida', 'electrónica,shawarma', '50', 1),
(17, 17, 'Gustos Musicales', 'bachata', '80', 1),
(18, 18, 'Empleos,Gustos Musicales,Comida rápida', 'chef,clásica,sushi', '40', 1),
(19, 19, 'Gustos Musicales,Empleos,Mascotas', 'jazz,ingeniero,gatos', '50', 1),
(20, 20, 'Gustos Musicales,Comida rápida,Mascotas', 'rock,pizza,perros', '50', 1),
(21, 21, 'Empleos,Gustos Musicales,Comida rápida', 'dentista,clásica,tacos', '45', 1),
(22, 22, 'Gustos Musicales,Comida rápida', 'samba,hamburguesas', '35', 1),
(23, 26, 'Mascotas', 'Gato', '100', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbperfilusuariopersonal`
--

CREATE TABLE `tbperfilusuariopersonal` (
  `tbperfilusuariopersonalid` int(11) NOT NULL,
  `tbusuarioid` int(11) NOT NULL,
  `tbperfilusuariopersonalcriterio` varchar(1024) NOT NULL,
  `tbperfilusuariopersonalvalor` varchar(1024) NOT NULL,
  `tbareaconocimiento` varchar(50) DEFAULT NULL,
  `tbgenero` varchar(50) NOT NULL,
  `tborientacionsexual` varchar(50) NOT NULL,
  `tbuniversidad` varchar(50) NOT NULL,
  `tbuniversidadcampus` varchar(100) NOT NULL,
  `tbuniversidadcampuscolectivo` varchar(100) DEFAULT NULL,
  `tbperfilusuariopersonalestado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbperfilusuariopersonal`
--

INSERT INTO `tbperfilusuariopersonal` (`tbperfilusuariopersonalid`, `tbusuarioid`, `tbperfilusuariopersonalcriterio`, `tbperfilusuariopersonalvalor`, `tbareaconocimiento`, `tbgenero`, `tborientacionsexual`, `tbuniversidad`, `tbuniversidadcampus`, `tbuniversidadcampuscolectivo`, `tbperfilusuariopersonalestado`) VALUES
(1, 1, 'Gustos Musicales', 'samba', 'Ingeniería', 'Masculino', 'Heterosexual', 'Universidad 1', 'Campus 1', 'Colectivo 1', 1),
(2, 2, 'Empleos,Gustos Musicales,Comida rápida', 'médico,samba,tacos', 'Artes', 'No binario', 'Heterosexual', 'Universidad 3', 'Campus 3', 'Colectivo 2', 1),
(3, 3, 'Empleos,Gustos Musicales,Comida rápida', 'ingeniero,rock,pastas', 'Ingeniería', 'Masculino', 'Heterosexual', 'Universidad 2', 'Campus Norte', NULL, 1),
(4, 4, 'Empleos,Gustos Musicales', 'médico,jazz', 'Artes', 'No binario', 'Heterosexual', 'Universidad 3', 'Campus 3', 'Colectivo 1', 1),
(5, 5, 'Empleos,Gustos Musicales', 'arquitecto,salsa', 'Arquitectura', 'No binario', 'Heterosexual', 'Universidad 3', 'Campus 3', 'Colectivo 2', 1),
(6, 6, 'Gustos Musicales,Comida rápida', 'electrónica,shawarma', 'Tecnología', 'Masculino', 'Heterosexual', 'Universidad Politécnica', 'Campus Central', 'Club de Robótica', 1),
(7, 7, 'Empleos,Gustos Musicales', 'abogado,bachata', 'Derecho', 'Masculino', 'Heterosexual', 'Universidad de Derecho', 'Campus Norte', NULL, 1),
(8, 8, 'Empleos,Gustos Musicales,Comida rápida', 'médico,reggae,pizzas', 'Artes', 'Femenino', 'Heterosexual', 'Universidad 3', 'Campus 3', 'Colectivo 3', 1),
(9, 9, 'Empleos,Gustos Musicales,Comida rápida', 'médico,reggae,pizzas', 'Artes', 'Femenino', 'Heterosexual', 'Universidad 3', 'Campus 3', 'Colectivo 3', 1),
(10, 10, 'Empleos,Gustos Musicales,Comida rápida', 'dentista,clásica,sushi', 'Medicina', 'Masculino', 'Heterosexual', 'Universidad Nacional', 'Campus Ciencias Médicas', 'Club de Debate', 1),
(11, 11, 'Empleos,Gustos Musicales', 'ingeniero,jazz', 'Ingeniería', 'Masculino', 'Heterosexual', 'Universidad Técnica', 'Campus Sur', 'Club de Fotografía', 1),
(12, 12, 'Empleos,Mascotas,Comida rápida,Empleos,Redes Sociales', 'médico,gatos,hamburguesas,ingeniero,Instagram', 'Matemáticas', 'Masculino', 'Heterosexual', 'Universidad Nacional de Costa Rica', 'Campus Sarapiquí', 'Volleyball', 1),
(13, 13, 'Gustos Musicales,Comida rápida', 'rock,burgers', 'Ciencias Sociales', 'Masculino', 'Heterosexual', 'Universidad del Estado', 'Campus Oeste', NULL, 1),
(14, 14, 'Gustos Musicales,Comida rápida', 'pop,hamburguesas', 'Derecho', 'Femenino', 'Heterosexual', 'Universidad Nacional', 'Campus Central', NULL, 1),
(15, 15, 'Empleos,Gustos Musicales', 'arquitecto,salsa', 'Arquitectura', 'Masculino', 'Heterosexual', 'Universidad del Sur', 'Campus Arquitectura', 'Club de Danza', 1),
(16, 16, 'Gustos Musicales,Comida rápida', 'electrónica,shawarma', 'Ingeniería', 'Femenino', 'Heterosexual', 'Universidad Politécnica', 'Campus Este', 'Club de Electrónica', 1),
(17, 17, 'Empleos,Gustos Musicales', 'chef,bachata', 'Ciencias Culinarias', 'Masculino', 'Heterosexual', 'Instituto Culinario', 'Campus Gastronómico', NULL, 1),
(18, 18, 'Empleos,Gustos Musicales', 'chef,clásica,sushi', 'Gastronomía', 'Femenino', 'Heterosexual', 'Universidad Gastronómica', 'Campus Principal', 'Club de Cocina', 1),
(19, 19, 'Empleos,Gustos Musicales,Mascotas', 'ingeniero,jazz,gatos', 'Ingeniería', 'Masculino', 'Heterosexual', 'Universidad Técnica', 'Campus Sur', 'Club de Animales', 1),
(20, 20, 'Gustos Musicales,Comida rápida,Mascotas', 'rock,pizza,perros', 'Ingeniería', 'Femenino', 'Heterosexual', 'Universidad del Este', 'Campus Central', 'Club de Atletismo', 1),
(21, 21, 'Empleos,Gustos Musicales,Comida rápida', 'dentista,clásica,tacos', 'Medicina', 'Masculino', 'Heterosexual', 'Universidad Nacional', 'Campus Medicina', 'Club de Estudios', 1),
(22, 22, 'Gustos Musicales,Comida rápida', 'samba,hamburguesas', 'Artes', 'Femenino', 'Heterosexual', 'Universidad de las Artes', 'Campus Principal', 'Club de Teatro', 1),
(23, 26, 'comida', 'pizza', 'Matemáticas', 'Masculino', 'Heterosexual', 'Universidad Nacional de Costa Rica', 'Campus Sarapiquí', 'Volleyball', 1);

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
(9, '131313131', 'fernanda', 'Rojas', 1),
(10, '141414141', 'Andres', 'Gutierrez', 1),
(11, '000000001', 'admin', 'admin', 1),
(12, '000000002', 'client', 'client', 1),
(13, '000000003', 'user', 'user', 1),
(14, '123444', 'cavil', 'he', 1),
(15, '4353245', 'Vasquez', 'Fibronas', 1),
(16, '3453452', 'Lopez', 'Cascante', 1),
(17, '12313212', 'Blanca', 'Nieve', 1),
(18, '3452345', 'Hernandez', 'Bena', 1),
(19, '214234324', 'Jejon', 'Saron', 1),
(20, '43214213', 'Mende', 'Badi', 1),
(21, '234124', 'Neron', 'Baxa', 1),
(22, '324543532', 'Salazar', 'Mendez', 1),
(23, '23453425325', 'sdfasf', 'sadfasf', 1),
(24, '53145', 'Sandi', 'Brenes', 1),
(25, '24235', 'Obando', 'Ganboa', 1),
(26, '73483484', 'eloisa', 'osuna', 1);

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
(10, 10, 2, 'andres', '123', 1, '', 'No disponible'),
(11, 11, 1, 'admin', 'admin', 1, '', ''),
(12, 12, 2, 'client', 'client', 1, '../resources/img/profile/client.webp', 'No disponible'),
(13, 13, 2, 'user', 'user', 1, '', 'Disponible'),
(17, 17, 2, 'Zoe', '123', 1, '../resources/img/profile/zoe.webp', 'No disponible'),
(19, 19, 2, 'Enry', '123', 1, '../resources/img/profile/enry.webp', NULL),
(20, 20, 2, 'Lenka', '123', 1, '../resources/img/profile/lenka.webp', NULL),
(21, 21, 2, 'julian', '123', 1, '../resources/img/profile/julian.webp', NULL),
(22, 22, 2, 'Helen', '123', 1, '../resources/img/profile/helen.webp', NULL),
(23, 23, 2, 'Om', '123', 1, '../resources/img/profile/om.webp', NULL),
(24, 24, 2, 'Felipe', '123', 1, '../resources/img/profile/felipe.webp', NULL),
(25, 25, 2, 'Keilin', '123', 1, '../resources/img/profile/keilin.webp', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbusuariomensaje`
--

CREATE TABLE `tbusuariomensaje` (
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
(14, 12, 13, 'bien bien', '2024-11-01 05:39:22'),
(15, 12, 1, 'a', '2024-11-01 21:50:51'),
(16, 10, 12, 'Hola', '2024-11-05 09:26:07'),
(17, 10, 12, 'hey', '2024-11-05 09:26:36');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbperfilusuariodeseado`
--
ALTER TABLE `tbperfilusuariodeseado`
  ADD UNIQUE KEY `tbusuarioid` (`tbusuarioid`);

--
-- Indices de la tabla `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD PRIMARY KEY (`tbusuarioid`);

--
-- Indices de la tabla `tbusuariomensaje`
--
ALTER TABLE `tbusuariomensaje`
  ADD PRIMARY KEY (`tbusuariomensajeid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbusuario`
--
ALTER TABLE `tbusuario`
  MODIFY `tbusuarioid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `tbusuariomensaje`
--
ALTER TABLE `tbusuariomensaje`
  MODIFY `tbusuariomensajeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
