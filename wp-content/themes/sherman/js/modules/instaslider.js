// -----------------------------------------------
// --Instaslider
//
// The functionality for the instaslider
//
;(function( DUVINE, $, undefined ){

    var Instaslider = (function(){

        var

            CAN_AJAX = typeof duvine_ajax === 'object' && duvine_ajax.ajaxurl,

            PREVARROW = '<span class="sk-slider-nav nav-previous"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 612 792"><polyline points="427.9,646.4 168.2,389.4 427.9,129.7 "/></svg></span>',
            NEXTARROW = '<span class="sk-slider-nav nav-next"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 612 792"><polyline points="168.2,129.7 427.9,386.7 168.2,646.4 "/></svg></span>',

            TIMING = 600;

        


        // ---------------
        // PUBLIC API
        //



        /**
         * init the things
         */
        function Instaslider( el ){
            this.el = el;
            this.$el = $(el);

            this.instaTarget = this.$el.data('id') || 'duvine-instagram';
            this.$sliderContainer = this.$el.find('#' + this.instaTarget);
            this.instaTag = this.$el.attr('data-tag') || '';
            this.taggedCount = 0;
            
            if( this.$sliderContainer.length === 0 || this.$sliderContainer.hasClass('insta__list--fromcache') ){
                this.init_carousel();
                return false;
            }

            // instafeed keys
            this.userId = 775430103450959;
            this.clientId = '243a5ddf0e709b9bd51f2afacb190175';
            //this.accessToken = '25164000.1677ed0.8ddb17265af143258efde82bf5c5df14';
            //this.accessToken = '25164000.1677ed0.93e16ef4001844baa7172179ab5158d8';
            this.accessToken = 'IGQWRQV3R5MDdkSXhzNlpNSzBtOW4zUWhmYnZAxUk5KcXlNZADE2TGk3b3pDdlgxY2FfRFltajlmT19nbFZA0SHFvTnZA3SjhhR3h2M2h2Q1FhVDRkNUZANcmQxVE04ODhKZAmZAxb1ZAIMnFlajFaeF9zS01GT2sxWVZApSHMZD';


            // build
            this.build();
        }




        //
        // PROTOTYPE
        //


        /**
         * Build it
         */
        Instaslider.prototype.build = function(){

            var _this = this;

            var imgs = [];

            var tag = this.instaTag;

            var taggedCount = this.taggedCount;

            if( tag != 'duvine' && tag.length > 0) {

                console.log('looking for tag: '+tag);
                var tag = this.instaTag;
                var feed = new Instafeed({
                    get         : 'user',
                    userId      : this.userId,
                    clientId    : this.clientId,
                    accessToken : 'IGQWRQV3R5MDdkSXhzNlpNSzBtOW4zUWhmYnZAxUk5KcXlNZADE2TGk3b3pDdlgxY2FfRFltajlmT19nbFZA0SHFvTnZA3SjhhR3h2M2h2Q1FhVDRkNUZANcmQxVE04ODhKZAmZAxb1ZAIMnFlajFaeF9zS01GT2sxWVZApSHMZD',
                    sortBy      : 'most-recent',
                    // resolution  : 'thumbnail',
                    resolution  : 'standard_resolution',
                    limit       : 150, /* increase to 60 for tags */
                    template    : '<li class="insta__post"><a class="insta__postimg" href="{{link}}" target="_blank" style="background-image: url({{image}})"></a></li>',
                    target      : this.instaTarget,
                    filter: function(image) {
                        //console.log('tag: '+image.tags.indexOf(tag));
                        if( image.tags.indexOf(tag) >=0 ) {
                            ++taggedCount;
                        }
                        return image.tags.indexOf(tag) >= 0;
                    },
                    after       : function () {

                        console.log('taggedCount: '+taggedCount);
                        if( taggedCount < 1 && this.hasNext() ) {
                            console.log('hasNext!!!');
                            this.next();
                            return false;
                        } else {
                            console.log('no hasNext!!!');
                        }

                        if( !CAN_AJAX ){
                            _this.init_carousel();
                            return false;
                        }

                        var markup = $.trim( _this.$sliderContainer.html() );

                        _this.init_carousel();
  
                        $.ajax({
                            url  : duvine_ajax.ajaxurl,
                            type : 'POST',
                            data : {
                                action : 'save_instagram_markup',
                                markup : encodeURIComponent( markup ),
                            }
                        }).done( function(){
                            console.log("Instagram tag data cached.");
                        } ).fail( function ( jqXHR, textStatus, error ) {
                            console.error("error");
                            console.error( error );
                        } );
                    }

                });

                this.taggedCount = taggedCount;

            } else {

                console.log('this is duvine');
                var feed = new Instafeed({
                    get         : 'user',
                    userId      : "775430103450959",
                    clientId    : "243a5ddf0e709b9bd51f2afacb190175",
                    accessToken : 'IGQWRQV3R5MDdkSXhzNlpNSzBtOW4zUWhmYnZAxUk5KcXlNZADE2TGk3b3pDdlgxY2FfRFltajlmT19nbFZA0SHFvTnZA3SjhhR3h2M2h2Q1FhVDRkNUZANcmQxVE04ODhKZAmZAxb1ZAIMnFlajFaeF9zS01GT2sxWVZApSHMZD',
                    sortBy      : 'none',
                    // resolution  : 'thumbnail',
                    resolution  : 'standard_resolution',
                    limit       : 8,
                    template    : '<li class="insta__post"><a class="insta__postimg" href="{{link}}" target="_blank" style="background-image: url({{image}})"></a></li>',
                    target      : this.instaTarget,
                    /*filter: function(image) {
                        return image.tags.indexOf('duvineapresvelo') >= 0;
                    },*/
                    after       : function () {

                        if( !CAN_AJAX ){
                            _this.init_carousel();
                            return false;
                        }

                        var markup = $.trim( _this.$sliderContainer.html() );

                        _this.init_carousel();

                        $.ajax({
                            url  : duvine_ajax.ajaxurl,
                            type : 'POST',
                            data : {
                                action : 'save_instagram_markup',
                                markup : encodeURIComponent( markup ),
                            }
                        }).done( function(){
                            console.log("Instagram data cached.");
                        } ).fail( function ( jqXHR, textStatus, error ) {
                            console.error("error");
                            console.error( error );
                        } );
                    }

                });
            }

            feed.run();
        }




        /**
         * Inits the carousel
         */
        Instaslider.prototype.init_carousel = function(){

            if( typeof $().slick !== 'function' ){
                console.error( "Slick is not initialized." );
                return false;
            }

            var slidesToShow = 6;
            var centerMode = true;

            if( this.instaTag != '' && this.taggedCount < 6 ) {
                slidesToShow = this.taggedCount;
                centerMode = false;
            }

            this.$sliderContainer.slick({
                centerMode    : centerMode,
                centerPadding : '60px',
                slidesToShow  : slidesToShow,
                prevArrow     : PREVARROW,
                nextArrow     : NEXTARROW,
                responsive    : [{
                   breakpoint : 1780,
                   settings   : {
                       slidesToShow: 5
                   } 
                },{
                    breakpoint : 1060,
                    settings   : {
                        slidesToShow: 4,
                        centerMode: true
                    }
                },{
                    breakpoint : 800,
                    settings   : {
                        slidesToShow: 3,
                        centerMode: true
                    }
                }, {
                    breakpoint : 560,
                    settings   : {
                        slidesToShow: 2,
                        centerMode: true
                    }
                }, {
                    breakpoint : 420,
                    settings   : {
                        slidesToShow: 1,
                        centerMode: true
                    }
                }]
            });
        }



        return Instaslider;

    })();




    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){
        $('.instaslider').each( function(){
            var $this         = $(this),
                instaslider = $this.data('instaslider');

            if( !(instaslider instanceof Instaslider ) ){
                $this.data('instaslider', new Instaslider( this ) );
            }
        });
    });

})( window.DUVINE = window.DUVINE || {}, jQuery );