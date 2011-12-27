CREATE TABLE `przelewy24` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `payed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `card` tinyint(1) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payed` (`payed`),
  KEY `shop_id` (`shop_id`),
  KEY `created` (`created`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
