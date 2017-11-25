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

    /**
     * @param $view waSmarty3View
     * @param $start
     * @param $limit
     * @param $total_rows
     */
    public static function assignPagination($view, $start, $limit, $total_rows)
    {
        $pagination = [];
        $limit = empty($limit) ? 1 : $limit;
        $current_page = floor($start / $limit) + 1;
        $total_pages = floor(($total_rows - 1) / $limit) + 1;
        $dots_added = false;
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i < 2) {
                $pagination[$i] = ($i - 1) * $limit;
                $dots_added = false;
            } else {
                if (abs($i - $current_page) < 2) {
                    $pagination[$i] = ($i - 1) * $limit;
                    $dots_added = false;
                } else {
                    if ($total_pages - $i < 1) {
                        $pagination[$i] = ($i - 1) * $limit;
                        $dots_added = false;
                    } else {
                        if (!$dots_added) {
                            $dots_added = true;
                            $pagination[$i] = false;
                        }
                    }
                }
            }
        }

        $view->assign(compact('start', 'limit', 'total_rows', 'pagination', 'current_page'));
    }
}
