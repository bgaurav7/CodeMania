-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: code_mania
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.10.2

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
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
  `qid` varchar(15) NOT NULL,
  `uid` varchar(15) NOT NULL,
  `comment` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notice`
--

DROP TABLE IF EXISTS `notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notice` (
  `uid` varchar(30) DEFAULT NULL,
  `notice` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notice`
--

LOCK TABLES `notice` WRITE;
/*!40000 ALTER TABLE `notice` DISABLE KEYS */;
INSERT INTO `notice` VALUES (NULL,'Contest Start at 9:00 PM & ENDS AT 12:00 PM');
/*!40000 ALTER TABLE `notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question` (
  `qid` varchar(15) NOT NULL DEFAULT '',
  `setter` varchar(100) DEFAULT NULL,
  `description` text,
  `submissions` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(100) NOT NULL,
  `time_limit` int(3) NOT NULL DEFAULT '10',
`level` int,
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES
('GOD','LOOP INFINITY(Anuj Shrivastava, Pankaj Wadhwani, Shiva Bhalla)','Aloknath and Sanskari Words',0,'data/question/GOD.txt',2,1),
('PROPOSE','Gaurav Bansal','Aloknath and Sanskari Son',0,'data/question/PROPOSE.txt',2,2), 
('COST','LOOP INFINITY(Anuj Shrivastava, Pankaj Wadhwani, Shiva Bhalla)','Aloknath and Sanskari Cost',0,'data/question/COST.txt',2, 4),
('DOLLS','Anirudh Bhargava','Aloknath and his Sanskari Dolls',0,'data/question/DOLLS.txt',2,6),
('MATH','LOOP INFINITY(Anuj Shrivastava, Pankaj Wadhwani, Shiva Bhalla)','Aloknath and Sanskari Maths',0,'data/question/MATH.txt',2, 3),
('KALYAN','LOOP INFINITY(Anuj Shrivastava, Pankaj Wadhwani, Shiva Bhalla)','Aloknath and Kalyan Mandap',0,'data/question/KALYAN.txt',2,5);
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rank`
--

DROP TABLE IF EXISTS `rank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rank` (
  `rank` int(11) NOT NULL,
  `uid` varchar(30) NOT NULL,
  `submissions` int(11) DEFAULT NULL,
  `avg_time` time DEFAULT NULL,
  PRIMARY KEY (`rank`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rank`
--

LOCK TABLES `rank` WRITE;
/*!40000 ALTER TABLE `rank` DISABLE KEYS */;
/*INSERT INTO `rank` VALUES (1,'IIT2012186',3,'838:59:59'),(2,'IIT2012077',1,'838:59:59')*/;
/*!40000 ALTER TABLE `rank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `submissions` (
  `code_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(30) DEFAULT NULL,
  `qid` varchar(30) DEFAULT NULL,
  `md5sum` char(32) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code_id`),
  KEY `uid` (`uid`),
  KEY `qid` (`qid`),
  CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`qid`) REFERENCES `question` (`qid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submissions`
--

LOCK TABLES `submissions` WRITE;
/*!40000 ALTER TABLE `submissions` DISABLE KEYS */;
/*INSERT INTO `submissions` VALUES (2,'IIT2012186','GOD','a1c4a64410742d6f5bf0caa353a1c48a','2014-04-10 13:45:01'),(3,'IIT2012186','MATH','f3cf435535d2d5526490ef218065bbe0','2014-04-11 08:23:07'),(4,'IIT2012186','DOLLS','a330a9f30ef56d48d5473d4f9b3dbf78','2014-04-11 08:27:13'),(5,'IIT2012077','GOD','dd31986b7dba3dc60e87c8d37f146a8c','2014-04-11 08:32:51'),(8,'IIT2012186','PROPOSE','d245cd27efd5016fc7c0641c551da179','2014-04-12 05:01:12')*/;
/*!40000 ALTER TABLE `submissions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-12 10:39:12
