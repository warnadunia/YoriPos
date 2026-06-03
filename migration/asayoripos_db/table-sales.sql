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

-- Dumping structure for table asayoripos_db.sales
CREATE TABLE IF NOT EXISTS `sales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(50) NOT NULL,
  `reference_no` varchar(50) DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `type` enum('direct','order') DEFAULT 'direct',
  `status` enum('proses','terkirim','lunas','piutang') DEFAULT 'lunas',
  `shipping_proof` varchar(255) DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'CASH',
  `payment_proof` mediumtext DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) /*T![clustered_index] CLUSTERED */,
  UNIQUE KEY `invoice_no` (`invoice_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin AUTO_INCREMENT=570001;

-- Dumping data for table asayoripos_db.sales: ~17 rows (approximately)
DELETE FROM `sales`;
INSERT INTO `sales` (`id`, `invoice_no`, `reference_no`, `customer_id`, `type`, `status`, `shipping_proof`, `total_amount`, `payment_method`, `payment_proof`, `created_at`) VALUES
	(30001, 'INV-20260504042529-954', NULL, NULL, 'direct', 'lunas', NULL, 44000.00, 'CASH', NULL, '2026-05-04 04:25:29'),
	(30002, 'INV-20260504043055-251', NULL, NULL, 'direct', 'lunas', NULL, 22000.00, 'CASH', NULL, '2026-05-04 04:30:55'),
	(60001, 'INV-20260504044010-779', NULL, NULL, 'direct', 'lunas', NULL, 22000.00, 'CASH', NULL, '2026-05-04 04:40:11'),
	(90001, 'INV-20260504060423-926', NULL, NULL, 'direct', 'lunas', NULL, 22000.00, 'CASH', NULL, '2026-05-04 06:04:24'),
	(120001, 'INV-20260504060956-241', NULL, NULL, 'direct', 'lunas', NULL, 22000.00, 'CASH', NULL, '2026-05-04 06:09:56'),
	(240001, 'INV-20260526225014-272', NULL, NULL, 'direct', 'lunas', NULL, 27000.00, 'CASH', NULL, '2026-05-26 22:50:14'),
	(270001, 'INV-20260526232955-547', NULL, NULL, 'direct', 'lunas', NULL, 27400.00, 'CASH', NULL, '2026-05-26 23:29:55'),
	(300001, 'INV-20260527002435-780', NULL, NULL, 'direct', 'lunas', NULL, 35500.00, 'CASH', NULL, '2026-05-27 00:24:35'),
	(300002, 'INV-20260527002720-887', NULL, NULL, 'direct', 'lunas', NULL, 10800.00, 'TRANSFER', NULL, '2026-05-27 00:27:20'),
	(330001, 'INV-20260527004335-890', NULL, NULL, 'direct', 'lunas', NULL, 2700.00, 'TRANSFER', NULL, '2026-05-27 00:43:35'),
	(360001, 'INV-20260527044449-792', NULL, 30001, 'direct', 'piutang', NULL, 162000.00, 'PIUTANG', NULL, '2026-05-27 04:44:49'),
	(390001, 'INV-20260527052505-980', 'ORD-20260527052505-980', 1, 'direct', 'proses', NULL, 40000.00, 'PIUTANG', NULL, '2026-05-27 05:25:05'),
	(450008, 'KWI-20260529123537-233', 'INV-20260529123537-233', 3, 'direct', 'lunas', NULL, 320000.00, 'CASH', NULL, '2026-05-29 12:35:38'),
	(450009, 'ORD-20260529125213-883', NULL, 2, 'order', 'proses', NULL, 108000.00, 'PENDING', NULL, '2026-05-29 12:52:13'),
	(480001, 'INV-20260530000237-881', NULL, 1, 'direct', 'proses', NULL, 16200.00, 'PIUTANG', NULL, '2026-05-30 00:02:39'),
	(510001, 'INV-2026053010021376', 'ORD-2026053010021376', 1, 'order', 'proses', NULL, 240000.00, 'PIUTANG', NULL, '2026-05-30 10:02:13'),
	(540001, 'ORD-2026053118340672', NULL, 1, 'order', 'proses', NULL, 10800.00, 'CASH', NULL, '2026-05-31 18:34:06');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
