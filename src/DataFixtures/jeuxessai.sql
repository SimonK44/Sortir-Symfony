-- suppression enregistrement
-- DELETE FROM lieux;
--  DELETE FROM sorties;
-- creation table lieux
INSERT INTO `lieux` (`id`, `ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES (NULL, '1', 'Cinema', 'rue des peuplier', '120.23', '131.21');
INSERT INTO `lieux` (`id`, `ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES (NULL, '1', 'Karting', 'rue des chenes', '20.23', '1.21');
INSERT INTO `lieux` (`id`, `ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES (NULL, '1', 'Karting', 'rue des chenes', '20.23', '1.21');

-- Creation Sortie;
INSERT INTO `sorties` (`id`, `etat_id`, `lieux_id`, `user_id`, `site_id`, `nom`, `date_debut`, `duree`, `date_cloture`, `nb_inscriptions_max`, `description_infos`, `etat_sortie`, `url_photo`, `is_published`) VALUES (NULL, '1', '1', '1', '1', 'cinema', '2024-08-28 14:17:35.000000', '2', '2024-08-28 14:17:35.000000', '10', 'info', '1', NULL, '1');
INSERT INTO `sorties` (`id`, `etat_id`, `lieux_id`, `user_id`, `site_id`, `nom`, `date_debut`, `duree`, `date_cloture`, `nb_inscriptions_max`, `description_infos`, `etat_sortie`, `url_photo`, `is_published`) VALUES (NULL, '1', '1', '1', '1', 'theatre', '2024-08-28 14:17:35.000000', '2', '2024-08-28 14:17:35.000000', '10', 'info', '1', NULL, '1');



