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

-- Dumping structure for table asayoripos_db.cashier_shifts
CREATE TABLE IF NOT EXISTS `cashier_shifts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `open_time` timestamp DEFAULT CURRENT_TIMESTAMP,
  `close_time` timestamp NULL DEFAULT NULL,
  `starting_cash` decimal(15,2) DEFAULT '0',
  `actual_cash` decimal(15,2) DEFAULT '0',
  `expected_cash` decimal(15,2) DEFAULT '0',
  `notes` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'open',
  PRIMARY KEY (`id`) /*T![clustered_index] CLUSTERED */
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin AUTO_INCREMENT=60001;

-- Dumping data for table asayoripos_db.cashier_shifts: ~3 rows (approximately)
DELETE FROM `cashier_shifts`;
INSERT INTO `cashier_shifts` (`id`, `user_id`, `user_name`, `open_time`, `close_time`, `starting_cash`, `actual_cash`, `expected_cash`, `notes`, `status`) VALUES
	(1, 1, 'Super Admin', '2026-05-30 10:45:40', NULL, 20000.00, 0.00, 0.00, NULL, 'open'),
	(30001, 1, 'Super Admin', '2026-05-30 11:06:21', NULL, 10000.00, 0.00, 0.00, NULL, 'open'),
	(30002, 1, 'Super Admin', '2026-05-30 11:06:21', NULL, 10000.00, 0.00, 0.00, NULL, 'open');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
