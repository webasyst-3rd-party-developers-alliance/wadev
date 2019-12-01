<?php

/**
 * Class wadevProductModel
 */
class wadevProductModel extends wadevModel
{
    public function findBySlug($slug)
    {
        return $this->select('*')->where('slug = s:slug', ['slug' => $slug])->fetchAssoc();
    }
}