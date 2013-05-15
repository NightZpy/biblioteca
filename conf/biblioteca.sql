-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 15-05-2013 a las 10:18:44
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
-- Estructura de tabla para la tabla `cotas`
--

CREATE TABLE IF NOT EXISTS `cotas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'Indicador de cota',
  `libro_id` int(11) unsigned NOT NULL COMMENT 'codigo del libro al que pertenece la copia',
  `disponible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `codigo_libro` (`libro_id`),
  KEY `disponible` (`disponible`),
  KEY `nombre` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Volcado de datos para la tabla `cotas`
--

INSERT INTO `cotas` (`id`, `nombre`, `libro_id`, `disponible`) VALUES
(1, 1, 7, 1),
(22, 2, 7, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE IF NOT EXISTS `libros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL COMMENT 'código único de identificación del libro en la biblioteca',
  `autor` varchar(100) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text,
  `editorial` varchar(150) NOT NULL,
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en que fue recibido el libro en la biblioteca',
  `categoria_id` int(10) unsigned DEFAULT NULL COMMENT 'Código asociado a la categoría perteneciente al libro',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `codigo_2` (`codigo`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `codigo`, `autor`, `titulo`, `descripcion`, `editorial`, `fecha_ingreso`, `categoria_id`) VALUES
(7, 'a2c3d4D', 'Alguien', 'Matematica Divertida', 'sdfasdfasdf', 'Santillana', '2013-05-24', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE IF NOT EXISTS `personas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  KEY `carrera_id` (`tipo_persona_id`),
  KEY `tipo_persona_id` (`tipo_persona_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `nombres`, `apellidos`, `cedula`, `nacionalidad`, `email`, `telefono`, `movil`, `direccion`, `procedencia`, `tipo_persona_id`) VALUES
(1, 'Lenynnnnn', 'asdfasdf', '1234567', 'e', 'Lenyn@gmail.com', '123412', '123423412', 'dfasdf', 'asdfasdfasdfasdfasdfasd', 3),
(2, 'pedro', 'ramirez', '19729157', 'v', 'r_ramirez@hotmail.com', '2147483647', '02767678678', 'barrio sucre', 'bolivariana', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE IF NOT EXISTS `prestamos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `persona_id` int(10) unsigned DEFAULT NULL COMMENT 'Id asociado al estudiante que realizó el prestamo',
  `usuario_id` int(10) unsigned DEFAULT NULL COMMENT 'Identificador del usuario que aprueba el prestamo',
  `cota_id` int(10) unsigned DEFAULT NULL COMMENT 'Id asociado al libro prestado',
  `fecha_prestamo` date NOT NULL COMMENT 'Fecha en que se sede el libro en calidad de prestamo',
  `fecha_entrega` date NOT NULL COMMENT 'Fecha en que se recibe el libro',
  `fecha_entregado` date DEFAULT NULL COMMENT 'Fecha en que es devuelto el libro',
  PRIMARY KEY (`id`),
  KEY `libro_id` (`cota_id`),
  KEY `usuario_id_2` (`usuario_id`),
  KEY `libro_id_2` (`cota_id`),
  KEY `persona_id` (`persona_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=159 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suspendidos`
--

CREATE TABLE IF NOT EXISTS `suspendidos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cota_id` int(10) unsigned DEFAULT NULL COMMENT 'Id asociado al libro por el cual se suspende',
  `persona_id` int(10) unsigned DEFAULT NULL COMMENT 'Id asociado al estudiante que ha sido suspendido del servicio ',
  `desde` date NOT NULL COMMENT 'Fecha desde la cuál se suspende',
  `hasta` date NOT NULL COMMENT 'Fecha hasta la que permanecerá suspendido el estudiante',
  PRIMARY KEY (`id`),
  KEY `libro_id` (`cota_id`,`persona_id`),
  KEY `usuario_id` (`persona_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_personas`
--

CREATE TABLE IF NOT EXISTS `tipo_personas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `tipo_personas`
--

INSERT INTO `tipo_personas` (`id`, `nombre`) VALUES
(1, 'Profesor'),
(2, 'Alumno'),
(3, 'Interno'),
(4, 'Circulante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL COMMENT 'Usuario que tiene acceso para agregar libros, realizar prestamos, etc. (Encargado)',
  `password` varchar(200) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `movil` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `cedula` (`cedula`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `cedula`, `nombres`, `apellidos`, `direccion`, `email`, `movil`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1234', 'Administrador 1', 'Admini', 'Por ahí', 'admin@biblioteca.com', '12345678'),
(2, 'user2', 'e10adc3949ba59abbe56e057f20f883e', '234234234', 'asdfa', 'asfadf', 'asdfasdfasdf', 'lenyn@algo.com', '234234234234');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cotas`
--
ALTER TABLE `cotas`
  ADD CONSTRAINT `cotas_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `libros_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`tipo_persona_id`) REFERENCES `tipo_personas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_14` FOREIGN KEY (`cota_id`) REFERENCES `cotas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `prestamos_ibfk_12` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `prestamos_ibfk_13` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `suspendidos`
--
ALTER TABLE `suspendidos`
  ADD CONSTRAINT `suspendidos_ibfk_9` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `suspendidos_ibfk_8` FOREIGN KEY (`cota_id`) REFERENCES `cotas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
