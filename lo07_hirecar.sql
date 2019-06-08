-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: mysql-lo07.alwaysdata.net
-- Generation Time: Jun 08, 2019 at 05:56 PM
-- Server version: 10.2.22-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lo07_hirecar`
--

-- --------------------------------------------------------

--
-- Table structure for table `airport`
--

CREATE TABLE `airport` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `lat` float UNSIGNED NOT NULL,
  `lng` float UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `airport`
--

INSERT INTO `airport` (`id`, `name`, `lat`, `lng`) VALUES
(1, 'Agen-La-Garenne', 44.17, 0.589722),
(2, 'Ajaccio-Napoleon-Bonaparte', 41.92, 8.8),
(3, 'Albi-Le-Sequestre', 43.9097, 2.12),
(4, 'Angers-Marce', 47.56, 0.31),
(5, 'Angouleme-Brie-Champniers', 45.7297, 0.22),
(6, 'Annecy-Meythet', 45.9297, 6.11),
(7, 'Aurillac', 44.8997, 2.41972),
(8, 'Auxerre-Branches', 47.7983, 3.56694),
(9, 'Avignon-Caumont', 43.9097, 4.9),
(10, 'Bastia-Poretta', 42.5497, 9.48),
(11, 'Beauvais-Tille', 49.45, 2.10972),
(12, 'Bergerac-Roumaniere', 44.82, 0.52),
(13, 'Beziers-Vias', 43.32, 3.35),
(15, 'Bordeaux-Merignac', 44.8297, 0.719722),
(16, 'Bourges', 47.06, 2.37972),
(18, 'Brive-Vallee-De-Dordogne-', 45.0411, 1.48306),
(19, 'Caen-Carpiquet', 49.17, 0.45),
(20, 'Cahors-Lalbenque', 44.4481, 1.44111),
(21, 'Calais-Dunkerque', 50.9478, 1.85639),
(22, 'Calvi-St-Catherine', 42.52, 8.78972),
(23, 'Cannes-Mandelieu', 43.5497, 6.95972),
(24, 'Carcassonne-Salvaza', 43.2197, 2.31),
(25, 'Castres-Mazamet', 43.5497, 2.29),
(26, 'Chalons-Vatry', 48.7733, 4.20611),
(27, 'Chambery-Aix-Les-Bains', 45.64, 5.87972),
(28, 'Chateauroux-Deols', 46.8597, 1.71972),
(30, 'Cholet-Le-Pontreau', 47.0797, 0.889722),
(31, 'Clermont-Ferrand-Clermont-Ferrand/Auvergne', 45.7897, 3.16),
(32, 'Colmar-Houssen', 48.1097, 7.36),
(33, 'Deauville-St-Gatien', 49.3597, 0.16),
(34, 'Dijon-Longvic', 47.27, 5.08972),
(36, 'Dole-Tavaux', 47.0397, 5.42972),
(37, 'Epinal-Mirecourt', 48.3297, 6.07),
(38, 'Figari-Sud-Corse', 41.5, 9.09972),
(39, 'Grenoble-Isere', 45.3597, 5.33),
(43, 'Laval-Entrammes', 48.03, 0.739722),
(44, 'Le-Havre-Octeville-Sur-Mer', 49.53, 0.09),
(45, 'Le-Mans-Arnage', 47.95, 0.2),
(46, 'Le-Puy-Loudes', 45.0797, 3.75972),
(47, 'Le-Touquet-Paris-Plage', 50.5097, 1.62972),
(48, 'Lille-Lesquin', 50.56, 3.08972),
(49, 'Limoges-Bellegarde', 45.8597, 1.17972),
(51, 'Lyon-Bron-Bron', 45.7297, 4.94),
(52, 'Lyon-Saint-Exupery', 45.7297, 5.08),
(53, 'Marseille-Provence', 43.4397, 5.20972),
(54, 'Megeve-Megeve', 45.8575, 6.61806),
(55, 'Metz-Nancy-Lorraine', 48.9797, 6.25),
(56, 'Montlucon-Gueret', 46.2297, 2.35972),
(57, 'Montpellier-Mediterranee', 43.5797, 3.95972),
(59, 'Bale-Mulhouse', 47.59, 7.53),
(61, 'Nice-Cote-D\'Azur', 43.67, 7.20972),
(62, 'Nimes-Garons-', 43.7597, 4.41972),
(63, 'Niort-Souche', 46.31, 0.39),
(64, 'Paris-Charles-De-Gaulle', 49.0097, 2.55),
(65, 'Paris-Issy-Les-Moulineaux', 48.82, 2.27972),
(66, 'Paris-Le-Bourget', 48.9697, 2.43972),
(67, 'Paris-Orly', 48.7197, 2.37972),
(68, 'Pau-Pyrenees', 43.38, 0.42),
(69, 'Perigueux-Bassillac', 45.2, 0.819722),
(70, 'Perpignan-Rivesaltes', 42.74, 2.87),
(71, 'Poitiers-Biard', 46.59, 0.31),
(72, 'Propriano', 41.6597, 8.89),
(75, 'Roanne-Renaison', 46.0497, 4),
(76, 'Rochefort-St-Agnant', 45.89, 0.979722),
(77, 'Rodez-Marcillac', 44.4097, 2.47972),
(78, 'Rouen-Vallee-De-Seine', 49.39, 1.17972),
(80, 'Saint-Etienne-Boutheon', 45.53, 4.3),
(82, 'Strasbourg-Entzheim', 48.5397, 7.62972),
(83, 'Tarbes-Lourdes-Pyrenees', 43.1897, 0),
(84, 'Hyeres-Le-Palyvestre', 43.1, 6.15),
(85, 'Toulouse-Blagnac', 43.63, 1.37),
(86, 'Tours-Val-De-Loire', 47.4297, 0.719722),
(87, 'Troyes-Barberey', 48.2972, 4.07417),
(88, 'Valence-Chabeuil', 44.92, 4.96972),
(89, 'Valenciennes-Denain', 50.32, 3.47),
(90, 'Vichy-Charmeil', 46.17, 3.4);

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `doors` int(11) DEFAULT NULL,
  `owner_id` smallint(5) UNSIGNED DEFAULT NULL,
  `gearbox_id` smallint(5) UNSIGNED DEFAULT NULL,
  `fuel_id` smallint(5) UNSIGNED DEFAULT NULL,
  `price_per_day` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`id`, `model`, `seats`, `doors`, `owner_id`, `gearbox_id`, `fuel_id`, `price_per_day`) VALUES
(1, 'Fiat 500', 5, 3, 10, 2, 1, 14),
(3, 'Fiat Punto', 5, 5, 9, 2, 1, 10),
(5, 'Peugeot 207', 5, 3, 7, 2, 1, 10),
(6, 'Peugeot 407 ', 5, 4, 7, 1, 2, 10),
(7, 'Opel Meriva', 5, 5, 6, 2, 2, 10),
(8, 'Opel Astra', 5, 5, 4, 2, 2, 12.5),
(9, 'Renault Scenic 4', 5, 5, 3, 2, 2, 10),
(10, 'Renault Espace 5', 5, 5, 2, 1, 2, 10),
(11, 'Suzuki Swift 2013', 5, 5, 12, 2, 2, 120.5),
(20, 'Fiat Punto Evo 2012', 1, 1, 1, 1, 1, 1),
(22, 'Fiat 500', 5, 3, 13, 2, 1, 10.45),
(35, 'Renault Clio 4', 5, 3, 13, 2, 1, 6.5),
(67, 'Lamborghini Urus', 2, 3, 14, 1, 1, 40),
(103, 'BMW Z4', 2, 2, 15, 2, 1, 300),
(104, 'Pagani Huayra', 2, 2, 15, 2, 1, 300),
(105, 'Tesla Model S', 5, 5, 13, 1, 4, 130.99),
(119, 'Tesla Model 3', 5, 5, 1, 1, 4, 199),
(128, 'Citroën C3', 5, 5, 10, 2, 1, 8.5),
(132, 'Volkswagen Coccinelle 2018', 4, 3, 153, 2, 2, 20.5);

-- --------------------------------------------------------

--
-- Table structure for table `fuel`
--

CREATE TABLE `fuel` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fuel`
--

INSERT INTO `fuel` (`id`, `type`) VALUES
(1, 'Essence'),
(2, 'Diesel'),
(3, 'GPL'),
(4, 'Électrique'),
(5, 'Hybride');

-- --------------------------------------------------------

--
-- Table structure for table `gearbox`
--

CREATE TABLE `gearbox` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gearbox`
--

INSERT INTO `gearbox` (`id`, `type`) VALUES
(1, 'Automatique'),
(2, 'Manuelle');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `type`) VALUES
(1, 'Hirecar'),
(2, 'Google'),
(3, 'Facebook');

-- --------------------------------------------------------

--
-- Table structure for table `parking_lot`
--

CREATE TABLE `parking_lot` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `label` varchar(60) DEFAULT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `price_per_day` int(11) DEFAULT NULL,
  `airport_id` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parking_lot`
--

INSERT INTO `parking_lot` (`id`, `label`, `lat`, `lng`, `capacity`, `price_per_day`, `airport_id`) VALUES
(1, 'Paris Charles de Gaulle - Terminal D1', 49.0013, 2.54, 45, 12, 64),
(2, 'Paris Charles de Gaulle - Terminal E2', 49.023, 2.6, 25, 11, 64),
(4, 'Colmar-Houssen - Parking A1', 48.1094, 7.36204, 80, 12, 32),
(5, 'Colmar-Houssen - Parking B3', 48.1088, 7.36211, 30, 12, 32),
(8, 'Paris Charles de Gaulle - Terminal D3', 49.006, 2.58, 64, 13, 64);

-- --------------------------------------------------------

--
-- Table structure for table `rent_car`
--

CREATE TABLE `rent_car` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `user_id` smallint(5) UNSIGNED DEFAULT NULL,
  `parking_spot_id` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rent_car`
--

INSERT INTO `rent_car` (`id`, `start_date`, `end_date`, `user_id`, `parking_spot_id`) VALUES
(3, '2019-05-31 00:00:00', '2019-06-02 00:00:00', 13, 24),
(4, '2019-06-05 00:00:00', '2019-06-09 00:00:00', 13, 25);

-- --------------------------------------------------------

--
-- Table structure for table `rent_parking_spot`
--

CREATE TABLE `rent_parking_spot` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `car_id` smallint(5) UNSIGNED DEFAULT NULL,
  `parking_lot_id` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rent_parking_spot`
--

INSERT INTO `rent_parking_spot` (`id`, `start_date`, `end_date`, `car_id`, `parking_lot_id`) VALUES
(24, '2019-05-30 00:00:00', '2019-06-07 00:00:00', 1, 2),
(25, '2019-06-05 00:00:00', '2019-06-12 00:00:00', 1, 2),
(35, '2019-06-01 08:00:00', '2019-06-14 12:30:00', 119, 5),
(36, '2019-05-01 08:00:00', '2019-05-22 11:11:00', 20, 4),
(37, '2019-05-01 06:30:00', '2019-05-23 11:20:00', 20, 4),
(38, '2019-05-01 08:40:00', '2019-05-14 13:30:00', 119, 1),
(45, '2019-06-01 00:00:00', '2019-06-21 00:00:00', 20, 1),
(46, '2019-06-02 10:00:00', '2019-06-12 10:00:00', 105, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `password` char(64) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `login_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `phone`, `password`, `admin`, `login_id`) VALUES
(1, 'Nathan', 'Soufflet', 'nathan.soufflet@utt.fr', '0643232423', '$2a$10$5p/XjRv1Kw9OIqzg4t186e8DTwDxMtppR.8l74idnbhB3IHBV.a86', 1, 1),
(2, 'Lison', 'Meyer', 'lison.meyer@example.com', '0570010553', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(3, 'Louison', 'Petit', 'louison.petit@example.com', '0570345942', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(4, 'Dorian', 'Bertrand', 'dorian.bertrand@example.com', '0368303035', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(6, 'Maëline', 'Berger', 'maëline.berger@example.com', '0382078547', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(7, 'Nora', 'Hubert', 'nora.hubert@example.com', '0317350181', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(8, 'Juliette', 'Colin', 'juliette.colin@example.com', '0576391830', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(9, 'Lucy', 'Schmitt', 'lucy.schmitt@example.com', '0426595951', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(10, 'Charline', 'Dumont', 'charline.dumont@example.com', '0511808598', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(11, 'Léonie', 'Giraud', 'léonie.giraud@example.com', '0185094248', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(12, 'Farah', 'Hadjoudj', 'farah.hadjoudj@utt.fr', '0643546567', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(13, 'Nathalie', 'La Chipours', 'nathalie.zhang@utt.fr', '0214343254', '$2a$10$5p/XjRv1Kw9OIqzg4t186eBMfo.3JXzZl4a9Fk9qZpC4MGwPzg.aq', 1, 1),
(14, 'Elena', 'Thieu', 'elena.thieu@utt.fr', '0643546567', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(15, 'Guillaume', 'Gilles', 'guillaume.gilles@utt.fr', '0643546567', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(47, 'Marty', 'McFly', 'marty@delorean.com', '0123456789', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(49, 'Dark', 'Vador', 'dark.vador@utt.fr', '0523454342', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(125, 'Olivia', 'Dupond', 'olivia.dupond@utt.fr', '0324353456', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(129, 'Albert', 'Einstein', 'albert.einstein@utt.fr', '3141592653', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(134, 'Chloé', 'Chamaillard', 'chloe.chamaillard@utt.fr', '0124543676', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0, 1),
(153, 'Nathalie', 'Zhang', 'mlle.zhang.nathalie@gmail.com', NULL, NULL, 0, 2),
(154, 'Nathalie', 'Zhang', 'mlle.zhang.nathalie@gmail.com', NULL, NULL, 0, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `airport`
--
ALTER TABLE `airport`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `gearbox_id` (`gearbox_id`),
  ADD KEY `fuel_id` (`fuel_id`);

--
-- Indexes for table `fuel`
--
ALTER TABLE `fuel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gearbox`
--
ALTER TABLE `gearbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parking_lot`
--
ALTER TABLE `parking_lot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `airport_id` (`airport_id`);

--
-- Indexes for table `rent_car`
--
ALTER TABLE `rent_car`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parking_spot_id` (`parking_spot_id`);

--
-- Indexes for table `rent_parking_spot`
--
ALTER TABLE `rent_parking_spot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `parking_lot_id` (`parking_lot_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_per_social_media` (`email`,`login_id`),
  ADD KEY `login_id` (`login_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `airport`
--
ALTER TABLE `airport`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `fuel`
--
ALTER TABLE `fuel`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gearbox`
--
ALTER TABLE `gearbox`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `parking_lot`
--
ALTER TABLE `parking_lot`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rent_car`
--
ALTER TABLE `rent_car`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rent_parking_spot`
--
ALTER TABLE `rent_parking_spot`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `fk_fuel_id` FOREIGN KEY (`fuel_id`) REFERENCES `fuel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gearbox_id` FOREIGN KEY (`gearbox_id`) REFERENCES `gearbox` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_owner_id` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `parking_lot`
--
ALTER TABLE `parking_lot`
  ADD CONSTRAINT `fk_airport_id` FOREIGN KEY (`airport_id`) REFERENCES `airport` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rent_car`
--
ALTER TABLE `rent_car`
  ADD CONSTRAINT `fk_parking_spot_id` FOREIGN KEY (`parking_spot_id`) REFERENCES `rent_parking_spot` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rent_parking_spot`
--
ALTER TABLE `rent_parking_spot`
  ADD CONSTRAINT `fk_car_id` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_parking_lot_id` FOREIGN KEY (`parking_lot_id`) REFERENCES `parking_lot` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_login_id` FOREIGN KEY (`login_id`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
