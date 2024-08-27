-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-08-2024 a las 04:08:40
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
(1, 'Caribe', 'En Costa Rica es uno de los lugares preferidos de los visitantes debido a su encanto cultural, playas de arena blanca, aguas turquesas, exuberante vegetación, abundante vida silvestre y su proximidad al Parque Nacional Cahuita y a la Reserva Nacional de Vida Silvestre Gandoca Manzanill', 1),
(2, 'San José y Valle Central', 'Un clima agradable y primaveral. Colinas salpicadas de cultivos de colores. Todas las comodidades de la vida moderna en la ciudad no es de extrañar que la mayoría de los costarricenses vivan en la capital de San José, en el Valle Central.', 1),
(3, 'Península de Osa', 'Este es el lugar más salvaje de Costa Rica, una de las zonas más remotas y místicas del país que ha permanecido intacta. Vuelan las guacamayas escarlatas. Los monos se balancean. El jaguar y el tapir dejan huellas en el suelo del bosque.', 1),
(4, 'Pacífico Central', 'De fácil acceso desde San José. Kilómetros de amplias playas. Vibrantes selvas tropicales llenas de fauna. Por eso, el Pacífico Central es una de las regiones más visitadas de Costa Rica.', 1),
(5, 'Guanacaste', 'Con un clima asoleado y seco durante casi todo el año y 400 millas de costa con una variedad de playas vírgenes de arena negra, coralina y dorada, Guanacaste es una de las regiones favoritas de los visitantes internacionales.', 1),
(6, 'Norte', 'Abarca esencialmente las llanuras de Guatuso, San Carlos y Sarapiquí, fronterizas con Nicaragua, así como las estribaciones de la vertiente oriental de la Cordillera Volcánica de Guanacaste y la norte de la Cordillera Vólcanica Central.', 1),
(7, 'Península de Nicoya', 'La península de Nicoya es una península de Costa Rica, la más grande del país, bañada por el océano Pacífico, limitada por el golfo de Papagayo al norte y el Golfo de Nicoya al este y al sur. Tiene una superficie de 5130 km² siendo por lo tanto una de las más grandes de América Central y sólo es sup', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbcriterio`
--

CREATE TABLE `tbcriterio` (
  `tbcriterioid` int(11) NOT NULL,
  `tbcriterionombre` varchar(200) NOT NULL,
  `tbcriterioestado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(10, 'Géneros de Películas Favoritos', 1),
(11, 'Géneros de Libros Favoritos', 1),
(12, 'Géneros de Series de TV Favoritas', 1),
(13, 'Religión', 1),
(14, 'Orientación Política', 1),
(15, 'Frecuencia de Viajes', 1),
(16, 'Relación con la Tecnología', 1),
(17, 'Mascotas', 1),
(18, 'Voluntariado', 1),
(19, 'Vida Nocturna', 1),
(20, 'Uso de Redes Sociales', 1),
(21, 'Preferencias de Clima', 1),
(22, 'Tipo de Lectura', 1);


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
(1, 2, 4, '../resources/img/areaConocimiento/', 'ingeniería-informática.jfif', 1),
(2, 1, 1, '../resources/img/universidad/', 'universidad-nacional-de-costa-rica.png', 1),
(3, 3, 4, '../resources/img/genero/', 'género-fluido.jfif', 1),
(4, 4, 8, '../resources/img/orientacionSexual/', 'sapiosexual.webp', 1);

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
(1, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Estilo de Vida', 'Pop,Introvertido,Ingeniería,Tenis,Activo', '20,20,20,20,20', 1, 1),
(2, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Estilo de Vida', 'Jazz,Extrovertido,Ciencias Sociales,Fútbol,Relajado', '15,25,30,15,15', 2, 1),
(3, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Estilo de Vida', 'Electrónica,Introvertido,Ciencias,Esgrima,Urbano', '25,25,20,10,20', 3, 1),
(4, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Estilo de Vida', 'Rock,Extrovertido,Artes,Baloncesto,Minimalista', '30,20,20,10,20', 4, 1),
(5, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Estilo de Vida', 'Clásica,Introvertido,Humanidades,Natación,Activo', '20,30,20,20,10', 5, 1),
(6, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Estilo de Vida', 'Hip-Hop,Extrovertido,Tecnología,Baloncesto,Urbano', '25,30,15,20,10', 6, 1),
(7, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Estilo de Vida', 'Indie,Introvertido,Ciencias Ambientales,Ciclismo,Minimalista', '20,25,20,15,20', 7, 1),
(8, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Estilo de Vida', 'Reggae,Extrovertido,Derecho,Fútbol,Relajado', '15,25,20,20,20', 8, 1),
(9, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Estilo de Vida', 'Metal,Introvertido,Filosofía,Boxeo,Activo', '30,20,25,10,15', 9, 1),
(10, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Estilo de Vida', 'Blues,Extrovertido,Psicología,Tenis,Relajado', '20,25,20,15,20', 10, 1);

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
(1, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Rock,Introvertido,Ciencias,Fútbol,Pintura,Mañana,Italiana,Lectura,Activo,Comedia,Ficción,Suspenso,Cristianismo,Conservador,Frecuente,Tecnófilo,Perros,ONGs,Discotecas,Facebook', 1, 1),
(2, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Pop,Extrovertido,Artes,Baloncesto,Danza,Tarde,Mexicana,Música,Relajado,Drama,Fantasía,Acción,Ateo,Liberal,Eventual,Desconectado,Gatos,No,Bar,Snapchat', 2, 1),
(3, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Jazz,Introvertido,Literatura,Tenis,Teatro,Noche,China,Cine,Tranquilo,Suspenso,Ciencia ficción,Terror,Católico,Moderado,Frecuente,Tecnófilo,Peces,Sí,Café,Twitter', 3, 1),
(4, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Clásica,Extrovertido,Filosofía,Natación,Pintura,Mañana,Mediterránea,Viajar,Activo,Aventura,Biografía,Comedia,Hinduismo,Liberal,Rara vez,Desconectado,Ninguna,No,Bares,Instagram', 4, 1),
(5, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Rock,Introvertido,Ciencias,Fútbol,Pintura,Mañana,Italiana,Lectura,Activo,Comedia,Ficción,Suspenso,Cristianismo,Conservador,Frecuente,Tecnófilo,Perros,ONGs,Discotecas,Facebook', 5, 1),
(6, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Reggae,Extrovertido,Ingeniería,Baloncesto,Escalada,Tarde,Francesa,Dibujo,Deportivo,Thriller,Fantasía,Documentales,Islam,Conservador,Rara vez,Tecnófilo,Aves,No,Bar,WhatsApp', 6, 1),
(7, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Electrónica,Introvertido,Matemáticas,Running,Club de Lectura,Noche,Japonesa,Videojuegos,Tranquilo,Acción,Ciencia ficción,Suspenso,Ateo,Liberal,Frecuente,Desconectado,Ninguna,No,Ninguna,Reddit', 7, 1),
(8, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Salsa,Extrovertido,Psicología,Fútbol,Baile,Mañana,Mexicana,Cocina,Activo,Romance,Biografía,Comedia,Católico,Moderado,Eventual,Tecnófilo,Gatos,Sí,Bar,Instagram', 8, 1),
(9, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Hip-hop,Extrovertido,Economía,Ciclismo,Voluntariado,Tarde,Tailandesa,Fotografía,Activo,Acción,Biografía,Aventura,Budismo,Moderado,Ocasional,Tecnófilo,Perros,Sí,Festival,Facebook', 9, 1),
(10, 'Gustos Musicales,Introversión/Extroversión,Intereses Académicos,Deportes Favoritos,Actividades Extracurriculares,Preferencias de Estudio,Tipos de Comida Favorita,Hobbies,Estilo de Vida,Películas Favoritas,Libros Favoritos,Series de TV Favoritas,Religión,Orientación Política,Frecuencia de Viajes,Relación con la Tecnología,Mascotas,Voluntariado,Vida Nocturna,Uso de Redes Sociales', 'Blues,Introvertido,Historia,Golf,Fotografía,Mañana,India,Escritura,Tranquilo,Drama,Fantasía,Horror,Judío,Conservador,Eventual,Conectado,Ninguna,No,Cine,LinkedIn', 10, 1);

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
(5, '333333333', 'Kevin', 'Venegas', 1),
(6, '444444444', 'Jamel', 'Hernandez', 1),
(7, '777777777', 'Josue', 'Perez', 1),
(8, '121212121', 'Lucia', 'Mendez', 1),
(9, '131313131', 'Fernando', 'Rojas', 1),
(10, '141414141', 'Andres', 'Gutierrez', 1);

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
  `tbuniversidadcampusespecializacionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampus`
--

INSERT INTO `tbuniversidadcampus` (`tbuniversidadcampusid`, `tbuniversidadid`, `tbuniversidadcampusnombre`, `tbuniversidadcampusdireccion`, `tbuniversidadcampusestado`, `tbuniversidadcampuslatitud`, `tbuniversidadcampuslongitud`, `tbuniversidadcampusregionid`, `tbuniversidadcampusespecializacionid`) VALUES
(1, 1, 'Campus Sarapiquí', 'La Victoria', 1, '', '', 6, 0),
(2, 2, 'Rodrigo Facio', 'Montes de Oca', 1, '', '', 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuniversidadcampuscolectivo`
--

CREATE TABLE `tbuniversidadcampuscolectivo` (
  `tbuniversidadcampuscolectivoid` int(11) NOT NULL,
  `tbuniversidadcampuscolectivonombre` varchar(633) NOT NULL,
  `tbuniversidadcampuscolectivodescripcion` varchar(255) NOT NULL,
  `tbuniversidadcampuscolectivoestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampuscolectivo`
--

INSERT INTO `tbuniversidadcampuscolectivo` (`tbuniversidadcampuscolectivoid`, `tbuniversidadcampuscolectivonombre`, `tbuniversidadcampuscolectivodescripcion`, `tbuniversidadcampuscolectivoestado`) VALUES
(1, 'Volleyball', 'de Volleyball', 1),
(2, 'Fútbol', 'Equipo de Fútbol', 1),
(3, 'Danza', 'Equipo Representativo de Baile', 1),
(4, 'Ping pong', 'Comunidad estudiantil grande interesada en el ping pong', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbuniversidadcampusespecializacion`
--

CREATE TABLE `tbuniversidadcampusespecializacion` (
  `tbuniversidadcampusespecializacionid` int(11) NOT NULL,
  `tbuniversidadcampusespecializacionnombre` varchar(63) NOT NULL,
  `tbuniversidadcampusespecializaciondescripcion` varchar(255) NOT NULL,
  `tbuniversidadcampusespecializacionestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampusespecializacion`
--

INSERT INTO `tbuniversidadcampusespecializacion` (`tbuniversidadcampusespecializacionid`, `tbuniversidadcampusespecializacionnombre`, `tbuniversidadcampusespecializaciondescripcion`, `tbuniversidadcampusespecializacionestado`) VALUES
(1, 'Agricultura y Ciencias Agropecuarias', 'Enfoque en el estudio de agrícolas avanzadas, manejo de cultivos, ganadería, y sostenibilidad en la agricultura.', 1),
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
-- Estructura de tabla para la tabla `tbuniversidadcampusregion`
--

CREATE TABLE `tbuniversidadcampusregion` (
  `tbuniversidadcampusregionid` int(11) NOT NULL,
  `tbuniversidadcampusregionnombre` varchar(300) NOT NULL,
  `tbuniversidadcampusregiondescripcion` varchar(300) NOT NULL,
  `tbuniversidadcampusregionestado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbuniversidadcampusregion`
--

INSERT INTO `tbuniversidadcampusregion` (`tbuniversidadcampusregionid`, `tbuniversidadcampusregionnombre`, `tbuniversidadcampusregiondescripcion`, `tbuniversidadcampusregionestado`) VALUES
(1, 'Caribe', 'En Costa Rica es uno de los lugares preferidos de los visitantes debido a su encanto cultural, playas de arena blanca, aguas turquesas, exuberante vegetación, abundante vida silvestre y su proximidad al Parque Nacional Cahuita y a la Reserva Nacional de Vida Silvestre Gandoca Manzanill', 1),
(2, 'San José y Valle Central', 'Un clima agradable y primaveral. Colinas salpicadas de cultivos de colores. Todas las comodidades de la vida moderna en la ciudad no es de extrañar que la mayoría de los costarricenses vivan en la capital de San José, en el Valle Central.', 1),
(3, 'Península de Osa', 'Este es el lugar más salvaje de Costa Rica, una de las zonas más remotas y místicas del país que ha permanecido intacta. Vuelan las guacamayas escarlatas. Los monos se balancean. El jaguar y el tapir dejan huellas en el suelo del bosque.', 1),
(4, 'Pacífico Central', 'De fácil acceso desde San José. Kilómetros de amplias playas. Vibrantes selvas tropicales llenas de fauna. Por eso, el Pacífico Central es una de las regiones más visitadas de Costa Rica.', 1),
(5, 'Guanacaste', 'Con un clima asoleado y seco durante casi todo el año y 400 millas de costa con una variedad de playas vírgenes de arena negra, coralina y dorada, Guanacaste es una de las regiones favoritas de los visitantes internacionales.', 1),
(6, 'Norte', 'Abarca esencialmente las llanuras de Guatuso, San Carlos y Sarapiquí, fronterizas con Nicaragua, así como las estribaciones de la vertiente oriental de la Cordillera Volcánica de Guanacaste y la norte de la Cordillera Vólcanica Central.', 1),
(7, 'Península de Nicoya', 'La península de Nicoya es una península de Costa Rica, la más grande del país, bañada por el océano Pacífico, limitada por el golfo de Papagayo al norte y el Golfo de Nicoya al este y al sur. Tiene una superficie de 5130 km² siendo por lo tanto una de las más grandes de América Central y sólo es sup', 1);

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
  `tbtipousuarioid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbusuario`
--

INSERT INTO `tbusuario` (`tbusuarioid`, `tbpersonaid`, `tbusuarionombre`, `tbusuariocontrasena`, `tbusuarioestado`, `tbtipousuarioid`) VALUES
(1, 1, 'jamyrg', '123', 1, 1),
(2, 2, 'jeycobbg', '123', 1, 1),
(3, 3, 'profe', '123', 1, 1),
(4, 4, 'gerald', '123', 1, 1),
(5, 5, 'kevin', '123', 1, 1),
(6, 6, 'jamel', '123', 1, 1),
(7, 7, 'josue', '123', 1, 2),
(8, 8, 'lucia', '123', 1, 2),
(9, 9, 'fernanda', '123', 1, 2),
(10, 10, 'andres', '123', 1, 2);

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
(3, 'Clásica', 1, 1),
(4, 'Jazz', 1, 1),
(5, 'Introvertido', 2, 1),
(6, 'Extrovertido', 2, 1),
(7, 'Ciencias', 3, 1),
(8, 'Humanidades', 3, 1),
(9, 'Fútbol', 4, 1),
(10, 'Baloncesto', 4, 1),
(11, 'Pintura', 5, 1),
(12, 'Música', 5, 1),
(13, 'Mañana', 6, 1),
(14, 'Noche', 6, 1),
(15, 'Italiana', 7, 1),
(16, 'Mexicana', 7, 1),
(17, 'Lectura', 8, 1),
(18, 'Videojuegos', 8, 1),
(19, 'Activo', 9, 1),
(20, 'Tranquilo', 9, 1),
(21, 'Comedia', 10, 1),
(22, 'Drama', 10, 1),
(23, 'Ficción', 11, 1),
(24, 'No ficción', 11, 1),
(25, 'Suspenso', 12, 1),
(26, 'Ciencia ficción', 12, 1),
(27, 'Cristianismo', 13, 1),
(28, 'Ateísmo', 13, 1),
(29, 'Conservador', 14, 1),
(30, 'Liberal', 14, 1),
(31, 'Frecuente', 15, 1),
(32, 'Ocasional', 15, 1),
(33, 'Tecnófilo', 16, 1),
(34, 'Tecnófobo', 16, 1),
(35, 'Perros', 17, 1),
(36, 'Gatos', 17, 1),
(37, 'ONGs', 18, 1),
(38, 'Ecología', 18, 1),
(39, 'Discotecas', 19, 1),
(40, 'Bares tranquilos', 19, 1),
(41, 'Facebook', 20, 1),
(42, 'Instagram', 20, 1),
(43, 'Frío', 21, 1),
(44, 'Cálido', 21, 1),
(45, 'Novela', 22, 1),
(46, 'Poesía', 22, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
