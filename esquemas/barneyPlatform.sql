-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 20 Septembre 2014 à 11:02
-- Version du serveur: 5.5.38-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `barneyPlatform`
--

-- --------------------------------------------------------

--
-- Structure de la table `archivo`
--

CREATE TABLE IF NOT EXISTS `archivo` (
  `archivo_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`archivo_id`),
  KEY `fk_archivo_tipo` (`tipo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Contenu de la table `archivo`
--

INSERT INTO `archivo` (`archivo_id`, `tipo_id`, `nombre`, `peso`, `extension`) VALUES
(1, 1, 'p3-suscripcion-curso-1.pdf', NULL, 'pdf'),
(2, 1, 'p3-suscripcion-curso-1.pdf', NULL, 'pdf'),
(3, 1, 'p4-suscripcion-curso-2.pdf', NULL, 'pdf'),
(4, 1, 'p6-suscripcion-curso-2.pdf', NULL, 'pdf'),
(5, 1, 'p9-suscripcion-curso-2.pdf', NULL, 'pdf'),
(6, 1, 'usuario-suscripcion-curso-1.pdf', NULL, 'pdf'),
(7, 1, 'nuevo-suscripcion-curso-2.pdf', NULL, 'pdf'),
(8, 1, 'nuevo-suscripcion-curso-1.pdf', NULL, 'pdf'),
(9, 1, 'p1-suscripcion-curso-2.pdf', NULL, 'pdf'),
(10, 1, 'p2-suscripcion-curso-2.pdf', NULL, 'pdf'),
(11, 1, 'p3-suscripcion-curso-2.pdf', NULL, 'pdf'),
(12, 1, 'p1-suscripcion-curso-10.pdf', NULL, 'pdf'),
(14, 2, '5413d55959668php.JPG', 7591, 'image/jpeg'),
(20, 3, '54149ccd9463b.pdf', 550611, 'applicatio'),
(21, 3, '54149ccdaf661.pdf', 1232460, 'applicatio'),
(22, 3, '54149ccdc7e9b.pdf', 274044, 'applicatio'),
(23, 3, '5414c5e185a81.pdf', 274044, 'applicatio'),
(24, 1, 'p3-suscripcion-curso-36.pdf', NULL, 'pdf'),
(25, 1, 'barney-suscripcion-curso-36.pdf', NULL, 'pdf'),
(26, 1, 'barney-suscripcion-curso-10.pdf', NULL, 'pdf'),
(27, 3, '541702bd1bf8a.pdf', 1232460, 'applicatio'),
(28, 1, 'tabcin-suscripcion-curso-10.pdf', NULL, 'pdf'),
(29, 1, 'nuevo-suscripcion-curso-36.pdf', NULL, 'pdf'),
(30, 1, 'p3-suscripcion-curso-1.pdf', NULL, 'pdf'),
(31, 3, '541a11840fe6b.pdf', 17272, 'applicatio'),
(32, 3, '541cf5acac0ee.pdf', 42427, 'applicatio');

-- --------------------------------------------------------

--
-- Structure de la table `archivoPerfil`
--

CREATE TABLE IF NOT EXISTS `archivoPerfil` (
  `perfil_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `archivo_id` int(11) NOT NULL,
  `fechaExp` date DEFAULT NULL,
  KEY `fk_aP_perfil` (`perfil_id`),
  KEY `fk_aP_curso` (`curso_id`),
  KEY `fk_archP_archivo` (`archivo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `archivoPerfil`
--

INSERT INTO `archivoPerfil` (`perfil_id`, `curso_id`, `archivo_id`, `fechaExp`) VALUES
(15, 1, 6, '2014-09-06'),
(16, 2, 7, '2014-09-06'),
(16, 1, 8, '2014-09-06'),
(21, 2, 9, '2014-09-07'),
(22, 2, 10, '2014-09-07'),
(23, 2, 11, '2014-09-07'),
(21, 10, 12, '2014-09-11'),
(23, 36, 24, '2014-09-14'),
(26, 36, 25, '2014-09-15'),
(26, 10, 26, '2014-09-15'),
(27, 10, 28, '2014-09-15'),
(16, 36, 29, '2014-09-15'),
(23, 1, 30, '2014-09-17');

-- --------------------------------------------------------

--
-- Structure de la table `archivo_leccion`
--

CREATE TABLE IF NOT EXISTS `archivo_leccion` (
  `archivo_id` int(11) NOT NULL,
  `leccion_id` int(11) NOT NULL,
  KEY `fk_al_archivo` (`archivo_id`),
  KEY `fk_al_leccion` (`leccion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `archivo_leccion`
--

INSERT INTO `archivo_leccion` (`archivo_id`, `leccion_id`) VALUES
(20, 91),
(21, 91),
(22, 91),
(23, 92),
(27, 93),
(31, 91),
(32, 91);

-- --------------------------------------------------------

--
-- Structure de la table `beca`
--

CREATE TABLE IF NOT EXISTS `beca` (
  `beca_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`beca_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `beca`
--

INSERT INTO `beca` (`beca_id`, `nombre`, `descripcion`) VALUES
(1, 'normal', 'no se aplica descuento');

-- --------------------------------------------------------

--
-- Structure de la table `curso`
--

CREATE TABLE IF NOT EXISTS `curso` (
  `curso_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipoCurso` int(11) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `fechaInicio` date DEFAULT NULL,
  `fechaTermino` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `imagenCurso` int(11) DEFAULT NULL,
  `total_alumnos` int(11) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`curso_id`),
  KEY `fk_curso_tipo` (`tipoCurso`),
  KEY `fk_foto_archivo` (`imagenCurso`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Contenu de la table `curso`
--

INSERT INTO `curso` (`curso_id`, `tipoCurso`, `nombre`, `fechaInicio`, `fechaTermino`, `status`, `imagenCurso`, `total_alumnos`, `descripcion`) VALUES
(1, 1, 'Frontend', '2014-09-21', '2014-09-25', 1, NULL, 1, 'Un curso profesional de Frontend'),
(2, 2, 'Backend', '2014-09-21', '2014-09-25', 1, NULL, 0, NULL),
(10, 1, 'HTML', '2014-09-21', '2014-09-25', 1, NULL, 2, NULL),
(36, 2, 'PHP-MYSQL', '2014-09-21', '2014-09-25', 1, 14, 2, 'Un curso profesional de PHP');

-- --------------------------------------------------------

--
-- Structure de la table `curso_maestro`
--

CREATE TABLE IF NOT EXISTS `curso_maestro` (
  `curso_id` int(11) NOT NULL,
  `maestro_id` int(11) NOT NULL,
  KEY `curso_id` (`curso_id`),
  KEY `maestro_id` (`maestro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `curso_maestro`
--

INSERT INTO `curso_maestro` (`curso_id`, `maestro_id`) VALUES
(36, 20);

-- --------------------------------------------------------

--
-- Structure de la table `curso_perfil`
--

CREATE TABLE IF NOT EXISTS `curso_perfil` (
  `perfil_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `beca_id` int(11) NOT NULL,
  `payed` int(11) NOT NULL,
  KEY `fk_pc_perfil` (`perfil_id`),
  KEY `fk_pc_curso` (`curso_id`),
  KEY `beca_id` (`beca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `curso_perfil`
--

INSERT INTO `curso_perfil` (`perfil_id`, `curso_id`, `beca_id`, `payed`) VALUES
(15, 1, 1, 1),
(16, 2, 1, 1),
(16, 1, 1, 1),
(21, 2, 1, 1),
(22, 2, 1, 0),
(23, 2, 1, 1),
(21, 10, 1, 0),
(23, 36, 1, 1),
(26, 36, 1, 1),
(26, 10, 1, 0),
(27, 10, 1, 0),
(16, 36, 1, 1),
(23, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `leccion`
--

CREATE TABLE IF NOT EXISTS `leccion` (
  `leccion_id` int(11) NOT NULL AUTO_INCREMENT,
  `unidad_id` int(11) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`leccion_id`),
  KEY `fk_leccion_unidad` (`unidad_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100 ;

--
-- Contenu de la table `leccion`
--

INSERT INTO `leccion` (`leccion_id`, `unidad_id`, `nombre`, `descripcion`) VALUES
(91, 38, 'Algo de Historia', 'una descripcion de leccion'),
(92, 38, 'Conceptos Basicos', 'una descripcion de leccion'),
(93, 38, 'Primer plantilla', 'una descripcion de leccion'),
(94, 39, 'Algo de Historia', 'una descripcion de leccion'),
(95, 39, 'Conceptos Basicos', 'una descripcion de leccion'),
(96, 39, 'Primer plantilla', 'una descripcion de leccion'),
(97, 40, 'Algo de Historia', 'una descripcion de leccion'),
(98, 40, 'Conceptos Basicos', 'una descripcion de leccion'),
(99, 40, 'Primer plantilla', 'una descripcion de leccion');

-- --------------------------------------------------------

--
-- Structure de la table `level`
--

CREATE TABLE IF NOT EXISTS `level` (
  `level_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(80) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `level`
--

INSERT INTO `level` (`level_id`, `label`, `level`) VALUES
(1, 'admin', 10000),
(2, 'escolares', 1000),
(3, 'maestro', 100),
(4, 'alumno', 10),
(5, 'invitado', 0);

-- --------------------------------------------------------

--
-- Structure de la table `listaCurso`
--

CREATE TABLE IF NOT EXISTS `listaCurso` (
  `lista_id` int(11) NOT NULL AUTO_INCREMENT,
  `perfil_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `asistencia` int(11) DEFAULT NULL,
  PRIMARY KEY (`lista_id`),
  KEY `curso_id` (`curso_id`),
  KEY `perfil_id` (`perfil_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `maestro`
--

CREATE TABLE IF NOT EXISTS `maestro` (
  `maestro_id` int(11) NOT NULL,
  PRIMARY KEY (`maestro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `maestro`
--

INSERT INTO `maestro` (`maestro_id`) VALUES
(20),
(24);

-- --------------------------------------------------------

--
-- Structure de la table `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `perfil_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellidoPaterno` varchar(50) DEFAULT NULL,
  `apellidoMaterno` varchar(50) DEFAULT NULL,
  `institucion` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `fotoPerfil` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`perfil_id`),
  KEY `fk_foto_archivo` (`fotoPerfil`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `perfil`
--

INSERT INTO `perfil` (`perfil_id`, `username`, `email`, `nombre`, `apellidoPaterno`, `apellidoMaterno`, `institucion`, `telefono`, `fechaNacimiento`, `descripcion`, `fotoPerfil`, `status`) VALUES
(15, 'usuario', 'usuario@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'nuevo', 'nuevo@gmail.com', NULL, '', '', '', '', NULL, 'un nuevo usuario XD', NULL, NULL),
(17, 'escolares', 'escolares@gmai.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'maestro', 'maestro@maestro.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'p1', 'p1@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 'esto es una descripcion', NULL, NULL),
(22, 'p2', 'p2@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'p3', 'p3@hotmail.com', NULL, 'ppppp', '', '', '', NULL, '', NULL, NULL),
(24, 'nuevoMaestro', 'nuevoMaestro@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'p4', 'p4@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'barney', 'barney@gmail.com', NULL, 'awesome', '', '', '', NULL, '', NULL, NULL),
(27, 'tabcin', 'tabcin@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `pregunta`
--

CREATE TABLE IF NOT EXISTS `pregunta` (
  `pregunta_id` int(11) NOT NULL AUTO_INCREMENT,
  `perfil_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `asunto` varchar(80) DEFAULT NULL,
  `descripcion` text,
  `fechaEntrada` date DEFAULT NULL,
  PRIMARY KEY (`pregunta_id`),
  KEY `fk_pregunta_perfil` (`perfil_id`),
  KEY `fk_pregunta_curso` (`curso_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `pregunta`
--

INSERT INTO `pregunta` (`pregunta_id`, `perfil_id`, `curso_id`, `asunto`, `descripcion`, `fechaEntrada`) VALUES
(1, 23, 36, 'asunto ', 'una pregunta para el curso', '2014-09-14'),
(2, 23, 36, 'acerca de PHP', 'que es una funcion anonima?', '2014-09-14'),
(3, 23, 36, 'asunto ', 'esto es una descripcion de una pregunta del curso', '2014-09-14'),
(4, 23, 36, 'otro asunto', 'otra pregunta', '2014-09-14'),
(5, 23, 36, 'otra pregunta', 'otra descripcion de una pregunta', '2014-09-14'),
(6, 23, 2, 'Una nueva pregunta', 'Sirve este foro?', '2014-09-15'),
(7, 23, 2, 'preguntando', 'descripcion de una pregunta', '2014-09-15'),
(8, 23, 2, 'otra pregunta', 'descripcion', '2014-09-15'),
(9, 26, 10, 'hay cursos?', 'No veo alguna leccion??', '2014-09-15'),
(10, 27, 10, 'Que significa html?', 'Hay alguien que me podria decir que significa HTML', '2014-09-15'),
(11, 23, 2, 'nueva pregunta', 'probando junto a manuel', '2014-09-17'),
(12, 23, 36, 'otra preguna mas', 'esto es una pregunta mas', '2014-09-19');

-- --------------------------------------------------------

--
-- Structure de la table `respuesta`
--

CREATE TABLE IF NOT EXISTS `respuesta` (
  `respuesta_id` int(11) NOT NULL AUTO_INCREMENT,
  `pregunta_id` int(11) NOT NULL,
  `perfil_id` int(11) NOT NULL,
  `descripcion` text,
  `fechaRespuesta` date DEFAULT NULL,
  PRIMARY KEY (`respuesta_id`),
  KEY `fk_respuesta_pregunta` (`pregunta_id`),
  KEY `fk_respuesta_perfil` (`perfil_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `respuesta`
--

INSERT INTO `respuesta` (`respuesta_id`, `pregunta_id`, `perfil_id`, `descripcion`, `fechaRespuesta`) VALUES
(1, 1, 23, 'una respuesta a esta pregunta', '2014-09-15'),
(2, 7, 23, 'puedo responder?', '2014-09-15'),
(3, 8, 23, 'ahora si funciona?', '2014-09-15'),
(4, 9, 26, 'esto es una respuesta a esta pregunta', '2014-09-15'),
(5, 9, 27, 'Yo quiero hacer una pregunta?', '2014-09-15'),
(6, 10, 27, 'HTML significa &lt;&lt; Hypertext Markup Language &gt;&gt;', '2014-09-15'),
(7, 6, 23, 'esto es una respuesta a esta pregunta', '2014-09-17'),
(8, 1, 20, 'esto es una respuesta', '2014-09-19');

-- --------------------------------------------------------

--
-- Structure de la table `tipoArchivo`
--

CREATE TABLE IF NOT EXISTS `tipoArchivo` (
  `tipo_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`tipo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `tipoArchivo`
--

INSERT INTO `tipoArchivo` (`tipo_id`, `descripcion`) VALUES
(1, 'factura'),
(2, 'Imagen curso'),
(3, 'Archivo de una leccion'),
(4, 'avatar');

-- --------------------------------------------------------

--
-- Structure de la table `tipoCurso`
--

CREATE TABLE IF NOT EXISTS `tipoCurso` (
  `tipoCurso` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(100) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`tipoCurso`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `tipoCurso`
--

INSERT INTO `tipoCurso` (`tipoCurso`, `tipo`, `descripcion`) VALUES
(1, 'HTML & CSS', 'Curso front-end code something'),
(2, 'PHP & MYSQL ', 'Aprender a crear sitios web dinamicos');

-- --------------------------------------------------------

--
-- Structure de la table `unidad`
--

CREATE TABLE IF NOT EXISTS `unidad` (
  `unidad_id` int(11) NOT NULL AUTO_INCREMENT,
  `curso_id` int(11) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`unidad_id`),
  KEY `fk_unidad_curso` (`curso_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Contenu de la table `unidad`
--

INSERT INTO `unidad` (`unidad_id`, `curso_id`, `nombre`, `descripcion`) VALUES
(1, 1, 'HTML Basics', NULL),
(2, 1, 'Build your own webpage', NULL),
(5, 2, 'PHP home', 'basico de php'),
(6, 2, 'PHP Intro', 'Introduccino a php'),
(38, 36, 'unidad 1', 'Es la primer unidad del curso'),
(39, 36, 'unidad 2', 'Es la segunda unidad del curso'),
(40, 36, 'unidad 3', 'Es la segunda unidad del curso');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `level_id` int(11) DEFAULT NULL,
  `passwd` varchar(256) DEFAULT NULL,
  `salt` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_usr_level` (`level_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`user_id`, `level_id`, `passwd`, `salt`) VALUES
(15, 4, '668d2d95fb172d4d63f3cd93ddc5bb9259ff77e1a8a79e867100a69f27a1f26b', '540b66f1d9d69'),
(16, 4, '1ca4a2eecadf33657ee29e855c02c676099f1d245256707017003315744ca719', '540b7d4594edf'),
(17, 2, '601fbe7860d95bc5c584a8f50fc2797b4f5eeb860e7f713d8ebe102b7d247460', '540b96df9c530'),
(18, 3, '4f5b0e83f957ab77182c2a2d4feb59e277884158c1e4abd20fbf077c756166e5', '540bff4bdec33'),
(19, 3, 'd2e4184a8dc68fa3cbb69d9242ece8ca93165eafc8a048c0005f0a78d1cdb16b', '540bff6cddbce'),
(20, 3, 'cf50ce8e6110f0c02bd806336bb565e34141d9371656db081e49be9443747c67', '540bff78b2c54'),
(21, 2, 'c7d68e57d9e0ae043b194e08153d0d5c61fcba5cb9934cbf31eb25d43466bce1', '540c743353add'),
(22, 4, '3a4ea860eab4c7c39cfeeed2efe5f1cb5ec09b2d562db39b1ba8ca12e8bddc83', '540c7545a30d6'),
(23, 4, 'aa0c56e3a79c5d14b84909069103b5bc8d119b194745560e3f487744b1f5a98c', '540c75a036c7a'),
(24, 3, '34497a325fd1caed7714aebecb6cb4e2d7e42761b9fe25be196912040f36c1a2', '540c768f2e779'),
(25, 4, '8cd3b600e5a4f1fea61984a43614883ef6564bd9644cb3370b3dcdc81687a63e', '540c78468759c'),
(26, 4, 'e44c51a46977033692d2fb3f4cd6ab98c67eba1f94d49aa1bd53b57cd038f508', '5416fd67af4f8'),
(27, 4, '64c1df113ba841656cbeb8d262818e414cc259a626f8aadaaebaa4d21b11b19f', '541705f692823');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `archivo`
--
ALTER TABLE `archivo`
  ADD CONSTRAINT `archivo_ibfk_1` FOREIGN KEY (`tipo_id`) REFERENCES `tipoArchivo` (`tipo_id`);

--
-- Contraintes pour la table `archivoPerfil`
--
ALTER TABLE `archivoPerfil`
  ADD CONSTRAINT `archivoPerfil_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`perfil_id`),
  ADD CONSTRAINT `archivoPerfil_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`curso_id`),
  ADD CONSTRAINT `archivoPerfil_ibfk_3` FOREIGN KEY (`archivo_id`) REFERENCES `archivo` (`archivo_id`);

--
-- Contraintes pour la table `archivo_leccion`
--
ALTER TABLE `archivo_leccion`
  ADD CONSTRAINT `archivo_leccion_ibfk_1` FOREIGN KEY (`archivo_id`) REFERENCES `archivo` (`archivo_id`),
  ADD CONSTRAINT `archivo_leccion_ibfk_2` FOREIGN KEY (`leccion_id`) REFERENCES `leccion` (`leccion_id`);

--
-- Contraintes pour la table `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`tipoCurso`) REFERENCES `tipoCurso` (`tipoCurso`),
  ADD CONSTRAINT `curso_ibfk_2` FOREIGN KEY (`imagenCurso`) REFERENCES `archivo` (`archivo_id`);

--
-- Contraintes pour la table `curso_maestro`
--
ALTER TABLE `curso_maestro`
  ADD CONSTRAINT `curso_maestro_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`curso_id`),
  ADD CONSTRAINT `curso_maestro_ibfk_2` FOREIGN KEY (`maestro_id`) REFERENCES `maestro` (`maestro_id`);

--
-- Contraintes pour la table `curso_perfil`
--
ALTER TABLE `curso_perfil`
  ADD CONSTRAINT `curso_perfil_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`perfil_id`),
  ADD CONSTRAINT `curso_perfil_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`curso_id`),
  ADD CONSTRAINT `curso_perfil_ibfk_3` FOREIGN KEY (`beca_id`) REFERENCES `beca` (`beca_id`);

--
-- Contraintes pour la table `leccion`
--
ALTER TABLE `leccion`
  ADD CONSTRAINT `leccion_ibfk_1` FOREIGN KEY (`unidad_id`) REFERENCES `unidad` (`unidad_id`);

--
-- Contraintes pour la table `listaCurso`
--
ALTER TABLE `listaCurso`
  ADD CONSTRAINT `listaCurso_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`curso_id`),
  ADD CONSTRAINT `listaCurso_ibfk_2` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`perfil_id`);

--
-- Contraintes pour la table `maestro`
--
ALTER TABLE `maestro`
  ADD CONSTRAINT `maestro_ibfk_1` FOREIGN KEY (`maestro_id`) REFERENCES `perfil` (`perfil_id`);

--
-- Contraintes pour la table `perfil`
--
ALTER TABLE `perfil`
  ADD CONSTRAINT `perfil_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `perfil_ibfk_2` FOREIGN KEY (`fotoPerfil`) REFERENCES `archivo` (`archivo_id`);

--
-- Contraintes pour la table `pregunta`
--
ALTER TABLE `pregunta`
  ADD CONSTRAINT `pregunta_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`perfil_id`),
  ADD CONSTRAINT `pregunta_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`curso_id`);

--
-- Contraintes pour la table `respuesta`
--
ALTER TABLE `respuesta`
  ADD CONSTRAINT `respuesta_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `pregunta` (`pregunta_id`),
  ADD CONSTRAINT `respuesta_ibfk_2` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`perfil_id`);

--
-- Contraintes pour la table `unidad`
--
ALTER TABLE `unidad`
  ADD CONSTRAINT `unidad_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`curso_id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `level` (`level_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
