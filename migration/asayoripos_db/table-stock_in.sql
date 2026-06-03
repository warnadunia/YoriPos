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

-- Dumping structure for table asayoripos_db.stock_in
CREATE TABLE IF NOT EXISTS `stock_in` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `qty` int NOT NULL,
  `price_buy` decimal(15,2) NOT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `date_in` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) /*T![clustered_index] CLUSTERED */,
  KEY `fk_1` (`product_id`),
  CONSTRAINT `fk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin AUTO_INCREMENT=120001;

-- Dumping data for table asayoripos_db.stock_in: ~9 rows (approximately)
DELETE FROM `stock_in`;
INSERT INTO `stock_in` (`id`, `product_id`, `qty`, `price_buy`, `supplier`, `date_in`) VALUES
	(1, 1, 1000, 200.00, 'Roastery Lokal', '2026-05-27 17:52:54'),
	(2, 2, 2000, 15.00, 'Distributor Susu', '2026-05-27 17:52:54'),
	(3, 3, 1000, 25.00, 'Toko Bahan Kue', '2026-05-27 17:52:54'),
	(4, 4, 100, 500.00, 'Toko Plastik', '2026-05-27 17:52:54'),
	(5, 5, 5000, 45.00, 'Supplier Daging', '2026-05-27 17:52:54'),
	(6, 6, 50, 1000.00, 'Toko Bahan Kue', '2026-05-27 17:52:54'),
	(7, 10, 150, 2100.00, 'Peternakan AKAM', '2026-05-27 17:52:54'),
	(90002, 210001, 200, 800.00, 'Toko Plastik Bagong', '2026-05-29 11:12:26'),
	(90003, 90001, 300, 533.33, 'Peternakan mas Andi', '2026-05-29 11:16:22');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
