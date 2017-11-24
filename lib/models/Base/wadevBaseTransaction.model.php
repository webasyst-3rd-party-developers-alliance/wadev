<?php

/**
 * Class wadevBaseTransactionModel
 * @property $id integer
 * @property $datetime string
 * @property $balance_before float
 * @property $amount float
 * @property $balance_after float
 * @property $order_id integer
 * @property $comment string
 */
class wadevBaseTransactionModel extends wadevModelExt
{
    protected $table = 'wadev_transaction';
}