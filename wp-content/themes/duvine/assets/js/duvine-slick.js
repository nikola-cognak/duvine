(function($){
    $(function(){
    	if( $('.slick-home').length ) {

          $('.slick-home').on('init', function(event, slick, direction) {
            $('.slick-home').addClass( 'slick-ready' );
          });

        	$('.slick-home').slick({
                lazyLoad: 'progressive',
                dots: false,
                arrows: false,
                infinite: true,
                speed: 500,
                fade: true,
                cssEase: 'linear',
                autoplay: true,
                autoplaySpeed: 2000,
            });
        }
    });
})(jQuery);
