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

-- Dumping structure for table asayoripos_db.product_recipes
CREATE TABLE IF NOT EXISTS `product_recipes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_id` int NOT NULL,
  `material_id` int NOT NULL,
  `qty_required` decimal(10,2) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) /*T![clustered_index] CLUSTERED */
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin AUTO_INCREMENT=120002;

-- Dumping data for table asayoripos_db.product_recipes: ~10 rows (approximately)
DELETE FROM `product_recipes`;
INSERT INTO `product_recipes` (`id`, `menu_id`, `material_id`, `qty_required`, `created_at`) VALUES
	(1, 7, 1, 15.00, '2026-05-27 17:54:38'),
	(2, 7, 2, 150.00, '2026-05-27 17:54:38'),
	(3, 7, 3, 20.00, '2026-05-27 17:54:38'),
	(4, 7, 4, 1.00, '2026-05-27 17:54:38'),
	(5, 8, 5, 150.00, '2026-05-27 17:54:38'),
	(6, 8, 6, 2.00, '2026-05-27 17:54:38'),
	(30002, 120001, 210001, 1.00, '2026-05-29 11:16:56'),
	(30003, 120001, 90001, 10.00, '2026-05-29 11:16:56'),
	(60002, 10, 90001, 1.00, '2026-05-29 11:53:03'),
	(90002, 180001, 90001, 30.00, '2026-05-29 12:00:26');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
