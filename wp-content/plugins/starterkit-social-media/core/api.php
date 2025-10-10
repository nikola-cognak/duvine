<?php
/**
 * API functions for this plugin
 */


/**
 * Render a menu containing the social links
 *
 * @param array $args : An array of arguments
 *    - intro_text (string) : any intro copy to appear before the list
 *    - new_tab (boolean)   : whether or not to open the social link in a new window
 *    - list_class (string) : the class to assign to the ul element
 *
 * @usage
 *
 *    sk_social_media_menu();
 *
 */
function sk_social_media_menu( $args = array() ){
    
    $links = get_option('sk_social');

    if( !$links){
        return false;
    }

    $defaults = array(
        'intro_text' => '',
        'new_tab'    => true,
        'list_class' => 'menu'
    );

    $options = array_merge($defaults, $args);
?>
    <div class="sk-social-media-menu">
        <?php if( $options['intro_text'] !== '' ) : ?>
            <span class="sk-social-media-intro"><?php echo $options['intro_text']; ?></span>
        <?php endif; ?>
        <ul class="menu">
            <?php foreach($links as $site => $link) : if($link) : ?>
                <li><a href="<?php echo $link; ?>"<?php if( $options['new_tab'] ) echo ' target="_blank"'; ?>><?php include_svg('social--' . $site); ?></a></li>
            <?php endif; endforeach; ?>
        </ul>
    </div>
<?php
}
