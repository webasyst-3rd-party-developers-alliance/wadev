<?php

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2017
 */
class wadevSettingsAction extends wadevViewAction
{
    /** @var waAppSettingsModel */
    protected $AppSetting;

    public function execute()
    {
        //@todo: checkRights

        $settings = (array)$this->AppSetting->get('wadev');
        unset($settings['api_key'], $settings['update_time']);

        $this->view->assign(compact('settings'));
    }

    protected function preExecute()
    {
        parent::preExecute();

        $this->AppSetting = new waAppSettingsModel();
    }


}
