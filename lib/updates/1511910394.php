<?php
$model = new waModel();
try {
    $transaction_schema = $model->describe('wadev_product');

    if (isset($transaction_schema['price']) && $transaction_schema['price']['type'] != 'decimal') {
        $model->exec('ALTER TABLE `wadev_product` CHANGE `price` `price` DECIMAL(15,2)');
        $model->exec('ALTER TABLE `wadev_product` MODIFY `price` DECIMAL(15,2) DEFAULT 0.0;');
    }

} catch (waException $e) {

}
