<?php

/**
 * Class wadevTransactionAction
 */
class wadevTransactionAction extends wadevViewAction
{
    public function execute()
    {
        $t = new wadevTransaction(new wadevTransactionModel());
        $new_transactions_count = $t->updateFromApi();
        $last_update = (int)wa('wadev')->getConfig()->getSetting('api.transactions');

        // удалим инфу о новых
        (new wadevSettingsModel())->set('new_transactions', 0);
        wa('wadev')->getConfig()->getSetting('new_transactions', 0);
        $counts = wa()->getStorage()->get('apps-count');
        $counts['wadev'] = 0;
        wa()->getStorage()->write('apps-count', $counts);

        $search = waRequest::get('search', '', waRequest::TYPE_STRING_TRIM);
        $start = waRequest::param('start', 0, waRequest::TYPE_INT);
        $limit = waRequest::param('limit', 10, waRequest::TYPE_INT);
        $from = waRequest::get('from', '', waRequest::TYPE_STRING_TRIM);
        $to = waRequest::get('to', '', waRequest::TYPE_STRING_TRIM);
        $total_rows = true;

        /** @var wadevTransactionModel[] $transactions */
        $conditions = [];
        $condition_values = [];
        if ($search) {
            $conditions[] = 'comment LIKE \'%l:search%\'';
            $condition_values['search'] = $search;
        }
        if ($from) {
            $conditions[] = 'datetime >= s:from';
            $condition_values['from'] = date('Y-m-d 00:00:00', strtotime($from));
        }
        if ($to) {
            $conditions[] = 'datetime <= s:to';
            $condition_values['to'] = date('Y-m-d 00:00:00', strtotime($to));
        }

        $Transaction = new wadevTransactionModel();
        $condition = implode(' AND ', $conditions);

        $total_rows = $Transaction->select('COUNT(*)');
        if ($condition) $total_rows = $total_rows->where($condition, $condition_values);
        $total_rows = (int)$total_rows->fetchField();
        $transactions = $Transaction->select('*');
        if ($condition) $transactions = $transactions->where($condition, $condition_values);
        $transactions = $transactions->limit("$start, $limit")->fetchAll();

        $total = ['plus' => 0, 'minus' => 0];
        foreach ($transactions as $transaction) {
            $total[$transaction['amount'] > 0 ? 'plus' : 'minus'] += $transaction['amount'];
        }

        $balance = wa('wadev')->getConfig()->currentBalance(true);

        wadevHelper::assignPagination($this->view, $start, $limit, $total_rows);

        $this->view->assign(compact('search', 'from', 'to'));
        $this->view->assign(compact('balance', 'new_transactions_count', 'transactions', 'total', 'last_update'));
    }
}