(function ($) {
    $.wadev = {
        app_url: '',
        is_debug: '',
        init: function (options) {
            'app_url|is_debug'.split('|').forEach(function (o) {
                $.wadev[o] = options[o];
            });

            $.wadev.router = new WadevRouter({
                $content: $('#content')
            });
        }
    };
})(jQuery);


var WadevRouter = (function ($) {

    WadevRouter = function (options) {
        var that = this;

        that.$window = $(window);
        that.$content = options["$content"];

        that.api_history = ( window.history && window.history.pushState );

        that.xhr = false;
        that.is_enabled = true;

        that.prevent = false;

        that.except = '.do-not-user-router-here';

        that.bindEvents();
    };

    WadevRouter.prototype.preventLoad = function (prevent) {
        var that = this;

        that.prevent = !!prevent;
    };

    WadevRouter.prototype.bindEvents = function () {
        var that = this,
            full_app_url = window.location.origin + $.wadev.app_url;

        $('#wa-app').on('click', 'a', function (event) {
            var $link = $(this);
            if (that.prevent) {
                return false;
            }
            if (event.ctrlKey) {
                return true;
            }
            var $except = $link.closest(that.except);
            if ($except.length) {
                return true;
            }
            var use_content_router = ( that.is_enabled && ( this.href.substr(0, full_app_url.length) == full_app_url ) );
            if (use_content_router) {
                event.preventDefault();
                that.load(this).done(function () {
                    var $menu = $link.closest('[data-wadev-menu]');
                    if ($menu.length) {
                        $menu.find('li').removeClass('selected');
                        $link.closest('li').addClass('selected');
                    }
                });
            }
        });

        if (that.api_history) {
            window.onpopstate = function (event) {
                event.stopPropagation();
                that.onPopState(event);
            };
        }
    };

    WadevRouter.prototype.url = function (url, full) {
        var that = this,
            full_app_url = window.location.origin + $.wadev.app_url;

        full = !!full;
        window.location = (full ? '' : full_app_url) + url;
    };

    WadevRouter.prototype.load = function (link, is_reload) {
        var that = this,
            content_uri = link;

        if (link !== null && typeof link === 'object') {
            var $link = $(link),
                content_target = $link.data('wadev-content-target') || false;

            content_uri = link.href;
        }
        var uri_has_app_url = ( content_uri.indexOf($.wadev.app_url) >= 0 );
        if (!uri_has_app_url) {
            content_uri = $.wadev.app_url + content_uri;
            // TODO:
            // alert("Determine the path error");
            // return false;
        }

        // that.animate( true );

        if (that.xhr) {
            that.xhr.abort();
        }

        that.xhr = $.get(content_uri, function (html) {
            if (!is_reload && that.api_history) {
                history.pushState({
                    reload: true,               // force reload history state
                    content_uri: content_uri    // url, string
                    // content: html,              // ajax html, string
                }, "", content_uri);
            }
            that.except = false;
            that.setContent(html, content_target);

            // that.animate( false );

            that.xhr = false;
        });

        return that.xhr;
    };

    WadevRouter.prototype.reload = function () {
        var that = this,
            content_uri = (that.api_history && history.state && history.state.content_uri) ? history.state.content_uri : false;

        if (content_uri) {
            that.load(content_uri, true);
        }
    };

    WadevRouter.prototype.setContent = function (html, target) {
        var that = this,
            $target = target ? $(target) : that.$content;

        $target.html(html);
    };

    WadevRouter.prototype.onPopState = function (event) {
        var that = this,
            state = ( event.state || false );

        if (state) {
            if (!state.content_uri) {
                // TODO:
                alert("Determine the path error");
                return false;
            }

            if (state.reload) {
                that.reload(state.content_uri);
            } else if (state.content) {
                that.setContent(state.content);
            }
            //
            // // TITLE
            // if (state.title) {
            //     $.team.setTitle(state.title);
            // }

            // SIDEBAR
            // $.team.sidebar.selectLink( state.content_uri );
        } else {
            location.reload();
        }
    };

    WadevRouter.prototype.animate = function (show) {
        var that = this,
            $content = that.$content;

        $(".router-loading-indicator").remove();

        if (show) {
            var $header = $content.find(".t-content-header h1"),
                loading = '<i class="icon16 loading router-loading-indicator"></i>';

            if ($header.length) {
                $header.append(loading);
            }
        }
    };

    return WadevRouter;
})(jQuery);