<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package sherman
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<!-- <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico"> -->
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">


<?php if (is_load_cookie_disclaimer()): ?>
<!-- OneTrust Cookies Consent Notice start for www.duvine.com -->

<script src="https://cdn.cookielaw.org/scripttemplates/otSDKStub.js"  type="text/javascript" charset="UTF-8" data-domain-script="075c1463-4528-41fe-aca4-73f71b22fb53" ></script>
<script type="text/javascript">
function OptanonWrapper() { }
</script>
<!-- OneTrust Cookies Consent Notice end for www.duvine.com -->
<?php endif; ?>


<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.setAttributeNode(d.createAttribute('data-ot-ignore'));
j.async=true;j.src= 'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WHMZ38W');</script>
<!-- End Google Tag Manager -->
<?php if (wp_get_environment_type() !== 'production') {
    remove_from_indexing();
} ?>
<?php wp_head(); ?>

<!-- TRACKING -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-866383-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-866383-1');
//   gtag('consent', 'default', {
// 	   'ad_storage': 'denied',
// 	   'ad_user_data': 'denied',
// 	   'ad_personalization': 'denied',
// 	   'analytics_storage': 'denied'
//   });

</script>

<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"4074005"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script><noscript><img src="//bat.bing.com/action/0?ti=4074005&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>

<!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/408217.js"></script>
<!-- End of HubSpot Embed Code -->

</head>

<body <?php body_class(); ?>>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WHMZ38W"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


<!-- Date range picker -->
<script type="text/javascript" data-ot-ignore src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" data-ot-ignore src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- <script data-ot-ignore src='https://www.duvine.com/wp-content/plugins/autoptimize/classes/external/js/lazysizes.min.js?ao_version=3.1.8.1'></script> -->
	<script data-ot-ignore 
        src='https://www.duvine.com/wp-content/plugins/autoptimize/classes/external/js/lazysizes.min.js?ao_version=3.1.8.1'
        data-no-optimize="1"></script>
<!-- End date range picker -->

<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'sherman' ); ?></a>

	<header id="masthead" class="globalheader" role="banner">
		<div class="site-branding">
			<?php if( $logo = get_theme_mod('sk_logo') ) : ?>
				<a href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo $logo; ?>"></a>
			<?php endif; ?>
		</div><nav id="site-navigation" class="mainnav" role="navigation">
			<button class="menu-toggle js-toggle-mobilenav toggle-switch__htx"><span></span></button>
			<?php
				wp_nav_menu( array(
					'theme_location'  => 'primary',
					'container_class' => 'mainnav__container',
					'duvine_location' => 'header'
				) );
			?>
		</nav><!-- #site-navigation -->

		<nav id="site-search-menu" class="searchnav">
			<a href="#" class="triggersearch">Search</a>
		</nav>

		<nav id="site-utility-nav" class="utilitynav">
			<?php if( $phoneNumber = duvine_get_phone_number() ) : ?>
				<span class="headerspan"><?php echo duvine_get_phone_number(); ?></span>
			<?php endif; ?>

			<?php
				wp_nav_menu(array(
					'theme_location'  => 'utility',
					'container_class' => 'utilitynav__container',
					'fallback_cb'     => false
				));
			?>
		</nav>
		
	</header><!-- #masthead -->

	<div id="content" class="site-content">
