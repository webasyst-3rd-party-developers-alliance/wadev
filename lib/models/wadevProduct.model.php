<?php

/**
 * Class wadevProductModel
 */
class wadevProductModel extends wadevModel
{
    protected $table = 'wadev_product';

    public function findBySlug($slug)
    {
        return $this->select('*')->where('slug = s:slug', ['slug' => $slug])->fetchAssoc();
    }
}