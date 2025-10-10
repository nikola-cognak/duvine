<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.ico" />
<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon.png" />
<?php wp_head(); ?>

<?php
if( $header_tracking_codes = get_field( 'header_tracking_codes', 'option' ) ) {
  echo $header_tracking_codes;
}
?>

<!-- Start Quantcast Tag -->
<script type="text/javascript">
var _qevents = _qevents || [];

 (function() {
   var elem = document.createElement('script');
   elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
   elem.async = true;
   elem.type = "text/javascript";
   var scpt = document.getElementsByTagName('script')[0];
   scpt.parentNode.insertBefore(elem, scpt);
  })();

_qevents.push({qacct: "p-MxwzEjZUUJL2P"});

</script>
<noscript>
 <img src="//pixel.quantserve.com/pixel/p-MxwzEjZUUJL2P.gif?labels=_fp.event.Default" style="display: none;" border="0" height="1" width="1" alt="Quantcast"/>
</noscript>
<!-- End Quantcast tag -->



<!--ShareThis-->
<script>
var switchTo5x=false;
(function(){
var e=document.createElement("script"); e.type="text/javascript"; e.async=true;
e.onload=function(){try{stLight.options({publisher: "005d0a3d-6904-42af-9827-da2eb495f17c", doNotHash: true, doNotCopy: true, hashAddressBar: false});}catch(e){}}
e.src=('https:' == document.location.protocol ? 'https://ws' : 'http://w') + '.sharethis.com/button/buttons.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(e, s);
})();
</script>

</head>

<body <?php body_class(); ?>>

    <div id="viewport">
        <header id="header" role="banner">
            <div class="Page">

                <a class="Logo" href="/">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/site/duvine-logo.png" alt="DuVine Adventures Cycling Adventure Company" />
                </a>

                <?php get_search_form(); ?>

                <a href="tel://6177764441" class="telephone-smallscreen">617 776 4441</a>
                <a href="#nav" aria-controls="nav" class="nav-menu-toggle control" id="menu-toggle"><span></span></a>

                <div class="Utility Clear">
                    <p class="Phone-Numbers">
                        <a class="TelephoneLink" href="tel://6177764441" onClick="trackEvent('phone', 'call', '6177764441')">617 776 4441</a><br />
                        <a class="TelephoneLink" href="tel://8883965383" onClick="trackEvent('phone', 'call', '8883965383')">888 396 5383</a>
                    </p>

                    <div class="Utility-Nav Bold">
                        <a class="Item Utility-Link Overlay-Link" href="/schedule-a-call/" data-overlay-mode="iframe" rel=".Overlay">Schedule a Call</a>
                        <span class="Item Separator">|</span>
                        <a class="Item Last Utility-Link Overlay-Link" href="/contact/" data-overlay-mode="iframe" rel=".Overlay">Email Us</a>
                    </div>

                    <div class="Action-Nav">
                        <a class="Item" href="/tours/">Trip Finder</a>
                        <a class="Item" href="/request">Request A Brochure</a>
                    </div>
                </div><!--/.Utility-->

                <div id="primaryNav" class="Nav Inline-List Clear">
                    <div class="Wrapper Clear">
                        <nav role="navigation" class="nav" id="nav">
                            <?php
                            //***************************************************************************
                            // Header, primary navigation
                            //***************************************************************************
                            wp_nav_menu(array(
                                'theme_location'    => 'header',
                                'container'         => false,
                                'menu_class'        => 'nav-menu',
                            ));
                            ?>
                        </nav>
                    </div><!--/.Wrapper-->
                </div><!--/#primaryNav-->
            </div><!--/.Page-->

        </header><!--/#header-->

        <div id="content"<?php if( 'on' == get_post_meta( $post->ID, 'duvine_dark_background', true ) ) {echo ' class="Dark"';}?>>
            <?php duvine_get_child_navigation(); ?>
