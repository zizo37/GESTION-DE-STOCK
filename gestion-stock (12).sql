-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 25 avr. 2024 à 13:52
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion-stock`
--

-- --------------------------------------------------------

--
-- Structure de la table `adminauthentification`
--

DROP TABLE IF EXISTS `adminauthentification`;
CREATE TABLE IF NOT EXISTS `adminauthentification` (
  `idAd` int NOT NULL AUTO_INCREMENT,
  `loginAd` varchar(50) NOT NULL,
  `passwordAD` varchar(50) NOT NULL,
  PRIMARY KEY (`idAd`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `adminauthentification`
--

INSERT INTO `adminauthentification` (`idAd`, `loginAd`, `passwordAD`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `amortissement`
--

DROP TABLE IF EXISTS `amortissement`;
CREATE TABLE IF NOT EXISTS `amortissement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_Article` int NOT NULL,
  `TypeArt_ID` int NOT NULL,
  `QteRegion` int NOT NULL,
  `QteME` int NOT NULL,
  `Total` int NOT NULL,
  `QteSortie` int NOT NULL,
  `QteReste` int NOT NULL,
  `bc_marche_id` int NOT NULL,
  `beneficiaire_matricule` int NOT NULL,
  `DateAmor` datetime NOT NULL,
  `Location_Name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_MORTISSEMent_Articles` (`id_Article`),
  KEY `FK_typeArt` (`TypeArt_ID`),
  KEY `FK_BENIF` (`beneficiaire_matricule`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `amortissement`
--

INSERT INTO `amortissement` (`id`, `id_Article`, `TypeArt_ID`, `QteRegion`, `QteME`, `Total`, `QteSortie`, `QteReste`, `bc_marche_id`, `beneficiaire_matricule`, `DateAmor`, `Location_Name`) VALUES
(1, 25, 4, 0, 0, 0, 10, 0, 2, 1, '2024-03-14 00:00:00', ''),
(2, 25, 4, 0, 0, 0, 40, 250, 3, 5, '2024-03-14 00:00:00', ''),
(3, 25, 4, 300, 50, 350, 50, 200, 1, 1, '2024-03-14 00:00:00', ''),
(4, 21, 2, 0, 0, 0, 40, 60, 2, 5, '2024-03-14 00:00:00', ''),
(5, 12, 1, 0, 0, 0, 4, 96, 2, 1, '2024-03-14 00:00:00', ''),
(6, 7, 3, 0, 0, 0, 40, 60, 2, 1, '2024-03-14 00:00:00', ''),
(7, 7, 3, 0, 0, 0, 40, 20, 2, 1, '2024-03-14 00:00:00', ''),
(8, 17, 2, 0, 0, 0, 40, 60, 1, 1, '2024-03-14 00:00:00', ''),
(9, 16, 2, 0, 100, 100, 40, 60, 2, 6, '2024-03-14 00:00:00', ''),
(10, 25, 1, 300, 50, 350, 50, 150, 2, 1, '2024-03-15 00:00:00', ''),
(11, 25, 1, 500, 100, 600, 50, 350, 1, 1, '2024-03-16 00:00:00', ''),
(12, 25, 2, 500, 100, 600, 100, 250, 1, 6, '2024-03-16 00:00:00', ''),
(13, 25, 2, 500, 100, 600, 200, 50, 1, 1, '2024-03-16 00:00:00', ''),
(14, 25, 1, 500, 100, 600, 6, 44, 1, 1, '2024-03-16 00:00:00', ''),
(15, 24, 1, 50, 50, 100, 50, 150, 1, 5, '2024-03-16 00:00:00', ''),
(17, 19, 1, 0, 0, 0, 4, 96, 2, 5, '2024-03-16 00:00:00', ''),
(18, 24, 1, 0, 0, 0, 40, 0, 2, 1, '2024-03-18 00:00:00', ''),
(19, 25, 1, 0, 0, 0, 40, 0, 2, 1, '2024-03-18 00:00:00', ''),
(20, 1, 2, 0, 0, 0, 40, 0, 1, 5, '2024-03-18 00:00:00', ''),
(21, 1, 1, 60, 0, 60, 60, 0, 1, 1, '2024-03-18 00:00:00', ''),
(22, 4, 1, 0, 60, 160, 50, 110, 1, 1, '2024-03-19 00:00:00', ''),
(23, 4, 1, 20, 60, 180, 30, 150, 1, 5, '2024-03-19 00:00:00', ''),
(24, 4, 2, 20, 60, 150, 40, 110, 1, 6, '2024-03-19 00:00:00', ''),
(25, 9, 1, 50, 50, 200, 20, 180, 1, 6, '2024-03-20 00:00:00', ''),
(33, 25, 1, 350, 140, 490, 4, 486, 1, 5, '2024-03-20 00:00:00', ''),
(34, 24, 1, 160, 60, 220, 7, 213, 1, 5, '2024-03-20 00:00:00', ''),
(35, 1, 1, 100, 0, 100, 4, 96, 2, 5, '2024-03-21 00:00:00', ''),
(37, 1, 1, 40, 0, 40, 4, 36, 1, 1, '2024-03-21 00:00:00', ''),
(38, 1, 1, 36, 0, 36, 9, 27, 3, 6, '2024-03-21 00:00:00', ''),
(39, 4, 2, 0, 100, 100, 10, 90, 1, 5, '2024-03-21 00:00:00', ''),
(40, 4, 2, -10, 100, 90, 10, 80, 1, 5, '2024-03-21 00:00:00', ''),
(41, 4, 2, -20, 100, 80, 10, 70, 1, 5, '2024-03-21 00:00:00', ''),
(42, 4, 2, -30, 100, 70, 10, 60, 1, 6, '2024-03-21 00:00:00', ''),
(43, 5, 2, 20, 0, 20, 4, 16, 1, 5, '2024-03-21 00:00:00', ''),
(44, 5, 2, 16, 0, 16, 10, 6, 1, 1, '2024-03-21 00:00:00', ''),
(45, 4, 2, -40, 100, 60, 70, -10, 1, 6, '2024-03-21 00:00:00', ''),
(51, 2, 1, 7, 0, 7, 5, 2, 2, 6, '2024-03-22 21:36:28', ''),
(52, 2, 1, 7, 0, 7, 2, 5, 1, 6, '2024-03-22 21:40:10', ''),
(53, 2, 1, 7, 0, 7, 2, 5, 2, 5, '2024-03-22 21:43:06', ''),
(54, 2, 1, 7, 0, 7, 1, 6, 1, 1, '2024-03-22 21:45:55', ''),
(55, 1, 1, 7, 0, 7, 3, 4, 3, 5, '2024-03-22 23:29:32', 'Région'),
(56, 3, 1, 70, 60, 130, 4, 126, 1, 1, '2024-03-23 00:30:43', 'Région'),
(57, 3, 1, 66, 60, 126, 2, 124, 1, 1, '2024-03-23 15:49:39', 'Région'),
(58, 25, 1, 10, 10, 20, 2, 18, 1, 1, '2024-03-23 16:39:09', 'Région'),
(59, 25, 1, 8, 10, 18, 2, 16, 1, 1, '2024-03-23 16:40:29', 'Région'),
(60, 24, 1, 20, 8, 28, 5, 23, 1, 1, '2024-03-23 16:50:38', 'Région'),
(61, 24, 1, 15, 8, 23, 5, 18, 1, 1, '2024-03-23 16:55:21', 'Région'),
(62, 24, 1, 10, 8, 18, 5, 13, 1, 1, '2024-03-23 16:56:26', 'Région'),
(63, 24, 1, 5, 8, 13, 5, 8, 1, 6, '2024-03-23 16:56:55', 'Région'),
(64, 25, 1, 6, 10, 16, 1, 15, 1, 1, '2024-03-23 16:59:35', 'Région'),
(65, 19, 1, 7, 3, 10, 2, 8, 1, 1, '2024-03-24 16:14:16', 'ME'),
(66, 19, 1, 7, 3, 10, 3, 7, 1, 6, '2024-03-24 16:17:49', 'ME'),
(67, 25, 2, 160, 100, 260, 60, 200, 1, 1, '2024-03-24 16:18:13', 'Région'),
(68, 25, 1, 100, 100, 200, 5, 195, 1, 1, '2024-03-24 16:27:30', 'ME'),
(69, 25, 1, 100, 95, 195, 4, 191, 3, 6, '2024-03-24 16:29:26', 'ME'),
(70, 24, 2, 65, 60, 125, 5, 120, 1, 1, '2024-03-24 16:30:07', 'ME'),
(71, 1, 1, 20, 20, 40, 5, 35, 1, 1, '2024-03-24 16:46:11', 'Région'),
(72, 7, 1, 20, 20, 40, 4, 36, 1, 1, '2024-03-25 01:18:13', 'Région'),
(73, 25, 1, 60, 40, 100, 20, 80, 1, 1, '2024-03-27 13:48:01', 'Région'),
(74, 24, 1, 20, 20, 40, 10, 30, 1, 1, '2024-03-27 14:01:40', 'Région'),
(75, 24, 1, 10, 20, 30, 2, 28, 1, 1, '2024-03-27 14:02:51', 'Région'),
(76, 25, 1, 40, 40, 80, 34, 46, 1, 1, '2024-03-29 10:22:32', 'Région'),
(77, 24, 1, 8, 20, 28, 8, 20, 1, 1, '2024-03-29 12:22:37', 'Région'),
(78, 25, 1, 6, 40, 46, 1, 45, 1, 9, '2024-03-30 14:34:11', 'ME'),
(81, 25, 1, 6, 39, 45, 1, 44, 1, 1, '2024-04-01 14:50:45', 'Région');

--
-- Déclencheurs `amortissement`
--
DROP TRIGGER IF EXISTS `update_qte_stock`;
DELIMITER $$
CREATE TRIGGER `update_qte_stock` AFTER INSERT ON `amortissement` FOR EACH ROW BEGIN
    DECLARE qte_s INT;
    DECLARE articleId INT;
    DECLARE location_name VARCHAR(50);
    DECLARE marche_id INT;
    
    SET qte_s = NEW.QteSortie;
    SET articleId = NEW.id_Article;
    SET location_name = NEW.Location_Name;
    SET marche_id = NEW.bc_marche_id;
    
    IF location_name = 'Région' THEN
        UPDATE qte_stock
        SET QteRegion = QteRegion - qte_s,
            total_quantity = QteRegion + QteME
        WHERE article_id = articleId AND marche = marche_id;
    ELSEIF location_name = 'ME' THEN
        UPDATE qte_stock
        SET QteME = QteME - qte_s,
            total_quantity = QteRegion + QteME
        WHERE article_id = articleId AND marche = marche_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) NOT NULL,
  `type_id` int NOT NULL,
  `image_path` varchar(250) NOT NULL,
  `Qte_Total` int NOT NULL,
  `QteRegion` int NOT NULL DEFAULT '0',
  `QteME` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `designation`, `type_id`, `image_path`, `Qte_Total`, `QteRegion`, `QteME`) VALUES
(1, 'TONER HP CF400A', 1, '', 0, 0, 0),
(2, 'TONER HP CF401A', 1, '', 0, 0, 0),
(3, 'TONER HP CF402A', 1, '', 0, 0, 0),
(4, 'TONER HP CF403A', 1, '', 0, 0, 0),
(5, 'TONER 305 A NOIR', 1, '', 0, 0, 0),
(6, 'TONER 305 A CYAN', 1, 'images/660842803159a.PNG', 0, 0, 0),
(7, 'TONER 305 A MAGENTA', 1, 'images/660842803159a.PNG', 0, 0, 0),
(8, 'TONER 305 A JAUNE', 1, 'images/660842803159a.PNG', 0, 0, 0),
(9, 'TONER 26A', 1, 'images/660842803159a.PNG', 0, 0, 0),
(10, 'TONER KONICA MINOLTA TN-324K', 1, 'images/660842803159a.PNG', 0, 0, 0),
(11, 'TONER KONICA MINOLTA TN-324C', 1, 'images/660842803159a.PNG', 0, 0, 0),
(12, 'TONER KONICA MINOLTA TN-324M', 1, 'images/660842803159a.PNG', 0, 0, 0),
(13, 'TONER KONICA MINOLTA TN-324Y', 1, 'images/660842803159a.PNG', 0, 0, 0),
(14, 'CARTOUCHE POUR IMPRIMANTE LEXMARK MC2535 NOIR', 2, 'images/6608aa2c62afb.jpg', 0, 0, 0),
(15, 'CARTOUCHE POUR IMPRIMANTE LEXMARK MC2535 CYAN', 2, 'images/6608a17d23cfb.jpg', 0, 0, 0),
(16, 'CARTOUCHE POUR IMPRIMANTE LEXMARK MC2535 MAGENTA', 2, 'images/6608aa21dc356.jpg', 0, 0, 0),
(17, 'CARTOUCHE POUR IMPRIMANTE LEXMARK MC2535 JAUNE', 2, 'images/6608a4a961eb2.jpg', 0, 0, 0),
(18, 'TONER POUR IMPRIMANTE KONICA MINOLTA BIZHUB 4000 I ET 4020 I ET PLUS', 3, 'images/660842803159a.PNG', 0, 0, 0),
(19, 'ENROULEUR DOMESTIQUE 2P+T 5M', 4, 'images/6609689587c46.jpg', 0, 0, 0),
(20, 'DATATRAVELER CLÉ USB 3.1/3.0/2.0 16GB', 4, 'images/6608aa3dee727.jpg', 0, 0, 0),
(21, 'CLAVIER BILINGUE USB HP OU SIMILAIRE POUR ORDINATEUR (FRANÇAIS - ARABE) AZERTY', 4, 'images/6608aa34efa33.jpg', 100, 10, 90),
(22, 'SOURIS À MOLETTE OPTIQUE USB', 4, 'images/660ad81e45014.jpg', 0, 0, 0),
(24, 'cable vga 20 metre', 4, 'images/6608a14b80921.jpg', 40, 10, 30),
(25, 'cable hdmi 20 metres', 4, 'images/6608a11e50bf3.jpg', 64, 15, 49),
(34, 'Designation1', 2, 'images/66084a4b2f3d1.PNG', 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `bc_marche`
--

DROP TABLE IF EXISTS `bc_marche`;
CREATE TABLE IF NOT EXISTS `bc_marche` (
  `idMarche` int NOT NULL AUTO_INCREMENT,
  `NomMarche` varchar(225) NOT NULL,
  PRIMARY KEY (`idMarche`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `bc_marche`
--

INSERT INTO `bc_marche` (`idMarche`, `NomMarche`) VALUES
(1, 'Marché1'),
(2, 'Marché2'),
(3, 'Marché3');

-- --------------------------------------------------------

--
-- Structure de la table `beneficiaire`
--

DROP TABLE IF EXISTS `beneficiaire`;
CREATE TABLE IF NOT EXISTS `beneficiaire` (
  `matricule` int NOT NULL AUTO_INCREMENT,
  `MatriculeU` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  PRIMARY KEY (`matricule`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `beneficiaire`
--

INSERT INTO `beneficiaire` (`matricule`, `MatriculeU`, `nom`, `prenom`) VALUES
(1, 1, 'nom1', 'prenom1'),
(5, 0, 'nom2', 'prenom2'),
(6, 0, 'nom3', 'prenom3'),
(7, 0, 'nom4', 'prenom4'),
(8, 0, 'nom5', 'prenom5'),
(9, 0, 'nom6', 'prenom6'),
(10, 0, 'nom7', 'prenom7');

--
-- Déclencheurs `beneficiaire`
--
DROP TRIGGER IF EXISTS `before_insert_beneficiaire`;
DELIMITER $$
CREATE TRIGGER `before_insert_beneficiaire` BEFORE INSERT ON `beneficiaire` FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM beneficiaire WHERE nom = NEW.nom AND prenom = NEW.prenom) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ERROOOOOOOOOOR Beneficiary already exists';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE IF NOT EXISTS `location` (
  `idL` int NOT NULL AUTO_INCREMENT,
  `locationName` varchar(50) NOT NULL,
  PRIMARY KEY (`idL`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `location`
--

INSERT INTO `location` (`idL`, `locationName`) VALUES
(1, 'Région'),
(2, 'ME');

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `location_id` int DEFAULT NULL,
  `beneficiary_matricule` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`),
  KEY `location_id` (`location_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `notification`
--

INSERT INTO `notification` (`id`, `article_id`, `quantity`, `location_id`, `beneficiary_matricule`, `created_at`) VALUES
(9, 25, 10, 1, 1, '2024-04-22 15:47:14'),
(8, 25, 4, 1, 12, '2024-04-08 16:04:40'),
(5, 25, 1, 1, 1, '2024-03-31 15:37:18');

-- --------------------------------------------------------

--
-- Structure de la table `qte_stock`
--

DROP TABLE IF EXISTS `qte_stock`;
CREATE TABLE IF NOT EXISTS `qte_stock` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `designation` varchar(250) NOT NULL,
  `total_quantity` int NOT NULL DEFAULT '0',
  `QteRegion` int NOT NULL DEFAULT '0',
  `QteME` int NOT NULL DEFAULT '0',
  `Old_Qte_Region` int NOT NULL,
  `Old_Qte_ME` int NOT NULL,
  `marche` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `qte_stock_FK_idArticle` (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `qte_stock`
--

INSERT INTO `qte_stock` (`id`, `article_id`, `designation`, `total_quantity`, `QteRegion`, `QteME`, `Old_Qte_Region`, `Old_Qte_ME`, `marche`) VALUES
(95, 25, 'cable hdmi 20 metres', 44, 5, 39, 50, 30, 1),
(97, 24, 'cable vga 20 metre', 20, 0, 20, 10, 10, 1),
(98, 25, 'cable hdmi 20 metres', 20, 10, 10, 0, 0, 2),
(99, 24, 'cable vga 20 metre', 20, 10, 10, 0, 0, 2),
(100, 21, 'CLAVIER BILINGUE USB HP OU SIMILAIRE POUR ORDINATEUR (FRANÇAIS - ARABE) AZERTY', 100, 10, 90, 0, 0, 1);

--
-- Déclencheurs `qte_stock`
--
DROP TRIGGER IF EXISTS `TR1_insert_update_articles`;
DELIMITER $$
CREATE TRIGGER `TR1_insert_update_articles` AFTER INSERT ON `qte_stock` FOR EACH ROW BEGIN
    -- Update the corresponding row in the articles table
    UPDATE articles
    SET QteRegion = (SELECT SUM(QteRegion) FROM qte_stock WHERE article_id = NEW.article_id),
        QteME = (SELECT SUM(QteME) FROM qte_stock WHERE article_id = NEW.article_id),
        Qte_Total = (SELECT SUM(QteRegion) + SUM(QteME) FROM qte_stock WHERE article_id = NEW.article_id)
    WHERE id = NEW.article_id;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `TR1_update_articles`;
DELIMITER $$
CREATE TRIGGER `TR1_update_articles` AFTER UPDATE ON `qte_stock` FOR EACH ROW BEGIN
    -- Update the corresponding row in the articles table
    UPDATE articles
    SET QteRegion = (SELECT SUM(QteRegion) FROM qte_stock WHERE article_id = NEW.article_id),
        QteME = (SELECT SUM(QteME) FROM qte_stock WHERE article_id = NEW.article_id),
        Qte_Total = (SELECT SUM(QteRegion) + SUM(QteME) FROM qte_stock WHERE article_id = NEW.article_id)
    WHERE id = NEW.article_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `location` int NOT NULL,
  `quantity` int NOT NULL,
  `bc_marche_id` int NOT NULL,
  `marche` int NOT NULL,
  `DateSt` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_IDARTICLE_Stock` (`article_id`),
  KEY `FK_Marche_Stock` (`bc_marche_id`),
  KEY `FK_Location_Stock` (`location`)
) ENGINE=InnoDB AUTO_INCREMENT=339 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`id`, `article_id`, `location`, `quantity`, `bc_marche_id`, `marche`, `DateSt`) VALUES
(290, 25, 1, 10, 1, 1, '0000-00-00'),
(291, 25, 2, 10, 1, 1, '0000-00-00'),
(298, 25, 1, 10, 1, 1, '0000-00-00'),
(299, 25, 2, 10, 1, 1, '0000-00-00'),
(300, 25, 1, 10, 1, 1, '0000-00-00'),
(301, 25, 2, 10, 1, 1, '0000-00-00'),
(302, 25, 1, 10, 1, 1, '0000-00-00'),
(303, 25, 2, 10, 1, 1, '0000-00-00'),
(304, 25, 1, 10, 1, 1, '0000-00-00'),
(305, 25, 2, 5, 1, 1, '0000-00-00'),
(306, 25, 1, 10, 1, 1, '0000-00-00'),
(307, 25, 2, 5, 1, 1, '0000-00-00'),
(308, 25, 1, 10, 1, 1, '0000-00-00'),
(309, 25, 2, 5, 1, 1, '0000-00-00'),
(310, 25, 1, 10, 1, 1, '0000-00-00'),
(311, 25, 2, 5, 1, 1, '0000-00-00'),
(312, 25, 1, 10, 1, 1, '0000-00-00'),
(313, 25, 2, 5, 1, 1, '0000-00-00'),
(314, 25, 1, 10, 1, 1, '0000-00-00'),
(315, 25, 2, 5, 1, 1, '0000-00-00'),
(316, 25, 1, 10, 1, 1, '0000-00-00'),
(317, 25, 2, 5, 1, 1, '0000-00-00'),
(318, 25, 1, 10, 1, 1, '0000-00-00'),
(319, 25, 2, 5, 1, 1, '0000-00-00'),
(321, 25, 1, 10, 1, 1, '0000-00-00'),
(322, 25, 2, 5, 1, 1, '0000-00-00'),
(323, 24, 1, 10, 1, 1, '0000-00-00'),
(324, 24, 2, 10, 1, 1, '0000-00-00'),
(325, 25, 1, 10, 1, 1, '0000-00-00'),
(326, 25, 2, 5, 1, 1, '0000-00-00'),
(327, 24, 1, 10, 1, 1, '0000-00-00'),
(328, 24, 2, 10, 1, 1, '0000-00-00'),
(329, 25, 1, 10, 1, 1, '0000-00-00'),
(330, 25, 2, 10, 1, 1, '0000-00-00'),
(331, 24, 1, 10, 1, 1, '0000-00-00'),
(332, 24, 2, 10, 1, 1, '0000-00-00'),
(333, 25, 1, 10, 2, 2, '0000-00-00'),
(334, 25, 2, 10, 2, 2, '0000-00-00'),
(335, 24, 1, 10, 2, 2, '0000-00-00'),
(336, 24, 2, 10, 2, 2, '0000-00-00'),
(337, 21, 1, 10, 1, 1, '0000-00-00'),
(338, 21, 2, 90, 1, 1, '0000-00-00');

--
-- Déclencheurs `stock`
--
DROP TRIGGER IF EXISTS `TR1`;
DELIMITER $$
CREATE TRIGGER `TR1` AFTER INSERT ON `stock` FOR EACH ROW BEGIN
    DECLARE existing_record_id INT;
    DECLARE location_name VARCHAR(50);
    DECLARE article_designation VARCHAR(100);
    DECLARE old_Qte_Region INT;
    DECLARE old_Qte_ME INT;

    -- Retrieve the location name based on the location ID
    SELECT locationName INTO location_name
    FROM location
    WHERE idL = NEW.location;

    -- Retrieve the article designation
    SELECT designation INTO article_designation
    FROM articles
    WHERE id = NEW.article_id;

    -- Check if the article and marche combination already exists in the qte_stock table
    SELECT id, QteRegion, QteME INTO existing_record_id, old_Qte_Region, old_Qte_ME
    FROM qte_stock
    WHERE article_id = NEW.article_id AND marche = NEW.bc_marche_id;

    IF existing_record_id IS NOT NULL THEN
        IF location_name = 'Région' THEN
            -- Update the existing record for Région
            SET @old_Qte_Region := old_Qte_Region;
            UPDATE qte_stock
            SET QteRegion = QteRegion + NEW.quantity,
                Old_Qte_Region = @old_Qte_Region,
                total_quantity = total_quantity + NEW.quantity
            WHERE id = existing_record_id;
        ELSE
            -- Update the existing record for ME
            SET @old_Qte_ME := old_Qte_ME;
            UPDATE qte_stock
            SET QteME = QteME + NEW.quantity,
                Old_Qte_ME = @old_Qte_ME,
                total_quantity = total_quantity + NEW.quantity
            WHERE id = existing_record_id;
        END IF;
    ELSE
        -- Insert a new record for Région or ME
        IF location_name = 'Région' THEN
            INSERT INTO qte_stock (article_id, designation, total_quantity, QteRegion, QteME, Old_Qte_Region, Old_Qte_ME, marche)
            VALUES (NEW.article_id, article_designation, NEW.quantity, NEW.quantity, 0, 0, 0, NEW.bc_marche_id);
        ELSE
            INSERT INTO qte_stock (article_id, designation, total_quantity, QteRegion, QteME, Old_Qte_Region, Old_Qte_ME, marche)
            VALUES (NEW.article_id, article_designation, NEW.quantity, 0, NEW.quantity, 0, 0, NEW.bc_marche_id);
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `typeart`
--

DROP TABLE IF EXISTS `typeart`;
CREATE TABLE IF NOT EXISTS `typeart` (
  `idT` int NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`idT`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typeart`
--

INSERT INTO `typeart` (`idT`, `type`) VALUES
(1, 'U'),
(2, 'R'),
(3, 'B'),
(4, 'Jeu');

-- --------------------------------------------------------

--
-- Structure de la table `usersauthentification`
--

DROP TABLE IF EXISTS `usersauthentification`;
CREATE TABLE IF NOT EXISTS `usersauthentification` (
  `idU` int NOT NULL AUTO_INCREMENT,
  `matricule` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`idU`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `usersauthentification`
--

INSERT INTO `usersauthentification` (`idU`, `matricule`, `nom`, `prenom`, `address`, `telephone`, `email`, `password`) VALUES
(1, 1, 'nom10', 'prenom10', 'Adresse5', '06643114184', 'user10@gmail.com', 'user10'),
(2, 11, 'nom11', 'prenom11', 'Adresse11', '06643114184', 'nom11@gmail.com', 'user11'),
(3, 12, 'nom12', 'prenom12', 'Adresse12', '06643114184', 'nom12@gmail.com', 'user12');

--
-- Déclencheurs `usersauthentification`
--
DROP TRIGGER IF EXISTS `insert_beneficiaire`;
DELIMITER $$
CREATE TRIGGER `insert_beneficiaire` AFTER INSERT ON `usersauthentification` FOR EACH ROW BEGIN
    INSERT INTO beneficiaire (MatriculeU, nom, prenom) VALUES (NEW.matricule, NEW.nom, NEW.prenom);
END
$$
DELIMITER ;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `amortissement`
--
ALTER TABLE `amortissement`
  ADD CONSTRAINT `FK_BENIF` FOREIGN KEY (`beneficiaire_matricule`) REFERENCES `beneficiaire` (`matricule`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MORTISSEMent_Articles` FOREIGN KEY (`id_Article`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_typeArt` FOREIGN KEY (`TypeArt_ID`) REFERENCES `typeart` (`idT`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `qte_stock`
--
ALTER TABLE `qte_stock`
  ADD CONSTRAINT `qte_stock_FK_idArticle` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `FK_IDARTICLE_Stock` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Location_Stock` FOREIGN KEY (`location`) REFERENCES `location` (`idL`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Marche_Stock` FOREIGN KEY (`bc_marche_id`) REFERENCES `bc_marche` (`idMarche`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
