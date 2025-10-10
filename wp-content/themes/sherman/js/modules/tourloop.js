// -----------------------------------------------
// --tourfinder
//
// The tourfinder functionality
//
;(function( DUVINE, $, undefined ){

    var Tourloop = (function(){

        var

            CAN_AJAX = typeof duvine_ajax === 'object' && duvine_ajax.ajaxurl,

            TIMING = 600;


        /**
         * Constructor
         */
        function Tourloop( el ){
            this.el = el;
            this.$el = $(el);

            // cache
            this.$tourloop_container = this.$el.find('.tourloop__holder');  // the actual tour loop container

            this.$filters = this.$el.find('.tourfilters');                  // tour filters
            this.$quickfilters = this.$el.find('.quickfilter__filters');    // the quick filters

            this.$filterdrawer_trigger = this.$el.find('.js-trigger-advancedfilters'); // trigger for filter drawer
            this.$filterdrawer = this.$el.find('.tourfilters__expandable'); // the expandable drawer with the extra filters

            // static 
            this.loading_class = 'duvine__loading';


            // setup
            this.checked_filter_count = this.get_selected_filter_count();

            // filters
            this.filters = {};
            this.sortby = '';


            // event listeners
            this.handle_date_drawer();
            this.handle_more_filters();
            this.handle_checkbox_filters();
            this.handle_loadmore();
            this.handle_private_filter();
            this.handle_quickfilters();
            this.handle_sort_tours();
            this.handle_mobile_filter();
            this.handle_clear_filters();
            this.init_tooltips();

            // filtering
            this.handle_main_filters();
        }



        /**
         * Gets the currently checked filters
         */
        Tourloop.prototype.get_selected_filter_count = function(){
            var selected = this.$filterdrawer.find('input[type="checkbox"]:checked').length;

            return selected;
        }





        /* ------------------------------------------
         * --event listeners
         * ------------------------------------------ */


        /**
         * Date drawer stuff, like the trigger and the year slider
         *
         * @param jQuery $parent // The parent item
         */
        Tourloop.prototype.handle_date_drawer = function( $parent ){
            var $outer = $parent || this.$el;

            $outer.find('.js-open-datedrawer').on('click', function(event) {
                event.preventDefault();

                $(this).toggleClass('active')
                    .closest('.tourlist__listing').find('.tourlisting__datedrawer').slideToggle( TIMING );
                
            });
        }



        /**
         * Expands the more filters drawer
         */
        Tourloop.prototype.handle_more_filters = function(){

            // this is not used any more since they are in a dropdown
            return;

            var _this = this;

            this.$filterdrawer_trigger.on('click', function(event) {
                event.preventDefault();

                var $trigger = $(this).toggleClass('active'),
                    currText = $trigger.text(),
                    altText  = $trigger.data('active');

                $trigger.text( altText ).data('active', currText );
                _this.$filterdrawer.slideToggle( TIMING );
            });
        }



        /**
         * Handles checks of the checkmark filters
         */
        Tourloop.prototype.handle_checkbox_filters = function(){

            var _this        = this,
                $filterCount = this.$filterdrawer_trigger.prev('.tourfilters__filtercount'),
                activeClass  = 'tourfilters__filtercount--visible';

            this.$filterdrawer.find('input[type="checkbox"]').on('change', function(event) {
                event.preventDefault();

                if( this.checked ){
                    _this.checked_filter_count++;
                } else {
                    _this.checked_filter_count--;
                }

                $filterCount.text( _this.checked_filter_count );

                if( _this.checked_filter_count > 0 ){
                    $filterCount.addClass( activeClass );
                } else {
                    $filterCount.removeClass( activeClass );
                }
            });
        }




        //
        // MAIN FILTERS
        //


        /**
         * Filters the tours
         */
        Tourloop.prototype.handle_main_filters = function(){
            var _this = this;

            this.filters = this.$filters.serializeObject();


            this.$filters.on('submit', function(event, eventData) {

                if( ! CAN_AJAX || eventData && eventData.bypassAjax ){
                    return true;
                }

                event.preventDefault();

                var $form       = $(this),
                    filterQuery = $form.serialize(),
                    filterObj   = $form.serializeObject();

                _this.filters = filterObj;
                _this.disable_mobile_filters();
                if ((filterObj.hasOwnProperty('date') && filters.date != '') && _this.active_auto_check !== false) {
                    _this.active_auto_check = true;
                }

                history.pushState( filterObj, "Filtered tours", window.location.origin + window.location.pathname + '?' + filterQuery);
                _this.filter_tours();
            });
        }



        /**
         * Handles the buttons selection between all tours, scheduled tours,
         *  private only tours
         */
        Tourloop.prototype.handle_private_filter = function(){

            var _this           = this,
                $scheduleSelect = this.$el.find('input[name="tourtype[]"]');

            if (window.innerWidth < 581) {
                $('.privatefilter').not('.tour-finder-mobile').detach();
            } else {
                $('.privatefilter.tour-finder-mobile').detach();
            }
            $scheduleSelect.on('change', function(event) {
                event.preventDefault();
                if (
                    $('.privatefilter').find('#tourtype--scheduled').prop('checked') &&
                    _this.filters.hasOwnProperty('tourtype') &&
                    _this.filters.tourtype.includes('scheduled') &&
                    (!_this.filters.hasOwnProperty('date') || filters.date == '')
                ) {
                    _this.active_auto_check = true;
                } else {
                    _this.active_auto_check = false;
                }
                _this.$filters.trigger('submit');
            });
        }


        /**
         * Sort tours by the different sort options
         */
        Tourloop.prototype.handle_sort_tours = function(){
            
            var _this       = this,
                $sortselect = this.$el.find('.js-duvine-sortfilters');

            if( ! CAN_AJAX){
                $sortselect.hide().siblings('.chosen-container').hide();
                return false;
            }

            $sortselect.on('change', function(event) {
                event.preventDefault();
                _this.sortby = $(this).val();
                _this.filter_tours();
            });
        }




        //
        // LOAD MORE
        //


        /**
         * Handles the ajax call to lazy load more tours on click
         */
        Tourloop.prototype.handle_loadmore = function(){
            
            var _this     = this,
                $trigger  = this.$el.find('.js-trigger-tourloop-lazyload'),
                $tourlist = this.$tourloop_container.find('.tourlist');

            if( ! CAN_AJAX  ){
                $trigger.hide();
                return false;
            }

            $trigger.on('click', function(event) {
                event.preventDefault();

                var $button  = $(this),
                    offset   = $button.data('touroffset'),
                    filters  = _this.$filters.serializeObject(),
                    $spinner = $( '<ul class="cta__loadingspinner"><li></li><li></li><li></li></ul>').appendTo( $button );

                setTimeout(function(){
                    $button.addClass('cta--loading');
                }, 50);

                $.ajax({
                    url  : duvine_ajax.ajaxurl,
                    data : {
                        action  : 'lazyload_tours',
                        offset  : offset,
                        filters : filters,
                        sortby  : _this.sortby
                    },
                }).done(function( response ) {


                    if( response.success === false || response.data === undefined || !response.data.markup ){
                        server_error_handler( response );
                        $button.hide();
                        return false;
                    }

                    var $newTourlist = $('<ul class="tourlist baselist" style="display:none;"></ul>');

                    $newTourlist.append( response.data.markup ).insertAfter( $tourlist );

                    setTimeout(function(){
                        $newTourlist.slideDown( TIMING, function(){
                            if( response.data.hide_loadmore ){
                                $button.hide();
                            }
                        } );

                        $spinner.remove();
                        $button.removeClass('cta--loading');
                        
                    }, 20);

                    $tourlist = $newTourlist;


                    if( response.data.post_count ){
                        $button.data('touroffset', response.data.post_count + offset );
                    }

                    _this.handle_date_drawer( $newTourlist );

                    $(window).trigger('tourloop.load');


                }).fail( server_error_handler );
            });

        }


        //
        // MOBILE FILTERING
        //


        /**
         * Trigger the mobile filtering drawer
         */
        Tourloop.prototype.handle_mobile_filter = function(){
            var _this          = this,
                $mobileTrigger = this.$el.find('.tourfilter__mobiletrigger'),
                $closeTrigger  = this.$el.find('.js-close-mobilefilters');

            $mobileTrigger.on('click', function(event) {
                event.preventDefault();
                _this.enable_mobile_filters();
            });

            $closeTrigger.on('click', function(event) {
                event.preventDefault();
                _this.disable_mobile_filters();
            });
        }


        /**
         * Clear the filter checkboxes
         */
        Tourloop.prototype.handle_clear_filters = function(){
            var _this         = this,
                $clearTrigger = this.$filters.find('.js-clear-filters'),
                $filterChecks = this.$filters.find('.tourfilters__expandable input[type="checkbox"]');

            $clearTrigger.on('click', function(event) {
                event.preventDefault();
                $filterChecks.each(function(){
                    if( this.checked ){
                        $(this).prop('checked', false).trigger('change');
                    }
                });

                _this.$filters.find('.duvinefilters .js-duvine-chosen').each(function(){
                    var $select = $(this)

                    $select.val('').trigger("chosen:updated");
                });

                _this.$filters.find('.js-duvine-datepicker').each(function(){
                    var $datepicker = $(this);

                    $datepicker.find('.search-choice .search-choice-close').trigger('click');
                });

                _this.$filters.submit();
            });
        }





        //
        // OTHER FILTERS
        //


        /**
         * Handles the quick filters (like on the collection page)
         */
        Tourloop.prototype.handle_quickfilters = function(){

            var $countryGroups = this.$el.find('.tourlist__country');

            this.$quickfilters.on('change', function( event ){
                event.preventDefault();

                var country = $(this).val();

                $countryGroups.show();

                if( country !== 'all' ){
                    $countryGroups.filter(':not([data-country="' + country + '"])').hide();
                }

                
            });
        }









        /* ------------------------------------------
         * --handle all the filtering
         * ------------------------------------------ */

        Tourloop.prototype.filter_tours = function(){

            var _this     = this,
                filters   = this.filters,
                sortby    = this.sortby,
                $loadable = this.$tourloop_container.closest('.block--tour_browser');
            if ((filters.hasOwnProperty('date') && filters.date != '') && _this.active_auto_check) {
                $('.privatefilter').find('#tourtype--scheduled').prop('checked', 'checked');
                if (filters.hasOwnProperty('tourtype')) {
                    filters.tourtype.push('scheduled');
                } else {
                    filters.tourtype = ['scheduled'];
                }
                var $form = $('form.tourfilters'),
                    filterQuery = $form.serialize(),
                    filterObj   = $form.serializeObject();
                history.pushState( filterObj, "Filtered tours", window.location.origin + window.location.pathname + '?' + filterQuery);
            }
            $loadable.addClass( this.loading_class );

            $.ajax({
                url  : duvine_ajax.ajaxurl,
                data : {
                    action  : 'filter_tourloop',
                    filters : filters,
                    sortby  : sortby
                }
            }).done(function( response ) {
                if( response.success === false || response.data === undefined || !response.data.markup ){
                    server_error_handler( response );
                    return false;
                }

                _this.$tourloop_container.html( response.data.markup );
                _this.handle_date_drawer( _this.$tourloop_container );
                _this.handle_loadmore();
                _this.init_tooltips();
                _this.reset_private_filter();

                $(window).trigger('tourloop.load');

            }).always(function(){
                $loadable.removeClass(_this.loading_class );
            }).fail( server_error_handler );

        }




        /* ------------------------------------------
         * --other prototype util
         * ------------------------------------------ */

        Tourloop.prototype.enable_mobile_filters = function(){
            this.$filters.addClass('tourfilters--active');
            $('html, #page').addClass('html-prevent-scroll');
        }
        Tourloop.prototype.disable_mobile_filters = function(){
            this.$filters.removeClass('tourfilters--active');
            $('html, #page').removeClass('html-prevent-scroll');
        }
        Tourloop.prototype.init_tooltips = function(){
            $('.tooltip').tooltipster({
                maxWidth           : 280,
                animation          : 'fade',
                delay              : 200,
                repositionOnScroll : true,
                interactive        : true,
                delayTouch         : 300,
                zIndex             : 10,
                // trigger            : 'click'
                trigger            : 'custom',
                triggerOpen        : { mouseenter: true, touchstart: true },
                triggerClose       : { mouseleave: true, tap: true }
            });
            $('.tooltip-tour-calendar').tooltipster({
                contentAsHTML      : true,
                theme              : ['tooltipster-noir', 'tooltipster-noir-customized'],
                //theme              : ['tooltipster-noir'],
                side               : 'right',
                maxWidth           : 280,
                animation          : 'fade',
                delay              : 200,
                repositionOnScroll : true,
                interactive        : true,
                delayTouch         : 300,
                zIndex             : 10,
                // trigger            : 'click'
                trigger            : 'custom',
                triggerOpen        : { mouseenter: true, touchstart: true },
                triggerClose       : { mouseleave: true, tap: true }
            });
            $('.tooltip-tour-calendar').on('click', function(event) {
                event.preventDefault();
            });
        }
        Tourloop.prototype.reset_private_filter = function(){
            if( $('.privatefilter .tourtype__label.active').length < 1 ) {
                $(".privatefilter label[for='tourtype--all'] input:checkbox").prop('checked', true);
                $(".privatefilter label[for='tourtype--all']").addClass('active');
            }
        }


        /* ------------------------------------------
         * --private / util
         * ------------------------------------------ */

        /**
         * Fires when there's a server error when ajaxing
         */
        function server_error_handler( error ){
            console.error("There was a server error.");
            console.error(error);
        }



        // return
        return Tourloop;

    })();




    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){
        $('.tourloop').each( function(){
            var $this    = $(this),
                tourloop = $this.data('tourloop');

            if( !( tourloop instanceof Tourloop )){
                $this.data('tourloop', new Tourloop( this ));
            }
        });
    });

})( window.DUVINE = window.DUVINE || {}, jQuery );