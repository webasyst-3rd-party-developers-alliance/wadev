<?php

class wadevPromocodeAction extends wadevViewAction
{
    public function execute()
    {
        wadevPromocode::updateFromApi();

        $promos = wadevPromocode::generate(wadevPromocodeModel::model()->findAll());

        $this->view->assign('promocodes', $promos);
    }
}