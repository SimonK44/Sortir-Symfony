-- suppression enrgistrement table USER
DELETE FROM user;
-- creation parametrage USER
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `pseudo`, `nom`, `prenom`, `telephone`, `actif`, `site_id`) VALUES ('1', 'heejung.kim2024@campus-eni.fr', '["ROLE_ADMIN"]', '$2y$13$ka/BKOJSR.UK6VEBZ2pWBuTEp9FuVKm506fgBLgoqrpS6.FyeA9LW', 'superheejung', 'KIM', 'HEEJUNG', '0612131415', '1', '3');
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `pseudo`, `nom`, `prenom`, `telephone`, `actif`, `site_id`) VALUES ('2', 'lilian.boumendil2024@campus-eni.fr', '["ROLE_USER"]', '$2y$13$ka/BKOJSR.UK6VEBZ2pWBuTEp9FuVKm506fgBLgoqrpS6.FyeA9LW', 'superlilian', 'BOUMENDIL', 'LILIAN', '0612131415', '1', '3');
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `pseudo`, `nom`, `prenom`, `telephone`, `actif`, `site_id`) VALUES ('3', 'simon.kervadec2024@campus-eni.fr', '["ROLE_USER"]', '$2y$13$ka/BKOJSR.UK6VEBZ2pWBuTEp9FuVKm506fgBLgoqrpS6.FyeA9LW', 'supersimon', 'KERVADEC', 'SIMON', '0612131415', '1', '3');
