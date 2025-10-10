/*!
 * jQuery Tools v1.2.7 - The missing UI library for the Web
 *
 * overlay/overlay.js
 * toolbox/toolbox.expose.js
 *
 * NO COPYRIGHTS OR LICENSES. DO WHAT YOU LIKE.
 *
 * http://flowplayer.org/tools/
 *
 */
(function(a){a.tools=a.tools||{version:"v1.2.7"},a.tools.overlay={addEffect:function(a,b,d){c[a]=[b,d]},conf:{close:null,closeOnClick:!0,closeOnEsc:!0,closeSpeed:"fast",effect:"default",fixed:!a.browser.msie||a.browser.version>6,left:"center",load:!1,mask:null,oneInstance:!0,speed:"normal",target:null,top:"10%"}};var b=[],c={};a.tools.overlay.addEffect("default",function(b,c){var d=this.getConf(),e=a(window);d.fixed||(b.top+=e.scrollTop(),b.left+=e.scrollLeft()),b.position=d.fixed?"fixed":"absolute",this.getOverlay().css(b).fadeIn(d.speed,c)},function(a){this.getOverlay().fadeOut(this.getConf().closeSpeed,a)});function d(d,e){var f=this,g=d.add(f),h=a(window),i,j,k,l=a.tools.expose&&(e.mask||e.expose),m=Math.random().toString().slice(10);l&&(typeof l=="string"&&(l={color:l}),l.closeOnClick=l.closeOnEsc=!1);var n=e.target||d.attr("rel");j=n?a(n):null||d;if(!j.length)throw"Could not find Overlay: "+n;d&&d.index(j)==-1&&d.click(function(a){f.load(a);return a.preventDefault()}),a.extend(f,{load:function(d){if(f.isOpened())return f;var i=c[e.effect];if(!i)throw"Overlay: cannot find effect : \""+e.effect+"\"";e.oneInstance&&a.each(b,function(){this.close(d)}),d=d||a.Event(),d.type="onBeforeLoad",g.trigger(d);if(d.isDefaultPrevented())return f;k=!0,l&&a(j).expose(l);var n=e.top,o=e.left,p=j.outerWidth(true),q=j.outerHeight(true);typeof n=="string"&&(n=n=="center"?Math.max((h.height()-q)/2,0):parseInt(n,10)/100*h.height()),o=="center"&&(o=Math.max((h.width()-p)/2,0)),i[0].call(f,{top:n,left:o},function(){k&&(d.type="onLoad",g.trigger(d))}),l&&e.closeOnClick&&a.mask.getMask().one("click",f.close),e.closeOnClick&&a(document).on("click."+m,function(b){a(b.target).parents(j).length||f.close(b)}),e.closeOnEsc&&a(document).on("keydown."+m,function(a){a.keyCode==27&&f.close(a)});return f},close:function(b){if(!f.isOpened())return f;b=b||a.Event(),b.type="onBeforeClose",g.trigger(b);if(!b.isDefaultPrevented()){k=!1,c[e.effect][1].call(f,function(){b.type="onClose",g.trigger(b)}),a(document).off("click."+m+" keydown."+m),l&&a.mask.close();return f}},getOverlay:function(){return j},getTrigger:function(){return d},getClosers:function(){return i},isOpened:function(){return k},getConf:function(){return e}}),a.each("onBeforeLoad,onStart,onLoad,onBeforeClose,onClose".split(","),function(b,c){a.isFunction(e[c])&&a(f).on(c,e[c]),f[c]=function(b){b&&a(f).on(c,b);return f}}),i=j.find(e.close||".close"),!i.length&&!e.close&&(i=a("<a class=\"close\"></a>"),j.prepend(i)),i.click(function(a){f.close(a)}),e.load&&f.load()}a.fn.overlay=function(c){var e=this.data("overlay");if(e)return e;a.isFunction(c)&&(c={onBeforeLoad:c}),c=a.extend(!0,{},a.tools.overlay.conf,c),this.each(function(){e=new d(a(this),c),b.push(e),a(this).data("overlay",e)});return c.api?e:this}})(jQuery);
(function(a){a.tools=a.tools||{version:"v1.2.7"};var b;b=a.tools.expose={conf:{maskId:"exposeMask",loadSpeed:"slow",closeSpeed:"fast",closeOnClick:!0,closeOnEsc:!0,zIndex:9998,opacity:.8,startOpacity:0,color:"#fff",onLoad:null,onClose:null}};function c(){if(a.browser.msie){var b=a(document).height(),c=a(window).height();return[window.innerWidth||document.documentElement.clientWidth||document.body.clientWidth,b-c<20?c:b]}return[a(document).width(),a(document).height()]}function d(b){if(b)return b.call(a.mask)}var e,f,g,h,i;a.mask={load:function(j,k){if(g)return this;typeof j=="string"&&(j={color:j}),j=j||h,h=j=a.extend(a.extend({},b.conf),j),e=a("#"+j.maskId),e.length||(e=a("<div/>").attr("id",j.maskId),a("body").append(e));var l=c();e.css({position:"absolute",top:0,left:0,width:l[0],height:l[1],display:"none",opacity:j.startOpacity,zIndex:j.zIndex}),j.color&&e.css("backgroundColor",j.color);if(d(j.onBeforeLoad)===!1)return this;j.closeOnEsc&&a(document).on("keydown.mask",function(b){b.keyCode==27&&a.mask.close(b)}),j.closeOnClick&&e.on("click.mask",function(b){a.mask.close(b)}),a(window).on("resize.mask",function(){a.mask.fit()}),k&&k.length&&(i=k.eq(0).css("zIndex"),a.each(k,function(){var b=a(this);/relative|absolute|fixed/i.test(b.css("position"))||b.css("position","relative")}),f=k.css({zIndex:Math.max(j.zIndex+1,i=="auto"?0:i)})),e.css({display:"block"}).fadeTo(j.loadSpeed,j.opacity,function(){a.mask.fit(),d(j.onLoad),g="full"}),g=!0;return this},close:function(){if(g){if(d(h.onBeforeClose)===!1)return this;e.fadeOut(h.closeSpeed,function(){d(h.onClose),f&&f.css({zIndex:i}),g=!1}),a(document).off("keydown.mask"),e.off("click.mask"),a(window).off("resize.mask")}return this},fit:function(){if(g){var a=c();e.css({width:a[0],height:a[1]})}},getMask:function(){return e},isLoaded:function(a){return a?g=="full":g},getConf:function(){return h},getExposed:function(){return f}},a.fn.mask=function(b){a.mask.load(b);return this},a.fn.expose=function(b){a.mask.load(b,this);return this}})(jQuery);


/*! http://mths.be/placeholder v1.8.7 by @mathias */
(function(f,h,c){var a='placeholder' in h.createElement('input'),d='placeholder' in h.createElement('textarea'),i=c.fn,j;if(a&&d){j=i.placeholder=function(){return this};j.input=j.textarea=true}else{j=i.placeholder=function(){return this.filter((a?'textarea':':input')+'[placeholder]').not('.placeholder').bind('focus.placeholder',b).bind('blur.placeholder',e).trigger('blur.placeholder').end()};j.input=a;j.textarea=d;c(function(){c(h).delegate('form','submit.placeholder',function(){var k=c('.placeholder',this).each(b);setTimeout(function(){k.each(e)},10)})});c(f).bind('unload.placeholder',function(){c('.placeholder').val('')})}function g(l){var k={},m=/^jQuery\d+$/;c.each(l.attributes,function(o,n){if(n.specified&&!m.test(n.name)){k[n.name]=n.value}});return k}function b(){var k=c(this);if(k.val()===k.attr('placeholder')&&k.hasClass('placeholder')){if(k.data('placeholder-password')){k.hide().next().show().focus().attr('id',k.removeAttr('id').data('placeholder-id'))}else{k.val('').removeClass('placeholder')}}}function e(){var o,n=c(this),k=n,m=this.id;if(n.val()===''){if(n.is(':password')){if(!n.data('placeholder-textinput')){try{o=n.clone().attr({type:'text'})}catch(l){o=c('<input>').attr(c.extend(g(this),{type:'text'}))}o.removeAttr('name').data('placeholder-password',true).data('placeholder-id',m).bind('focus.placeholder',b);n.data('placeholder-textinput',o).data('placeholder-id',m).before(o)}n=n.removeAttr('id').hide().prev().attr('id',m).show()}n.addClass('placeholder').val(n.attr('placeholder'))}else{n.removeClass('placeholder')}}}(this,document,jQuery));

/*!
 * jQuery imagesLoaded plugin v2.1.1
 * http://github.com/desandro/imagesloaded
 *
 * MIT License. by Paul Irish et al.
 */
(function(c,q){var m="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";c.fn.imagesLoaded=function(f){function n(){var b=c(j),a=c(h);d&&(h.length?d.reject(e,b,a):d.resolve(e));c.isFunction(f)&&f.call(g,e,b,a)}function p(b){k(b.target,"error"===b.type)}function k(b,a){b.src===m||-1!==c.inArray(b,l)||(l.push(b),a?h.push(b):j.push(b),c.data(b,"imagesLoaded",{isBroken:a,src:b.src}),r&&d.notifyWith(c(b),[a,e,c(j),c(h)]),e.length===l.length&&(setTimeout(n),e.unbind(".imagesLoaded",p)))}var g=this,d=c.isFunction(c.Deferred)?c.Deferred():0,r=c.isFunction(d.notify),e=g.find("img").add(g.filter("img")),l=[],j=[],h=[];c.isPlainObject(f)&&c.each(f,function(b,a){if("callback"===b)f=a;else if(d)d[b](a)});e.length?e.bind("load.imagesLoaded error.imagesLoaded",p).each(function(b,a){var d=a.src,e=c.data(a,"imagesLoaded");if(e&&e.src===d)k(a,e.isBroken);else if(a.complete&&a.naturalWidth!==q)k(a,0===a.naturalWidth||0===a.naturalHeight);else if(a.readyState||a.complete)a.src=m,a.src=d}):n();return d?d.promise(g):g}})(jQuery);

(function($){

    $(function(){

        if( $(".Overlay").length ) {

        var $overlay = $(".Overlay"),
            $overlayContent = $(".Overlay-Content", $overlay),
            $overlayLoading = $(".Overlay-Loading", $overlay),
            hasSelectBoxes = false,
            $listingSection = $(".Listing-Section"),
            hoverPageUrl = false,
            overlayOptions = {
                    close: '.Close',
                    fixed: false,
                    left: 'center',
                    mask: {color: '#fff', opacity: 0.5},
                    closeOnClick: false,
                    onBeforeLoad: function() {
                        var pageUrl = this.getTrigger().attr("href");

                        if (pageUrl.indexOf("videos") !== -1 ) {
                            $overlay.addClass('Video');
                        }
                        else {
                            $overlay.removeClass('Video');
                        }

                        //Show Loading
                        $overlayLoading.show();

                        var overlayMode = "display";
                        if (this.getTrigger().data("overlay-mode")) {
                            overlayMode = this.getTrigger().data("overlay-mode").toLowerCase();
                        }


                        //Load the page into the overlay
                        $.ajax({
                            url: pageUrl,
                            dataType:'html'
                        }).done(function( data ) {

                            $overlayContent.empty().append(data);

                            if ( pageUrl == "/schedule-a-call" ) {
                                $.getScript('/assets/js/jquery/plugins/jquery.selectBox.min.js').done(function(script, textStatus) {
                                    hasSelectBoxes = true;
                                });

                            }

                            if (overlayMode === "iframe") {

                                jQuery("form", $overlayContent).on("submit", function (event) {
                                    var form = jQuery(event.currentTarget);

                                    if (form.attr("target") && link.attr("target") != "overlay") {
                                        return;
                                    }

                                    event.preventDefault();

                                    var formValueArray = form.serializeArray();
                                    var formValueMap = {};
                                    for (var i = 0; i < formValueArray.length; i++) {
                                        formValueMap[formValueArray[i].name] = formValueArray[i].value;
                                    }
                                    formValueMap["action"] = true;
                                    $overlayContent.empty().load(form.attr("action"), formValueMap);

                                });
                            }

                            //Remove Loading screen
                            $overlayLoading.hide("fast");
                        });


                    },
                    onBeforeClose: function() {
                        //Destroy Select boxes created by pages loaded into the overlay
                        if ( hasSelectBoxes ) {
                            $(".SelectBox", $overlayContent).selectBox('destroy');
                            hasSelectBoxes = false;
                        }
                        $overlayContent.empty();
                    }
            };
        } // if Overlay

        //Placeholder Text Fallback
        $('input[placeholder], textarea[placeholder]').placeholder();

        //Overlays
        var screenSize = window.getComputedStyle(document.body,':before').getPropertyValue('content') || '';
        if( 'mediumscreen' !== screenSize ) {

            $("a.Overlay-Link").overlay(overlayOptions);

            $("a.Video-Overlay").overlay($.extend( overlayOptions, {closeOnClick: true} ));
        }


        $("a.Overlay-Hover").live("click", function(event){
                event.stopPropagation();
                event.preventDefault();
            }).live("hover",function(event){
                hoverPageUrl = $(event.currentTarget).attr("href");

                $overlay.overlay({
                    close: '.Close',
                    fixed: false,
                    left: 'center',
                    mask: {color: '#fff', opacity: 0.5},
                    closeOnClick: false,
                    load: true,
                    onBeforeLoad: function() {

                        if (hoverPageUrl.indexOf("videos") !== -1 ) {
                            $overlay.addClass('Video');
                        }
                        else {
                            $overlay.removeClass('Video');
                        }

                        //Show Loading
                        $overlayLoading.show();

                        var overlayMode = "display";
                        if (this.getTrigger().data("overlay-mode")) {
                            overlayMode = this.getTrigger().data("overlay-mode").toLowerCase();
                        }


                        //Load the page into the overlay
                        $.ajax({
                            url: hoverPageUrl,
                            dataType:'html'
                        }).done(function( data ) {

                            $overlayContent.empty().append(data);

                            if ( hoverPageUrl == "/schedule-a-call" ) {
                                $.getScript('/assets/js/jquery/plugins/jquery.selectBox.min.js').done(function(script, textStatus) {
                                    hasSelectBoxes = true;
                                });

                            }

                            if (overlayMode === "iframe") {

                                jQuery("form", $overlayContent).on("submit", function (event) {
                                    var form = jQuery(event.currentTarget);

                                    if (form.attr("target") && link.attr("target") != "overlay") {
                                        return;
                                    }

                                    event.preventDefault();

                                    var formValueArray = form.serializeArray();
                                    var formValueMap = {};
                                    for (var i = 0; i < formValueArray.length; i++) {
                                        formValueMap[formValueArray[i].name] = formValueArray[i].value;
                                    }
                                    formValueMap["action"] = true;
                                    $overlayContent.empty().load(form.attr("action"), formValueMap);

                                });
                            }

                            //Remove Loading screen
                            $overlayLoading.hide("fast");
                        });


                    },
                    onBeforeClose: function() {
                        //Destroy Select boxes created by pages loaded into the overlay
                        if ( hasSelectBoxes ) {
                            $(".SelectBox", $overlayContent).selectBox('destroy');
                            hasSelectBoxes = false;
                        }
                        $overlayContent.empty();
                    }
                });
            });

        $("a.Book-Overlay").live("click", function(event){
            $overlay.overlay({
                    close: '.Close',
                    fixed: false,
                    left: 'center',
                    mask: {color: '#fff', opacity: 0.5},
                    closeOnClick: true,
                    load: true,
                    onBeforeLoad: function() {

                        $overlay.removeClass('Video');

                        //Show Loading
                        $overlayLoading.show();

                        var overlayMode = "display";
                        if (this.getTrigger().data("overlay-mode")) {
                            overlayMode = this.getTrigger().data("overlay-mode").toLowerCase();
                        }


                        //Load the page into the overlay
                        $.ajax({
                            url: '/schedule-a-call',
                            dataType:'html'
                        }).done(function( data ) {

                            $overlayContent.empty().append(data);

                            $.getScript('/assets/js/jquery/plugins/jquery.selectBox.min.js').done(function(script, textStatus) {
                                hasSelectBoxes = true;
                            });

                            if (overlayMode === "iframe") {

                                jQuery("form", $overlayContent).on("submit", function (event) {
                                    var form = jQuery(event.currentTarget);

                                    if (form.attr("target") && link.attr("target") != "overlay") {
                                        return;
                                    }

                                    event.preventDefault();

                                    var formValueArray = form.serializeArray();
                                    var formValueMap = {};
                                    for (var i = 0; i < formValueArray.length; i++) {
                                        formValueMap[formValueArray[i].name] = formValueArray[i].value;
                                    }
                                    formValueMap["action"] = true;
                                    $overlayContent.empty().load(form.attr("action"), formValueMap);

                                });
                            }

                            //Remove Loading screen
                            $overlayLoading.hide("fast");
                        });


                    },
                    onBeforeClose: function() {
                        //Destroy Select boxes created by pages loaded into the overlay
                        if ( hasSelectBoxes ) {
                            $(".SelectBox", $overlayContent).selectBox('destroy');
                            hasSelectBoxes = false;
                        }
                        $overlayContent.empty();
                    }
                }).load();
            });

        //Pagination
        if ( $listingSection.length ) {

            //Remote Pagaintion for users with javascript enabled
            // $listingSection.gfPaginate_remote({
            //     paginateIdentifier: ".Pagination-Links",
            //     loadingImage: "/assets/images/icons/loading.gif",
            //     loadingClass: '.Listing-Loading'
            // });

        }

        // if( $(".locationString").length )
        //     $(".locationString").autocomplete({
        //         source: function( request, response ) {
        //             $.ajax({
        //                 url: "/region/locations",
        //                 dataType: "json",
        //                 data: {
        //                     locationString: request.term,
        //                     maxRows: 12
        //                 },
        //                 success: function( data ) {
        //                     response (
        //                         $.map( data.locations, function( item ) {
        //                             return {
        //                                 label: item.title,
        //                                 value: item.title,
        //                                 url: item.url
        //                             };
        //                         })
        //                     );
        //
        //                 }
        //             });
        //         },
        //         minLength: 2,
        //         select: function(event, ui) {
        //             document.location.href = ui.item.url;
        //         }
        //     });

        //Collapsing list
        $('.Slim-List .Link .Expand').on('click', function(event){
            event.stopPropagation();
            event.preventDefault();

            var link = $(event.currentTarget);
            var hideLink = link.parents('.Link').children('.Collapse');
            var collapsedItems = link.parents('.Slim-List').children('li.Collapse');

            link.hide();
            hideLink.show();
            collapsedItems.show();
        });
        $('.Slim-List .Link .Collapse').on('click', function(event){
            event.stopPropagation();
            event.preventDefault();

            var link = $(event.currentTarget);
            var showLink = link.parents('.Link').children('.Expand');
            var collapsedItems = link.parents('.Slim-List').children('li.Collapse');

            link.hide();
            showLink.show();
            collapsedItems.hide();
        });

    }); //DR



    
    /* ------------------------------------------
     * --newsletter modal
     * ------------------------------------------ */


    if( window.innerWidth > 860 ){
        
        var newsletterIdleTime = 0,
            newsletterIdleTimeInterval = 0;

        $(document).ready(function () {
            //Increment the idle time counter every minute.
            newsletterIdleTimeInterval = setInterval(timerIncrement, getNewsletterTimmer());

            //Zero the idle timer on mouse movement.
            // $(this).mousemove(function (e) {
            //     newsletterIdleTime = 0;
            // });
            // $(this).keypress(function (e) {
            //     newsletterIdleTime = 0;
            // });
        });

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
            }
            return "";
        }

        function timerIncrement() {
            newsletterIdleTime = newsletterIdleTime + 1;

            var timeLimit = 0;

            if (newsletterIdleTime > timeLimit) { // 20 seconds
            
                // Stop counter
                clearInterval(newsletterIdleTimeInterval);
                newsletterIdleTime = 0;

                if( getCookie( "newsletter_modal.wasViewed" ) !== "true" ) {
                    showNewsletterForm();
                }
            }
        }

        function getNewsletterTimmer(){
            return 1000; // 1 second
        }

        function showNewsletterForm() {
            $(".Overlay").overlay({
                close: '.Close',
                fixed: false,
                left: 'center',
                mask: {color: '#fff', opacity: 0.5},
                closeOnClick: true,
                load: true,
                onBeforeLoad: function() {

                    $(".Overlay").removeClass('Video');

                    //Show Loading
                    $(".Overlay-Loading", $(".Overlay")).show();

                    var overlayMode = "display";
                    if (this.getTrigger().data("overlay-mode")) {
                        overlayMode = this.getTrigger().data("overlay-mode").toLowerCase();
                    }


                    //Load the page into the overlay
                    $.ajax({
                        url: '/newsletter-modal',
                        dataType:'html'
                    }).done(function( data ) {

                        $(".Overlay-Content", $(".Overlay")).empty().append(data);

                        //$.getScript('/assets/js/jquery/plugins/jquery.selectBox.min.js').done(function(script, textStatus) {
                        //  hasSelectBoxes = true;
                        //});
                                        hasSelectBoxes = true;

                        if (overlayMode === "iframe") {

                            jQuery("form", $(".Overlay-Content", $(".Overlay"))).on("submit", function (event) {
                                var form = jQuery(event.currentTarget);

                                if (form.attr("target") && link.attr("target") != "overlay") {
                                    return;
                                }

                                event.preventDefault();

                                var formValueArray = form.serializeArray();
                                var formValueMap = {};
                                for (var i = 0; i < formValueArray.length; i++) {
                                    formValueMap[formValueArray[i].name] = formValueArray[i].value;
                                }
                                formValueMap["action"] = true;
                                $(".Overlay-Content", $(".Overlay")).empty().load(form.attr("action"), formValueMap);

                            });
                        }

                        //Remove Loading screen
                        $(".Overlay-Loading", $(".Overlay")).hide("fast");
                    });


                },
                onBeforeClose: function() {
                    $(".Overlay-Content", $(".Overlay")).empty();
                }
            }).load();

               var now = new Date();
               now.setTime( now.getTime() + (24 * 60 * 60 * 1000) );
               document.cookie="newsletter_modal.wasViewed=true;path=/;expires=" + now.toUTCString() ;
        }
    }



})(jQuery);
