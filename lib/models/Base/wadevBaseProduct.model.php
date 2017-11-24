<?php

/**
 * Class wadevBaseProductModel
 * @property $id integer
 * @property $name string
 * @property $price float
 * @property $repeated_license integer
 * @property $partner integer
 * @property $current_version string
 * @property $slug string
 */
class wadevBaseProductModel extends wadevModelExt
{
    protected $table = 'wadev_product';
}