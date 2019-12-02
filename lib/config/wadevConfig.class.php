<?php

use SergeR\CakeUtility\Hash;

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2017
 */
class wadevConfig extends waAppConfig
{
    /** @var waAppSettingsModel */
    protected $AppSetting;

    /**
     * @param array $route
     * @return array|null
     * @throws waException
     */
    public function getRouting($route = array())
    {
        if ($this->routes === null) {
            $path = $this->getConfigPath('routing.backend.php', true, $this->application);
            if (!file_exists($path)) {
                $path = $this->getConfigPath('routing.backend.php', false, $this->application);
            }
            if (file_exists($path)) {
                $this->routes = include($path);
            } else {
                $this->routes = array();
            }

            $this->routes = array_merge($this->getPluginRoutes($route), $this->routes);
        }
        return $this->routes;
    }

    /**
     * @param $route
     * @return array
     * @throws waException
     */
    protected function getPluginRoutes($route)
    {
        /**
         * Extend routing via plugin routes
         * @event routing
         * @param array $routes
         * @return array $routes routes collected for every plugin
         */
        $result = wa()->event(array($this->application, 'routing_backend'), $route);
        $all_plugins_routes = array();
        foreach ($result as $plugin_id => $routing_rules) {
            if (!$routing_rules) {
                continue;
            }
            $plugin = str_replace('-plugin', '', $plugin_id);
            array_walk($routing_rules, function ($route, $url) use ($all_plugins_routes, $plugin) {
                if (!is_array($route)) {
                    list($route_ar['module'], $route_ar['action']) = explode('/', $route);
                    $route = $route_ar;
                }
                if (!array_key_exists('plugin', $route)) {
                    $route['plugin'] = $plugin;
                }
                $all_plugins_routes[$url] = $route;
            });
        }
        return $all_plugins_routes;
    }

    /**
     * @param null $name
     * @param string $default
     * @return array|mixed|string
     * @throws waDbException
     * @throws waException
     */
    public function getSetting($name = null, $default = '')
    {
        return $this->getAppSettingsModel()->get($name, $default);
    }

    /**
     * @param $name
     * @param $value
     * @return wadevConfig
     * @throws waDbException
     * @throws waException
     */
    public function setSetting($name, $value)
    {
        return $this->setUserSetting($name, $value);
    }

    /**
     * Считывает сохраненные переменные, относящиеся к текущему пользователю и нашему приложению
     *
     * @param null $name
     * @param null $default
     * @return array|mixed|null
     * @throws waDbException
     * @throws waException
     *
     * @todo это будет обёртка к waContact->getSetting()
     * @todo wadevUser
     */
    public function getUserSetting($name = null, $default = null)
    {
        return (new wadevSettingsModel)->get(wa()->getUser()->getId(), $name, $default);
    }

    /**
     * Сохраняет переменную в профиле текущего пользователя
     *
     * @param $name
     * @param null $value
     * @return $this
     * @throws waDbException
     * @throws waException
     *
     * @todo Это будет обёртка к waContact->setSetting()
     * @todo wadevUser
     */
    public function setUserSetting($name, $value = null)
    {
        (new wadevSettingsModel)->set(wa()->getUser()->getId(), $name, $value);
        return $this;
    }

    /**
     * @param bool $update
     * @return array|mixed|null
     * @throws waDbException
     * @throws waException
     */
    public function currentBalance($update = false)
    {
        $cached_balance = $this->getUserSetting('balance', null);

        if (is_string($cached_balance)) {
            $balance = json_decode($cached_balance, true);
        }

        if ($update || !is_array($balance)) {
            try {
                $balance = (new wadevWebasystMyApi($this->getUserSetting('api_key')))->balance();
                $this->setUserSetting('balance', waUtils::jsonEncode($balance));
            } catch (waException $e) {
                $balance = array_merge(['balance' => 0.0, 'currency' => 'RUB', 'update_datetime' => date('Y-m-d H:i:s'), 'error' => $e->getMessage()]);
            }
        }

        return $balance;
    }

    /**
     * @param null $id
     * @return wadevUser
     */
    public function getUser($id = null)
    {
        return new wadevUser($id);
    }

    /**
     * @return wadevWebasystMyApi
     * @throws waException
     */
    public function getWebasystMyApi()
    {
        return new wadevWebasystMyApi($this->getUserSetting('api_key'));
    }

    /**
     * @return wadevSettingsModel
     * @throws waDbException
     * @throws waException
     */
    protected function getAppSettingsModel()
    {
        if (!$this->AppSetting) {
            $this->AppSetting = new wadevSettingsModel();
        }

        return $this->AppSetting;
    }

    /**
     * @return bool|int|string
     * @throws waDbException
     * @throws waException
     */
    public function onCount()
    {
        $unseen_transactions = (int)$this->getSetting('new_transactions');

        if ($this->isTransactionRefreshRequired()) {
            if (($new_transactions = $this->fetchNewTransactions())) {
                $unseen_transactions += $new_transactions;
                $this->setUserSetting('new_transactions', $unseen_transactions);
            }
        }
        return $unseen_transactions;
    }

    /**
     * Сахар для получения люча API текущего пользователя
     *
     * @return string
     * @throws waDbException
     * @throws waException
     * @todo refactor when user will have many "profiles"
     *
     */
    public function getApiKey()
    {
        return $this->getUserSetting('api_key', '');
    }

    /**
     * Загружает новые транзакции с сервера Webasyst и сохраняет их в БД
     *
     * @return int количество добавленных транзакций
     * @throws waDbException
     * @throws waException
     */
    public function fetchNewTransactions()
    {
        if (!($api_key = $this->getApiKey())) {
            return 0;
        }

        $TransactionModel = new wadevTransactionModel();

        $transactions = (new wadevWebasystMyApi($api_key))->transactions(['last' => 100]);
        $this->setUserSetting('api.transactions', time());
        $contact_id = (int)wa()->getUser()->getId();

        $last_transaction = $TransactionModel->findLast($contact_id, 1);
        if ($last_transaction) {
            $last_transaction = array_shift($last_transaction);
            $last_recorded_time = strtotime($last_transaction['datetime']);
            $transactions = array_filter($transactions, function ($lt) use ($last_recorded_time) {
                return strtotime($lt['datetime']) > $last_recorded_time;
            });
        }

        $transactions = array_map(function ($t) use ($contact_id) {
            return [
                'contact_id'     => $contact_id,
                'datetime' => date('Y-m-d H:i:s', strtotime($t['datetime'])),
                'balance_before' => (float)str_replace(',', '.', $t['balance_before']),
                'balance_after'  => (float)str_replace(',', '.', $t['balance_after']),
                'amount'         => (float)str_replace(',', '.', $t['amount']),
                'order_id'       => (int)$t['order_id'],
                'comment'        => $t['comment']
            ];
        }, $transactions);
        $transaction_chunks = array_chunk($transactions, 25);
        array_walk($transaction_chunks, function ($transactions) use ($TransactionModel) {
            $TransactionModel->multipleInsert($transactions);
        });

        return count($transactions);
    }

    /**
     * Вовращает true если нужно опросить сервер Webasyst на предмет новых транзакций.
     * Учитывает настройки пользователя по частоте опроса
     *
     * @return bool
     * @throws waDbException
     * @throws waException
     * @todo move to wadevUser
     */
    public function isTransactionRefreshRequired()
    {
        $user_settings = $this->getAppSettingsModel()->get(wa()->getUser()->getId());
        if (!($api_key = Hash::get($user_settings, 'api_key'))) {
            return false;
        }

        // Блин, ну точка-то зачем? Придется ifset из-за этого
        $last_update = (int)ifset($user_settings, 'api.transactions', 0);
        $refresh_rate = 60 * max(1, (int)Hash::get($user_settings, 'refresh_rate'));

        return time() - $last_update > $refresh_rate;
    }

    /**
     * Загрузим сначала нашу автозагрузку классов (а то вебассист из своей исключениями бросается)
     */
    public function init()
    {
        require_once __DIR__ . '/../vendors/autoload.php';
        parent::init();
    }
}
