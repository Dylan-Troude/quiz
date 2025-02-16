-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 16 fév. 2025 à 22:01
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `quiz_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'dylan', 'dylan');

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `question` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_question_quiz` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `question`
--

INSERT INTO `question` (`id`, `quiz_id`, `question`) VALUES
(1, 1, 'Quelle est la planète la plus proche du Soleil ?'),
(2, 1, 'Combien de protons possède un atome d’hydrogène ?'),
(3, 1, 'Quel est l’élément chimique de symbole O ?'),
(4, 1, 'L’eau bout à 100°C à quelle pression atmosphérique ?'),
(5, 2, 'En quelle année a eu lieu la Révolution française ?'),
(6, 2, 'Qui était le premier empereur romain ?'),
(7, 2, 'Quel événement a marqué la fin de la Seconde Guerre mondiale ?'),
(8, 2, 'Qui a découvert l’Amérique en 1492 ?'),
(9, 3, 'Combien de joueurs y a-t-il dans une équipe de football ?'),
(10, 3, 'Quel est le sport principal des Jeux Olympiques ?'),
(11, 3, 'Combien de points vaut un panier à 3 points au basketball ?'),
(12, 3, 'Quel pays a remporté la Coupe du Monde 2018 ?'),
(13, 4, 'Qui est le fondateur de Microsoft ?'),
(14, 4, 'Quel est le langage de programmation principal pour les applications Android ?'),
(15, 4, 'Quelle entreprise a créé l’iPhone ?'),
(16, 4, 'Que signifie HTML ?');

-- --------------------------------------------------------

--
-- Structure de la table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
CREATE TABLE IF NOT EXISTS `quiz` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_quiz` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz`
--

INSERT INTO `quiz` (`id`, `nom_quiz`) VALUES
(1, 'Science'),
(2, 'Histoire'),
(3, 'Sport'),
(4, 'Technologie');

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE IF NOT EXISTS `reponse` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_question` int NOT NULL,
  `reponse_1` varchar(255) NOT NULL,
  `reponse_2` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reponse`
--

INSERT INTO `reponse` (`id`, `id_question`, `reponse_1`, `reponse_2`) VALUES
(1, 1, 'Mercure', 'Vénus'),
(2, 2, '1', '2'),
(3, 3, 'Oxygène', 'Or'),
(4, 4, '1 atm', '2 atm'),
(5, 5, '1789', '1815'),
(6, 6, 'Jules César', 'Auguste'),
(7, 7, '1945', '1939'),
(8, 8, 'Christophe Colomb', 'Vasco de Gama'),
(9, 9, '11', '9'),
(10, 10, 'Athlétisme', 'Natation'),
(11, 11, '3', '2'),
(12, 12, 'France', 'Brésil'),
(13, 13, 'Steve Jobs', 'Bill Gates'),
(14, 14, 'Java', 'Python'),
(15, 15, 'Apple', 'Samsung'),
(16, 16, 'HyperText Markup Language', 'High Tech Modern Language');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
