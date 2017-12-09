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
    public function findAll($search = '', $between = [], $bounds = [], &$total_rows = null)
    {
        ifempty($bounds[1], 10);

        $search_sql = '';
        if (!empty($search)) {
            $search_sql = "AND CONCAT(order_id, '', comment) LIKE '%" . $this->escape($search, 'like') . "%'";
        }

        $between[0] = date("Y-m-d 00:00:00", ifempty($between[0], 0));
        $between[1] = date("Y-m-d 23:59:59", ifempty($between[1], 1893456000));
        $between_sql = 'datetime BETWEEN s:from AND s:to';

        $transactions = $this->query("
                SELECT SQL_CALC_FOUND_ROWS
                    *
                FROM wadev_transaction
                WHERE
                {$between_sql}
                {$search_sql}
                ORDER BY datetime DESC
                LIMIT i:start, i:limit
            ", [
                'start'  => $bounds[0],
                'limit'  => $bounds[1],
                'search' => $search,
                'from' => $between[0],
                'to' => $between[1],
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