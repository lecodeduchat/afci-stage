-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 18 mai 2023 à 09:35
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `chiro`
--

-- --------------------------------------------------------

--
-- Structure de la table `acts`
--

CREATE TABLE `acts` (
  `invoice_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `care_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `child_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `appointments`
--

INSERT INTO `appointments` (`id`, `care_id`, `user_id`, `date`, `child_id`) VALUES
(72, 4, 1, '2023-05-17 09:30:00', NULL),
(73, 1, 25, '2023-05-17 10:00:00', NULL),
(74, 6, 63, '2023-05-17 10:45:00', NULL),
(75, 1, 4, '2023-05-16 11:30:00', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `cares`
--

CREATE TABLE `cares` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `duration` time NOT NULL,
  `color` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cares`
--

INSERT INTO `cares` (`id`, `name`, `price`, `duration`, `color`) VALUES
(1, 'Première séance chiropractique', 67, '00:45:00', '#b2ecf7'),
(2, 'Première séance enfant', 67, '00:45:00', '#d9c7ed'),
(3, 'Première séance femme enceinte', 67, '00:45:00', '#f3bfcd'),
(4, 'Suivi de consultation', 60, '00:30:00', '#bbe7d7'),
(5, 'Suivi de consultation enfant', 60, '00:30:00', '#fbedb6'),
(6, 'Suivi de consultation femme enceinte', 60, '00:30:00', '#fdc197');

-- --------------------------------------------------------

--
-- Structure de la table `childs`
--

CREATE TABLE `childs` (
  `id` int(11) NOT NULL,
  `parent1_id` int(11) NOT NULL,
  `parent2_id` int(11) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `birthday` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `days_off`
--

CREATE TABLE `days_off` (
  `id` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `title` longtext NOT NULL,
  `color` varchar(7) NOT NULL,
  `all_day` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `days_off`
--

INSERT INTO `days_off` (`id`, `start`, `end`, `title`, `color`, `all_day`, `created_at`) VALUES
(1, '2023-05-05 00:00:00', '2023-05-05 00:00:00', 'Jour férié', '#b2ecf7', 1, '2023-05-05 10:36:20'),
(2, '2023-05-18 00:00:00', '2023-05-18 00:00:00', 'Jour férié', '#b2ecf7', 1, '2023-05-05 10:48:28'),
(3, '2023-05-29 00:00:00', '2023-05-29 00:00:00', 'Jour de congé', '#b2ecf7', 1, '2023-05-05 10:50:38');

-- --------------------------------------------------------

--
-- Structure de la table `days_on`
--

CREATE TABLE `days_on` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_morning` time DEFAULT NULL,
  `end_morning` time DEFAULT NULL,
  `start_afternoon` time DEFAULT NULL,
  `end_afternoon` time DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `days_on`
--

INSERT INTO `days_on` (`id`, `date`, `start_morning`, `end_morning`, `start_afternoon`, `end_afternoon`, `created_at`) VALUES
(1, '2023-05-05', '09:30:00', '12:30:00', '13:30:00', '18:00:00', '2023-05-05 10:55:33'),
(3, '2023-05-06', '09:00:00', '13:00:00', NULL, NULL, '2023-05-05 11:03:32'),
(4, '2023-05-09', '09:30:00', '12:30:00', '13:30:00', '18:00:00', '2023-05-05 11:03:32'),
(5, '2023-05-10', '09:00:00', '12:30:00', '13:30:00', '18:00:00', '2023-05-05 11:05:07'),
(6, '2023-05-11', '09:00:00', '12:30:00', '13:30:00', '16:30:00', '2023-05-05 11:12:21'),
(7, '2023-05-12', '09:30:00', '12:30:00', '13:30:00', '18:00:00', '2023-05-05 11:12:21'),
(8, '2023-05-13', '09:30:00', '13:00:00', NULL, NULL, '2023-05-09 14:52:49'),
(9, '2023-05-15', '09:30:00', '12:30:00', '13:30:00', '16:30:00', '2023-05-09 14:52:49'),
(10, '2023-05-16', '09:30:00', '12:30:00', '13:30:00', '18:00:00', '2023-05-09 14:53:49'),
(11, '2023-05-17', '09:30:00', '12:30:00', '13:30:00', '16:30:00', '2023-05-09 14:53:49'),
(13, '2023-05-18', '09:30:00', '12:30:00', '13:30:00', '16:30:00', '2023-05-16 16:53:04');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230516070355', '2023-05-16 09:04:01', 32);

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `address_number` varchar(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address_details` varchar(255) DEFAULT NULL,
  `zipcode` varchar(5) NOT NULL,
  `city` varchar(150) NOT NULL,
  `country` varchar(150) NOT NULL,
  `home_phone` varchar(10) NOT NULL,
  `cell_phone` varchar(10) NOT NULL,
  `is_blocked` tinyint(1) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)',
  `user_ref` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`, `birthday`, `address_number`, `address`, `address_details`, `zipcode`, `city`, `country`, `home_phone`, `cell_phone`, `is_blocked`, `is_verified`, `reset_token`, `created_at`, `user_ref`) VALUES
(1, 'delportelaurence@gmail.com', '[\"ROLE_ADMIN\"]', 'admin', 'Laurence', 'Delporte', '1975-07-11', '', '28 allée Thalès', 'appartement 12', '59650', 'Villeneuve d\'Ascq', 'France', '', '680766956', 0, 1, '', '2023-05-12 12:15:59', NULL),
(2, '', '', '', 'Guy', ' Bonneau', '0000-00-00', '112', 'rue arago', '', '59120', 'loos', 'France', '', '628811689', 0, 0, '', '0000-00-00 00:00:00', NULL),
(4, 'garcia.jennifer2903@gmail.com', '', '', 'Izan', ' Garcia', '0000-00-00', '', '21/101 rue breughel', '', '59650', 'villeneuve d\'ascq', 'France', '', '782058284', 0, 0, '', '0000-00-00 00:00:00', NULL),
(6, 'sylvie0059@hotmail.fr', '', '', 'Sylvie ', ' Henon', '0000-00-00', '248', 'rue de bruxelle', '', '59112', 'Annoeulin', 'France', '', '641609711', 0, 0, '', '0000-00-00 00:00:00', NULL),
(9, 'ali.doudou@yahoo./fr', '', '', 'Souhaïl', 'Abbouchi', '0000-00-00', '30b', 'rue de la motte', '', '59320', 'haubourdin', 'France', '', '659420289', 0, 0, '', '0000-00-00 00:00:00', NULL),
(13, 'lhomme.margaux@gmail.com', '', '', 'Téa', 'Accart', '0000-00-00', '8', 'rue guillaume d\'orange', '', '8600', 'givet', 'France', '', '662863039', 0, 0, '', '0000-00-00 00:00:00', NULL),
(14, 'coraliecharvet@hotmail.com', '', '', 'Charlie Garcon', 'Acciari', '0000-00-00', '76', 'rue du marche', '', '59000', 'lille', 'France', '', '645294137', 0, 0, '', '0000-00-00 00:00:00', NULL),
(15, 'kad59139@hotmail.fr', '', '', 'Khadija', 'Achikar', '0000-00-00', '', '12, Pierre et Marie curie, 59139, WATTIGNIES', '', '', '', 'France', '', '663271953', 0, 0, '', '0000-00-00 00:00:00', NULL),
(21, 'ophelie.adabra@gmail.com', '', '', 'Liam', 'Adabra', '0000-00-00', '', '371 rue de marquette, 59118, Wambrechies ', '', '', '', 'France', '', '618275304', 0, 0, '', '0000-00-00 00:00:00', NULL),
(25, 'sandry_9@hotmail.fr', '', '', 'Sandy', 'Afankous', '0000-00-00', '', '2 square pierre billon, 59120, LOOS', '', '', '', 'France', '', '785891812', 0, 0, '', '0000-00-00 00:00:00', NULL),
(26, 'ludivine.devries@yahoo.fr', '', '', 'Sohanne', 'Afankous De VRIES', '0000-00-00', '', '', '', '', '', 'France', '', '613978725', 0, 0, '', '0000-00-00 00:00:00', NULL),
(32, 'akaouch-med@hotmail.fr', '', '', 'Mohamed', 'Akaouch', '0000-00-00', '', '5 Rue De L’hirondelle, 59320, HALLENNES LEZ HAUBOURDIN', '', '', '', 'France', '', '615863823', 0, 0, '', '0000-00-00 00:00:00', NULL),
(33, 'bouf-akaouch@hotmail.fr', '', '', 'Yamina', 'Akaouch', '0000-00-00', '', '5rue de l hirondelle, 59320, HALLENNES LEZ HAUBOURDIN', '', '', '', 'France', '', '610090786', 0, 0, '', '0000-00-00 00:00:00', NULL),
(37, 'alberti.orlane@free.fr', '', '', 'Orlane', 'Alberti', '0000-00-00', '2', 'rue st exupery', '', '59320', 'emmerin', 'France', '', '670582544', 0, 0, '', '0000-00-00 00:00:00', NULL),
(38, 'horfee@gmail.com', '', '', 'Romain', 'Alexandre', '0000-00-00', '32', 'rue albert crepin', '', '59139', 'wattignies', 'France', '', '662934347', 0, 0, '', '0000-00-00 00:00:00', NULL),
(41, 'khadija54_@hotmail.com', '', '', 'Khadija', 'Alif', '0000-00-00', '', '15 Rue Carnot , 59147, GONDECOURT', '', '', '', 'France', '', '695142709', 0, 0, '', '0000-00-00 00:00:00', NULL),
(42, 'jbaghouz@gmail.com', '', '', 'Jamila', 'Alit', '0000-00-00', '6', 'rue camillecorot', '', '59120', 'loos', 'France', '', '769824155', 0, 0, '', '0000-00-00 00:00:00', NULL),
(46, 'emiliedesoutter@sfr.fr', '', '', 'Marius', 'Allender', '0000-00-00', '6', 'rue des chats', '', '59470', 'zegerscappel', 'France', '', '686444238', 0, 0, '', '0000-00-00 00:00:00', NULL),
(47, 'kallep@laposte.net', '', '', 'Karine', 'Allepaerts', '0000-00-00', '', '3 rue Albert Samain', '', '59320', 'hallennes les haubourdin', 'France', '', '634714832', 0, 0, '', '0000-00-00 00:00:00', NULL),
(48, 'bertilleurban@gmail.com', '', '', 'Oscar', 'Allix', '0000-00-00', '', '14 rue du general mangin, 59700, MARCQ EN BAROEUL', '', '', '', 'France', '', '615928189', 0, 0, '', '0000-00-00 00:00:00', NULL),
(50, 'aurelie.treille@yahoo.fr', '', '', 'Naël', 'Altinkaynak', '0000-00-00', '', '1 rue Édouard Lenglart , 59120, LOOS', '', '', '', 'France', '', '645929853', 0, 0, '', '0000-00-00 00:00:00', NULL),
(52, 'soraya.rebbache@hotmail.fr', '', '', 'Saïd', 'Amalou', '0000-00-00', '21', 'rue ernest renan', '', '59100', 'roubaix', 'France', '', '611103596', 0, 0, '', '0000-00-00 00:00:00', NULL),
(55, 'titile59@hotmail.fr', '', '', 'Naëlle', 'Amari Wonderzy', '0000-00-00', '13', 'rue anna gomis', '', '59810', 'lesquin', 'France', '', '618134222', 0, 0, '', '0000-00-00 00:00:00', NULL),
(56, 'ttauvry@laposte.net', '', '', 'Assia', 'Amhal Tauvry', '0000-00-00', '83', 'rue achille pinteau', '', '59136', 'wavrin', 'France', '', '679156520', 0, 0, '', '0000-00-00 00:00:00', NULL),
(57, 'amichotlola@gmail.com', '', '', 'Lola', 'Amichot', '0000-00-00', '', '1132 Route de Varye, 18230, ST DOULCHARD', '', '', '', 'France', '', '686084128', 0, 0, '', '0000-00-00 00:00:00', NULL),
(59, 'g.am@bdn.fr', '', '', 'Gaëtan', 'Ammeloot', '0000-00-00', '57', 'rue marcel henaux app01', '', '59700', 'Marcq en baroeul', 'France', '', '618355337', 0, 0, '', '0000-00-00 00:00:00', NULL),
(63, 'amragael@aol.com', '', '', 'Gaëlle ', 'Amrani', '0000-00-00', '', '26 rue de La République', '', '59496', 'Salomé', 'France', '', '610743805', 0, 0, '', '0000-00-00 00:00:00', NULL),
(64, 'leila-aklouche@live.fr', '', '', 'Leila', 'Ancelly', '0000-00-00', '', '', '', '', '', 'France', '', '767164193', 0, 0, '', '0000-00-00 00:00:00', NULL),
(71, 'anselin.alban@hotmail.fr', '', '', 'Alban ', 'Anselin', '0000-00-00', '', '421 rue Faidherbe', '', '59120', 'loos', 'France', '', '698960647', 0, 0, '', '0000-00-00 00:00:00', NULL),
(74, 'aurore.bournaud@gmail.com', '', '', 'Romane', 'Antoni Bournaud', '0000-00-00', '', '8 rue de Lannoy , 59800, Lille', '', '', '', 'France', '', '628231173', 0, 0, '', '0000-00-00 00:00:00', NULL),
(75, 'perrine1710@live.fr', '', '', 'Mahé', 'Anzolo', '0000-00-00', '12', 'rue de la liniere bat C31 ', '', '59155', 'faches thumesnil', 'France', '', '662840385', 0, 0, '', '0000-00-00 00:00:00', NULL),
(76, 'anna.morton.123@gmail.com', '', '', 'Robin', 'Arbore', '0000-00-00', '', '71 bis rue Jean Jaurès , 62138, DOUVRIN', '', '', '', 'France', '', '624883234', 0, 0, '', '0000-00-00 00:00:00', NULL),
(78, 'naima2706@hotmail.fr', '', '', 'Sofia', 'Asrir', '0000-00-00', '31', 'rue charle bodelaire', '', '62220', 'carvin', 'France', '', '777849207', 0, 0, '', '0000-00-00 00:00:00', NULL),
(81, 'florance.assom@gmail.com', '', '', 'Raphael', 'Assoum', '0000-00-00', '', '', '', '', '', 'France', '', '785492670', 0, 0, '', '0000-00-00 00:00:00', NULL),
(82, 'sevatonde@hotmail.fr', '', '', 'Louane', 'ATONDE', '0000-00-00', '11', 'allée marguerite yourcenar', '', '59120', 'loos', 'France', '', '614531401', 0, 0, '', '0000-00-00 00:00:00', NULL),
(91, 'guillemettem@hotmail.fr', '', '', 'Azélie', 'Auneau', '0000-00-00', '24', 'rue des hautes voies', '', '59700', 'Marcq en baroeul', 'France', '', '664696775', 0, 0, '', '0000-00-00 00:00:00', NULL),
(95, 'catherine.ayad@outlook.fr', '', '', 'Catherine', 'Ayad', '0000-00-00', '', '24 rue Henri carette, 59290, wasquehal', '', '', '', 'France', '', '630824753', 0, 0, '', '0000-00-00 00:00:00', NULL),
(96, 'soumaiaayoub3@outlook.fr', '', '', 'Abdelkader', 'Ayoub', '0000-00-00', '', '', '', '', '', 'France', '', '768276551', 0, 0, '', '0000-00-00 00:00:00', NULL),
(97, 'aicha.meskine2@gmail.com', '', '', 'Houria', 'Ayoub', '0000-00-00', '', '4 avenue du maréchal de lattre de tassigny , 59320, Haubourdin ', '', '', '', 'France', '', '661461923', 0, 0, '', '0000-00-00 00:00:00', NULL),
(99, 'a.celou59@hotmail.fr', '', '', 'Kais', 'Azdoud', '0000-00-00', '', '16/1 rue pierre et marie curie, 59139, Wattignies ', '', '', '', 'France', '', '768228632', 0, 0, '', '0000-00-00 00:00:00', NULL),
(102, 'cylia.1805@gmail.com', '', '', 'Chaim', 'Azziz', '0000-00-00', '', '', '', '', '', 'France', '', '776546946', 0, 0, '', '0000-00-00 00:00:00', NULL),
(113, 'philippe-bailleux@orange.fr', '', '', 'Christel', 'Bailleux', '0000-00-00', '64', 'rue bleriot', '', '59320', 'emmerin', 'France', '', '610878405', 0, 0, '', '0000-00-00 00:00:00', NULL),
(114, 'sonibail@hotmail.fr', '', '', 'Sonia', 'Bailloeuil', '0000-00-00', '275', 'avenue du marechal juin', '', '62400', 'bethune', 'France', '', '621025773', 0, 0, '', '0000-00-00 00:00:00', NULL),
(119, 'valentin.baldini.2015@gmail.com', '', '', 'Valentin', 'Baldini', '0000-00-00', '16', 'rue masséna', '', '59000', 'lille', 'France', '', '781618354', 0, 0, '', '0000-00-00 00:00:00', NULL),
(123, 'emmanuelle.bamps@gmail.com', '', '', 'Emmanuelle', 'Bamps', '0000-00-00', '50', 'rue albert samain', '', '59000', 'lille', 'France', '', '698095699', 0, 0, '', '0000-00-00 00:00:00', NULL),
(131, 'massezmaeva@gmail.com', '', '', 'Maeva', 'Barenne', '0000-00-00', '3', 'residence de la briqueterie', '', '59840', 'perenchies', 'France', '', '770109665', 0, 0, '', '0000-00-00 00:00:00', NULL),
(132, 'barennenicolas@gmail.com', '', '', 'Nicolas', 'Barenne', '0000-00-00', '3', 'residence de la briqueterie', '', '59840', 'perenchies', 'France', '', '621403736', 0, 0, '', '0000-00-00 00:00:00', NULL),
(136, 'r.barichard@hotmail.fr', '', '', 'Jean Louis', 'Barichard', '0000-00-00', '', '12 rue a watteau, 59120, Loos', '', '', '', 'France', '', '652436689', 0, 0, '', '0000-00-00 00:00:00', NULL),
(141, 'charlotte62970@gmail.com', '', '', 'Benji', 'Bartier', '0000-00-00', '', '77 RUE DE MURET, 62420, BILLY MONTIGNY', '', '', '', 'France', '', '770070176', 0, 0, '', '0000-00-00 00:00:00', NULL),
(150, 'stef-flo935@orange.fr', '', '', 'Clarisse', 'BAUDOT', '0000-00-00', '21', 'rue georges pompidou', '', '59810', 'lesquin', 'France', '', '698139722', 0, 0, '', '0000-00-00 00:00:00', NULL),
(153, 'pierre.baudour@gmail.com', '', '', 'Sara', 'Baudour', '0000-00-00', '12', 'rue malpart apt 32', '', '59800', 'lille', 'France', '', '781598238', 0, 0, '', '0000-00-00 00:00:00', NULL),
(156, 'm.prestat@gmail.com', '', '', 'Constance', 'Baudy', '0000-00-00', '12 bis', 'quai du wault', '', '59000', 'lillle', 'France', '', '687639450', 0, 0, '', '0000-00-00 00:00:00', NULL),
(169, 'lydie.intner@hotmail.fr', '', '', 'Simon', 'Baujon', '0000-00-00', '', '90 boulevarde la marne', '', '59420', 'mouvaux', 'France', '', '616746607', 0, 0, '', '0000-00-00 00:00:00', NULL),
(180, 'daphnee.soret@gmail.com', '', '', 'Guillaume', 'Beauvois Soret', '0000-00-00', '17', 'rue simons', '', '59320', 'hallennes les haubourdin', 'France', '', '661176480', 0, 0, '', '0000-00-00 00:00:00', NULL),
(181, 'capucine126@hotmail.fr', '', '', 'Lilas', 'Bec Lefevre', '0000-00-00', '', '5 ter rue du gros moulin , 62400, BETHUNE', '', '', '', 'France', '', '628545994', 0, 0, '', '0000-00-00 00:00:00', NULL),
(182, 'larocheaurelie@hotmail.fr', '', '', 'Maël', 'Becar', '0000-00-00', '', '14 RUE ERNEST DUJARDIN, 59290, WASQUEHAL', '', '', '', 'France', '', '622827968', 0, 0, '', '0000-00-00 00:00:00', NULL),
(183, 'auwen59@live.fr', '', '', 'Emmanuelle', 'Becquet', '0000-00-00', '', '', '', '', '', 'France', '', '', 0, 0, '', '0000-00-00 00:00:00', NULL),
(188, 'annesophie.becuwe@gmail.com', '', '', 'Anne Sophie', 'Becuwe', '0000-00-00', '', '296 rue pierre brossette, 59120, LOOS', '', '', '', 'France', '', '634215920', 0, 0, '', '0000-00-00 00:00:00', NULL),
(190, 'berenice.lecluyse@gmail.com', '', '', 'Salomé', 'Beerens', '0000-00-00', '', '94 rue de Beaumont , 59510, Hem', '', '', '', 'France', '', '669992331', 0, 0, '', '0000-00-00 00:00:00', NULL),
(191, 'lucien059@hotmail.fr', '', '', 'Lucien', 'Beghain', '0000-00-00', '4', 'residence des templiers', '', '59114', 'eecke', 'France', '', '663211968', 0, 0, '', '0000-00-00 00:00:00', NULL),
(192, 'tbeghyn@free.fr', '', '', 'Terence', 'Beghyn', '0000-00-00', '17', ' rue Jean de la Fontaine', '', '59320', 'haubourdin', 'France', '', '637277970', 0, 0, '', '0000-00-00 00:00:00', NULL),
(197, 'victoria.juliana@live.fr', '', '', 'Wassim', 'Belabbassi', '0000-00-00', '156/24', 'rue nationale', '', '59200', 'tourcoing', 'France', '', '629238136', 0, 0, '', '0000-00-00 00:00:00', NULL),
(198, 'malak.menouni@gmail.com', '', '', 'Ilian', 'Belervaque', '0000-00-00', '', '', '', '', '', 'France', '', '659269084', 0, 0, '', '0000-00-00 00:00:00', NULL),
(201, 'belguendouzdallal@gmail.com', '', '', 'Samy', 'Belguendouz', '0000-00-00', '', '20A rue simone de beauvoir , 59120, LOOS', '', '', '', 'France', '', '762156539', 0, 0, '', '0000-00-00 00:00:00', NULL),
(204, 'regnier.johanna@wanadoo.fr', '', '', 'Marilou', 'Bellegueulle', '0000-00-00', '', '15 rue Lefort, 59870, MARCHIENNES', '', '', '', 'France', '', '647294038', 0, 0, '', '0000-00-00 00:00:00', NULL),
(208, 'badra.amar@hotmail.fr', '', '', 'Zaynab', 'Ben Barka', '0000-00-00', '207', 'rue du long pot', '', '59800', 'lille', 'France', '', '699287727', 0, 0, '', '0000-00-00 00:00:00', NULL),
(210, 'j.branswyck@hotmail.fr', '', '', 'Elyas', 'Ben Miled', '0000-00-00', '10', 'rue du ferier app C51', '', '59000', 'lille', 'France', '', '658760997', 0, 0, '', '0000-00-00 00:00:00', NULL),
(215, 'sofiabenfrih@live.fr', '', '', 'Sofia', 'Benfrih', '0000-00-00', '', '', '', '', '', 'France', '', '610168160', 0, 0, '', '0000-00-00 00:00:00', NULL),
(228, 'bgxsophy@aol.com', '', '', 'Sophie', 'Bergougnoux', '0000-00-00', '', '710 rue Edouard Vaillant, 59184, Sainghin en Weppes', '', '', '', 'France', '', '664266070', 0, 0, '', '0000-00-00 00:00:00', NULL),
(229, 'cbernable@free.fr', '', '', 'Claire', 'Bernable', '0000-00-00', '15', 'rue germain delhaye', '', '59710', 'pont a marcq', 'France', '', '611905096', 0, 0, '', '0000-00-00 00:00:00', NULL),
(237, 'ptitdav100584@hotmail.com', '', '', 'David', 'Bernard', '0000-00-00', '', '26 ALLEE DE LA BECQUE, 59320, HAUBOURDIN', '', '', '', 'France', '', '786633871', 0, 0, '', '0000-00-00 00:00:00', NULL),
(238, 'jeanmichel.bernard602@sfr.fr', '', '', 'Jean-Michel', 'Bernard', '0000-00-00', '428', 'rue des boulant', '', '62840', 'neuve chapelle', 'France', '', '685514056', 0, 0, '', '0000-00-00 00:00:00', NULL),
(239, 'nathalie62@hotmail.fr', '', '', 'Martin', 'Bernard', '0000-00-00', '23', 'chausse brune haut', '', '62129', 'therouanne', 'France', '', '622421352', 0, 0, '', '0000-00-00 00:00:00', NULL),
(242, 'loulioulouliou@hotmail.com', '', '', 'Ludivine', 'BERSON', '0000-00-00', '287', 'rue du 8 mai 1945', '', '59113', 'seclin', 'France', '', '683168571', 0, 0, '', '0000-00-00 00:00:00', NULL),
(245, 'nicole.bertout@gmail.com', '', '', 'Nicole', 'Bertout', '0000-00-00', '', '15 A rue Paul Lafargue, 59100, ROUBAIX', '', '', '', 'France', '', '637355322', 0, 0, '', '0000-00-00 00:00:00', NULL),
(246, 'marion.goubel@gmail.com', '', '', 'Samuel', 'Bertran', '0000-00-00', '62', 'rue voltaire', '', '59139', 'wattignies', 'France', '', '665777870', 0, 0, '', '0000-00-00 00:00:00', NULL),
(254, 'anne.rubenstrunk@gmail.com', '', '', 'Simon', 'Bettan', '0000-00-00', '82', 'rue de la louviere', '', '59800', 'lille', 'France', '', '66059691', 0, 0, '', '0000-00-00 00:00:00', NULL),
(259, 'veroniquebeugnies59120@gmail.com', '', '', 'Veronique', 'Beugnies', '0000-00-00', '', 'A24 rue georges clemenceau 505, 59120, Loos', '', '', '', 'France', '', '670666520', 0, 0, '', '0000-00-00 00:00:00', NULL),
(263, 'virginie.wojcik@orange.fr', '', '', 'Louis', 'Beys', '0000-00-00', '5', 'rue des aubesieres', '', '59249', 'aubers', 'France', '', '674058286', 0, 0, '', '0000-00-00 00:00:00', NULL),
(266, 'lucile0181@gmail.com', '', '', 'Lucile', 'Bezé', '0000-00-00', '170', 'rue Faidherbe', '', '59120', 'loos', 'France', '', '785602307', 0, 0, '', '0000-00-00 00:00:00', NULL),
(269, 'lynedancing@gmail.com', '', '', 'Fary', 'Bibron', '0000-00-00', '', '57 rue saint Aubert, 62000, ARRAS', '', '', '', 'France', '', '634045538', 0, 0, '', '0000-00-00 00:00:00', NULL),
(272, 'mi.coutteure@gmail.com', '', '', 'Celeste', 'Bigny', '0000-00-00', '', '88 rue du petit pavé, 59879, Bouvignies', '', '', '', 'France', '', '689512570', 0, 0, '', '0000-00-00 00:00:00', NULL),
(273, 'm.billiau@outlook.com', '', '', 'Marie', 'Billiau', '0000-00-00', '', '', '', '', '', 'France', '', '625595615', 0, 0, '', '0000-00-00 00:00:00', NULL),
(278, 'amelie.blaevoet@outlook.fr', '', '', 'Amelie', 'Blaevoet Truy', '0000-00-00', '', '', '', '', '', 'France', '', '787957984', 0, 0, '', '0000-00-00 00:00:00', NULL),
(283, 'nim.annesophie@laposte.net', '', '', 'Baptiste', 'Blart Nimmegeers', '0000-00-00', '12', 'impasse du docteur fleming', '', '62221', 'noyelles sous lens', 'France', '', '614255365', 0, 0, '', '0000-00-00 00:00:00', NULL),
(284, 'maureen.roussel@hotmail.fr', '', '', 'Mahé', 'Blauwart', '0000-00-00', '', '19 rue de frémicourt, 62159, VAULX VRAUCOURT', '', '', '', 'France', '', '638219688', 0, 0, '', '0000-00-00 00:00:00', NULL),
(287, 'alexy.bleuzet@gmail.com', '', '', 'Alexy', 'BLEUZET', '0000-00-00', '3', 'rue paul lafargue', 'appt 16, tour carpeaux', '62300', 'lens', 'France', '', '650955303', 0, 0, '', '0000-00-00 00:00:00', NULL),
(289, 'bleuzet.edith@numericable.fr', '', '', 'Edith', 'BLEUZET', '0000-00-00', '16', 'impasse jean moulin', '', '62960', 'Courcelles', 'France', '', '661474663', 0, 0, '', '0000-00-00 00:00:00', NULL),
(292, 'sachaetseb@gmail.com', '', '', 'Cesar', 'Blin', '0000-00-00', '6', 'rue james hague', '', '59710', 'pont a marcq', 'France', '', '658694678', 0, 0, '', '0000-00-00 00:00:00', NULL),
(300, 'blondeaupriscilla@hotmail.com', '', '', 'Martin', 'Blondeau', '0000-00-00', '', '4 avenue de l’europe, 59320, HAUBOURDIN', '', '', '', 'France', '', '610532053', 0, 0, '', '0000-00-00 00:00:00', NULL),
(313, 'sandravermeesch@gmail.com', '', '', 'Gabrielle', 'Bocquet', '0000-00-00', '55', 'rue sadi carnot', '', '59350', 'saint andre lez lille', 'France', '', '675713566', 0, 0, '', '0000-00-00 00:00:00', NULL),
(314, 'sandrine.59@laposte.net', '', '', 'Lola', 'Bocquet', '0000-00-00', '10', 'rue conia', '', '59320', 'haubourdin', 'France', '', '668067925', 0, 0, '', '0000-00-00 00:00:00', NULL),
(319, 'camille_fillebeen@hotmail.com', '', '', 'Marceau', 'Bodele', '0000-00-00', '53', 'rue de touraine', '', '59112', 'Annoeulin', 'France', '', '610521825', 0, 0, '', '0000-00-00 00:00:00', NULL),
(320, 'l.delmotte@hotmail.fr', '', '', 'Agathe', 'Bodin', '0000-00-00', '', '1 Rue des Chataîgniers', '', '62138', 'auchy les mines', 'France', '', '624940773', 0, 0, '', '0000-00-00 00:00:00', NULL),
(321, 'cha07220@msn.com', '', '', 'Charlie Fille', 'Bodin', '0000-00-00', '28', 'ave du general de gaulle', '', '62440', 'harnes', 'France', '', '614532461', 0, 0, '', '0000-00-00 00:00:00', NULL),
(326, 'berengere.formet@gmail.com', '', '', 'Gaspard', 'Boissier', '0000-00-00', '101', 'rue auguste potié', '', '59320', 'haubourdin', 'France', '', '683970549', 0, 0, '', '0000-00-00 00:00:00', NULL),
(327, 'c.bouchart14@gmail.com', '', '', 'Elio', 'Boitrel', '0000-00-00', '33', 'avenue de l\'amitié', '', '59211', 'santes', 'France', '', '678083884', 0, 0, '', '0000-00-00 00:00:00', NULL),
(328, 'jeremy.boitrel@gmail.com', '', '', 'Jérémy', 'BOITREL', '0000-00-00', '107', 'rue foch', '', '59211', 'Saintes', 'France', '', '699597018', 0, 0, '', '0000-00-00 00:00:00', NULL),
(331, 'fabienne.bona@hotmail.fr', '', '', 'Fabienne', 'Bona', '0000-00-00', '67', 'rue de carpentra', '', '59320', 'secquedin', 'France', '', '686150854', 0, 0, '', '0000-00-00 00:00:00', NULL),
(338, 'elz.sbonnel@gmail.com', '', '', 'Sophie', 'Bonnel', '0000-00-00', '', '16 Rue Conia, 59320, HAUBOURDIN', '', '', '', 'France', '', '675015227', 0, 0, '', '0000-00-00 00:00:00', NULL),
(339, 'marthe-cochard@orange.fr', '', '', 'Agathe', 'Bonnier', '0000-00-00', '35', 'rue paul lafargue', '', '59120', 'loos', 'France', '', '669773416', 0, 0, '', '0000-00-00 00:00:00', NULL),
(340, 'marthe.cochard@gmail.com', '', '', 'Romy', 'Bonnier', '0000-00-00', '', '35 rue Paul lafargue', '', '59120', 'loos', 'France', '', '669773416', 0, 0, '', '0000-00-00 00:00:00', NULL),
(344, 'aurelieduchateau@orange.fr', '', '', 'Théo', 'Bonte', '0000-00-00', '25', 'rue gabriel peri', '', '59320', 'haubourdin', 'France', '', '608882450', 0, 0, '', '0000-00-00 00:00:00', NULL),
(354, 'celine.bossaert@gmail.com', '', '', 'Victor', 'BOSSAERT', '0000-00-00', '29', 'rue léon gambetta', '', '59320', 'haubourdin', 'France', '', '662567557', 0, 0, '', '0000-00-00 00:00:00', NULL),
(357, 'ameliepennel@wanadoo.fr', '', '', 'Maël', 'Bossu', '0000-00-00', '518', 'rue du general de gaulle', '', '62110', 'henin beaumont', 'France', '', '642442312', 0, 0, '', '0000-00-00 00:00:00', NULL),
(358, 'bot-emmanuelle@hotmail.fr', '', '', 'Emmanuelle', 'Bot', '0000-00-00', '1', 'rue joseph hentges', '', '59420', 'mouvaux', 'France', '', '628455306', 0, 0, '', '0000-00-00 00:00:00', NULL),
(362, 'jlanguillat@hotmail.com', '', '', 'Mia', 'Bouche Languillat', '0000-00-00', '50', 'rue de l\'eglise', '', '59390', 'toufflers', 'France', '', '612720563', 0, 0, '', '0000-00-00 00:00:00', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `acts`
--
ALTER TABLE `acts`
  ADD PRIMARY KEY (`invoice_id`,`appointment_id`),
  ADD KEY `IDX_6A10A6772989F1FD` (`invoice_id`),
  ADD KEY `IDX_6A10A677E5B533F9` (`appointment_id`);

--
-- Index pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6A41727AF270FD45` (`care_id`);

--
-- Index pour la table `cares`
--
ALTER TABLE `cares`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `childs`
--
ALTER TABLE `childs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_599EE433861B2665` (`parent1_id`),
  ADD KEY `IDX_599EE43394AE898B` (`parent2_id`);

--
-- Index pour la table `days_off`
--
ALTER TABLE `days_off`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `days_on`
--
ALTER TABLE `days_on`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT pour la table `cares`
--
ALTER TABLE `cares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `childs`
--
ALTER TABLE `childs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `days_off`
--
ALTER TABLE `days_off`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `days_on`
--
ALTER TABLE `days_on`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `acts`
--
ALTER TABLE `acts`
  ADD CONSTRAINT `FK_6A10A6772989F1FD` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  ADD CONSTRAINT `FK_6A10A677E5B533F9` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`);

--
-- Contraintes pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `FK_6A41727AF270FD45` FOREIGN KEY (`care_id`) REFERENCES `cares` (`id`);

--
-- Contraintes pour la table `childs`
--
ALTER TABLE `childs`
  ADD CONSTRAINT `FK_599EE433861B2665` FOREIGN KEY (`parent1_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_599EE43394AE898B` FOREIGN KEY (`parent2_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
