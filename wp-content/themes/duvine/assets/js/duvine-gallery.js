(function($){
    'use strict';
    $(function() {

        $('.slider-for').on( 'init', function(){
            $('.slider-for').removeClass( 'slick-is-loading' );
        });

        $('.slider-nav').on( 'init', function(){
            $('.slider-for').removeClass( 'slick-is-loading' );
        });

        $('.slider-for').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: false,
            arrows: false,
            lazyLoad: 'ondemand',
            asNavFor: '.slider-nav',
            responsive: [{
                breakpoint: 480,
                settings: {
                    arrows: true,
                    asNavFor: null
                }
            }]
        });

        $('.slider-nav').slick({
            asNavFor: '.slider-for',
            slidesToShow: 7,
            slidesToScroll: 1,
            centerMode: true,
            focusOnSelect: true,
            arrows: true,
            responsive: [{
                breakpoint: 480,
                settings: 'unslick'
            }]
        });
    });
})(jQuery);
