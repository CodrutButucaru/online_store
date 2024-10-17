-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 04, 2024 at 05:42 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `magazin_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Electrocasnice'),
(2, 'Electronice'),
(3, 'Colectii Carti'),
(4, 'Imbracaminte'),
(5, 'Jucarii'),
(6, 'Accesorii'),
(7, 'Accesorii Sportive'),
(8, 'Papetarie');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`) VALUES
(94, 1, '2024-10-04 17:35:30'),
(93, 1, '2024-10-04 17:35:09'),
(92, 1, '2024-10-04 17:34:55'),
(91, 1, '2024-10-04 17:22:02'),
(90, 1, '2024-10-04 17:17:33'),
(89, 1, '2024-10-04 16:57:30'),
(88, 1, '2024-10-04 16:57:08'),
(87, 1, '2024-10-04 16:56:38'),
(86, 1, '2024-10-04 16:54:30'),
(85, 1, '2024-10-04 16:36:33'),
(84, 1, '2024-10-04 16:36:13'),
(83, 1, '2024-10-04 16:12:04'),
(82, 1, '2024-10-04 15:59:55'),
(81, 1, '2024-10-04 15:57:32'),
(80, 1, '2024-10-04 15:50:05'),
(79, 1, '2024-10-04 15:49:55'),
(78, 1, '2024-10-04 15:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `discounted_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`, `original_price`, `discounted_price`) VALUES
(49, 40, 1, '25.00', NULL, NULL),
(48, 8, 1, '60.00', NULL, NULL),
(48, 21, 13, '833.00', NULL, NULL),
(47, 40, 5, '25.00', NULL, NULL),
(46, 23, 9, '1.00', NULL, NULL),
(46, 36, 1, '80.00', NULL, NULL),
(45, 14, 1, '200.00', NULL, NULL),
(45, 27, 1, '75.00', NULL, NULL),
(45, 29, 1, '85.00', NULL, NULL),
(44, 8, 1, '60.00', NULL, NULL),
(43, 4, 1, '80.00', NULL, NULL),
(43, 6, 1, '357.00', NULL, NULL),
(42, 5, 8, '178.50', NULL, NULL),
(42, 2, 1, '45.00', NULL, NULL),
(41, 40, 4, '25.00', NULL, NULL),
(41, 17, 1, '2.00', NULL, NULL),
(41, 8, 1, '60.00', NULL, NULL),
(40, 36, 2, '80.00', NULL, NULL),
(39, 2, 2, '45.00', NULL, NULL),
(39, 1, 1, '1.00', NULL, NULL),
(39, 3, 1, '4.00', NULL, NULL),
(39, 9, 2, '120.00', NULL, NULL),
(50, 8, 2, '0.00', '60.00', '60.00'),
(50, 1, 1, '0.00', '1428.00', '1428.00'),
(51, 9, 6, '0.00', '120.00', '120.00'),
(52, 2, 1, '0.00', '450.00', '450.00'),
(53, 3, 4, '0.00', '4165.00', '4165.00'),
(54, 1, 8, '0.00', '1428.00', '1142.40'),
(55, 9, 6, '0.00', '120.00', '96.00'),
(56, 8, 1, '0.00', '60.00', '60.00'),
(57, 3, 4, '0.00', '4165.00', '3748.50'),
(58, 40, 10, '0.00', '25.00', '20.00'),
(59, 9, 6, '0.00', '120.00', '96.00'),
(60, 9, 6, '0.00', '120.00', '96.00'),
(61, 19, 7, '0.00', '142.80', '42.80'),
(62, 19, 5, '0.00', '142.80', '42.80'),
(63, 19, 6, '0.00', '142.80', '42.80'),
(64, 1, 5, '0.00', '1428.00', '1285.20'),
(65, 1, 6, '0.00', '1428.00', '1142.40'),
(66, 1, 6, '0.00', '1428.00', '1142.40'),
(69, 1, 6, '0.00', '1428.00', '1142.40'),
(71, 1, 1, '0.00', '1428.00', '1428.00'),
(72, 1, 1, '0.00', '1428.00', '1428.00'),
(73, 1, 1, '0.00', '1428.00', '1428.00'),
(74, 1, 1, '0.00', '1428.00', '1428.00'),
(75, 8, 1, '0.00', '60.00', '60.00'),
(76, 1, 1, '0.00', '1428.00', '1428.00'),
(77, 7, 1, '0.00', '900.00', '900.00'),
(78, 8, 1, '0.00', '60.00', '60.00'),
(79, 8, 1, '0.00', '60.00', '60.00'),
(80, 1, 1, '0.00', '1428.00', '1428.00'),
(81, 1, 1, '0.00', '1428.00', '1428.00'),
(82, 9, 6, '0.00', '120.00', '96.00'),
(83, 35, 6, '0.00', '120.00', '96.00'),
(84, 9, 9, '0.00', '120.00', '96.00'),
(85, 8, 10, '0.00', '60.00', '48.00'),
(86, 40, 1, '0.00', '25.00', '25.00'),
(87, 1, 5, '0.00', '1428.00', '1285.20'),
(88, 9, 20, '0.00', '120.00', '96.00'),
(89, 27, 1, '0.00', '750.00', '650.00'),
(90, 1, 6, '0.00', '1428.00', '1142.40'),
(90, 7, 4, '0.00', '900.00', '810.00'),
(91, 1, 6, '0.00', '1428.00', '1142.40'),
(92, 1, 5, '0.00', '1428.00', '1285.20'),
(93, 8, 1, '0.00', '60.00', '60.00'),
(94, 9, 30, '0.00', '120.00', '96.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `is_taxable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `price`, `is_taxable`) VALUES
(1, 'Televizor LED 40\"', 'Televizor LED cu diagonala de 40 inch.', '1200.00', 1),
(2, 'Colectie carti: Introducere in PHP', 'Carti pentru invatarea limbajului PHP.', '450.00', 0),
(3, 'Laptop Gaming', 'Laptop performant pentru gaming.', '3500.00', 1),
(4, 'Tricou Polo', 'Tricou confortabil din bumbac.', '80.00', 0),
(5, 'Set LEGO', 'Set de constructie LEGO pentru copii.', '150.00', 1),
(6, 'Aspirator fara sac', 'Aspirator eficient pentru curatenie.', '300.00', 1),
(7, 'Kindle', 'Carte audio cu sunet de înalta calitate.', '900.00', 0),
(8, 'Mouse Wireless', 'Mouse fara fir ergonomic.', '60.00', 0),
(9, 'Rochie de vara', 'Rochie lejera pentru sezonul cald.', '120.00', 0),
(10, 'Puzzle 1000 piese', 'Puzzle distractiv cu 1000 de piese.', '70.00', 0),
(11, 'Placa de baza ASUS', 'Placa de baza pentru gaming', '700.00', 1),
(12, 'Telefon Samsung Galaxy', 'Telefon performant Samsung', '3000.00', 1),
(13, 'Televizor OLED 55 inch', 'Televizor OLED de înalta calitate', '5000.00', 1),
(14, 'Rochie de iarna', 'Rochie eleganta pentru iarna', '200.00', 0),
(15, 'Pantofi de alergare', 'Pantofi sport pentru alergare', '250.00', 0),
(16, 'Espressor Philips', 'Espressor automat cu funciii de curatare automata.', '800.00', 1),
(17, 'Frigider Samsung', 'Frigider cu tehnologie No Frost.', '2000.00', 1),
(18, 'Masina de spalat Arctic', 'Masina de spalat rufe cu încarcare frontala.', '1100.00', 1),
(19, 'Mixer de mana', 'Mixer pentru bucatarie.', '120.00', 1),
(20, 'Masina de tocat carne', 'Masina de tocat carne pentru uz casnic.', '300.00', 1),
(21, 'Monitor LED Dell', 'Monitor LED cu rezolutie Full HD.', '700.00', 1),
(22, 'Smartphone iPhone 13', 'Smartphone de ultima generatie.', '4500.00', 1),
(23, 'Banda de alergat electrica', 'Aparat de fitness pentru alergat.', '1500.00', 1),
(24, 'Trotineta electrica Xiaomi', 'Trotineta electrica pliabila.', '1200.00', 1),
(25, 'Castile Wireless Sony', 'Carti wireless cu functie de anulare a zgomotului.', '600.00', 1),
(26, 'Colectie carti: Ghid de supravietuire în jungla', 'O colectie de carti captivanta despre tehnici de supravietuire în jungla.', '500.00', 0),
(27, 'Colectie de carti: Misterele Pamantului', 'Explorarea celor mai mari mistere naturale ale Pamantului.', '750.00', 0),
(28, 'Colectie de carti: Filosofia moderna', 'O introducere în filozofiile care au schimbat lumea.', '600.00', 0),
(29, 'Colectie de carti: Istoria Informatica', 'Istoria dezvoltarii calculatoarelor si a programarii.', '850.00', 0),
(30, 'Colectie de carti: Arta de a negocia', 'Ghidul suprem pentru negocieri de succes.', '950.00', 0),
(31, 'Jucarie: Set de constructie LEGO', 'Set LEGO pentru dezvoltarea imaginatiei copiilor.', '180.00', 1),
(32, 'Jucarie: Papusi Barbie', 'Papusi Barbie cu accesorii de moda.', '130.00', 0),
(33, 'Jucarie: Puzzle 500 piese', 'Puzzle educativ pentru copii.', '55.00', 0),
(34, 'Jucarie: Tren electric', 'Set de tren electric cu sine.', '250.00', 1),
(35, 'Jucarie: Masinuta cu telecomanda', 'Masinuta controlata prin telecomanda, ideala pentru copii.', '120.00', 0),
(36, 'Curea din piele', 'Curea din piele naturala de inalta calitate.', '80.00', 0),
(37, 'Sapca sport', 'Sapca sport usoara si confortabila.', '50.00', 0),
(38, 'Minge de fotbal', 'Minge de fotbal pentru antrenamente si competitii.', '90.00', 0),
(39, 'Banda elastica fitness', 'Banda elastica pentru exercitii de intarire musculara.', '120.00', 0),
(40, 'Set pixuri colorate', 'Set de 10 pixuri colorate pentru birou.', '25.00', 0),
(41, 'Agenda de birou', 'Agenda eleganta cu coperta din piele artificiala.', '85.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE IF NOT EXISTS `product_categories` (
  `product_id` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES
(1, 2),
(2, 3),
(3, 2),
(4, 4),
(5, 5),
(6, 1),
(7, 2),
(8, 2),
(9, 4),
(10, 5),
(11, 2),
(12, 2),
(13, 1),
(14, 4),
(15, 4),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 3),
(27, 3),
(28, 3),
(29, 3),
(30, 3),
(31, 5),
(32, 5),
(33, 5),
(34, 5),
(35, 5),
(36, 6),
(37, 6),
(38, 7),
(39, 7),
(40, 8),
(41, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('client','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`) VALUES
(2, 'Butucaru Codrut', 'user@email.com', '$2y$10$X2KCagat26l./TGntehOJu9Pnhz9HVZYtAm3pNv9EbZoHMZOkbiNm', '0756927622', 'Strada Buftea Nr.7', 'client'),
(1, 'Admin', 'admin@email.com', '$2y$10$yXc51bFAEiC3ZqJwhTFRRuRfvczQeL0hv.DzJfTTcou7QLDYqxyNu', '0723456789', 'Adresa Admin', 'admin'),
(4, 'user2', 'user2@gmail.com', '$2y$10$sbY.aJeDb706K6SyRuTT4.CNDOfo9B9p77p3GP5dJBR30snz.J6aS', '0742301051', 'Bacau', 'client'),
(5, 'user3', 'user3@gmail.com', '$2y$10$Tx6EsmskUwMjHOlEtWj8F.7SeIwyLtR0x5yL2nZ3/2GuYpvih75/K', '1234567890', 'Bacau', 'client');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
