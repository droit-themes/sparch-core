;(function($){
    
    "use strict";
   /*----------------------------------------------------*/
    /*  slider js
    /*----------------------------------------------------*/
    $(".swiper-container").each(function () {
        var t = $(this),
            i = ($(this).attr("id"), $(this).data("perpage") || 1),
            a = $(this).data("loop"),
            e = $(this).data("speed") || 1000,
            o = $(this).data("space") || 0,
            l = $(this).data("effect"),
            c = $(this).data("center"),
            pl = $(this).data("autoplay"),
            nex = $(this).data("next"),
            pre = $(this).data("prev"),
            pag = $(this).data("pagination"),
            pagtype = $(this).data("paginationtype"),
            d = $(this).data("direction") || "horizontal",
            r = $(this).data("breakpoints");
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
        })
    })


})(jQuery);
