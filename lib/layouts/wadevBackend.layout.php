<?php

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @version
 * @copyright Serge Rodovnichenko, 2017
 * @license
 */
class wadevBackendLayout extends waLayout
{
    public function execute()
    {
        /**
         * Include plugins js and css
         * @event backend_assets
         * @return array[string]string $return[%plugin_id%]
         */
        $this->view->assign('backend_assets', wa()->event('backend_assets'));

        $this->view->assign(array(
            'is_debug' => (int)waSystemConfig::isDebug(),
        ));
    }
}
