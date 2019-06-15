<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

/**
 * Добавляем колонку account_code, которая будет ссылаться на аккаунт, подключенный к приложению
 * Добавляем колонку, добавляем всем записям значение по умолчанию, делаем колонку NOT NULL и создаём индекс
 */

$m = new waModel();

try {
    $v = $m->query('SELECT `account_id` FROM `wadev_transaction` WHERE 1=1 LIMIT 1');
} catch (waDbException $e) {
    $m->exec('ALTER TABLE `wadev_transaction` ADD `account_code` VARCHAR(100)');
    $m->exec('UPDATE `wadev_transaction` SET `account_code` = \'default\'');
    $m->exec('ALTER TABLE `wadev_transaction` MODIFY `account_code` VARCHAR(100) NOT NULL');
    $m->exec('CREATE INDEX `account_code_index` ON `wadev_transaction` (`account_code`)');
}
