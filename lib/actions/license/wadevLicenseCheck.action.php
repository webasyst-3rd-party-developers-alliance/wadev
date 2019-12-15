<?php

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2017
 */
class wadevLicenseCheckAction extends wadevViewAction
{
    public function execute()
    {
        $data = (array)$this->getRequest()->post('data', [], waRequest::TYPE_ARRAY);
        $data = array_filter(array_intersect_key($data, array_flip(['domain', 'product'])));

        $error = '';
        $licenses = array();

        try {
            if (empty($data['domain'])) {
                throw new waException(_w('Domain required'));
            }
            $data['domain'] = trim($data['domain']);
            if (wa_is_int($data['domain'])) {
                $order = wa('wadev')->getConfig()->getWebasystMyApi()->order($data['domain']);
                $order['id'] = $data['domain'];
                $this->setTemplate(wa()->getAppPath('templates/actions/order/OrderCheck.html'));
                $this->view->assign(compact('order'));
            } else {
                $licenses = wa('wadev')->getConfig()->getWebasystMyApi()->check(
                    $data['domain'], empty($data['product']) ? null : $data['product']
                );
                $net = new wadevNet();
                try {
                    $is_cloud = $net->query('http://' . $data['domain'] . '/wa-apps/hosting/css/hosting.css');
                } catch (waException $e) {
                    $is_cloud = false;
                }
                $this->view->assign(compact('licenses', 'is_cloud'));
            }
        } catch (waException $e) {
            $error = $e->getMessage();
        }
        $this->view->assign('error', $error);
    }
}
