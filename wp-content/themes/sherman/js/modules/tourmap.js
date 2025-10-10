// -----------------------------------------------
// --Tourmap
//
// Functionality for the tour map
//
;(function( DUVINE, $, undefined ){

    window.triggerGoogleLoaded = function(){
        $(window).trigger('tourmap.googleloaded');
    }


    var mapstyle = [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"visibility":"on"}]},{"featureType":"administrative.country","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative.country","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#4c4c4c"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#ededed"}]},{"featureType":"landscape","elementType":"labels.text.fill","stylers":[{"color":"#4c4c4c"}]},{"featureType":"landscape.natural.terrain","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry.fill","stylers":[{"gamma":"9.34"},{"weight":"0.01"},{"lightness":"0"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"saturation":"0"}]},{"featureType":"water","elementType":"labels.text","stylers":[{"color":"#ffffff"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"visibility":"on"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"saturation":"0"}]}];

    /**
     * loads the googles
     */
    function loadGoogleMaps(){
        var script_tag = document.createElement('script'),
            apiKey     = 'AIzaSyCkWXdm7lkeOIl233uZHBVtRWEU297FUNI';
        script_tag.setAttribute("type","text/javascript");
        script_tag.setAttribute("src","https://maps.googleapis.com/maps/api/js?key=" + apiKey + "&callback=triggerGoogleLoaded");
        (document.getElementsByTagName("body")[0] || document.documentElement).appendChild(script_tag);
    }


    var Tourmap = (function(){

        function Tourmap( el ){
            this.el = el;
            this.$el = $(el);
            this.center = this.$el.data('center');
            this.points = this.$el.siblings('.mappoints').find('.mappoint');

            this.map = null;


            // load
            $(window).on('tourmap.googleloaded', $.proxy( this.initialize, this ) );

            if( typeof google === "undefined" || typeof google.maps === "undefined" ){
                loadGoogleMaps();
            } else {
                window.triggerGoogleLoaded();
            }

            return this;
        }



        /**
         * init the map
         */
        Tourmap.prototype.initialize = function(){
            console.log( "loaded" );

            var mapCenter = this.center ? new google.maps.LatLng(this.center[0], this.center[1]) : new google.maps.LatLng(42.3442913,-71.1008899);

            var mapOptions = {
                center             : mapCenter,
                scrollwheel        : false,
                zoom               : 14,
                maxZoom            : 16,
                zoomControlOptions : {
                    style: google.maps.ZoomControlStyle.SMALL
                },
                styles : mapstyle
            };

            this.bounds = new google.maps.LatLngBounds();
            this.markers = [];

            this.map = new google.maps.Map( this.el, mapOptions );

            this.add_points();

            google.maps.event.addListenerOnce( this.map, 'bounds_changed', function(event) {
                if (this.getZoom() > 8) {
                    this.setZoom(8);
                }
            });
        }



        /**
         * Add the points to the map
         */
        Tourmap.prototype.add_points = function(){
            if( this.points.length === 0 ){
                return false;
            }

            var _this = this,
                i     = 1;

            this.points.each(function(){
                var point  = $(this).data('point'),
                    lat    = point[0],
                    lng    = point[1],
                    latLng = new google.maps.LatLng( lat, lng );

                _this.bounds.extend( latLng );
                _this.create_marker( latLng, i );

                i++;
            });

            this.map.fitBounds( this.bounds, 1 );
        }



        /**
         * Creates a marker for the point
         *
         * @param google.maps.LatLng  latLng  The lat/lng to place the marker
         */
        Tourmap.prototype.create_marker = function( latLng, index ){

            var markerIndex = index ? index + '' : '';
            var marker = new google.maps.Marker({
                position    : latLng,
                map         : this.map,
                icon        : {
                    url         :'/wp-content/themes/sherman/img/mapmarker.png',
                    size        : new google.maps.Size( 30, 56 ),
                    labelOrigin : new google.maps.Point(15, 15)
                },
                label       : markerIndex
            });
        }




        return Tourmap;
    })();





    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){
        
        var tourmapEl = document.getElementById('d-tourmap');

        if( ! tourmapEl ){
            return false;
            
        }

        var $tourmap = $( tourmapEl ),
            tourmap  = $tourmap.data('tourmap');

        if( !( tourmap instanceof Tourmap )){
            $tourmap.data('tourmap', new Tourmap( tourmapEl ));
        }

    });

})( window.DUVINE = window.DUVINE || {}, jQuery );

