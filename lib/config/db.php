<?php
return array(
    'wadev_product' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'name' => array('varchar', 256, 'null' => 0),
        'price' => array('decimal', "15,2", 'default' => '0.00'),
        'repeated_license' => array('int', 1, 'null' => 0, 'default' => '0'),
        'partner' => array('int', 1, 'null' => 0, 'default' => '0'),
        'current_version' => array('varchar', 16, 'null' => 0),
        'slug' => array('varchar', 255, 'null' => 0, 'default' => ''),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'wadev_promocode' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'create_datetime' => array('datetime', 'null' => 0, 'default' => 'CURRENT_TIMESTAMP'),
        'type' => array('varchar', 8, 'null' => 0, 'default' => 'single'),
        'code' => array('varchar', 64, 'null' => 0),
        'start_date' => array('datetime'),
        'end_date' => array('datetime'),
        'percent' => array('float', 'null' => 0),
        'description' => array('varchar', 256, 'null' => 0),
        'usage' => array('int', 11, 'null' => 0, 'default' => '0'),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'wadev_promocode_products' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'product_id' => array('int', 11, 'null' => 0),
        'code_id' => array('int', 11, 'null' => 0),
        'usage' => array('int', 11, 'null' => 0, 'default' => '0'),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'wadev_transaction' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'datetime' => array('datetime', 'null' => 0),
        'balance_before' => array('decimal', "15,2", 'default' => '0.00'),
        'amount' => array('decimal', "15,2", 'default' => '0.00'),
        'balance_after' => array('decimal', "15,2", 'default' => '0.00'),
        'order_id' => array('int', 16, 'null' => 0),
        'comment' => array('varchar', 256, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
);
