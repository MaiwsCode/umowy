(function ($) {

    "use strict";
        var arrowUp = "url(data/Base_Theme/templates/default/Utils/GenericBrowser/sort-ascending.png)";
        var arrowDown =  "url(data/Base_Theme/templates/default/Utils/GenericBrowser/sort-descending.png)";

        $(".expand").live("click",function(){
            var tr = $(this).parent().parent().parent().children("tr");
           for(var i =1;i<tr.length;i++){
               $(tr[i]).show();
           }
           $(this).addClass("collaps");
           $(this).removeClass("expand");
           $(this).css("background-image",arrowUp);

        });
        $(".collaps").live("click",function(){
            var tr = $(this).parent().parent().parent().children("tr");
           for(var i =1;i<tr.length;i++){
               $(tr[i]).hide();
           }
           $(this).addClass("expand");
           $(this).removeClass("collaps");
           $(this).css("background-image",arrowDown);
        });



})(jQuery);