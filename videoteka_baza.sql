-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2026 at 10:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `videoteka_baza`
--

-- --------------------------------------------------------

--
-- Table structure for table `filmovi`
--

CREATE TABLE `filmovi` (
  `id` int(11) NOT NULL,
  `naslov` varchar(255) NOT NULL,
  `zanr` varchar(100) NOT NULL,
  `godina` int(4) NOT NULL,
  `trajanje` int(4) NOT NULL,
  `prosjecna_ocjena` decimal(3,1) DEFAULT 0.0,
  `slika` varchar(255) DEFAULT NULL,
  `zemlja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `filmovi`
--

INSERT INTO `filmovi` (`id`, `naslov`, `zanr`, `godina`, `trajanje`, `prosjecna_ocjena`, `slika`, `zemlja`) VALUES
(1, 'Matrix', 'SciFi', 1999, 120, 8.5, '', NULL),
(2, 'Forrest Gump', 'Drama', 1990, 180, 9.5, '', NULL),
(3, 'Hulk', 'SciFi', 2005, 100, 4.5, '', NULL),
(4, 'Spider Man', 'SciFi', 2002, 100, 9.5, '', 'USA');

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

CREATE TABLE `korisnici` (
  `id` int(11) NOT NULL,
  `korisnicko_ime` varchar(50) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `uloga` enum('korisnik','admin') DEFAULT 'korisnik'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`id`, `korisnicko_ime`, `lozinka`, `uloga`) VALUES
(1, 'Borna', '$2y$10$Lf/Bf7TK.UuYdV9tDev75unIHtHxHN6TsrLu6ooCZwZsKsSeRw21y', 'korisnik');

-- --------------------------------------------------------

--
-- Table structure for table `ocjene`
--

CREATE TABLE `ocjene` (
  `id` int(11) NOT NULL,
  `id_korisnik` int(11) NOT NULL,
  `id_slika` int(11) NOT NULL,
  `ocjena` int(11) DEFAULT NULL CHECK (`ocjena` >= 1 and `ocjena` <= 5),
  `vrijeme_ocjene` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ocjene`
--

INSERT INTO `ocjene` (`id`, `id_korisnik`, `id_slika`, `ocjena`, `vrijeme_ocjene`) VALUES
(1, 1, 1, 4, '2026-05-24 21:46:32'),
(3, 1, 2, 5, '2026-05-24 21:46:24'),
(5, 1, 5, 5, '2026-05-24 21:51:55');

-- --------------------------------------------------------

--
-- Table structure for table `slike`
--

CREATE TABLE `slike` (
  `id` int(11) NOT NULL,
  `naziv_datoteke` varchar(255) NOT NULL,
  `opis` text DEFAULT NULL,
  `putanja` varchar(255) NOT NULL,
  `izvor` varchar(50) DEFAULT 'lokalno'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slike`
--

INSERT INTO `slike` (`id`, `naziv_datoteke`, `opis`, `putanja`, `izvor`) VALUES
(1, 'Priroda', 'Prekrasna šuma', 'https://unsplash.it/900/?random=1', 'API'),
(2, 'Grad', 'Zalazak sunca u gradu', 'https://unsplash.it/900/?random=2', 'API'),
(3, 'More', 'Plaža i valovi', 'https://unsplash.it/900/?random=3', 'API'),
(4, 'Planine', 'Snježni vrhovi', 'https://unsplash.it/900/?random=4', 'API'),
(5, '1779659472_Snimka zaslona 2025-10-11 222038.png', 'macka', 'slike/1779659472_Snimka zaslona 2025-10-11 222038.png', 'lokalno'),
(6, '1779660090_Snimka zaslona 2025-10-12 161418.png', 'umorna macka', 'slike/1779660090_Snimka zaslona 2025-10-12 161418.png', 'lokalno');

-- --------------------------------------------------------

--
-- Table structure for table `zeljeni_filmovi`
--

CREATE TABLE `zeljeni_filmovi` (
  `id` int(11) NOT NULL,
  `korisnik_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zeljeni_filmovi`
--

INSERT INTO `zeljeni_filmovi` (`id`, `korisnik_id`, `film_id`) VALUES
(10, 1, 1),
(11, 1, 3),
(13, 1, 2),
(15, 1, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `filmovi`
--
ALTER TABLE `filmovi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `korisnicko_ime` (`korisnicko_ime`);

--
-- Indexes for table `ocjene`
--
ALTER TABLE `ocjene`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_korisnik` (`id_korisnik`,`id_slika`);

--
-- Indexes for table `slike`
--
ALTER TABLE `slike`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zeljeni_filmovi`
--
ALTER TABLE `zeljeni_filmovi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `korisnik_id` (`korisnik_id`),
  ADD KEY `film_id` (`film_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `filmovi`
--
ALTER TABLE `filmovi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `korisnici`
--
ALTER TABLE `korisnici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ocjene`
--
ALTER TABLE `ocjene`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `slike`
--
ALTER TABLE `slike`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `zeljeni_filmovi`
--
ALTER TABLE `zeljeni_filmovi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `zeljeni_filmovi`
--
ALTER TABLE `zeljeni_filmovi`
  ADD CONSTRAINT `zeljeni_filmovi_ibfk_1` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `zeljeni_filmovi_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `filmovi` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
