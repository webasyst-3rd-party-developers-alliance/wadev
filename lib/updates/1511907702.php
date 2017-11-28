<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @version
 * @copyright Serge Rodovnichenko, 2017
 * @license
 */

$model = new waModel();
try {
    $transaction_schema = $model->describe('wadev_transaction');

    if (isset($transaction_schema['balance_before']) && $transaction_schema['balance_before']['type'] != 'decimal') {
        $model->exec('ALTER TABLE `wadev_transaction` CHANGE `balance_before` `balance_before` DECIMAL(15,2)');
        $model->exec('ALTER TABLE `wadev_transaction` MODIFY `balance_before` DECIMAL(15,2) DEFAULT 0.0;');
    }

    if (isset($transaction_schema['amount']) && $transaction_schema['amount']['type'] != 'decimal') {
        $model->exec('ALTER TABLE `wadev_transaction` CHANGE `amount` `amount` DECIMAL(15,2)');
        $model->exec('ALTER TABLE `wadev_transaction` MODIFY `amount` DECIMAL(15,2) DEFAULT 0.0;');
    }

    if (isset($transaction_schema['balance_after']) && $transaction_schema['balance_after']['type'] != 'decimal') {
        $model->exec('ALTER TABLE `wadev_transaction` CHANGE `balance_after` `balance_after` DECIMAL(15,2)');
        $model->exec('ALTER TABLE `wadev_transaction` MODIFY `balance_after` DECIMAL(15,2) DEFAULT 0.0;');
    }
} catch (waException $e) {

}
