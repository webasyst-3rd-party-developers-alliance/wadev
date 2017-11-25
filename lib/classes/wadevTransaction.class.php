<?php

/**
 * Class wadevTransaction
 *
 * @property wadevTransactionModel $model
 */
class wadevTransaction extends wadevEntity
{
    /**
     * Получает транзакции из API и сохраняет новые в БД
     *
     * @return bool|int
     */
    public function updateFromApi()
    {
        $api_key = wa('wadev')->getConfig()->getSetting('api_key');
        if(!$api_key) {
            return false;
        }
        $transactions = (new wadevWebasystMyApi($api_key))->transactions(['last' => 100]);

        $last_transaction = $this->model->findLast(1);
        $new_transactions = [];

        if (is_array($transactions)) {
            foreach ($transactions as $t) {
                if (strtotime($t['datetime']) <= strtotime($last_transaction->datetime)) {
                    break;
                }

                $transaction = new wadevTransactionModel($t);
                if ($transaction->save()) {
                    $new_transactions[] = $transaction;
                }
            }
        }

        return count($new_transactions);
    }
}