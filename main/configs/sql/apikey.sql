-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: keyserver-test.stonemor.com    Database: apikey
-- ------------------------------------------------------
-- Server version	5.7.23-0ubuntu0.16.04.1-log

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
-- Table structure for table `app`
--

DROP TABLE IF EXISTS `app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_client_idx` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COMMENT='Contains every api app that may be exposed to a client';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app`
--

LOCK TABLES `app` WRITE;
/*!40000 ALTER TABLE `app` DISABLE KEYS */;
INSERT INTO `app` VALUES (1,'dips','Document,Invoice,P-Card,Scanning'),(2,'carlib','Cemetery Account Recievable'),(3,'lotinv','Lot Inventory'),(4,'corpstruct','Corporate Structure Maintenance App'),(5,'docscan','Document Scanning'),(6,'csi','Cemetery Space Inventory'),(7,'docscan2','Document Scanning v2'),(8,'hpate-app','Harshal Testing App'),(9,'orgchrt','Dynamic org charts maintained by ops'),(10,'sbrm','Summarized Business Rule Manager');
/*!40000 ALTER TABLE `app` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apikey` varchar(255) NOT NULL,
  `client_name` varchar(80) NOT NULL,
  `issued_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expiration_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `apikey_idx` (`apikey`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COMMENT='Stores every client that has been given access the stonemor ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (1,'N2MzNjc3MjNjZDA4MjUyYzJhNjFjZGEx','MTS IntellaFlow','2013-03-26 04:00:00','2014-04-03 04:00:00','API access for DIPS'),(2,'MmRkMTA3NzhlZWJhYmFmYjEwYWE4YjJm','MTS Intella Flow','2013-04-24 15:26:41','2017-01-01 05:00:00','Allow MTS access to security api'),(3,'Y2FhYTUwNGI3OWJjNDg5YzEyMjAxZDdh','MTS Intella Flow','2013-08-21 13:53:30','2020-08-24 04:00:00','MTS api key for test environment'),(4,'YTczZTVlOWMxOTE3ODVmNTlhMTY4OTVl','StoneMor','2013-11-18 05:00:00','2020-11-15 05:00:00','Corpstruct API Key for test environment'),(5,'OGI1ZjNhYmJlOGNkMzFlMTVmMGQzMzk4','StoneMor','2013-12-05 13:40:00','2020-12-05 13:40:00','API key for document scanning app'),(6,'NDNlMzE4N2EyZTg3OTljYmM3OWVjMDY4','StoneMor','2013-12-16 18:00:00','2018-12-16 18:00:00','API key for cemetery space inventory web app'),(7,'D0D3AA7517A94037A27F9D9F5257AC04','StoneMor','2015-04-24 04:00:00','2020-04-24 04:00:00','API key for document scanning v2 app'),(8,'M2M2MWI0N2NiMzBiMjM1YmQ0ZjI4MzM0','Harshal Patel','2016-08-24 12:00:00','2016-12-01 05:00:00','API key for Harshal\'s test client'),
  (9,'NzBlYmZmYmFlMjg2NWU5ZWYwNmI3ZTU3','StoneMor','2018-02-11 13:00:00','2020-02-11 05:00:00','API key for Dynamic Org Charts maintained by ops'),
  (10,'NzkwNjk2YzJjNDhkY2NmZDczNjg1ZjY5','StoneMor','2018-02-06 21:34:00','2020-02-06 05:00:00','Summarized Business Rule Manager');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_app`
--

DROP TABLE IF EXISTS `client_app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COMMENT='describes the relationship between client and app tables';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_app`
--

LOCK TABLES `client_app` WRITE;
/*!40000 ALTER TABLE `client_app` DISABLE KEYS */;
INSERT INTO `client_app` VALUES (1,1,1,0),(4,2,1,0),(5,3,1,1),(6,4,4,1),(7,5,5,1),(8,6,6,1),(9,7,7,1),(10,8,8,1),(11,9,9,1),(12,10,10,1);
/*!40000 ALTER TABLE `client_app` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'apikey'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-06 15:06:08
