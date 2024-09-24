CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` varchar(65) DEFAULT NULL,
  `old_price` varchar(65) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `html` longtext DEFAULT NULL,
  `price_for` varchar(100) DEFAULT NULL,
  `weight` varchar(100) DEFAULT NULL,
  `length` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- agelar_parser.`options` definition

CREATE TABLE `options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned DEFAULT NULL,
  `option_name` varchar(255) DEFAULT NULL,
  `option_value` varchar(1000) DEFAULT NULL,
  `option_key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- agelar_parser.images definition

CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `filename` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- agelar_parser.categories definition

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(500) NOT NULL,
  `html` longtext DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `seo_text` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;