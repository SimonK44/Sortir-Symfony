- creation parametrage ETATS
INSERT INTO ETATS (libelle) VALUES ('En Création');
INSERT INTO ETATS (libelle) VALUES ('Ouverte');
INSERT INTO ETATS (libelle) VALUES ('Cloturée');
INSERT INTO ETATS (libelle) VALUES ('Activité en cours');
INSERT INTO ETATS (libelle) VALUES ('Activité terminée');
INSERT INTO ETATS (libelle) VALUES ('Activité Historisée');
INSERT INTO ETATS (libelle) VALUES ('Annulée');


INSERT INTO `sites` (`id`, `nom_site`) VALUES (null, 'ENI_NANTES');
INSERT INTO `sites` (`id`, `nom_site`) VALUES (null, 'ENI_NIORT');
INSERT INTO `sites` (`id`, `nom_site`) VALUES (null, 'ENI_RENNES');

-- creation parametrage USER
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `pseudo`, `nom`, `prenom`, `telephone`, `actif`,`is_verified`, `site_id`) VALUES ('1', 'heejung.kim2024@campus-eni.fr', '["ROLE_ADMIN"]', '$2y$13$ka/BKOJSR.UK6VEBZ2pWBuTEp9FuVKm506fgBLgoqrpS6.FyeA9LW', 'superheejung', 'KIM', 'HEEJUNG', '0612131415', '1','1', '3');
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `pseudo`, `nom`, `prenom`, `telephone`, `actif`,`is_verified`, `site_id`) VALUES ('2', 'lilian.boumendil2024@campus-eni.fr', '["ROLE_USER"]', '$2y$13$ka/BKOJSR.UK6VEBZ2pWBuTEp9FuVKm506fgBLgoqrpS6.FyeA9LW', 'superlilian', 'BOUMENDIL', 'LILIAN', '0612131415', '1','1', '3');
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `pseudo`, `nom`, `prenom`, `telephone`, `actif`,`is_verified`, `site_id`) VALUES ('3', 'simon.kervadec2024@campus-eni.fr', '["ROLE_USER"]', '$2y$13$ka/BKOJSR.UK6VEBZ2pWBuTEp9FuVKm506fgBLgoqrpS6.FyeA9LW', 'supersimon', 'KERVADEC', 'SIMON', '0612131415', '1','1', '3');


//INSERT INTO villes (nom_ville,code_postal) VALUES ('Nantes','44000');
//INSERT INTO villes (nom_ville,code_postal) VALUES ('Niort','79000');
//INSERT INTO villes (nom_ville,code_postal) VALUES ('Saint-Herblain','44850');
//INSERT INTO villes (nom_ville,code_postal) VALUES ('Rennes','35000');
//INSERT INTO villes (nom_ville,code_postal) VALUES ('Paris','75000');
INSERT INTO Villes (code_postal,nom_ville)
SELECT Codepos, Commune from `codes postaux`;

INSERT INTO `lieux` (`id`, `ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES (NULL, '1', 'Cinema', 'rue des peuplier', '120.23', '131.21');
INSERT INTO `lieux` (`id`, `ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES (NULL, '1', 'Karting', 'rue des chenes', '20.23', '1.21');
INSERT INTO `lieux` (`id`, `ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES (NULL, '1', 'Theatre', 'rue des erables', '120.23', '2.34');

-- Creation Sortie;
INSERT INTO `sorties` (`id`, `etat_id`, `lieux_id`, `user_id`, `site_id`, `nom`, `date_debut`, `duree`, `date_cloture`, `nb_inscriptions_max`, `description_infos`, `etat_sortie`, `url_photo`, `is_published`) VALUES (NULL, '1', '1', '1', '1', 'cinema', '2024-08-28 14:17:35.000000', '2', '2024-08-28 14:17:35.000000', '10', 'info', '1', NULL, '1');
INSERT INTO `sorties` (`id`, `etat_id`, `lieux_id`, `user_id`, `site_id`, `nom`, `date_debut`, `duree`, `date_cloture`, `nb_inscriptions_max`, `description_infos`, `etat_sortie`, `url_photo`, `is_published`) VALUES (NULL, '1', '1', '1', '1', 'theatre', '2024-08-28 14:17:35.000000', '2', '2024-08-28 14:17:35.000000', '10', 'info', '1', NULL, '1');

INSERT INTO `user_sorties` (`user_id`, `sorties_id`) VALUES ('1', '3');
INSERT INTO `user_sorties` (`user_id`, `sorties_id`) VALUES ('2', '3');
INSERT INTO `user_sorties` (`user_id`, `sorties_id`) VALUES ('3', '3');

