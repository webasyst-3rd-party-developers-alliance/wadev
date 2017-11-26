<?php

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

    public function getSetting($name = null, $default = '')
    {
        return $this->getAppSettingsModel()->get('wadev', $name, $default);
    }

    public function setSetting($name, $value)
    {
        return $this->getAppSettingsModel()->set('wadev', $name, $value);
    }

    public function currentBalance($update = false)
    {
        $cached_balance = $this->getSetting('balance', null);
        $balance = null;

        if (is_string($cached_balance)) {
            $balance = json_decode($cached_balance, true);
        }

        if ($update || !is_array($balance)) {
            try {
                $balance = (new wadevWebasystMyApi($this->getSetting('api_key')))->balance();
                $this->getAppSettingsModel()->set('wadev', 'balance', json_encode($balance));
            } catch (waException $e) {
                if (!is_array($balance)) {
                    $balance = array();
                }
                $balance = array_merge(['balance' => 0.0, 'currency' => 'RUB', 'update_datetime' => date('Y-m-d H:i:s'), 'error' => $e->getMessage()]);
            }
        }

        return $balance;
    }

    /**
     * @return waAppSettingsModel
     */
    protected function getAppSettingsModel()
    {
        if (!$this->AppSetting) {
            $this->AppSetting = new waAppSettingsModel();
        }

        return $this->AppSetting;
    }

    public function onCount()
    {
        $new_transactions = 0;
        $transaction = new wadevTransaction();
        if ($new_ones = $transaction->updateFromApi()) {
            $new_transactions = (int) $this->getSetting('new_transactions') + $new_ones;
            $this->setSetting('new_transactions', $new_transactions);
        }
        return $new_transactions;
    }
}
