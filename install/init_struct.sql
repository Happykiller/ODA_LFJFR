-- --------------------------------------------------------
--
-- Structure de la table `tab_experiences`
--

CREATE TABLE IF NOT EXISTS `tab_experiences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordre` int(11) NOT NULL,
  `titre_experience` varchar(250) NOT NULL,
  `commentaire_titre` varchar(100) NOT NULL,
  `debut` varchar(100) NOT NULL,
  `fin` varchar(100) NOT NULL,
  `employeur` varchar(100) NOT NULL,
  `fonction` varchar(100) NOT NULL,
  `secteur` varchar(100) NOT NULL,
  `fonctionnel` text NOT NULL,
  `realisation` text NOT NULL,
  `language` varchar(250) NOT NULL,
  `applicatif` varchar(250) NOT NULL,
  `develloppement` varchar(250) NOT NULL,
  `bdd` varchar(250) NOT NULL,
  `meta` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
);


--
-- Structure de la table `rec_budget`
--

CREATE TABLE IF NOT EXISTS `rec_budget` (
  `Date` varchar(250) NOT NULL,
  `Compte` varchar(250) NOT NULL,
  `Tiers` varchar(250) NOT NULL,
  `Statut` varchar(250) NOT NULL,
  `Categorie` varchar(250) NOT NULL,
  `Type` varchar(250) NOT NULL,
  `Montant` varchar(250) NOT NULL,
  `Numero` varchar(250) NOT NULL,
  `Note` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Structure de la table `vue_budget`
--
DROP TABLE IF EXISTS `vue_budget`;

CREATE VIEW `vue_budget` 
AS 
SELECT
STR_TO_DATE(a.`Date`,'%d/%m/%Y') as 'date'
, a.`Compte` as 'compte'
, a.`Tiers` as 'tiers'
, a.`Categorie` as 'categorie'
, a.`Type` as 'type'
, CONVERT(REPLACE(REPLACE(REPLACE(a.`Montant`,'€',''),',',''),' ',''),SIGNED INTEGER)/100 as 'mantant'
, a.`Note` as 'note'
FROM `rec_budget` as a
WHERE 1=1
;