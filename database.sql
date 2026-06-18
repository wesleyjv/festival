-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: haarlem-festival-dev-haarlemfestival123.i.aivencloud.com    Database: defaultdb
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cuisine_tags`
--

DROP TABLE IF EXISTS `cuisine_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cuisine_tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cuisine_tags_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuisine_tags`
--

LOCK TABLES `cuisine_tags` WRITE;
/*!40000 ALTER TABLE `cuisine_tags` DISABLE KEYS */;
INSERT INTO `cuisine_tags` VALUES (3,'Dutch'),(5,'Fine Dining'),(1,'French'),(6,'International'),(2,'Seafood'),(4,'Vegan');
/*!40000 ALTER TABLE `cuisine_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('jazz','yummy','history','story') NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'Gumbo Kings','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(2,'Evolve','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(3,'Ntjam Rosie','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(4,'Wicked Jazz Sounds','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(5,'Wouter Hamel','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(6,'Jonna Frazer','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(7,'Karsu','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(8,'Uncle Sue','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(9,'Chris Allen','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(10,'Myles Sanko','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(11,'Ilse Huizinga','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(12,'Eric Vloeimans and Hotspot!','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(13,'Gare du Nord','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(14,'Rilan & The Bombadiers','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(15,'Soul Six','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(16,'Han Bennink','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(17,'The Nordanians','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(18,'Lilith Merlot','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(19,'Ruis Soundsystem','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(20,'Wicked Jazz Sounds','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(21,'Evolve','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(22,'The Nordanians','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(23,'Gumbo Kings','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(24,'Gare du Nord','jazz',NULL,NULL,'2026-02-16 22:07:23',NULL),(25,'Gumbo Kings','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(26,'Evolve','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(27,'Ntjam Rosie','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(28,'Wicked Jazz Sounds','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(29,'Wouter Hamel','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(30,'Jonna Frazer','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(31,'Karsu','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(32,'Uncle Sue','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(33,'Chris Allen','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(34,'Myles Sanko','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(35,'Ilse Huizinga','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(36,'Eric Vloeimans and Hotspot!','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(37,'Gare du Nord','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(38,'Rilan & The Bombadiers','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(39,'Soul Six','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(40,'Han Bennink','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(41,'The Nordanians','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(42,'Lilith Merlot','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(43,'Ruis Soundsystem','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(44,'Wicked Jazz Sounds','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(45,'Evolve','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(46,'The Nordanians','jazz',NULL,NULL,'2026-02-16 22:17:54',NULL),(47,'Gumbo Kings','jazz',NULL,NULL,'2026-02-16 22:17:55',NULL),(48,'Gare du Nord','jazz',NULL,NULL,'2026-02-16 22:17:55',NULL),(49,'Gumbo Kings','jazz','High-energy New Orleans jazz and blues band from the Netherlands.',NULL,'2026-02-16 23:49:56',NULL),(50,'Evolve','jazz','Dutch neo-soul and jazz fusion collective blending modern grooves.',NULL,'2026-02-16 23:49:56',NULL),(51,'Ntjam Rosie','jazz','Cameroonian-Dutch vocalist known for soulful jazz and African-inspired melodies.',NULL,'2026-02-16 23:49:56',NULL),(52,'Wicked Jazz Sounds','jazz','Amsterdam-based DJ collective fusing jazz, hip-hop, and funk.',NULL,'2026-02-16 23:49:56',NULL),(53,'Wouter Hamel','jazz','Dutch crooner with a smooth vocal jazz and bossa nova style.',NULL,'2026-02-16 23:49:56',NULL),(54,'Jonna Frazer','jazz','Amsterdam R&B and neo-soul artist with Caribbean-influenced vocals.',NULL,'2026-02-16 23:49:56',NULL),(55,'Karsu','jazz','Dutch-Turkish pianist and singer blending jazz with Turkish folk traditions.',NULL,'2026-02-16 23:49:56',NULL),(56,'Uncle Sue','jazz','Dutch jazz-funk-soul band delivering high-energy live performances.',NULL,'2026-02-16 23:49:56',NULL),(57,'Chris Allen','jazz','American jazz and blues guitarist based in the Netherlands.',NULL,'2026-02-16 23:49:56',NULL),(58,'Myles Sanko','jazz','British soul and funk vocalist with a rich, warm sound.',NULL,'2026-02-16 23:49:56',NULL),(59,'Ilse Huizinga','jazz','Award-winning Dutch jazz vocalist known for interpreting the Great American Songbook.',NULL,'2026-02-16 23:49:56',NULL),(60,'Eric Vloeimans and Hotspot!','jazz','Dutch trumpet virtuoso blending modern jazz with world music influences.',NULL,'2026-02-16 23:49:56',NULL),(61,'Gare du Nord','jazz','Dutch duo combining lounge jazz, electronic beats, and cinematic soundscapes.',NULL,'2026-02-16 23:49:56',NULL),(62,'Rilan & The Bombadiers','jazz','High-octane swing and jump blues band with vintage flair.',NULL,'2026-02-16 23:49:56',NULL),(63,'Soul Six','jazz','Six-piece soul and funk ensemble from the Netherlands.',NULL,'2026-02-16 23:49:56',NULL),(64,'Han Bennink','jazz','Legendary Dutch percussionist and pioneer of European free jazz.',NULL,'2026-02-16 23:49:56',NULL),(65,'The Nordanians','jazz','Dutch jazz-funk group with tight grooves and improvisational energy.',NULL,'2026-02-16 23:49:56',NULL),(66,'Lilith Merlot','jazz','Dutch singer-songwriter mixing jazz cabaret with theatrical storytelling.',NULL,'2026-02-16 23:49:56',NULL),(67,'Ruis Soundsystem','jazz','Amsterdam-based DJ collective spinning jazz-infused electronic sets.',NULL,'2026-02-16 23:49:56',NULL),(68,'Wicked Jazz Sounds','jazz','Amsterdam-based DJ collective fusing jazz, hip-hop, and funk.',NULL,'2026-02-16 23:49:56',NULL),(69,'Evolve','jazz','Dutch neo-soul and jazz fusion collective blending modern grooves.',NULL,'2026-02-16 23:49:56',NULL),(70,'The Nordanians','jazz','Dutch jazz-funk group with tight grooves and improvisational energy.',NULL,'2026-02-16 23:49:56',NULL),(71,'Gumbo Kings','jazz','High-energy New Orleans jazz and blues band from the Netherlands.',NULL,'2026-02-16 23:49:56',NULL),(72,'Gare du Nord','jazz','Dutch duo combining lounge jazz, electronic beats, and cinematic soundscapes.',NULL,'2026-02-16 23:49:56',NULL),(73,'Gumbo Kings','jazz','High-energy New Orleans jazz and blues band from the Netherlands.',NULL,'2026-02-16 23:54:30',NULL),(74,'Evolve','jazz','Dutch neo-soul and jazz fusion collective blending modern grooves.',NULL,'2026-02-16 23:54:30',NULL),(75,'Ntjam Rosie','jazz','Cameroonian-Dutch vocalist known for soulful jazz and African-inspired melodies.',NULL,'2026-02-16 23:54:30',NULL),(76,'Wicked Jazz Sounds','jazz','Amsterdam-based DJ collective fusing jazz, hip-hop, and funk.',NULL,'2026-02-16 23:54:30',NULL),(77,'Wouter Hamel','jazz','Dutch crooner with a smooth vocal jazz and bossa nova style.',NULL,'2026-02-16 23:54:30',NULL),(78,'Jonna Frazer','jazz','Amsterdam R&B and neo-soul artist with Caribbean-influenced vocals.',NULL,'2026-02-16 23:54:30',NULL),(79,'Karsu','jazz','Dutch-Turkish pianist and singer blending jazz with Turkish folk traditions.',NULL,'2026-02-16 23:54:30',NULL),(80,'Uncle Sue','jazz','Dutch jazz-funk-soul band delivering high-energy live performances.',NULL,'2026-02-16 23:54:30',NULL),(81,'Chris Allen','jazz','American jazz and blues guitarist based in the Netherlands.',NULL,'2026-02-16 23:54:30',NULL),(82,'Myles Sanko','jazz','British soul and funk vocalist with a rich, warm sound.',NULL,'2026-02-16 23:54:30',NULL),(83,'Ilse Huizinga','jazz','Award-winning Dutch jazz vocalist known for interpreting the Great American Songbook.',NULL,'2026-02-16 23:54:30',NULL),(84,'Eric Vloeimans and Hotspot!','jazz','Dutch trumpet virtuoso blending modern jazz with world music influences.',NULL,'2026-02-16 23:54:30',NULL),(85,'Gare du Nord','jazz','Dutch duo combining lounge jazz, electronic beats, and cinematic soundscapes.',NULL,'2026-02-16 23:54:30',NULL),(86,'Rilan & The Bombadiers','jazz','High-octane swing and jump blues band with vintage flair.',NULL,'2026-02-16 23:54:30',NULL),(87,'Soul Six','jazz','Six-piece soul and funk ensemble from the Netherlands.',NULL,'2026-02-16 23:54:31',NULL),(88,'Han Bennink','jazz','Legendary Dutch percussionist and pioneer of European free jazz.',NULL,'2026-02-16 23:54:31',NULL),(89,'The Nordanians','jazz','Dutch jazz-funk group with tight grooves and improvisational energy.',NULL,'2026-02-16 23:54:31',NULL),(90,'Lilith Merlot','jazz','Dutch singer-songwriter mixing jazz cabaret with theatrical storytelling.',NULL,'2026-02-16 23:54:31',NULL),(91,'Ruis Soundsystem','jazz','Amsterdam-based DJ collective spinning jazz-infused electronic sets.',NULL,'2026-02-16 23:54:31',NULL),(92,'Wicked Jazz Sounds','jazz','Amsterdam-based DJ collective fusing jazz, hip-hop, and funk.',NULL,'2026-02-16 23:54:31',NULL),(93,'Evolve','jazz','Dutch neo-soul and jazz fusion collective blending modern grooves.',NULL,'2026-02-16 23:54:31',NULL),(94,'The Nordanians','jazz','Dutch jazz-funk group with tight grooves and improvisational energy.',NULL,'2026-02-16 23:54:31',NULL),(95,'Gumbo Kings','jazz','High-energy New Orleans jazz and blues band from the Netherlands.',NULL,'2026-02-16 23:54:31',NULL),(96,'Gare du Nord','jazz','Dutch duo combining lounge jazz, electronic beats, and cinematic soundscapes.',NULL,'2026-02-16 23:54:31',NULL),(97,'Yummy Festival 2026','yummy','A curated culinary experience featuring participating restaurants and exclusive festival-only menus.',NULL,'2026-02-28 02:12:50',NULL),(99,'skibidy','jazz','3frgfuiyhrkjkeioewuhkb','/img/jazz-festival.jpg','2026-03-24 09:48:58',NULL),(100,'test','jazz','test','/img/jazz-festival.jpg','2026-03-24 11:40:37',NULL);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history_events`
--

DROP TABLE IF EXISTS `history_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history_events` (
  `event_id` int NOT NULL,
  `guide_name` varchar(255) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  CONSTRAINT `history_events_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history_events`
--

LOCK TABLES `history_events` WRITE;
/*!40000 ALTER TABLE `history_events` DISABLE KEYS */;
INSERT INTO `history_events` VALUES (1,'Jan de Vries','English'),(2,'Sophie van Dijk','Dutch'),(3,'Emma Bakker','English'),(4,'Pieter Mulder','English'),(5,'Anna Visser','Dutch');
/*!40000 ALTER TABLE `history_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `client_name` varchar(255) NOT NULL,
  `client_address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `vat_amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jazz_events`
--

DROP TABLE IF EXISTS `jazz_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jazz_events` (
  `event_id` int NOT NULL,
  `artist` varchar(255) NOT NULL,
  `style` varchar(255) DEFAULT NULL,
  `description` text,
  `profile_image` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `seats` int DEFAULT NULL,
  `images` json DEFAULT NULL,
  `tracks` json DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  CONSTRAINT `jazz_events_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jazz_events`
--

LOCK TABLES `jazz_events` WRITE;
/*!40000 ALTER TABLE `jazz_events` DISABLE KEYS */;
INSERT INTO `jazz_events` VALUES (49,'Gumbo Kings','New Orleans Jazz, Blues','High-energy New Orleans jazz and blues band from the Netherlands.',NULL,NULL,'Patronaat - Main Hall','2026-07-23 18:00:00','2026-07-23 19:00:00',15.00,300,NULL,NULL),(50,'Evolve','Neo-Soul, Jazz Fusion','Dutch neo-soul and jazz fusion collective blending modern grooves.',NULL,NULL,'Patronaat - Main Hall','2026-07-23 19:30:00','2026-07-23 20:30:00',15.00,300,NULL,NULL),(51,'Ntjam Rosie','Vocal Jazz, Soul','Cameroonian-Dutch vocalist known for soulful jazz and African-inspired melodies.',NULL,NULL,'Patronaat - Main Hall','2026-07-23 21:00:00','2026-07-23 22:00:00',15.00,300,NULL,NULL),(52,'Wicked Jazz Sounds','Jazz, Hip-Hop, Funk','Amsterdam-based DJ collective fusing jazz, hip-hop, and funk.',NULL,NULL,'Patronaat - Second Hall','2026-07-23 18:00:00','2026-07-23 19:00:00',10.00,200,NULL,NULL),(53,'Wouter Hamel','Vocal Jazz, Bossa Nova','Dutch crooner with a smooth vocal jazz and bossa nova style.',NULL,NULL,'Patronaat - Second Hall','2026-07-23 19:30:00','2026-07-23 20:30:00',10.00,200,NULL,NULL),(54,'Jonna Frazer','R&B, Neo-Soul','Amsterdam R&B and neo-soul artist with Caribbean-influenced vocals.',NULL,NULL,'Patronaat - Second Hall','2026-07-23 21:00:00','2026-07-23 22:00:00',10.00,200,NULL,NULL),(55,'Karsu','Jazz, World Music','Dutch-Turkish pianist and singer blending jazz with Turkish folk traditions.',NULL,NULL,'Patronaat - Main Hall','2026-07-24 18:00:00','2026-07-24 19:00:00',15.00,300,NULL,NULL),(56,'Uncle Sue','Jazz, Funk, Soul','Dutch jazz-funk-soul band delivering high-energy live performances.',NULL,NULL,'Patronaat - Main Hall','2026-07-24 19:30:00','2026-07-24 20:30:00',15.00,300,NULL,NULL),(57,'Chris Allen','Jazz, Blues','American jazz and blues guitarist based in the Netherlands.','/uploads/img_69d81017b72073.12896097.jpg','/uploads/img_69d81477527898.81931993.jpg','Patronaat - Main Hall','2026-07-24 21:00:00','2026-07-24 22:00:00',15.00,300,NULL,'[{\"url\": \"/uploads/audio/track_69d833c24eeba4.30174885.mp3\", \"genre\": \"R1\", \"title\": \"Islamic Sermon\", \"duration\": \"0:39\", \"duration_seconds\": 39}]'),(58,'Myles Sanko','Soul, Funk','British soul and funk vocalist with a rich, warm sound.',NULL,NULL,'Patronaat - Second Hall','2026-07-24 18:00:00','2026-07-24 19:00:00',10.00,200,NULL,NULL),(59,'Ilse Huizinga','Vocal Jazz','Award-winning Dutch jazz vocalist known for interpreting the Great American Songbook.',NULL,NULL,'Patronaat - Second Hall','2026-07-24 19:30:00','2026-07-24 20:30:00',10.00,200,NULL,NULL),(60,'Eric Vloeimans and Hotspot!','Modern Jazz, World','Dutch trumpet virtuoso blending modern jazz with world music influences.',NULL,NULL,'Patronaat - Second Hall','2026-07-24 21:00:00','2026-07-24 22:00:00',10.00,200,NULL,NULL),(61,'Gare du Nord','Lounge Jazz, Downtempo','Dutch duo combining lounge jazz, electronic beats, and cinematic soundscapes.',NULL,NULL,'Patronaat - Main Hall','2026-07-25 18:00:00','2026-07-25 19:00:00',15.00,300,NULL,NULL),(62,'Rilan & The Bombadiers','Swing, Jump Blues','High-octane swing and jump blues band with vintage flair.',NULL,NULL,'Patronaat - Main Hall','2026-07-25 19:30:00','2026-07-25 20:30:00',15.00,300,NULL,NULL),(63,'Soul Six','Soul, Funk','Six-piece soul and funk ensemble from the Netherlands.',NULL,NULL,'Patronaat - Main Hall','2026-07-25 21:00:00','2026-07-25 22:00:00',15.00,300,NULL,NULL),(64,'Han Bennink','Free Jazz, Avant-Garde','Legendary Dutch percussionist and pioneer of European free jazz.',NULL,NULL,'Patronaat - Third Hall','2026-07-25 18:00:00','2026-07-25 19:00:00',10.00,150,NULL,NULL),(65,'The Nordanians','Jazz, Funk','Dutch jazz-funk group with tight grooves and improvisational energy.',NULL,NULL,'Patronaat - Third Hall','2026-07-25 19:30:00','2026-07-25 20:30:00',10.00,150,NULL,NULL),(66,'Lilith Merlot','Singer-Songwriter, Jazz Cabaret','Dutch singer-songwriter mixing jazz cabaret with theatrical storytelling.',NULL,NULL,'Patronaat - Third Hall','2026-07-25 21:00:00','2026-07-25 22:00:00',10.00,150,NULL,NULL),(67,'Ruis Soundsystem','DJ, Electronic Jazz','Amsterdam-based DJ collective spinning jazz-infused electronic sets.',NULL,NULL,'Grote Markt','2026-07-26 15:00:00','2026-07-26 16:00:00',0.00,0,NULL,NULL),(99,'skibidy','rir32oi','3frgfuiyhrkjkeioewuhkb',NULL,NULL,'egrijhf32','2026-03-15 15:30:00','0202-03-15 06:40:00',50.00,400,NULL,NULL),(100,'test','fusion','test',NULL,NULL,'grote markt','2026-03-25 15:30:00','2026-03-25 17:30:00',15.00,44,NULL,NULL);
/*!40000 ALTER TABLE `jazz_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jazz_page_contents`
--

DROP TABLE IF EXISTS `jazz_page_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jazz_page_contents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `page` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g. jazz, jazz_12',
  `section_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_value` longtext COLLATE utf8mb4_unicode_ci,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_page_section` (`page`,`section_key`),
  KEY `idx_page` (`page`)
) ENGINE=InnoDB AUTO_INCREMENT=219 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jazz_page_contents`
--

LOCK TABLES `jazz_page_contents` WRITE;
/*!40000 ALTER TABLE `jazz_page_contents` DISABLE KEYS */;
INSERT INTO `jazz_page_contents` VALUES (1,'jazz','hero_title','Haarlem Jazz\r\nLive in the heart of the city.',NULL,'2026-04-10 10:45:36'),(2,'jazz','intro_heading','Feel the rhythm of Haarlem',NULL,'2026-04-10 10:45:36'),(3,'jazz','intro_sub','Soul, swing & late-night sessions',NULL,'2026-04-10 10:45:36'),(4,'jazz','intro_text','<p>Welcome to Haarlem Jazz – where the city resonates with the soulful notes of jazz. Explore the artists, events, and the dynamic vibe of this enchanting Dutch festival right here on our Haarlem Jazz page. Get ready for a musical journey that defines the spirit of jazz in the heart of Haarlem!</p>',NULL,'2026-04-10 10:45:36'),(5,'jazz','artists_heading','Line-up',NULL,'2026-04-10 10:45:36'),(6,'jazz','artists_sub','Filter by day and discover who plays when.',NULL,'2026-04-10 10:45:36'),(7,'jazz','locations_heading','Festival locations',NULL,'2026-04-10 10:45:36'),(8,'jazz','locations_sub','Around Haarlem – stroll between venues.',NULL,'2026-04-10 10:45:36'),(9,'jazz','locations_text','<div class=\"location-list__item\"><div class=\"location-list__name\">De Patronaat</div><div class=\"location-list__addr\">Zijlsingel 2, 2013 DN<br>Haarlem</div></div><div class=\"location-list__item\"><div class=\"location-list__name\">Grote Markt</div><div class=\"location-list__addr\">Grote Markt<br>Haarlem</div></div><div class=\"location-list__item\"><div class=\"location-list__name\">Station Haarlem</div><div class=\"location-list__addr\">Stationsplein 1IL<br>2011 LR Haarlem</div></div>',NULL,'2026-04-10 10:45:36'),(10,'jazz','intro_image',NULL,'/uploads/img_69d83250d98ef8.00334914.jpg','2026-04-10 10:45:36'),(11,'jazz_67','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(12,'jazz_66','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(13,'jazz_65','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(14,'jazz_64','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(15,'jazz_63','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(16,'jazz_62','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(17,'jazz_61','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(18,'jazz_60','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(19,'jazz_59','tracks_heading','Listen to their sounds',NULL,'2026-03-24 00:06:39'),(20,'jazz_58','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(21,'jazz_57','tracks_heading','Listen to their sounds',NULL,'2026-04-09 23:18:30'),(22,'jazz_56','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(23,'jazz_55','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(24,'jazz_54','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(25,'jazz_53','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(26,'jazz_52','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(27,'jazz_51','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(28,'jazz_50','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(29,'jazz_49','tracks_heading','Listen to their sounds',NULL,'2026-03-23 23:55:08'),(30,'jazz_67','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(31,'jazz_66','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(32,'jazz_65','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(33,'jazz_64','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(34,'jazz_63','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(35,'jazz_62','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(36,'jazz_61','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(37,'jazz_60','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(38,'jazz_59','performances_heading','Upcoming performances',NULL,'2026-03-24 00:06:39'),(39,'jazz_58','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(40,'jazz_57','performances_heading','Upcoming performances',NULL,'2026-04-09 23:18:30'),(41,'jazz_56','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(42,'jazz_55','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(43,'jazz_54','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(44,'jazz_53','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(45,'jazz_52','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(46,'jazz_51','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(47,'jazz_50','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(48,'jazz_49','performances_heading','Upcoming performances',NULL,'2026-03-23 23:55:08'),(49,'jazz_67','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(50,'jazz_66','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(51,'jazz_65','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(52,'jazz_64','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(53,'jazz_63','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(54,'jazz_62','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(55,'jazz_61','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(56,'jazz_60','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(57,'jazz_59','price_note','Included in passes',NULL,'2026-03-24 00:06:39'),(58,'jazz_58','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(59,'jazz_57','price_note','Included in passes',NULL,'2026-04-09 23:18:30'),(60,'jazz_56','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(61,'jazz_55','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(62,'jazz_54','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(63,'jazz_53','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(64,'jazz_52','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(65,'jazz_51','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(66,'jazz_50','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(67,'jazz_49','price_note','Included in passes',NULL,'2026-03-23 23:55:08'),(83,'jazz','hero_background_image',NULL,'/uploads/img_69c1d4de319107.17155645.png','2026-04-10 10:45:36'),(85,'jazz','locations_image',NULL,NULL,'2026-04-09 23:12:19'),(134,'jazz_59','artist_name','',NULL,'2026-03-24 00:06:39'),(135,'jazz_59','bio_html','',NULL,'2026-03-24 00:06:39'),(139,'jazz_59','banner_image',NULL,NULL,'2026-03-24 00:06:40'),(140,'jazz_59','profile_image',NULL,NULL,'2026-03-24 00:06:40'),(141,'jazz_57','artist_name','',NULL,'2026-04-09 23:18:30'),(142,'jazz_57','bio_html','',NULL,'2026-04-09 23:18:30'),(146,'jazz_57','banner_image',NULL,'/uploads/img_69d81477527898.81931993.jpg','2026-04-09 23:18:30'),(147,'jazz_57','profile_image',NULL,'/uploads/img_69d81017b72073.12896097.jpg','2026-04-09 23:18:30'),(178,'jazz_57','homepage_image',NULL,'/uploads/img_69d81456b93110.12407528.jpg','2026-04-09 23:18:30');
/*!40000 ALTER TABLE `jazz_page_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `capacity` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'Patronaat - Main Hall','Zijlsingel 2, 2013 DN Haarlem',300),(2,'Patronaat - Second Hall','Zijlsingel 2, 2013 DN Haarlem',200),(3,'Patronaat - Third Hall','Zijlsingel 2, 2013 DN Haarlem',150),(4,'Grote Markt','Grote Markt, Haarlem',0),(5,'Patronaat - Main Hall','Zijlsingel 2, 2013 DN Haarlem',300),(6,'Patronaat - Second Hall','Zijlsingel 2, 2013 DN Haarlem',200),(7,'Patronaat - Third Hall','Zijlsingel 2, 2013 DN Haarlem',150),(8,'Grote Markt','Grote Markt, Haarlem',0),(9,'Patronaat - Main Hall','Zijlsingel 2, 2013 DN Haarlem',300),(10,'Patronaat - Second Hall','Zijlsingel 2, 2013 DN Haarlem',200),(11,'Patronaat - Third Hall','Zijlsingel 2, 2013 DN Haarlem',150),(12,'Grote Markt','Grote Markt, Haarlem',0),(13,'Patronaat - Main Hall','Zijlsingel 2, 2013 DN Haarlem',300),(14,'Patronaat - Second Hall','Zijlsingel 2, 2013 DN Haarlem',200),(15,'Patronaat - Third Hall','Zijlsingel 2, 2013 DN Haarlem',150),(16,'Grote Markt','Grote Markt, Haarlem',0);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `session_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `vat_rate` decimal(5,2) NOT NULL DEFAULT '21.00',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,42,1,60.00,21.00),(2,2,41,1,17.50,21.00),(3,2,50,1,60.00,21.00),(4,3,42,1,60.00,21.00),(5,4,47,1,17.50,21.00),(6,4,42,1,60.00,21.00),(7,5,43,1,17.50,21.00),(8,6,43,1,17.50,21.00),(9,7,44,1,60.00,21.00),(10,7,41,1,17.50,21.00),(11,8,42,1,60.00,21.00),(12,9,42,1,60.00,21.00),(13,10,42,1,60.00,21.00),(14,11,42,1,60.00,21.00),(15,12,43,1,17.50,21.00),(16,13,44,1,60.00,21.00),(17,14,42,1,60.00,21.00),(18,15,43,1,17.50,21.00),(19,16,44,1,60.00,21.00),(20,17,43,1,17.50,21.00),(21,18,41,1,17.50,21.00),(22,19,42,1,60.00,21.00),(23,20,41,1,17.50,21.00),(24,21,42,1,60.00,21.00),(25,22,42,1,60.00,21.00),(26,23,42,1,60.00,21.00),(27,24,46,1,60.00,21.00),(28,25,43,1,17.50,21.00),(29,26,42,1,60.00,21.00),(30,27,42,1,60.00,21.00),(31,28,43,1,17.50,21.00),(32,29,49,1,17.50,21.00),(35,32,51,1,60.00,21.00),(36,32,52,1,17.50,21.00),(37,33,53,2,60.00,21.00),(38,33,54,1,60.00,21.00),(39,34,55,1,60.00,21.00),(40,35,56,2,60.00,21.00),(41,35,57,1,17.50,21.00),(42,36,58,22,17.50,21.00),(43,37,59,1,17.50,21.00),(44,38,60,1,17.50,21.00),(45,39,61,1,17.50,21.00),(46,39,62,1,60.00,21.00),(47,39,63,1,17.50,21.00),(48,40,41,1,17.50,21.00),(49,40,68,3,15.00,21.00),(54,45,113,4,60.00,21.00),(55,45,114,9,17.50,21.00),(56,46,115,5,60.00,21.00),(57,47,118,2,60.00,21.00),(58,48,119,1,17.50,21.00),(59,49,120,1,17.50,21.00),(60,49,121,167,60.00,21.00),(61,50,68,2,15.00,21.00),(62,50,41,1,17.50,21.00),(63,50,94,1,10.00,21.00),(64,51,104,1,12.50,21.00),(65,52,44,1,60.00,21.00),(66,53,133,1,17.50,21.00),(67,53,134,1,10.00,21.00),(68,53,135,1,15.00,21.00),(69,53,136,1,15.00,21.00),(70,54,137,1,17.50,21.00),(71,55,139,1,10.00,21.00),(72,55,140,1,10.00,21.00),(73,55,141,1,60.00,21.00),(74,55,142,1,10.00,21.00),(75,56,145,1,17.50,21.00);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `order_number` varchar(50) NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','paid','cancelled') DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,3,'ORD-48508E86E428','2026-03-01 20:13:37','paid',60.00),(2,3,'ORD-4363A6383360','2026-03-01 20:26:42','paid',77.50),(3,3,'ORD-AAEC6487E633','2026-03-01 20:36:49','paid',60.00),(4,4,'ORD-299E2C24F215','2026-03-01 20:48:16','paid',77.50),(5,4,'ORD-6E1DADE334F5','2026-03-01 21:03:21','paid',17.50),(6,4,'ORD-76C924111FDD','2026-03-02 09:31:00','paid',17.50),(7,4,'ORD-51C59635DF20','2026-03-02 10:04:25','paid',77.50),(8,3,'ORD-7839F750C2B8','2026-03-02 10:20:35','paid',60.00),(9,4,'ORD-304671AB15A7','2026-03-02 11:29:08','paid',60.00),(10,5,'ORD-E0D0AE7B942D','2026-03-02 12:22:53','paid',60.00),(11,5,'ORD-290A9B3DA785','2026-03-02 13:06:41','paid',60.00),(12,1,'ORD-F083799FE1DF','2026-03-02 13:09:13','paid',17.50),(13,1,'ORD-A164BB911890','2026-03-02 13:17:15','paid',60.00),(14,5,'ORD-2C99D0D62861','2026-03-02 13:18:14','paid',60.00),(15,1,'ORD-FA6581C964A0','2026-03-02 13:25:10','paid',17.50),(16,5,'ORD-863E1434EDF7','2026-03-02 13:25:44','paid',60.00),(17,1,'ORD-2C693A9F42B9','2026-03-02 13:31:04','paid',17.50),(18,4,'ORD-15C0ADBB2B13','2026-03-10 08:07:39','paid',17.50),(19,4,'ORD-19FC182B4CFA','2026-03-10 10:19:18','paid',60.00),(20,4,'ORD-6469EE1D81CD','2026-03-10 10:29:39','paid',17.50),(21,4,'ORD-E6D015C483B0','2026-03-10 10:50:13','paid',60.00),(22,4,'ORD-5A3FC86A0135','2026-03-10 11:46:01','paid',60.00),(23,5,'ORD-E498BE659B49','2026-03-10 13:40:17','paid',60.00),(24,5,'ORD-76CBF7300403','2026-03-10 13:42:05','paid',60.00),(25,4,'ORD-11D28056B846','2026-03-10 14:04:28','paid',17.50),(26,1,'ORD-DEED085E0AC5','2026-03-10 14:08:54','paid',60.00),(27,4,'ORD-610C18B51C2D','2026-03-17 09:56:34','paid',60.00),(28,2,'ORD-557595A8ECDA','2026-03-17 11:43:40','paid',17.50),(29,1,'ORD-CD98AD7FD907','2026-03-17 11:44:28','paid',17.50),(32,4,'ORD-8423642C9868','2026-03-17 12:31:24','paid',77.50),(33,4,'ORD-7A7446C353F1','2026-03-17 12:37:07','paid',180.00),(34,4,'ORD-AE8D707B6110','2026-03-17 12:50:13','paid',60.00),(35,4,'ORD-C9684E20C186','2026-03-17 12:59:31','paid',137.50),(36,4,'ORD-FF320D362AC1','2026-03-17 13:01:14','paid',385.00),(37,4,'ORD-BE42E4EDD8B9','2026-03-17 13:02:29','paid',17.50),(38,4,'ORD-BE06D09E69AF','2026-03-17 13:42:55','paid',17.50),(39,4,'ORD-079A3DFE4132','2026-03-17 13:49:11','paid',95.00),(40,1,'ORD-093FF721485A','2026-03-24 02:28:28','paid',62.50),(45,4,'ORD-EE03F407CB2B','2026-03-24 10:55:33','paid',397.50),(46,4,'ORD-A1A44260C08B','2026-03-24 10:56:32','paid',300.00),(47,4,'ORD-200D536D7B56','2026-03-24 11:45:09','paid',120.00),(48,4,'ORD-0D96E48898CB','2026-03-24 12:17:27','paid',17.50),(49,4,'ORD-96F5CEF098C4','2026-03-24 12:26:29','paid',10037.50),(50,1,'ORD-0A5CD9C2CF61','2026-03-28 08:36:12','paid',57.50),(51,1,'ORD-EE6B65AC97F9','2026-03-28 08:37:11','paid',12.50),(52,1,'ORD-9E01FE1744B9','2026-03-28 11:06:09','paid',60.00),(53,4,'ORD-3BF62950B976','2026-04-10 10:20:48','paid',57.50),(54,4,'ORD-DC48853549FB','2026-04-10 10:32:24','paid',17.50),(55,4,'ORD-9A61EA62428A','2026-04-10 10:42:02','paid',90.00),(56,11,'ORD-28A1374100D7','2026-04-10 11:12:48','paid',17.50);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `passes`
--

DROP TABLE IF EXISTS `passes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `passes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_id` int NOT NULL,
  `pass_type` enum('all_access','day_pass') NOT NULL,
  `valid_date` date DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `passes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `passes_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passes`
--

LOCK TABLES `passes` WRITE;
/*!40000 ALTER TABLE `passes` DISABLE KEYS */;
/*!40000 ALTER TABLE `passes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(50) DEFAULT NULL,
  `status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_cuisine_tags`
--

DROP TABLE IF EXISTS `restaurant_cuisine_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurant_cuisine_tags` (
  `restaurant_id` int NOT NULL,
  `tag_id` int NOT NULL,
  PRIMARY KEY (`restaurant_id`,`tag_id`),
  KEY `fk_rct_tag` (`tag_id`),
  CONSTRAINT `fk_rct_tag` FOREIGN KEY (`tag_id`) REFERENCES `cuisine_tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_cuisine_tags`
--

LOCK TABLES `restaurant_cuisine_tags` WRITE;
/*!40000 ALTER TABLE `restaurant_cuisine_tags` DISABLE KEYS */;
INSERT INTO `restaurant_cuisine_tags` VALUES (2,1),(4,1),(1,2),(2,2),(3,2),(7,2),(1,3),(3,3),(4,3),(6,3),(7,3),(5,4),(1,6),(2,6),(3,6),(4,6),(6,6),(7,6);
/*!40000 ALTER TABLE `restaurant_cuisine_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_menu_items`
--

DROP TABLE IF EXISTS `restaurant_menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurant_menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `restaurant_id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `restaurant_id` (`restaurant_id`),
  CONSTRAINT `restaurant_menu_items_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_menu_items`
--

LOCK TABLES `restaurant_menu_items` WRITE;
/*!40000 ALTER TABLE `restaurant_menu_items` DISABLE KEYS */;
INSERT INTO `restaurant_menu_items` VALUES (1,2,'Coq au Vin','Braised chicken in red wine sauce with pearl onions, mushrooms, and bacon lardons.','/assets/yummy/image/rata-menu-coq-au-vin.jpg.jpeg',1),(2,2,'Miral Duck','Tender slices of pan-seared duck breast, served with a rich red wine sauce, seasonal vegetables, and fresh herb garnish.','/assets/yummy/image/rata-menu-miral-duck.jpg.jpeg',2),(3,2,'Kalfszwezerik','Thick-cut cauliflower steak with a golden herb crust, served with roasted root vegetables, quinoa pilaf.','/assets/yummy/image/rata-menu-kalfszwezerik.jpg.png',3),(4,2,'Dessert','Passion fruit, Mango, Yoghurt, Valrhona chocolate.','/assets/yummy/image/rata-menu-dessert.jpg.jpg',4);
/*!40000 ALTER TABLE `restaurant_menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurants`
--

DROP TABLE IF EXISTS `restaurants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `short_description` text,
  `about` text,
  `adult_price_cents` int DEFAULT NULL,
  `child_price_cents` int DEFAULT NULL,
  `child_max_age` int DEFAULT NULL,
  `seats` int DEFAULT NULL,
  `session_count` int DEFAULT NULL,
  `session_duration_minutes` int DEFAULT NULL,
  `session_one_start_time` time DEFAULT NULL,
  `session_two_start_time` time DEFAULT NULL,
  `session_three_start_time` time DEFAULT NULL,
  `rating` tinyint DEFAULT NULL,
  `review_count` int DEFAULT NULL,
  `restaurant_image_path` varchar(255) DEFAULT NULL,
  `chef_image_path` varchar(255) DEFAULT NULL,
  `chef_bio` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `about_image_path` varchar(255) DEFAULT NULL,
  `reservation_image_path` varchar(255) DEFAULT NULL,
  `chef_name` varchar(255) DEFAULT NULL,
  `chef_title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurants`
--

LOCK TABLES `restaurants` WRITE;
/*!40000 ALTER TABLE `restaurants` DISABLE KEYS */;
INSERT INTO `restaurants` VALUES (1,'Café de Roemer','cafe-de-roemer','Botermarkt 17, 2011 XL Haarlem',NULL,NULL,3500,1750,12,35,3,90,'18:00:00','19:30:00','21:00:00',4,NULL,NULL,NULL,NULL,1,'2026-04-09 20:19:56',NULL,NULL,NULL,NULL),(2,'Ratatouille','ratatouille','Spaarne 96, 2011 CL Haarlem',NULL,'Welcome to Ratatouille Food and Wine, where gastronomy becomes an art and hospitality is at the core of our experience. Located in the heart of Haarlem, our restaurant led by the passionate chef Jozua Jaring is a haven for lovers of exotic flavors and stylish culinary adventures. Step into the world of authentic French gastronomy at Ratatouille, where culinary tradition meets contemporary innovation. Our restaurant embodies the essence of classic French dining, offering an intimate and refined atmosphere that transports you to the heart of Paris.',4500,2250,12,52,3,120,'17:00:00','19:00:00','21:00:00',4,NULL,'/assets/yummy/image/Rata_homepage.png','/assets/yummy/image/rata-chef-imag.jpg','With over 10 years of experience in Michelin-starred kitchens across France, Chef Jozua brings authentic French culinary artistry to Haarlem. His passion for perfection and dedication to quality shine through in every dish he creates. Trained under renowned masters in Lyon and Paris, his philosophy centers on respecting ingredients and honoring traditional techniques while allowing creativity to flourish. His work has earned recognition from international culinary publications and food critics alike.',1,'2026-04-09 20:19:56','/assets/yummy/image/rata-about.jpeg','/assets/yummy/image/rata-reservation-card.jpeg','Chef Jozua Jaring','Executive Chef & Owner'),(3,'Restaurant ML','restaurant-ml','Kleine Houtstraat 70, 2011 DR Haarlem',NULL,NULL,4500,2250,12,60,2,120,'17:00:00','19:00:00',NULL,4,NULL,NULL,NULL,NULL,1,'2026-04-09 20:19:56',NULL,NULL,NULL,NULL),(4,'Restaurant Fris','restaurant-fris','Twijnderslaan 7, 2012 BG Haarlem',NULL,NULL,4500,2250,12,45,3,90,'17:30:00','19:00:00','20:30:00',4,NULL,NULL,NULL,NULL,1,'2026-04-09 20:19:56',NULL,NULL,NULL,NULL),(5,'New Vegas','new-vegas','Koningstraat 5, 2011 TB Haarlem',NULL,NULL,3500,1750,12,36,3,90,'17:00:00','18:30:00','20:00:00',3,NULL,NULL,NULL,NULL,1,'2026-04-09 20:19:56',NULL,NULL,NULL,NULL),(6,'Grand Cafe Brinkman','grand-cafe-brinkman','Grote Markt 13, 2011 RC Haarlem',NULL,NULL,3500,1750,12,100,3,90,'16:30:00','18:00:00','19:30:00',3,NULL,NULL,NULL,NULL,1,'2026-04-09 20:19:56',NULL,NULL,NULL,NULL),(7,'Urban Frenchy Bistro Toujours','urban-frenchy-bistro-toujours','Oude Groenmarkt 10-12, 2011 HL Haarlem',NULL,NULL,3500,1750,12,48,3,90,'17:30:00','19:00:00','20:30:00',3,NULL,NULL,NULL,NULL,1,'2026-04-09 20:19:56',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `restaurants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_id` int NOT NULL,
  `location_id` int NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `capacity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES (1,1,1,'2026-07-23 18:00:00','2026-07-23 19:00:00',300,15.00),(2,2,1,'2026-07-23 19:30:00','2026-07-23 20:30:00',300,15.00),(3,3,1,'2026-07-23 21:00:00','2026-07-23 22:00:00',300,15.00),(4,4,2,'2026-07-23 18:00:00','2026-07-23 19:00:00',200,10.00),(5,5,2,'2026-07-23 19:30:00','2026-07-23 20:30:00',200,10.00),(6,6,2,'2026-07-23 21:00:00','2026-07-23 22:00:00',200,10.00),(7,7,1,'2026-07-24 18:00:00','2026-07-24 19:00:00',300,15.00),(8,8,1,'2026-07-24 19:30:00','2026-07-24 20:30:00',300,15.00),(9,9,1,'2026-07-24 21:00:00','2026-07-24 22:00:00',300,15.00),(10,10,2,'2026-07-24 18:00:00','2026-07-24 19:00:00',200,10.00),(11,11,2,'2026-07-24 19:30:00','2026-07-24 20:30:00',200,10.00),(12,12,2,'2026-07-24 21:00:00','2026-07-24 22:00:00',200,10.00),(13,13,1,'2026-07-25 18:00:00','2026-07-25 19:00:00',300,15.00),(14,14,1,'2026-07-25 19:30:00','2026-07-25 20:30:00',300,15.00),(15,15,1,'2026-07-25 21:00:00','2026-07-25 22:00:00',300,15.00),(16,16,3,'2026-07-25 18:00:00','2026-07-25 19:00:00',150,10.00),(17,17,3,'2026-07-25 19:30:00','2026-07-25 20:30:00',150,10.00),(18,18,3,'2026-07-25 21:00:00','2026-07-25 22:00:00',150,10.00),(19,19,4,'2026-07-26 15:00:00','2026-07-26 16:00:00',0,0.00),(20,20,4,'2026-07-26 16:00:00','2026-07-26 17:00:00',0,0.00),(21,21,4,'2026-07-26 17:00:00','2026-07-26 18:00:00',0,0.00),(22,22,4,'2026-07-26 18:00:00','2026-07-26 19:00:00',0,0.00),(23,23,4,'2026-07-26 19:00:00','2026-07-26 20:00:00',0,0.00),(24,24,4,'2026-07-26 20:00:00','2026-07-26 21:00:00',0,0.00),(25,25,5,'2026-07-23 18:00:00','2026-07-23 19:00:00',300,15.00),(26,26,5,'2026-07-23 19:30:00','2026-07-23 20:30:00',300,15.00),(27,27,5,'2026-07-23 21:00:00','2026-07-23 22:00:00',300,15.00),(28,28,6,'2026-07-23 18:00:00','2026-07-23 19:00:00',200,10.00),(29,29,6,'2026-07-23 19:30:00','2026-07-23 20:30:00',200,10.00),(30,30,6,'2026-07-23 21:00:00','2026-07-23 22:00:00',200,10.00),(31,31,5,'2026-07-24 18:00:00','2026-07-24 19:00:00',300,15.00),(32,32,5,'2026-07-24 19:30:00','2026-07-24 20:30:00',300,15.00),(33,33,5,'2026-07-24 21:00:00','2026-07-24 22:00:00',300,15.00),(34,34,6,'2026-07-24 18:00:00','2026-07-24 19:00:00',200,10.00),(35,35,6,'2026-07-24 19:30:00','2026-07-24 20:30:00',200,10.00),(36,36,6,'2026-07-24 21:00:00','2026-07-24 22:00:00',200,10.00),(37,37,5,'2026-07-25 18:00:00','2026-07-25 19:00:00',300,15.00),(38,38,5,'2026-07-25 19:30:00','2026-07-25 20:30:00',300,15.00),(39,39,5,'2026-07-25 21:00:00','2026-07-25 22:00:00',300,15.00),(40,40,7,'2026-07-25 18:00:00','2026-07-25 19:00:00',150,10.00),(41,41,7,'2026-07-25 19:30:00','2026-07-25 20:30:00',150,10.00),(42,42,7,'2026-07-25 21:00:00','2026-07-25 22:00:00',150,10.00),(43,43,8,'2026-07-26 15:00:00','2026-07-26 16:00:00',0,0.00),(44,44,8,'2026-07-26 16:00:00','2026-07-26 17:00:00',0,0.00),(45,45,8,'2026-07-26 17:00:00','2026-07-26 18:00:00',0,0.00),(46,46,8,'2026-07-26 18:00:00','2026-07-26 19:00:00',0,0.00),(47,47,8,'2026-07-26 19:00:00','2026-07-26 20:00:00',0,0.00),(48,48,8,'2026-07-26 20:00:00','2026-07-26 21:00:00',0,0.00),(49,49,9,'2026-07-23 18:00:00','2026-07-23 19:00:00',300,15.00),(50,50,9,'2026-07-23 19:30:00','2026-07-23 20:30:00',300,15.00),(51,51,9,'2026-07-23 21:00:00','2026-07-23 22:00:00',300,15.00),(52,52,10,'2026-07-23 18:00:00','2026-07-23 19:00:00',200,10.00),(53,53,10,'2026-07-23 19:30:00','2026-07-23 20:30:00',200,10.00),(54,54,10,'2026-07-23 21:00:00','2026-07-23 22:00:00',200,10.00),(55,55,9,'2026-07-24 18:00:00','2026-07-24 19:00:00',300,15.00),(56,56,9,'2026-07-24 19:30:00','2026-07-24 20:30:00',300,15.00),(57,57,9,'2026-07-24 21:00:00','2026-07-24 22:00:00',300,15.00),(58,58,10,'2026-07-24 18:00:00','2026-07-24 19:00:00',200,10.00),(59,59,10,'2026-07-24 19:30:00','2026-07-24 20:30:00',200,10.00),(60,60,10,'2026-07-24 21:00:00','2026-07-24 22:00:00',200,10.00),(61,61,9,'2026-07-25 18:00:00','2026-07-25 19:00:00',300,15.00),(62,62,9,'2026-07-25 19:30:00','2026-07-25 20:30:00',300,15.00),(63,63,9,'2026-07-25 21:00:00','2026-07-25 22:00:00',300,15.00),(64,64,11,'2026-07-25 18:00:00','2026-07-25 19:00:00',150,10.00),(65,65,11,'2026-07-25 19:30:00','2026-07-25 20:30:00',150,10.00),(66,66,11,'2026-07-25 21:00:00','2026-07-25 22:00:00',150,10.00),(67,67,12,'2026-07-26 15:00:00','2026-07-26 16:00:00',0,0.00),(68,68,12,'2026-07-26 16:00:00','2026-07-26 17:00:00',0,0.00),(69,69,12,'2026-07-26 17:00:00','2026-07-26 18:00:00',0,0.00),(70,70,12,'2026-07-26 18:00:00','2026-07-26 19:00:00',0,0.00),(71,71,12,'2026-07-26 19:00:00','2026-07-26 20:00:00',0,0.00),(72,72,12,'2026-07-26 20:00:00','2026-07-26 21:00:00',0,0.00),(73,73,13,'2026-07-23 18:00:00','2026-07-23 19:00:00',300,15.00),(74,74,13,'2026-07-23 19:30:00','2026-07-23 20:30:00',300,15.00),(75,75,13,'2026-07-23 21:00:00','2026-07-23 22:00:00',300,15.00),(76,76,14,'2026-07-23 18:00:00','2026-07-23 19:00:00',200,10.00),(77,77,14,'2026-07-23 19:30:00','2026-07-23 20:30:00',200,10.00),(78,78,14,'2026-07-23 21:00:00','2026-07-23 22:00:00',200,10.00),(79,79,13,'2026-07-24 18:00:00','2026-07-24 19:00:00',300,15.00),(80,80,13,'2026-07-24 19:30:00','2026-07-24 20:30:00',300,15.00),(81,81,13,'2026-07-24 21:00:00','2026-07-24 22:00:00',300,15.00),(82,82,14,'2026-07-24 18:00:00','2026-07-24 19:00:00',200,10.00),(83,83,14,'2026-07-24 19:30:00','2026-07-24 20:30:00',200,10.00),(84,84,14,'2026-07-24 21:00:00','2026-07-24 22:00:00',200,10.00),(85,85,13,'2026-07-25 18:00:00','2026-07-25 19:00:00',300,15.00),(86,86,13,'2026-07-25 19:30:00','2026-07-25 20:30:00',300,15.00),(87,87,13,'2026-07-25 21:00:00','2026-07-25 22:00:00',300,15.00),(88,88,15,'2026-07-25 18:00:00','2026-07-25 19:00:00',150,10.00),(89,89,15,'2026-07-25 19:30:00','2026-07-25 20:30:00',150,10.00),(90,90,15,'2026-07-25 21:00:00','2026-07-25 22:00:00',150,10.00),(91,91,16,'2026-07-26 15:00:00','2026-07-26 16:00:00',0,0.00),(92,92,16,'2026-07-26 16:00:00','2026-07-26 17:00:00',0,0.00),(93,93,16,'2026-07-26 17:00:00','2026-07-26 18:00:00',0,0.00),(94,94,16,'2026-07-26 18:00:00','2026-07-26 19:00:00',0,0.00),(95,95,16,'2026-07-26 19:00:00','2026-07-26 20:00:00',0,0.00),(96,96,16,'2026-07-26 20:00:00','2026-07-26 21:00:00',0,0.00);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_carts`
--

DROP TABLE IF EXISTS `shopping_carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shopping_carts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `session_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `shopping_carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shopping_carts_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_carts`
--

LOCK TABLES `shopping_carts` WRITE;
/*!40000 ALTER TABLE `shopping_carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_event`
--

DROP TABLE IF EXISTS `story_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `story_event` (
  `story_event_id` int NOT NULL AUTO_INCREMENT,
  `event_date` varchar(50) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time_slot` varchar(20) NOT NULL,
  `location` varchar(100) NOT NULL,
  `age_group` varchar(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `language` varchar(10) DEFAULT NULL,
  `price` varchar(50) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`story_event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_event`
--

LOCK TABLES `story_event` WRITE;
/*!40000 ALTER TABLE `story_event` DISABLE KEYS */;
INSERT INTO `story_event` VALUES (1,'<last weekend of July>','Thursday','16:00-17:00','Verhalenhuis Haarlem','4+','Winnie de Poeh','NL','6','stories for the whole family'),(2,'<last weekend of July>','Thursday','19:00-20:15','De Schuur','16+','Omdenken Podcast','NL','12.5','recording podcast with audience'),(3,'<last weekend of July>','Thursday','20:30-21:45','Kweekcafé','16+','The story of Buurderij Haarlem','ENG','pay as you like','stories with impact'),(4,'<last weekend of July>','Friday','16:00-17:00','Corrie ten Boom huis','10+','Corrie voor kinderen','NL','pay as you like','stories for the whole family'),(5,'<last weekend of July>','Friday','19:00-20:30','Verhalenhuis Haarlem','12+','Winnaars van verhalenvertel wedstrijd','NL','12.5','best of'),(6,'<last weekend of July>','Friday','19:00-20:15','Kweekcafé','16+','Het verhaal van de Oeserzwammerij','NL','pay as you like','stories with impact'),(7,'<last weekend of July>','Friday','20:30-21:45','De Schuur','16+','Flip Thinking Podcast','ENG','12.5','recording podcast with audience'),(8,'<last weekend of July>','Saturday','10:00-11:00','Theater Elswout','2-102','Meneer Anansi','NL','10','stories for the whole family'),(9,'<last weekend of July>','Saturday','15:00-16:00','Theater Elswout','2-102','Mister Anansi','ENG','10','stories for the whole family'),(10,'<last weekend of July>','Saturday','14:00-15:15','De Schuur','12+','Podcastlab Haarlem Special','NL','12.5','recording podcast with audience'),(11,'<last weekend of July>','Saturday','13:00-14:30','Corrie ten Boom huis','12+','De geschiedenis van familie ten Boom','NL','pay as you like','stories with impact'),(12,'<last weekend of July>','Sunday','10:00-11:00','Theater Elswout','2-102','Mister Anansi','ENG','10','stories for the whole family'),(13,'<last weekend of July>','Sunday','15:00-16:00','Theater Elswout','2-102','Meneer Anansi','NL','10','stories for the whole family'),(14,'<last weekend of July>','Sunday','13:00-14:30','Corrie ten Boom huis','12+','The history of the Ten Boom Family','ENG','pay as you like','stories with impact'),(15,'<last weekend of July>','Sunday','16:00-17:30','Verhalenhuis Haarlem','12+','Winners of storytelling competition','ENG','12.5','best of'),(17,'2026-03-27','THURSDAY','16:00-17:00','Leiden','4+','three','EN','100','horror');
/*!40000 ALTER TABLE `story_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_events`
--

DROP TABLE IF EXISTS `story_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `story_events` (
  `event_id` int NOT NULL,
  `guide_name` varchar(255) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  CONSTRAINT `story_events_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_events`
--

LOCK TABLES `story_events` WRITE;
/*!40000 ALTER TABLE `story_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `story_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `event_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `event_date` varchar(50) DEFAULT NULL,
  `event_time` varchar(50) DEFAULT NULL,
  `event_language` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ticket_code` varchar(100) NOT NULL,
  `qr_code_path` varchar(255) DEFAULT NULL,
  `is_scanned` tinyint(1) DEFAULT '0',
  `scanned_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket_code` (`ticket_code`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_tickets_event_id` (`event_id`),
  CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (41,NULL,1,NULL,'Stroll Through History - Single',NULL,NULL,NULL,17.50,'HIST-001',NULL,1,'2026-03-24 11:16:42'),(42,NULL,1,NULL,'Stroll Through History - Family',NULL,NULL,NULL,60.00,'HIST-002',NULL,0,NULL),(43,NULL,2,NULL,'Stroll Through History - Single',NULL,NULL,NULL,17.50,'HIST-003',NULL,0,NULL),(44,NULL,2,NULL,'Stroll Through History - Family',NULL,NULL,NULL,60.00,'HIST-004',NULL,0,NULL),(45,NULL,3,NULL,'Stroll Through History - Single',NULL,NULL,NULL,17.50,'HIST-005',NULL,0,NULL),(46,NULL,3,NULL,'Stroll Through History - Family',NULL,NULL,NULL,60.00,'HIST-006',NULL,1,'2026-03-24 11:00:22'),(47,NULL,4,NULL,'Stroll Through History - Single',NULL,NULL,NULL,17.50,'HIST-007',NULL,0,NULL),(48,NULL,4,NULL,'Stroll Through History - Family',NULL,NULL,NULL,60.00,'HIST-008',NULL,0,NULL),(49,NULL,5,NULL,'Stroll Through History - Single',NULL,NULL,NULL,17.50,'HIST-009',NULL,1,'2026-03-28 08:38:23'),(50,NULL,5,NULL,'Stroll Through History - Family',NULL,NULL,NULL,60.00,'HIST-010',NULL,0,NULL),(51,32,0,4,'Family Ticket',NULL,NULL,NULL,60.00,'GEN-a48f67cd6156',NULL,0,NULL),(52,32,0,4,'Admission Ticket',NULL,NULL,NULL,17.50,'GEN-b27c5cd2a06f',NULL,0,NULL),(53,33,0,4,'Family Ticket',NULL,NULL,NULL,60.00,'GEN-1f9f60cc23e2',NULL,0,NULL),(54,33,0,4,'Family Ticket',NULL,NULL,NULL,60.00,'GEN-18c7dce65436',NULL,0,NULL),(55,34,0,4,'Family Ticket',NULL,NULL,NULL,60.00,'GEN-154176c7ce73',NULL,0,NULL),(56,35,0,4,'Family Ticket',NULL,NULL,NULL,60.00,'GEN-ce146e04cd36',NULL,0,NULL),(57,35,0,4,'Admission Ticket',NULL,NULL,NULL,17.50,'GEN-76a4b6932d93',NULL,0,NULL),(58,36,0,4,'Admission Ticket',NULL,NULL,NULL,17.50,'GEN-6fd61fe18f2d',NULL,0,NULL),(59,37,0,4,'Admission Ticket',NULL,NULL,NULL,17.50,'GEN-942745df9394',NULL,0,NULL),(60,38,0,4,'Admission Ticket',NULL,NULL,NULL,17.50,'GEN-e1a976c33fd3',NULL,0,NULL),(61,39,0,4,'Admission Ticket',NULL,NULL,NULL,17.50,'GEN-a6024b26df8a',NULL,0,NULL),(62,39,0,4,'Family Ticket',NULL,NULL,NULL,60.00,'GEN-d94ba925819d',NULL,0,NULL),(63,39,0,4,'Admission Ticket',NULL,NULL,NULL,17.50,'GEN-e246ea00445c',NULL,0,NULL),(64,NULL,57,NULL,'Admission — Chris Allen',NULL,NULL,NULL,15.00,'JZ-57-11F0072B5E',NULL,0,NULL),(65,NULL,60,NULL,'Admission — Eric Vloeimans and Hotspot!',NULL,NULL,NULL,10.00,'JZ-60-C807E00362',NULL,0,NULL),(66,NULL,50,NULL,'Admission — Evolve',NULL,NULL,NULL,15.00,'JZ-50-38BA710AF9',NULL,0,NULL),(67,NULL,61,NULL,'Admission — Gare du Nord',NULL,NULL,NULL,15.00,'JZ-61-F0503FCA1E',NULL,0,NULL),(68,NULL,49,NULL,'Admission — Gumbo Kings',NULL,NULL,NULL,15.00,'JZ-49-AA467B2C32',NULL,0,NULL),(69,NULL,64,NULL,'Admission — Han Bennink',NULL,NULL,NULL,10.00,'JZ-64-6B6CCAD0E7',NULL,0,NULL),(70,NULL,59,NULL,'Admission — Ilse Huizinga',NULL,NULL,NULL,10.00,'JZ-59-1D9C6F5B09',NULL,0,NULL),(71,NULL,54,NULL,'Admission — Jonna Frazer',NULL,NULL,NULL,10.00,'JZ-54-1D6768D9CD',NULL,0,NULL),(72,NULL,55,NULL,'Admission — Karsu',NULL,NULL,NULL,15.00,'JZ-55-68B5B8796B',NULL,0,NULL),(73,NULL,66,NULL,'Admission — Lilith Merlot',NULL,NULL,NULL,10.00,'JZ-66-48323DD94D',NULL,0,NULL),(74,NULL,58,NULL,'Admission — Myles Sanko',NULL,NULL,NULL,10.00,'JZ-58-5EB0CF8F85',NULL,0,NULL),(75,NULL,51,NULL,'Admission — Ntjam Rosie',NULL,NULL,NULL,15.00,'JZ-51-392254CF7E',NULL,0,NULL),(76,NULL,62,NULL,'Admission — Rilan & The Bombadiers',NULL,NULL,NULL,15.00,'JZ-62-F903C85F2E',NULL,0,NULL),(77,NULL,67,NULL,'Admission — Ruis Soundsystem',NULL,NULL,NULL,0.00,'JZ-67-4A008781CB',NULL,0,NULL),(78,NULL,63,NULL,'Admission — Soul Six',NULL,NULL,NULL,15.00,'JZ-63-B98C97DC6D',NULL,0,NULL),(79,NULL,65,NULL,'Admission — The Nordanians',NULL,NULL,NULL,10.00,'JZ-65-0A92131D6C',NULL,0,NULL),(80,NULL,56,NULL,'Admission — Uncle Sue',NULL,NULL,NULL,15.00,'JZ-56-AF810B1E30',NULL,0,NULL),(81,NULL,52,NULL,'Admission — Wicked Jazz Sounds',NULL,NULL,NULL,10.00,'JZ-52-129622926E',NULL,0,NULL),(82,NULL,53,NULL,'Admission — Wouter Hamel',NULL,NULL,NULL,10.00,'JZ-53-790F58C1AB',NULL,0,NULL),(83,NULL,99,NULL,'Admission — skibidy',NULL,NULL,NULL,50.00,'JZ-99-59415A9670',NULL,0,NULL),(84,NULL,8,NULL,'Admission — Meneer Anansi',NULL,NULL,NULL,10.00,'JZ-8-3EDC04F63E',NULL,0,NULL),(85,NULL,12,NULL,'Admission — Mister Anansi',NULL,NULL,NULL,10.00,'JZ-12-FD3CE68B8A',NULL,0,NULL),(86,NULL,11,NULL,'Admission — De geschiedenis van familie ten Boom',NULL,NULL,NULL,0.00,'JZ-11-735185E0AB',NULL,0,NULL),(87,NULL,14,NULL,'Admission — The history of the Ten Boom Family',NULL,NULL,NULL,0.00,'JZ-14-0F9E19C867',NULL,0,NULL),(88,NULL,10,NULL,'Admission — Podcastlab Haarlem Special',NULL,NULL,NULL,12.50,'JZ-10-56008C62B2',NULL,0,NULL),(89,NULL,9,NULL,'Admission — Mister Anansi',NULL,NULL,NULL,10.00,'JZ-9-0976DAF557',NULL,0,NULL),(90,NULL,13,NULL,'Admission — Meneer Anansi',NULL,NULL,NULL,10.00,'JZ-13-FCD720E9C6',NULL,0,NULL),(91,NULL,15,NULL,'Admission — Winners of storytelling competition',NULL,NULL,NULL,12.50,'JZ-15-8A65CB47DC',NULL,0,NULL),(92,NULL,6,NULL,'Admission — Het verhaal van de Oeserzwammerij',NULL,NULL,NULL,0.00,'JZ-6-E27CED83D1',NULL,0,NULL),(93,NULL,7,NULL,'Admission — Flip Thinking Podcast',NULL,NULL,NULL,12.50,'JZ-7-F9B1CB8312',NULL,0,NULL),(94,NULL,8,NULL,'Storytelling — Meneer Anansi',NULL,NULL,NULL,10.00,'JZ-8-A0EC7DF67F',NULL,0,NULL),(95,NULL,12,NULL,'Storytelling — Mister Anansi',NULL,NULL,NULL,10.00,'JZ-12-BA7F974CB5',NULL,0,NULL),(96,NULL,11,NULL,'Storytelling — De geschiedenis van familie ten Boom',NULL,NULL,NULL,0.00,'JZ-11-E4D3887ABF',NULL,0,NULL),(97,NULL,14,NULL,'Storytelling — The history of the Ten Boom Family',NULL,NULL,NULL,0.00,'JZ-14-918AC22DA1',NULL,0,NULL),(98,NULL,10,NULL,'Storytelling — Podcastlab Haarlem Special',NULL,NULL,NULL,12.50,'JZ-10-86296C8765',NULL,0,NULL),(99,NULL,9,NULL,'Storytelling — Mister Anansi',NULL,NULL,NULL,10.00,'JZ-9-57E110E38F',NULL,0,NULL),(100,NULL,13,NULL,'Storytelling — Meneer Anansi',NULL,NULL,NULL,10.00,'JZ-13-AAEE905D00',NULL,0,NULL),(101,NULL,1,NULL,'Storytelling — Winnie de Poeh',NULL,NULL,NULL,6.00,'JZ-1-43AFF7A1CD',NULL,0,NULL),(102,NULL,4,NULL,'Storytelling — Corrie voor kinderen',NULL,NULL,NULL,0.00,'JZ-4-850D901FCA',NULL,0,NULL),(103,NULL,15,NULL,'Storytelling — Winners of storytelling competition',NULL,NULL,NULL,12.50,'JZ-15-85D4181DCA',NULL,0,NULL),(104,NULL,2,NULL,'Storytelling — Omdenken Podcast',NULL,NULL,NULL,12.50,'JZ-2-60AE0B6D98',NULL,0,NULL),(105,NULL,6,NULL,'Storytelling — Het verhaal van de Oeserzwammerij',NULL,NULL,NULL,0.00,'JZ-6-68368BE70E',NULL,0,NULL),(106,NULL,5,NULL,'Storytelling — Winnaars van verhalenvertel wedstrijd',NULL,NULL,NULL,12.50,'JZ-5-672F4C0002',NULL,0,NULL),(107,NULL,3,NULL,'Storytelling — The story of Buurderij Haarlem',NULL,NULL,NULL,0.00,'JZ-3-00BF5F27DD',NULL,0,NULL),(108,NULL,7,NULL,'Storytelling — Flip Thinking Podcast',NULL,NULL,NULL,12.50,'JZ-7-43B8876C82',NULL,0,NULL),(113,45,0,4,'Family Ticket',NULL,NULL,NULL,60.00,'GEN-8EEC49C312ED',NULL,1,'2026-04-10 11:19:16'),(114,45,0,4,'Admission Ticket',NULL,NULL,NULL,17.50,'GEN-B9B151FE1859',NULL,0,NULL),(115,46,0,4,'Family Ticket',NULL,NULL,NULL,60.00,'GEN-DB495B083F70',NULL,0,NULL),(116,NULL,17,NULL,'Storytelling — three',NULL,NULL,NULL,100.00,'JZ-17-43F097F93A',NULL,0,NULL),(117,NULL,100,NULL,'Admission — test',NULL,NULL,NULL,15.00,'JZ-100-65BC78F5FB',NULL,0,NULL),(118,47,0,4,'Family Ticket',NULL,NULL,NULL,60.00,'GEN-37D5B9919549',NULL,0,NULL),(119,48,0,4,'Admission Ticket',NULL,NULL,NULL,17.50,'GEN-FAAFF96FC400',NULL,0,NULL),(120,49,0,4,'Admission Ticket',NULL,NULL,NULL,17.50,'GEN-79599B799558',NULL,0,NULL),(121,49,0,4,'Family Ticket',NULL,NULL,NULL,60.00,'GEN-573646E0FB20',NULL,0,NULL),(128,NULL,0,NULL,'Yummy – Ratatouille | Fri 25 July | Session 3 21:00–23:00',NULL,NULL,NULL,40.00,'YMY-6B491D9EC7B6',NULL,0,NULL),(129,NULL,0,NULL,'Yummy – Ratatouille | Thu 24 July | Session 3 21:00–23:00',NULL,NULL,NULL,20.00,'YMY-1D2C6199B69A',NULL,0,NULL),(130,NULL,0,NULL,'Yummy – Ratatouille | Thu 24 July | Session 1 17:00–19:00',NULL,NULL,NULL,30.00,'YMY-53900A66D0CD',NULL,0,NULL),(131,NULL,0,NULL,'Yummy – Ratatouille | Wed 23 July | Session 1 17:00–19:00',NULL,NULL,NULL,10.00,'YMY-5BA52D5810BA',NULL,0,NULL),(132,NULL,0,NULL,'Yummy – Ratatouille | Wed 23 July | Session 1 17:00–19:00',NULL,NULL,NULL,40.00,'YMY-CFB4623FEBBF',NULL,0,NULL),(133,53,0,4,'Admission Ticket','Thursday','10:00','Dutch',17.50,'GEN-D59EF68554E2',NULL,0,NULL),(134,53,0,4,'Yummy – Ratatouille | Wed 23 July | Session 1 17:00–19:00',NULL,NULL,NULL,10.00,'GEN-FE37F70D517C',NULL,0,NULL),(135,53,49,4,'Admission — Gumbo Kings',NULL,NULL,NULL,15.00,'GEN-47DDE3DB4428',NULL,0,NULL),(136,53,57,4,'Admission — Chris Allen',NULL,NULL,NULL,15.00,'GEN-C9D2364AF83F',NULL,0,NULL),(137,54,0,4,'Admission Ticket','Thursday','10:00','Dutch',17.50,'GEN-946BBAA51CD2',NULL,0,NULL),(138,NULL,0,NULL,'Yummy – Ratatouille | Wed 23 July | Session 1 17:00–19:00',NULL,NULL,NULL,10.00,'YMY-3CA31936A6CC',NULL,0,NULL),(139,55,0,4,'Yummy – Ratatouille | Wed 23 July | Session 1 17:00–19:00',NULL,NULL,NULL,10.00,'GEN-9C08EF46D52C',NULL,0,NULL),(140,55,52,4,'Admission — Wicked Jazz Sounds',NULL,NULL,NULL,10.00,'GEN-65C386E3271F',NULL,0,NULL),(141,55,0,4,'Family Ticket','Thursday','10:00','Dutch',60.00,'GEN-A716A0AB42CB',NULL,0,NULL),(142,55,8,4,'Storytelling — Meneer Anansi',NULL,NULL,NULL,10.00,'GEN-423E66D1C847',NULL,0,NULL),(143,NULL,0,NULL,'Yummy – Ratatouille | Fri 25 July | Session 3 21:00–23:00',NULL,NULL,NULL,60.00,'YMY-2BED0554DF4E',NULL,0,NULL),(144,NULL,0,NULL,'Yummy – Ratatouille | Fri 25 July | Session 3 21:00–23:00',NULL,NULL,NULL,10.00,'YMY-BF7D212418D1',NULL,0,NULL),(145,56,0,11,'Admission Ticket','Thursday','10:00','Dutch',17.50,'GEN-D10CD1D6D65B',NULL,0,NULL);
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','employee','admin') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_image` varchar(255) DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `password_reset_token` varchar(64) DEFAULT NULL,
  `password_reset_expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Mikotaj','730282@student.inholland.nl','$2y$12$P/ZKyx3CMG5usWAMdyJI7ebiHLiTG817DvJdY02wYKp8/zmjG6qqG','admin','2026-02-16 14:31:42','/uploads/profiles/profile_1_1773141669.gif',NULL,NULL,NULL),(2,'cityboy','cityboyyyy@cityboysssss.com','$2y$12$IsRcDDusAX52sTMzAViHve4geFpATY0H33C3sFZfnfwDR9VwlVKFa','employee','2026-02-17 10:45:27',NULL,NULL,NULL,NULL),(3,'User','user@gmail.com','$2y$12$TK27zTPAS6HdlCqtQoK5ruFDMlqYb3IZijTaKxKuiXK6coJgYcTUi','customer','2026-03-01 19:47:26',NULL,NULL,NULL,NULL),(4,'Inholland','727115@student.inholland.nl','$2y$12$a.jZsLmR0/n0npnmuZ3w/Of6ktVsfKU12f8r4yVrTT89KKlgR.l5C','admin','2026-03-01 20:47:33','/uploads/profiles/profile_4_1773137929.jpg',NULL,NULL,NULL),(5,'Wesley','w.r.vanderkleij@gmail.com','$2y$12$CMju0P5X9VAZQL/kqBqdCus2oEi/7fdinKY1MiGPlYGkP.nLI/sxS','admin','2026-03-02 12:22:39',NULL,NULL,NULL,NULL),(6,'Ilyes Ouerghi','zagloulou94@gmail.com','$2y$12$htI2WXziwH0H5C2Ho46nhOpLp8GPHlpnIBAT32g5QSr6FronqYrEa','admin','2026-03-02 12:46:53','/uploads/profiles/profile_6_1773147295.gif',NULL,NULL,NULL),(7,'micheal angelo','zagloulou@gmail.com','$2y$12$YdwxZ48VVM3lJVlda4fPXepJg56Ka8lC4Jfr5k43h8uliYYaO9XL.','customer','2026-03-02 13:21:48',NULL,NULL,NULL,NULL),(8,'Mo','motest@gmail.com','$2y$12$QuQATwacXpayBRPB74QkFu1v9Zo2aGMYYOm2qoGReBOLJvHyr.kOy','admin','2026-03-09 15:20:41',NULL,NULL,NULL,NULL),(9,'skibidy','skibidy@gmail.com','$2y$12$va0q5OxZQEieQoTOTD6n..t31CuS25U.90lWvF3Tb.iwHT7Hpv6MG','customer','2026-03-10 13:40:50',NULL,NULL,NULL,NULL),(10,'jw90djaiodjawio','wjdioajdioawjiodjaw@gmail.com','$2y$12$1G5j1BUyYQCUcvu7szt.re270W/SyMxbPcY2a2Oo95KvopMvZ6Bnm','admin','2026-04-09 18:02:59',NULL,NULL,NULL,NULL),(11,'Peter','peter@gmail.com','$2y$12$pPL1T.nALebeP.waMhavTerAwIRQLlvGtrU0EG4Z7Lno3VoWURZyS','customer','2026-04-10 11:11:07',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `yummy_reservations`
--

DROP TABLE IF EXISTS `yummy_reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `yummy_reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `restaurant_id` int NOT NULL,
  `session_number` tinyint NOT NULL,
  `festival_date` date NOT NULL,
  `adults` int NOT NULL DEFAULT '0',
  `children` int NOT NULL DEFAULT '0',
  `special_request` text,
  `reservation_fee_cents` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `ticket_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `restaurant_id` (`restaurant_id`),
  KEY `fk_yr_ticket` (`ticket_id`),
  CONSTRAINT `fk_yr_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE SET NULL,
  CONSTRAINT `yummy_reservations_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `yummy_reservations`
--

LOCK TABLES `yummy_reservations` WRITE;
/*!40000 ALTER TABLE `yummy_reservations` DISABLE KEYS */;
INSERT INTO `yummy_reservations` VALUES (1,2,1,'2026-07-24',1,1,'Im allergic to fish.',2000,8,'2026-04-10 00:29:22','pending',NULL),(2,2,1,'2026-07-24',2,0,NULL,2000,8,'2026-04-10 01:18:34','pending',NULL),(3,2,3,'2026-07-25',1,0,NULL,1000,8,'2026-04-10 01:23:44','pending',NULL),(4,2,1,'2026-07-26',1,2,NULL,3000,8,'2026-04-10 01:37:22','pending',NULL),(5,1,3,'2026-07-24',2,1,'dwa',3000,4,'2026-04-10 09:42:08','pending',NULL),(6,2,2,'2026-07-24',2,0,NULL,2000,1,'2026-04-10 09:54:26','pending',NULL),(7,2,2,'2026-07-24',2,0,NULL,2000,1,'2026-04-10 09:54:52','pending',NULL),(8,2,2,'2026-07-25',1,1,NULL,2000,NULL,'2026-04-10 10:05:38','pending',NULL),(9,2,2,'2026-07-25',1,1,NULL,2000,NULL,'2026-04-10 10:06:20','pending',NULL),(10,2,3,'2026-07-25',3,1,'test',4000,NULL,'2026-04-10 10:12:44','pending',128),(11,2,3,'2026-07-24',1,1,NULL,2000,NULL,'2026-04-10 10:15:04','pending',129),(12,2,1,'2026-07-24',1,2,NULL,3000,8,'2026-04-10 10:16:03','pending',130),(13,2,1,'2026-07-23',1,0,NULL,1000,4,'2026-04-10 10:19:02','pending',131),(14,2,1,'2026-07-23',3,1,NULL,4000,8,'2026-04-10 10:20:31','pending',132),(15,2,1,'2026-07-23',1,0,NULL,1000,4,'2026-04-10 10:41:05','pending',138),(16,2,3,'2026-07-25',6,0,NULL,6000,4,'2026-04-10 11:04:06','pending',143),(17,2,3,'2026-07-25',1,0,NULL,1000,NULL,'2026-04-10 11:04:46','pending',144);
/*!40000 ALTER TABLE `yummy_reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'defaultdb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-08 22:37:48
