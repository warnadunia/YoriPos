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

-- Dumping structure for table asayoripos_db.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `sku` varchar(50) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `type` enum('produk_jual','bahan_baku') DEFAULT 'produk_jual',
  `unit` varchar(20) DEFAULT 'pcs',
  `image_url` varchar(255) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `price_sell` decimal(15,2) NOT NULL,
  `total_stock` int NOT NULL DEFAULT '0',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) /*T![clustered_index] CLUSTERED */,
  UNIQUE KEY `sku` (`sku`),
  KEY `fk_1` (`category_id`),
  CONSTRAINT `fk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin AUTO_INCREMENT=240001;

-- Dumping data for table asayoripos_db.products: ~16 rows (approximately)
DELETE FROM `products`;
INSERT INTO `products` (`id`, `category_id`, `sku`, `category`, `type`, `unit`, `image_url`, `name`, `price_sell`, `total_stock`, `created_at`) VALUES
	(1, NULL, 'RAW-001', 'Lainnya', 'bahan_baku', 'gram', NULL, 'Biji Kopi Arabica Blend', 0.00, 1000, '2026-05-27 17:52:40'),
	(2, NULL, 'RAW-002', 'Lainnya', 'bahan_baku', 'ml', NULL, 'Susu UHT Full Cream', 0.00, 2000, '2026-05-27 17:52:40'),
	(3, NULL, 'RAW-003', 'Lainnya', 'bahan_baku', 'ml', NULL, 'Gula Aren Cair', 0.00, 1000, '2026-05-27 17:52:40'),
	(4, NULL, 'RAW-004', 'Lainnya', 'bahan_baku', 'pcs', NULL, 'Cup Plastik 16oz + Lid', 0.00, 100, '2026-05-27 17:52:40'),
	(5, NULL, 'RAW-005', 'Lainnya', 'bahan_baku', 'gram', NULL, 'Daging Ayam Fillet', 0.00, 5000, '2026-05-27 17:52:40'),
	(6, NULL, 'RAW-006', 'Lainnya', 'bahan_baku', 'pcs', NULL, 'Keju Slice Cheddar', 0.00, 50, '2026-05-27 17:52:40'),
	(7, NULL, 'MNU-001', 'Minuman Kopi', 'produk_jual', 'porsi', NULL, 'Kopi Susu Gula Aren', 18000.00, 0, '2026-05-27 17:52:40'),
	(8, NULL, 'MNU-002', 'Makanan Utama', 'produk_jual', 'porsi', NULL, 'Chicken Double Cheese', 35000.00, 0, '2026-05-27 17:52:40'),
	(9, NULL, 'MNU-003', 'Minuman Non-Kopi', 'produk_jual', 'porsi', NULL, 'Matcha Latte Dingin', 22000.00, 0, '2026-05-27 17:52:40'),
	(10, NULL, 'RTL-001', 'Sembako / Retail', 'produk_jual', 'pcs', NULL, 'Telur AKAM Herbal (Retail)', 2700.00, 150, '2026-05-27 17:52:40'),
	(30001, NULL, 'SKU-002', 'Minuman Non-Kopi', 'produk_jual', 'pcs', NULL, 'Matcha Latte Dingin', 22000.00, 7, '2026-05-04 04:24:27'),
	(60001, NULL, 'SKU-003', 'Sembako', 'produk_jual', 'pcs', 'https://nqfpnuiuw2ly0kre.public.blob.vercel-storage.com/1779889401_anakmakantelurmatang-SoN9l6LvwAfbwuVaUpp5HfzNDPl58w.webp', 'Telur AKAM Herbal', 2700.00, 208, '2026-05-04 22:20:33'),
	(90001, NULL, 'SKU-004', 'Sembako', 'bahan_baku', 'pcs', 'https://nqfpnuiuw2ly0kre.public.blob.vercel-storage.com/1779894287_telurakam-Fx3oevaIjCD4MNUTntSeWURojNj2su.jpg', 'Telur Ayam Kampung Herbal', 0.00, 50, '2026-05-27 08:55:01'),
	(120001, NULL, 'AKP-001', 'Sembako', 'produk_jual', 'porsi', '', 'Telur Ayam Kampung Pack 10', 27000.00, 0, '2026-05-27 16:50:39'),
	(180001, NULL, 'SKU-005', 'Sembako', 'produk_jual', 'porsi', '', 'Telur AKAM Tray', 80000.00, 0, '2026-05-27 20:15:21'),
	(210001, NULL, 'PCK-010', 'Lainnya', 'bahan_baku', 'pcs', '', 'Mika Lock isi 10', 0.00, 196, '2026-05-29 11:08:06');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
