-- Table pour stocker les codes d'authentification des professionnels

CREATE TABLE IF NOT EXISTS `code_professionnel` (
  `ID_code` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `type_professionnel` enum('journaliste','coach') NOT NULL,
  `date_generation` datetime DEFAULT current_timestamp(),
  `date_utilisation` datetime DEFAULT NULL,
  `utilise` tinyint(1) DEFAULT 0,
  `id_college` int(11) NOT NULL,
  PRIMARY KEY (`ID_code`),
  UNIQUE KEY `code` (`code`),
  KEY `id_college` (`id_college`),
  CONSTRAINT `code_professionnel_ibfk_1` FOREIGN KEY (`id_college`) REFERENCES `college` (`ID_college`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
