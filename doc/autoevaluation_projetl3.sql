-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 05 mars 2018 à 13:43
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
(2, 'asurion61@gmail.com', 'bdd4f2a2ab707f538331670b0c2c1a2b556dee4896052530383090e8a13282c43600a61739959048d6167f060cd42fd0e654ace64bdf777dfd141b50fcef02af', 'ac8cc10364ee8a7893ae62aeb4bd529be50f91a5f52a5c95751684842ed5f1040acf15a2ec60010da2c27b38d86369b8e4fc2bd35cc385636611a44ed73d4404', 0, 'c74d0fab2873a1a41210e2993bea622e');

-- --------------------------------------------------------

--
-- Structure de la table `annees`
--

CREATE TABLE `annees` (
  `code_annee` varchar(10) NOT NULL,
  `num_annee` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `annees`
--

INSERT INTO `annees` (`code_annee`, `num_annee`) VALUES
('1LINFO', 1),
('1LMATH', 1),
('2LINFO', 2),
('2LMATH', 2),
('3LINFO', 3),
('3LMATH', 3);

-- --------------------------------------------------------

--
-- Structure de la table `diplomes`
--

CREATE TABLE `diplomes` (
  `code_diplome` varchar(10) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `diplomes`
--

INSERT INTO `diplomes` (`code_diplome`, `libelle`) VALUES
('INFO', 'informatique'),
('MATH', 'mathématique');

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
(21404260, '2018-01-12', NULL),
(21999997, '2018-03-02', '2018-11-30');

-- --------------------------------------------------------

--
-- Structure de la table `exercice`
--

CREATE TABLE `exercice` (
  `id_exercice` int(11) NOT NULL,
  `id_matiere` int(10) NOT NULL,
  `enonce` varchar(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `exercice`
--

INSERT INTO `exercice` (`id_exercice`, `id_matiere`, `enonce`, `date`) VALUES
(16, 9, 'Les bases de la sécurité informatique', '2018-03-14'),
(17, 17, 'PHP Objet', '2018-03-31');

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

CREATE TABLE `matieres` (
  `id_matiere` int(11) NOT NULL,
  `code_diplome` varchar(10) NOT NULL,
  `code_annee` varchar(10) NOT NULL,
  `code_semestre` varchar(10) NOT NULL,
  `code_ue` varchar(10) NOT NULL,
  `code_ec` varchar(10) DEFAULT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `matieres`
--

INSERT INTO `matieres` (`id_matiere`, `code_diplome`, `code_annee`, `code_semestre`, `code_ue`, `code_ec`, `libelle`) VALUES
(1, 'INFO', '3LINFO', 'INF5', 'A', NULL, 'Génie logiciel'),
(2, 'INFO', '3LINFO', 'INF5', 'A', '1', 'Méthodes de conception'),
(3, 'INFO', '3LINFO', 'INF5', 'A', '2', 'Conduite de projet'),
(4, 'INFO', '3LINFO', 'INF5', 'B', NULL, 'Système et Réseaux 2'),
(5, 'INFO', '3LINFO', 'INF5', 'B', '1', 'Système'),
(6, 'INFO', '3LINFO', 'INF5', 'B', '2', 'Réseaux'),
(7, 'INFO', '3LINFO', 'INF5', 'C', NULL, 'Bases de données 2'),
(8, 'INFO', '3LINFO', 'INF5', 'D', NULL, 'Découverte d\'un domaine de l\'informatique'),
(9, 'INFO', '3LINFO', 'INF5', 'D', '1', 'Sécurité Informatique et Protection des données'),
(10, 'INFO', '3LINFO', 'INF5', 'D', '2', 'Option2'),
(11, 'INFO', '3LINFO', 'INF5', 'D', '3', 'Option3'),
(12, 'INFO', '3LINFO', 'INF5', 'E', NULL, 'Informatique Industrielle'),
(13, 'INFO', '3LINFO', 'INF5', 'E', '1', 'Machines numériques'),
(14, 'INFO', '3LINFO', 'INF5', 'E', '2', 'Parallélisme'),
(15, 'INFO', '3LINFO', 'INF6', 'A', NULL, 'Théorie des Langages et Compilation'),
(16, 'INFO', '3LINFO', 'INF6', 'B', NULL, 'Structures discrètes pour l\'Informatique'),
(17, 'INFO', '3LINFO', 'INF6', 'C', NULL, 'Technologies Web'),
(18, 'INFO', '3LINFO', 'INF6', 'D', NULL, 'Conception d\'applications'),
(19, 'INFO', '3LINFO', 'INF6', 'D', '1', 'Programmation Androïd'),
(20, 'INFO', '3LINFO', 'INF6', 'D', '2', 'Créativité logicielle'),
(21, 'INFO', '3LINFO', 'INF6', 'D', '3', 'Projet'),
(22, 'INFO', '3LINFO', 'INF6', 'C', NULL, 'Stage et Communication S6'),
(23, 'INFO', '3LINFO', 'INF6', 'C', '1', 'Stage'),
(24, 'INFO', '3LINFO', 'INF6', 'C', '2', 'Anglais S6'),
(25, 'INFO', '3LINFO', 'INF6', 'C', '3', 'Communication');

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

-- --------------------------------------------------------

--
-- Structure de la table `semestres`
--

CREATE TABLE `semestres` (
  `code_semestre` varchar(10) NOT NULL,
  `num_semestre` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `semestres`
--

INSERT INTO `semestres` (`code_semestre`, `num_semestre`) VALUES
('INF1', 1),
('INF2', 2),
('INF3', 3),
('INF4', 4),
('INF5', 5),
('INF6', 6),
('MATH1', 1),
('MATH2', 2),
('MATH3', 3),
('MATH4', 4),
('MATH5', 5),
('MATH6', 6);

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
-- Index pour la table `annees`
--
ALTER TABLE `annees`
  ADD PRIMARY KEY (`code_annee`);

--
-- Index pour la table `diplomes`
--
ALTER TABLE `diplomes`
  ADD PRIMARY KEY (`code_diplome`);

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
  ADD PRIMARY KEY (`id_matiere`),
  ADD KEY `matieres_semestres_id_semestre_id_diplome_fk` (`code_semestre`,`code_diplome`),
  ADD KEY `matieres_annees_code_annee_fk` (`code_annee`),
  ADD KEY `matieres_diplomes_code_diplome_fk` (`code_diplome`);

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
-- Index pour la table `semestres`
--
ALTER TABLE `semestres`
  ADD PRIMARY KEY (`code_semestre`);

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
  MODIFY `id_exercice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `id_matiere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
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
  ADD CONSTRAINT `exercice_matieres_id_matiere_fk` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id_matiere`);

--
-- Contraintes pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD CONSTRAINT `matieres_annees_code_annee_fk` FOREIGN KEY (`code_annee`) REFERENCES `annees` (`code_annee`),
  ADD CONSTRAINT `matieres_diplomes_code_diplome_fk` FOREIGN KEY (`code_diplome`) REFERENCES `diplomes` (`code_diplome`),
  ADD CONSTRAINT `matieres_semestres_code_semestre_fk` FOREIGN KEY (`code_semestre`) REFERENCES `semestres` (`code_semestre`);

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
