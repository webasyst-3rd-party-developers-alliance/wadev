"use strict";

var LicensePage = ( function($) {

    LicensePage = function(options) {
        var that = this;

        that.$wrapper = options["$wrapper"];
        that.$form = $('#wadev-license-form');
        that.$searchButton = that.$form.find("button[type=\"submit\"]");

        // VARS
        that.locales = options["locales"];

        // DYNAMIC VARS
        that.$notice = false;
        that.is_locked = false;
        that.xhr = false;

        // INIT
        that.initClass();
    };

    LicensePage.prototype.initClass = function() {
        var that = this;
        //
        that.bindEvents();
    };

    LicensePage.prototype.bindEvents = function() {
        var that = this;

        that.$form.on("submit", function(event) {
            event.preventDefault();
            that.submit(that.$form );
        });
    };

    LicensePage.prototype.submit = function( $form ) {
        var that = this,
            url = $.wadev.app_url + "?module=license&action=check",
            data = $form.serializeArray();

        var $loading = $("<i class=\"icon16 loading\" style=\"margin: 0 4px;\"></i>");
        $loading.insertAfter( that.$searchButton );

        if (!that.is_locked) {
            that.is_locked = true;
            $.post(url, data, function(r) {

                if (r.status === 'ok') {

                }

            }).always( function() {
                $loading.remove();
                that.is_locked = false;
            });
        }
    };

    return LicensePage;

})(jQuery);
