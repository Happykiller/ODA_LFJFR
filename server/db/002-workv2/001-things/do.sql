ALTER TABLE `@prefix@tab_experiences`
CHANGE `titre_experience` `titre_experience` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `commentaire_titre` `commentaire_titre` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `debut` `debut` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `fin` `fin` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `employeur` `employeur` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `fonction` `fonction` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `secteur` `secteur` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `language` `language` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `applicatif` `applicatif` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `develloppement` `develloppement` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `bdd` `bdd` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;