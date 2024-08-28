-- suppression enregistrement
-- DELETE FROM lieux;
--  DELETE FROM sorties;
-- creation table lieux
INSERT INTO `lieux` (`id`, `ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES (NULL, '1', 'Cinema', 'rue des peuplier', '120.23', '131.21');
INSERT INTO `lieux` (`id`, `ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES (NULL, '1', 'Karting', 'rue des chenes', '20.23', '1.21');
INSERT INTO `lieux` (`id`, `ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES (NULL, '1', 'Karting', 'rue des chenes', '20.23', '1.21');

-- Creation Sortie;
INSERT INTO `sorties` (`id`, `organisateur_id`, `nom`, `date_debut`, `duree`, `date_cloture`, `nb_inscriptions_max`, `description_infos`, `etat_sortie`, `url_photo`, `etat_id`, `lieux_id`) VALUES (NULL, '1', 'cinema', '2024-08-28 08:14:31.000000', '1', '2024-08-28 08:14:31.000000', '10', 'sortie cinema', NULL, NULL, '12', '1');
INSERT INTO `sorties` (`id`, `organisateur_id`, `nom`, `date_debut`, `duree`, `date_cloture`, `nb_inscriptions_max`, `description_infos`, `etat_sortie`, `url_photo`, `etat_id`, `lieux_id`) VALUES (NULL, '1', 'Karting', '2024-09-28 08:14:31.000000', '1', '2024-09-28 08:14:31.000000', '10', 'sortie karting', NULL, NULL, '12', '1');






