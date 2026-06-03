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

-- Dumping structure for table asayoripos_db.sale_details
CREATE TABLE IF NOT EXISTS `sale_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sale_id` int NOT NULL,
  `product_id` int NOT NULL,
  `stock_batch_id` int DEFAULT '0',
  `qty` int NOT NULL,
  `price_buy_at_sale` decimal(15,2) NOT NULL,
  `price_sell_at_sale` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`) /*T![clustered_index] CLUSTERED */,
  KEY `fk_1` (`sale_id`),
  KEY `fk_2` (`product_id`),
  KEY `fk_3` (`stock_batch_id`),
  CONSTRAINT `fk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin AUTO_INCREMENT=480001;

-- Dumping data for table asayoripos_db.sale_details: ~20 rows (approximately)
DELETE FROM `sale_details`;
INSERT INTO `sale_details` (`id`, `sale_id`, `product_id`, `stock_batch_id`, `qty`, `price_buy_at_sale`, `price_sell_at_sale`) VALUES
	(1, 30001, 30001, 2, 2, 12000.00, 22000.00),
	(2, 30002, 30001, 2, 1, 12000.00, 22000.00),
	(30001, 60001, 30001, 2, 1, 12000.00, 22000.00),
	(60001, 90001, 30001, 2, 1, 12000.00, 22000.00),
	(90001, 120001, 30001, 2, 1, 12000.00, 22000.00),
	(180001, 240001, 60001, 30001, 10, 2000.00, 2700.00),
	(210001, 270001, 30001, 2, 1, 12000.00, 22000.00),
	(210002, 270001, 60001, 30001, 2, 2000.00, 2700.00),
	(240001, 300001, 60001, 30001, 5, 2000.00, 2700.00),
	(240002, 300001, 30001, 2, 1, 12000.00, 22000.00),
	(240003, 300002, 60001, 30001, 4, 2000.00, 2700.00),
	(270001, 330001, 60001, 30001, 1, 2000.00, 2700.00),
	(300001, 360001, 60001, 30001, 60, 2000.00, 2700.00),
	(330001, 390001, 30001, 2, 1, 12000.00, 22000.00),
	(330002, 390001, 1, 30003, 1, 8000.00, 18000.00),
	(360004, 450008, 180001, 0, 4, 15999.90, 80000.00),
	(360005, 450009, 120001, 0, 4, 6133.30, 27000.00),
	(390001, 480001, 60001, 0, 6, 2000.00, 2700.00),
	(420001, 510001, 180001, 0, 3, 15999.90, 80000.00),
	(450001, 540001, 60001, 0, 4, 2000.00, 2700.00);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
