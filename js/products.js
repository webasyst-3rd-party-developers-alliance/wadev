"use strict";
// Pages

var ProductsPage = ( function($) {
    ProductsPage = function(options) {
        var that = this;

        that.$wrapper = options["$wrapper"];
        that.$editLink = that.$wrapper.find(".wadev-products-editlink");
        
        that.initClass();
    };

    ProductsPage.prototype.initClass = function() {
        var that = this;
        that.bindEvents();
    };

    ProductsPage.prototype.bindEvents = function() {
        var that = this;
        that.$editLink.click(function(event){
            var product_id = event.currentTarget.attributes['product_id'].nodeValue;
            $.post('?module=product&action=edit',{'product_id':product_id}, function(html){
        	$(html).waDialog({
		    'height' : '300px',
		    'width' : '450px',
		    'onClose' : function(f) {
			$(this).remove;
		    },
		});        	
            });
        });
    };

    return ProductsPage;

})(jQuery);
