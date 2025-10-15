//
// namespace - DUVINE
//

(function(DUVINE, $, undefined){


    var CAN_AJAX = typeof duvine_ajax === 'object' && duvine_ajax.ajaxurl;


    /* --------------------------------------------
     * --util
     * -------------------------------------------- */


    /**  
     * check for mobile
     */
    function is_lt_mobile(){
        return $(window).width() <= 580;
    }

    /**
     * check for mobile
     */
    function is_lt_tablet(){
        return $(window).width() <= 860;
    }

    /**
     * Creates and returns a new, throttled version of the passed
     *  function, that, when invoked repeatedly, will only actually
     *  call the original function at most once per every 'threshold'
     *  milliseconds. Useful for rate-limiting events that occur
     *  faster than you can keep up with.
     * @link - https://remysharp.com/2010/07/21/throttling-function-calls
     */
    function throttle(fn, threshhold, scope) {
        threshhold || (threshhold = 250);
        var last, deferTimer;
        return function () {
            var context = scope || this;

            var now = +new Date,
                args = arguments;
            if (last && now < last + threshhold) {
                // hold on to it
                clearTimeout(deferTimer);
                deferTimer = setTimeout(function () {
                    last = now;
                    fn.apply(context, args);
                }, threshhold);
            } else {
                last = now;
                fn.apply(context, args);
            }
        };
    }





    /* --------------------------------------------
     * --func
     * -------------------------------------------- */


    /**
     * setup for the main nav
     */
    function setup_main_nav(){

        var $mainnav = $('#menu-main-nav');


        // set up the minimum height of the supernav, since the height is
        //  really determined by an absolutely positioned element
        var minheight = 0;
        $mainnav.find('.tourmenu__countrywrapper').each(function(){
            var h = $(this).outerHeight();

            if( h > minheight ){
                minheight = h;
            }
        }).closest('.tournav__column').css( 'minHeight', minheight + 'px' );


        //
        // Sets up the subnavigation left position, but needs to make sure tha
        //  the webfont is loaded correctly so we have the right widths. So,
        //  compare the first link width in an interval to determine when the
        //  font gets loaded.
        //
        var $firstlink = $mainnav.find('a').eq(0),
            initWidth  = $firstlink.width(),
            $menuItems = $mainnav.children('.menu-item');

        var checkerCount = 0,
            fontchecker;

        /**
         * Moves the subnavs
         */
        function move_subnavs(){
            var checkWidth = $firstlink.width();

            if( checkerCount > 5 || checkWidth !== initWidth ){
                clearInterval(fontchecker);
            }

            $menuItems.each(function(){
                var $li      = $(this),
                    $submenu = $li.find('ul.sub-menu'),
                    liLeft   = parseInt( $li.css('paddingLeft') ) + $li.offset().left;

                if( $submenu.length > 0 ){

                    $submenu.css('paddingLeft', liLeft + 'px');
                }
            });

            checkerCount++;
        }
        move_subnavs();
        fontchecker = setInterval( move_subnavs, 50 );
    }




    /**
     * Site setup
     */
    function general_setup(){

        // if none of the tour dates have special events, hide that column
        $('.dateblock__year').each(function(){
            var $block     = $(this),
                eventCount = 0;

            $block.find('.dateblock__row:not(.dateblock__row--header) .dateblock__cell--events').each(function(){
                var $row = $(this);

                if( $row.text() !== '' ){
                    eventCount = 1;
                    return false;
                }
            });

            if( eventCount === 0 ){
                $block.addClass('dateblock--hide-events');
            }
        });


        if( !is_lt_tablet() ){
            // location page hero
            $('.sublocations--setminheight').each(function(){
                var $this     = $(this),
                    $heroImg  = $this.siblings('.location__heroimage'),
                    $hero     = $this.closest('.location__hero'),
                    subHeight = $this.outerHeight() + 80;

                $heroImg.add( $hero ).css('minHeight', subHeight + 'px');

            });
        }
    }






    /**
     * General UX things and event listeners
     */
    function generalUX(){

        // engage the chosen plugin
        $('.js-duvine-chosen').each(function(){
            var $select       = $(this),
                notSearchable = $select.data('search') ? false : true,
                noResults     = $select.data('noresults') || "No results for ";

            if( $select.hasClass('more-filters') ) {
                $($select).on("chosen:ready", set_more_filters_checkboxes($select) );
            }

            var $chosen = $select.chosen({
                width           : '100%',
                disable_search  : notSearchable,
                no_results_text : noResults,
                search_contains: true,
                enable_split_word_search: true
            });

            // Stay open on multiselect - from https://github.com/harvesthq/chosen/issues/1546
            var chosen = $chosen.data("chosen");
            if( (typeof chosen === "object") && (chosen !== null) ) {
                var _fn = chosen.result_select;
                chosen.result_select = function(evt) {    
                    evt["metaKey"] = true;
                    evt["ctrlKey"] = true;
                    chosen.result_highlight.addClass("result-selected");
                    return _fn.call(chosen, evt);
                };
            }
        });
        


        // in the supernav, hovering on a continent opens the country list for
        //  that continent
        $('.tourmenu__continent').on('mouseover', function(event) {
            event.preventDefault();
            $(this).addClass('tourmenu__hoverable--active').siblings('.tourmenu__hoverable--active').removeClass('tourmenu__hoverable--active');
        });


        // mobile open the region location
        $('.sublocations__title').on('click', function(event) {
            event.preventDefault();
            if( is_lt_tablet() ){
                console.log('title -- lt');
                $(this).toggleClass('active').siblings('.locationkids-wrapper').slideToggle();
                $(this).siblings('.locationkids-wrapper').css('display','block');
            }
        });
    }

    function set_more_filters_checkboxes( dropdown ) {
        $(document).on("click",".psuedo-checkbox",function(){
            if( $(this).hasClass('result-selected') ) {
                array_text = $(this).text();
                $(this).addClass('active-result').removeClass('result-selected');
                $('.more-filters option:contains(' + array_text + ')').prop("selected", false);
                $('.more-filters').trigger('chosen:updated');
            }
        });
    }





    /**
     * Opens the search modal
     */
    function trigger_search(){
        var $searchmodal = $('#duvine-searchmodal'),
            search_shown = false;

        // open the search modal
        $('.triggersearch').on('click', function(event) {
            event.preventDefault();
            $(this).toggleClass('triggersearch--activated');
            $searchmodal.toggleClass('searchmodal--active');
            search_shown = !search_shown;
            if( search_shown ){
                setTimeout( function(){
                    $searchmodal.find('input[type="search"]').trigger('focus');
                }, 600);
            }
        });
        $searchmodal.on('click', function (event){
            if (event.target.id === 'duvine-searchmodal') {
                $('.triggersearch').click();
            }
        });
        // $('.closesearch').on('click', function(event) {
        //     event.preventDefault();
        //     search_shown = false;
        //     $searchmodal.removeClass('searchmodal--active');
        // });
    }





    /**
     * Set Hubspot labels to be input placeholders
     */
    function newsletterSetup(){
        var newsletterTimeout = setInterval(function(){
            var $newsletterInputs = $(".footer-newsletter__form .hs-form-field");
            if($newsletterInputs.length > 0) {
                // Form has been loaded, apply placeholders
                $newsletterInputs.each(function(i){
                    var $this     = $(this),
                        labelText = $this.find("label span:first-child").text(),
                        $input    = $this.find("input"),
                        $label    = $this.children('label').appendTo( $this );
                    
                    // $input.attr("placeholder", labelText);
                });
                
                clearTimeout(newsletterTimeout);
            }
        }, 10);

        let newsletterCookie = Cookies.get('duvine-newsletter');
        let isShowPopup = window.OneTrust ? window.OneTrust.IsAlertBoxClosed() : true;
        var timeoutLength = 20000;

        if (newsletterCookie != '1' && isShowPopup) {
            if (localStorage.getItem('startTime') === null) {
                localStorage.setItem('startTime', Date.now());
            }
            var newsletterPopUp = setInterval(function(){
                let currentTime = Date.now();
                if (currentTime > parseInt(localStorage.getItem('startTime')) + timeoutLength) {
                    localStorage.removeItem('startTime');
                    $('#duvine-newslettermodal').toggleClass('newslettermodal--active');
                    clearTimeout(newsletterPopUp);
                }
            }, 1000);
        }

        $('#duvine-newslettermodal .close').on('click', function(event){
            event.preventDefault();
            $('#duvine-newslettermodal').fadeOut();

            Cookies.set('duvine-newsletter', '1', { expires: 90 });
        });

        if (newsletterCookie != '1' && !isShowPopup && window.OneTrust) {
            window.OneTrust.OnConsentChanged(function () {
                newsletterSetup();
            })
        }
    }


    /**
     * Init the tooltips
     */
    function toolTipsSetup(){
        $('.tooltip').tooltipster({
            maxWidth           : 280,
            animation          : 'fade',
            delay              : 200,
            repositionOnScroll : true,
            interactive        : true,
            delayTouch         : 300,
            zIndex             : 10,
            multiple           :true,
            // trigger            : 'click'
            trigger            : 'custom',
            triggerOpen        : { mouseenter: true, touchstart: true },
            triggerClose       : { mouseleave: true, tap: true }
        });

        /*$('.tooltip-tour-calendar').tooltipster({
            contentAsHTML      : true,
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
        });*/

        $('body').on('mouseenter', '.tooltip:not(.tooltipstered)', function(){
            $(this)
                .tooltipster({
                    maxWidth           : 280,
                    animation          : 'fade',
                    delay              : 200,
                    repositionOnScroll : true,
                    interactive        : true,
                    delayTouch         : 300,
                    zIndex             : 10,
                    multiple           :true,
                    // trigger            : 'click'
                    trigger            : 'custom',
                    triggerOpen        : { mouseenter: true, touchstart: true },
                    triggerClose       : { mouseleave: true, tap: true }
                })
                .tooltipster('show');
        });
    }


    function matchSquareRectangle() {

        $square_height = $('.tourgallery').find('.tourgallery__image--square img').first().css('height', 'auto').height();

        $('.tourgallery').find('.tourgallery__image--rectangle img').css('height', $square_height + 'px');
        $('.tourgallery').find('.tourgallery__image--square img').css('height', $square_height + 'px');
    }


    function addEllipsis() {
        $('.dateslider__monthblock').each(function(){
            $(this).on('click', function(){

                if($('.chosen-date').find('.search-choice').length > 1) {
                    $('.chosen-date').addClass('ellipse');
                } else {
                    $('.chosen-date').removeClass('ellipse');
                }
            });
        });

        // $('.chosen-container .chosen-results li').each(function(){
        //     $(this).on('click', function(){
        //         if($(this).closest('.chosen-container').find('.search-choice').length > 1) {
        //             $(this).closest('.chosen-container').find('.chosen-choices').addClass('ellipse');
        //             } else {
        //                 $(this).closest('.chosen-container').find('.chosen-choices').removeClass('ellipse');
        //             }
        //     });
        // });
    }

    function mobileHeader() {
        if ($('body').hasClass("home") && $(window).width() > 856) {
            $video = $('.bannerhero video');
           $source = $('<source src="' + $video.attr("data") + '" type="video/mp4">');
           $source.appendTo($video);
           $video.addClass("is--active");
        }

        $('.menu-toggle').on('click', function(event) {
            event.preventDefault();
            $(this).toggleClass('active');
        });
    }

    function setMobileHeader() {
        if( is_lt_mobile() ) {
            if( $(document).scrollTop() > 100 ) {
                $('body').addClass("scrolling");
                // remove utility nav from header and put in mobile nav
                //$('.utilitynav').fadeOut(100);
                $('.utilitynav').prependTo('.mobilenav__mainpane');
            } else {
                $('body').removeClass("scrolling");
                $('.utilitynav').fadeIn(400);
                $('.utilitynav').insertAfter('.searchnav');
            }
        } else {
            $('body').removeClass("scrolling");
            $('.utilitynav').insertAfter('.searchnav').fadeIn(400);
        }
    }

    function setRegionsInPageNav() {
        if( is_lt_tablet() && !$('.sublocations__title').hasClass('active') ) {
            $('.locationkids-wrapper').hide();
        } else if( is_lt_tablet() && $('.sublocations__title').hasClass('active') ) {
            $('.locationkids-wrapper').css('display', 'block');
        } else if( !is_lt_tablet() &&  !is_lt_mobile() ) {
            $('.locationkids-wrapper').css('display', 'flex');
            $('.sublocations__title').removeClass('active');
        }
    }

    function anchorLinkSelect() {
        $('#main .anchor-links').each(function(){

            var $select = '<span>JUMP TO</span>';

            $select += '<select class="anchor-links-select">';

            $select += '<option value="">Select a category</option>';

            $(this).find('.anchor-link').each(function(){
                $select += '<option';

                    $select += (' value=' + $(this).attr('href'));

                    $select += ('>' + $(this).text());

                $select += '</option>';
            });

            $select += '</select>';

            $(this).append($select);

            $(this).find('select').on('change', function(){
                window.location.hash = $(this).val();
            });

        });
    }

    function removeStyleFromLinkedImages() {
        $("a:has(img)").addClass('linked-image');
    }

    // Safari video controls are displaying below the _featured_blog_posts block
    function setVideoForSafari() {
        if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1)  { 
           $('#vid1').css("height", "inherit");
        }
    }

    // this will play video in slick slider when active slide
    function setVideoForCustomSlider() {
        $('.featuredblogs__slider.slick-slider').on('afterChange', function(event, slick, currentSlide){
          if( $('.featuredblog__slide.slick-active #vid1').length ) {
            $('.featuredblog__slide.slick-active #vid1').get(0).play();
          } else {
              $('.featuredblog__slide #vid1').get(0).pause();    
          }
        });
    }


    // set up date range picker in Find Tour
    function setDateRangePicker() {
        console.log('setDateRangePicker')
        $('.chosen-date').dateRangePicker({
            inline:true,
            container: '.dateslider__range',
            alwaysOpen:true,
            singleMonth: true,
            showTopbar: false,
            format: 'MMM D, YYYY',

            getValue: function()
            {
                return this.innerHTML;
            },
            setValue: function(s)
            {
                this.innerHTML = s;
            }
        })
        .bind('datepicker-change',function(event,obj) {
            /* This event will be triggered when second date is selected */
            event.preventDefault();
            console.log(obj);
            console.log(obj.date1);

            // grab the val and place inside container in chosen format
            var date_range = obj.value;

            var html = '<li class="search-field"><span class="chosen-date-placeholder">DATES</span></li>';
            html = html + '<li class="search-choice" data-label="May 2019"><span>'+date_range+'</span><a class="search-choice-close"></a></li>';
            $("ul.chosen-date").empty().append(html);





            // obj will be something like this:
            // {
            //      date1: (Date object of the earlier date),
            //      date2: (Date object of the later date),
            //      value: "2013-06-05 to 2013-06-07"
            // }
        });
    }

    function setCopyLinkToClipboard() {
        

        $('.copy-link-to-clipboard').each(function(i, el){

        var $el = $(el),
            clipboard = new ClipboardJS(el),
            instance = $el
                .tooltipster({
                    content: $el.attr('data-copy'),
                    maxWidth           : 280,
                    animation          : 'fade',
                    delay              : 200,
                    repositionOnScroll : true,
                    interactive        : true,
                    delayTouch         : 300,
                    zIndex             : 10,
                    trigger: 'custom',
                    triggerClose: {
                        mouseleave: true,
                        touchleave: true,
                        tap: true
                    },
                    triggerOpen: {
                        click: true,
                        tap: true,
                        touchstart: true
                    }
                })
                .tooltipster('instance');

        clipboard
            .on('success', function(e) {
                instance
                    .content($el.attr('data-copied'))
                    .one('after', function(){
                        instance.content($el.attr('data-copy'));
                    });
            })
            .on('error', function(e) {
                instance
                    .content($el.attr('data-copyerror'))
                    .one('after', function(){
                        instance.content($el.attr('data-copy'));
                    });
            });
    });


    }

    function setTemperatureConversion() {
        // convert between farenheit and celcius on the tour page weather widget
        $('.weather-degree-toggle a').on('click', function(event) {
            event.preventDefault();
            var current_measurement = $('.weather-degree-toggle a span').text();
            
            if( current_measurement == 'C' ) {
                $('.weather-degree-toggle a span').text('F');
                $('.cell.mobile-header.temp span').text('C');
                $('.cell.mobile-header.rain span').text('mm');
                $('.weather-table .cell.temperature span').each(function( index ) {
                  var current_temp = $( this ).text();
                  var converted_temp = ((current_temp - 32) * (5/9)).toFixed(0);
                  $( this ).text(converted_temp);
                });
                $('.weather-table .cell.rain').each(function( index ) {
                  $(this).find(".inches").css("display","none");
                  $(this).find(".mm").css("display","block");
                });
            } else if( current_measurement == 'F' ) {
                $('.weather-degree-toggle a span').text('C');
                $('.cell.mobile-header.temp span').text('F');
                $('.cell.mobile-header.rain span').text('in');
                $('.weather-table .cell.temperature span').each(function( index ) {
                  var current_temp = $( this ).text();
                  var converted_temp = ((current_temp * 9/5) + 32).toFixed(0);
                  $( this ).text(converted_temp);
                });
                $('.weather-table .cell.rain').each(function( index ) {
                  $(this).find(".mm").css("display","none");
                  $(this).find(".inches").css("display","block");
                });
            }
        });

    }

    function changeYearGlanceFilter() {

        $('#glance-apply').on('click', function(){
            var q = '?gyear=' + $('#glance-year').val() + '&glevel=' + $('#glance-level').val() + '&tourtype=' + $('input[name=tourtype]:checked').val();
            var current_url=window.location.href.split('?')[0];
            window.location = current_url+q;
        });
    }
    function setPrivateTourAccordion() {
        if( $(window).width() > 580 ) {
            if( !$('.private-planning-form .accordion__header').hasClass('active') ) {
                $('.private-planning-form .accordion__header').trigger('click');
                return;
            }
        }
    }

    function handleMoreFiltersCheckbox() {
        $('.filtercell--morefilters .result-selected').on('click', function(){
            console.log('clicked');
        });
    }






    /* --------------------------------------------
     * --public
     * -------------------------------------------- */

    //
    // init the things
    //
    DUVINE.init = function(){

        general_setup();

        generalUX();

        trigger_search();

        toolTipsSetup();

        setup_main_nav();

        addEllipsis();

        anchorLinkSelect();

        mobileHeader();

        setVideoForSafari();

        setVideoForCustomSlider();


        //setDateRangePicker();

        setCopyLinkToClipboard();

        setTemperatureConversion();

        changeYearGlanceFilter();


        if ($("#myChosenField").length) {
          var chosen = $chosen.data("chosen");
          var _fn = chosen.result_select;
          chosen.result_select = function(evt) {    
          evt["metaKey"] = true;
          evt["ctrlKey"] = true;
          chosen.result_highlight.addClass("result-selected");
          return _fn.call(chosen, evt);
          };
        }

        $(window).load(function(){
            newsletterSetup();
            matchSquareRectangle();
            setMobileHeader();
            removeStyleFromLinkedImages();
            setRegionsInPageNav();
            setPrivateTourAccordion();
        });

        $(window).resize(function() {
            matchSquareRectangle(); 
            setMobileHeader();
            setRegionsInPageNav();
        });

        $(document).on("scroll", function() {
            setMobileHeader(); 
        });

    }


    // -------------------------------
    // DOM ready
    //
    $(document).ready(function(){
        DUVINE.init();
    });


})(window.DUVINE = window.DUVINE || {}, jQuery);