CREATE DATABASE IF NOT EXISTS maintenance_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE maintenance_db;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 02:14 PM
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
-- Database: `maintenance_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `equipements`
--

CREATE TABLE `equipements` (
  `id` int(11) NOT NULL,
  `nom` varchar(120) DEFAULT NULL,
  `reference` varchar(80) DEFAULT NULL,
  `date_mise_service` date DEFAULT NULL,
  `etat` enum('en_service','arrete','maintenance') DEFAULT 'en_service',
  `usine` enum('DMA1','DMA2') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipements`
--

INSERT INTO `equipements` (`id`, `nom`, `reference`, `date_mise_service`, `etat`, `usine`) VALUES
(1, 'Balancing Machine 1', 'DMA1-MACH-001', '2023-03-15', 'en_service', 'DMA1'),
(2, 'Balancing Machine 2', 'DMA1-MACH-002', '2023-03-15', 'en_service', 'DMA1'),
(3, 'Manual Balancing Machine', 'DMA1-MACH-003', '2023-06-20', 'en_service', 'DMA1'),
(4, 'Brush Machine 1', 'DMA1-MACH-004', '2023-03-15', 'en_service', 'DMA1'),
(5, 'Brush Machine 2', 'DMA1-MACH-005', '2023-03-15', 'en_service', 'DMA1'),
(6, 'Brush Machine 3', 'DMA1-MACH-006', '2023-05-10', 'en_service', 'DMA1'),
(7, 'Brush Machine 4', 'DMA1-MACH-007', '2023-05-10', 'en_service', 'DMA1'),
(8, 'Brush Machine 5', 'DMA1-MACH-008', '2023-07-22', 'en_service', 'DMA1'),
(9, 'Brush Machine 6', 'DMA1-MACH-009', '2023-07-22', 'en_service', 'DMA1'),
(10, 'Aluminum Chip Transport', 'DMA1-MACH-010', '2023-03-15', 'en_service', 'DMA1'),
(11, 'Centralized Liquid Feeding System', 'DMA1-MACH-011', '2023-03-15', 'en_service', 'DMA1'),
(12, 'Oil Separator System', 'DMA1-MACH-012', '2023-03-15', 'en_service', 'DMA1'),
(13, 'Crusher', 'DMA1-MACH-013', '2023-04-01', 'en_service', 'DMA1'),
(14, 'Scraper Elevator', 'DMA1-MACH-014', '2023-04-01', 'en_service', 'DMA1'),
(15, 'Filtration Machine', 'DMA1-MACH-015', '2023-03-15', 'en_service', 'DMA1'),
(16, 'Manual Beating Machine', 'DMA1-MACH-016', '2023-06-15', 'en_service', 'DMA1'),
(17, 'CMM', 'DMA1-MACH-017', '2023-03-15', 'en_service', 'DMA1'),
(18, 'Auxiliary Machining Zone Conveyor', 'DMA1-MACH-018', '2023-03-15', 'en_service', 'DMA1'),
(19, 'Machining Zone Conveyor', 'DMA1-MACH-019', '2023-03-15', 'en_service', 'DMA1'),
(20, 'Diamond Cut Unit 1', 'DMA1-MACH-020', '2023-08-01', 'en_service', 'DMA1'),
(21, 'Diamond Cut Unit 2', 'DMA1-MACH-021', '2023-08-01', 'en_service', 'DMA1'),
(22, 'Rough Machining Unit A1', 'DMA1-ROUG-001', '2023-03-15', 'en_service', 'DMA1'),
(23, 'Rough Machining Unit A2', 'DMA1-ROUG-002', '2023-03-15', 'en_service', 'DMA1'),
(24, 'Rough Machining Unit A3', 'DMA1-ROUG-003', '2023-03-15', 'en_service', 'DMA1'),
(25, 'Rough Machining Unit A4', 'DMA1-ROUG-004', '2023-03-15', 'en_service', 'DMA1'),
(26, 'Rough Machining Unit B1', 'DMA1-ROUG-005', '2023-04-20', 'en_service', 'DMA1'),
(27, 'Rough Machining Unit B2', 'DMA1-ROUG-006', '2023-04-20', 'en_service', 'DMA1'),
(28, 'Rough Machining Unit B3', 'DMA1-ROUG-007', '2023-04-20', 'en_service', 'DMA1'),
(29, 'Rough Machining Unit B4', 'DMA1-ROUG-008', '2023-04-20', 'en_service', 'DMA1'),
(30, 'Rough Machining Unit B5', 'DMA1-ROUG-009', '2023-06-01', 'en_service', 'DMA1'),
(31, 'Rough Machining Unit B6', 'DMA1-ROUG-010', '2023-06-01', 'en_service', 'DMA1'),
(32, 'Casting Machine A1', 'DMA1-CAST-001', '2023-03-15', 'en_service', 'DMA1'),
(33, 'Casting Machine A2', 'DMA1-CAST-002', '2023-03-15', 'en_service', 'DMA1'),
(34, 'Casting Machine A3', 'DMA1-CAST-003', '2023-03-15', 'en_service', 'DMA1'),
(35, 'Casting Machine B1', 'DMA1-CAST-004', '2023-05-10', 'en_service', 'DMA1'),
(36, 'Casting Machine B2', 'DMA1-CAST-005', '2023-05-10', 'en_service', 'DMA1'),
(37, 'Casting Roller A', 'DMA1-CAST-006', '2023-03-15', 'en_service', 'DMA1'),
(38, 'Casting Roller B', 'DMA1-CAST-007', '2023-03-15', 'en_service', 'DMA1'),
(39, 'Casting Robot 1', 'DMA1-CAST-008', '2023-07-01', 'en_service', 'DMA1'),
(40, 'Casting Robot 2', 'DMA1-CAST-009', '2023-07-01', 'en_service', 'DMA1'),
(41, 'Heat Treatment Furnace A', 'DMA1-HEAT-001', '2023-03-15', 'en_service', 'DMA1'),
(42, 'Heat Treatment Furnace B', 'DMA1-HEAT-002', '2023-03-15', 'en_service', 'DMA1'),
(43, 'Quenching Tank 1', 'DMA1-HEAT-003', '2023-03-15', 'en_service', 'DMA1'),
(44, 'Quenching Tank 2', 'DMA1-HEAT-004', '2023-03-15', 'en_service', 'DMA1'),
(45, 'Aging Furnace A', 'DMA1-HEAT-005', '2023-05-20', 'en_service', 'DMA1'),
(46, 'Aging Furnace B', 'DMA1-HEAT-006', '2023-05-20', 'en_service', 'DMA1'),
(47, 'Cooling Conveyor', 'DMA1-HEAT-007', '2023-03-15', 'en_service', 'DMA1'),
(48, 'Melting Furnace A', 'DMA1-MELT-001', '2023-03-15', 'en_service', 'DMA1'),
(49, 'Melting Furnace B', 'DMA1-MELT-002', '2023-03-15', 'en_service', 'DMA1'),
(50, 'Holding Furnace 1', 'DMA1-MELT-003', '2023-03-15', 'en_service', 'DMA1'),
(51, 'Holding Furnace 2', 'DMA1-MELT-004', '2023-05-01', 'en_service', 'DMA1'),
(52, 'Degassing Unit', 'DMA1-MELT-005', '2023-03-15', 'en_service', 'DMA1'),
(53, 'Transfer Ladle 1', 'DMA1-MELT-006', '2023-03-15', 'en_service', 'DMA1'),
(54, 'Transfer Ladle 2', 'DMA1-MELT-007', '2023-06-01', 'en_service', 'DMA1'),
(55, 'X-Ray Machine 1', 'DMA1-XRAY-001', '2023-03-15', 'en_service', 'DMA1'),
(56, 'X-Ray Machine 2', 'DMA1-XRAY-002', '2023-03-15', 'en_service', 'DMA1'),
(57, 'X-Ray Machine 3', 'DMA1-XRAY-003', '2023-07-15', 'en_service', 'DMA1'),
(58, 'X-Ray Machine 4', 'DMA1-XRAY-004', '2023-07-15', 'en_service', 'DMA1'),
(59, 'Painting Line 1', 'DMA1-PAIN-001', '2023-03-15', 'en_service', 'DMA1'),
(60, 'Painting Line 2', 'DMA1-PAIN-002', '2023-06-01', 'en_service', 'DMA1'),
(61, 'Powder Coating Booth', 'DMA1-PAIN-003', '2023-03-15', 'en_service', 'DMA1'),
(62, 'Curing Oven', 'DMA1-PAIN-004', '2023-03-15', 'en_service', 'DMA1'),
(63, 'Pre-treatment Station', 'DMA1-PAIN-005', '2023-03-15', 'en_service', 'DMA1'),
(64, 'Packaging Line 1', 'DMA1-PACK-001', '2023-03-15', 'en_service', 'DMA1'),
(65, 'Packaging Line 2', 'DMA1-PACK-002', '2023-05-15', 'en_service', 'DMA1'),
(66, 'Wrapping Machine', 'DMA1-PACK-003', '2023-03-15', 'en_service', 'DMA1'),
(67, 'Labeling Machine', 'DMA1-PACK-004', '2023-03-15', 'en_service', 'DMA1'),
(68, 'Palletizer', 'DMA1-PACK-005', '2023-06-20', 'en_service', 'DMA1'),
(69, 'Air Compressor 1', 'DMA1-COMP-001', '2023-03-15', 'en_service', 'DMA1'),
(70, 'Air Compressor 2', 'DMA1-COMP-002', '2023-03-15', 'en_service', 'DMA1'),
(71, 'Air Compressor 3', 'DMA1-COMP-003', '2023-07-01', 'en_service', 'DMA1'),
(72, 'Air Dryer 1', 'DMA1-COMP-004', '2023-03-15', 'en_service', 'DMA1'),
(73, 'Air Dryer 2', 'DMA1-COMP-005', '2023-03-15', 'en_service', 'DMA1'),
(74, 'Cooling Tower 1', 'DMA1-COOL-001', '2023-03-15', 'en_service', 'DMA1'),
(75, 'Cooling Tower 2', 'DMA1-COOL-002', '2023-03-15', 'en_service', 'DMA1'),
(76, 'Chiller Unit 1', 'DMA1-COOL-003', '2023-03-15', 'en_service', 'DMA1'),
(77, 'Chiller Unit 2', 'DMA1-COOL-004', '2023-05-01', 'en_service', 'DMA1'),
(78, 'Circulation Pump 1', 'DMA1-COOL-005', '2023-03-15', 'en_service', 'DMA1'),
(79, 'Circulation Pump 2', 'DMA1-COOL-006', '2023-03-15', 'en_service', 'DMA1'),
(80, 'Flow Forming Machine 1', 'DMA1-FLOW-001', '2023-04-15', 'en_service', 'DMA1'),
(81, 'Flow Forming Machine 2', 'DMA1-FLOW-002', '2023-04-15', 'en_service', 'DMA1'),
(82, 'Flow Forming Machine 3', 'DMA1-FLOW-003', '2023-08-01', 'en_service', 'DMA1'),
(83, 'Helium Leak Test 1', 'DMA1-LEAK-001', '2023-03-15', 'en_service', 'DMA1'),
(84, 'Helium Leak Test 2', 'DMA1-LEAK-002', '2023-03-15', 'en_service', 'DMA1'),
(85, 'Water Leak Test', 'DMA1-LEAK-003', '2023-05-01', 'en_service', 'DMA1'),
(86, 'Balancing Machine 1', 'DMA2-MACH-001', '2024-01-15', 'en_service', 'DMA2'),
(87, 'Balancing Machine 2', 'DMA2-MACH-002', '2024-01-15', 'en_service', 'DMA2'),
(88, 'Brush Machine 1', 'DMA2-MACH-003', '2024-01-15', 'en_service', 'DMA2'),
(89, 'Brush Machine 2', 'DMA2-MACH-004', '2024-01-15', 'en_service', 'DMA2'),
(90, 'Brush Machine 3', 'DMA2-MACH-005', '2024-03-01', 'en_service', 'DMA2'),
(91, 'Diamond Cut Unit 1', 'DMA2-MACH-006', '2024-01-15', 'en_service', 'DMA2'),
(92, 'Diamond Cut Unit 2', 'DMA2-MACH-007', '2024-04-01', 'en_service', 'DMA2'),
(93, 'CMM', 'DMA2-MACH-008', '2024-01-15', 'en_service', 'DMA2'),
(94, 'Machining Conveyor', 'DMA2-MACH-009', '2024-01-15', 'en_service', 'DMA2'),
(95, 'Rough Machining Unit A1', 'DMA2-ROUG-001', '2024-01-15', 'en_service', 'DMA2'),
(96, 'Rough Machining Unit A2', 'DMA2-ROUG-002', '2024-01-15', 'en_service', 'DMA2'),
(97, 'Rough Machining Unit A3', 'DMA2-ROUG-003', '2024-01-15', 'en_service', 'DMA2'),
(98, 'Rough Machining Unit B1', 'DMA2-ROUG-004', '2024-03-01', 'en_service', 'DMA2'),
(99, 'Rough Machining Unit B2', 'DMA2-ROUG-005', '2024-03-01', 'en_service', 'DMA2'),
(100, 'Rough Machining Unit B3', 'DMA2-ROUG-006', '2024-05-15', 'en_service', 'DMA2'),
(101, 'Casting Machine A1', 'DMA2-CAST-001', '2024-01-15', 'en_service', 'DMA2'),
(102, 'Casting Machine A2', 'DMA2-CAST-002', '2024-01-15', 'en_service', 'DMA2'),
(103, 'Casting Machine B1', 'DMA2-CAST-003', '2024-04-01', 'en_service', 'DMA2'),
(104, 'Casting Robot 1', 'DMA2-CAST-004', '2024-01-15', 'en_service', 'DMA2'),
(105, 'Casting Robot 2', 'DMA2-CAST-005', '2024-06-01', 'en_service', 'DMA2'),
(106, 'Heat Treatment Furnace', 'DMA2-HEAT-001', '2024-01-15', 'en_service', 'DMA2'),
(107, 'Quenching Tank', 'DMA2-HEAT-002', '2024-01-15', 'en_service', 'DMA2'),
(108, 'Aging Furnace', 'DMA2-HEAT-003', '2024-01-15', 'en_service', 'DMA2'),
(109, 'Cooling Conveyor', 'DMA2-HEAT-004', '2024-01-15', 'en_service', 'DMA2'),
(110, 'Melting Furnace', 'DMA2-MELT-001', '2024-01-15', 'en_service', 'DMA2'),
(111, 'Holding Furnace', 'DMA2-MELT-002', '2024-01-15', 'en_service', 'DMA2'),
(112, 'Degassing Unit', 'DMA2-MELT-003', '2024-01-15', 'en_service', 'DMA2'),
(113, 'Transfer Ladle', 'DMA2-MELT-004', '2024-03-01', 'en_service', 'DMA2'),
(114, 'X-Ray Machine 1', 'DMA2-XRAY-001', '2024-01-15', 'en_service', 'DMA2'),
(115, 'X-Ray Machine 2', 'DMA2-XRAY-002', '2024-01-15', 'en_service', 'DMA2'),
(116, 'X-Ray Machine 3', 'DMA2-XRAY-003', '2024-06-01', 'en_service', 'DMA2'),
(117, 'Painting Line', 'DMA2-PAIN-001', '2024-01-15', 'en_service', 'DMA2'),
(118, 'Powder Coating Booth', 'DMA2-PAIN-002', '2024-01-15', 'en_service', 'DMA2'),
(119, 'Curing Oven', 'DMA2-PAIN-003', '2024-01-15', 'en_service', 'DMA2'),
(120, 'Packaging Line', 'DMA2-PACK-001', '2024-01-15', 'en_service', 'DMA2'),
(121, 'Wrapping Machine', 'DMA2-PACK-002', '2024-01-15', 'en_service', 'DMA2'),
(122, 'Labeling Machine', 'DMA2-PACK-003', '2024-03-15', 'en_service', 'DMA2'),
(123, 'Air Compressor 1', 'DMA2-COMP-001', '2024-01-15', 'en_service', 'DMA2'),
(124, 'Air Compressor 2', 'DMA2-COMP-002', '2024-01-15', 'en_service', 'DMA2'),
(125, 'Air Dryer', 'DMA2-COMP-003', '2024-01-15', 'en_service', 'DMA2'),
(126, 'Cooling Tower', 'DMA2-COOL-001', '2024-01-15', 'en_service', 'DMA2'),
(127, 'Chiller Unit', 'DMA2-COOL-002', '2024-01-15', 'en_service', 'DMA2'),
(128, 'Circulation Pump 1', 'DMA2-COOL-003', '2024-01-15', 'en_service', 'DMA2'),
(129, 'Circulation Pump 2', 'DMA2-COOL-004', '2024-04-01', 'en_service', 'DMA2'),
(130, 'Flow Forming Machine 1', 'DMA2-FLOW-001', '2024-02-01', 'en_service', 'DMA2'),
(131, 'Flow Forming Machine 2', 'DMA2-FLOW-002', '2024-05-01', 'en_service', 'DMA2'),
(132, 'Helium Leak Test', 'DMA2-LEAK-001', '2024-01-15', 'en_service', 'DMA2'),
(133, 'Water Leak Test', 'DMA2-LEAK-002', '2024-01-15', 'en_service', 'DMA2');

-- --------------------------------------------------------

--
-- Table structure for table `interventions`
--

CREATE TABLE `interventions` (
  `id` int(11) NOT NULL,
  `id_panne` int(11) DEFAULT NULL,
  `id_technicien` int(11) DEFAULT NULL,
  `type` enum('preventive','corrective') DEFAULT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `statut` enum('en_cours','terminee','en_attente') DEFAULT 'en_cours',
  `importance` enum('normale','importante','critique') DEFAULT 'normale',
  `usine` enum('DMA1','DMA2') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `interventions`
--

INSERT INTO `interventions` (`id`, `id_panne`, `id_technicien`, `type`, `date_debut`, `date_fin`, `statut`, `importance`, `usine`) VALUES
(8, 1, 2, 'corrective', '2025-01-06 08:00:00', '2025-01-06 14:00:00', 'terminee', 'importante', 'DMA1'),
(9, 2, 3, 'corrective', '2025-01-09 10:30:00', '2025-01-10 11:30:00', 'terminee', 'critique', 'DMA1'),
(10, 3, 2, 'corrective', '2025-01-14 08:30:00', '2025-01-14 16:30:00', 'terminee', 'importante', 'DMA1'),
(11, 4, 2, 'corrective', '2025-01-17 14:30:00', '2025-01-17 18:00:00', 'terminee', 'normale', 'DMA1'),
(12, 5, 3, 'corrective', '2025-01-21 07:00:00', '2025-01-22 10:00:00', 'terminee', 'importante', 'DMA1'),
(13, 6, 2, 'corrective', '2025-01-24 11:30:00', '2025-01-25 09:00:00', 'terminee', 'critique', 'DMA1'),
(14, 7, 3, 'corrective', '2025-01-28 15:45:00', '2025-01-28 18:00:00', 'terminee', 'normale', 'DMA1'),
(15, 8, 2, 'corrective', '2025-02-03 08:15:00', '2025-02-03 12:00:00', 'terminee', 'normale', 'DMA1'),
(16, 9, 3, 'corrective', '2025-02-07 07:30:00', '2025-02-08 14:00:00', 'terminee', 'critique', 'DMA1'),
(17, 10, 2, 'corrective', '2025-02-12 09:45:00', '2025-02-12 17:00:00', 'terminee', 'importante', 'DMA1'),
(18, 11, 2, 'corrective', '2025-02-17 10:15:00', '2025-02-17 15:30:00', 'terminee', 'normale', 'DMA1'),
(19, 12, 3, 'corrective', '2025-02-21 13:15:00', '2025-02-21 18:00:00', 'terminee', 'normale', 'DMA1'),
(20, 13, 2, 'corrective', '2025-02-26 06:45:00', '2025-02-26 14:00:00', 'terminee', 'importante', 'DMA1'),
(21, 14, 3, 'corrective', '2025-03-04 08:30:00', '2025-03-04 11:00:00', 'terminee', 'normale', 'DMA1'),
(22, 15, 2, 'corrective', '2025-03-10 14:15:00', '2025-03-10 17:30:00', 'terminee', 'normale', 'DMA1'),
(23, 16, 3, 'corrective', '2025-03-14 07:30:00', '2025-03-15 12:00:00', 'terminee', 'importante', 'DMA1'),
(24, 17, 2, 'corrective', '2025-03-20 11:45:00', '2025-03-20 16:00:00', 'terminee', 'normale', 'DMA1'),
(25, 18, 2, 'corrective', '2025-03-26 09:15:00', '2025-03-26 13:00:00', 'terminee', 'importante', 'DMA1'),
(26, 19, 3, 'corrective', '2025-04-02 10:15:00', '2025-04-02 15:00:00', 'terminee', 'normale', 'DMA1'),
(27, 20, 2, 'corrective', '2025-04-09 08:45:00', '2025-04-09 17:00:00', 'terminee', 'importante', 'DMA1'),
(28, 21, 2, 'corrective', '2025-04-16 06:15:00', '2025-04-16 09:00:00', 'terminee', 'normale', 'DMA1'),
(29, 22, 3, 'corrective', '2025-04-23 14:45:00', '2025-04-23 17:00:00', 'terminee', 'normale', 'DMA1'),
(30, 23, 2, 'corrective', '2025-05-05 08:00:00', '2025-05-05 14:30:00', 'terminee', 'importante', 'DMA1'),
(31, 24, 3, 'corrective', '2025-05-12 09:15:00', '2025-05-12 12:00:00', 'terminee', 'normale', 'DMA1'),
(32, 25, 2, 'corrective', '2025-05-19 11:15:00', '2025-05-19 18:00:00', 'terminee', 'importante', 'DMA1'),
(33, 26, 3, 'corrective', '2025-05-23 15:15:00', '2025-05-23 17:30:00', 'terminee', 'normale', 'DMA1'),
(34, 27, 2, 'corrective', '2025-05-28 08:30:00', '2025-05-29 10:00:00', 'terminee', 'critique', 'DMA1'),
(35, 28, 3, 'corrective', '2025-06-03 06:45:00', '2025-06-03 10:00:00', 'terminee', 'importante', 'DMA1'),
(36, 29, 2, 'corrective', '2025-06-11 10:45:00', '2025-06-12 16:00:00', 'terminee', 'importante', 'DMA1'),
(37, 30, 3, 'corrective', '2025-06-18 14:15:00', '2025-06-18 18:30:00', 'terminee', 'normale', 'DMA1'),
(38, 31, 2, 'corrective', '2025-06-25 09:30:00', '2025-06-25 12:00:00', 'terminee', 'normale', 'DMA1'),
(39, 32, 2, 'corrective', '2025-07-02 11:15:00', '2025-07-02 18:00:00', 'terminee', 'critique', 'DMA1'),
(40, 33, 3, 'corrective', '2025-07-08 08:15:00', '2025-07-08 10:30:00', 'terminee', 'normale', 'DMA1'),
(41, 34, 2, 'corrective', '2025-07-14 07:45:00', '2025-07-14 15:00:00', 'terminee', 'importante', 'DMA1'),
(42, 35, 3, 'corrective', '2025-07-18 13:15:00', '2025-07-19 09:00:00', 'terminee', 'importante', 'DMA1'),
(43, 36, 2, 'corrective', '2025-07-24 10:15:00', '2025-07-24 17:00:00', 'terminee', 'importante', 'DMA1'),
(44, 37, 3, 'corrective', '2025-07-29 14:45:00', '2025-07-30 11:00:00', 'terminee', 'critique', 'DMA1'),
(45, 38, 2, 'corrective', '2025-08-04 08:45:00', '2025-08-04 14:00:00', 'terminee', 'normale', 'DMA1'),
(46, 39, 3, 'corrective', '2025-08-11 09:15:00', '2025-08-11 16:00:00', 'terminee', 'importante', 'DMA1'),
(47, 40, 2, 'corrective', '2025-08-18 07:15:00', '2025-08-18 13:00:00', 'terminee', 'importante', 'DMA1'),
(48, 41, 3, 'corrective', '2025-08-22 11:45:00', '2025-08-23 15:00:00', 'terminee', 'importante', 'DMA1'),
(49, 42, 2, 'corrective', '2025-08-28 14:15:00', '2025-08-28 17:30:00', 'terminee', 'normale', 'DMA1'),
(50, 43, 3, 'corrective', '2025-09-03 07:00:00', '2025-09-04 14:00:00', 'terminee', 'critique', 'DMA1'),
(51, 44, 2, 'corrective', '2025-09-10 10:15:00', '2025-09-10 15:00:00', 'terminee', 'normale', 'DMA1'),
(52, 45, 3, 'corrective', '2025-09-17 08:45:00', '2025-09-17 12:00:00', 'terminee', 'normale', 'DMA1'),
(53, 46, 2, 'corrective', '2025-09-24 13:15:00', '2025-09-24 18:00:00', 'terminee', 'importante', 'DMA1'),
(54, 47, 3, 'corrective', '2025-10-02 09:15:00', '2025-10-02 12:30:00', 'terminee', 'normale', 'DMA1'),
(55, 48, 2, 'corrective', '2025-10-08 07:45:00', '2025-10-08 14:00:00', 'terminee', 'importante', 'DMA1'),
(56, 49, 3, 'corrective', '2025-10-15 06:15:00', '2025-10-15 16:00:00', 'terminee', 'critique', 'DMA1'),
(57, 50, 2, 'corrective', '2025-10-21 11:15:00', '2025-10-21 17:00:00', 'terminee', 'normale', 'DMA1'),
(58, 51, 3, 'corrective', '2025-10-28 14:45:00', '2025-10-28 18:00:00', 'terminee', 'normale', 'DMA1'),
(59, 52, 2, 'corrective', '2025-11-04 08:15:00', '2025-11-04 15:00:00', 'terminee', 'normale', 'DMA1'),
(60, 53, 3, 'corrective', '2025-11-12 07:15:00', NULL, 'en_cours', 'critique', 'DMA1'),
(61, 54, 2, 'corrective', '2025-11-18 11:00:00', NULL, 'en_attente', 'importante', 'DMA1'),
(62, 55, 2, 'corrective', '2025-01-10 06:45:00', '2025-01-10 14:00:00', 'terminee', 'critique', 'DMA2'),
(63, 56, 3, 'corrective', '2025-01-20 09:15:00', '2025-01-20 13:00:00', 'terminee', 'importante', 'DMA2'),
(64, 57, 2, 'corrective', '2025-01-27 11:45:00', '2025-01-27 16:00:00', 'terminee', 'normale', 'DMA2'),
(65, 58, 3, 'corrective', '2025-02-05 07:15:00', '2025-02-05 15:00:00', 'terminee', 'importante', 'DMA2'),
(66, 59, 2, 'corrective', '2025-02-14 08:45:00', '2025-02-14 11:00:00', 'terminee', 'normale', 'DMA2'),
(67, 60, 3, 'corrective', '2025-02-20 10:15:00', '2025-02-20 14:30:00', 'terminee', 'normale', 'DMA2'),
(68, 61, 2, 'corrective', '2025-02-26 13:15:00', '2025-02-27 10:00:00', 'terminee', 'critique', 'DMA2'),
(69, 62, 3, 'corrective', '2025-03-06 09:30:00', '2025-03-06 14:00:00', 'terminee', 'importante', 'DMA2'),
(70, 63, 2, 'corrective', '2025-03-17 07:45:00', '2025-03-17 10:00:00', 'terminee', 'normale', 'DMA2'),
(71, 64, 3, 'corrective', '2025-03-25 11:15:00', '2025-03-25 18:00:00', 'terminee', 'importante', 'DMA2'),
(72, 65, 2, 'corrective', '2025-04-07 08:15:00', '2025-04-07 16:00:00', 'terminee', 'importante', 'DMA2'),
(73, 66, 3, 'corrective', '2025-04-15 10:45:00', '2025-04-16 09:00:00', 'terminee', 'critique', 'DMA2'),
(74, 67, 2, 'corrective', '2025-04-24 14:15:00', '2025-04-24 18:30:00', 'terminee', 'normale', 'DMA2'),
(75, 68, 3, 'corrective', '2025-05-08 08:00:00', '2025-05-08 12:00:00', 'terminee', 'normale', 'DMA2'),
(76, 69, 2, 'corrective', '2025-05-19 09:45:00', '2025-05-19 13:00:00', 'terminee', 'normale', 'DMA2'),
(77, 70, 3, 'corrective', '2025-05-28 15:15:00', '2025-05-28 18:00:00', 'terminee', 'normale', 'DMA2'),
(78, 71, 2, 'corrective', '2025-06-05 06:15:00', '2025-06-05 15:00:00', 'terminee', 'importante', 'DMA2'),
(79, 72, 3, 'corrective', '2025-06-16 11:15:00', '2025-06-17 14:00:00', 'terminee', 'importante', 'DMA2'),
(80, 73, 2, 'corrective', '2025-06-26 08:45:00', '2025-06-26 11:00:00', 'terminee', 'normale', 'DMA2'),
(81, 74, 3, 'corrective', '2025-07-03 10:15:00', '2025-07-03 18:00:00', 'terminee', 'critique', 'DMA2'),
(82, 75, 2, 'corrective', '2025-07-11 08:15:00', '2025-07-11 14:00:00', 'terminee', 'importante', 'DMA2'),
(83, 76, 3, 'corrective', '2025-07-21 07:45:00', '2025-07-21 16:00:00', 'terminee', 'importante', 'DMA2'),
(84, 77, 2, 'corrective', '2025-07-29 09:15:00', '2025-07-29 11:30:00', 'terminee', 'normale', 'DMA2'),
(85, 78, 3, 'corrective', '2025-08-06 07:15:00', '2025-08-06 15:00:00', 'terminee', 'importante', 'DMA2'),
(86, 79, 2, 'corrective', '2025-08-18 13:45:00', '2025-08-18 17:00:00', 'terminee', 'normale', 'DMA2'),
(87, 80, 3, 'corrective', '2025-08-27 10:15:00', '2025-08-27 16:00:00', 'terminee', 'normale', 'DMA2'),
(88, 81, 2, 'corrective', '2025-09-04 06:45:00', '2025-09-04 14:00:00', 'terminee', 'critique', 'DMA2'),
(89, 82, 3, 'corrective', '2025-09-15 09:15:00', '2025-09-15 13:00:00', 'terminee', 'normale', 'DMA2'),
(90, 83, 2, 'corrective', '2025-09-25 11:45:00', '2025-09-25 15:00:00', 'terminee', 'normale', 'DMA2'),
(91, 84, 3, 'corrective', '2025-10-06 08:15:00', '2025-10-06 12:00:00', 'terminee', 'normale', 'DMA2'),
(92, 85, 2, 'corrective', '2025-10-16 14:15:00', '2025-10-16 19:00:00', 'terminee', 'importante', 'DMA2'),
(93, 86, 3, 'corrective', '2025-10-25 07:45:00', '2025-10-25 13:00:00', 'terminee', 'normale', 'DMA2'),
(94, 87, 2, 'corrective', '2025-11-05 10:15:00', '2025-11-05 15:30:00', 'terminee', 'normale', 'DMA2'),
(95, 88, 3, 'corrective', '2025-11-15 08:15:00', NULL, 'en_cours', 'importante', 'DMA2'),
(96, NULL, 2, 'preventive', '2025-01-07 08:00:00', '2025-01-07 12:00:00', 'terminee', 'normale', 'DMA1'),
(97, NULL, 3, 'preventive', '2025-01-13 08:00:00', '2025-01-13 11:00:00', 'terminee', 'normale', 'DMA1'),
(98, NULL, 2, 'preventive', '2025-01-21 08:00:00', '2025-01-21 10:30:00', 'terminee', 'normale', 'DMA1'),
(99, NULL, 3, 'preventive', '2025-01-29 08:00:00', '2025-01-29 12:00:00', 'terminee', 'normale', 'DMA1'),
(100, NULL, 2, 'preventive', '2025-02-04 08:00:00', '2025-02-04 11:00:00', 'terminee', 'normale', 'DMA1'),
(101, NULL, 3, 'preventive', '2025-02-11 08:00:00', '2025-02-11 12:00:00', 'terminee', 'normale', 'DMA1'),
(102, NULL, 2, 'preventive', '2025-02-18 08:00:00', '2025-02-18 10:00:00', 'terminee', 'normale', 'DMA1'),
(103, NULL, 3, 'preventive', '2025-02-25 08:00:00', '2025-02-25 11:30:00', 'terminee', 'normale', 'DMA1'),
(104, NULL, 2, 'preventive', '2025-03-03 08:00:00', '2025-03-03 12:00:00', 'terminee', 'normale', 'DMA1'),
(105, NULL, 3, 'preventive', '2025-03-11 08:00:00', '2025-03-11 10:30:00', 'terminee', 'normale', 'DMA1'),
(106, NULL, 2, 'preventive', '2025-03-18 08:00:00', '2025-03-18 11:00:00', 'terminee', 'normale', 'DMA1'),
(107, NULL, 3, 'preventive', '2025-03-25 08:00:00', '2025-03-25 12:00:00', 'terminee', 'normale', 'DMA1'),
(108, NULL, 2, 'preventive', '2025-04-01 08:00:00', '2025-04-01 11:00:00', 'terminee', 'normale', 'DMA1'),
(109, NULL, 3, 'preventive', '2025-04-08 08:00:00', '2025-04-08 10:00:00', 'terminee', 'normale', 'DMA1'),
(110, NULL, 2, 'preventive', '2025-04-15 08:00:00', '2025-04-15 12:00:00', 'terminee', 'normale', 'DMA1'),
(111, NULL, 3, 'preventive', '2025-04-22 08:00:00', '2025-04-22 11:30:00', 'terminee', 'normale', 'DMA1'),
(112, NULL, 2, 'preventive', '2025-04-29 08:00:00', '2025-04-29 10:30:00', 'terminee', 'normale', 'DMA1'),
(113, NULL, 3, 'preventive', '2025-05-06 08:00:00', '2025-05-06 11:00:00', 'terminee', 'normale', 'DMA1'),
(114, NULL, 2, 'preventive', '2025-05-13 08:00:00', '2025-05-13 10:00:00', 'terminee', 'normale', 'DMA1'),
(115, NULL, 3, 'preventive', '2025-05-20 08:00:00', '2025-05-20 12:00:00', 'terminee', 'normale', 'DMA1'),
(116, NULL, 2, 'preventive', '2025-05-27 08:00:00', '2025-05-27 11:30:00', 'terminee', 'normale', 'DMA1'),
(117, NULL, 3, 'preventive', '2025-07-01 08:00:00', '2025-07-01 11:30:00', 'terminee', 'normale', 'DMA1'),
(118, NULL, 2, 'preventive', '2025-07-08 08:00:00', '2025-07-08 10:00:00', 'terminee', 'normale', 'DMA1'),
(119, NULL, 3, 'preventive', '2025-07-15 08:00:00', '2025-07-15 12:00:00', 'terminee', 'normale', 'DMA1'),
(120, NULL, 2, 'preventive', '2025-07-22 08:00:00', '2025-07-22 11:00:00', 'terminee', 'normale', 'DMA1'),
(121, NULL, 3, 'preventive', '2025-07-29 08:00:00', '2025-07-29 10:30:00', 'terminee', 'normale', 'DMA1'),
(122, NULL, 2, 'preventive', '2025-08-05 08:00:00', '2025-08-05 11:00:00', 'terminee', 'normale', 'DMA1'),
(123, NULL, 3, 'preventive', '2025-08-12 08:00:00', '2025-08-12 10:00:00', 'terminee', 'normale', 'DMA1'),
(124, NULL, 2, 'preventive', '2025-08-19 08:00:00', '2025-08-19 12:00:00', 'terminee', 'normale', 'DMA1'),
(125, NULL, 3, 'preventive', '2025-08-26 08:00:00', '2025-08-26 11:30:00', 'terminee', 'normale', 'DMA1');

-- --------------------------------------------------------

--
-- Table structure for table `pannes`
--

CREATE TABLE `pannes` (
  `id` int(11) NOT NULL,
  `id_equipement` int(11) DEFAULT NULL,
  `type_panne` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `priorite` enum('basse','moyenne','haute','critique') DEFAULT 'moyenne',
  `statut` enum('en_cours','terminee','en_attente') DEFAULT 'en_cours',
  `usine` enum('DMA1','DMA2') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pannes`
--

INSERT INTO `pannes` (`id`, `id_equipement`, `type_panne`, `description`, `date_debut`, `date_fin`, `priorite`, `statut`, `usine`) VALUES
(1, 1, 'Vibration anormale', 'Vibration excessive sur palier principal, roulement à billes usé', '2025-01-06 07:30:00', '2025-01-06 14:00:00', 'haute', 'terminee', 'DMA1'),
(2, 76, 'Fuite réfrigérant', 'Perte de gaz R410A détectée au niveau du compresseur', '2025-01-09 10:15:00', '2025-01-10 11:30:00', 'critique', 'terminee', 'DMA1'),
(3, 69, 'Surchauffe moteur', 'Température moteur 95°C, ventilateur défaillant', '2025-01-14 08:00:00', '2025-01-14 16:30:00', 'haute', 'terminee', 'DMA1'),
(4, 24, 'Défaut capteur', 'Capteur de position X hors service, erreur E-4521', '2025-01-17 14:20:00', '2025-01-17 18:00:00', 'moyenne', 'terminee', 'DMA1'),
(5, 33, 'Problème hydraulique', 'Pression circuit hydraulique insuffisante 120 bar au lieu de 180', '2025-01-21 06:45:00', '2025-01-22 10:00:00', 'haute', 'terminee', 'DMA1'),
(6, 48, 'Défaut électrique', 'Disjonction répétée circuit puissance four', '2025-01-24 11:00:00', '2025-01-25 09:00:00', 'critique', 'terminee', 'DMA1'),
(7, 66, 'Bourrage film', 'Bourrage répétitif du film d\'emballage', '2025-01-28 15:30:00', '2025-01-28 18:00:00', 'basse', 'terminee', 'DMA1'),
(8, 4, 'Usure brosses', 'Brosses usées à 90%, qualité de finition dégradée', '2025-02-03 08:00:00', '2025-02-03 12:00:00', 'moyenne', 'terminee', 'DMA1'),
(9, 41, 'Défaut résistance', 'Résistance chauffante zone 3 HS, température non atteinte', '2025-02-07 07:00:00', '2025-02-08 14:00:00', 'critique', 'terminee', 'DMA1'),
(10, 80, 'Fuite huile', 'Fuite huile hydraulique au vérin principal', '2025-02-12 09:30:00', '2025-02-12 17:00:00', 'haute', 'terminee', 'DMA1'),
(11, 55, 'Erreur calibration', 'Défaut de calibration, images floues', '2025-02-17 10:00:00', '2025-02-17 15:30:00', 'moyenne', 'terminee', 'DMA1'),
(12, 59, 'Colmatage buses', 'Buses de peinture partiellement colmatées', '2025-02-21 13:00:00', '2025-02-21 18:00:00', 'moyenne', 'terminee', 'DMA1'),
(13, 28, 'Bruit anormal', 'Bruit métallique broche principale', '2025-02-26 06:30:00', '2025-02-26 14:00:00', 'haute', 'terminee', 'DMA1'),
(14, 11, 'Colmatage filtre', 'Filtre huile colmaté, pression différentielle élevée', '2025-03-04 08:15:00', '2025-03-04 11:00:00', 'basse', 'terminee', 'DMA1'),
(15, 70, 'Fuite air comprimé', 'Fuite importante raccord sortie sécheur', '2025-03-10 14:00:00', '2025-03-10 17:30:00', 'moyenne', 'terminee', 'DMA1'),
(16, 36, 'Défaut robot', 'Robot casting erreur axe 3, câble encodeur endommagé', '2025-03-14 07:00:00', '2025-03-15 12:00:00', 'haute', 'terminee', 'DMA1'),
(17, 83, 'Fuite hélium', 'Consommation hélium anormale, fuite circuit test', '2025-03-20 11:30:00', '2025-03-20 16:00:00', 'moyenne', 'terminee', 'DMA1'),
(18, 50, 'Thermocouple HS', 'Thermocouple four maintien défaillant', '2025-03-26 09:00:00', '2025-03-26 13:00:00', 'haute', 'terminee', 'DMA1'),
(19, 19, 'Arrêt convoyeur', 'Moteur convoyeur en surcharge thermique', '2025-04-02 10:00:00', '2025-04-02 15:00:00', 'moyenne', 'terminee', 'DMA1'),
(20, 74, 'Ventilateur HS', 'Ventilateur tour de refroidissement grillé', '2025-04-09 08:30:00', '2025-04-09 17:00:00', 'haute', 'terminee', 'DMA1'),
(21, 22, 'Usure outil', 'Alerte usure outil, vibration détectée', '2025-04-16 06:00:00', '2025-04-16 09:00:00', 'basse', 'terminee', 'DMA1'),
(22, 61, 'Défaut pistolet', 'Pistolet poudre bouché', '2025-04-23 14:30:00', '2025-04-23 17:00:00', 'moyenne', 'terminee', 'DMA1'),
(23, 20, 'Vibration broche', 'Vibration excessive unité diamond cut', '2025-05-05 07:45:00', '2025-05-05 14:30:00', 'haute', 'terminee', 'DMA1'),
(24, 32, 'Capteur niveau', 'Capteur niveau métal HS', '2025-05-12 09:00:00', '2025-05-12 12:00:00', 'moyenne', 'terminee', 'DMA1'),
(25, 43, 'Fuite eau trempe', 'Fuite circuit eau de trempe', '2025-05-19 11:00:00', '2025-05-19 18:00:00', 'haute', 'terminee', 'DMA1'),
(26, 72, 'Purge auto HS', 'Électrovanne purge automatique défaillante', '2025-05-23 15:00:00', '2025-05-23 17:30:00', 'basse', 'terminee', 'DMA1'),
(27, 81, 'Défaut variateur', 'Variateur broche en défaut F-012', '2025-05-28 08:00:00', '2025-05-29 10:00:00', 'critique', 'terminee', 'DMA1'),
(28, 6, 'Courroie cassée', 'Courroie transmission cassée', '2025-06-03 06:30:00', '2025-06-03 10:00:00', 'haute', 'terminee', 'DMA1'),
(29, 56, 'Tube RX faible', 'Puissance tube RX insuffisante', '2025-06-11 10:30:00', '2025-06-12 16:00:00', 'haute', 'terminee', 'DMA1'),
(30, 52, 'Rotor usé', 'Rotor dégazage usé, efficacité réduite', '2025-06-18 14:00:00', '2025-06-18 18:30:00', 'moyenne', 'terminee', 'DMA1'),
(31, 64, 'Capteur présence', 'Capteur présence pièce défaillant', '2025-06-25 09:15:00', '2025-06-25 12:00:00', 'basse', 'terminee', 'DMA1'),
(32, 76, 'Surchauffe chiller', 'Température condenseur trop élevée', '2025-07-02 11:00:00', '2025-07-02 18:00:00', 'critique', 'terminee', 'DMA1'),
(33, 69, 'Filtre air colmaté', 'Filtre aspiration colmaté, débit réduit', '2025-07-08 08:00:00', '2025-07-08 10:30:00', 'moyenne', 'terminee', 'DMA1'),
(34, 34, 'Surchauffe moule', 'Température moule hors tolérance', '2025-07-14 07:30:00', '2025-07-14 15:00:00', 'haute', 'terminee', 'DMA1'),
(35, 26, 'Pompe arrosage', 'Pompe arrosage haute pression HS', '2025-07-18 13:00:00', '2025-07-19 09:00:00', 'haute', 'terminee', 'DMA1'),
(36, 45, 'Défaut ventilation', 'Ventilateur four vieillissement grillé', '2025-07-24 10:00:00', '2025-07-24 17:00:00', 'haute', 'terminee', 'DMA1'),
(37, 62, 'Surchauffe four', 'Four polymérisation surchauffe zone 2', '2025-07-29 14:30:00', '2025-07-30 11:00:00', 'critique', 'terminee', 'DMA1'),
(38, 2, 'Défaut équilibrage', 'Erreur mesure équilibrage, capteur décalé', '2025-08-04 08:30:00', '2025-08-04 14:00:00', 'moyenne', 'terminee', 'DMA1'),
(39, 75, 'Pompe circulation', 'Pompe circulation tour arrêtée', '2025-08-11 09:00:00', '2025-08-11 16:00:00', 'haute', 'terminee', 'DMA1'),
(40, 49, 'Porte four bloquée', 'Vérin porte four bloqué en position fermée', '2025-08-18 07:00:00', '2025-08-18 13:00:00', 'haute', 'terminee', 'DMA1'),
(41, 57, 'Défaut détecteur', 'Détecteur flat panel pixels morts', '2025-08-22 11:30:00', '2025-08-23 15:00:00', 'haute', 'terminee', 'DMA1'),
(42, 84, 'Fuite circuit test', 'Fuite eau circuit test étanchéité', '2025-08-28 14:00:00', '2025-08-28 17:30:00', 'moyenne', 'terminee', 'DMA1'),
(43, 30, 'Axe Z bloqué', 'Axe Z bloqué, vis à billes endommagée', '2025-09-03 06:45:00', '2025-09-04 14:00:00', 'critique', 'terminee', 'DMA1'),
(44, 39, 'Défaut préhenseur', 'Préhenseur robot ne ferme plus', '2025-09-10 10:00:00', '2025-09-10 15:00:00', 'moyenne', 'terminee', 'DMA1'),
(45, 71, 'Clapet anti-retour', 'Clapet anti-retour défaillant', '2025-09-17 08:30:00', '2025-09-17 12:00:00', 'moyenne', 'terminee', 'DMA1'),
(46, 60, 'Chaîne convoyeur', 'Chaîne convoyeur peinture détendue', '2025-09-24 13:00:00', '2025-09-24 18:00:00', 'haute', 'terminee', 'DMA1'),
(47, 17, 'Erreur palpage', 'Stylet CMM endommagé, erreurs de mesure', '2025-10-02 09:00:00', '2025-10-02 12:30:00', 'moyenne', 'terminee', 'DMA1'),
(48, 82, 'Fuite hydraulique', 'Fuite flexible haute pression', '2025-10-08 07:30:00', '2025-10-08 14:00:00', 'haute', 'terminee', 'DMA1'),
(49, 42, 'Brûleur défaillant', 'Brûleur zone 1 ne s\'allume plus', '2025-10-15 06:00:00', '2025-10-15 16:00:00', 'critique', 'terminee', 'DMA1'),
(50, 10, 'Vis sans fin usée', 'Vis sans fin transport copeaux usée', '2025-10-21 11:00:00', '2025-10-21 17:00:00', 'moyenne', 'terminee', 'DMA1'),
(51, 78, 'Joint pompe', 'Joint mécanique pompe fuyant', '2025-10-28 14:30:00', '2025-10-28 18:00:00', 'moyenne', 'terminee', 'DMA1'),
(52, 37, 'Galet usé', 'Galets roller casting usés', '2025-11-04 08:00:00', '2025-11-04 15:00:00', 'moyenne', 'terminee', 'DMA1'),
(53, 23, 'Broche bloquée', 'Broche bloquée suite collision', '2025-11-12 07:00:00', NULL, 'critique', 'en_cours', 'DMA1'),
(54, 48, 'Défaut réfractaire', 'Usure réfractaire four fusion détectée', '2025-11-18 10:30:00', NULL, 'haute', 'en_attente', 'DMA1'),
(55, 110, 'Défaut allumage', 'Brûleur principal ne démarre pas', '2025-01-10 06:30:00', '2025-01-10 14:00:00', 'critique', 'terminee', 'DMA2'),
(56, 123, 'Surpression', 'Soupape sécurité déclenchée', '2025-01-20 09:00:00', '2025-01-20 13:00:00', 'haute', 'terminee', 'DMA2'),
(57, 88, 'Moteur surchauffe', 'Protection thermique moteur déclenchée', '2025-01-27 11:30:00', '2025-01-27 16:00:00', 'moyenne', 'terminee', 'DMA2'),
(58, 101, 'Vérin bloqué', 'Vérin fermeture moule bloqué', '2025-02-05 07:00:00', '2025-02-05 15:00:00', 'haute', 'terminee', 'DMA2'),
(59, 96, 'Défaut lubrification', 'Alarme niveau lubrifiant bas', '2025-02-14 08:30:00', '2025-02-14 11:00:00', 'moyenne', 'terminee', 'DMA2'),
(60, 114, 'Porte bloquée', 'Porte cabine RX ne ferme plus', '2025-02-20 10:00:00', '2025-02-20 14:30:00', 'moyenne', 'terminee', 'DMA2'),
(61, 106, 'Fuite gaz', 'Micro-fuite circuit gaz détectée', '2025-02-26 13:00:00', '2025-02-27 10:00:00', 'critique', 'terminee', 'DMA2'),
(62, 91, 'Vibration outil', 'Vibration excessive outil diamant', '2025-03-06 09:15:00', '2025-03-06 14:00:00', 'haute', 'terminee', 'DMA2'),
(63, 126, 'Niveau eau bas', 'Niveau bassin tour trop bas', '2025-03-17 07:30:00', '2025-03-17 10:00:00', 'basse', 'terminee', 'DMA2'),
(64, 130, 'Défaut asservissement', 'Erreur asservissement axe C', '2025-03-25 11:00:00', '2025-03-25 18:00:00', 'haute', 'terminee', 'DMA2'),
(65, 117, 'Pompe peinture', 'Pompe circulation peinture défaillante', '2025-04-07 08:00:00', '2025-04-07 16:00:00', 'haute', 'terminee', 'DMA2'),
(66, 104, 'Câble robot', 'Câble puissance robot endommagé', '2025-04-15 10:30:00', '2025-04-16 09:00:00', 'critique', 'terminee', 'DMA2'),
(67, 98, 'Fuite huile', 'Fuite huile broche', '2025-04-24 14:00:00', '2025-04-24 18:30:00', 'moyenne', 'terminee', 'DMA2'),
(68, 86, 'Capteur vitesse', 'Capteur vitesse rotation HS', '2025-05-08 07:45:00', '2025-05-08 12:00:00', 'moyenne', 'terminee', 'DMA2'),
(69, 111, 'Thermocouple', 'Thermocouple type K défaillant', '2025-05-19 09:30:00', '2025-05-19 13:00:00', 'moyenne', 'terminee', 'DMA2'),
(70, 120, 'Vérin pneumatique', 'Vérin poussoir défaillant', '2025-05-28 15:00:00', '2025-05-28 18:00:00', 'basse', 'terminee', 'DMA2'),
(71, 108, 'Résistance HS', 'Résistance chauffante grillée', '2025-06-05 06:00:00', '2025-06-05 15:00:00', 'haute', 'terminee', 'DMA2'),
(72, 115, 'Source affaiblie', 'Puissance source RX insuffisante', '2025-06-16 11:00:00', '2025-06-17 14:00:00', 'haute', 'terminee', 'DMA2'),
(73, 124, 'Filtre séparateur', 'Filtre séparateur eau saturé', '2025-06-26 08:30:00', '2025-06-26 11:00:00', 'basse', 'terminee', 'DMA2'),
(74, 127, 'Surchauffe compresseur', 'Compresseur chiller en surchauffe', '2025-07-03 10:00:00', '2025-07-03 18:00:00', 'critique', 'terminee', 'DMA2'),
(75, 102, 'Fuite circuit eau', 'Fuite refroidissement moule', '2025-07-11 08:00:00', '2025-07-11 14:00:00', 'haute', 'terminee', 'DMA2'),
(76, 95, 'Défaut axe Y', 'Erreur suivie axe Y', '2025-07-21 07:30:00', '2025-07-21 16:00:00', 'haute', 'terminee', 'DMA2'),
(77, 93, 'Palpeur cassé', 'Palpeur CMM cassé', '2025-07-29 09:00:00', '2025-07-29 11:30:00', 'moyenne', 'terminee', 'DMA2'),
(78, 131, 'Défaut hydraulique', 'Pression hydraulique instable', '2025-08-06 07:00:00', '2025-08-06 15:00:00', 'haute', 'terminee', 'DMA2'),
(79, 118, 'Électrovanne', 'Électrovanne poudre bloquée', '2025-08-18 13:30:00', '2025-08-18 17:00:00', 'moyenne', 'terminee', 'DMA2'),
(80, 132, 'Spectro défaut', 'Spectromètre hélium décalibré', '2025-08-27 10:00:00', '2025-08-27 16:00:00', 'moyenne', 'terminee', 'DMA2'),
(81, 112, 'Rotor cassé', 'Rotor dégazeur cassé', '2025-09-04 06:30:00', '2025-09-04 14:00:00', 'critique', 'terminee', 'DMA2'),
(82, 103, 'Capteur pression', 'Capteur pression injection HS', '2025-09-15 09:00:00', '2025-09-15 13:00:00', 'moyenne', 'terminee', 'DMA2'),
(83, 89, 'Usure brosse', 'Brosses usées à remplacer', '2025-09-25 11:30:00', '2025-09-25 15:00:00', 'basse', 'terminee', 'DMA2'),
(84, 99, 'Alarme surcharge', 'Surcharge broche détectée', '2025-10-06 08:00:00', '2025-10-06 12:00:00', 'moyenne', 'terminee', 'DMA2'),
(85, 109, 'Chaîne convoyeur', 'Chaîne convoyeur détachée', '2025-10-16 14:00:00', '2025-10-16 19:00:00', 'haute', 'terminee', 'DMA2'),
(86, 128, 'Garniture pompe', 'Garniture mécanique fuyante', '2025-10-25 07:30:00', '2025-10-25 13:00:00', 'moyenne', 'terminee', 'DMA2'),
(87, 116, 'Erreur calibration', 'Calibration automatique échouée', '2025-11-05 10:00:00', '2025-11-05 15:30:00', 'moyenne', 'terminee', 'DMA2'),
(88, 101, 'Vérin injection', 'Vérin injection lent', '2025-11-15 08:00:00', NULL, 'haute', 'en_cours', 'DMA2');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `role` enum('admin','responsable','technicien') DEFAULT 'technicien'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Ghanim', 'Farid', 'farid.ghanim@dika.com', 'ghanim123', 'technicien'),
(2, 'Moutawakil', 'Adnane', 'adnane.moutawakil@dika.com', 'moutawakil123', 'technicien'),
(3, 'Alauoi', 'Ali', 'ali.alauoi@dma.com', 'alauoi123', 'technicien'),
(4, 'Tijan', 'Hamza', 'hamza.tijan@dma.com', 'tijan123', 'technicien'),
(9, 'Filali', 'Youness', 'Youness.filali@dika.com', 'admin123', 'admin'),
(10, 'Bernoussi', 'Ahmed', 'ahmed.bernoussi@dika.com', 'tech123', 'technicien'),
(11, 'Essakhi', 'Laila', 'laila.essakhi@dika.com', 'resp123', 'responsable');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipements`
--
ALTER TABLE `equipements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_equipements_usine` (`usine`);

--
-- Indexes for table `interventions`
--
ALTER TABLE `interventions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_panne` (`id_panne`),
  ADD KEY `id_technicien` (`id_technicien`),
  ADD KEY `idx_interventions_usine` (`usine`);

--
-- Indexes for table `pannes`
--
ALTER TABLE `pannes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_equipement` (`id_equipement`),
  ADD KEY `idx_pannes_usine` (`usine`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipements`
--
ALTER TABLE `equipements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `interventions`
--
ALTER TABLE `interventions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `pannes`
--
ALTER TABLE `pannes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `interventions`
--
ALTER TABLE `interventions`
  ADD CONSTRAINT `interventions_ibfk_1` FOREIGN KEY (`id_panne`) REFERENCES `pannes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `interventions_ibfk_2` FOREIGN KEY (`id_technicien`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pannes`
--
ALTER TABLE `pannes`
  ADD CONSTRAINT `pannes_ibfk_1` FOREIGN KEY (`id_equipement`) REFERENCES `equipements` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
