<?php

/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2017
 */
class wadevHelper
{
    /**
     * @return bool
     */
    public static function isAjax()
    {
        $is_ajax = waRequest::request('is_ajax', null, waRequest::TYPE_INT);
        if ($is_ajax !== null) {
            return (bool)$is_ajax;
        }
        return waRequest::isXMLHttpRequest();
    }
}
