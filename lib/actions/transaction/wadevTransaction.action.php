<?php

class wadevTransactionAction extends wadevContentViewAction
{
    public function execute()
    {
        $transactions = wadevTransactionModel::model()->getLast();

        $this->view->assign([
            'hello'        => 'hi',
            'transactions' => $transactions,
        ]);
    }
}