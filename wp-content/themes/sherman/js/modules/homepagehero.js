// -----------------------------------------------
// --Hero
//
// The functionality for the homepage banner hero image
//
;(function( DUVINE, $, undefined ){

    var homepageHero = (function(){

        var

            TIMING = 600,

            activeLinkClass = 'dayslider__time--active',
            activeImgClass  = 'heroslide__slide--active',

            // cached
            $hero,
            
            autoslideInterval;

        
        // return the API
        return {
            init : init
        };


        // ---------------
        // PUBLIC API
        //



        /**
         * init the things
         */
        function init(){

            $hero = $('.duvine-homepage-hero');

            $hero.find('.dayslider__time').on('click', function(event) {
                event.preventDefault();

                var $link = $(this),
                    index = $link.index();

                $link.addClass(activeLinkClass).siblings('.' + activeLinkClass).removeClass(activeLinkClass);

                $hero.find('.heroslide__slide').eq(index).addClass( activeImgClass ).siblings('.' + activeImgClass).removeClass(activeImgClass);

                // if clicked, clear the interval
                clearInterval(autoslideInterval);
            });

            var autoscroll = $hero.data('autoscroll');

            if(autoscroll && autoscroll > 0){
                autoslideInterval = setInterval(auto_slide, autoscroll * 1000);
            }
        }

        function auto_slide() {
            
            var $links     = $hero.find('.dayslider__time'),
                index      = $hero.find('.'+activeLinkClass).index(),
                next_index = (index > -1) ? (index+1) % $links.length : 0;

            $links.eq(next_index).addClass(activeLinkClass).siblings('.' + activeLinkClass).removeClass(activeLinkClass);
            $hero.find('.heroslide__slide').eq(next_index).addClass( activeImgClass ).siblings('.' + activeImgClass).removeClass(activeImgClass);
        }



        // ---------------
        // PRIVATE
        //



        // ---------------
        // UTIL
        //


    })();




    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){
        homepageHero.init();
    });

})( window.DUVINE = window.DUVINE || {}, jQuery );