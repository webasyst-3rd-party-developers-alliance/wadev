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
        $uniqid = str_replace('.', '-', uniqid('s', true));
        $menuname = htmlspecialchars(_w('Check license'));
        $wadev_url = wa()->getAppUrl('wadev');
        $wa_url = wa()->getRootUrl();
        return <<<EOF

            <!-- begin output from wadevHelpdeskView_actionHandler -->
            <script src="{$wa_url}wa-apps/wadev/js/licenses.js" type="text/javascript"></script>
            <ul style="display:none" id="{$uniqid}">
                <li><a href="#" target="_blank"><i class="icon16" style="background-image:url('{$wa_url}wa-apps/wadev/img/wadev48.png');background-size:16px 16px"></i>{$menuname}</a></li>
            </ul>
            <script>(function() {
                var wrapper = $('#{$uniqid}');
                wrapper.find('a').on('click', function(e) {
                  e.preventDefault();
                  $.wadev = {
                      app_url: '{$wadev_url}'
                  }; 
                  $('<div>').waDialog({
                    url: '{$wadev_url}?&module=license'
                  });
                });
                var dropdown = $('#h-request-operations .menu-v');
                wrapper.children('li').insertBefore(dropdown.children('li.hr:last'));
                wrapper.remove();
            })();</script>
            <!-- end output from wadevHelpdeskView_actionHandler -->
EOF;
    }
}
