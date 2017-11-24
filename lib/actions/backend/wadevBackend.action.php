<?php
class wadevBackendAction extends wadevViewAction
{
    public function execute()
    {
        
        $message = 'Hello world!';
        $this->view->assign('message', $message);
    }
}
