-- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
--
-- Host: mysql.info.unicaen.fr    Database: niveau_dev
-- ------------------------------------------------------
-- Server version	5.5.47-0+deb7u1-log

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
-- Table structure for table `egyptologists`
--

DROP TABLE IF EXISTS `egyptologists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `egyptologists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `discovery` varchar(2043) DEFAULT NULL,
  `birth_year` int(11) DEFAULT NULL,
  `death_year` int(11) DEFAULT NULL,
  `image` int(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `egyptologists`
--

LOCK TABLES `egyptologists` WRITE;
/*!40000 ALTER TABLE `egyptologists` DISABLE KEYS */;
INSERT INTO `egyptologists` VALUES (1,'Howard Carter','the Tomb of Tutankhamun',1874,1939,0),
                                  (2,'Jean-François Champollion','a working method to translate hieroglyphs.',1790,1832,1),
                                  (3,'Auguste Mariette','the Serapeum of Saqqara.',1808,1894,0),
                                  (4,'Hussein Bassir', 'the valley of Golden Mummies', 1973, 3001,0),
                                  (5,'Sarah Parcak', '17 new pyramids using satellite imaging.', 1979, 3001,0), 
                                  (6,'Charles Edwin Wilbour', 'the Elephantine Papyri', 1833,1896,0),
                                  (7,'Günter Dreyer', 'the burial site of the kin (U-j), the earliest known large royal tomb of old Egypt.', 1943, 2019,0);
/*!40000 ALTER TABLE `egyptologists` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-16 18:30:36
