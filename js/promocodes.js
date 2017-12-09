"use strict";

var PromocodePage = (function ($) {

    PromocodePage = function (options) {
        var that = this;

        that.$wrapper = options["$wrapper"];
        // that.$form = $('#wadev-license-form');
        // that.$searchButton = that.$form.find("button[type=\"submit\"]");
        // that.$result_block = $('.result.block', that.$wrapper);

        // VARS
        that.locales = options["locales"];

        // DYNAMIC VARS
        that.$notice = false;
        that.is_locked = false;
        that.xhr = false;

        // INIT
        that.initClass();
    };

    PromocodePage.prototype.initClass = function () {
        var that = this;
        //
        that.bindEvents();
    };

    PromocodePage.prototype.bindEvents = function () {
        var that = this;
    };

    return PromocodePage;

})(jQuery);
