-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 02 mars 2018 à 19:10
-- Version du serveur :  10.1.22-MariaDB
-- Version de PHP :  7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `autoevaluation_projetl3`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `persopass` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  `actif_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`persopass`, `email`, `password`, `salt`, `isAdmin`, `actif_token`) VALUES
(2, 'asurion61@gmail.com', '8fdb38bdc487d3d1d49fcc508d34329216a57c4e403ff5441e797b5f9760485871ee0dd8b8aa0f43c18bf387c1ed4a51d73759871e9ca86526ef790cb9b6a03c', 'ac8cc10364ee8a7893ae62aeb4bd529be50f91a5f52a5c95751684842ed5f1040acf15a2ec60010da2c27b38d86369b8e4fc2bd35cc385636611a44ed73d4404', 0, 'c74d0fab2873a1a41210e2993bea622e');

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id_etudiant` int(8) NOT NULL,
  `date_prem_conn` date NOT NULL,
  `fin_inscription` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id_etudiant`, `date_prem_conn`, `fin_inscription`) VALUES
(21404260, '2018-01-12', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `exercice`
--

CREATE TABLE `exercice` (
  `id_exercice` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `niv_etude` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `exercice`
--

INSERT INTO `exercice` (`id_exercice`, `id_matiere`, `niv_etude`, `libelle`, `date`) VALUES
(1, 3, 1, 'Exercice 7', '2018-01-13'),
(14, 1, 1, 'Exercice 1', '2018-01-16'),
(15, 1, 1, 'Exercice 1', '2018-01-16');

-- --------------------------------------------------------

--
-- Structure de la table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `u_id` int(11) NOT NULL,
  `la_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

CREATE TABLE `matieres` (
  `id_matiere` int(11) NOT NULL,
  `sem_id` int(11) NOT NULL,
  `ec_num` int(11) DEFAULT NULL,
  `ue_num` int(11) NOT NULL,
  `enonce` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `matieres`
--

INSERT INTO `matieres` (`id_matiere`, `sem_id`, `ec_num`, `ue_num`, `enonce`) VALUES
(1, 1, NULL, 1, 'Algèbre linéaire'),
(2, 2, NULL, 1, 'Algèbre linéaire'),
(3, 3, NULL, 1, 'Introduction à la POO'),
(4, 4, NULL, 1, 'Programmation avancée'),
(5, 5, NULL, 1, 'Génie logiciel'),
(6, 6, NULL, 1, 'Théorie des langages et compilation'),
(7, 1, NULL, 2, 'Outils de Calculs, Probabilités, Statistique'),
(8, 2, NULL, 2, 'Logique'),
(9, 3, NULL, 2, 'Système et réseaux 1'),
(10, 4, NULL, 2, 'Découverte des domaines de l’informatique'),
(11, 5, NULL, 2, 'Système et réseaux 2'),
(12, 6, NULL, 2, 'Structures discrètes pour l’informatique'),
(13, 1, NULL, 3, ' Introduction à la programmation'),
(14, 2, NULL, 3, 'Technologies informatiques'),
(15, 3, NULL, 3, 'Algorithmique'),
(16, 4, NULL, 3, 'Travail personnel approfondi'),
(17, 5, NULL, 3, 'Bases de données 2'),
(18, 6, NULL, 3, 'Technologies web'),
(19, 1, NULL, 4, 'Méthodologie informatique et projet professionnel'),
(20, 2, NULL, 4, 'Conception de logiciels'),
(21, 3, NULL, 4, 'Mathématiques'),
(22, 4, NULL, 4, 'Humanités'),
(23, 5, NULL, 4, 'Découverte d’un domaine de l’informatique'),
(24, 6, NULL, 4, ' Conception d’applications'),
(25, 1, NULL, 5, 'Informatique et Internet'),
(26, 2, NULL, 5, 'Humanités'),
(27, 3, NULL, 5, 'Mathématiques appliquées'),
(28, 4, NULL, 5, 'Structures algébriques pour l’informatique'),
(29, 5, NULL, 5, 'Informatique industrielle'),
(30, 6, NULL, 5, 'Stage et Communication');

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id_question` int(11) NOT NULL,
  `id_exercice` int(11) NOT NULL,
  `question` varchar(100) NOT NULL,
  `id_type` int(11) NOT NULL,
  `choix` longtext NOT NULL,
  `reponses` varchar(255) NOT NULL,
  `justificaiton` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id_question`, `id_exercice`, `question`, `id_type`, `choix`, `reponses`, `justificaiton`) VALUES
(1, 1, 'Pourquoi ?4', 3, 'Parcequew,ZQD,qzdq', '0', NULL),
(2, 1, 'Pourquzoi ?', 2, 'Parceque,ZQD,qzdq', '1', NULL),
(16, 14, 'Comment démarrer une balise PHP ?', 2, '<?php,<php>,?<php,<<php', '1', NULL),
(17, 14, 'Comment terminer une balise PHP ?', 2, 'php?>,php>,</php>,?>', '1', NULL),
(18, 15, 'Combien de pâtes ont les chats ?', 2, '1,2,3,12', '1', NULL),
(19, 15, 'Pourquoi ils ont des poals ?', 3, 'parke c d\'où,parke sait bo,pour miaulait,poux rit un', '1', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `scores`
--

CREATE TABLE `scores` (
  `id_etudiant` int(11) NOT NULL,
  `id_exercice` int(11) NOT NULL,
  `resultat` int(11) NOT NULL,
  `resultat_ancien` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `scores`
--

INSERT INTO `scores` (`id_etudiant`, `id_exercice`, `resultat`, `resultat_ancien`) VALUES
(21404260, 1, 37, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `type_question`
--

CREATE TABLE `type_question` (
  `id_type` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `type_question`
--

INSERT INTO `type_question` (`id_type`, `libelle`) VALUES
(1, 'Choix multiple'),
(2, 'Choix unique'),
(3, 'Normal');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`persopass`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id_etudiant`);

--
-- Index pour la table `exercice`
--
ALTER TABLE `exercice`
  ADD PRIMARY KEY (`id_exercice`),
  ADD KEY `id_matiere` (`id_matiere`);

--
-- Index pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD PRIMARY KEY (`id_matiere`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id_question`),
  ADD KEY `id_exercice` (`id_exercice`),
  ADD KEY `id_type` (`id_type`);

--
-- Index pour la table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id_etudiant`,`id_exercice`),
  ADD UNIQUE KEY `scores_id_etudiant_id_exercice_pk` (`id_etudiant`,`id_exercice`),
  ADD KEY `etupass` (`id_etudiant`),
  ADD KEY `ex_id` (`id_exercice`);

--
-- Index pour la table `type_question`
--
ALTER TABLE `type_question`
  ADD PRIMARY KEY (`id_type`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `exercice`
--
ALTER TABLE `exercice`
  MODIFY `id_exercice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `id_matiere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id_question` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `type_question`
--
ALTER TABLE `type_question`
  MODIFY `id_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `exercice`
--
ALTER TABLE `exercice`
  ADD CONSTRAINT `exercice_ibfk_1` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id_matiere`);

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`id_exercice`) REFERENCES `exercice` (`id_exercice`),
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `type_question` (`id_type`);

--
-- Contraintes pour la table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiants` (`id_etudiant`),
  ADD CONSTRAINT `scores_ibfk_2` FOREIGN KEY (`id_exercice`) REFERENCES `exercice` (`id_exercice`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
