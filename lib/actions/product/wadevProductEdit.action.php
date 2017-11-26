<?php

class wadevProductEditAction extends wadevViewAction
{

    public function execute()
    {
        $post = waRequest::post();
        if((int)$post['product_id']){
            $model_product = new wadevProductModel();
            $product = $model_product->getById((int)$post['product_id']);
        }else{
            $product = null;
        }
        $this->setTemplate(wa()->getAppPath('templates/actions/products/productEdit.html'));
        $this->view->assign('product', $product);
    }
}