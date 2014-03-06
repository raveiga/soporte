SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte_administracion`
--

DROP TABLE IF EXISTS `soporte_administracion`;
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

DROP TABLE IF EXISTS `soporte_equipamiento`;
CREATE TABLE IF NOT EXISTS `soporte_equipamiento` (
  `idmaquina` varchar(50) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
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

DROP TABLE IF EXISTS `soporte_prioridades`;
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

DROP TABLE IF EXISTS `soporte_respuestasprogramadas`;
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

DROP TABLE IF EXISTS `soporte_tickets`;
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

DROP TABLE IF EXISTS `soporte_topicos`;
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

DROP TABLE IF EXISTS `soporte_usuarios`;
CREATE TABLE IF NOT EXISTS `soporte_usuarios` (
  `idusuario` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mensajeria` varchar(100) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `privilegio` varchar(2) NOT NULL,
  `preferenciacomunicacion` varchar(2) NOT NULL,
  `rangonomolestar` varchar(50) NOT NULL,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `soporte_usuarios`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte_zonas`
--

DROP TABLE IF EXISTS `soporte_zonas`;
CREATE TABLE IF NOT EXISTS `soporte_zonas` (
  `idzona` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombrezona` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`idzona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Filtros para la tabla `soporte_administracion`
--
ALTER TABLE `soporte_administracion`
  ADD CONSTRAINT `soporte_administracion_ibfk_2` FOREIGN KEY (`idzonagestiona`) REFERENCES `soporte_zonas` (`idzona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `soporte_administracion_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `soporte_usuarios` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `soporte_tickets`
--
ALTER TABLE `soporte_tickets`
  ADD CONSTRAINT `soporte_tickets_ibfk_5` FOREIGN KEY (`idusuariocierra`) REFERENCES `soporte_usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `soporte_tickets_ibfk_1` FOREIGN KEY (`idmaquina`) REFERENCES `soporte_equipamiento` (`idmaquina`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `soporte_tickets_ibfk_2` FOREIGN KEY (`idusuariotransferida`) REFERENCES `soporte_usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `soporte_tickets_ibfk_3` FOREIGN KEY (`topico`) REFERENCES `soporte_topicos` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `soporte_tickets_ibfk_4` FOREIGN KEY (`idusuariocomunica`) REFERENCES `soporte_usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE CASCADE;
