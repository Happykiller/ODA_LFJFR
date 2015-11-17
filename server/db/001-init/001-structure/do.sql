CREATE TABLE IF NOT EXISTS `@prefix@tab_experiences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordre` int(11) NOT NULL,
  `titre_experience` varchar(500) NOT NULL,
  `commentaire_titre` varchar(500) NOT NULL,
  `debut` varchar(500) NOT NULL,
  `fin` varchar(500) NOT NULL,
  `employeur` varchar(500) NOT NULL,
  `fonction` varchar(500) NOT NULL,
  `secteur` varchar(500) NOT NULL,
  `fonctionnel` text NOT NULL,
  `realisation` text NOT NULL,
  `language` varchar(500) NOT NULL,
  `applicatif` varchar(500) NOT NULL,
  `develloppement` varchar(500) NOT NULL,
  `bdd` varchar(500) NOT NULL,
  `meta` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
);