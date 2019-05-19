-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: mysql-lo07.alwaysdata.net
-- Generation Time: May 19, 2019 at 11:24 PM
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
(1, 'Agen-La-Garenne', 44.1012, 0.3523),
(2, 'Ajaccio-Napoleon-Bonaparte', 41.5512, 8.48),
(3, 'Albi-Le-Sequestre', 43.5435, 2.712),
(5, 'Angouleme-Brie-Champniers', 45.4347, 0.1312),
(6, 'Annecy-Meythet', 45.5547, 6.636),
(7, 'Aurillac', 44.5359, 2.2511),
(8, 'Auxerre-Branches', 47.4754, 3.341),
(9, 'Avignon-Caumont', 43.5435, 4.54),
(10, 'Bastia-Poretta', 42.3259, 9.2848),
(11, 'Beauvais-Tille', 49.27, 2.635),
(12, 'Bergerac-Roumaniere', 44.4912, 0.3112),
(13, 'Beziers-Vias', 43.1912, 3.21),
(16, 'Bourges', 47.336, 2.2247),
(18, 'Brive-Vallee-De-Dordogne-', 45.0228, 1.2859),
(20, 'Cahors-Lalbenque', 44.2653, 1.2628),
(21, 'Calais-Dunkerque', 50.5652, 1.5123),
(22, 'Calvi-St-Catherine', 42.3112, 8.4723),
(23, 'Cannes-Mandelieu', 43.3259, 6.5735),
(24, 'Carcassonne-Salvaza', 43.1311, 2.1836),
(25, 'Castres-Mazamet', 43.3259, 2.1724),
(26, 'Chalons-Vatry', 48.4624, 4.1222),
(27, 'Chambery-Aix-Les-Bains', 45.3824, 5.5247),
(28, 'Chateauroux-Deols', 46.5135, 1.4311),
(31, 'Clermont-Ferrand-Clermont-Ferrand/Auvergne', 45.4723, 3.936),
(32, 'Colmar-Houssen', 48.635, 7.2136),
(33, 'Deauville-St-Gatien', 49.2135, 0.936),
(34, 'Dijon-Longvic', 47.1612, 5.523),
(36, 'Dole-Tavaux', 47.223, 5.2547),
(37, 'Epinal-Mirecourt', 48.1947, 6.412),
(38, 'Figari-Sud-Corse', 41.3, 9.559),
(39, 'Grenoble-Isere', 45.2135, 5.1948),
(44, 'Le-Havre-Octeville-Sur-Mer', 49.3148, 0.524),
(45, 'Le-Mans-Arnage', 47.57, 0.12),
(46, 'Le-Puy-Loudes', 45.447, 3.4535),
(47, 'Le-Touquet-Paris-Plage', 50.3035, 1.3747),
(48, 'Lille-Lesquin', 50.3336, 3.523),
(49, 'Limoges-Bellegarde', 45.5135, 1.1047),
(51, 'Lyon-Bron-Bron', 45.4347, 4.5624),
(52, 'Lyon-Saint-Exupery', 45.4347, 5.448),
(53, 'Marseille-Provence', 43.2623, 5.1235),
(54, 'Megeve-Megeve', 45.5127, 6.375),
(55, 'Metz-Nancy-Lorraine', 48.5847, 6.15),
(56, 'Montlucon-Gueret', 46.1347, 2.2135),
(57, 'Montpellier-Mediterranee', 43.3447, 3.5735),
(59, 'Bale-Mulhouse', 47.3524, 7.3148),
(61, 'Nice-Cote-D\'Azur', 43.4012, 7.1235),
(62, 'Nimes-Garons-', 43.4535, 4.2511),
(64, 'Paris-Charles-De-Gaulle', 49.035, 2.33),
(65, 'Paris-Issy-Les-Moulineaux', 48.4912, 2.1647),
(66, 'Paris-Le-Bourget', 48.5811, 2.2623),
(67, 'Paris-Orly', 48.4311, 2.2247),
(69, 'Perigueux-Bassillac', 45.12, 0.4911),
(70, 'Perpignan-Rivesaltes', 42.4424, 2.5212),
(71, 'Poitiers-Biard', 46.3524, 0.1836),
(72, 'Propriano', 41.3935, 8.5324),
(75, 'Roanne-Renaison', 46.259, 4),
(77, 'Rodez-Marcillac', 44.2435, 2.2847),
(78, 'Rouen-Vallee-De-Seine', 49.2324, 1.1047),
(80, 'Saint-Etienne-Boutheon', 45.3148, 4.18),
(82, 'Strasbourg-Entzheim', 48.3223, 7.3747),
(83, 'Tarbes-Lourdes-Pyrenees', 43.1123, 0),
(84, 'Hyeres-Le-Palyvestre', 43.6, 6.9),
(85, 'Toulouse-Blagnac', 43.3748, 1.2212),
(86, 'Tours-Val-De-Loire', 47.2547, 0.4311),
(87, 'Troyes-Barberey', 48.175, 4.427),
(88, 'Valence-Chabeuil', 44.5512, 4.5811),
(89, 'Valenciennes-Denain', 50.1912, 3.2812),
(90, 'Vichy-Charmeil', 46.1012, 3.24);

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `nb_places` int(11) DEFAULT NULL,
  `nb_doors` int(11) DEFAULT NULL,
  `owner_id` smallint(5) UNSIGNED DEFAULT NULL,
  `gearbox_id` smallint(5) UNSIGNED DEFAULT NULL,
  `fuel_id` smallint(5) UNSIGNED DEFAULT NULL,
  `price_per_day` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`id`, `model`, `nb_places`, `nb_doors`, `owner_id`, `gearbox_id`, `fuel_id`, `price_per_day`) VALUES
(1, 'Fiat 500', 5, 3, 10, 2, 1, NULL),
(2, 'Renault Clio 4', 5, 5, 5, 2, 2, 10.5),
(3, 'Fiat Punto', 5, 5, 9, 2, 1, NULL),
(5, 'Peugeot 207', 5, 3, 7, 2, 1, NULL),
(6, 'Peugeot 407 ', 5, 4, 7, 1, 2, NULL),
(7, 'Opel Meriva', 5, 5, 6, 2, 2, NULL),
(8, 'Opel Astra', 5, 5, 4, 2, 2, 12.5),
(9, 'Renault Scenic 4', 5, 5, 3, 2, 2, NULL),
(10, 'Renault Espace 5', 5, 5, 2, 1, 2, NULL),
(11, 'Suzuki S Cross', 5, 5, 12, 2, 2, 9.5),
(20, 'Fiat Punto Evo 2012', 5, 3, 1, 2, 1, 1);

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
(1, 'automatique'),
(2, 'manuelle');

-- --------------------------------------------------------

--
-- Table structure for table `parking_lot`
--

CREATE TABLE `parking_lot` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `label` varchar(60) DEFAULT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `nb_places` int(11) DEFAULT NULL,
  `price_per_day` int(11) DEFAULT NULL,
  `airport_id` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parking_lot`
--

INSERT INTO `parking_lot` (`id`, `label`, `lat`, `lng`, `nb_places`, `price_per_day`, `airport_id`) VALUES
(1, 'Paris Charles de Gaulle - Terminal D1', 49.032, 2.31, 45, 12, 64),
(2, 'Paris Charles de Gaulle - Terminal E2', 49.031, 2.32, 25, 11, 64),
(4, 'Colmar-Houssen - Parking A1', 48.633, 7.2134, 80, 12, 32),
(5, 'Colmar-Houssen - Parking B3', 48.632, 7.2133, 30, 12, 32);

-- --------------------------------------------------------

--
-- Table structure for table `rent_car`
--

CREATE TABLE `rent_car` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `user_id` smallint(5) UNSIGNED DEFAULT NULL,
  `parking_spot_id` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rent_car`
--

INSERT INTO `rent_car` (`id`, `start_date`, `end_date`, `user_id`, `parking_spot_id`) VALUES
(1, '2019-04-28', '2019-04-29', 6, 2),
(2, '2019-04-19', '2019-04-21', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rent_parking_spot`
--

CREATE TABLE `rent_parking_spot` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `car_id` smallint(5) UNSIGNED DEFAULT NULL,
  `parking_lot_id` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rent_parking_spot`
--

INSERT INTO `rent_parking_spot` (`id`, `start_date`, `end_date`, `car_id`, `parking_lot_id`) VALUES
(1, '2019-04-22', '2019-04-29', 2, 1),
(2, '2019-04-30', '2019-05-04', 8, 2);

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
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `phone`, `password`, `admin`) VALUES
(1, 'Nathan', 'Soufflet', 'nathan.soufflet@utt.fr', '0643232423', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 1),
(2, 'Lison', 'Meyer', 'lison.meyer@example.com', '0570010553', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(3, 'Louison', 'Petit', 'louison.petit@example.com', '0570345942', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(4, 'Dorian', 'Bertrand', 'dorian.bertrand@example.com', '0368303035', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(5, 'Lorenzo', 'Fournier', 'lorenzo.fournier@example.com', '0312365498', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(6, 'Maëline', 'Berger', 'maëline.berger@example.com', '0382078547', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(7, 'Nora', 'Hubert', 'nora.hubert@example.com', '0317350181', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(8, 'Juliette', 'Colin', 'juliette.colin@example.com', '0576391830', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(9, 'Lucy', 'Schmitt', 'lucy.schmitt@example.com', '0426595951', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(10, 'Charline', 'Dumont', 'charline.dumont@example.com', '0511808598', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(11, 'Léonie', 'Giraud', 'léonie.giraud@example.com', '0185094248', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(12, 'Farah', 'Hadjoudj', 'farah.hadjoudj@utt.fr', '0643546567', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(13, 'Nathalie', 'Zhang', 'nathalie.zhang@utt.fr', '0643546567', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 1),
(14, 'Elena', 'Thieu', 'elena.thieu@utt.fr', '0643546567', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(15, 'Guillaume', 'Gilles', 'guillaume.gilles@utt.fr', '0643546567', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(16, 'Michelle', 'Taylor', 'michelle.taylor@gmail.com', '0643546567', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(47, 'Marty', 'McFly', 'marty@delorean.com', '0123456789', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(49, 'Dark', 'Vador', 'dark.vador@utt.fr', '0523454342', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(67, 'Meline', 'Hong', 'meline.hong@utt.fr', '0325423456', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(69, 'Tom', 'Olivier', 'tom.olivier@utt.fr', '0643546567', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(72, 'Myriam', 'Martin', 'myriam.martin@gmail.com', '0532423445', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0),
(120, 'Ronald', 'Michel', 'ronald.michel@utt.fr', '0324534545', '$2a$10$5p/XjRv1Kw9OIqzg4t186eV1UxpYxFTyXR4KZmABZaxV/.QlAscNe', 0);

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
  ADD UNIQUE KEY `email` (`email`) USING BTREE;

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
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
-- AUTO_INCREMENT for table `parking_lot`
--
ALTER TABLE `parking_lot`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rent_car`
--
ALTER TABLE `rent_car`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rent_parking_spot`
--
ALTER TABLE `rent_parking_spot`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
