(function($){
    'use strict';
    $(function() {

        $('.Slideshow .slick-slider').on( 'init', function(){
            $('.Slideshow .slick-slider').removeClass( 'slick-is-loading' );
        });

        $('.Slideshow .slick-slider').slick({
            infinite: true,
            autoplay: true,
            arrows: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: false,
            lazyLoad: 'ondemand'
        });
    });
})(jQuery);
