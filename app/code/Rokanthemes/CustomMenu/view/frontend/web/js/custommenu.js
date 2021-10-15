;(function($, window, document, undefined) {
    $.fn.CustomMenu = function() {
        $(".nav-toggle").click(function(e){
            if(!$("html").hasClass("nav-open")) {
                $("html").addClass("nav-before-open");
                setTimeout(function(){ 
                    $("html").addClass("nav-open");
                }, 300);
            }
            else {
                $("html").removeClass("nav-open");
                setTimeout(function(){
                    $("html").removeClass("nav-before-open");
                }, 300);
            }
        }); 
		$(document).on('click', '#close-menu', function(){
            if(!$("html").hasClass("nav-open")) {
                $("html").addClass("nav-before-open");
                setTimeout(function(){
                    $("html").addClass("nav-open");
                }, 300);
            }
            else {
                $("html").removeClass("nav-open");
                setTimeout(function(){
                    $("html").removeClass("nav-before-open");
                }, 300);
            }
        }); 
        $(".navigation.custommenu li.ui-menu-item > .open-children-toggle").click(function(){
            if(!$(this).parent().children(".submenu").hasClass("opened")) {
                $(this).parent().children(".submenu").addClass("opened");
                $(this).parent().children("a").addClass("ui-state-active");
            }
            else {
                $(this).parent().children(".submenu").removeClass("opened");
                $(this).parent().children("a").removeClass("ui-state-active");
            }
        });
		 $(".navigation.custommenu .submenu .subchildmenu li.ui-menu-item  > .open-children-toggle").click(function() {
            if (!$(this).parent().children(".subchildmenu").hasClass("opened")) {
                $(this).parent().children(".subchildmenu").addClass("opened");
                $(this).parent().children("a").addClass("ui-state-active");
                $(this).parent().children(".subchildmenu.opened").show();
            } else {
                $(this).parent().children(".subchildmenu").removeClass("opened");
                $(this).parent().children("a").removeClass("ui-state-active");
                $(this).parent().children(".subchildmenu").hide();
            }
        });
    };
})(window.Zepto || window.jQuery, window, document);