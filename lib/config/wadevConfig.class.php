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

    public function getSetting($name = null)
    {
        return $this->getAppSettingsModel()->get('wadev', $name);
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
}
