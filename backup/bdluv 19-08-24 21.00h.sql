-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-08-2024 a las 06:23:18
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
-- Estructura de tabla para la tabla `tbcampuscolectivo`
--

CREATE TABLE `tbcampuscolectivo` (
  `tbcampuscolectivoid` int(11) NOT NULL,
  `tbcampuscolectivonombre` varchar(633) NOT NULL,
  `tbcampuscolectivodescripcion` varchar(255) NOT NULL,
  `tbcampuscolectivoestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbcampuscolectivo`
--

INSERT INTO `tbcampuscolectivo` (`tbcampuscolectivoid`, `tbcampuscolectivonombre`, `tbcampuscolectivodescripcion`, `tbcampuscolectivoestado`) VALUES
(1, 'Volleyball', 'Equipo Representativo de Volleyball', 1),
(2, 'Fútbol', 'Equipo Representativo de Fútbol', 1),
(3, 'Danza', 'Equipo Representativo de Baile', 1),
(4, 'Ping pong', 'Comunidad estudiantil grande interesada en el ping pong', 1);

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
(1, 'Agricultura y Ciencias Agropecuarias', 'Especialización en técnicas agrícolas avanzadas, manejo de cultivos, ganadería, y sostenibilidad en la agricultura.', 1),
(2, 'Ciencias Sociales', 'Enfoque en el estudio de la sociedad y el comportamiento humano, abarcando psicología, sociología, y antropología.', 1),
(3, 'Ciencias Políticas', 'Especialización en el análisis de sistemas políticos, relaciones internacionales, teoría política, y políticas públicas.', 1),
(4, 'Economía', 'Estudio de principios económicos, análisis de mercados, teoría económica, y políticas económicas para el desarrollo sostenible.', 1),
(5, 'Ingeniería Ambiental', 'Foco en la protección del medio ambiente, manejo de recursos naturales, y desarrollo de tecnologías para la sostenibilidad.', 1),
(6, 'Medicina y Ciencias de la Salud', 'Especialización en prácticas médicas, investigación en salud, manejo de enfermedades y promoción de la salud pública.', 1),
(7, 'Arquitectura y Urbanismo', 'Estudio del diseño de edificios y espacios urbanos, planificación territorial, y desarrollo de proyectos arquitectónicos sostenibles.', 1),
(8, 'Derecho', 'Enfoque en el estudio de leyes, ética legal, y procedimientos judiciales, incluyendo áreas como derecho civil, penal y comercial.', 1),
(9, 'Historia y Arqueología', 'Estudio de eventos históricos, análisis de civilizaciones antiguas, y técnicas arqueológicas para entender el pasado humano.', 1),
(10, 'Literatura y Lenguas', 'Exploración de diversas literaturas y lenguas, análisis literario, y estudios de traducción y lingüística aplicada.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbcampusregion`
--

CREATE TABLE `tbcampusregion` (
  `tbcampusregionid` int(11) NOT NULL,
  `tbcampusregionnombre` varchar(300) NOT NULL,
  `tbcampusregiondescripcion` varchar(300) NOT NULL,
  `tbcampusregionestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbcampusregion`
--

INSERT INTO `tbcampusregion` (`tbcampusregionid`, `tbcampusregionnombre`, `tbcampusregiondescripcion`, `tbcampusregionestado`) VALUES
(1, 'Caribe', 'El Caribe Sur de Costa Rica es uno de los lugares preferidos de los visitantes debido a su encanto cultural, playas de arena blanca, aguas turquesas, exuberante vegetación, abundante vida silvestre y su proximidad al Parque Nacional Cahuita y a la Reserva Nacional de Vida Silvestre Gandoca Manzanill', 1),
(2, 'San José y Valle Central', 'Un clima agradable y primaveral. Colinas salpicadas de cultivos de colores. Todas las comodidades de la vida moderna en la ciudad no es de extrañar que la mayoría de los costarricenses vivan en la capital de San José, en el Valle Central.', 1),
(3, 'Península de Osa', 'Este es el lugar más salvaje de Costa Rica, una de las zonas más remotas y místicas del país que ha permanecido intacta. Vuelan las guacamayas escarlatas. Los monos se balancean. El jaguar y el tapir dejan huellas en el suelo del bosque.', 1),
(4, 'Pacífico Central', 'De fácil acceso desde San José. Kilómetros de amplias playas. Vibrantes selvas tropicales llenas de fauna. Por eso, el Pacífico Central es una de las regiones más visitadas de Costa Rica.', 1),
(5, 'Guanacaste', 'Con un clima asoleado y seco durante casi todo el año y 400 millas de costa con una variedad de playas vírgenes de arena negra, coralina y dorada, Guanacaste es una de las regiones favoritas de los visitantes internacionales.', 1),
(6, 'Norte', 'Abarca esencialmente las llanuras de Guatuso, San Carlos y Sarapiquí, fronterizas con Nicaragua, así como las estribaciones de la vertiente oriental de la Cordillera Volcánica de Guanacaste y la norte de la Cordillera Vólcanica Central.', 1),
(7, 'Península de Nicoya', 'La península de Nicoya es una península de Costa Rica, la más grande del país, bañada por el océano Pacífico, limitada por el golfo de Papagayo al norte y el Golfo de Nicoya al este y al sur. Tiene una superficie de 5130 km² siendo por lo tanto una de las más grandes de América Central y sólo es sup', 1);

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

--
-- Volcado de datos para la tabla `tbsolicituduniversidadcampus`
--

INSERT INTO `tbsolicituduniversidadcampus` (`tbsolicituduniversidadcampusid`, `tbsolicituduniversidadcampusnombre`, `tbsolicituduniversidadid`, `tbsolicituduniversidadcampusestado`) VALUES
(1, '2', 1, 0);

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
  `tbuniversidadcampusregionid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampus`
--

INSERT INTO `tbuniversidadcampus` (`tbuniversidadcampusid`, `tbuniversidadid`, `tbuniversidadcampusnombre`, `tbuniversidadcampusdireccion`, `tbuniversidadcampusestado`, `tbuniversidadcampuslatitud`, `tbuniversidadcampuslongitud`, `tbuniversidadcampusregionid`) VALUES
(1, 1, 'Campus Sarapiquí', 'La Victoria', 1, '', '', 6),
(2, 2, 'Rodrigo Facio', 'Montes de Oca', 1, '', '', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuniversidadcampuscolectivo`
--

CREATE TABLE `tbuniversidadcampuscolectivo` (
  `tbuniversidadcampuscolectivoid` int(11) NOT NULL,
  `tbuniversidadcampusid` int(11) NOT NULL,
  `tbcolectivoid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampuscolectivo`
--

INSERT INTO `tbuniversidadcampuscolectivo` (`tbuniversidadcampuscolectivoid`, `tbuniversidadcampusid`, `tbcolectivoid`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 1),
(6, 2, 2),
(7, 2, 3),
(8, 2, 4);

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
