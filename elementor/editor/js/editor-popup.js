/**
* Name: Dl_Editor
* Desc: Droit Addons editor elementor popup
* Params: no params
* Return: @void
* version: 1.0.0
* Package: @droitedd
* Author: DroitThemes
* Developer: Hazi
*/
"use strict";
class Dl_Editor{

    constructor(){

    }

    loading($target, $parent) {
        let $body = $parent.jQuery(".dlpopup-main");
        if ( $body.length ) {
            $body.attr('style', 'display:block;');
            let $modalOpen = $parent.jQuery("div.dlopen-editor");
            if( !$modalOpen.length){
                $body.addClass('dialog-type-lightbox').addClass('dlopen-editor');
            }
            let $iframe = $body.find('iframe');

            setTimeout(function(){
                let $parenId = Dl_Editor.instance().getParams('elementor-preview');
                // ajax call request
                jQuery.ajax({
                    url: dlproeditor.ajax_url+'?action=dtaddons_editor',
                    type: "post",
                    data: {
                       template_id: $target.data('templateid'),
                       widgets_id: $target.data('widgetsid'),
                       repeater_id: $target.data('repeaterid'),
                       parent_id: Number($parenId),
                       post_id: Number($target.data('postid')),
                    },
                    // success ajax 
                    success: function(res){
                        if(res.data !== 0){
                            $iframe.attr('src', dlproeditor.posturl + '?post='+res.data+'&action=elementor');  
                            $target.attr('data-postid', res.data);
                        } else {
                            $body.attr('style', 'display:none;');
                            $body.removeClass('dialog-type-lightbox').removeClass('dlopen-editor');
                            $iframe.removeAttr('src');
                            $body.attr('data-render-status', 'false');
                        }
                    },
                    error: function(res){
                       console.log('Sorry!! DL Editor couldn\'t open right now');
                    }               
                });
            }, 100);

            // close button
            let $close = $body.find('i.eicon-close');
            if($close.length){
                $close.click(function(){
                    $body.attr('style', 'display:none;');
                    $body.removeClass('dialog-type-lightbox').removeClass('dlopen-editor');
                    $iframe.removeAttr('src');
                    $body.attr('data-render-status', 'true');
                });
            }
        }
    }

    getParams(params) {
        var result = null,
            tmp = [];
        location.search
            .substr(1)
            .split("&")
            .forEach(function (item) {
              tmp = item.split("=");
              if (tmp[0] === params) result = decodeURIComponent(tmp[1]);
            });
        return result;
    }

    setCookie( cname, cvalue, exdays ){
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    static instance() {
        return new Dl_Editor();
    }
}

/**
* Name: load frontend editor
* Desc: loading editor of Elementor Builder
* Params: no params
* Return: @void
* version: 1.0.0
* Package: @droitedd
* Author: DroitThemes
* Developer: Hazi
*/
// load builder data
!(function ($, frontend) {
        
    if (void 0 === window.parent) return;
    let nparent = window.parent;
    let $ids = '';
    if (window.elementorFrontend) {
        // loading editor content builder
        var editor_loading = function(){
            frontend.hooks.addAction("frontend/element_ready/global", function ($self) {
                var $element = $self.find( '.dl-editor-icon' );
                if ( $element.length ) {                 
                    $element.on('click', function( ev ){
                        ev.preventDefault();
                        let $body = nparent.jQuery(".dlpopup-main");
                        if ( $body.length ) {
                            $body.attr('data-render-status', 'false');
                        }
                        let $this = $(this);
                        let $id = $this.data('templateid');
                        
                        let $modalOpen = nparent.jQuery("div.dlopen-editor");
                        if($id != $ids || !$modalOpen.length){
                            new Dl_Editor().loading($this, nparent);
                            $ids = $id;
                        }                        
                    });
                }
            });
        }
        $(window).on("elementor/frontend/init", editor_loading);
    }

})(jQuery, window.elementorFrontend);

 



 


