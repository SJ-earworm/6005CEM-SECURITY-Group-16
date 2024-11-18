-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2024 at 08:59 PM
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
-- Database: `santasplushiefactory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `carousel_promo`
--

CREATE TABLE `carousel_promo` (
  `promoImageID` bigint(20) UNSIGNED NOT NULL,
  `promoImage` varchar(255) NOT NULL,
  `promoTitle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carousel_promo`
--

INSERT INTO `carousel_promo` (`promoImageID`, `promoImage`, `promoTitle`) VALUES
(1, 'images/banner1.jpg', 'Banner 1'),
(2, 'images/banner2.jpg', 'Banner 2'),
(3, 'images/banner3.jpg', 'Banner 3'),
(14, 'images/bannr4s.jpg', 'It&#39;s Christmas! in the dungeon with &nts');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `pdID` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `db`
--

CREATE TABLE `db` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `role` enum('admin','sub_admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `db`
--

INSERT INTO `db` (`id`, `email`, `name`, `pw`, `role`) VALUES
(9, 'admin1@mail.com', 'Admin1', '$2y$10$w/xdcUqqhp8EjbY2xN1o0uJw0GsX3HMFl6p7QsRuMiVbpd8DKm7ky', 'admin'),
(10, 'admin2@mail.com', 'Admin2', '$2y$10$zOBRUwZrHAE1oGEdDSBZ..KxLCfwztK4O0Rq5XB61vj/Kg/rKp9PG', 'sub_admin'),
(11, 'johndoe@mail.com', 'John Doe', '$2y$10$2OarqFzPJ5xWHGTiwXKF4ewbX1Ryi9b4erkBfig/LRwfWNbLfzTAS', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `billName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` text NOT NULL,
  `state` text NOT NULL,
  `zip` int(5) NOT NULL,
  `datePay` date NOT NULL,
  `cartID` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `billName`, `email`, `address`, `city`, `state`, `zip`, `datePay`, `cartID`) VALUES
(68, 'John Doe', 'johndoe@mail.com', 'The Address Yeet, Summerskye 12', 'Bayan Lepas', 'Penang', 11900, '2024-11-15', 8),
(69, 'John Doe', 'johndoe@mail.com', 'The Address Yeet, Summerskye 12', 'Bayan Lepas', 'Penang', 11900, '2024-11-15', 9),
(70, 'John Doe', 'johndoe@mail.com', 'The Address Yeet, Summerskye 12', 'Bayan Lepas', 'Penang', 11900, '2024-11-15', 10),
(71, 'John Doe', 'johndoe@mail.com', 'The Address Yeet, Summerskye 12', 'Bayan Lepas', 'Penang', 11900, '2024-11-15', 11);

-- --------------------------------------------------------

--
-- Table structure for table `pd_category_relationship`
--

CREATE TABLE `pd_category_relationship` (
  `pdID` bigint(20) UNSIGNED NOT NULL,
  `catID` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pd_category_relationship`
--

INSERT INTO `pd_category_relationship` (`pdID`, `catID`) VALUES
(2, 3),
(2, 1),
(1, 2),
(1, 5),
(1, 1),
(4, 2),
(4, 3),
(4, 5),
(4, 1),
(5, 3),
(7, 2),
(7, 3),
(7, 1),
(8, 2),
(8, 5),
(9, 5),
(10, 4),
(11, 2),
(11, 4),
(12, 4);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pdID` bigint(20) UNSIGNED NOT NULL,
  `pdName` varchar(255) NOT NULL,
  `pdPrice` decimal(5,2) NOT NULL,
  `pdSize` varchar(255) NOT NULL,
  `pdStockCount` int(11) DEFAULT NULL,
  `pdDescription` text DEFAULT NULL,
  `pdImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pdID`, `pdName`, `pdPrice`, `pdSize`, `pdStockCount`, `pdDescription`, `pdImage`) VALUES
(1, 'Wingman Plush', 40.00, '50cm x 30cm x 30cm', 120, 'Great sidekick', 'images/wingman-plushie.jpeg'),
(2, 'Durian Plush', 20.46, '30cm x 30cm x 30cm', 550, 'Spiky guy', 'images/durian.jpg'),
(4, 'Meena - Illuminate&#39;s Sing', 45.00, '50cm x 50cm x 50cm', 200, 'Shy elephant who has a voice big enough to make the roof come down...legit', 'images/Meenaplushforsecurity-enterpriseproject.jpg'),
(5, 'Coffee Bean', 999.99, '30cm x 40cm x 30cm', 50, 'Tea Leaf', 'images/coffeebean.jpg'),
(6, 'Cute Dinosaur', 15.00, '30cm x 50cm x 20cm', 50, 'Rawr', 'images/dinosaur.jpg'),
(7, 'Bunny', 999.99, '80cm x 30cm x 50cm', 80, 'Hoppity Hop', 'images/bunny.jpg'),
(8, 'Pinkfong! Baby Shark', 120.00, '20cm x 20cm x 20cm', 70, 'Doo doo doo doo doo doo', 'images/pinkfongbabyshark.jpg'),
(9, 'MIckey Mouse', 120.00, '50cm x 50cm x 50cm', 210, 'Not copyrighted anymore muahaha', 'images/mck3ymu.se.jpeg'),
(10, 'Santa Claus', 45.00, '50cm x 40cm x 40cm', 180, 'Ho ho ho', 'images/santaclaus.jpg'),
(11, 'Reindeer Plush', 25.00, '25cm x 30cm x 40cm', 60, 'Trusty flying mythical creatures', 'images/reindeer.jpg'),
(12, 'Snowman', 80.00, '40cm x 40cm x 50cm', 230, 'Too cute, need more stock, confirm snagged up quick', 'images/snowman.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `catID` bigint(20) UNSIGNED NOT NULL,
  `catName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`catID`, `catName`) VALUES
(1, 'Featured'),
(2, 'Animals'),
(3, 'Fun'),
(4, 'Festive'),
(5, 'Collaborations');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carousel_promo`
--
ALTER TABLE `carousel_promo`
  ADD PRIMARY KEY (`promoImageID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `pdID` (`pdID`);

--
-- Indexes for table `db`
--
ALTER TABLE `db`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cartID` (`cartID`);

--
-- Indexes for table `pd_category_relationship`
--
ALTER TABLE `pd_category_relationship`
  ADD KEY `pdID` (`pdID`),
  ADD KEY `catID` (`catID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pdID`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`catID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carousel_promo`
--
ALTER TABLE `carousel_promo`
  MODIFY `promoImageID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `db`
--
ALTER TABLE `db`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pdID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `catID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `db` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`pdID`) REFERENCES `product` (`pdID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pd_category_relationship`
--
ALTER TABLE `pd_category_relationship`
  ADD CONSTRAINT `pd_category_relationship_ibfk_1` FOREIGN KEY (`pdID`) REFERENCES `product` (`pdID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pd_category_relationship_ibfk_2` FOREIGN KEY (`catID`) REFERENCES `product_category` (`catID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
