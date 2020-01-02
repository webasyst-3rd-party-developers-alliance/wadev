<?php
class wadevHelpdeskView_actionHandler extends waEventHandler
{
    public function execute(&$params)
    {
        if (!ifset($params['action']) instanceof helpdeskRequestsInfoAction) {
            return null;
        }
        if (!wa()->getUser()->getRights('wadev', 'backend')) {
            return null;
        }

        return $this->getHtml($params['action']->request_id);
    }

    protected function getHtml($request_id) {

        $d = waLocale::getDomain();
        waLocale::loadByDomain('wadev');

        try {
            $view = new waSmarty3View(wa());
            $view->assign([
                'wadev_app_url' => wa()->getAppUrl('wadev')
            ]);

            $result = $view->fetch(wa('wadev')->getAppPath('templates/handlers/helpdesk/view_action.html', 'wadev'));
        } catch (Exception $e) {
            $result = '';
        }

        waLocale::loadByDomain($d);;

        return $result;
    }
}
