;(function($){
    
    "use strict";
    /*---------------------- Pricing table tab ---------------------------*/
    $(".dl_table_nav").on("click", "button", function () {
        var pos = $(this).index() + 2;
        $("tr").find('td:not(:eq(0))').hide();
        $('td:nth-child(' + pos + ')').css('display', 'table-cell');
        $("tr").find('th:not(:eq(0))').hide();
        $('button').removeClass('active');
        $(this).addClass('active');
    });


})(jQuery);
