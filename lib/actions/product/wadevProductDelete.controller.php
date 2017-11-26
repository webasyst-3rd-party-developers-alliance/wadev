<?php

class wadevProductDeleteController extends waJsonController
{

    public function execute()
    {
        $product_id = waRequest::post('product_id');
        $model_product = new wadevProductModel();
        if ($model_product->deleteById($product_id)) {
            return;
        } else {
            throw new waException('Error delete product (id='.$product_id.') from DB ');
        }
    }
}