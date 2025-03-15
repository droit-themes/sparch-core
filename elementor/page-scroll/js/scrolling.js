(function ($, window) {
    "use strict";
    var $window = $(window);
    var dl_section_scrolling = function(){
        var $onpage = document.querySelector("[data-onpage-scroll]");
        if($onpage){
            var $settings = $onpage.getAttribute("data-onpage-scroll");
            var $parentEle = $onpage.parentElement;
            if($parentEle){
                $parentEle.setAttribute('id', 'dl-onpage-section');
            }

            let $menuAppend = $parentEle.parentElement;
            
            // pre button for control
            let $previewButton = $onpage.getAttribute("data-onpage-preview");
            if( $previewButton != ''){
                let $pre = document.createElement("span");
                $pre.setAttribute('class', 'dlone-scroll-control dl-prev');
                $pre.setAttribute('id', 'dlone-moveUp');
                $pre.innerHTML = $previewButton;
                $menuAppend.appendChild($pre);
            }
            let $nextButton = $onpage.getAttribute("data-onpage-next");
            if( $nextButton != ''){
                let $next = document.createElement("span");
                $next.setAttribute('class', 'dlone-scroll-control dl-next');
                $next.setAttribute('id', 'dlone-moveDown');
                $next.innerHTML = $nextButton;
                $menuAppend.appendChild($next);
            }
          
            if( $onpage.getAttribute("data-dlmenus") ){

                let $menus = $onpage.getAttribute("data-dlmenus");
                $menus = JSON.parse($menus);

                var $meniParent = document.createElement('div');
                $meniParent.setAttribute('class', 'dlonpage-scroll-menu');

                var $ul = document.createElement('ul');
                $ul.setAttribute('id', 'dlonpage-menu');

                var $listanchors = [];
                $menus.forEach(function($v, $k){
                    let $li = document.createElement('li');
                    $li.setAttribute('data-menuanchor', $v.dlmenu_id);

                    let $a = document.createElement('a');
                    $a.setAttribute('href', '#'+$v.dlmenu_id);
                    if($v.dlmenu_icon.value && $v.dlmenu_icon.value.length > 0){
                        $a.innerHTML = '<i class="'+$v.dlmenu_icon.value+'"></i>';
                    }
                    $a.innerHTML += $v.dlmenu_title;
                    
                    $li.appendChild($a);

                    $ul.appendChild($li);
                    $listanchors.push($v.dlmenu_id);
                });
                $meniParent.appendChild($ul);
                $menuAppend.appendChild($meniParent);
            }

            if ($('#dl-onpage-section').length > 0) {

                // setup fullpage settings
                $('#dl-onpage-section').fullpage(JSON.parse($settings));

                $.fn.fullpage.setAllowScrolling(true);

                if ($('#dlone-moveDown').length > 0) {
                    $('#dlone-moveDown').click(function () {
                        $.fn.fullpage.moveSectionDown();
                    });
                }
                if ($('#dlone-moveUp').length > 0) {
                    $('#dlone-moveUp').click(function () {
                        $.fn.fullpage.moveSectionUp();
                    });
                }
                
            }
        }
      
    }
    // load elementor
    $window.on("elementor/frontend/init", dl_section_scrolling);

})(jQuery, window);


jQuery( function( $ ) {
    "use strict";
    if ( typeof elementor != "undefined" && typeof elementor.settings.page != "undefined") {
        var dl_onepage_enable_function = function ( newValue ) {
            elementor.reloadPreview();
            /*elementor.saver.update( {
                onSuccess: function() {
                    elementor.reloadPreview();
                    elementor.once( 'preview:loaded', function() {
                        elementor.getPanelView().setPage( 'page_settings' );
                    } );
                }
            } );*/
        }
        elementor.settings.page.addChangeCallback( 'dl_onepage_enable', dl_onepage_enable_function);
    }

});