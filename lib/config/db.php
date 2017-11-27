<?php
return array(
    'wadev_product' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'name' => array('varchar', 256, 'null' => 0),
        'price' => array('float', 'null' => 0, 'default' => '0'),
        'repeated_license' => array('int', 1, 'null' => 0, 'default' => '0'),
        'partner' => array('int', 1, 'null' => 0, 'default' => '0'),
        'current_version' => array('varchar', 16, 'null' => 0),
        'slug' => array('varchar', 32, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'wadev_promocode' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'create_datetime' => array('datetime'),
        'type' => array('enum', "'single','multi'", 'null' => 0, 'default' => 'single'),
        'code' => array('varchar', 64, 'null' => 0),
        'start_date' => array('date'),
        'end_date' => array('date'),
        'percent' => array('float', 'null' => 0),
        'description' => array('varchar', 256, 'null' => 0),
        'usage' => array('int', 11, 'null' => 0, 'default' => '0'),
        'hash' => array('varchar', 32, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
            'code' => 'code',
            'hash' => 'hash',
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
        'balance_before' => array('float', 'null' => 0, 'default' => '0'),
        'amount' => array('float', 'null' => 0, 'default' => '0'),
        'balance_after' => array('float', 'null' => 0, 'default' => '0'),
        'order_id' => array('int', 16, 'null' => 0),
        'comment' => array('varchar', 256, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
);
