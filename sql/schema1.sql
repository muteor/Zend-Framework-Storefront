-- MySQL dump 10.11
--
-- Host: localhost    Database: storefront
-- ------------------------------------------------------
-- Server version   5.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `categoryId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(200)  NOT NULL,
  `parentId` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoryImage`
--

DROP TABLE IF EXISTS `categoryImage`;
CREATE TABLE `categoryImage` (
  `imageId` int(10) unsigned NOT NULL auto_increment,
  `thumbnail` varchar(200)  NOT NULL,
  `full` varchar(200)  NOT NULL,
  `categoryId` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`imageId`),
  KEY `categoryImage` (`categoryId`),
  CONSTRAINT `categoryImage` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categoryImage`
--

LOCK TABLES `categoryImage` WRITE;
/*!40000 ALTER TABLE `categoryImage` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoryImage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_product`
--

DROP TABLE IF EXISTS `category_product`;
CREATE TABLE `category_product` (
  `productId` int(10) unsigned NOT NULL,
  `categoryId` int(10) unsigned NOT NULL,
  `isDefault` enum('Yes','No') NOT NULL default 'No',
  PRIMARY KEY  (`productId`,`categoryId`),
  KEY `product` (`productId`),
  KEY `fk_categoryId` (`categoryId`),
  CONSTRAINT `fk_productId` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`) ON DELETE CASCADE,
  CONSTRAINT `fk_categoryId` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category_product`
--

LOCK TABLES `category_product` WRITE;
/*!40000 ALTER TABLE `category_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `category_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `productId` int(10) unsigned NOT NULL auto_increment,
  `ident` varchar(56)  NOT NULL,
  `name` varchar(64)  NOT NULL,
  `description` text  NOT NULL,
  `shortDescription` varchar(200)  NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discountPercent` int(3) NOT NULL,
  `taxable` enum('Yes','No')  NOT NULL,
  `deliveryMethod` enum('Mail','Download')  NOT NULL,
  `stockStatus` enum('InStock','PreOrder','Discontinued','Unavailable')  NOT NULL,
  PRIMARY KEY  (`productId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'product-1','product 1','My lovely product...','My lovely product...','10.99',0,'Yes','Mail','InStock'),(2,'product-2','product 2','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productImage`
--

DROP TABLE IF EXISTS `productImage`;
CREATE TABLE `productImage` (
  `imageId` int(10) unsigned NOT NULL auto_increment,
  `productId` int(10) unsigned NOT NULL,
  `thumbnail` varchar(200)  NOT NULL,
  `full` varchar(200)  NOT NULL,
  PRIMARY KEY  (`imageId`),
  KEY `productId` (`productId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `productImage`
--

LOCK TABLES `productImage` WRITE;
/*!40000 ALTER TABLE `productImage` DISABLE KEYS */;
/*!40000 ALTER TABLE `productImage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userAddress`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE  `user` (
  `userId` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(10) NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `passwd` char(40) NOT NULL,
  `salt` char(32) NOT NULL,
  `role` varchar(100) NOT NULL default 'customer',
  PRIMARY KEY  (`userId`),
  KEY `email_pass` (`email`,`passwd`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-12-04 10:18:27
