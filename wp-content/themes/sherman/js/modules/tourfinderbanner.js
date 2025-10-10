// -----------------------------------------------
// --tourfinder banner
//
// The tourfinder banner functionality
//
;(function( DUVINE, $, undefined ){

    var Tourfinderbanner = (function(){

        var

            TIMING = 600;

        


        // ---------------
        // PUBLIC API
        //



        /**
         * init the things
         */
        function Tourfinderbanner( el ){
            this.el = el;
            this.$el = $(el);

            this.$window = $(window);

            // setup
            this.handle_dropdowns();
            this.handle_selectall();
            this.handle_close_dropdown();

            // classes
            this.class_active_dropdown = 'tourfinderbanner__dropdown--active';
            this.class_active_select = 'tourfinderbanner__select--active';
        }




        /**
         * Handles the dropdowns
         */
        Tourfinderbanner.prototype.handle_dropdowns = function(){
            
            var _this      = this,
                $selects   = this.$el.find('.tourfinderbanner__select'),
                $dropdowns = this.$el.find('.tourfinderbanner__dropdown');

            $selects.on('click', function(event) {
                event.preventDefault();

                var $clicked      = $(this),
                    alreadyActive = $clicked.hasClass( _this.class_active_select );

                $dropdowns.removeClass( _this.class_active_dropdown );
                $selects.removeClass( _this.class_active_select );

                if( alreadyActive ){
                    return false;
                }

                $clicked.toggleClass( _this.class_active_select ).siblings('.tourfinderbanner__dropdown').toggleClass( _this.class_active_dropdown );

                if( _this.$window.width() < 861 ){
                    $('html').toggleClass('html-prevent-scroll');
                }
                
            });
        }


        /**
         * Handles the checking off of "Select all"
         */
        Tourfinderbanner.prototype.handle_selectall = function(){
            this.$el.find('.checkbox--selectall').on('change', function(event) {
                $(this).closest('.tourfinderbanner__option').siblings().find('input[type="checkbox"]').prop('checked', this.checked);
            });

            this.$el.find('input[type="checkbox"]').on('change', function(event) {
                event.preventDefault();
                if( !this.checked ){
                    $(this).closest('.tourfinderbanner__option').prevAll('.tourfinderbanner__option--selectall').find('input[type="checkbox"]').prop('checked', false);
                    $(this).closest('.dateslider__el').find('.dateslider__option--selectall input[type="checkbox"]').prop('checked', false);
                }
            });


            this.$el.find('.dateslider__option--selectall input[type="checkbox"]').on('change', function(event) {
                event.preventDefault();
                var $monthboxes = $(this).closest('.dateslider__el').find('.dateslider__dateblocks input[type="checkbox"]');

                if( this.checked ){
                    $monthboxes.prop('checked', true)
                } else {
                    $monthboxes.prop('checked', false)
                }
                
            });
        }



        /**
         * Close the dropdown
         */
        Tourfinderbanner.prototype.handle_close_dropdown = function(){
            
            var _this = this;

            this.$el.find('.tourfinderbanner__dropdownclose').on('click', function(event) {
                event.preventDefault();
                $(this).closest('.tourfinderbanner__dropdown').removeClass( _this.class_active_dropdown ).siblings('.tourfinderbanner__select').removeClass(_this.class_active_select);

                if( _this.$window.width() < 861 ){
                    $('html').removeClass('html-prevent-scroll');
                }
            });
        }





        return Tourfinderbanner;

    })();




    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){
        $('.tourfinderbanner').each( function(){
            var $this            = $(this),
                tourfinderbanner = $this.data('tourfinderbanner');

            if( !(tourfinderbanner instanceof Tourfinderbanner ) ){
                $this.data('tourfinderbanner', new Tourfinderbanner( this ) );
            }
        });
    });

})( window.DUVINE = window.DUVINE || {}, jQuery );