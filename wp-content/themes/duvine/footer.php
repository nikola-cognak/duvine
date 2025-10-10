<footer id="footer" class="Page">
                <div class="Footer-Content  Clear">

                    <div class="Clear">
                        <nav class="Footer-Nav">
                            <div class="Section One Clear">

                                <?php
                                wp_nav_menu(array(
                                    'menu'          => 'header-menu',
                                    'container'     => false,
                                    'menu_class'    => 'nav-menu',
                                    'depth'         => 1
                                ));
                                ?>

                            </div><!--/.Section.One-->

                            <div class="Section Two Clear">
                                <div class="Center">

                                    <span class="Item Strong NoHover">Contact Us</span>

                                    <a class="Item Overlay-Link" href="/contact" data-overlay-mode="iframe" rel=".Overlay">Email Us</a><span class="Item Separator NoHover">|</span>
                                    <a class="Item" href="/our-offices">Our Offices</a><span class="Item Separator NoHover">|</span>
                                    <a class="Item Overlay-Link" href="/schedule-a-call" rel=".Overlay">Schedule A Call</a><span class="Item Separator NoHover">|</span>
                                    <a class="Item" href="/request-an-itinerary">Request An Itinerary</a><span class="Item Separator NoHover">|</span>
                                    <a class="Item" href="/media-request">Media Request</a><span class="Item Separator NoHover">|</span>
                                    <a class="Item" href="/careers">Careers</a><span class="Item Separator NoHover">|</span>
                                    <a class="Item" href="/travel-agents">Travel Agents</a>
                                </div>
                            </div><!--/.Section.Two-->

                            <div class="Section Three Clear">
                                <div class="Social">
                                    <span class="Item Strong NoHover">Connect</span>
                                    <a class="Item Social-Icon Facebook-Icon" href="http://www.facebook.com/duvine" target="_blank"></a>
                                    <a class="Item Social-Icon Twitter-Icon" href="http://twitter.com/duvine" target="_blank"></a>
                                    <a class="Item Social-Icon YouTube-Icon" href="http://www.youtube.com/bicycletours" target="_blank"></a>
                                    <a class="Item Social-Icon LinkedIn-Icon" href="http://www.linkedin.com/company/duvine-cycling-adventure-co-" target="_blank"></a>
                                    <a class="Item Social-Icon GooglePlus-Icon" href="https://plus.google.com/+Duvine" target="_blank"></a>
                                    <a class="Item Social-Icon Pinterest-Icon" href="http://www.pinterest.com/DuVineBikeTours" target="_blank"></a>
                                    <a class="Item Social-Icon Instagram-Icon" href="http://instagram.com/duvine" target="_blank"></a>
                                    <a class="Item Social-Icon RSS-Icon" href="/rss" target="_blank"></a>
                                </div><!--/.Social-->

                                <div class="Separator">|</div>

                                <div class="Newsletter">
                                    <strong>Sign Up For Our Newsletter</strong>
                                    <!--[if lte IE 8]>
<!--[if lte IE 8]>
<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
<![endif]-->
<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
<script>
  hbspt.forms.create({ 
    css: '',
    portalId: '408217',
    formId: '32aadc65-fd15-4047-b608-f546ecfad56f'
  });
</script>

                                </div><!--/.Newsletter-->
                            </div><!--/.Section.Three-->
                        </nav>
                    </div><!--/.Clear-->

                    <p class="Copyright">
                        <span class="Item NoHover">Copyright 2017 : DuVine Cycling + Adventure Co.</span><span class="Separator NoHover">|</span>
                        <a class="Item" href="/terms-and-conditions">Terms + Conditions</a><span class="Item Separator NoHover">|</span>
                        <a class="Item" href="/privacy-policy">Privacy Policy</a>
                    </p>

                </div><!--/.Footer-Content-->
            </footer><!--/#footer-->


        </div><!--/#content-->

    </div><!--/#viewport-->

    <div class="Overlay Border-Lines">
        <div class="Close">close</div>
        <div class="Overlay-Content"></div>
        <div class="Overlay-Loading"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/loading.gif" alt="Loading" /></div>
    </div>

    <?php wp_footer(); ?>
    <?php
    if( $footer_tracking_codes = get_field( 'footer_tracking_codes', 'option' ) ) {
    	echo $footer_tracking_codes;
    }
    ?>
</body>
</html>