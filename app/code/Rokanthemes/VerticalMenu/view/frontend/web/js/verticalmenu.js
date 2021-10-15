;(function($, window, document, undefined) {
    $.fn.VerticalMenu = function() {
        $(".navigation.verticalmenu li.ui-menu-item > .open-children-toggle").click(function(){
            if(!$(this).parent().children(".submenu").hasClass("opened")) {
                $(this).parent().children(".submenu").addClass("opened");
                $(this).parent().children("a").addClass("ui-state-active");
            }
            else {
                $(this).parent().children(".submenu").removeClass("opened");
                $(this).parent().children("a").removeClass("ui-state-active"); 
            }
        });
        $(".navigation.verticalmenu .submenu .subchildmenu li.ui-menu-item  > .open-children-toggle").click(function(){
            if(!$(this).parent().children(".subchildmenu").hasClass("opened")) {
                $(this).parent().children(".subchildmenu").addClass("opened");
                $(this).parent().children("a").addClass("ui-state-active");
                 $(this).parent().children(".subchildmenu.opened").show();
            }
            else {
                $(this).parent().children(".subchildmenu").removeClass("opened");
                $(this).parent().children("a").removeClass("ui-state-active");
                 $(this).parent().children(".subchildmenu").hide();
            }
        });
    };
})(window.Zepto || window.jQuery, window, document);