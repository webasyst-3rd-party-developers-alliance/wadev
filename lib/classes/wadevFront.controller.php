<?php

class wadevFrontController extends waFrontController
{
    /**
     * Base frontend controller with backend routing support
     * Taken from team app
     *
     * @throws waException
     */
    public function dispatch()
    {
        $env = $this->system->getEnv();
        if ($env == 'backend') {
            // Assign routing parameters to waRequest::param()
            // to enable routing.backend.php
            $module = waRequest::get($this->options['module']);
            if (empty($module)) {
                $routing = new waRouting($this->system, array(
                    'default' => array(
                        array(
                            'url' => wa()->getConfig()->systemOption('backend_url').'/wadev/*',
                            'app' => 'wadev',
                        ),
                    ),
                ));
                $routing->dispatch();
                if (!waRequest::param('module')) {
                    throw new waException('Page not found', 404);
                }
            }
        }
        parent::dispatch();
    }
}
