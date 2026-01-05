-- Supprimer les anciens candidats et reinitialiser
DELETE FROM vote;
UPDATE electeur SET has_voted = 0;
DELETE FROM candidat;

-- Inserer les nouveaux candidats avec leurs images
INSERT INTO candidat (email, mot_de_passe, prenom, nom, nationalite, palmares, photo_profil, compte_verifie, compte_actif, id_scrutin) VALUES
('alexandre.pantoja@mma.com', '$2y$12$abcd1234567890abcdefgh', 'Alexandre', 'Pantoja', 'Bresilienne', '{"victoires":28,"defaites":5,"egalites":0,"no_contest":0}', 'images/candidats/AlexandrePantoja.png', 1, 1, 3),
('brandon.moreno@mma.com', '$2y$12$abcd1234567890abcdefgh', 'Brandon', 'Moreno', 'Mexicaine', '{"victoires":21,"defaites":7,"egalites":2,"no_contest":0}', 'images/candidats/BrandonMoreno.png', 1, 1, 3),
('brandon.royval@mma.com', '$2y$12$abcd1234567890abcdefgh', 'Brandon', 'Royval', 'Americaine', '{"victoires":16,"defaites":7,"egalites":0,"no_contest":0}', 'images/candidats/BrandonRoyval.png', 1, 1, 3),
('joshua.van@mma.com', '$2y$12$abcd1234567890abcdefgh', 'Joshua', 'Van', 'Americaine', '{"victoires":10,"defaites":1,"egalites":0,"no_contest":0}', 'images/candidats/JoshuaVan.png', 1, 1, 3),
('manel.kape@mma.com', '$2y$12$abcd1234567890abcdefgh', 'Manel', 'Kape', 'Angolaise', '{"victoires":19,"defaites":6,"egalites":0,"no_contest":0}', 'images/candidats/ManelKape.png', 1, 1, 3),
('tatsuro.taira@mma.com', '$2y$12$abcd1234567890abcdefgh', 'Tatsuro', 'Taira', 'Japonaise', '{"victoires":16,"defaites":0,"egalites":0,"no_contest":0}', 'images/candidats/TatsuroTaira.png', 1, 1, 3);
