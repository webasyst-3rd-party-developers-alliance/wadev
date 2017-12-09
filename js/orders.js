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

        if(that.xhr) {
            that.xhr.abort();
            that.xhr = false;
        }

        var dialog_wrapper = '<div class="w-dialog-wrapper is-full-screen w-view-order-dialog" id="w-view-order"><div class="w-dialog-background"></div><div class="w-dialog-block gray-header compact-header"><div class="loading128"></div></div></div>';
        dialog = new WadevDialog({html: dialog_wrapper});
        that.xhr = $.get(href, data, function (html) {
            $('.w-dialog-block', '#w-view-order').html(html);
            dialog.setPosition();
            //new WadevDialog({html: html})
        });
    };

    return OrderDialog;
})(jQuery);