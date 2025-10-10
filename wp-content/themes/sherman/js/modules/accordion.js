// -----------------------------------------------
// --Accordion
//
// The accordion functionality
//
;(function( DUVINE, $, undefined ){

    var Accordion = (function(){

        var

            TIMING = 600;


        /**
         * description
         */
        function Accordion( el ){
            this.el = el;
            this.$el = $(el);

            this.$triggers = this.$el.find('.js-duvine-accordion-trigger');
            this.$expandall = this.$el.find('.js-duvine-accordion-expandall');

            this.setup_triggers();
        }


        // ---------------
        // PUBLIC API
        //



        /**
         * Sets up triggers for the accordion
         */
        Accordion.prototype.setup_triggers = function(){

            var _this = this;

            this.$triggers.on('click', function( event ){
                event.preventDefault();
                $(this).toggleClass('active').parent().toggleClass('active').find('.accordion__content').slideToggle( TIMING, function(){
                    $(document).trigger('tournav.update_sections');
                } );
            });

            this.$expandall.on('click', function(event) {
                event.preventDefault();
                var $t   = $(this),
                    alt  = $t.data('alt'),
                    text = $t.text();

                $t.text(alt).data('alt', text).toggleClass('expand_them_all');

                if( $t.hasClass('expand_them_all') ){
                    _this.$triggers.each( function(){
                        var $t = $(this);

                        if( !$t.hasClass('active') ){
                            $t.trigger('click');
                        }
                    });
                } else {
                    _this.$triggers.each( function(){
                        var $t = $(this);

                        if( $t.hasClass('active') ){
                            $t.trigger('click');
                        }
                    });
                }

                
            });

        }



        return Accordion;

    })();




    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){

        $('.duvine-accordion').each( function(){
            var $this     = $(this),
                accordion = $this.data('accordion');

            if( !( accordion instanceof Accordion )){
                $this.data('accordion', new Accordion( this ));
            }
        });
    });

})( window.DUVINE = window.DUVINE || {}, jQuery );