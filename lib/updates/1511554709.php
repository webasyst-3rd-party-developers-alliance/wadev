<?php
/**
 * Created by PhpStorm.
 * User: kirillmaramygin
 * Date: 11/24/17
 * Time: 11:18 PM
 */

$m = new wadevTransactionModel([
    'datetime' => waDateTime::date('Y-m-d'),
    'balance_before' => 0,
    'amount' => 100.1,
    'balance_after' => 100.1,
    'order_id' => 123233,
    'comment' => 'ты у меня первый',
]);
$m->save();

$m = new wadevTransactionModel([
    'datetime' => waDateTime::date('Y-m-d'),
    'balance_before' => 100.1,
    'amount' => 1,
    'balance_after' => 102.1,
    'order_id' => 4423233,
    'comment' => 'ты у меня НЕ первый',
]);
$m->save();