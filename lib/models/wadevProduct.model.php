<?php

class wadevProductModel extends wadevBaseProductModel
{
    public function findBySlug($slug)
    {
        return self::generateModels($this->select('*')->where('slug = s:slug', ['slug' => $slug])->fetch(), false);
    }
}