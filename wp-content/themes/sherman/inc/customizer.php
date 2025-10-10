<?php
/**
 * Sherman Theme Customizer
 *
 * @package sherman
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sk_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    /**
     * add a section to the customizer
     
     */
    // $wp_customize->add_section( 'sk_hero_text_section' , array(
    //     'title'      => __( 'Homepage hero text', 'sherman' ),
    //     'priority'   => 60,
    // ) );

    // $wp_customize->add_setting( 'sk_hero_text', array(
    //     'default' => 'Default copyright text',
    // ));

    // $wp_customize->add_control('sk_hero_text', array(
    //     'label' => 'Copyright text',
    //     'section' => 'sk_hero_text_section',
    //     'type' => 'text',
    // ));


    /**
     * add a section for the logo to the customizer
     */
    $wp_customize->add_section( 'sk_branding' , array(
        'title'    => __( 'Site Branding', 'sherman' ),
        'priority' => 60,
    ) );

    // @usage get_theme_mod('sk_logo')
    $wp_customize->add_setting('sk_logo', array(
        'default'   => '',
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'sk_logo',
            array(
                'label'      => __( 'Upload a logo', 'sherman' ),
                'section'    => 'sk_branding',
                'settings'   => 'sk_logo',
            )
        )
    );


    /**
     * section for footer text
     */
    $wp_customize->add_section( 'sk_copyright' , array(
        'title'    => __( 'Footer Copyright', 'sherman' ),
        'priority' => 60,
    ) );

    // @usage get_theme_mod('sk_copyright_text')
    $wp_customize->add_setting('sk_copyright_text', array(
        'default'   => '',
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('sk_copyright_text', array(
        'label'   => __('Footer copyright text', 'sherman'),
        'type'    => 'text',
        'section' => 'sk_copyright',
    ));
}
add_action( 'customize_register', 'sk_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sk_customize_preview_js() {
	wp_enqueue_script( 'sk_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), false, true );
}
add_action( 'customize_preview_init', 'sk_customize_preview_js' );
