(function($){
    $(window).load(function() {
    	var $slideshow = $(".Slideshow"),
    		$slideshowImages = $(".Slideshow-Item", $slideshow);

        $slideshow.cycle({
    		slideExpr: 'img',
    		prev: '.Rotator-Prev',
    		next: '.Rotator-Next',
    		before: function(currSlideElement, nextSlideElement, options, forwardFlag) {
    			var image = $(nextSlideElement);

    			//Determine if it is the image or it is a link
    			if (! image.is("img") ) {
    				image = image.children("img");
    			}

    			if ( image.length && image.data('image') !== undefined ) {
    				if ( image.data('isloaded') === undefined ) {
    					//Pause Rotator
    					$slideshow.cycle('pause');

    					//Load image
    					image.prop('src', image.data('image'));

    					//Detect if the image is loaded
    					image.imagesLoaded( function(){

    						//Set image to loaded
    						image.addClass("Loaded").data('isloaded', 'true');

    						//Resume Rotator
    						$slideshow.cycle('resume');
    					});
    				}
    			}
    		}
        });

        // hide the nav if there is only 1 image
        if( $slideshowImages.length <= 1 ) {
    		$('.Rotator-Prev, .Rotator-Next', $slideshow).hide();
        }
    });
})(jQuery);
