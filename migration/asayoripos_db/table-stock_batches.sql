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

-- Dumping structure for table asayoripos_db.stock_batches
CREATE TABLE IF NOT EXISTS `stock_batches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `batch_no` varchar(50) NOT NULL,
  `price_buy` decimal(15,2) NOT NULL,
  `qty_initial` int NOT NULL,
  `qty_remaining` int NOT NULL,
  `date_received` datetime NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) /*T![clustered_index] CLUSTERED */,
  KEY `fk_1` (`product_id`),
  CONSTRAINT `fk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin AUTO_INCREMENT=120001;

-- Dumping data for table asayoripos_db.stock_batches: ~12 rows (approximately)
DELETE FROM `stock_batches`;
INSERT INTO `stock_batches` (`id`, `product_id`, `batch_no`, `price_buy`, `qty_initial`, `qty_remaining`, `date_received`, `created_at`) VALUES
	(1, 1, 'BCH-1001', 200.00, 1000, 1000, '2026-05-27 17:54:27', '2026-05-27 17:54:27'),
	(2, 2, 'BCH-1002', 15.00, 2000, 2000, '2026-05-27 17:54:27', '2026-05-27 17:54:27'),
	(3, 3, 'BCH-1003', 25.00, 1000, 1000, '2026-05-27 17:54:27', '2026-05-27 17:54:27'),
	(4, 4, 'BCH-1004', 500.00, 100, 100, '2026-05-27 17:54:27', '2026-05-27 17:54:27'),
	(5, 5, 'BCH-1005', 45.00, 5000, 5000, '2026-05-27 17:54:27', '2026-05-27 17:54:27'),
	(6, 6, 'BCH-1006', 1000.00, 50, 50, '2026-05-27 17:54:27', '2026-05-27 17:54:27'),
	(7, 10, 'BCH-1007', 2100.00, 150, 150, '2026-05-27 17:54:27', '2026-05-27 17:54:27'),
	(30001, 60001, 'BCH-SYNC-2', 2000.00, 300, 208, '2026-05-04 22:39:55', '2026-05-26 22:49:36'),
	(30002, 30001, 'BCH-SYNC-3', 12000.00, 10, 10, '2026-05-04 22:45:34', '2026-05-26 22:49:36'),
	(30003, 1, 'BCH-SYNC-4', 8000.00, 15, 14, '2026-05-04 23:00:45', '2026-05-26 22:49:36'),
	(90001, 210001, 'BCH-20260529111226-156', 800.00, 200, 196, '2026-05-29 11:12:26', '2026-05-29 11:12:26'),
	(90002, 90001, 'BCH-20260529111621-984', 533.33, 300, 50, '2026-05-29 11:16:22', '2026-05-29 11:16:22');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
