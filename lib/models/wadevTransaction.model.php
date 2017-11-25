<?php

class wadevTransactionModel extends wadevBaseTransactionModel
{
    /**
     * @param int    $limit
     * @param string $search
     * @param int    $start
     * @param int    $total_rows
     *
     * @return null|wadevTransactionModel|wadevTransactionModel[]
     */
    public function findAll($search = '', $start = 0, $limit = 10, &$total_rows = null)
    {
        $search_sql = '';
        if (!empty($search)) {
            $search_sql = "WHERE CONCAT(order_id, '', comment) LIKE '%" . $this->escape($search, 'like') . "%'";
        }

        $transactions = $this->query("
                SELECT SQL_CALC_FOUND_ROWS
                    *
                FROM wadev_transaction
                {$search_sql}
                LIMIT i:start, i:limit
            ", [
                'start'  => $start,
                'limit'  => $limit,
                'search' => $search,
            ]
        )->fetchAll();

        if ($total_rows) {
            $total_rows = $this->query('SELECT FOUND_ROWS()')->fetchField();
        }

        return self::generateModels($transactions);
    }

    public function findLast($limit = 10)
    {
        return self::generateModels($this->order('datetime DESC')->limit($limit)->fetchAll(), $limit === 1?: false);
    }

}