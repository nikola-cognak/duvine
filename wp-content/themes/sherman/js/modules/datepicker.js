// -----------------------------------------------
// --datepicker
//
// The accordion functionality
//
;(function( DUVINE, $, undefined ){


    var Dateslider = (function(){

        function Dateslider( el ){
            this.el = el;
            this.$el = $(el);

            this.$slider = this.$el.find('.dateslider__slider');
            this.$slides = this.$el.find('.dateslider__el');

            
            this.el_classlist = '.' + this.$el.attr('class').replace( / /g, '.');
            
            // options
            this.options = this.$el.data();

            // global inits
            this.drawer_open = false;
            this.slideIndex = 0;
            

            // defaults
            this.setup_defaults();


            // setup
            this.setup_slider();
            this.build_nav();

            return this;
        }



        /**
         * sets up the default options for the slider
         */
        Dateslider.prototype.setup_defaults = function(){
            this.slideCount = this.$slides.length;
            this.yearsperslide = window.innerWidth > 580 ? this.options.yearsperslide : 1;
            this.hiddenCols = this.slideCount - this.yearsperslide;
        }


        /**
         * sets up the slider
         */
        Dateslider.prototype.setup_slider = function(){
            this.$slides.addClass('dateslider__el--yearcount-' + this.yearsperslide );
        };


        /**
         * sets up the navigation for the dateslider
         */
        Dateslider.prototype.build_nav = function(){

            var navMarkup = '<ul class="dateslider__nav baselist"><li class="dateslider__navlink dateslider__navlink--prev dateslider__navlink--hidden" data-direction="-1">Previous</li><li class="dateslider__navlink dateslider__navlink--next" data-direction="1">Next</li></ul>',
                
                _this       = this,
                $sliderWrap = this.$el.find('.dateslider__sliderwrap');

            if( this.hiddenCols > 0 ){
                $(navMarkup).insertAfter( $sliderWrap ).find( '.dateslider__navlink' ).on('click', function(event) {
                    var move = $(this).data('direction');
                    _this.moveTo(_this.slideIndex + move);
                });
            }

            if(this.slideCount > 2){
                $(document).ready(function () {
                    _this.moveTo(0);
                });
                $('.more-filters').on('change', function(){
                    _this.moveTo(0);
                })
            }
        }



        /**
         *  Move the dateslider
         */
        Dateslider.prototype.moveTo = function(i){
            
            var hiddenClass = 'dateslider__navlink--hidden',
                $navlinks   = this.$el.find('.dateslider__navlink').removeClass(hiddenClass),
                colWidth    = this.$slides.eq(0).outerWidth();
            
            if(colWidth === 0){
                colWidth = this.$slides.eq(0).closest('.chosen-container').outerWidth();
            }
                
            this.slideIndex = i;
            
            if( i >= this.hiddenCols ){
                $navlinks.filter('.dateslider__navlink--next').addClass( hiddenClass );
            } else if( i <= 0 ){
                $navlinks.filter('.dateslider__navlink--prev').addClass( hiddenClass );
            }

            if( i > this.hiddenCols ){
                this.slideIndex = this.hiddenCols;
            } else if( i < 0 ){
                this.slideIndex = 0;
            }

            var slideDelta = i * colWidth * -1;
            
            this.$slider.css('transform', 'translate3d(' + slideDelta + 'px, 0px, 0px)');
        }



        return Dateslider;
    })();





    var Datepicker = (function(){

        function Datepicker( el ){
            this.el = el;
            this.$el = $(el);

            this.$datepickerTrigger = this.$el.find('.datepicker__trigger');
            this.$choicelist = this.$el.find('.chosen-choices');
            this.$searchField = this.$el.find('.search-field');
            this.$monthCheckboxes = this.$el.find('.dateslider__monthblock input[type="checkbox"]');

            // helpers
            this.el_classlist = '.' + this.$el.attr('class').replace( / /g, '.');

            // state
            this.drawer_open = false;

            this.dates_selected = [];

            // setup
            this.setup_field_click();
            this.setup_monthpicker();
            this.get_load_filtered();


            this.$searchField.css( {
                minHeight : this.$datepickerTrigger.outerHeight() + 'px'
            });


            // handlers
            this.$el.on('datepicker:update', $.proxy( this.handle_update, this ));

        }


        /**
         * Sets up the trigger on the input field to open the datepicker
         */
        Datepicker.prototype.setup_field_click = function(){

            var _this = this;
            
            this.$datepickerTrigger.on('click', function(event) {
                event.preventDefault();

                if( _this.drawer_open ){
                    _this.close_datepicker_drawer();
                } else {
                    _this.open_datepicker_drawer();
                    _this.setup_field_blur();
                }
                
            });
        }


        /**
         * Closes the datepicker drawer
         */
        Datepicker.prototype.close_datepicker_drawer = function(){
            this.$el.removeClass('chosen-with-drop');
            this.drawer_open = false;
            $(document).off('click.duvinedatepicker');
        }


        /**
         * Opens the datepicker drawer
         */
        Datepicker.prototype.open_datepicker_drawer = function(){
            this.$el.addClass('chosen-with-drop');
            this.drawer_open = true;
            
        }



        /**
         * Sets up the blur interaction
         */
        Datepicker.prototype.setup_field_blur = function(){
            var _this = this;

            $(document).on('click.duvinedatepicker', function(event) {
                
                if ( _this.drawer_open && $(event.target).closest( _this.el_classlist ).length === 0 ) {
                    _this.close_datepicker_drawer();
                }
            });
        }


        /**
         * select months
         */
        Datepicker.prototype.setup_monthpicker = function(){
            if( this.$choicelist.length === 0 || this.$monthCheckboxes.length === 0 || this.$searchField.length === 0 ){
                return false;
            }

            var _this = this;

            this.$monthCheckboxes.on('change', function(event) {
                event.preventDefault();
                var $checkbox   = $(this),
                    label       = $checkbox.data('display'),
                    labelIndex  = _this.dates_selected.indexOf( label );

                if( labelIndex > -1 ){
                    _this.dates_selected.splice( labelIndex, 1 );
                    _this.$choicelist.find('li[data-label="' + label + '"]').remove();
                } else {
                    var $pillMarkup = $('<li class="search-choice" data-label="' + label + '"><span>' + label + '</span><a class="search-choice-close"></a></li>');
                    
                    _this.dates_selected.push( label );

                    $pillMarkup.appendTo( _this.$choicelist )
                        .find('.search-choice-close').on('click', $.proxy( _this.remove_pill_onclick, _this ));
                }

                _this.handle_update();

                // setTimeout(function(){
                //     _this.close_datepicker_drawer();
                // }, 200);

                
            });
            
        }


        /**
         * Gets any dates that are already populated from filters
         */
        Datepicker.prototype.get_load_filtered = function(){
            var _this = this;

            this.$choicelist.find('.search-choice').each(function(){
                var $pill = $(this),
                    label = $pill.data('label');

                _this.dates_selected.push( label );

                $pill.find('.search-choice-close').on('click', $.proxy( _this.remove_pill_onclick, _this ) );
            });
        }



        /**
         * Removes a pill
         */
        Datepicker.prototype.remove_pill_onclick = function( event ){
            event.preventDefault();
            var $pill = $(event.target).closest('.search-choice'),
                index = $pill.index() - 1;
                label = $pill.data('label');

            this.$monthCheckboxes.filter('[data-display="' + label + '"]').prop( 'checked', false );

            $pill.remove();

            this.dates_selected.splice( index, 1 );

            this.handle_update();
        }


        /**
         * Handle an update to the monthpicker
         */
        Datepicker.prototype.handle_update = function(){
            if( this.dates_selected.length === 0 ){
                this.$searchField.css('width', 'auto');
            } else {
                this.$searchField.css({
                    width    : '0',
                    overflow : 'hidden'
                });
            }
        }


        return Datepicker;
    })();



    /**
     * Init
     */
    function dateslider_init(){
        $('.dateslider').each( function(){
            var $this = $(this),
                dateslider = $this.data('dateslider');

            if( !( dateslider instanceof Dateslider )){
                $this.data('dateslider', new Dateslider( this ));
            }
        });

        $('.js-duvine-datepicker').each(function(){
            var $this = $(this),
                datepicker = $this.data('datepicker');

            if( ! ( datepicker instanceof Datepicker)){
                $this.data('datepicker', new Datepicker( this ));
            }
        });
    }




    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){
        dateslider_init();

        $(window).on('tourloop.load', function() {
            dateslider_init();
        });
    });

})( window.DUVINE = window.DUVINE || {}, jQuery );
