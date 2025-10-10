<?php 
/**
 * Plugin Name: Starterkit Social Media
 * Plugin URI: http://tankdesign.com
 * Description: Creates an admin page to edit the social links for this site, and provides a function to render the social links in a menu
 * Version: 0.1
 * Author: Tank Design
 * Author URI: http://tankdesign.com
 * License: GPL2
 */



if( ! class_exists('StarterkitSocialMedia') ) :

/**
 * admin class -
 * StarterkitSocialMedia
 */
class StarterkitSocialMedia {


    public $socialOutlets = array(
        'facebook'   => array(
            'title' => 'Facebook'
        ),
        'twitter'    => array(
            'title' => 'Twitter'
        ),
        'instagram'  => array(
            'title' => 'Instagram'
        ),
        'linkedin'   => array(
            'title' => 'LinkedIn'
        ),
        'googleplus' => array(
            'title' => 'Google Plus'
        ),
        'pinterest'  => array(
            'title' => 'Pinterest'
        ),
    );


    /**
     * Start up
     */
    public function __construct() {


        $this->include_before_theme();

        add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
        add_action( 'admin_init', array( $this, 'init' ) );
    }





    /* -------------------------------------------------
     *
     * --getters
     *
     * ------------------------------------------------- */



    /**
     * Get the socialOutlets var
     */
    public function getSocialOutlets(){
        return $this->socialOutlets;
    }





    /* -------------------------------------------------
     *
     * --setup
     *
     * ------------------------------------------------- */


    /**
     * Include other plugin files
     */
    public function include_before_theme(){
        // incudes
        include_once('core/api.php');
    }




    /**
     * Add options page
     */
    public function add_admin_page() {
        add_menu_page(
            'Social Links', 
            'Social Links', 
            'manage_options', 
            'sk-social', 
            array( $this, 'create_admin_page' )
        );
    }



    /**
     * Options page callback
     */
    public function create_admin_page() {
        ?>
        <div class="wrap">    
            <h2>Social links</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'sk_social' );   
                do_settings_sections( 'sk-social' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }


    /* --------------------------------------------
     * --init and setting up the sections
     * -------------------------------------------- */

    /**
     * Register and add settings
     */
    public function init() {
        register_setting(
            'sk_social',         // Option group
            'sk_social',               // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'sk_social',                         // ID
            '',                                  // Title
            array( $this, 'printSectionInfo' ),  // Callback
            'sk-social'                          // Page
        );

        $socialOutlets = $this->getSocialOutlets();

        foreach($socialOutlets as $id => $social){

            add_settings_field(
                $id,
                $social['title'],
                array($this, 'settingCallback'),
                'sk-social',
                'sk_social',
                array( 'id' => $id )
            );
        }
    }


    /** 
     * Print the section helper text
     */
    public function printSectionInfo() {
        print '<p>Add links to your social media pages here.</p><p><em><strong>IMPORTANT:</strong> All links must start with a leading <code>http://</code> or <code>https://</code>.</em></p>';
    }





    /* --------------------------------------------
     * --callbacks
     * -------------------------------------------- */

    /**
     * Callback function for the social links
     *
     * @param array $args : A collection of arguments
     */
    public function settingCallback($args){
        $id = $args['id'];

        $options = get_option( 'sk_social' );

        printf(
            '<input type="text" id="' . $id . '" name="sk_social[' . $id . ']" value="%s" />',
            isset( $options[$id] ) ? esc_attr( $options[$id]) : ''
        );
    }




    /* -------------------------------------------------
     *
     * --util
     *
     * ------------------------------------------------- */



    /**
     * Sanitize each setting field as needed
     *
     * @param array $input : Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
        $newInput = array();

        $socialOutlets = $this->getSocialOutlets();

        foreach($socialOutlets as $id =>$social){
            if(isset($input[$id])){
                $newInput[$id] = sanitize_text_field($input[$id]);
            }
        }

        return $newInput;
    }



}


// init
function initSocialMediaClass(){
    global $StarterkitSocialMedia;

    if( !isset( $StarterkitSocialMedia ) ){
        $StarterkitSocialMedia = new StarterkitSocialMedia();
    }

    return $StarterkitSocialMedia;
}
initSocialMediaClass();


endif; // end classcheck for StarterkitSocialMedia




