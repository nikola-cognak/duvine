//
// the blog filter
;(function(DUVINE, $, undefined){


    /**
     * Handles clicks on the blogfilters
     */
    function handle_blogfilters(){
        var $filters = $(this),
            $cats    = $filters.find('.container-subnav__topics input[type="radio"]');

        $cats.on('change', function(event) {
            event.preventDefault();
            if( this.checked ){
                $filters.trigger('submit');
            }
        });

    }








    $(document).ready(function(){

        $('.duvine-blogfilters').each( handle_blogfilters );
    });

})(window.DUVINE = window.DUVINE || {}, jQuery);