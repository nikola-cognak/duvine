<?php

/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package sherman
 */
?>

</div><!-- #content -->

<div class="footer-newsletter">
    <div class="footer-newsletter__container">
        <h3 class="blockheader">Our Newsletter:</h3>
        <div class="footer-newsletter__form">
            <!--[if lte IE 8]>
        <script charset="utf-8" type="text/javascript" src="https://js.hsforms.net/forms/v2-legacy.js"></script>
        <![endif]-->
            <script charset="utf-8" type="text/javascript" src="https://js.hsforms.net/forms/v2.js"></script>
            <script>
                hbspt.forms.create({
                    css: '',
                    portalId: '408217',
                    formId: '8f9177f0-0d14-4b9a-8ddf-7b2731274bb4'
                });
            </script>
        </div>
    </div>
</div>

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="l-container">
        <div class="d-column-container d-column-container--nogutter">
            <div class="d-col--footer-col">
                <p class="duvine__tagline">Bike<br />Eat<br />Drink<br />Sleep</p>
            </div>


            <div class="d-col--footer-col">
                <h5 class="footer__navheader capsheader">Company</h5>
                <?php
                wp_nav_menu(array(
                    'theme_location'  => 'company',
                    'container_class' => 'companynav__container',
                    'menu_class'      => 'blockmenu footermenu',
                    'fallback_cb'     => false
                ));
                ?>
            </div>


            <div class="d-col--footer-col">
                <h5 class="footer__navheader capsheader">Connect</h5>
                <?php
                wp_nav_menu(array(
                    'theme_location'  => 'connect',
                    'container_class' => 'connectnav__container',
                    'menu_class'      => 'blockmenu footermenu',
                    'fallback_cb'     => false
                ));
                ?>
            </div>


            <div class="d-col--footer-col">
                <h5 class="footer__navheader capsheader">Contact</h5>
                <?php
                wp_nav_menu(array(
                    'theme_location'  => 'contact',
                    'container_class' => 'contactnav__container',
                    'menu_class'      => 'blockmenu footermenu',
                    'fallback_cb'     => false
                ));
                ?>
                <p class="footercontact">Call us at <?php echo duvine_get_phone_number(); ?></p>

                <?php echo duvine_get_office_hours(); ?>
            </div>


            <div class="d-col--footer-col">
                <h5 class="footer__navheader capsheader">Follow DuVine</h5>
                <?php include 'inc/social-links.php'; ?>
                <?php if (function_exists('sk_social_media_menu')) : ?>
                    <?php sk_social_media_menu(); ?>

                <?php endif; ?>

                <?php if ($request_brochure_page = get_field('d_request_a_brochure_page', 'option')) : ?>
                    <a href="<?php echo $request_brochure_page['url']; ?>" class="cta cta--hoverwhite">Request a Brochure</a>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <div class="footer__lightband">
        <?php if ($globalPress = get_field('d_press_recently_recognized_global', 'option')) : ?>
            <?php $pinnedPress = get_field('d_press_pinned_recently_recognized_global', 'option') ?>
            <section class="footer__featuredpubs l-container">
                <?php
                $pressData = [];
                $ulPinClass = '';
                foreach ($globalPress as $key => $pub) {
                    $liClass = 'featuredpubs__publication publication--' . $pub->post_name;
                    $pubthumb = duvine_get_img(get_field('d_pub_grayscale_logo', $pub->ID), array('container' => false));
                    if (!$pubthumb) {
                        $liClass .= ' featuredpubs__publication--blend';
                        $pubthumb = get_the_post_thumbnail($pub, 'full');
                    }
                    if ($pinnedPress && array_key_exists(0, $pinnedPress) && $pinnedPress[0]->ID === $pub->ID) {
                        $ulPinClass = ' with-pinned';
                        $liClass .= ' pinned-publication';
                        $pressData[$key - 1]['liClass'] .= ' before-pinned';
                        $pressData[$key + 1]['liClass'] = 'after-pinned ';
                    }
                    if (array_key_exists($key, $pressData) && array_key_exists('liClass', $pressData[$key])) {
                        $pressData[$key]['liClass'] .= $liClass;
                    } else {
                        $pressData[$key]['liClass'] = $liClass;
                    }
                    $pressData[$key]['postName'] = $pub->post_name;
                    $pressData[$key]['postThumb'] = $pubthumb;
                }
                ksort($pressData);
                echo '<ul class="featuredpubs__publications featuredpubs__publications--stackable featuredpubs__publications--middle' . $ulPinClass . '">';
                foreach ($pressData as $pressDatum) {
                    if ($pressDatum['postThumb']) {
                        echo '<li class="' . $pressDatum['liClass'] . '"><a href="/press-awards/?publication=' . $pressDatum['postName'] . '#featuredpubs">' . $pressDatum['postThumb'] . '</a></li>';
                    }
                }
                echo '</ul>';
                ?>
            </section>

        <?php endif; ?>

        <div class="l-container site-copyright">
            <div>
                <p class="copyright__copy">&copy; <?php echo do_shortcode(get_theme_mod('sk_copyright_text')); ?></p>
                <?php
                wp_nav_menu(array(
                    'theme_location'  => 'footer_utility',
                    'container_class' => 'footerutilnav__container',
                    'fallback_cb'     => false
                ));
                ?>
            </div>
            <div class="carbon">

                <img src="https://cdn.duvine.com/wp-content/uploads/2023/10/17060730/carbon.png" alt="Carbon Neutral" />
                <a href="https://www.duvine.com/why-duvine/sustainable-travel/">
                    <span>
                        Carbon Neutral <b>·</b> Sustainable Travel
                    </span>
                </a>
            </div>

        </div><!-- .site-info -->

    </div>

</footer><!-- #colophon -->
</div><!-- #page -->


<?php duvine_render_mobilenav() ?>

<?php duvine_render_search_modal(); ?>
<script>
    jQuery(document).ready(function() {
        if (window.location.href.indexOf("Nat%20Hab") > -1) {
            jQuery('.popup-img').show();
            jQuery('.newslettermodal').css('opacity', '1');
            jQuery('.newslettermodal').css('visibility', 'visible');
            jQuery('.popup-title').html('Sign up for our newsletter');
            jQuery('.popup-h3').html('Experience the world by bike!');
        }
        if (window.location.href.indexOf("Lindblad") > -1) {
            jQuery('.popup-img').attr("src", "/wp-content/uploads/2021/04/duvine-lindblad.png");
            jQuery('.popup-img').show();
            jQuery('.newslettermodal').css('opacity', '1');
            jQuery('.newslettermodal').css('visibility', 'visible');
            jQuery('.popup-title').html('Sign up for our newsletter');
            jQuery('.popup-h3').html('Experience the world by bike!');
        }
    });
</script>
<script>
	if (jQuery('body').hasClass('page-id-393')) {
		if(window.location.hash) {
			var hash = window.location.hash.substring(1);
			console.log(hash);
			jQuery('.anchor-target[name="' + hash + '"]').closest('li').addClass('active');
			jQuery('.anchor-target[name="' + hash + '"]').closest('.accordion__content').css('display','block');
		}
	}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
//   console.log('[Slider Fix] DOMContentLoaded fired');

  const slider = document.querySelector('.featuredblogs__slider');
  const firstSlide = document.querySelector('.featuredblog__slide');

  if (!firstSlide || !slider) {
//     console.warn('[Slider Fix] First slide or slider not found');
    return;
  }

  const img = firstSlide.querySelector('img.lazyload');

  if (!img) {
//     console.warn('[Slider Fix] No lazyloaded image found in first slide');
    return;
  }

  const reinitSlider = () => {
//     console.log('[Slider Fix] Reinitializing slick slider');
    if (typeof jQuery !== 'undefined') {
      jQuery(slider).slick('setPosition');
    } else {
      console.error('[Slider Fix] jQuery not available');
    }
  };

  // Listen for lazysizes' custom lazyloaded event
  document.addEventListener('lazyloaded', function (e) {
    if (e.target === img) {
//       console.log('[Slider Fix] lazyloaded fired for first slide image');
      reinitSlider();
    }
  });

//   console.log('[Slider Fix] Waiting for lazyloaded event');
});
</script>


<?php duvine_render_newsletter_modal(); ?>

<?php wp_footer(); ?>

</body>

</html>