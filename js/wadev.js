(function ($) {
    "use strict";

    $.wadev = {
        // как-то разрастается
        app_url: '',
        is_debug: '',
        sidebar: '',
        app_name: '',
        account_name: '',
        init: function (options) {
            'app_url|is_debug|sidebar|app_name|account_name'.split('|').forEach(function (o) {
                $.wadev[o] = options[o];
            });

            $.wadev.router = new WadevRouter({
                $content: $('#content')
            });

            this.highlightMenu();
            this.setTitle();
            this.handlers();
            this.events();
        },
        highlightMenu: function (menu, el) {
            var $menu = menu ? $(menu) : $($.wadev.sidebar),
                link = window.location.pathname + window.location.search,
                $lnks = $menu.find('a'),
                $lnk = $menu.find('a[href="' + link + '"]'),
                $el = el ? $(el) : $lnk.closest('li');

            // если не найдено по полному урлу или не передан элемент - найдем более-менее подходящий
            if (!$el.length) {
                $lnks.each(function () {
                    if (link.indexOf(this.href.replace(window.location.origin, '')) === 0) {
                        $el = $(this).closest('li');
                        return false;
                    }
                });
            }

            if ($el.length) {
                $menu.find('.selected').removeClass('selected');
                $el.addClass('selected');
            }
        },
        setTitle: function (title) {
            var h1 = $('.content-header h1');
            title = title || (h1.length ? h1.text() : '') || $.wadev.app_name;

            document.title = title + ' — ' + $.wadev.account_name;
        },
        handlers: function () {
        },
        events: function () {
            $('#wa-app')
                .on('highlight.wadev', function (e, menu, el) {
                    $.wadev.highlightMenu(menu, el);
                })
                .on('settitle.wadev', function (e, title) {
                    $.wadev.setTitle(title);
                });
        },
        hashCode: function (str) {
            var hash = 0, i, chr;
            if (str.length === 0) return hash;
            for (i = 0; i < str.length; i++) {
                chr = str.charCodeAt(i);
                hash = ((hash << 5) - hash) + chr;
                hash |= 0; // Convert to 32bit integer
            }
            return hash;
        },
        watchForChanges: function (options) {
            var o = {
                    $form: null,
                    onChange: function () {
                        $(this).find('[type="submit"]').removeClass('yellow red green').addClass('yellow');
                    },
                    onSame: function () {
                        $(this).find('[type="submit"]').removeClass('yellow red green').addClass('green');
                    },
                    prevent: true,
                    notSaved: true,
                    preventText: 'На странице есть несохраненные данные. Точно уйти с этой страницы?'
                },
                handlers_set = false;

            if (!options.$form) {
                return;
            }

            o = $.extend({}, o, options);
            var settings_values_hash = $.wadev.hashCode(o.$form.serialize());

            var prevent = function (e) {
                var $a = $(e.target),
                    in_form = $a.closest(o.$form);
                if (in_form && !in_form.length) {
                    if (o.notSaved && !confirm(o.preventText)) {
                        e.stopPropagation();
                        e.preventDefault();
                    } else {
                        _saved();
                    }
                }
            };

            o.$form.on('change click blur', ':input', function (e) {
                var new_hash = $.wadev.hashCode(o.$form.serialize());

                if (new_hash !== settings_values_hash) {
                    o.notSaved = true;
                    if (o.prevent && !handlers_set) {
                        $('#wa-app').find('a').on('click.wadev', prevent);
                        handlers_set = true;
                    }
                    $.isFunction(o.onChange) && o.onChange.call(o.$form.get(0));
                } else {
                    _saved();
                    settings_values_hash = new_hash;
                }
            });

            function _saved() {
                $('#wa-app').find('a').off('click.wadev', prevent);
                o.notSaved = false;
                handlers_set = false;
                $.isFunction(o.onSame) && o.onSame.call(o.$form.get(0));
            }

            return {
                saved: _saved
            };
        }
    };
})(jQuery);


var WadevRouter = (function ($) {
    "use strict";

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
                    $('#wa-app')
                        .trigger('highlight.wadev')
                        .trigger('settitle.wadev');
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

        that.animate(true);

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

            that.animate(false);

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
        } else {
            location.reload();
        }
    };

    WadevRouter.prototype.animate = function (show) {
        var that = this,
            $content = that.$content;

        $(".router-loading-indicator").remove();

        if (show) {
            var $header = $content.find(".content-header h1"),
                loading = '<i class="icon16 loading router-loading-indicator"></i>';

            if ($header.length) {
                $header.append(loading);
            }
        }
    };

    return WadevRouter;
})(jQuery);