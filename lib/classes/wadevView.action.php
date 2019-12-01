<?php

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2017
 */
abstract class wadevViewAction extends waViewAction
{
    public function __construct($params = null)
    {
        parent::__construct($params);
        if (!wadevHelper::isAjax()) {
            $this->setLayout(new wadevBackendLayout());
        }
    }

    //todo: rights
}
