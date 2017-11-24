"use strict";
// Pages

var SettingsPage = ( function($) {

    SettingsPage = function(options) {
        var that = this;

        that.$wrapper = options["$wrapper"];
        that.$form = that.$wrapper.find("form");
        that.$submitButton = that.$form.find("button[type=\"submit\"]");

        // VARS
        that.locales = options["locales"];

        // DYNAMIC VARS
        that.$notice = false;
        that.is_locked = false;
        that.is_form_changed = false;
        that.xhr = false;

        // INIT
        that.initClass();
    };

    SettingsPage.prototype.initClass = function() {
        var that = this;
        //
        that.bindEvents();
    };

    SettingsPage.prototype.bindEvents = function() {
        var that = this;

        that.$form.on("submit", function(event) {
            event.preventDefault();
            if (that.is_form_changed) {
                that.save( that.$form );
            }
        });

        that.$form.on("change", "input, select, textarea", setChanged);

        function setChanged() {
            if (!that.is_form_changed) {
                that.is_form_changed = true;
                that.$submitButton.removeClass("green").addClass("yellow");
            }
        }
    };

    SettingsPage.prototype.save = function( $form ) {
        var that = this,
            url = $.wadev.app_url + "?module=settings&action=save",
            data = $form.serializeArray();

        var $loading = $("<i class=\"icon16 loading\" style=\"margin: 0 4px;\"></i>");
        $loading.insertAfter( that.$submitButton );

        if (!that.is_locked) {
            that.is_locked = true;
            $.post(url, data, function(r) {
                that.is_form_changed = false;
                that.$submitButton.removeClass("yellow").addClass("green");

                if (r.status === 'ok') {

                }

            }).always( function() {
                $loading.remove();
                that.is_locked = false;
            });
        }
    };

    return SettingsPage;

})(jQuery);
