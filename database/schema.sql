-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 16 nov. 2025 à 17:25
-- Version du serveur : 8.0.39
-- Version de PHP : 8.0.26

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
-- Table `administrateur`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `administrateur`;
CREATE TABLE IF NOT EXISTS `administrateur` (
  `ID_admin` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `mot_de_passe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `prenom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Table `candidat`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `candidat`;
CREATE TABLE IF NOT EXISTS `candidat` (
  `ID_candidat` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `prenom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `surnom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nom` varchar(50) NOT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `palmares_annee` varchar(50) DEFAULT NULL,
  `photo_profil` varchar(255) DEFAULT NULL,
  `id_scrutin` int DEFAULT NULL,
  PRIMARY KEY (`ID_candidat`),
  KEY `fk_candidat_scrutin` (`id_scrutin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Table `college`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `college`;
CREATE TABLE IF NOT EXISTS `college` (
  `ID_college` int NOT NULL AUTO_INCREMENT,
  `type` enum('public','journaliste','coach') NOT NULL,
  `poids` decimal(4,2) NOT NULL,
  PRIMARY KEY (`ID_college`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Données de la table `college`
--

INSERT INTO `college` (`ID_college`, `type`, `poids`) VALUES
(1, 'public', 0.20),
(2, 'journaliste', 0.40),
(3, 'coach', 0.40);

-- --------------------------------------------------------
-- Table `electeur`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `electeur`;
CREATE TABLE IF NOT EXISTS `electeur` (
  `ID_electeur` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `age` int DEFAULT NULL,
  `sexe` enum('Homme','Femme') DEFAULT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `adresse_IP` varchar(45) DEFAULT NULL,
  `code_fourni` varchar(50) DEFAULT NULL,
  `has_voted` tinyint(1) DEFAULT 0,
  `id_college` int DEFAULT NULL,
  PRIMARY KEY (`ID_electeur`),
  KEY `fk_electeur_college` (`id_college`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Données de la table `electeur`
--

INSERT INTO `electeur`
(`ID_electeur`, `email`, `mot_de_passe`, `prenom`, `nom`, `age`, `sexe`,
 `nationalite`, `adresse_IP`, `code_fourni`, `has_voted`, `id_college`)
VALUES
(1, 'lucascharpentier55@gmail.com', 'Lulu', 'Lucas', 'Charpentier',
 21, 'Homme', 'Française', '127.0.3.2', NULL, 0, 1);

-- --------------------------------------------------------
-- Table `media`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `ID_media` int NOT NULL AUTO_INCREMENT,
  `type` enum('image','video') NOT NULL,
  `chemin_fichier` varchar(255) NOT NULL,
  `id_post` int DEFAULT NULL,
  PRIMARY KEY (`ID_media`),
  KEY `fk_media_post` (`id_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Table `post`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `ID_post` int NOT NULL AUTO_INCREMENT,
  `texte` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`ID_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Table `reaction`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `reaction`;
CREATE TABLE IF NOT EXISTS `reaction` (
  `ID_reaction` int NOT NULL AUTO_INCREMENT,
  `type` enum('like','commentaire') NOT NULL,
  `contenu_commentaire` text,
  `date` datetime NOT NULL,
  `id_electeur` int DEFAULT NULL,
  `id_post` int DEFAULT NULL,
  PRIMARY KEY (`ID_reaction`),
  KEY `fk_reaction_electeur` (`id_electeur`),
  KEY `fk_reaction_post` (`id_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Table `scrutin`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `scrutin`;
CREATE TABLE IF NOT EXISTS `scrutin` (
  `ID_scrutin` int NOT NULL AUTO_INCREMENT,
  `annee` int NOT NULL,
  `date_ouverture` date NOT NULL,
  `date_fermeture` date NOT NULL,
  `phase` enum('preparation','vote','resultat') NOT NULL,
  `id_admin` int DEFAULT NULL,
  PRIMARY KEY (`ID_scrutin`),
  KEY `fk_scrutin_admin` (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Table `vote`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `vote`;
CREATE TABLE IF NOT EXISTS `vote` (
  `ID_vote` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `age` int DEFAULT NULL,
  `sexe` enum('Homme','Femme') DEFAULT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `id_college` int DEFAULT NULL,
  `id_candidat` int DEFAULT NULL,
  `id_scrutin` int DEFAULT NULL,
  PRIMARY KEY (`ID_vote`),
  KEY `fk_vote_college` (`id_college`),
  KEY `fk_vote_candidat` (`id_candidat`),
  KEY `fk_vote_scrutin` (`id_scrutin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Contraintes
-- --------------------------------------------------------

ALTER TABLE `candidat`
  ADD CONSTRAINT `fk_candidat_scrutin`
    FOREIGN KEY (`id_scrutin`) REFERENCES `scrutin` (`ID_scrutin`)
    ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `electeur`
  ADD CONSTRAINT `fk_electeur_college`
    FOREIGN KEY (`id_college`) REFERENCES `college` (`ID_college`)
    ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `media`
  ADD CONSTRAINT `fk_media_post`
    FOREIGN KEY (`id_post`) REFERENCES `post` (`ID_post`)
    ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `reaction`
  ADD CONSTRAINT `fk_reaction_electeur`
    FOREIGN KEY (`id_electeur`) REFERENCES `electeur` (`ID_electeur`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reaction_post`
    FOREIGN KEY (`id_post`) REFERENCES `post` (`ID_post`)
    ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `scrutin`
  ADD CONSTRAINT `fk_scrutin_admin`
    FOREIGN KEY (`id_admin`) REFERENCES `administrateur` (`ID_admin`)
    ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `vote`
  ADD CONSTRAINT `fk_vote_candidat`
    FOREIGN KEY (`id_candidat`) REFERENCES `candidat` (`ID_candidat`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vote_college`
    FOREIGN KEY (`id_college`) REFERENCES `college` (`ID_college`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vote_scrutin`
    FOREIGN KEY (`id_scrutin`) REFERENCES `scrutin` (`ID_scrutin`)
    ON DELETE SET NULL ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
 /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
 /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
