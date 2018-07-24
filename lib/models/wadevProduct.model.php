<?php

class wadevProductModel extends wadevBaseProductModel
{
    public function findBySlug($slug)
    {
        $product = $this->select('*')->where('slug = s:slug', ['slug' => $slug])->fetchAssoc();
        return self::generateModels([$product], true);
    }
}