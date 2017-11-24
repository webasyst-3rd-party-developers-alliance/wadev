<?php
class wadevBackendAction extends wadevViewAction
{
    public function execute()
    {
        
        $message = 'Hello world!';
        $this->view->assign('message', $message);
        $this->view->assign('is_debug', waSystemConfig::isDebug());
    }
}
