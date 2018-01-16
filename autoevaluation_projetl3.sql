-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 16 jan. 2018 à 15:02
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
  `id` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  `actif_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `salt`, `isAdmin`, `actif_token`) VALUES
(2, 'asurion61@gmail.com', '8fdb38bdc487d3d1d49fcc508d34329216a57c4e403ff5441e797b5f9760485871ee0dd8b8aa0f43c18bf387c1ed4a51d73759871e9ca86526ef790cb9b6a03c', 'ac8cc10364ee8a7893ae62aeb4bd529be50f91a5f52a5c95751684842ed5f1040acf15a2ec60010da2c27b38d86369b8e4fc2bd35cc385636611a44ed73d4404', 0, 'c74d0fab2873a1a41210e2993bea622e');

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id_etudiant` int(8) NOT NULL,
  `date_prem_conn` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id_etudiant`, `date_prem_conn`) VALUES
(21404260, '2018-01-12');

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
(1, 1, 0, 'Exercice 1', '2018-01-13');

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
  `ue_num` int(11) NOT NULL,
  `sem_id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `matieres`
--

INSERT INTO `matieres` (`id_matiere`, `ue_num`, `sem_id`, `libelle`) VALUES
(1, 1, 1, 'Algèbre linéaire'),
(2, 1, 2, 'Algèbre linéaire'),
(3, 1, 3, 'Introduction à la POO'),
(4, 1, 4, 'Programmation avancée'),
(5, 1, 5, 'Génie logiciel'),
(6, 1, 6, 'Théorie des langages et compilation'),
(7, 2, 1, 'Outils de Calculs, Probabilités, Statistique'),
(8, 2, 2, 'Logique'),
(9, 2, 3, 'Système et réseaux 1'),
(10, 2, 4, 'Découverte des domaines de l’informatique'),
(11, 2, 5, 'Système et réseaux 2'),
(12, 2, 6, 'Structures discrètes pour l’informatique'),
(13, 3, 1, ' Introduction à la programmation'),
(14, 3, 2, 'Technologies informatiques'),
(15, 3, 3, 'Algorithmique'),
(16, 3, 4, 'Travail personnel approfondi'),
(17, 3, 5, 'Bases de données 2'),
(18, 3, 6, 'Technologies web'),
(19, 4, 1, 'Méthodologie informatique et projet professionnel'),
(20, 4, 2, 'Conception de logiciels'),
(21, 4, 3, 'Mathématiques'),
(22, 4, 4, 'Humanités'),
(23, 4, 5, 'Découverte d’un domaine de l’informatique'),
(24, 4, 6, ' Conception d’applications'),
(25, 5, 1, 'Informatique et Internet'),
(26, 5, 2, 'Humanités'),
(27, 5, 3, 'Mathématiques appliquées'),
(28, 5, 4, 'Structures algébriques pour l’informatique'),
(29, 5, 5, 'Informatique industrielle'),
(30, 5, 6, 'Stage et Communication');

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
  `reponses` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id_question`, `id_exercice`, `question`, `id_type`, `choix`, `reponses`) VALUES
(1, 1, 'Comment définir une variable en JavaScript ?', 1, 'var variable;,this.variable;,define variable;,Pas besoin de définir', '1'),
(2, 1, 'Pourquoi ?', 3, 'Parceque,ZQD,qzdq', '1');

-- --------------------------------------------------------

--
-- Structure de la table `scores`
--

CREATE TABLE `scores` (
  `score_id` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `id_exercice` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `scores`
--

INSERT INTO `scores` (`score_id`, `id_etudiant`, `id_exercice`, `total`) VALUES
(1, 21404260, 1, 37);

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
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`score_id`,`id_etudiant`),
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
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `exercice`
--
ALTER TABLE `exercice`
  MODIFY `id_exercice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `id_matiere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id_question` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `scores`
--
ALTER TABLE `scores`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
