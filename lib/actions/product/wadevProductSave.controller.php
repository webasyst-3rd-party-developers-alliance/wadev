<?php

class wadevProductSaveController extends waJsonController
{

    public function execute()
    {
        $post = waRequest::post();
        $model_product = new wadevProductModel();
        return $model_product->insert($post['product'], 1);
    }
}