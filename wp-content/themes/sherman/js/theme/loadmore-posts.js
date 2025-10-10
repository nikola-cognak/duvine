
jQuery( document ).ready( function( $ ) {


    var TIMING = 600;

    $('.loadmore__container').each(function(){
        var $container    = $(this),
            $postlist     = $container.find('.loadmore__mainposts'),
            $loadmores    = $container.find('.js-loadmore-posts'),
            $error        = $container.find('.load-more-posts-error');

        if( $postlist.length === 0 ){
            return false;
        }

        var mainpostClass = $postlist.attr('class').replace('loadmore__mainposts', '');

        $loadmores.on('click', function(event) {
            event.preventDefault();

            var $button       = $(this),
                previouspaged = $button.attr( 'data-paged' ),
                currentpaged  = parseInt( previouspaged ) + 1,
                $spinner      = $( '<ul class="cta__loadingspinner"><li></li><li></li><li></li></ul>').appendTo( $button );

            setTimeout(function(){
                $button.addClass('cta--loading');
            }, 50);
            
            $.ajax({
                type: 'POST',
                url: wp_ajax_url,
                data: {
                    action  : 'load_more_posts',
                    paged   : currentpaged,
                    context : $(this).attr( 'data-context' ),
                    query   : load_more_data.query,
                    nonce   : $( '#load-more-posts-nonce' ).val(),
                },
                dataType: 'html'
            }).done( function( response ) {
                if ( response ) {

                    var $newPosts = $('<div class="loadmore__newposts" style="display:none;"></ul>').addClass(mainpostClass);

                    $newPosts.append( response ).insertAfter( $postlist );

                    setTimeout( function(){
                        $newPosts.slideDown( TIMING );
                        $spinner.remove();
                        $button.removeClass('cta--loading');
                    }, 20);

                    $postlist = $newPosts;

                    if ( currentpaged >= parseInt( $button.attr( 'data-max-pages' ) ) ) {
                        $button.hide();
                    } else {
                        $button.attr( 'data-paged', currentpaged );
                    }       
                } else {
                    $button.hide();
                    $error.show();
                }
            } );
        });
    });


});