<?php

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @version
 * @copyright Serge Rodovnichenko, 2017
 * @license
 */
class wadevSettingsSaveController extends waJsonController
{
    protected $valid_settings = ['api_key', 'refresh_rate'];

    public function execute()
    {
        $data = (array)$this->getRequest()->post('data', array(), waRequest::TYPE_ARRAY);
        $AppSetting = new waAppSettingsModel();

        foreach ($data as $key => $val) {
            if (in_array($key, $this->valid_settings) && !empty($val)) {
                $AppSetting->set('wadev', $key, $val);
            }
        }
    }
}
