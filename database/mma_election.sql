-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 05 jan. 2026 à 15:03
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mma_election`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `ID_admin` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`ID_admin`, `email`, `mot_de_passe`, `prenom`, `nom`) VALUES
(1, 'admin@exemple.com', '$2y$12$vB0Awg2G7/1de8kRZ8qeOeD9XCGGQ7hNeaPLufKLIeYjb6k3HDtFu', 'Admin', 'Système');

-- --------------------------------------------------------

--
-- Structure de la table `candidat`
--

CREATE TABLE `candidat` (
  `ID_candidat` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `mdp_provisoire` varchar(255) DEFAULT NULL,
  `prenom` varchar(50) NOT NULL,
  `surnom` varchar(50) DEFAULT NULL,
  `slogan` varchar(255) DEFAULT NULL,
  `nom` varchar(50) NOT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `palmares` text DEFAULT NULL,
  `photo_profil` varchar(255) DEFAULT NULL,
  `compte_verifie` tinyint(1) DEFAULT 0,
  `compte_actif` tinyint(1) DEFAULT 1,
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_verification` datetime DEFAULT NULL,
  `id_scrutin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidat`
--

INSERT INTO `candidat` (`ID_candidat`, `email`, `mot_de_passe`, `mdp_provisoire`, `prenom`, `surnom`, `slogan`, `nom`, `nationalite`, `palmares`, `photo_profil`, `compte_verifie`, `compte_actif`, `date_creation`, `date_verification`, `id_scrutin`) VALUES
(7, 'alexandre.pantoja@mma.com', '$2y$12$abcd1234567890abcdefgh', NULL, 'Alexandre', NULL, NULL, 'Pantoja', 'Bresilienne', '{\"victoires\":28,\"defaites\":5,\"egalites\":0,\"no_contest\":0}', 'images/candidats/AlexandrePantoja.png', 1, 1, '2026-01-05 15:01:13', NULL, 3),
(8, 'brandon.moreno@mma.com', '$2y$12$abcd1234567890abcdefgh', NULL, 'Brandon', NULL, NULL, 'Moreno', 'Mexicaine', '{\"victoires\":21,\"defaites\":7,\"egalites\":2,\"no_contest\":0}', 'images/candidats/BrandonMoreno.png', 1, 1, '2026-01-05 15:01:13', NULL, 3),
(9, 'brandon.royval@mma.com', '$2y$12$abcd1234567890abcdefgh', NULL, 'Brandon', NULL, NULL, 'Royval', 'Americaine', '{\"victoires\":16,\"defaites\":7,\"egalites\":0,\"no_contest\":0}', 'images/candidats/BrandonRoyval.png', 1, 1, '2026-01-05 15:01:13', NULL, 3),
(10, 'joshua.van@mma.com', '$2y$12$abcd1234567890abcdefgh', NULL, 'Joshua', NULL, NULL, 'Van', 'Americaine', '{\"victoires\":10,\"defaites\":1,\"egalites\":0,\"no_contest\":0}', 'images/candidats/JoshuaVan.png', 1, 1, '2026-01-05 15:01:13', NULL, 3),
(11, 'manel.kape@mma.com', '$2y$12$abcd1234567890abcdefgh', NULL, 'Manel', NULL, NULL, 'Kape', 'Angolaise', '{\"victoires\":19,\"defaites\":6,\"egalites\":0,\"no_contest\":0}', 'images/candidats/ManelKape.png', 1, 1, '2026-01-05 15:01:13', NULL, 3),
(12, 'tatsuro.taira@mma.com', '$2y$12$abcd1234567890abcdefgh', NULL, 'Tatsuro', NULL, NULL, 'Taira', 'Japonaise', '{\"victoires\":16,\"defaites\":0,\"egalites\":0,\"no_contest\":0}', 'images/candidats/TatsuroTaira.png', 1, 1, '2026-01-05 15:01:13', NULL, 3);

-- --------------------------------------------------------

--
-- Structure de la table `code_professionnel`
--

CREATE TABLE `code_professionnel` (
  `ID_code` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `type_professionnel` enum('journaliste','coach') NOT NULL,
  `date_generation` datetime DEFAULT current_timestamp(),
  `date_utilisation` datetime DEFAULT NULL,
  `utilise` tinyint(1) DEFAULT 0,
  `id_college` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `code_professionnel`
--

INSERT INTO `code_professionnel` (`ID_code`, `code`, `email`, `prenom`, `nom`, `type_professionnel`, `date_generation`, `date_utilisation`, `utilise`, `id_college`) VALUES
(1, 'JOURN001-2025', 'journaliste1@exemple.com', 'Jacques', 'Moreau', 'journaliste', '2025-12-29 13:02:32', '2025-12-29 13:02:32', 1, 2),
(2, 'JOURN002-2025', 'journaliste2@exemple.com', 'Catherine', 'Leroy', 'journaliste', '2025-12-29 13:02:32', '2025-12-29 13:02:32', 1, 2),
(3, 'JOURN003-2025', 'journaliste3@exemple.com', 'Philippe', 'Girard', 'journaliste', '2025-12-29 13:02:32', '2025-12-29 13:02:32', 1, 2),
(4, 'JOURN004-2025', 'journaliste4@exemple.com', 'Isabelle', 'Rousseau', 'journaliste', '2025-12-29 13:02:32', '2025-12-29 13:02:32', 1, 2),
(5, 'JOURN005-2025', 'journaliste5@exemple.com', 'Thomas', 'Vincent', 'journaliste', '2025-12-29 13:02:32', '2025-12-29 13:02:32', 1, 2),
(6, 'COACH001-2025', 'coach1@exemple.com', 'Marc', 'Fontaine', 'coach', '2025-12-29 13:02:32', '2025-12-29 13:02:32', 1, 3),
(7, 'COACH002-2025', 'coach2@exemple.com', 'Nathalie', 'Chevalier', 'coach', '2025-12-29 13:02:32', '2025-12-29 13:02:32', 1, 3),
(8, 'COACH003-2025', 'coach3@exemple.com', 'Olivier', 'Gauthier', 'coach', '2025-12-29 13:02:32', '2025-12-29 13:02:32', 1, 3),
(9, 'COACH004-2025', 'coach4@exemple.com', 'Valérie', 'Lambert', 'coach', '2025-12-29 13:02:32', '2025-12-29 13:02:32', 1, 3),
(10, 'COACH005-2025', 'coach5@exemple.com', 'Sébastien', 'Bonnet', 'coach', '2025-12-29 13:02:32', '2025-12-29 13:02:32', 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `college`
--

CREATE TABLE `college` (
  `ID_college` int(11) NOT NULL,
  `type` enum('public','journaliste','coach') NOT NULL,
  `poids` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `college`
--

INSERT INTO `college` (`ID_college`, `type`, `poids`) VALUES
(1, 'public', 0.20),
(2, 'journaliste', 0.40),
(3, 'coach', 0.40);

-- --------------------------------------------------------

--
-- Structure de la table `electeur`
--

CREATE TABLE `electeur` (
  `ID_electeur` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `sexe` enum('Homme','Femme') DEFAULT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `adresse_IP` varchar(45) DEFAULT NULL,
  `code_fourni` varchar(50) DEFAULT NULL,
  `has_voted` tinyint(1) DEFAULT 0,
  `id_college` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `electeur`
--

INSERT INTO `electeur` (`ID_electeur`, `email`, `mot_de_passe`, `prenom`, `nom`, `age`, `sexe`, `nationalite`, `adresse_IP`, `code_fourni`, `has_voted`, `id_college`) VALUES
(1, 'electeur1@exemple.com', '$2y$12$pDxPB429Rsc0KAVy71zgguKwslDAo8K709JVU7f7ohm3/J9UWw00W', 'Jean', 'Dupont', 28, 'Homme', 'Française', NULL, NULL, 0, 1),
(2, 'electeur2@exemple.com', '$2y$12$SO6o8Lb23Vf.vQ8/8z/afOZPC1qHQ6f2e6FtMyU6BQVFm61zh8CGW', 'Marie', 'Martin', 32, 'Femme', 'Française', NULL, NULL, 0, 1),
(3, 'electeur3@exemple.com', '$2y$12$30v3JsQ7y.osjnN8pnTu1e51qVeFSr2TbVO.nVG78pgedCgIwLPg6', 'Pierre', 'Bernard', 45, 'Homme', 'Française', NULL, NULL, 0, 1),
(4, 'electeur4@exemple.com', '$2y$12$tN1p9LzpamK8cWWawHyjyun/mIh06MsG.4WYKfCWrCAnVlMNeqi02', 'Sophie', 'Dubois', 26, 'Femme', 'Française', NULL, NULL, 0, 1),
(5, 'electeur5@exemple.com', '$2y$12$w41PyhJNWqjgRaV8GIeDB.FTBGOtZPYlhoZCAkT8t/z/y/ZjpNGyC', 'Lucas', 'Laurent', 35, 'Homme', 'Française', NULL, NULL, 0, 1),
(6, 'journaliste1@exemple.com', '$2y$12$SsOjyrzErxzovpbR2lcbAOBS7dA57L4N6OAAmMAkk0b4yrGchk0sO', 'Jacques', 'Moreau', 42, 'Homme', 'Française', NULL, 'JOURN001-2025', 0, 2),
(7, 'journaliste2@exemple.com', '$2y$12$5KSMDuwgnb64R9BAkVZit.kd58rJQNE54T6lntXJR.1lhxBTYRHla', 'Catherine', 'Leroy', 38, 'Femme', 'Française', NULL, 'JOURN002-2025', 0, 2),
(8, 'journaliste3@exemple.com', '$2y$12$aCqNByCS7zaL/8OoMY06d.AA8m3CONBVm63MiTdm.yjAfauE4I7tW', 'Philippe', 'Girard', 45, 'Homme', 'Française', NULL, 'JOURN003-2025', 0, 2),
(9, 'journaliste4@exemple.com', '$2y$12$c9gCMwkDSW2UpuHio3cbG.Vlt0Nkr226ZdaJRmLuYEidRzeZKIJ4O', 'Isabelle', 'Rousseau', 40, 'Femme', 'Française', NULL, 'JOURN004-2025', 0, 2),
(10, 'journaliste5@exemple.com', '$2y$12$NhXyGJqTX8Y1kQcoAzNjx.iZsawpTet2ltRybKAgMsT/WNY4w3uw6', 'Thomas', 'Vincent', 36, 'Homme', 'Française', NULL, 'JOURN005-2025', 0, 2),
(11, 'coach1@exemple.com', '$2y$12$AkqS3Gyg9/jrEZooKhQjkucOrz2Xm6yIA90rEhc0Z0CeMQNObUKdG', 'Marc', 'Fontaine', 50, 'Homme', 'Française', NULL, 'COACH001-2025', 0, 3),
(12, 'coach2@exemple.com', '$2y$12$FYEQRk6wiJ32r3kEj.XrceXC9By0JLPYSuGQczGvC.9XmXVtrkpou', 'Nathalie', 'Chevalier', 44, 'Femme', 'Française', NULL, 'COACH002-2025', 0, 3),
(13, 'coach3@exemple.com', '$2y$12$9Y/9hhOefKvNz/cmpcQSsusvDxTneeq49uzQqImEneVaHc9WSsvH.', 'Olivier', 'Gauthier', 48, 'Homme', 'Française', NULL, 'COACH003-2025', 0, 3),
(14, 'coach4@exemple.com', '$2y$12$iQquGaqiM/u.IxbARqKhrOW2yDDLSIm1zqQKzEMJORjbk2fKOlMWG', 'Valérie', 'Lambert', 46, 'Femme', 'Française', NULL, 'COACH004-2025', 0, 3),
(15, 'coach5@exemple.com', '$2y$12$gt51uE0U4xDnHkxT5Tlve./4MnLIo0Dc6q1sugJcok3iV5dY7QSqi', 'Sébastien', 'Bonnet', 52, 'Homme', 'Française', NULL, 'COACH005-2025', 0, 3);

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `ID_media` int(11) NOT NULL,
  `type` enum('image','video') NOT NULL,
  `chemin_fichier` varchar(255) NOT NULL,
  `id_post` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `ID_post` int(11) NOT NULL,
  `texte` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reaction`
--

CREATE TABLE `reaction` (
  `ID_reaction` int(11) NOT NULL,
  `type` enum('like','commentaire') NOT NULL,
  `contenu_commentaire` text DEFAULT NULL,
  `date` datetime NOT NULL,
  `id_electeur` int(11) DEFAULT NULL,
  `id_post` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `scrutin`
--

CREATE TABLE `scrutin` (
  `ID_scrutin` int(11) NOT NULL,
  `annee` int(11) NOT NULL,
  `date_ouverture` date NOT NULL,
  `date_fermeture` date NOT NULL,
  `phase` enum('preparation','vote','resultat') NOT NULL,
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `scrutin`
--

INSERT INTO `scrutin` (`ID_scrutin`, `annee`, `date_ouverture`, `date_fermeture`, `phase`, `id_admin`) VALUES
(3, 2026, '2026-01-05', '2026-01-12', 'vote', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

CREATE TABLE `vote` (
  `ID_vote` int(11) NOT NULL,
  `id_electeur` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `date_vote` datetime DEFAULT current_timestamp(),
  `age` int(11) DEFAULT NULL,
  `sexe` enum('Homme','Femme') DEFAULT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `id_college` int(11) DEFAULT NULL,
  `id_candidat` int(11) DEFAULT NULL,
  `id_scrutin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`ID_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `candidat`
--
ALTER TABLE `candidat`
  ADD PRIMARY KEY (`ID_candidat`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_candidat_scrutin` (`id_scrutin`);

--
-- Index pour la table `code_professionnel`
--
ALTER TABLE `code_professionnel`
  ADD PRIMARY KEY (`ID_code`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `id_college` (`id_college`);

--
-- Index pour la table `college`
--
ALTER TABLE `college`
  ADD PRIMARY KEY (`ID_college`);

--
-- Index pour la table `electeur`
--
ALTER TABLE `electeur`
  ADD PRIMARY KEY (`ID_electeur`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_electeur_college` (`id_college`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`ID_media`),
  ADD KEY `fk_media_post` (`id_post`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`ID_post`);

--
-- Index pour la table `reaction`
--
ALTER TABLE `reaction`
  ADD PRIMARY KEY (`ID_reaction`),
  ADD KEY `fk_reaction_electeur` (`id_electeur`),
  ADD KEY `fk_reaction_post` (`id_post`);

--
-- Index pour la table `scrutin`
--
ALTER TABLE `scrutin`
  ADD PRIMARY KEY (`ID_scrutin`),
  ADD KEY `fk_scrutin_admin` (`id_admin`);

--
-- Index pour la table `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`ID_vote`),
  ADD KEY `fk_vote_college` (`id_college`),
  ADD KEY `fk_vote_candidat` (`id_candidat`),
  ADD KEY `fk_vote_scrutin` (`id_scrutin`),
  ADD KEY `fk_vote_electeur` (`id_electeur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `ID_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `candidat`
--
ALTER TABLE `candidat`
  MODIFY `ID_candidat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `code_professionnel`
--
ALTER TABLE `code_professionnel`
  MODIFY `ID_code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `college`
--
ALTER TABLE `college`
  MODIFY `ID_college` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `electeur`
--
ALTER TABLE `electeur`
  MODIFY `ID_electeur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `ID_media` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `ID_post` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reaction`
--
ALTER TABLE `reaction`
  MODIFY `ID_reaction` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `scrutin`
--
ALTER TABLE `scrutin`
  MODIFY `ID_scrutin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `vote`
--
ALTER TABLE `vote`
  MODIFY `ID_vote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `candidat`
--
ALTER TABLE `candidat`
  ADD CONSTRAINT `fk_candidat_scrutin` FOREIGN KEY (`id_scrutin`) REFERENCES `scrutin` (`ID_scrutin`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `code_professionnel`
--
ALTER TABLE `code_professionnel`
  ADD CONSTRAINT `code_professionnel_ibfk_1` FOREIGN KEY (`id_college`) REFERENCES `college` (`ID_college`);

--
-- Contraintes pour la table `electeur`
--
ALTER TABLE `electeur`
  ADD CONSTRAINT `fk_electeur_college` FOREIGN KEY (`id_college`) REFERENCES `college` (`ID_college`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `fk_media_post` FOREIGN KEY (`id_post`) REFERENCES `post` (`ID_post`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `reaction`
--
ALTER TABLE `reaction`
  ADD CONSTRAINT `fk_reaction_electeur` FOREIGN KEY (`id_electeur`) REFERENCES `electeur` (`ID_electeur`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reaction_post` FOREIGN KEY (`id_post`) REFERENCES `post` (`ID_post`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `scrutin`
--
ALTER TABLE `scrutin`
  ADD CONSTRAINT `fk_scrutin_admin` FOREIGN KEY (`id_admin`) REFERENCES `administrateur` (`ID_admin`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `fk_vote_candidat` FOREIGN KEY (`id_candidat`) REFERENCES `candidat` (`ID_candidat`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vote_college` FOREIGN KEY (`id_college`) REFERENCES `college` (`ID_college`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vote_electeur` FOREIGN KEY (`id_electeur`) REFERENCES `electeur` (`ID_electeur`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_vote_scrutin` FOREIGN KEY (`id_scrutin`) REFERENCES `scrutin` (`ID_scrutin`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
