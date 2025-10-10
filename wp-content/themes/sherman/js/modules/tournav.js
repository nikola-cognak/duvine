// -----------------------------------------------
// --Tour navigation
//
// The accordion functionality
//
;(function( DUVINE, $, undefined ){


    /**
     * Creates and returns a new, throttled version of the passed
     *  function, that, when invoked repeatedly, will only actually
     *  call the original function at most once per every 'threshold'
     *  milliseconds. Useful for rate-limiting events that occur
     *  faster than you can keep up with.
     * @link - https://remysharp.com/2010/07/21/throttling-function-calls
     */
    function throttle(fn, threshhold, scope) {
        threshhold || (threshhold = 250);
        var last, deferTimer;
        return function () {
            var context = scope || this;

            var now = +new Date,
                args = arguments;
            if (last && now < last + threshhold) {
                // hold on to it
                clearTimeout(deferTimer);
                deferTimer = setTimeout(function () {
                    last = now;
                    fn.apply(context, args);
                }, threshhold);
            } else {
                last = now;
                fn.apply(context, args);
            }
        };
    }


    var tournav = (function(){

        var



            TIMING     = 600,

            SECTIONS   = [],

            HEROPANE   = -1,
            NAVHEIGHT  = 0,
            TOURHEIGHT = 0,
            FIXBOTTOM  = 0,

            // cached
            $window    = $(window),
            $nav,
            $main;

        
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
            $nav = $('#tour-main-navigation');
            $main = $('#tour-main-content');

            if( $nav.length === 0 || $main.length === 0 ){
                return false;
            }

            build();

            handle_scroll();

            scrollto_anchor();

            $(document).on('tournav.update_sections', update_sections);
        }




        // ---------------
        // PRIVATE
        //


        /**
         * Builds the navigation from the content
         */
        function build(){
            var windowOffset = get_window_offset(),
                mastHeight   = get_mast_height(),
                $hero        = $('.tourhero');

            if( $hero.length > 0 ){
                HEROPANE = $hero.outerHeight();
            }

            $main.children('.toursection').each(function(){
                var $section   = $(this),
                    label      = $section.data('section'),
                    sectionTop = $section.position().top - windowOffset,
                    $link      = $('<li class="tournav__listel"><a href="#" class="tournav__link">' + label + '</a></li>').data( 'jqsection', $section );

                SECTIONS.push( sectionTop );

                $link.appendTo( $nav.find('.tournav__list') );

                $link.on('click', function(event) {
                    event.preventDefault();
                    var $section = $(this).data('jqsection')

                    if( $section === undefined ){
                        return false;
                    }

                    var sectionTop = $section.position().top;

                    $('html, body').animate({
                        scrollTop : sectionTop + 'px'
                    });
                });
            });

            $nav.css('top', $hero.height() + 'px');

            NAVHEIGHT  = $nav.outerHeight();
            TOURHEIGHT = $main.height() + $main.offset().top;
            FIXBOTTOM  = TOURHEIGHT - NAVHEIGHT - mastHeight;
        }




        /**
         * updates the data for sections
         */
        function update_sections(){
            var windowOffset = get_window_offset(),
                mastheight   = get_mast_height();

            SECTIONS = [];

            $main.children('.toursection').each(function(){
                var $section   = $(this),
                    sectionTop = $section.position().top - windowOffset;

                SECTIONS.push( sectionTop );
            });

            TOURHEIGHT = $main.height() + $main.offset().top;
            FIXBOTTOM  = TOURHEIGHT - NAVHEIGHT - mastheight;
        }




        /**
         * Handles the scrolling
         */
        function handle_scroll(){
            var numSections = SECTIONS.length;

            $(window).on('scroll', throttle( function(event) {
                var scrollTop = $window.scrollTop();

                if( scrollTop > FIXBOTTOM ){
                    $nav.addClass('fix-bottom');
                } else {
                    $nav.removeClass('fix-bottom');
                }

                if( scrollTop > HEROPANE){
                    $nav.addClass('visible');
                } else {
                    $nav.removeClass('visible');
                }


                if( scrollTop < SECTIONS[0] ){
                    $nav.find('.tournav__listel').first().addClass('active').siblings('.active').removeClass('active');
                    return true;
                }

                if( scrollTop > SECTIONS[ numSections - 1 ] ){
                    $nav.find('.tournav__listel').last().addClass('active').siblings('.active').removeClass('active');
                    return true;
                }

                for( var i = 0; i < numSections; i++ ){

                    if( scrollTop > SECTIONS[i] && scrollTop < SECTIONS[i+1] ){
                        $nav.find('.tournav__listel').eq(i).addClass('active').siblings('.active').removeClass('active');
                        break;
                    }
                }

            }, 50 )).trigger('scroll');
        }



        /**
         * Smooth scroll to an anchor on the page
         */
        function scrollto_anchor(){
            $('.infobox__cta a[href^="#"]').on('click', function(event) {
                var anchor       = $(this).attr('href'),
                    $anchorDiv   = $(anchor),
                    anchorOffset = $anchorDiv.offset();

                if( !anchorOffset ){
                    return true;
                }

                event.preventDefault();

                var goTo = anchorOffset.top;

                $('html, body').animate({
                    scrollTop : goTo + 'px'
                });

            });
        }


        // ---------------
        // UTIL
        //



        /**
         * Gets the window offset
         */
        function get_window_offset(){
            var windowHeight = $window.height(),
                windowOffset = windowHeight * .25;

            return windowOffset;
        }



        /**
         * Gets the mast height
         */
        function get_mast_height(){
            var mastheight = $('#masthead').outerHeight() + 20;

            if( $('#wpadminbar').length > 0 ){
                mastheight += $('#wpadminbar').outerHeight();
            }

            return mastheight;
        }



    })();




    // -------------------------------
    // DOM ready
    //
    $(window).load(function(){
        tournav.init();

        $('.tournav').css('opacity','1');
    });

})( window.DUVINE = window.DUVINE || {}, jQuery );