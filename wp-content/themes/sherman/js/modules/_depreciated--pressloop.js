// -----------------------------------------------
// --tourfinder
//
// The tourfinder functionality
//
// This ajax functionality has been elected not to be implemented. The filters
//  will simply hit a url with the corresponding query string rather than ajax
//  filter the press posts.
//
;(function( DUVINE, $, undefined ){

    var Pressloop = (function(){

        var

            CAN_AJAX = typeof duvine_ajax === 'object' && duvine_ajax.ajaxurl,

            TIMING = 600;


        /**
         * Constructor
         */
        function Pressloop( el ){
            this.el = el;
            this.$el = $(el);

            // cache
            this.$pressloop_container = this.$el.find('.press-articles__container');         // the actual press loop container
            this.$filters = this.$el.find('.featuredpubs__viewarticles[data-publication]'); // tour filters


            // setup
            this.publication_filter = '';


            // event listeners
            this.handle_publication_filter();
        }






        /* ------------------------------------------
         * --ajax
         * ------------------------------------------ */




        /**
         * Filters the tours
         */
        Pressloop.prototype.handle_publication_filter = function(){
            var _this = this;

            this.$filters.on('click', function( event ) {

                if( ! CAN_AJAX ){
                    return true;
                }

                event.preventDefault();

                var publication = $(this).data('publication'),
                    filterUrl   = window.location.origin + window.location.pathname + '?publication=' + publication;

                _this.publication_filter = publication;
                _this.$pressloop_container.slideUp( TIMING );

                history.pushState( publication, "Filtered Press", filterUrl);

                $.ajax({
                    url  : duvine_ajax.ajaxurl,
                    data : {
                        action      : 'filter_press_posts',
                        publication : publication
                    },
                }).done(function( response ) {

                    console.log( response );
                    if( response.success === false || response.data === undefined || !response.data.markup ){
                        window.location = filterUrl;
                        return false;
                    }

                    _this.$pressloop_container.html( response.data.markup ).slideDown( TIMING );
                    
                }).fail(function( error ) {
                    console.error("500 error...bypassing the ajax call.");
                    window.location = filterUrl;
                });
                
            });
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
        return Pressloop;

    })();




    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){
        $('.pressloop').each( function(){
            var $this     = $(this),
                pressloop = $this.data('pressloop');

            if( !( pressloop instanceof Pressloop )){
                $this.data('pressloop', new Pressloop( this ));
            }
        });
    });

})( window.DUVINE = window.DUVINE || {}, jQuery );