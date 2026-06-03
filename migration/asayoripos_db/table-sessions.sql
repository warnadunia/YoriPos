-- --------------------------------------------------------
-- Host:                         gateway01.ap-southeast-1.prod.aws.tidbcloud.com
-- Server version:               8.0.11-TiDB-v8.5.3-serverless - TiDB Server (Apache License 2.0) Community Edition, MySQL 8.0 compatible
-- Server OS:                    linux
-- HeidiSQL Version:             12.15.0.7171
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table asayoripos_db.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(128) NOT NULL,
  `data` text NOT NULL,
  `timestamp` int NOT NULL,
  PRIMARY KEY (`id`) /*T![clustered_index] CLUSTERED */
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table asayoripos_db.sessions: ~10 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `data`, `timestamp`) VALUES
	('03fqg7gt52lgj6912g60ol2umi', 'user_id|i:1;name|s:11:"Super Admin";role|s:5:"Owner";permissions|a:7:{i:0;s:3:"pos";i:1;s:9:"dashboard";i:2;s:8:"products";i:3;s:6:"stocks";i:4;s:12:"transactions";i:5;s:8:"settings";i:6;s:5:"users";}', 1780347808),
	('07c86ca03ba25510ab0c523bc97d0644', '', 1780248034),
	('0e4ede4be9e3795f822851c89ea1139c', '', 1780248016),
	('0fa58e8805b76c85c21a7992775bfc16', '', 1780258181),
	('188b614899151303d93a2b61d61e20b1', '', 1780258328),
	('520ca98e8bdb81247209faf2103b27b2', 'user_id|i:1;name|s:11:"Super Admin";role|s:5:"Owner";permissions|a:7:{i:0;s:3:"pos";i:1;s:9:"dashboard";i:2;s:8:"products";i:3;s:6:"stocks";i:4;s:12:"transactions";i:5;s:8:"settings";i:6;s:5:"users";}', 1780247689),
	('5900a484579e85ba9e880da6b52f0345', 'user_id|i:1;name|s:11:"Super Admin";role|s:5:"Owner";permissions|a:7:{i:0;s:3:"pos";i:1;s:9:"dashboard";i:2;s:8:"products";i:3;s:6:"stocks";i:4;s:12:"transactions";i:5;s:8:"settings";i:6;s:5:"users";}', 1780285246),
	('62e1b1f145903e97334ea5ba96d3d4b5', '', 1780257943),
	('72d810004c7939e4f6b6cfcea839b9c0', '', 1780257945),
	('9d06f59e2b710bcae7b2fe9ae8fb9ea4', '', 1780257942);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
