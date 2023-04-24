-- MariaDB dump 10.18  Distrib 10.5.8-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: rpias_server
-- ------------------------------------------------------
-- Server version	10.5.8-MariaDB-1:10.5.8+maria~focal

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `commands`
--

CREATE DATABASE rpias_server;
USE rpias_server;

DROP TABLE IF EXISTS `rpias_server`.`commands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rpias_server`.`commands` (
  `command_id` int(11) NOT NULL AUTO_INCREMENT,
  `command` text DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `response_date_time` datetime DEFAULT NULL,
  `executed` tinyint(1) NOT NULL DEFAULT 0,
  `response` text DEFAULT NULL,
  `exec_time` datetime DEFAULT NULL,
  `error` tinyint(1) NOT NULL DEFAULT 0,
  `host` varchar(50) DEFAULT NULL,
  `consumed` tinyint(1) NOT NULL DEFAULT 0,
  `to_be_exec` datetime DEFAULT NULL,
  `command_consumed_date_time` datetime DEFAULT NULL,
  `mongo_id` varchar(24) DEFAULT NULL,
  PRIMARY KEY (`command_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commands`
--

LOCK TABLES `commands` WRITE;
/*!40000 ALTER TABLE `commands` DISABLE KEYS */;
/*!40000 ALTER TABLE `commands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_log`
--

DROP TABLE IF EXISTS `rpias_server`.`system_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rpias_server`.`system_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `cpu_utilization` decimal(10,2) DEFAULT NULL,
  `system_load` varchar(45) DEFAULT NULL,
  `memory_free` float DEFAULT NULL,
  `memory_used` float DEFAULT NULL,
  `network_utilization` varchar(45) DEFAULT NULL,
  `disk_utilization` varchar(45) DEFAULT NULL,
  `hostname` varchar(45) DEFAULT NULL,
  `log_time` datetime DEFAULT NULL,
  `uptime_p` varchar(45) DEFAULT NULL,
  `uptime_s` varchar(45) DEFAULT NULL,
  `process_number` int(11) DEFAULT NULL,
  `logged_in_users` varchar(255) DEFAULT NULL,
  `logged_in_users_count` int(11) DEFAULT NULL,
  `users_work` text DEFAULT NULL,
  `ip_internal` varchar(15) DEFAULT NULL,
  `ip_external` varchar(15) DEFAULT NULL,
  `extra` text DEFAULT NULL,
  `log_server_time` datetime DEFAULT NULL,
  `disk_usage` varchar(45) DEFAULT NULL,
  `cpu_temp` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  UNIQUE KEY `log_id_UNIQUE` (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_log`
--

LOCK TABLES `system_log` WRITE;
/*!40000 ALTER TABLE `system_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_log` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-18 19:12:03
