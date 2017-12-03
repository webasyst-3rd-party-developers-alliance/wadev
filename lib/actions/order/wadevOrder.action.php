<?php

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @version
 * @copyright Serge Rodovnichenko, 2017
 * @license
 */
class wadevOrderAction extends wadevViewAction
{
    public function execute()
    {
        $order_id = $this->getRequest()->get('order_id');
        $error = null;

        try {
            if (!$order_id) {
                throw new waException(_w('Order ID required'));
            }

            $order = wa('wadev')->getConfig()->getWebasystMyApi()->order($order_id);
            $order['id'] = $order_id;
        } catch (waException $e) {
            $order = [];
            $error = $e->getMessage();
        }

        $this->view->assign(compact('order', 'error'));
    }
}
