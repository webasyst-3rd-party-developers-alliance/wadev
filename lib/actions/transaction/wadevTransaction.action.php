<?php

class wadevTransactionAction extends wadevViewAction
{
    public function execute()
    {
        $t = new wadevTransaction(new wadevTransactionModel());
        $new_transactions_count = $t->updateFromApi();

        $search = waRequest::get('search', '', waRequest::TYPE_STRING_TRIM);
        $start = waRequest::param('start', 0, waRequest::TYPE_INT);
        $limit = waRequest::param('limit', 10, waRequest::TYPE_INT);
        $total_rows = true;

        $transactions = wadevTransactionModel::model()->findAll($search, $start, $limit, $total_rows);

        $balance = wa('wadev')->getConfig()->currentBalance((bool)$new_transactions_count);

        wadevHelper::assignPagination($this->view, $start, $limit, $total_rows);

        $this->view->assign(compact('balance', 'search', 'new_transactions_count', 'transactions'));
    }
}