;(function(DUVINE, $, undefined){

    var $nav,
        $nav_container;




    /**
     * Opens/closes the mobile nav
     */
    function handle_activation(){
        $('.js-toggle-mobilenav').on('click', function(event) {
            event.preventDefault();
            $nav.toggleClass('mobilenav--active');
            $('html').toggleClass('html-prevent-scroll');
        });

        $('.js-close-mobilenav').on('click', function(event) {
            event.preventDefault();
            $nav.removeClass('mobilenav--active');
            $('html').removeClass('html-prevent-scroll');
        });
    }


    /**
     * Opens the submenus within the mobile menu
     */
    function handle_subdrawers(){
        var $subdrawer_trigger = $nav.find('.subdrawer__trigger, .menu-item-has-children > a'),
            $subdrawer_back    = $nav.find('.js-mobilenav-back'),

            $active_subdrawer;

        $subdrawer_trigger.on('click', function(event) {
            event.preventDefault();
            
            $nav_container.css('transform', 'translate3d(-50%, 0, 0)');

            $active_subdrawer = $(this).siblings('.sub-menu').show();
            
        });


        $subdrawer_back.on('click', function(event) {
            event.preventDefault();
            $nav_container.css('transform', 'translate3d(0%, 0, 0)');

            // $nav_container.on( window.transitionEnd, function(){

                $('.sub-menu').fadeOut('fast');

                $active_subdrawer = null;

            // } );
        });

    }








    $(document).ready(function(){

        $nav = $('#duvine-mobile-nav');
        $nav_container = $nav.find('.mobilenav__container');

        if( $nav.length === 0 || $nav_container.length === 0 ){
            return false;
        }

        handle_activation();
        handle_subdrawers();
    });

})(window.DUVINE = window.DUVINE || {}, jQuery);