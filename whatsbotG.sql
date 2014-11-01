CREATE DATABASE `whastbotg`;


DROP TABLE `usuarios_grupo`;

CREATE TABLE `usuarios_grupo` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `id` mediumint,
  `numero` varchar(100) default NULL,
  `grupo` varchar(255),
  `admin` varchar(255),
  `nombre` varchar(255) default NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=1;


DROP TABLE `grupos`;

CREATE TABLE `grupos` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `id` mediumint,
  `grupoid` varchar(255),
  `fecha` varchar(255),
  `idioma` varchar(255),
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=1;