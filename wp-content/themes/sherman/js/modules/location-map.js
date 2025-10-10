// -----------------------------------------------
// --Interactive SVG on the location page
//
// The accordion functionality
//
;(function( DUVINE, $, undefined ){

    var Locmap = (function(){

        var

            TIMING = 600;


        /**
         * description
         */
        function Locmap( el ){
            this.el = el;
            this.$el = $(el);

            this.$textTriggers = this.$el.find('.js-locmap-trigger[data-region]');
            this.$regions = this.$el.find('svg .region__outline');

            this.setup_triggers();
        }


        // ---------------
        // PUBLIC API
        //



        /**
         * Sets up triggers for the accordion
         */
        Locmap.prototype.setup_triggers = function(){

            var _this = this;

            this.$textTriggers.on('mouseover', function(){
                var region = $(this).data('region');
                _this.$regions.filter('[data-region="' + region + '"]').addClass('active');
            }).on('mouseout', function() {
                _this.$regions.removeClass('active');
            });

            this.$regions.on('mouseover', function(){
                var region = $(this).addClass('active').data('region');
                _this.$textTriggers.filter('[data-region="' + region + '"]').parent().addClass('active');
            }).on('mouseout', function(){
                var region = $(this).removeClass('active').data('region');
                _this.$textTriggers.parent().removeClass('active');
            }).on('click', function(event) {
                event.preventDefault();
                var region     = $(this).data('region'),
                    regionLink = _this.$textTriggers.filter('[data-region="' + region + '"]').attr('href');

                window.location = regionLink;
                
            });

        }



        return Locmap;

    })();




    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){

        $('.interactive-location').each( function(){
            var $this  = $(this),
                locmap = $this.data('locmap');

            if( !( locmap instanceof Locmap )){
                $this.data('locmap', new Locmap( this ));
            }
        });
    });

})( window.DUVINE = window.DUVINE || {}, jQuery );