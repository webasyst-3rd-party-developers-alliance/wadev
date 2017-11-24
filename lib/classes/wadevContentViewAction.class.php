<?php


class wadevContentViewAction extends waViewAction
{
    public function __construct($params = null)
    {
        parent::__construct($params);
        // если не ajax - отдадим весь layout (нужно для первоначальной загрузки)
        if (!waRequest::isXMLHttpRequest()) {
            $this->setLayout(new wadevBackendLayout());
        }
    }
}