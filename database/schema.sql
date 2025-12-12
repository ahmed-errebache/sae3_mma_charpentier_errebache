-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 12 déc. 2025 à 14:58
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
(1, 'ahmed@exemple.com', '$2y$12$LJQ9CYnZtP0tnGQZVzdSf.MorzpHWTi1wK6IoW51aK6QQMu1qluS2', 'ahmed', 'errebache');

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
(12, 'ahmed.errebache1@gmail.com', '$2y$10$dsEtO91HW08/zPupDuQ1qORJLEZuV/fBFSZmwbn7Mq4IFafjo0MVm', '0', 'Ahmed', 'cc', NULL, 'Errebache', 'Maroc', '{\"victoires\":10,\"defaites\":5,\"egalites\":2,\"no_contest\":0}', '/sae3_mma_charpentier_errebache/images/candidats/candidat_69399d159efd14.99079324.png', 1, 1, '2025-12-09 08:43:39', NULL, NULL);

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
(1, 'lucascharpentier55@gmail.com', 'Lulu', 'Lucas', 'Charpentier', 21, 'Homme', 'Française', '127.0.3.2', NULL, 0, 1),
(2, 'ahmedw@gmail.com', '$2y$10$FPjoU4qnPaiH3vT2Ca0VWOBB1WB5XN.WsddRjma.effi496DSdw.G', 'w', 'ahmed', NULL, NULL, 'marocc', NULL, NULL, 0, 1),
(3, 'cc@gmail.com', '$2y$10$wFUbfAsd8Yghe7mfeK0lveZvt37GN764AzDo2ysOFGtQxWo3hVP6.', 'ahmed', 'ahmed', NULL, NULL, NULL, NULL, NULL, 0, 1),
(4, 'ahmed.errebache@gmail.com', '$2y$10$xgefDhmRD2k9LsuSbep4o.w4bYdN86tmMGXVQncU9OsHXBCZGy5aO', 'ahmed', 'ahmed', NULL, NULL, NULL, NULL, NULL, 0, 1);

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

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

CREATE TABLE `vote` (
  `ID_vote` int(11) NOT NULL,
  `date` date NOT NULL,
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
  ADD PRIMARY KEY (`ID_admin`);

--
-- Index pour la table `candidat`
--
ALTER TABLE `candidat`
  ADD PRIMARY KEY (`ID_candidat`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `fk_candidat_scrutin` (`id_scrutin`);

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
  ADD KEY `fk_vote_scrutin` (`id_scrutin`);

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
-- AUTO_INCREMENT pour la table `college`
--
ALTER TABLE `college`
  MODIFY `ID_college` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `electeur`
--
ALTER TABLE `electeur`
  MODIFY `ID_electeur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `ID_scrutin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `vote`
--
ALTER TABLE `vote`
  MODIFY `ID_vote` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `candidat`
--
ALTER TABLE `candidat`
  ADD CONSTRAINT `fk_candidat_scrutin` FOREIGN KEY (`id_scrutin`) REFERENCES `scrutin` (`ID_scrutin`) ON DELETE SET NULL ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_vote_scrutin` FOREIGN KEY (`id_scrutin`) REFERENCES `scrutin` (`ID_scrutin`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
