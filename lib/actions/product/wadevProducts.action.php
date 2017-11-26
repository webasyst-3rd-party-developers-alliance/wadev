<?php

class wadevProductsAction extends wadevViewAction
{

    public function execute()
    {
        $model_product = new wadevProductModel();
        $this->view->assign('products', $model_product->getAll());
    }
}