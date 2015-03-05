-- MySQL dump 10.13  Distrib 5.1.67, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: apikey
-- ------------------------------------------------------
-- Server version	5.1.67-log

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

CREATE DATABASE IF NOT EXISTS apikey;

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Contains every api app that may be exposed to a client';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app`
--

LOCK TABLES `app` WRITE;
/*!40000 ALTER TABLE `app` DISABLE KEYS */;
INSERT INTO `app` VALUES (1,'dips','Document,Invoice,P-Card,Scanning'),(2,'carlib','Cemetery Account Recievable'),(3,'lotinv','Lot Inventory'),(4,'corpstruct','Corporate Structure Maintenance App'),(5,'docscan','Document Scanning'),(6,'csi','Cemetery Space Inventory');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Stores every client that has been given access the stonemor ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (1,'N2MzNjc3MjNjZDA4MjUyYzJhNjFjZGEx','MTS IntellaFlow','2013-03-26 04:00:00','2014-04-03 04:00:00','API access for DIPS'),(2,'MmRkMTA3NzhlZWJhYmFmYjEwYWE4YjJm','MTS Intella Flow','2013-04-24 15:26:41','2017-01-01 05:00:00','Allow MTS access to security api'),(3,'Y2FhYTUwNGI3OWJjNDg5YzEyMjAxZDdh','MTS Intella Flow','2013-08-21 13:53:30','2017-08-24 04:00:00','MTS api key for test environment'),(4,'YTczZTVlOWMxOTE3ODVmNTlhMTY4OTVl','StoneMor','2013-11-18 05:00:00','2017-11-15 05:00:00','Corpstruct API Key for test environment'),(5,'OGI1ZjNhYmJlOGNkMzFlMTVmMGQzMzk4','StoneMor','2013-12-05 13:40:00','2017-12-05 13:40:00','API key for document scanning app'),(6,'NDNlMzE4N2EyZTg3OTljYmM3OWVjMDY4','StoneMor','2013-12-16 18:00:00','2015-12-16 18:00:00','API key for cemetery space inventory web app');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='describes the relationship between client and app tables';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_app`
--

LOCK TABLES `client_app` WRITE;
/*!40000 ALTER TABLE `client_app` DISABLE KEYS */;
INSERT INTO `client_app` VALUES (1,1,1,0),(4,2,1,0),(5,3,1,1),(6,4,4,1),(7,5,5,1),(8,6,6,1);
/*!40000 ALTER TABLE `client_app` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-02-02 16:33:22
