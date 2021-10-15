(function( $ ) {
    // the sameHeight functions makes all the selected elements of the same height
    $.fn.sameMinHeight = function() {
        var selector = this;
        var heights = [];

        // Save the heights of every element into an array
        selector.each(function(){
            var height = $(this).outerHeight();
            heights.push(height);
        });

        // Get the biggest height
        var maxHeight = Math.max.apply(null, heights);
        // Show in the console to verify
        // Set the maxHeight to every selected element
        selector.each(function(){
            $(this).css({minHeight: maxHeight + 'px'});
        });
    };

}( jQuery ));
