-- Ajout des tables pour la fonctionnalite posts candidats
-- Date: 06/01/2026

-- Table des posts des candidats
CREATE TABLE `post` (
  `ID_post` int(11) NOT NULL AUTO_INCREMENT,
  `id_candidat` int(11) NOT NULL,
  `type_media` enum('image','video') NOT NULL,
  `chemin_media` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_post`),
  KEY `fk_post_candidat` (`id_candidat`),
  CONSTRAINT `fk_post_candidat` FOREIGN KEY (`id_candidat`) REFERENCES `candidat` (`ID_candidat`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des likes/dislikes
CREATE TABLE `reaction` (
  `ID_reaction` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `id_electeur` int(11) NOT NULL,
  `type_reaction` enum('like','dislike') NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_reaction`),
  UNIQUE KEY `unique_reaction` (`id_post`, `id_electeur`),
  KEY `fk_reaction_post` (`id_post`),
  KEY `fk_reaction_electeur` (`id_electeur`),
  CONSTRAINT `fk_reaction_post` FOREIGN KEY (`id_post`) REFERENCES `post` (`ID_post`) ON DELETE CASCADE,
  CONSTRAINT `fk_reaction_electeur` FOREIGN KEY (`id_electeur`) REFERENCES `electeur` (`ID_electeur`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des commentaires
CREATE TABLE `commentaire` (
  `ID_commentaire` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `id_electeur` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_commentaire`),
  KEY `fk_commentaire_post` (`id_post`),
  KEY `fk_commentaire_electeur` (`id_electeur`),
  CONSTRAINT `fk_commentaire_post` FOREIGN KEY (`id_post`) REFERENCES `post` (`ID_post`) ON DELETE CASCADE,
  CONSTRAINT `fk_commentaire_electeur` FOREIGN KEY (`id_electeur`) REFERENCES `electeur` (`ID_electeur`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
