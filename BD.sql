SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `basededatos`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tblsensores`
-- 

CREATE TABLE `tblsensores` (
  `id` int(11) NOT NULL auto_increment,
  `humedad` float(4,2) NOT NULL,
  `temperatura` float(4,2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Volcar la base de datos para la tabla `tblsensores`
-- 

INSERT INTO `tblsensores` VALUES (2, 41.50, 30.20);
INSERT INTO `tblsensores` VALUES (3, 42.00, 28.70);
INSERT INTO `tblsensores` VALUES (4, 50.00, 31.00);
INSERT INTO `tblsensores` VALUES (5, 45.00, 26.70);