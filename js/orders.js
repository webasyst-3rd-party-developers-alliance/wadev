"use strict";
var OrderDialog = (function ($) {
    OrderDialog = function (options) {
        const that = this,
            order_id = options.order_id || null;

        const href = $.wadev.app_url;
        var data = {module: 'order'},
            dialog = false;
        if (order_id) {
            data['order_id'] = order_id;
        }

        $.get(href, data, function (html) {
            new WadevDialog({html: html})
        });
    };

    return OrderDialog;
})(jQuery);