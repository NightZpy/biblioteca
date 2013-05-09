-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 09-05-2013 a las 03:46:53
-- Versión del servidor: 5.5.29
-- Versión de PHP: 5.4.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `biblioteca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Nombre para la categoria de los libros',
  `descripcion` text CHARACTER SET utf8 COLLATE utf8_bin COMMENT 'Descripción más detallada sobre dicha categoría',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Matemática', 'Ciencia exacta.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE IF NOT EXISTS `libros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL COMMENT 'código único de identificación del libro en la biblioteca',
  `autor` varchar(100) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `editorial` varchar(150) NOT NULL,
  `ejemplar` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Saber si es el original o una copia',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en que fue recibido el libro en la biblioteca',
  `categoria_id` int(10) unsigned NOT NULL COMMENT 'Código asociado a la categoría perteneciente al libro',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `codigo`, `autor`, `titulo`, `descripcion`, `editorial`, `ejemplar`, `fecha_ingreso`, `categoria_id`) VALUES
(2, 'abcdef', 'Laurent', 'Calculo ', 'Calculos con derivadas e integrales', 'Numeric Editorial', 1, '2013-02-02', 1),
(3, 'axcdeg', 'José', 'Geometria ', 'Calculos y representación geometrica', 'Santillana', 1, '2013-01-02', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE IF NOT EXISTS `personas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) DEFAULT NULL COMMENT 'Código del estudiante dentro de la institución',
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `nacionalidad` char(1) NOT NULL DEFAULT 'v' COMMENT 'V ó E',
  `email` varchar(60) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL COMMENT 'Teléfono fijo de lugar de habitación ',
  `movil` varchar(15) DEFAULT NULL,
  `direccion` text NOT NULL COMMENT 'Dirección de habitación del estudiante (Calle, edificio, etc.)',
  `procedencia` varchar(120) NOT NULL COMMENT 'Institución o lugar de donde proviene la persona',
  `tipo_persona_id` int(10) unsigned NOT NULL COMMENT 'Cógido asociado perteneciente a la carrera que cursa',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cedula` (`cedula`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `carrera_id` (`tipo_persona_id`),
  KEY `tipo_persona_id` (`tipo_persona_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE IF NOT EXISTS `prestamos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `persona_id` int(10) unsigned NOT NULL COMMENT 'Id asociado al estudiante que realizó el prestamo',
  `libro_id` int(10) unsigned NOT NULL COMMENT 'Id asociado al libro prestado',
  `fecha_prestamo` date NOT NULL COMMENT 'Fecha en que se sede el libro en calidad de prestamo',
  `fecha_entrega` date DEFAULT NULL COMMENT 'Fecha en que se recibe el libro',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`persona_id`,`libro_id`),
  KEY `libro_id` (`libro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supendidos`
--

CREATE TABLE IF NOT EXISTS `supendidos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `libro_id` int(10) unsigned NOT NULL COMMENT 'Id asociado al libro por el cual se suspende',
  `persona_id` int(10) unsigned NOT NULL COMMENT 'Id asociado al estudiante que ha sido suspendido del servicio ',
  `desde` date NOT NULL COMMENT 'Fecha desde la cuál se suspende',
  `hasta` date NOT NULL COMMENT 'Fecha hasta la que permanecerá suspendido el estudiante',
  PRIMARY KEY (`id`),
  KEY `libro_id` (`libro_id`,`persona_id`),
  KEY `usuario_id` (`persona_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_personas`
--

CREATE TABLE IF NOT EXISTS `tipo_personas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` int(11) NOT NULL COMMENT 'Usuario que tiene acceso para agregar libros, realizar prestamos, etc. (Encargado)',
  `password` int(11) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `movil` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `cedula` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`tipo_persona_id`) REFERENCES `tipo_personas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_3` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `supendidos`
--
ALTER TABLE `supendidos`
  ADD CONSTRAINT `supendidos_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
