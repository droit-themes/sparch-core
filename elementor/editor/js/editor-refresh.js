/**
* Name: elementor:init
* Desc: Elementor Content data refreshment
* Params: no params
* Return: @void
* version: 1.0.0
* Package: @droitedd
* Author: DroitThemes
* Developer: Hazi
*/
jQuery(window).on("elementor:init", function () {
    "use strict";

    var ele = elementor.modules.controls.BaseData;
    var dleditor_hidden = ele.extend(
        {
            onRender: function () {
                ele.prototype.onRender.apply(this, arguments);
               
                var tem = this;
                
                clearInterval(window.dlrefresh),
                (window.dlrefresh = setInterval(function () {
                    if (void 0 === window.parent) return;
                    let nparent = window.parent;
                    let $body = nparent.jQuery(".dlpopup-main");
                    var eltrue = $body.attr("data-render-status");
                    if ("true" == eltrue && 1 == tem.isRendered) {
                        var vl = new Date().getTime();
                        $body.attr("data-render-status", "false");
                        tem.setValue(vl);
                    }
                    
                }, 100));

            }
        },
        {}
    );

    elementor.addControlView("dleditor", dleditor_hidden);
});
