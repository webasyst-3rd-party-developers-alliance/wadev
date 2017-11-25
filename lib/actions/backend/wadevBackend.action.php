<?php
class wadevBackendAction extends wadevViewAction
{
    public function execute()
    {
        
        $message = 'Hello world!';
        $api_key = wa('wadev')->getConfig()->getSetting('api_key');
        if($api_key) {
            $balance = (new wadevWebasystMyApi($api_key))->balance();
        } else {
            $balance = [];
        }
        $this->view->assign(compact('balance', 'message'));
        $this->view->assign('is_debug', waSystemConfig::isDebug());
    }
}
