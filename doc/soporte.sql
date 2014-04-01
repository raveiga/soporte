-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 01-04-2014 a las 19:05:11
-- Versión del servidor: 5.1.73
-- Versión de PHP: 5.3.3-7+squeeze19

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `c2base2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte_administracion`
--

CREATE TABLE IF NOT EXISTS `soporte_administracion` (
  `idusuario` varchar(50) NOT NULL,
  `idzonagestiona` int(11) unsigned NOT NULL,
  PRIMARY KEY (`idusuario`,`idzonagestiona`),
  KEY `idzonagestiona` (`idzonagestiona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `soporte_administracion`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte_equipamiento`
--

CREATE TABLE IF NOT EXISTS `soporte_equipamiento` (
  `idmaquina` varchar(50) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `descrip` varchar(100) NOT NULL,
  `zona` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idmaquina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `soporte_equipamiento`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte_prioridades`
--

CREATE TABLE IF NOT EXISTS `soporte_prioridades` (
  `idprioridad` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prioridad` varchar(15) NOT NULL,
  `color` varchar(15) NOT NULL,
  `urgencia` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`idprioridad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `soporte_prioridades`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte_respuestasprogramadas`
--

CREATE TABLE IF NOT EXISTS `soporte_respuestasprogramadas` (
  `idrespuesta` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `texto` longtext NOT NULL,
  PRIMARY KEY (`idrespuesta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `soporte_respuestasprogramadas`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte_tickets`
--

CREATE TABLE IF NOT EXISTS `soporte_tickets` (
  `idticket` int(11) NOT NULL AUTO_INCREMENT,
  `idmaquina` varchar(50) NOT NULL,
  `idusuariotransferida` varchar(50) NOT NULL,
  `topico` int(10) unsigned NOT NULL,
  `prioridad` int(10) unsigned NOT NULL,
  `estado` varchar(15) NOT NULL,
  `asunto` varchar(150) NOT NULL,
  `detalles` longtext NOT NULL,
  `idusuariocomunica` varchar(50) NOT NULL,
  `fechacomunicacion` datetime NOT NULL,
  `fechacierre` datetime NOT NULL,
  `idusuariocierra` varchar(50) NOT NULL,
  `textocierre` longtext NOT NULL,
  `textohowto` longtext NOT NULL,
  `historicoprocesamiento` longtext NOT NULL,
  PRIMARY KEY (`idticket`),
  KEY `idmaquina` (`idmaquina`),
  KEY `topico` (`topico`),
  KEY `idusuariocomunica` (`idusuariocomunica`),
  KEY `idusuariocierra` (`idusuariocierra`),
  KEY `idusuariotransferida` (`idusuariotransferida`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `soporte_tickets`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte_topicos`
--

CREATE TABLE IF NOT EXISTS `soporte_topicos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `texto` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `soporte_topicos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte_usuarios`
--

CREATE TABLE IF NOT EXISTS `soporte_usuarios` (
  `idusuario` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mensajeria` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `preferenciacomunicacion` char(1) NOT NULL,
  `rangonomolestar` varchar(50) DEFAULT NULL,
  `tipousuario` char(1) NOT NULL,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `soporte_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte_zonas`
--

CREATE TABLE IF NOT EXISTS `soporte_zonas` (
  `idzona` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombrezona` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`idzona`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `soporte_zonas`
--


--
-- Filtros para las tablas descargadas (dump)
--

