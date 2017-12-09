<?php

class wadevPromocodeAction extends wadevViewAction
{
    public function execute()
    {
        wadevPromocode::updateFromApi();
        $last_update = (int)wa('wadev')->getConfig()->getSetting('api.promocode');

        $promos = wadevPromocode::generate(wadevPromocodeModel::model()->findAll());

        $this->view->assign('promocodes', $promos);
        $this->view->assign('last_update', $last_update);
    }
}