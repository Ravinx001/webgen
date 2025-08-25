-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: sludi
-- ------------------------------------------------------
-- Server version	8.0.33

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auth_logs`
--

DROP TABLE IF EXISTS `auth_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `success` tinyint(1) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_logs`
--

LOCK TABLES `auth_logs` WRITE;
/*!40000 ALTER TABLE `auth_logs` DISABLE KEYS */;
INSERT INTO `auth_logs` VALUES (1,1,'nimal',1,'::1','PostmanRuntime/7.37.3',NULL,'2025-08-03 07:49:45'),(2,1,'nimal',1,'::1','PostmanRuntime/7.37.3',NULL,'2025-08-03 07:50:04'),(3,1,'nimal',1,'::1','PostmanRuntime/7.37.3',NULL,'2025-08-03 07:50:33'),(4,1,'nimal',1,'::1','PostmanRuntime/7.37.3',NULL,'2025-08-03 08:03:38'),(5,1,'nimal',1,'::1','PostmanRuntime/7.37.3',NULL,'2025-08-03 08:06:00'),(6,1,'nimal',1,'::1','PostmanRuntime/7.37.3',NULL,'2025-08-03 08:20:13');
/*!40000 ALTER TABLE `auth_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department_user_role`
--

DROP TABLE IF EXISTS `department_user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `department_user_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_department_role` (`user_id`,`department_id`,`role_id`),
  KEY `fk_dur_department` (`department_id`),
  KEY `fk_dur_role` (`role_id`),
  CONSTRAINT `fk_dur_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_dur_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_dur_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department_user_role`
--

LOCK TABLES `department_user_role` WRITE;
/*!40000 ALTER TABLE `department_user_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `department_user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Agrarian Development Department',NULL,NULL),(2,'Agriculture Department',NULL,NULL),(3,'Animal Production & Health Department',NULL,NULL),(4,'Attorney General\'s Department',NULL,NULL),(5,'Ayurveda Department',NULL,NULL),(6,'Census and Statistics Department',NULL,NULL),(7,'Christian Religious Affairs Department',NULL,NULL),(8,'Commerce Department',NULL,NULL),(9,'Community Based Corrections Department',NULL,NULL),(10,'Cooperative Development Department',NULL,NULL),(11,'Department of Fiscal Policy',NULL,NULL),(12,'Department of National Budget',NULL,NULL),(13,'Department of Management Services',NULL,NULL),(14,'Department of External Resources',NULL,NULL),(15,'Department of Treasury Operations',NULL,NULL),(16,'Department of Trade and Investment Policies',NULL,NULL),(17,'Department of Information Technology Management',NULL,NULL),(18,'Department of Management Audit',NULL,NULL),(19,'Department of Development Finance',NULL,NULL),(20,'Department of Public Enterprises',NULL,NULL),(21,'Department of Project Management and Monitoring',NULL,NULL),(22,'Department of National Planning',NULL,NULL),(23,'Import and Export Control Department',NULL,NULL),(24,'Department of Customs',NULL,NULL),(25,'Department of Government Information',NULL,NULL);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `access_token` varchar(255) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `expires` timestamp NOT NULL,
  `scope` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `access_token` (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(80) NOT NULL,
  `client_secret` varchar(80) NOT NULL,
  `name` varchar(255) NOT NULL,
  `redirect_uri` text,
  `grant_types` text,
  `scope` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_id` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES (1,'client_app_001','secret_abc123','Police Department API Client',NULL,'client_credentials','read write','2025-08-07 14:09:32','2025-08-07 14:09:32'),(2,'client_app_002','secret_xyz789','Health Department API Client',NULL,'client_credentials','read','2025-08-07 14:09:32','2025-08-07 14:09:32'),(3,'client_app_003','secret_def456','Agriculture Department Client','https://example.com/callback','authorization_code','read write','2025-08-07 14:09:32','2025-08-07 14:09:32');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Manager',NULL,NULL),(2,'Officer',NULL,NULL),(3,'Auditor',NULL,NULL),(4,'Employee',NULL,NULL),(5,'Clerk',NULL,NULL),(6,'Director',NULL,NULL),(7,'Assistant Director',NULL,NULL),(8,'Consultant',NULL,NULL),(9,'Legal Advisor',NULL,NULL),(10,'Analyst',NULL,NULL),(11,'Administrator',NULL,NULL),(12,'Staff',NULL,NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `digital_id` char(36) DEFAULT NULL,
  `nic` varchar(20) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(120) NOT NULL,
  `status` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nic` (`nic`),
  UNIQUE KEY `digital_id` (`digital_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'550e8400-e29b-41d4-a716-446655440000','199023402345','456789','Nimal Perera','nimal@example.com','nimal','$2y$10$Zk0FdCftqejF0Hj82zBY4umltVLjHh5HG37GQ4wAut2mk0/r4LxK2',1,'2025-08-01 04:30:00'),(2,'550e8400-e29b-41d4-a716-446655440001','198045607891','123456','Kamal Silva','kamal@example.com','kamal','$2y$10$hZKIt4jYBG3eVzEvdoE6/utFqQ6BI7k6K7.NX.BCgECPWDJYYXEB2',1,'2025-08-01 04:35:00'),(3,'550e8400-e29b-41d4-a716-446655440002','200101201234','789012','Suneth Rajapaksha','suneth@example.com','suneth','$2y$10$Rk45D0ZyQG2Hk9QMCu4rEuq52lMIY9A38jqfzyfkkqzjXZ7jZIv0q',0,'2025-08-01 04:40:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-07 20:00:49
