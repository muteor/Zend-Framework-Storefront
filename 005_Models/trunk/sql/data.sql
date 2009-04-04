/*!40000 ALTER TABLE `category` DISABLE KEYS */;
LOCK TABLES `category` WRITE;
INSERT INTO `category` VALUES  (1,'Cashmere',6,'cashmere'),
 (2,'Silk',6,'silk'),
 (3,'Woolen',6,'woolen'),
 (4,'Caps',7,'caps'),
 (5,'Beenie',7,'beenie'),
 (6,'Scarves',0,'scarves'),
 (7,'Hats',0,'hats');
UNLOCK TABLES;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

/*!40000 ALTER TABLE `product` DISABLE KEYS */;
LOCK TABLES `product` WRITE;
INSERT INTO `product` VALUES  
 (1,7,'product-1','product 1','My lovely product...','My lovely product...','10.99',0,'Yes','Mail','InStock'),
 (2,7,'product-2','product 2','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (3,6,'product-3','product 3','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (4,5,'product-4','product 4','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (5,4,'product-5','product 5','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (6,3,'product-6','product 6','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (7,2,'product-7','product 7','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (8,1,'product-8','product 8','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (9,1,'product-9','product 9','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (10,2,'product-10','product 10','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (11,3,'product-11','product 11','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (12,4,'product-12','product 12','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (13,5,'product-13','product 13','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (14,6,'product-14','product 14','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (15,7,'product-15','product 15','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (16,6,'product-16','product 16','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (17,5,'product-17','product 17','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (18,4,'product-18','product 18','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock'),
 (19,3,'product-19','product 19','My lovely product...','My lovely product...','11.99',0,'Yes','Mail','InStock')
;
UNLOCK TABLES;
/*!40000 ALTER TABLE `product` ENABLE KEYS */;