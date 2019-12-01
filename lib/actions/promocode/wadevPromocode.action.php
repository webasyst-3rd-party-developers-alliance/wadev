<?php

class wadevPromocodeAction extends wadevViewAction
{
    public function execute()
    {
//        wadevPromocode::updateFromApi();
        $last_update = (int)wa('wadev')->getConfig()->getSetting('api.promocode');

        $Promocode = new wadevPromocodeModel();
        $PromocodeProducts = new wadevPromocodeProductsModel();
        $Product = new wadevProduct();


        $promocodes = $Promocode->order('create_datetime ASC')->fetchAll();

        array_walk($promocodes, function(&$promocode) use ($PromocodeProducts){
            $sql = 'SELECT product.* FROM wadev_promocode_products AS pp LEFT JOIN wadev_product AS product ON pp.product_id=product.id WHERE pp.code_id=i:code_id';
            $promocode['Product'] = (array)$PromocodeProducts->query($sql, ['code_id'=>$promocode['id']])->fetchAll();
        });

        $this->view->assign('promocodes', $promocodes);
        $this->view->assign('last_update', $last_update);
    }
}