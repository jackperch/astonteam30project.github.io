-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2023 at 02:08 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `acegear`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `addressID` int(11) NOT NULL,
  `house_number` int(11) NOT NULL,
  `Address_Line1` text NOT NULL,
  `Address_Line2` text NOT NULL,
  `Postcode` text NOT NULL,
  `City` text NOT NULL,
  `Country` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`addressID`, `house_number`, `Address_Line1`, `Address_Line2`, `Postcode`, `City`, `Country`) VALUES
(1, 0, 'BBB', 'BBB', 'BBB', 'BBB', 'BBB'),
(2, 11, 'T1', 'T1', 'T1', 'T1', 'T1'),
(3, 11, 'SS', 'SS', 'SS', 'SS', 'SS');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `username`, `password`, `first_name`, `last_name`, `email`) VALUES
(1, 's', '$2y$10$TLICk1TDkoWZ7scqPai.ze27qMeheB1jxWpBu04DkYPMuKuWioXEu', 's', 's', 's'),
(2, 'b', '$2y$10$deQvcdR09YY1UlwYXsxsg.HXHDJH/GBVNLLQj8bG7KufkrLbqmTym', 'A', 'b', 'b'),
(3, 'bb', '$2y$10$kcneanToK9rK/0HrY.xXOOoVOPmZ3zt/0KF8u7hOuO.43oCpB5KEm', 'Aa', 'b', 'b'),
(4, 'BBB', '$2y$10$sCQRcbRrmKXu1Trzag.pFOePZRKwctdMRRtAeNyF4R480PkFLtDn.', 'BBB', 'BBB', 'BBB'),
(5, 'T1', '$2y$10$9LuEaXjs9wG6TCftnhVEUu9nVGsycnMe2D9XA43qUwh8FflYm4NY6', 'T1', 'T1', 'T1'),
(6, 'SS', '$2y$10$XH6MVmYE8CV894L7c3MnLOnIiEo.831/kvuMfOd6Dxv1MlFY7w9g6', 'SS', 'SS', 'SS');

-- --------------------------------------------------------

--
-- Table structure for table `customer_address`
--

CREATE TABLE `customer_address` (
  `Customer_AddressID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `addressID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_address`
--

INSERT INTO `customer_address` (`Customer_AddressID`, `CustomerID`, `addressID`) VALUES
(0, 5, 2),
(0, 6, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`addressID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD KEY `customerID_fk` (`CustomerID`),
  ADD KEY `addressID_fk` (`addressID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD CONSTRAINT `customer_address_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`),
  ADD CONSTRAINT `customer_address_ibfk_2` FOREIGN KEY (`addressID`) REFERENCES `address` (`addressID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE table Category (
  'categoryID' int NOT NULL PRIMARY KEY,
  'name' VARCHAR NOT NULL,
  'description' VARCHAR NOT NULL
)

CREATE table ProductListing (
  'productListingID' int NOT NULL PRIMARY KEY,
  'productName' VARCHAR NOT NULL,
  'price' double NOT NULL,
  'description' VARCHAR NOT NULL,
  'ctategoryID' int NOT NULL,
  FOREIGN KEY (ctategoryID) REFERENCES Category (ctategoryID)
)

CREATE Table Admin (
  'adminID' int NOT NULL PRIMARY KEY,
  'username' VARCHAR NOT NULL,
  'password' VARCHAR NOT NULL
)

CREATE table StockLevel (
  'stockID' int NOT NULL PRIMARY KEY,
  'quantity' int NOT NULL,
  'modified' Time NOT NULL,
  'adminID' int NOT NULL,
  FOREIGN KEY (adminID) REFERENCES Admin (adminID)
)

CREATE table ProductListingInfo (
  'productListingInfoID' int NOT NULL PRIMARY KEY,
  'productListingID' int NOT NULL,
  'color' VARCHAR NOT NULL,
  'size' int NOT NULL,
  'stockID' int NOT NULL,
  FOREIGN KEY (productListingID) REFERENCES ProductListing (productListingID),
  FOREIGN KEY (stockID) REFERENCES StockLevel (stockID)
)

CREATE table ProductOrderPlaced (
  'orderID' int NOT NULL PRIMARY KEY,
  'CustomerID' int NOT NULL,
  'productListingID' int NOT NULL,
  FOREIGN KEY (CustomerID) REFERENCES customers(CustomerID),
  FOREIGN KEY (productListingID) REFERENCES ProductListing (productListingID)
)

CREATE table ProductOrderDetails (
  'ProductOrderDetailsID' int NOT NULL PRIMARY KEY,
  'orderID' int NOT NULL,
  'productListingID' int NOT NULL,
  'quantity' double NOT NULL,
  'price' double NOT NULL,
  'color' VARCHAR NOT NULL,
  'size' int NOT NULL,
  'date_purchased' date,
  FOREIGN KEY (orderID) REFERENCES ProductOrderPlaced (orderID)
)

CREATE table Basket (
  'basketID' int NOT NULL PRIMARY KEY,
  'customerID' int NOT NULL,
  'productListingID' int NOT NULL,
  FOREIGN KEY (customerID) REFERENCES customers (customerID),
  FOREIGN KEY (productListingID) REFERENCES ProductListing (productListingID)
)

CREATE table GuestCart (
  'guestCartID' int NOT NULL PRIMARY KEY,
  'orderID' int NOT NULL,
  'productListingID' int NOT NULL,
  'quantity' double NOT NULL,
  'price' double NOT NULL,
  'color' VARCHAR NOT NULL,
  'size' int NOT NULL,
  'date_purchased' date,
  FOREIGN KEY (orderID) REFERENCES ProductOrderPlaced (orderID)
)

CREATE table Guest (
  'guestID' int NOT NULL PRIMARY KEY,
  'guestEmail' VARCHAR NOT NULL,
  'guestFname' VARCHAR NOT NULL,
  'guestSname' VARCHAR NOT NULL
)
ALTER TABLE ProductOrderPlaced MODIFY ProductOrderPlacedID INT AUTO_INCREMENT;
ALTER TABLE Guest MODIFY guestID INT AUTO_INCREMENT;
ALTER TABLE Basket MODIFY basketID INT AUTO_INCREMENT;
ALTER TABLE GuestCart MODIFY guestCartID INT AUTO_INCREMENT;
ALTER TABLE ProductOrderDetails MODIFY ProductOrderDetailsID INT AUTO_INCREMENT;
ALTER TABLE ProductListingInfo MODIFY productListingInfoID INT AUTO_INCREMENT;
ALTER TABLE StockLevel MODIFY stockID INT AUTO_INCREMENT;
ALTER TABLE Admin MODIFY adminID INT AUTO_INCREMENT;
ALTER TABLE ProductListing MODIFY productListingID INT AUTO_INCREMENT;
ALTER TABLE Category MODIFY categoryID INT AUTO_INCREMENT;


