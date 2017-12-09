<?php
$m = new waModel();
$m->exec("ALTER TABLE `wadev_product` CHANGE `slug` `slug` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_general_ci  NOT NULL  DEFAULT ''");