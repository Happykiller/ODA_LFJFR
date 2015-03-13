--
-- Contenu de la table `tab_menu`
--
INSERT INTO `tab_menu` (`id`, `Description`, `Description_courte`, `id_categorie`, `Lien`) VALUES
(21, 'Admin projet', 'Admin projet', 1, 'page_admin_projet.html')
;

-- --------------------------------------------------------
--
-- Contenu de la table `tab_menu_rangs_droit`
--
UPDATE `tab_menu_rangs_droit`
SET `id_menu` = CONCAT(`id_menu`,"21;")
WHERE `id_rang` in (1)
; 