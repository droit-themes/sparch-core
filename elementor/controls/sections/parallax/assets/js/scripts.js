!(function (e, t) {
    "use strict";
    var a = {
        init: function () {
            t.hooks.addAction("frontend/element_ready/section", a.elementorSection);
        },
        elementorSection: function (e) {
            var a = e,
                n = null;
            Boolean(t.isEditMode());
            (n = new i(a)).init(n);
        },
    };
    e(window).on("elementor/frontend/init", a.init);
    var i = function (a) {
        var i = this,
            n = a.data("id"),
            r = Boolean(t.isEditMode()),
            l = e(window);
        e("body"), l.scrollTop(), l.height(), navigator.userAgent.match(/Version\/[\d\.]+.*Safari/), navigator.platform;
        (i.init = function () {
            return !(e(window).width() <= 1024) && (i.setParallaxMulti(n), !1);
        }),
            (i.setParallaxMulti = function (t) {
                var n,
                    l = {},
                    o = [];
                if ( ((l = i.getOptions(t, "droit_section_parallax_multi_items")), "multilayer" == (n = i.getOptions(t, "droit_section_parallax_multi")))) {
                    if (r) {
                        if (!l.hasOwnProperty("models") || 0 === Object.keys(l.models).length || "multilayer" != n) return;
                        l = l.models;
                    }
                    if (
                        (a.addClass("dladdons-parallax-multi-container"),
                        e.each(l, function (e, t) {
                            t.hasOwnProperty("attributes") && (t = t.attributes), o.push(t), i.pushElement(t), i.getWOW(t), i.mousemoveScroll(t), i.allowDevices(t);
                        }),
                        o.length < 0)
                    )
                        return o;
                    a.on("mousemove", function (t) {
                        e.each(o, function (e, a) {
                            "mousemove" == a.parallax_style && i.moveItem(a, t);
                        });
                    }),
                        e.each(o, function (e, t) {
                            "tilt" == t.parallax_style && i.tiltItem(t), "onscroll" == t.parallax_style && i.walkItem(t);
                        });
                }
            }),
            (i.walkItem = function (e) {
                void 0 !== e.parallax_transform &&
                    void 0 !== e.parallax_transform_value &&
                    a
                        .find(".elementor-repeater-item-" + e._id)
                        .magician({ type: "scroll", offsetTop: parseInt(e.offsettop), offsetBottom: parseInt(e.offsetbottom), duration: parseInt(e.smoothness), animation: { [e.parallax_transform]: e.parallax_transform_value } });
            }),
            (i.moveItem = function (e, t) {
                var i = t.pageX - a.offset().left,
                    n = t.pageY - a.offset().top,
                    cl = a.find(".elementor-repeater-item-" + e._id);
                TweenMax.to(cl, 0.2, { x: ((i - a.width() / 2) / a.width()) * e.parallax_speed, y: ((n - a.height() / 2) / a.height()) * e.parallax_speed, ease: Power2.ease });
            }),
            (i.tiltItem = function (e) {
                var t = a.find(".elementor-repeater-item-" + e._id);
                t.find("img");
                t.tilt({ disableAxis: e.disableaxis, scale: e.scale, speed: e.parallax_speed, maxTilt: e.maxtilt, glare: !0, maxGlare: 0.5 });
            }),
            (i.getOptions = function (t, a) {
                var i = null,
                    n = {};
                if (r) {
                    if (!window.elementor.hasOwnProperty("elements")) return !1;
                    if (!(i = window.elementor.elements).models) return !1;
                    if (
                        (e.each(i.models, function (e, a) {
                            t == a.id && (n = a.attributes.settings.attributes);
                        }),
                        !n.hasOwnProperty(a))
                    )
                        return !1;
                } else {
                    if (void 0 === (n = e((t = ".elementor-element-" + t)).data("settings"))) return;
                    if (!n.hasOwnProperty(a)) return !1;
                }
                return n[a];
            }),
            (i.pushElement = function (e) {
                var  i = "",
                    wow_class = "",
                    wowAnim = "",
                    wowDelay = "";
                    if('enable' === e.wow_enable){
                        wow_class = " wow ";
                        wowAnim = " " + e.wow_animation + " ";
                        wowDelay = e.wow_delay;
                    }
                    var cl = wow_class + wowAnim + "droit-section-parallax-mousemove droit-section-parallax-layer elementor-repeater-item-" + e._id;
                    0 === a.find(".elementor-repeater-item-" + e._id).length &&
                        "" != e.image.url &&
                        a.prepend(
                            `\n <div data-wow-delay="${wowDelay}ms" class="${cl} droit-section-parallax-type-${e.parallax_style}" >\n <img class="dladdons-parallax-graphic ${i}" src="${e.image.url}"/>\n </div>\n                    `
                        );
            },i.getWOW = function(e) {
                void 0 !== e._wow_animation && a.find(".elementor-repeater-item-" + e._id).each(function() {
                 var wow_mobile = 'yes' === e.wow_mobile ? true : false;
                 var wow = new WOW({
                        offset: 100,
                        mobile: wow_mobile,
                        animateClass: 'animated',
                        live: true 
                    });
                    wow.init();
                });
            },i.mousemoveScroll = function(e) {
                'yes' == e._mousemove_scroll_enable && void 0 !== e._mousemove_parallax_transform && void 0 !== e._mousemove_parallax_transform_value && a.find(".elementor-repeater-item-" + e._id + ' .dladdons-parallax-graphic' ).magician({
                    type: "scroll",
                    offsetTop: parseInt(e._mousemove_offsettop),
                    offsetBottom: parseInt(e._mousemove_offsetbottom),
                    duration: parseInt(e._mousemove_smoothness),
                    animation: {
                        [e._mousemove_parallax_transform]: e._mousemove_parallax_transform_value
                    }
                });
                
            },i.allowDevices = function(e) {
                var devices = elementorFrontend.getCurrentDeviceMode();
                var $class = a.find(".elementor-repeater-item-" + e._id);
                if('mobile' === e.parallax_on_mobile){
                    var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
                    if (mobile) {
                        $class.css('display', 'none');
                    }
                }
            });
    };
})(jQuery, window.elementorFrontend);
