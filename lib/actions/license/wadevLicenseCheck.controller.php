<?php

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @version
 * @copyright Serge Rodovnichenko, 2017
 * @license
 */
class wadevLicenseCheckController extends waJsonController
{
    public function execute()
    {
        $data = (array)$this->getRequest()->post('data', [], waRequest::TYPE_ARRAY);
        $data = array_filter(array_intersect_key($data, array_flip(['domain', 'product'])));

        if (empty($data['domain'])) {
            $this->errors[] = ['field' => 'domain', 'message' => _w('Domain required')];
            return;
        }

        try {
            $this->response = (new wadevWebasystMyApi(wa('wadev')->getConfig()->getSetting('api_key')))
                ->check($data['domain'], empty($data['product']) ? null : $data['product']);
            return;
        } catch (waException $e) {
            $this->errors[] = $e->getMessage();
        }
    }
}
