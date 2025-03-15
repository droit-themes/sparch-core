

(function ($, window) {
    "use strict";

    var $window = $(window);
    var drthWidgets = {
        LoadWidgets: function () {
            var e_front = elementorFrontend,
                e_module = elementorModules;
            var widgetsMap = {
                "drth-test.default": drthWidgets.drth_test,
                "droit-countdowns.default": drthWidgets._dl_pro_count_down,
                "droit-testimonials-theme.default": drthWidgets._dl_pro_swiperSlider,
                "droit-teams.default": drthWidgets._dl_pro_team,
                "droit-advance-accordions.default": drthWidgets._dl_pro_accordions,
                "droit-tabs.default": drthWidgets._dl_pro_tabs,
                "droit-image_compare.default": drthWidgets._dl_pro_compare,
                "droit-fun_fact.default": drthWidgets._dl_pro_counterup,
                "droit-portfolio-theme.default": drthWidgets._dl_pro_grid_portfolio,
                "droit-adavnced-pricing.default": drthWidgets._dl_pro_advance_pricing,
                "droit-adavnced-slider.default": drthWidgets._dl_pro_advance_slider,
                "droit-slider-theme.default": drthWidgets._dl_sp_swiper_slider,
                "droit-gallery-theme.default": drthWidgets._dl_pro_project_box,
            };
            $.each(widgetsMap, function (name, callback) {
                e_front.hooks.addAction("frontend/element_ready/" + name, callback);
            });
        },
        // test
        drth_test: function($scope){
            //alert();
        },
         _dl_pro_count_down: function ($scope, t) {
            var $selector = $scope.find(".droit-countdown-wrapper-pro"),
                $countdown_id = void 0 !== $selector.data("countdown-id") ? $selector.data("countdown-id") : "",
                $end_type = void 0 !== $selector.data("end-type") ? $selector.data("end-type") : "",
                $end_title = void 0 !== $selector.data("end-title") ? $selector.data("end-title") : "",
                $end_text = void 0 !== $selector.data("end-text") ? $selector.data("end-text") : "",
                $redirect_url = void 0 !== $selector.data("redirect-url") ? $selector.data("redirect-url") : "",
                $item = $scope.find("#droit-countdown-pro-" + $countdown_id);
            $item.countdown({
                end: function () {
                    if ("text" == $end_type) $item.html('<div class="droit-countdown-action-message"><h4 class="droit-countdown-end-title">' + $end_title + '</h4><div class="droit-countdown-end-text">' + $end_text + "</div></div>");
                    else if ("url" === $end_type) {
                        _dl_pro_count_down_redirect($redirect_url);
                    }
                },
            });
        },
        _dl_pro_swiperSlider: function ($scope) {
            var $swiperContainer = $scope.find(".swiper-container"),
                controls = null,
                autoplay = true,
                slider_speed = 500,
                slider_loop = true,
                slider_space = 50,
                slider_item = 1,
                slider_drag = true,
                slider_next = ".slider_next_one",
                slider_prev = ".slider_prev_one",
                slider_paginationtype = "bullets",
                slider_pagination = ".img_carousel_pagination",
                slider_effect = "",
                slider_center = false,
                slider_breakpoints = "";
            if ($swiperContainer.attr("data-controls")) {
                var controls = JSON.parse($swiperContainer.attr("data-controls"));
                autoplay = controls.slide_autoplay == "yes" ? true : false;
                slider_speed = controls.slider_speed;
                slider_loop = controls.slider_loop == "yes" ? true : false;
                slider_space = controls.slider_space;
                slider_item = controls.slider_item;
                slider_next = controls.slider_next;
                slider_prev = controls.slider_prev;
                (slider_paginationtype = controls.slider_paginationtype), (slider_pagination = controls.slider_pagination), (slider_center = controls.slider_center == "yes" ? true : false);
                slider_drag = controls.slider_drag == "yes" ? true : false;
                slider_breakpoints = controls.slider_breakpoints;
            }
            new Swiper($swiperContainer, {
                slidesPerView: slider_item,
                spaceBetween: slider_space,
                loop: slider_loop,
                speed: slider_speed,
                simulateTouch: slider_drag,
                effect: slider_effect,
                breakpoints: slider_breakpoints,
                centeredSlides: slider_center,
                autoplay: autoplay,
                disableOnInteraction: autoplay,
                pagination: { el: slider_pagination, type: slider_paginationtype, clickable: !0 },
                navigation: { nextEl: slider_next, prevEl: slider_prev },
            });
            $($swiperContainer).hover(
                function () {
                    this.swiper.autoplay.stop();
                },
                function () {
                    if (autoplay == true) {
                        this.swiper.autoplay.start();
                    }
                }
            );
        },
        _dl_pro_team: function ($scope, t) {
            var $selector = $scope.find(".mouse_move_animation");
            if ($($selector).length > 0) {
                $($selector).parallax({ scalarX: 10.0, scalarY: 8.0 });
            }
        },
        _dl_pro_accordions: function ($scope, t) {
            var t = $scope.find(".droit-accordion-wrap-pro"),
                h = $scope.find(".dl_accordion_item_title"),
                d = $scope.find(".dl-pro-active-default"),
                r = $scope.data("type"),
                s = $scope.data("speed");
            h.each(function () {
                $(this).hasClass("dl-pro-active-default") && ($(this).addClass("dl-pro-open dl-pro-active"), $(this).next().slideDown(s));
            }),
                h.click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    $this.hasClass("dl-pro-open")
                        ? ($this.removeClass("dl-pro-open dl-pro-active"), $this.next().slideUp(s))
                        : ($this.parent().parent().find(h).removeClass("dl-pro-open dl-pro-active"),
                            $this.parent().parent().find(".dl_accordion_panel").slideUp(s),
                            $this.toggleClass("dl-pro-open dl-pro-active"),
                            $this.next().slideToggle(s));
                });
            h.each(function () {
                if ($(this).hasClass("dl-pro-active-default") && $(this).addClass("dl-pro-open dl-pro-active")) {
                    var image = $(this).attr("data-src"),
                        alt = $(this).attr("data-alt");
                    $(".tab_img img").attr("src", image);
                    $(".tab_img img").attr("alt", alt);
                }
            }),
                $(h).on("click", function () {
                    var image = $(this).attr("data-src"),
                        alt = $(this).attr("data-alt");
                    $(".tab_img img").attr("src", image);
                    $(".tab_img img").attr("alt", alt);
                });
        },
        _dl_pro_tabs: function ($scope, t, e) {
                var a = "#" + $scope.find(".dl_tab_container").attr("id").toString();
                t(a + " ul.dl_tab_menu > li").each(function (e) {
                    t(this).hasClass("active-default")
                        ? (t(a + " ul.dl_tab_menu > li")
                            .removeClass("dl_active")
                            .addClass("dl_inactive"),
                            t(this).removeClass("dl_inactive"))
                        : 0 == e && t(this).removeClass("dl_inactive").addClass("dl_active");
                }),
                    t(a + " .tab_container div").each(function (e) {
                        t(this).hasClass("active-default") ? t(a + " .tab_container > div").removeClass("dl_active") : 0 == e && t(this).removeClass("dl_inactive").addClass("dl_active");
                    }),
                    t(a + " ul.dl_tab_menu > li").click(function () {
                        var e = t(this).index(),
                            a = t(this).closest(".droit-advance-tabs"),
                            n = t(a).children(".droit-tabs-nav").children("ul").children("li"),
                            i = t(a).children(".tab_container").children("div");
                        t(this).parent("li").addClass("dl_active"),
                            t(n).removeClass("dl_active active-default").addClass("dl_inactive"),
                            t(this).addClass("dl_active").removeClass("dl_inactive"),
                            t(i).removeClass("dl_active").addClass("dl_inactive"),
                            t(i).eq(e).addClass("dl_active").removeClass("dl_inactive");
                        t(i).each(function (e) {
                            t(this).removeClass("active-default");
                        });
                    });
        },
        _dl_pro_compare: function ($scope) {
            var $selector = $scope.find(".dl_image_swipe"),
                controls = null,
                before_label = 'Before',
                after_label = 'After',
                orientation_style = '',
                overlay = false,
                move = true,
                offset = 0.5,
                _icon_type = '',
                left_icon = 'fas fa-chevron-left',
                right_icon = 'fas fa-chevron-right';
            if ($selector.attr("data-controls")) {
                var controls = JSON.parse($selector.attr("data-controls"));
                before_label = controls.before;
                after_label = controls.after;
                move = controls.move == "yes" ? true : false;
                overlay = move == "yes" ? false : (controls.overlay == "yes" ? true : false);
                orientation_style = controls.orientation;
                offset = controls.offset;
                _icon_type = controls.icon_type;
                left_icon = controls.left_icon;
                right_icon = controls.right_icon;
            }
            var $obj = {};
            $obj.before_label = before_label;
            $obj.after_label = after_label;
            $obj.orientation = orientation_style;
            $obj.no_overlay = overlay;
            $obj.move_slider_on_hover = move;
            $obj.default_offset_pct = offset;
            $obj._icon_type = _icon_type;
            $obj.left_icon = left_icon;
            $obj.right_icon = right_icon;
            
            if ($($selector).length) {
                $($selector).imagesLoaded( function() {
                $($selector).twentytwenty($obj);
                });
            }
            $( document ).ready(function() {
                var itemW = $('.twentytwenty-before').width();
                $('.dl_product_compaire').css('width', itemW);
            });


        },
        _dl_pro_counterup: function ($scope) {

            var $selector = $scope.find(".dl-fun-fact-wrapper"),
                $selector_id = "#" + $selector.attr("id").toString(),
                controls = null,
                delay = 10,
                timer = 1000;
            if ($selector.attr("data-controls")) {
                var controls = JSON.parse($selector.attr("data-controls"));
                delay = controls.delay;
                timer = controls.timer;
            }
            if ($selector_id.length > 0) {
                $($selector_id + ' .fun-counter').counterUp({
                    delay: delay,
                    time: timer
                });
            }
        },
        _dl_pro_advance_pricing: function ($scope) {

            var t = $scope.find(".dl_switcher_control-item"),
                h = $scope.find(".dl_switcher_content-item");
            // tab style
            t.click(function (e) {
                e.preventDefault();
                var $this = $(this);
            
                // close all switcher active class
                t.each(function () {
                    let $self = $(this);
                    $self.removeClass('on-select');
                });

                // close all content tab
                h.each(function () {
                    let $self = $(this);
                    $self.removeClass('on-active');
                });

                // selector content
                let $target = $this.data('target');
                let $content = $scope.find("[data-toggle=" + $target + "]");
                $content.addClass('on-active');
                // selected tab
                $this.addClass('on-select');

            });

            // switch style
            var s = $scope.find(".dl_toggle");
            s.click(function (e) {
                e.preventDefault();
                var $this = $(this);
                $this.toggleClass('dl-active');
                $this.parents('.dl_switcher_control').toggleClass('dl-active');

                // content
                h.each(function () {
                    let $self = $(this);
                    $self.toggleClass('on-active');
                });
            });
        },
        _dl_pro_advance_slider: function ($scope) {
            var $slider = $scope.find(".dl_advance_slider"),
                $item = $scope.find(".swiper-slide"),
                $dat = $slider.data('settings');

            // render slider
            new Swiper($slider, $dat);

            // auto slider
            if ($dat.dl_mouseover) {
                $slider.hover(function () {
                    (this).swiper.autoplay.stop();
                }, function () {
                    (this).swiper.autoplay.start();
                });
            }

            if( $dat.dl_autoplay ){
                $slider.each(function () {
                    (this).swiper.autoplay.start();
                });
            } else{
                $slider.each(function () {
                    (this).swiper.autoplay.stop();
                });
            }

            let $delay = $dat.speed;
            if( $dat.autoplay ){
                $delay = $dat.autoplay.delay;
            }
            /*
            // advance slider settings
            let $animation = $slider.find('[data-settings]');
            let $settings = $animation.data('settings');
        //console.log($settings);
            if ( $settings != 'undefined' && $settings._animation_delay ) {
                let $checkDaly = $settings._animation_delay;
                if ($checkDaly < $delay) {
                    $settings._animation_delay = $delay;
                    $animation.attr('data-settings', JSON.stringify($settings));
                }
            }
            */
        },
        _video_popup: function($scope) {
            var $selector = $scope.find(".video_popup_area"),
            $selector_id = "#" + $selector.attr("id").toString();
            $($selector_id).each(function() {
                $(this).on("click", function(){
                var $video_popup =   "#" + $($selector_id + ' .video_popup').attr("id").toString();
                $($video_popup).addClass("popup-visible");
                    var videoSrc = $(this).attr("data-url"),
                        autoplay = $(this).attr("data-autoplay"),
                        mute = $(this).attr("data-mute"),
                        loop = $(this).attr("data-loop"),
                        controls = $(this).attr("data-controls"),
                        modestbranding = $(this).attr("data-modestbranding"),
                        start = $(this).attr("data-start"),
                        end = $(this).attr("data-end"),
                        playsinline = $(this).attr("data-playsinline"),
                        color = $(this).attr("data-color"),
                        title = $(this).attr("data-title"),
                        byline = $(this).attr("data-byline"),
                        logo = $(this).attr("data-logo"),
                        showinfo = $(this).attr("data-showinfo"),
                        portrait = $(this).attr("data-portrait"),
                        layout = $(this).attr("data-layout");
                        if(layout === 'youtube'){
                            $($video_popup + ' .dt_video_iframe').attr("src", videoSrc + "?autoplay="+autoplay+"&rel=0&controls="+controls+"&loop="+loop+"&modestbranding="+modestbranding+"&mute="+mute+"&start="+start+"&end="+end+"&playsinline="+playsinline+"&showinfo=1");
                        }else if(layout === 'vimeo'){
                            $($video_popup + ' .dt_video_iframe').attr("src", videoSrc + "?html5=1&autoplay="+autoplay+"&playsinline="+playsinline+"&title="+title+"&byline="+byline+"&portrait="+portrait+"&loop="+loop+"&muted="+mute+"&color="+color+"&t="+start+"s");
                        }else if(layout === 'dailymotion'){
                            $($video_popup + ' .dt_video_iframe').attr("src", videoSrc + "?autoplay="+autoplay+"&playsinline="+playsinline+"&ui-highlight="+color+"&start="+start+"&endscreen-enable=0&controls="+controls+"&mute="+mute+"&ui-start-screen-info="+showinfo+"&ui-logo="+ logo);
                        }else if(layout === 'hosted'){
                            $($video_popup + ' .dt_video_iframe').attr("src", videoSrc);
                        }
                    $(document).on("click", ".modal-close", function () {
                        $($video_popup + ' .dt_video_iframe').attr("src", '');
                        $($video_popup + '.video_popup').removeClass("popup-visible");
                    });
                });
            });
        },

        //maasonry js
        _dl_pro_grid_portfolio: function ($scope) {
            var $id = $scope.data('id');
           
            var $selector = $scope.find('#portfolio_area_'+$id);

            if ($selector.length) {

                var $el = $selector.find('.dl_addons_grid_wrapper');
                if( $el.length > 0){
                    $($el).each(function () {
                        $(this).dlAddonsGridLayout();
                    });
                }
               
                var $grid = $selector.find('.dl_addons_grid');
                
                $scope.find('.dl_sp_masonry_filter .dl_sp_masonry_filter_item').on('click', function () {
                    $('.dl_sp_masonry_filter_item').removeClass('current');
                    $(this).addClass('current');
    
                    var selector = $(this).attr("data-filter");
                    $grid.isotope({
                        filter: selector
                    });
    
                    return false;
                });
                
            }
        },
        _dl_pro_project_box: function ($scope) {
            var $dl_project_box = $scope.find('.dl_project_box');
            if ($dl_project_box.length) {
                $('.dl_project_box').on('mouseover', function () {
                    var index = $('.dl_project_box').index(this);
                    $('.dl_bg_changer .dl_section_bg').removeClass('dl_active').eq(index).addClass('dl_active');
                });
            }
        },


        
        _dl_sp_swiper_slider: function ($scope) {
        var $dl_sp_swiper_slider = $scope.find('.dl_sp_project_slider_wrapper');
        if ($dl_sp_swiper_slider.length > 0) {
           
            var t = $dl_sp_swiper_slider,
            i = (t.attr("id"), t.data("perpage") || 1),
            a = t.data("loop"),
            e = t.data("speed") || 1000,
            o = t.data("space") || 0,
            l = t.data("effect"),
            c = t.data("center"),
            pl = t.data("autoplay"),
            nex = t.data("next"),
            pre = t.data("prev"),
            pag = t.data("pagination"),
            pagtype = t.data("paginationtype"),
            d = t.data("direction") || "horizontal",
            r = t.data("breakpoints");
            
            new Swiper(t, {
                slidesPerView: i,
                direction: d,
                spaceBetween: o,
                loop: a,
                speed: e,
                effect: l,
                breakpoints: r,
                centeredSlides: c,
                // autoplay: {
                //     delay: 3000,
                //     disableOnInteraction: !1
                // },
                autoplay: pl,
                pagination: {
                    el: pag,
                    type: pagtype,
                    clickable: !0
                },
                navigation: {
                    nextEl: nex,
                    prevEl: pre
                }
            });
        }
    },

        
         
    };

   function _dl_pro_count_down_redirect(url) {
        window.location.replace(url);
    }

    // load elementor
    $window.on("elementor/frontend/init", drthWidgets.LoadWidgets);
})(jQuery, window);
