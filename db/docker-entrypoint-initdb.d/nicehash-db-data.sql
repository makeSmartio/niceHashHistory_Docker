CREATE DATABASE niceHash;
use niceHash;

--
-- Table structure for table `WebLog`
--

DROP TABLE IF EXISTS `WebLog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `WebLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `TimeStamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `Page` varchar(100) DEFAULT NULL,
  `IP` varchar(100) DEFAULT NULL,
  `UserAgent` varchar(200) DEFAULT NULL,
  `What` varchar(200) DEFAULT NULL,
  `RowCount` varchar(20) DEFAULT NULL,
  `FreeSpace` varchar(20) DEFAULT NULL,
  `Referrer` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12270 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `batchId` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `rigId` varchar(255) DEFAULT NULL,
  `rigName` varchar(255) DEFAULT NULL,
  `deviceId` varchar(255) DEFAULT NULL,
  `deviceName` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `powerUsage` int(11) DEFAULT NULL,
  `temperature` int(11) DEFAULT NULL,
  `deviceLoad` int(11) DEFAULT NULL,
  `revolutionsPerMinute` int(11) DEFAULT NULL,
  `revolutionsPerMinutePercentage` int(11) DEFAULT NULL,
  `intensity` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=584763 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `niceHash`
--

DROP TABLE IF EXISTS `niceHash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `niceHash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `batchId` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `statsTime` bigint(20) DEFAULT NULL,
  `market` varchar(255) DEFAULT NULL,
  `algorithm` varchar(255) DEFAULT NULL,
  `unpaidAmount` varchar(255) DEFAULT NULL,
  `difficulty` decimal(50,20) DEFAULT NULL,
  `proxyId` int(11) DEFAULT NULL,
  `timeConnected` bigint(20) DEFAULT NULL,
  `xnsub` varchar(255) DEFAULT NULL,
  `speedAccepted` decimal(25,15) DEFAULT NULL,
  `speedRejectedR1Target` decimal(25,15) DEFAULT NULL,
  `speedRejectedR2Stale` decimal(25,15) DEFAULT NULL,
  `speedRejectedR3Duplicate` decimal(25,15) DEFAULT NULL,
  `speedRejectedR4NTime` decimal(25,15) DEFAULT NULL,
  `speedRejectedR5Other` decimal(25,15) DEFAULT NULL,
  `speedRejectedTotal` decimal(25,15) DEFAULT NULL,
  `profitability` decimal(40,30) DEFAULT NULL,
  `rigName` varchar(255) DEFAULT NULL,
  `ignoreReading` varchar(50) DEFAULT 'false',
  `workerId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=309472 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `niceHashErrors`
--

DROP TABLE IF EXISTS `niceHashErrors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `niceHashErrors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `data` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=182675 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `niceHashKeys`
--

DROP TABLE IF EXISTS `niceHashKeys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `niceHashKeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `lastUpdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastPollResult` varchar(5000) DEFAULT NULL,
  `ignoreRecord` varchar(255) DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `pollingLog`
--

DROP TABLE IF EXISTS `pollingLog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pollingLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `page` varchar(255) DEFAULT NULL,
  `batchId` int(11) DEFAULT NULL,
  `records` int(11) DEFAULT NULL,
  `errors` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15671 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rigs2`
--

DROP TABLE IF EXISTS `rigs2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rigs2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `ignoreReading` varchar(50) DEFAULT 'false',
  `batchId` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `profitability` decimal(40,30) DEFAULT NULL,
  `localProfitability` decimal(40,30) DEFAULT NULL,
  `totalDevices` int(11) DEFAULT NULL,
  `unpaidAmount` decimal(40,30) DEFAULT NULL,
  `rigId` varchar(255) DEFAULT NULL,
  `rigName` varchar(255) DEFAULT NULL,
  `cpuMiningEnabled` varchar(50) DEFAULT NULL,
  `minerStatus` varchar(255) DEFAULT NULL,
  `rigPowerMode` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=236401 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `rigs2_polling`
--

DROP TABLE IF EXISTS `rigs2_polling`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rigs2_polling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `ignoreReading` tinyint(1) DEFAULT 0,
  `batchId` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `totalProfitability` decimal(40,30) DEFAULT NULL,
  `totalProfitabilityLocal` decimal(40,30) DEFAULT NULL,
  `unpaidAmount` decimal(40,30) DEFAULT NULL,
  `nextPayoutTimestamp` varchar(255) DEFAULT NULL,
  `lastPayoutTimestamp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142052 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `ignoreReading` tinyint(1) DEFAULT 0,
  `batchId` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `rigId` varchar(255) DEFAULT NULL,
  `rigName` varchar(255) DEFAULT NULL,
  `profitability` decimal(40,30) DEFAULT NULL,
  `market` varchar(255) DEFAULT NULL,
  `algorithm` varchar(255) DEFAULT NULL,
  `deviceDifficulty` decimal(30,15) DEFAULT NULL,
  `speedAccepted` decimal(25,15) DEFAULT NULL,
  `speedRejectedR1Target` decimal(25,15) DEFAULT NULL,
  `speedRejectedR2Stale` decimal(25,15) DEFAULT NULL,
  `speedRejectedR3Duplicate` decimal(25,15) DEFAULT NULL,
  `speedRejectedR4NTime` decimal(25,15) DEFAULT NULL,
  `speedRejectedR5Other` decimal(25,15) DEFAULT NULL,
  `speedRejectedTotal` decimal(25,15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=275859 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `timezones`
--

DROP TABLE IF EXISTS `timezones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timezones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timezones`
--

LOCK TABLES `timezones` WRITE;
/*!40000 ALTER TABLE `timezones` DISABLE KEYS */;
INSERT INTO `timezones` VALUES (1,'-12','(GMT -12) Eniwetok, Kwajalein'),(2,'-11','(GMT -11) Midway Island, Samoa'),(3,'-10','(GMT -10) Hawaii'),(4,'-9.50','(GMT -9:30) Taiohae'),(5,'-9','(GMT -9) Alaska'),(6,'-8','(GMT -8) Pacific Time (US &amp; Canada)'),(7,'-7','(GMT -7) Mountain Time (US &amp; Canada)'),(8,'-6','(GMT -6) Central Time (US &amp; Canada), Mexico City'),(9,'-5','(GMT -5) Eastern Time (US &amp; Canada), Bogota, Lima'),(10,'-4.50','(GMT -4:30) Caracas'),(11,'-4','(GMT -4) Atlantic Time (Canada), Caracas, La Paz'),(12,'-3.50','(GMT -3:30) Newfoundland'),(13,'-3','(GMT -3) Brazil, Buenos Aires, Georgetown'),(14,'-2','(GMT -2) Mid-Atlantic'),(15,'-1','(GMT -1) Azores, Cape Verde Islands'),(16,'0','(GMT) Western Europe Time, London, Lisbon, Casablanca'),(17,'1','(GMT +1) Brussels, Copenhagen, Madrid, Paris'),(18,'2','(GMT +2) Kaliningrad, South Africa'),(19,'3','(GMT +3) Baghdad, Riyadh, Moscow, St. Petersburg'),(20,'3.50','(GMT +3:30) Tehran'),(21,'4','(GMT +4) Abu Dhabi, Muscat, Baku, Tbilisi'),(22,'4.50','(GMT +4:30) Kabul'),(23,'5','(GMT +5) Ekaterinburg, Islamabad, Karachi, Tashkent'),(24,'5.50','(GMT +5:30) Bombay, Calcutta, Madras, New Delhi'),(25,'5.75','(GMT +5:45) Kathmandu, Pokhar'),(26,'6','(GMT +6) Almaty, Dhaka, Colombo'),(27,'6.50','(GMT +6:30) Yangon, Mandalay'),(28,'7','(GMT +7) Bangkok, Hanoi, Jakarta'),(29,'8','(GMT +8) Beijing, Perth, Singapore, Hong Kong'),(30,'8.75','(GMT +8:45) Eucla'),(31,'+9','(GMT +9) Tokyo, Seoul, Osaka, Sapporo, Yakutsk'),(32,'9.50','(GMT +9:30) Adelaide, Darwin'),(33,'10','(GMT +10) Eastern Australia, Guam, Vladivostok'),(34,'10.50','(GMT +10:30) Lord Howe Island'),(35,'11','(GMT +11) Magadan, Solomon Islands, New Caledonia'),(36,'11.50','(GMT +11:30) Norfolk Island'),(37,'12','(GMT +12) Auckland, Wellington, Fiji, Kamchatka'),(38,'12.75','(GMT +12:45) Chatham Islands'),(39,'13','(GMT +13) Apia, Nukualofa'),(40,'14','(GMT +14) Line Islands, Tokelau');
/*!40000 ALTER TABLE `timezones` ENABLE KEYS */;
UNLOCK TABLES;
