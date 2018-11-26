<?php
class wadevBackendAction extends wadevViewAction
{
    public function execute()
    {
        
        $api_key = wadevHelper::getApiKey();
        if($api_key) {
            $balance = (new wadevWebasystMyApi($api_key))->balance();
        } else {
            $balance = [];
        }
        $this->view->assign(compact('balance', 'message'));
        $this->view->assign('is_debug', waSystemConfig::isDebug());
    }
}
