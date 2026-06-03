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

-- Dumping structure for table asayoripos_db.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) /*T![clustered_index] CLUSTERED */
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin AUTO_INCREMENT=30002;

-- Dumping data for table asayoripos_db.customers: ~3 rows (approximately)
DELETE FROM `customers`;
INSERT INTO `customers` (`id`, `name`, `phone`, `address`, `latitude`, `longitude`, `created_at`) VALUES
	(1, 'Bapak Direktur', '081234567890', 'Kantor Pusat PT Jogjatama Vishesha', '-7.816438', '110.386829', '2026-05-27 18:15:26'),
	(2, 'Ibu Manager Operasional', '081987654321', 'Divisi Operasional', '-7.816438', '110.386829', '2026-05-27 18:15:26'),
	(3, 'Pelanggan Setia', '081122334455', 'Jl. Wates Raya', NULL, NULL, '2026-05-27 18:15:26');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
