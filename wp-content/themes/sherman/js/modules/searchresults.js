// -----------------------------------------------
// --Searchresults
//
// The search filtering functionality
//
;(function( DUVINE, $, undefined ){

    var Searchresults = (function(){

        /**
         * description
         */
        function Searchresults( el ){
            this.el = el;
            this.$el = $(el);

            this.$filterselect = this.$el.find('.js-filter-searchresults');
            this.$searchform = this.$el.find('.duvinefilters');
            this.$results = this.$el.find('.searchresults__results');
            this.$search_container = this.$el.closest('.content-area');

            this.handle_filter();
        }


        // ---------------
        // PUBLIC API
        //


        Searchresults.prototype.handle_filter = function(){
            if( this.$filterselect.length === 0 ){
                return false;
            }

            var _this = this;

            this.$filterselect.on('change', function(event) {
                event.preventDefault();
                _this.$search_container.addClass('duvine__loading');
                _this.$searchform.trigger('submit');
            });
        }




        return Searchresults;

    })();




    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){

        $('.searchresults').each( function(){
            var $this         = $(this),
                searchresults = $this.data('searchresults');

            if( !( searchresults instanceof Searchresults )){
                $this.data('searchresults', new Searchresults( this ));
            }
        });
    });

})( window.DUVINE = window.DUVINE || {}, jQuery );