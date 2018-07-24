<?php

/**
 * Class wadevBasePromocodeModel
 * @property $id integer
 * @property $create_datetime string
 * @property $type string
 * @property $code string
 * @property $start_date string
 * @property $end_date string
 * @property $percent float
 * @property $description string
 * @property $usage integer
 * @property $hash string
 */
class wadevBasePromocodeModel extends wadevModelExt
{
    protected $table = 'wadev_promocode';
}