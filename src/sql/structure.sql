--
-- Definition of table `storefront`.`category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE  `category` (
  `categoryId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `parentId` int(10) unsigned NOT NULL,
  `ident` varchar(200) NOT NULL,
  PRIMARY KEY  (`categoryId`),
  UNIQUE KEY `ident` (`ident`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Definition of table `storefront`.`product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE  `product` (
  `productId` int(10) unsigned NOT NULL auto_increment,
  `categoryId` int(10) unsigned NOT NULL,
  `ident` varchar(56) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `shortDescription` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discountPercent` int(3) NOT NULL,
  `taxable` enum('Yes','No') NOT NULL,
  `deliveryMethod` enum('Mail','Download') NOT NULL,
  `stockStatus` enum('InStock','PreOrder','Discontinued','Unavailable') NOT NULL,
  PRIMARY KEY  (`productId`),
  KEY `category` (`categoryId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Definition of table `storefront`.`productImage`
--

DROP TABLE IF EXISTS `productImage`;
CREATE TABLE  `productImage` (
  `imageId` int(10) unsigned NOT NULL auto_increment,
  `productId` int(10) unsigned NOT NULL,
  `thumbnail` varchar(200) NOT NULL,
  `full` varchar(200) NOT NULL,
  `isDefault` enum('Yes', 'No') NOT NULL,
  PRIMARY KEY  (`imageId`),
  KEY `productId` (`productId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;