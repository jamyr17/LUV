-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-09-2024 a las 03:03:05
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
(2, 'Física', 'Ciencia que las propiedades y el comportamiento de la materia y la energía.', 1),
(3, 'Biología', 'Ciencia que estudia a los seres vivos y sus procesos vitales.', 1),
(4, 'Ingeniería Informática', 'Disciplina que se encarga del diseño y desarrollo de sistemas y aplicaciones informáticas.', 1),
(5, 'Psicología', 'Ciencia que estudia el comportamiento y los procesos mentales de los individuos.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbcriterio`
--

CREATE TABLE `tbcriterio` (
  `tbcriterioid` int(11) NOT NULL,
  `tbcriterionombre` varchar(200) NOT NULL,
  `tbcriterioestado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbcriterio`
--

INSERT INTO `tbcriterio` (`tbcriterioid`, `tbcriterionombre`, `tbcriterioestado`) VALUES
(1, 'Gustos Musicales', 1),
(2, 'Introversión/Extroversión', 1),
(3, 'Intereses Académicos', 1),
(4, 'Deportes Favoritos', 1),
(5, 'Actividades Extracurriculares', 1),
(6, 'Preferencias de Estudio', 1),
(7, 'Tipos de Comida Favorita', 1),
(8, 'Hobbies', 1),
(9, 'Estilo de Vida', 1),
(10, 'Películas Favoritas', 1),
(11, 'Libros Favoritos', 1),
(12, 'Series de TV Favoritas', 1),
(13, 'Religión', 1),
(14, 'Orientación Política', 1),
(15, 'Frecuencia de Viajes', 1),
(16, 'Relación con la Tecnología', 1),
(17, 'Mascotas', 1),
(18, 'Voluntariado', 1),
(19, 'Vida Nocturna', 1),
(20, 'Uso de Redes Sociales', 1);

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
(1, 'Masculino', 'se refiere a personas que se identifican con el género masculino, lo que suele estar asociado a hombres cisgénero (aquellos cuya identidad de género coincide con el sexo asignado al nacer).', 1),
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
  `tbperfilusuariodeseadoestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbperfilusuariodeseado`
--

INSERT INTO `tbperfilusuariodeseado` (`tbperfilusuariodeseadoid`, `tbperfilusuariodeseadocriterio`, `tbperfilusuariodeseadovalor`, `tbperfilusuariodeseadoporcentaje`, `tbperfilusuariodeseadoestado`) VALUES
(1, 'Hobbies,Intereses Académicos,Deportes Favoritos,Religión,Series de TV Favoritas,Estilo de Vida,Mascotas', 'Videojuegos,Ciencias,Fútbol,Cristianismo,Ciencia ficción,Tranquilo,Perros', '10,6,40,30,10,2,2', 1),
(2, 'Hobbies,Estilo de Vida,Mascotas', 'Videojuegos,Tranquilo,Gatos', '16,20,64', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbperfilusuariopersonal`
--

CREATE TABLE `tbperfilusuariopersonal` (
  `tbperfilusuariopersonalid` int(11) NOT NULL,
  `tbperfilusuariopersonalcriterio` varchar(1024) NOT NULL,
  `tbperfilusuariopersonalvalor` varchar(1024) NOT NULL,
  `tbperfilusuariopersonalestado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbperfilusuariopersonal`
--

INSERT INTO `tbperfilusuariopersonal` (`tbperfilusuariopersonalid`, `tbperfilusuariopersonalcriterio`, `tbperfilusuariopersonalvalor`, `tbperfilusuariopersonalestado`) VALUES
(1, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Rock,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles,No hay valores disponibles', 1),
(2, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Rock,Introvertido,Ciencias,Fútbol,Pintura,Mañana,Italiana,Lectura,Activo,Comedia,Ficción,Suspenso,Cristianismo,Conservador,Frecuente,Tecnófilo,Perros,ONGs,Discotecas,Facebook', 1),
(3, '', '', 1),
(4, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Rock,Introvertido,Ciencias,Fútbol,Pintura,Mañana,Italiana,Lectura,Activo,Comedia,Ficción,Suspenso,Cristianismo,Conservador,Frecuente,Tecnófilo,Perros,ONGs,Discotecas,Facebook', 1),
(5, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Pop,Extrovertido,Ciencias,Fútbol,Música,Mañana,Mexicana,Videojuegos,Activo,Comedia,No ficción,Suspenso,Cristianismo,Liberal,Frecuente,Tecnófilo,Gatos,ONGs,Discotecas,Instagram', 1),
(6, '', '', 1),
(7, '', '', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampusuniversidadcolectivo`
--

INSERT INTO `tbuniversidadcampusuniversidadcolectivo` (`tbuniversidadcampusuniversidadcolectivoid`, `tbuniversidadcampusid`, `tbuniversidadcolectivoid`) VALUES
(6, 3, 1),
(7, 3, 2),
(6, 1, 1),
(7, 1, 2),
(8, 1, 4),
(6, 2, 1),
(7, 2, 2),
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
  `tbtipousuarioid` int(11) NOT NULL,
  `usuarioimagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbusuario`
--

INSERT INTO `tbusuario` (`tbusuarioid`, `tbusuarionombre`, `tbusuariocontrasena`, `tbusuarioestado`, `tbtipousuarioid`, `usuarioimagen`) VALUES
(1, 'jamyrg', '123', 1, 1, ''),
(1, 'jeycobbg', '123', 1, 1, ''),
(3, 'profe', '123', 1, 1, ''),
(4, 'gerald', '123', 1, 1, ''),
(5, 'kevin', '123', 1, 1, ''),
(6, 'jamel', '123', 1, 1, ''),
(7, 'admin', 'admin', 1, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbvalor`
--

CREATE TABLE `tbvalor` (
  `tbvalorid` int(11) NOT NULL,
  `tbvalornombre` varchar(200) NOT NULL,
  `tbcriterioid` int(11) NOT NULL,
  `tbvalorestado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbvalor`
--

INSERT INTO `tbvalor` (`tbvalorid`, `tbvalornombre`, `tbcriterioid`, `tbvalorestado`) VALUES
(1, 'Rock', 1, 1),
(2, 'Pop', 1, 1),
(3, 'Introvertido', 2, 1),
(4, 'Extrovertido', 2, 1),
(5, 'Ciencias', 3, 1),
(6, 'Humanidades', 3, 1),
(7, 'Fútbol', 4, 1),
(8, 'Baloncesto', 4, 1),
(9, 'Pintura', 5, 1),
(10, 'Música', 5, 1),
(11, 'Mañana', 6, 1),
(12, 'Noche', 6, 1),
(13, 'Italiana', 7, 1),
(14, 'Mexicana', 7, 1),
(15, 'Lectura', 8, 1),
(16, 'Videojuegos', 8, 1),
(17, 'Activo', 9, 1),
(18, 'Tranquilo', 9, 1),
(19, 'Comedia', 10, 1),
(20, 'Drama', 10, 1),
(21, 'Ficción', 11, 1),
(22, 'No ficción', 11, 1),
(23, 'Suspenso', 12, 1),
(24, 'Ciencia ficción', 12, 1),
(25, 'Cristianismo', 13, 1),
(26, 'Ateísmo', 13, 1),
(27, 'Conservador', 14, 1),
(28, 'Liberal', 14, 1),
(29, 'Frecuente', 15, 1),
(30, 'Ocasional', 15, 1),
(31, 'Tecnófilo', 16, 1),
(32, 'Tecnófobo', 16, 1),
(33, 'Perros', 17, 1),
(34, 'Gatos', 17, 1),
(35, 'ONGs', 18, 1),
(36, 'Ecología', 18, 1),
(37, 'Discotecas', 19, 1),
(38, 'Bares tranquilos', 19, 1),
(39, 'Facebook', 20, 1),
(40, 'Instagram', 20, 1),
(41, 'Reggae', 1, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
