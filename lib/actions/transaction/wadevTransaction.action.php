<?php

class wadevTransactionAction extends wadevContentViewAction
{
    public function execute()
    {
        $this->view->assign(['hello' => 'hi']);
    }
}