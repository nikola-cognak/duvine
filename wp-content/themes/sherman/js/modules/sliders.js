// -----------------------------------------------
// --Sliders
//
// All the sliders
//
;(function( DUVINE, $, undefined ){

    var sliders = (function(){

        var

            PLAYSPEED = 600,

            PREVARROW = '<span class="sk-slider-nav nav-previous"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 612 792"><polyline points="427.9,646.4 168.2,389.4 427.9,129.7"/></svg></span>',
            NEXTARROW = '<span class="sk-slider-nav nav-next"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 612 792"><polyline points="168.2,129.7 427.9,386.7 168.2,646.4"/></svg></span>';



        // ---------------
        // The individual sliders
        //

        var image_slider = function(){
            $('.image-slider__slider').slick({
                prevArrow     : PREVARROW,
                nextArrow     : NEXTARROW,
                appendArrows : ".image-slider__arrows",
                arrows        : true,
                dots          : true,
                swipe         : true,
                responsive: [
                {
                  breakpoint: 580,
                  settings: {
                    arrows    : false
                  }
                }
                ]
                /*dotsClass     : 'slick-dots',
                swipe         : true,
                autoplay      : false,
                autoplaySpeed : PLAYSPEED,8*/
            });
        }


        var blog_featuredarticles = function(){
            $('.blogpost__featureslider').slick({
                prevArrow     : PREVARROW,
                nextArrow     : NEXTARROW,
                arrows        : false,
                dots          : true,
                dotsClass     : 'slick-dots',
                slide         : '.blogpost__featured',
                swipe         : true,
                autoplay      : false,
                autoplaySpeed : PLAYSPEED,
            });
        }


        // the gallery blog post type
        var blog_gallery = function(){

            var $galleryarticle = $('.galleryarticle');

            $galleryarticle.each( function(){
                var $article    = $(this),
                    $slider     = $article.find('.gallery-post__gallery-slider'),
                    $slideIndex = $article.find('.gallery-post__slide-count .current-slide'),
                    $nav_next   = $article.find('.gallery-slider__pagenav.nav-next'),
                    $nav_prev   = $article.find('.gallery-slider__pagenav.nav-prev');

                if( $slider.length === 0 ){
                    return true;
                }

                if( $slideIndex.length > 0 ){
                    $slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
                        event.preventDefault();
                        $slideIndex.text(nextSlide+1);
                    });
                }

                $slider.on('init', function(event, slick) {
                    $slider.closest('.duvine__loading').removeClass('duvine__loading');
                });

                $nav_prev.on('click', function(event) {
                    event.preventDefault();
                    $slider.slick('slickPrev');
                });
                $nav_next.on('click', function(event) {
                    event.preventDefault();
                    $slider.slick('slickNext');
                });

                $slider.not('.slick-initialized').slick({
                    arrows         : false,
                    infinite       : true,
                    adaptiveHeight : true
                });
            });

        }



        // the gallery within a regular article
        var blog_article_post_gallery = function(){

            $('.blogpost__galleryslider').slick({
                prevArrow      : PREVARROW,
                nextArrow      : NEXTARROW,
                adaptiveHeight : true
            });
        }



        var block_featured_content = function(){

            var $slider = $('.featuredcontent__river');

            $slider.on('init', function(event, slick) {
                setTimeout(function(){
                    var imgH = $slider.find('.featuredcontent__image img').eq(0).height() + 40,
                        middle = imgH / 2;

                    $slider.find('.sk-slider-nav').css({
                        top: middle + 'px'
                    });
                }, 1000);
            });

            $slider.slick({
                prevArrow      : PREVARROW,
                nextArrow      : NEXTARROW,
                infinite       : false,
                slidesToShow   : 4,
                slidesToScroll : 1,
                responsive     : [{
                    breakpoint : 1120,
                    settings   : { slidesToShow : 3 }
                }, {
                    breakpoint : 860,
                    settings   : { slidesToShow: 2 }
                } ,{
                    breakpoint : 580,
                    settings   : { slidesToShow: 1 }
                }]
            });
        }



        var block_featured_blogs = function(){
            var $slider = $('.featuredblogs__slider');

            // $slider.on('init', function (event, slick) {
            //     var h = $slider.height();

            //     $slider.find('.featuredblog__slide').height(h);
            // });
            
            $slider.slick({
                prevArrow : PREVARROW,
                nextArrow : NEXTARROW,
				autoplay: true,
                appendArrows : ".featuredblogs__arrows",
                adaptiveHeight : true,
                infinite  : false
            });
        }


        var press_gallery = function(){
            $('.featured-press-articles').slick({
                prevArrow     : PREVARROW,
                nextArrow     : NEXTARROW,
                arrows        : false,
                dots          : true,
                dotsClass     : 'slick-dots',
                slide         : '.sk-slider',
                swipe         : true,
                autoplay      : false,
                autoplaySpeed : PLAYSPEED,
            });
        }


        //
        // INIT
        //



        /**
         * init the things
         */
        function init(){
            
            if( typeof $().slick !== 'function' ){
                console.error( "Slick is not initialized." );
                return false;
            }

            image_slider();

            blog_featuredarticles();

            blog_gallery();

            blog_article_post_gallery();

            block_featured_content();

            block_featured_blogs();

            press_gallery();

        }




        // return the API
        return {
            init : init
        };


    })();




    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){
        sliders.init();
    });

})( window.DUVINE = window.DUVINE || {}, jQuery );


