<?php

$m = new waModel();

try {
    $m->exec("DROP TABLE wadev_promocode");
    $m->exec("CREATE TABLE IF NOT EXISTS `wadev_promocode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_datetime` datetime NULL,
  `type` enum('single','multi') NOT NULL DEFAULT 'single',
  `code` varchar(64) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `percent` float NOT NULL,
  `description` varchar(256) NOT NULL,
  `usage` int(11) NOT NULL DEFAULT '0',
  `hash` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
} catch (waException $ex) {
    waLog::log("wadev update error: " . $ex->getMessage());
}