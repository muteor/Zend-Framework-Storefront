USE storefront;

/*!40000 ALTER TABLE `category` DISABLE KEYS */;
LOCK TABLES `category` WRITE;
INSERT INTO `storefront`.`category` VALUES  (1,'Cashmere',6,'cashmere'),
 (2,'Silk',6,'silk'),
 (3,'Woolen',6,'woolen'),
 (4,'Caps',7,'caps'),
 (5,'Beenie',7,'beenie'),
 (6,'Scarves',0,'scarves'),
 (7,'Hats',0,'hats');
UNLOCK TABLES;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

/*!40000 ALTER TABLE `categoryImage` DISABLE KEYS */;
LOCK TABLES `categoryImage` WRITE;
INSERT INTO `storefront`.`categoryImage` VALUES  (1,'hats_sm.png','hats.png',7),
 (2,'scarf_sm.png','scarf.png',6);
UNLOCK TABLES;
/*!40000 ALTER TABLE `categoryImage` ENABLE KEYS */;

/*!40000 ALTER TABLE `product` DISABLE KEYS */;
LOCK TABLES `product` WRITE;
INSERT INTO `storefront`.`product` VALUES  (1,7,'product-1','product 1','My lovely product...','My lovely product...','10.99',0,'Yes','Mail','InStock'),
 (2,7,'product-2','product 2','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock');
UNLOCK TABLES;
/*!40000 ALTER TABLE `product` ENABLE KEYS */;