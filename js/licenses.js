"use strict";

var LicensePage = (function ($) {

    LicensePage = function (options) {
        var that = this;

        that.$wrapper = options["$wrapper"];
        that.$form = $('#wadev-license-form');
        that.$searchButton = that.$form.find("button[type=\"submit\"]");
        that.$result_block = $('.result.block', that.$wrapper);

        // VARS
        that.locales = options["locales"];

        // DYNAMIC VARS
        that.$notice = false;
        that.is_locked = false;
        that.xhr = false;

        // INIT
        that.initClass();
    };

    LicensePage.prototype.initClass = function () {
        var that = this;
        //
        that.bindEvents();
    };

    LicensePage.prototype.bindEvents = function () {
        var that = this;

        that.$form.on("submit", function (event) {
            event.preventDefault();
            that.submit(that.$form);
        });
    };

    function l10n(str, locales) {
        return (locales[str] || str);
    }

    function ShowLicenses(data, $result, locales) {
        $result.empty();

        var $table = $('<table class="zebra"><thead><tr></tr></thead><tbody></tbody></table>');
        $('thead  tr', $table).append('<th>' + l10n('Product', locales) + '</th>')
            .append('<th>' + l10n('Issued', locales) + '</th>')
            .append('<th>' + l10n('Installed', locales) + '</th>')
            .append('<th>' + l10n('Order', locales) + '</th>')
            .append('<th class="min-width">' + l10n('Leased', locales) + '</th>');

        $('tbody', $table).append(tmpl('license-rows', {licenses: data}));

        $result.append($table);
    }

    LicensePage.prototype.submit = function ($form) {
        var that = this,
            url = $.wadev.app_url + "?module=license&action=check",
            data = $form.serializeArray();

        var $loading = $("<i class=\"icon16 loading\" style=\"margin: 0 4px;\"></i>");
        $loading.insertAfter(that.$searchButton);

        if (!that.is_locked) {
            that.is_locked = true;
            $.post(url, data, function (r) {

                if (r.status === 'ok') {
                    if (r.data && $.isArray(r.data) && r.data.length) {
                        ShowLicenses(r.data, that.$result_block, that.locales);
                    }
                }

            }).always(function () {
                $loading.remove();
                that.is_locked = false;
            });
        }
    };

    return LicensePage;

})(jQuery);
