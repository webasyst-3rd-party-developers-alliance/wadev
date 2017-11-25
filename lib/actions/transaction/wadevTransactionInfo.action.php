<?php

/**
 * Created by PhpStorm.
 * User: kirillmaramygin
 * Date: 11/25/17
 * Time: 1:52 PM
 */
class wadevTransactionInfoAction extends wadevViewAction
{
    public function execute()
    {
        $order_id = waRequest::get('order_id', 0, waRequest::TYPE_INT);

        if (!$order_id) {
            return;
        }

        $n = new wadevTransaction(new wadevTransactionModel());

        $this->view->assign('order', $n->getOrderInfo($order_id));
    }

}