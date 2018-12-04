<?php

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @version
 * @copyright Serge Rodovnichenko, 2017
 * @license
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

            $licenses = wa('wadev')->getConfig()->getWebasystMyApi()->check(
                $data['domain'], empty($data['product']) ? null : $data['product']
            );



        } catch (waException $e) {
            $error = $e->getMessage();
        }
        $net = new wadevNet();
        try {
            $is_cloud = $net->query('http://' . trim($data['domain']) . '/wa-apps/hosting/css/hosting.css');
        } catch (waException $e) {
            $is_cloud = false;
        }

        $this->view->assign(compact('error', 'licenses', 'is_cloud'));
    }
}
