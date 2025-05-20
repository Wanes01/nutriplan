-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 05:29 PM
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
-- Database: `nutriplan`
--
CREATE DATABASE IF NOT EXISTS `nutriplan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `nutriplan`;

-- --------------------------------------------------------

--
-- Table structure for table `amministratori`
--

CREATE TABLE `amministratori` (
  `nickname` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amministratori`
--

INSERT INTO `amministratori` (`nickname`, `nome`, `cognome`, `password`) VALUES
('wanes', 'Emir Wanes', 'Aouioua', 'password123');

-- --------------------------------------------------------

--
-- Table structure for table `composizioni`
--

CREATE TABLE `composizioni` (
  `nomeDieta` varchar(100) NOT NULL,
  `nicknameAutore` varchar(50) NOT NULL,
  `titolo` varchar(100) NOT NULL,
  `nicknameEditore` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `composizioni`
--

INSERT INTO `composizioni` (`nomeDieta`, `nicknameAutore`, `titolo`, `nicknameEditore`) VALUES
('Lunedì', 'tulipano02', 'Hamburger di manzo e patate', 'muk'),
('Lunedì', 'tulipano02', 'Insalata di ceci e pomodori', 'tulipano02'),
('Lunedì', 'tulipano02', 'Insalata di farro con pomodori e spinaci', 'tulipano02'),
('Lunedì', 'tulipano02', 'Pane dolce al cacao', 'muk'),
('Sabato', 'brus', 'Frittelle dolci alle mele', 'brus'),
('Sabato', 'brus', 'Polpette di fagioli cannellini e pane', 'tulipano02'),
('Sabato', 'brus', 'Salmone con spinaci e riso', 'tulipano02'),
('Sabato', 'brus', 'Zuppa rustica di legumi', 'brus'),
('Sabato', 'tulipano02', 'Insalata di ceci e pomodori', 'tulipano02'),
('Sabato', 'tulipano02', 'Pane dolce al cacao', 'muk'),
('Sabato', 'tulipano02', 'Pollo alla crema di parmigiano', 'brus'),
('Sabato', 'tulipano02', 'Salmone con spinaci e riso', 'tulipano02'),
('Venerdí palestra', 'brus', 'Zuppa rustica di legumi', 'brus');

-- --------------------------------------------------------

--
-- Table structure for table `diete`
--

CREATE TABLE `diete` (
  `nome` varchar(100) NOT NULL,
  `nicknameAutore` varchar(50) NOT NULL,
  `kcalDieta` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diete`
--

INSERT INTO `diete` (`nome`, `nicknameAutore`, `kcalDieta`) VALUES
('Lunedì', 'tulipano02', 1620.23),
('Sabato', 'brus', 2124.95),
('Sabato', 'tulipano02', 1964.81),
('Venerdí palestra', 'brus', 607.90);

-- --------------------------------------------------------

--
-- Table structure for table `infrazioni`
--

CREATE TABLE `infrazioni` (
  `nicknameAmministratore` varchar(50) NOT NULL,
  `nicknameValutatore` varchar(50) NOT NULL,
  `titolo` varchar(100) NOT NULL,
  `nicknameEditore` varchar(50) NOT NULL,
  `motivazione` text NOT NULL,
  `dataOra` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `infrazioni`
--

INSERT INTO `infrazioni` (`nicknameAmministratore`, `nicknameValutatore`, `titolo`, `nicknameEditore`, `motivazione`, `dataOra`) VALUES
('wanes', 'muk', 'Insalata di ceci e pomodori', 'tulipano02', 'inadequate', '2025-05-20 17:04:49'),
('wanes', 'muk', 'Parmigiana leggera di zucchine', 'brus', 'offensive', '2025-05-20 17:03:56'),
('wanes', 'muk', 'Pollo alla crema di parmigiano', 'brus', 'offensive', '2025-05-20 17:04:13'),
('wanes', 'muk', 'Polpette di fagioli cannellini e pane', 'tulipano02', 'offensive', '2025-05-20 17:04:30'),
('wanes', 'muk', 'Risotto con spinaci e parmigiano', 'tulipano02', 'inadequate', '2025-05-20 17:05:17');

-- --------------------------------------------------------

--
-- Table structure for table `ingredienti`
--

CREATE TABLE `ingredienti` (
  `nome` varchar(100) NOT NULL,
  `costo` decimal(6,2) NOT NULL,
  `unitaMisura` varchar(10) NOT NULL,
  `kcal` decimal(6,2) NOT NULL,
  `proteine` decimal(6,2) NOT NULL,
  `carboidrati` decimal(6,2) NOT NULL,
  `grassiInsaturi` decimal(6,2) NOT NULL,
  `grassiSaturi` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredienti`
--

INSERT INTO `ingredienti` (`nome`, `costo`, `unitaMisura`, `kcal`, `proteine`, `carboidrati`, `grassiInsaturi`, `grassiSaturi`) VALUES
('Arance', 0.30, 'g', 47.00, 0.90, 12.00, 0.10, 0.00),
('Burro', 1.45, 'g', 758.00, 0.60, 0.70, 21.80, 54.00),
('Cacao amaro in polvere', 2.00, 'g', 228.00, 19.60, 58.50, 6.00, 3.20),
('Ceci', 0.60, 'g', 360.00, 20.50, 61.00, 3.20, 0.60),
('Cioccolato fondente', 1.80, 'g', 598.00, 7.80, 45.90, 15.50, 18.50),
('Fagioli cannellini secchi', 0.70, 'g', 333.00, 23.40, 60.00, 0.80, 0.20),
('Farina di grano tenero 00', 0.18, 'g', 360.00, 9.20, 76.50, 0.30, 0.20),
('Farro', 0.60, 'g', 340.00, 15.00, 70.00, 1.50, 0.40),
('Filetto di manzo', 3.80, 'g', 198.00, 26.40, 0.00, 2.50, 1.90),
('Lenticchie secche', 0.65, 'g', 325.00, 25.80, 52.00, 0.90, 0.30),
('Lievito in polvere per dolci', 1.50, 'g', 133.00, 0.00, 47.00, 0.00, 0.00),
('Mele', 0.35, 'g', 52.00, 0.30, 14.00, 0.10, 0.00),
('Olio extravergine di oliva', 1.20, 'ml', 899.00, 0.00, 0.00, 73.00, 14.00),
('Pane comune', 0.35, 'g', 275.00, 8.60, 49.00, 0.80, 0.20),
('Panna fresca', 1.10, 'ml', 330.00, 2.40, 3.40, 12.00, 19.00),
('Parmigiano Reggiano DOP', 2.40, 'g', 392.00, 33.00, 0.00, 8.50, 17.50),
('Pasta di semola', 0.28, 'g', 371.00, 12.50, 75.20, 0.60, 0.30),
('Patate', 0.25, 'g', 85.00, 2.00, 17.50, 0.10, 0.00),
('Petto di pollo', 1.65, 'g', 165.00, 31.00, 0.00, 1.20, 0.30),
('Pomodori freschi', 0.40, 'g', 18.00, 0.90, 3.90, 0.10, 0.00),
('Prosciutto crudo', 4.20, 'g', 290.00, 25.80, 0.00, 10.80, 8.40),
('Riso Carnaroli', 0.45, 'g', 358.00, 6.70, 80.40, 0.50, 0.10),
('Salmone fresco', 2.95, 'g', 208.00, 20.50, 0.00, 8.10, 3.10),
('Spinaci freschi', 0.65, 'g', 23.00, 2.90, 3.60, 0.20, 0.00),
('Uova intere', 0.75, 'g', 155.00, 12.60, 1.10, 4.90, 3.10),
('Zucchero', 0.18, 'g', 392.00, 0.00, 99.80, 0.00, 0.00),
('Zucchine', 0.28, 'g', 17.00, 1.20, 3.10, 0.10, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `ricette`
--

CREATE TABLE `ricette` (
  `titolo` varchar(100) NOT NULL,
  `nicknameEditore` varchar(50) NOT NULL,
  `pubblica` tinyint(1) NOT NULL DEFAULT 0,
  `preparazione` text NOT NULL,
  `porzioni` int(11) NOT NULL,
  `tempoPreparazione` int(11) NOT NULL,
  `kcalTotali` decimal(8,2) NOT NULL,
  `costoTotale` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ricette`
--

INSERT INTO `ricette` (`titolo`, `nicknameEditore`, `pubblica`, `preparazione`, `porzioni`, `tempoPreparazione`, `kcalTotali`, `costoTotale`) VALUES
('Dolce cacao e mele', 'tulipano02', 1, 'Monta le uova con lo zucchero, aggiungi burro fuso, farina, cacao, lievito e mele a pezzetti. Versa in una tortiera e cuoci a 180°C per 35 minuti.', 4, 45, 2090.10, 4.05),
('Frittata di zucchine', 'tulipano02', 1, 'Taglia le zucchine e cuocile in padella con l’olio. Aggiungi le uova sbattute, cuoci da entrambi i lati e servi calda.', 2, 30, 492.80, 2.15),
('Frittelle dolci alle mele', 'brus', 1, 'Sbatti le uova con lo zucchero, unisci farina, lievito, burro fuso e mele grattugiate. Friggi cucchiaiate di composto in olio o cuoci in forno a 180°C per 20 minuti.', 6, 50, 1506.60, 2.41),
('Hamburger di manzo e patate', 'muk', 1, 'Trita il manzo e mescolalo con patate lesse e schiacciate, pane bagnato e strizzato. Forma gli hamburger e cuoci in padella con l’olio.', 2, 35, 1064.80, 10.52),
('Insalata di ceci e pomodori', 'tulipano02', 1, 'Taglia i pomodori a dadini. Mescolali con i ceci cotti e condisci con olio. Servi fredda.', 2, 20, 926.80, 2.04),
('Insalata di farro con pomodori e spinaci', 'tulipano02', 1, 'Cuoci il farro e lascialo raffreddare. Taglia i pomodori e unisci tutti gli ingredienti in una ciotola. Condisci con olio.', 2, 30, 773.80, 2.45),
('Insalata tiepida di farro e verdure', 'muk', 1, 'Cuoci il farro. Salta in padella zucchine e pomodori. Unisci tutto e servi tiepido con un filo d’olio a cruCuoci il farro. Salta in padella zucchine e pomodori. Unisci tutto e servi tiepido con un filo d’olio a crudo.do.', 3, 40, 880.25, 2.20),
('Mele al forno con zucchero e cacao', 'tulipano02', 1, 'Taglia le mele a metà, spolverale con zucchero e cacao. Cuoci in forno a 180°C per 25 minuti.', 3, 35, 385.80, 1.93),
('Omelette agli spinaci e parmigiano', 'muk', 1, 'Salta gli spinaci in padella con poco olio. Sbatti le uova con il parmigiano, unisci gli spinaci e cuoci l’omelette da entrambi i lati.', 1, 15, 372.70, 2.02),
('Pane dolce al cacao', 'muk', 1, 'Taglia il pane a cubetti e ammollalo nella panna. Aggiungi uova, cacao e zucchero. Versa in uno stampo e cuoci in forno a 180°C per 30 minuti.', 6, 45, 1425.20, 3.24),
('Parmigiana leggera di zucchine', 'brus', 1, 'Affetta le zucchine e grigliale. Disponile a strati in una teglia con parmigiano grattugiato. Inforna a 180°C per 20 minuti.', 2, 40, 387.60, 2.04),
('Pasta con zucchine e parmigiano', 'tulipano02', 1, 'Taglia le zucchine a rondelle sottili e falle saltare in padella con l\'olio. Cuoci la pasta, scolala e mescolala con le zucchine. Aggiungi il parmigiano grattugiato prima di servire.', 2, 25, 925.00, 1.97),
('Pollo alla crema di parmigiano', 'brus', 1, 'Cuoci il pollo in padella con l’olio. Quando è dorato, aggiungi la panna e il parmigiano grattugiato. Fai addensare a fuoco basso e servi caldo.', 2, 30, 1116.65, 7.19),
('Pollo con patate al forno', 'tulipano02', 1, 'Taglia il pollo e le patate a cubetti. Mescola con l\'olio e cuoci in forno a 200°C per 40 minuti mescolando a metà cottura.', 2, 50, 1019.70, 6.06),
('Polpette di fagioli cannellini e pane', 'tulipano02', 1, 'Frulla i fagioli con il pane ammorbidito in acqua. Forma delle polpette, cuocile in padella con l’olio per 10 minuti.', 2, 30, 1120.80, 1.99),
('Risotto con spinaci e parmigiano', 'tulipano02', 1, 'Fai appassire gli spinaci con l\'olio. Aggiungi il riso e fallo tostare, poi cuoci aggiungendo acqua calda poco per volta. A fine cottura aggiungi il parmigiano.', 2, 35, 904.70, 2.66),
('Salmone con spinaci e riso', 'tulipano02', 1, 'Cuoci il riso. Cuoci il salmone in padella con l’olio, aggiungi gli spinaci a fine cottura. Servi il tutto con il riso.', 2, 35, 1411.10, 10.79),
('Zucchine ripiene di ceci', 'tulipano02', 1, 'Taglia le zucchine a metà e svuotale. Frulla i ceci cotti con l’olio, farcisci le zucchine e cuoci in forno a 180°C per 25 minuti.', 2, 40, 950.80, 2.28),
('Zuppa rustica di legumi', 'brus', 1, 'Soffriggi i pomodori tritati con l’olio. Aggiungi i legumi cotti e acqua quanto basta. Lascia cuocere a fuoco lento per 1 ora. Servi calda con pane tostato.', 4, 90, 2431.60, 5.18);

-- --------------------------------------------------------

--
-- Table structure for table `utenti`
--

CREATE TABLE `utenti` (
  `nickname` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `accreditato` tinyint(1) NOT NULL DEFAULT 0,
  `fineLimitazione` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utenti`
--

INSERT INTO `utenti` (`nickname`, `nome`, `cognome`, `password`, `accreditato`, `fineLimitazione`) VALUES
('brus', 'Luca', 'Brusi', 'password123', 0, NULL),
('muk', 'Flavio', 'Muccioli', 'password123', 0, NULL),
('tulipano02', 'Rebecca', 'Bortolotto', 'password123', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `utilizzi`
--

CREATE TABLE `utilizzi` (
  `nomeIngrediente` varchar(100) NOT NULL,
  `titolo` varchar(100) NOT NULL,
  `nicknameEditore` varchar(50) NOT NULL,
  `quantita` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilizzi`
--

INSERT INTO `utilizzi` (`nomeIngrediente`, `titolo`, `nicknameEditore`, `quantita`) VALUES
('Burro', 'Dolce cacao e mele', 'tulipano02', 80),
('Burro', 'Frittelle dolci alle mele', 'brus', 50),
('Cacao amaro in polvere', 'Dolce cacao e mele', 'tulipano02', 30),
('Cacao amaro in polvere', 'Mele al forno con zucchero e cacao', 'tulipano02', 15),
('Cacao amaro in polvere', 'Pane dolce al cacao', 'muk', 20),
('Ceci', 'Insalata di ceci e pomodori', 'tulipano02', 200),
('Ceci', 'Zucchine ripiene di ceci', 'tulipano02', 200),
('Ceci', 'Zuppa rustica di legumi', 'brus', 200),
('Fagioli cannellini secchi', 'Polpette di fagioli cannellini e pane', 'tulipano02', 200),
('Fagioli cannellini secchi', 'Zuppa rustica di legumi', 'brus', 200),
('Farina di grano tenero 00', 'Dolce cacao e mele', 'tulipano02', 200),
('Farina di grano tenero 00', 'Frittelle dolci alle mele', 'brus', 200),
('Farro', 'Insalata di farro con pomodori e spinaci', 'tulipano02', 160),
('Farro', 'Insalata tiepida di farro e verdure', 'muk', 180),
('Filetto di manzo', 'Hamburger di manzo e patate', 'muk', 250),
('Lenticchie secche', 'Zuppa rustica di legumi', 'brus', 200),
('Lievito in polvere per dolci', 'Dolce cacao e mele', 'tulipano02', 10),
('Lievito in polvere per dolci', 'Frittelle dolci alle mele', 'brus', 10),
('Mele', 'Dolce cacao e mele', 'tulipano02', 200),
('Mele', 'Frittelle dolci alle mele', 'brus', 300),
('Mele', 'Mele al forno con zucchero e cacao', 'tulipano02', 450),
('Olio extravergine di oliva', 'Frittata di zucchine', 'tulipano02', 20),
('Olio extravergine di oliva', 'Hamburger di manzo e patate', 'muk', 20),
('Olio extravergine di oliva', 'Insalata di ceci e pomodori', 'tulipano02', 20),
('Olio extravergine di oliva', 'Insalata di farro con pomodori e spinaci', 'tulipano02', 20),
('Olio extravergine di oliva', 'Insalata tiepida di farro e verdure', 'muk', 25),
('Olio extravergine di oliva', 'Omelette agli spinaci e parmigiano', 'muk', 10),
('Olio extravergine di oliva', 'Parmigiana leggera di zucchine', 'brus', 20),
('Olio extravergine di oliva', 'Pasta con zucchine e parmigiano', 'tulipano02', 20),
('Olio extravergine di oliva', 'Pollo alla crema di parmigiano', 'brus', 15),
('Olio extravergine di oliva', 'Pollo con patate al forno', 'tulipano02', 30),
('Olio extravergine di oliva', 'Polpette di fagioli cannellini e pane', 'tulipano02', 20),
('Olio extravergine di oliva', 'Risotto con spinaci e parmigiano', 'tulipano02', 20),
('Olio extravergine di oliva', 'Salmone con spinaci e riso', 'tulipano02', 20),
('Olio extravergine di oliva', 'Zucchine ripiene di ceci', 'tulipano02', 20),
('Olio extravergine di oliva', 'Zuppa rustica di legumi', 'brus', 40),
('Pane comune', 'Hamburger di manzo e patate', 'muk', 80),
('Pane comune', 'Pane dolce al cacao', 'muk', 200),
('Pane comune', 'Polpette di fagioli cannellini e pane', 'tulipano02', 100),
('Panna fresca', 'Pane dolce al cacao', 'muk', 100),
('Panna fresca', 'Pollo alla crema di parmigiano', 'brus', 100),
('Parmigiano Reggiano DOP', 'Omelette agli spinaci e parmigiano', 'muk', 20),
('Parmigiano Reggiano DOP', 'Parmigiana leggera di zucchine', 'brus', 40),
('Parmigiano Reggiano DOP', 'Pasta con zucchine e parmigiano', 'tulipano02', 30),
('Parmigiano Reggiano DOP', 'Pollo alla crema di parmigiano', 'brus', 40),
('Parmigiano Reggiano DOP', 'Risotto con spinaci e parmigiano', 'tulipano02', 30),
('Pasta di semola', 'Pasta con zucchine e parmigiano', 'tulipano02', 160),
('Patate', 'Hamburger di manzo e patate', 'muk', 200),
('Patate', 'Pollo con patate al forno', 'tulipano02', 300),
('Petto di pollo', 'Pollo alla crema di parmigiano', 'brus', 300),
('Petto di pollo', 'Pollo con patate al forno', 'tulipano02', 300),
('Pomodori freschi', 'Insalata di ceci e pomodori', 'tulipano02', 150),
('Pomodori freschi', 'Insalata di farro con pomodori e spinaci', 'tulipano02', 150),
('Pomodori freschi', 'Insalata tiepida di farro e verdure', 'muk', 100),
('Pomodori freschi', 'Zuppa rustica di legumi', 'brus', 200),
('Riso Carnaroli', 'Risotto con spinaci e parmigiano', 'tulipano02', 160),
('Riso Carnaroli', 'Salmone con spinaci e riso', 'tulipano02', 160),
('Salmone fresco', 'Salmone con spinaci e riso', 'tulipano02', 300),
('Spinaci freschi', 'Insalata di farro con pomodori e spinaci', 'tulipano02', 100),
('Spinaci freschi', 'Omelette agli spinaci e parmigiano', 'muk', 80),
('Spinaci freschi', 'Risotto con spinaci e parmigiano', 'tulipano02', 150),
('Spinaci freschi', 'Salmone con spinaci e riso', 'tulipano02', 150),
('Uova intere', 'Dolce cacao e mele', 'tulipano02', 120),
('Uova intere', 'Frittata di zucchine', 'tulipano02', 180),
('Uova intere', 'Frittelle dolci alle mele', 'brus', 2),
('Uova intere', 'Omelette agli spinaci e parmigiano', 'muk', 120),
('Uova intere', 'Pane dolce al cacao', 'muk', 120),
('Zucchero', 'Dolce cacao e mele', 'tulipano02', 100),
('Zucchero', 'Frittelle dolci alle mele', 'brus', 60),
('Zucchero', 'Mele al forno con zucchero e cacao', 'tulipano02', 30),
('Zucchero', 'Pane dolce al cacao', 'muk', 80),
('Zucchine', 'Frittata di zucchine', 'tulipano02', 200),
('Zucchine', 'Insalata tiepida di farro e verdure', 'muk', 150),
('Zucchine', 'Parmigiana leggera di zucchine', 'brus', 300),
('Zucchine', 'Pasta con zucchine e parmigiano', 'tulipano02', 200),
('Zucchine', 'Zucchine ripiene di ceci', 'tulipano02', 300);

-- --------------------------------------------------------

--
-- Table structure for table `valutazioni`
--

CREATE TABLE `valutazioni` (
  `nicknameValutatore` varchar(50) NOT NULL,
  `titolo` varchar(100) NOT NULL,
  `nicknameEditore` varchar(50) NOT NULL,
  `dataOra` datetime NOT NULL,
  `nascosta` tinyint(1) NOT NULL DEFAULT 0,
  `voto` int(11) NOT NULL CHECK (`voto` between 1 and 5),
  `commento` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `valutazioni`
--

INSERT INTO `valutazioni` (`nicknameValutatore`, `titolo`, `nicknameEditore`, `dataOra`, `nascosta`, `voto`, `commento`) VALUES
('brus', 'Pollo con patate al forno', 'tulipano02', '2025-05-20 16:48:12', 0, 5, 'Piatto delizioso'),
('brus', 'Polpette di fagioli cannellini e pane', 'tulipano02', '2025-05-20 16:49:00', 0, 5, NULL),
('brus', 'Salmone con spinaci e riso', 'tulipano02', '2025-05-20 16:49:21', 0, 4, 'Ottimo piatto!'),
('brus', 'Zucchine ripiene di ceci', 'tulipano02', '2025-05-20 16:49:31', 0, 5, NULL),
('muk', 'Insalata di ceci e pomodori', 'tulipano02', '2025-05-20 17:02:07', 1, 4, 'COMMENTO INOPPORTUNO!'),
('muk', 'Parmigiana leggera di zucchine', 'brus', '2025-05-20 16:58:30', 1, 1, 'COMMENTO OFFENSIVO!'),
('muk', 'Pollo alla crema di parmigiano', 'brus', '2025-05-20 16:59:38', 1, 1, 'COMMENTO OFFENSIVO!'),
('muk', 'Polpette di fagioli cannellini e pane', 'tulipano02', '2025-05-20 17:01:09', 1, 3, 'COMMENTO OFFENSIVO!'),
('muk', 'Risotto con spinaci e parmigiano', 'tulipano02', '2025-05-20 17:02:53', 1, 5, 'COMMENTO INOPPORTUNO'),
('muk', 'Zuppa rustica di legumi', 'brus', '2025-05-20 16:57:52', 0, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amministratori`
--
ALTER TABLE `amministratori`
  ADD PRIMARY KEY (`nickname`);

--
-- Indexes for table `composizioni`
--
ALTER TABLE `composizioni`
  ADD PRIMARY KEY (`nomeDieta`,`nicknameAutore`,`titolo`,`nicknameEditore`),
  ADD KEY `composizioni_ibfk_2` (`titolo`,`nicknameEditore`);

--
-- Indexes for table `diete`
--
ALTER TABLE `diete`
  ADD PRIMARY KEY (`nome`,`nicknameAutore`),
  ADD KEY `nicknameAutore` (`nicknameAutore`);

--
-- Indexes for table `infrazioni`
--
ALTER TABLE `infrazioni`
  ADD PRIMARY KEY (`nicknameAmministratore`,`nicknameValutatore`,`titolo`,`nicknameEditore`),
  ADD KEY `nicknameValutatore` (`nicknameValutatore`,`titolo`,`nicknameEditore`);

--
-- Indexes for table `ingredienti`
--
ALTER TABLE `ingredienti`
  ADD PRIMARY KEY (`nome`);

--
-- Indexes for table `ricette`
--
ALTER TABLE `ricette`
  ADD PRIMARY KEY (`titolo`,`nicknameEditore`),
  ADD KEY `nicknameEditore` (`nicknameEditore`);

--
-- Indexes for table `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`nickname`);

--
-- Indexes for table `utilizzi`
--
ALTER TABLE `utilizzi`
  ADD PRIMARY KEY (`nomeIngrediente`,`titolo`,`nicknameEditore`),
  ADD KEY `utilizzi_ibfk_2` (`titolo`,`nicknameEditore`);

--
-- Indexes for table `valutazioni`
--
ALTER TABLE `valutazioni`
  ADD PRIMARY KEY (`nicknameValutatore`,`titolo`,`nicknameEditore`),
  ADD KEY `titolo` (`titolo`,`nicknameEditore`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `composizioni`
--
ALTER TABLE `composizioni`
  ADD CONSTRAINT `composizioni_ibfk_1` FOREIGN KEY (`nomeDieta`,`nicknameAutore`) REFERENCES `diete` (`nome`, `nicknameAutore`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `composizioni_ibfk_2` FOREIGN KEY (`titolo`,`nicknameEditore`) REFERENCES `ricette` (`titolo`, `nicknameEditore`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `diete`
--
ALTER TABLE `diete`
  ADD CONSTRAINT `diete_ibfk_1` FOREIGN KEY (`nicknameAutore`) REFERENCES `utenti` (`nickname`) ON DELETE CASCADE;

--
-- Constraints for table `infrazioni`
--
ALTER TABLE `infrazioni`
  ADD CONSTRAINT `infrazioni_ibfk_1` FOREIGN KEY (`nicknameAmministratore`) REFERENCES `amministratori` (`nickname`) ON DELETE CASCADE,
  ADD CONSTRAINT `infrazioni_ibfk_2` FOREIGN KEY (`nicknameValutatore`,`titolo`,`nicknameEditore`) REFERENCES `valutazioni` (`nicknameValutatore`, `titolo`, `nicknameEditore`) ON DELETE CASCADE;

--
-- Constraints for table `ricette`
--
ALTER TABLE `ricette`
  ADD CONSTRAINT `ricette_ibfk_1` FOREIGN KEY (`nicknameEditore`) REFERENCES `utenti` (`nickname`) ON DELETE CASCADE;

--
-- Constraints for table `utilizzi`
--
ALTER TABLE `utilizzi`
  ADD CONSTRAINT `utilizzi_ibfk_1` FOREIGN KEY (`nomeIngrediente`) REFERENCES `ingredienti` (`nome`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `utilizzi_ibfk_2` FOREIGN KEY (`titolo`,`nicknameEditore`) REFERENCES `ricette` (`titolo`, `nicknameEditore`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `valutazioni`
--
ALTER TABLE `valutazioni`
  ADD CONSTRAINT `valutazioni_ibfk_1` FOREIGN KEY (`nicknameValutatore`) REFERENCES `utenti` (`nickname`) ON DELETE CASCADE,
  ADD CONSTRAINT `valutazioni_ibfk_2` FOREIGN KEY (`titolo`,`nicknameEditore`) REFERENCES `ricette` (`titolo`, `nicknameEditore`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
