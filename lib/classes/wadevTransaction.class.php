<?php

/**
 * Class wadevTransaction
 *
 * @property wadevTransactionModel $model
 */
class wadevTransaction extends wadevEntity
{
    public function __construct($model = null)
    {
        if ($model === null) {
            $model = new wadevTransactionModel();
        }
        parent::__construct($model);
    }

    /**
     * Получает транзакции из API и сохраняет новые в БД
     *
     * @return bool|int
     */
    public function updateFromApi()
    {
        $api_key = wadevHelper::getApiKey();
        if (!$api_key) {
            return false;
        }
        $last_update = (int)wa('wadev')->getConfig()->getSetting('api.transactions');
        $refresh_rate = (int)wa('wadev')->getConfig()->getSetting('refresh_rate');
        // не прошел еще нужны интервал
        if (time() - $last_update < $refresh_rate * 60) {
            return 0;
        }

        $new_transactions = [];

        try {
            $transactions = (new wadevWebasystMyApi($api_key))->transactions(['last' => 100]);
            wa('wadev')->getConfig()->setSetting('api.transactions', time());

            $last_transaction = $this->model->findLast(1);
            if (!is_null($last_transaction)) {
                $transactions = array_filter($transactions, function ($t) use ($last_transaction) {
                    return strtotime($t['datetime']) > strtotime($last_transaction->datetime);
                });
            }

            foreach ($transactions as $t) {
                $transaction = new wadevTransactionModel($t);
                if ($transaction->save()) {
                    $new_transactions[] = $transaction;
                }
            }

        } catch (waException $e) {
            // todo do smth
        }

        return count($new_transactions);
    }

    public function getOrderInfo($order_id)
    {
        $api_key = wadevHelper::getApiKey();
        if (!$api_key) {
            return false;
        }
        $order = (new wadevWebasystMyApi($api_key))->order($order_id);

        return $order;
    }
}