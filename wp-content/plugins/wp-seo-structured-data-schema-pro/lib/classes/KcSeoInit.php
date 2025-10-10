<?php

if ( ! class_exists( 'KcSeoInit' ) ) :

	class KcSeoInit {
		private $version;

		function __construct() {
			$this->version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : KCSEO_VERSION;
			add_action( 'init', [ $this, 'kcSeoScript' ] );
			add_action( 'admin_menu', [ $this, 'kcSeo_Wp_Schema_menu' ] );
			add_action( 'plugins_loaded', [ $this, 'kcSeo_pluginInit' ] );
			add_action( 'wp_ajax_kcSeoWpSchemaSettings', [ $this, 'kcSeoWpSchemaSettings' ] );
			add_action( 'wp_ajax_kcSeoMainSettings_action', [ $this, 'kcSeoMainSettings_action' ] );
			add_action( 'wp_ajax_newSocial', [ $this, 'newSocial' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

			// for MU Site
			add_action( 'activated_plugin', [ $this, 'update_queue' ], 10, 2 );
			add_action( 'deactivated_plugin', [ $this, 'update_queue' ], 10, 2 );

			register_activation_hook( KCSEO_WP_SCHEMA_PLUGIN_ACTIVE_FILE_NAME, [ $this, 'activePlugin' ] );
			// register_deactivation_hook(KCSEO_WP_SCHEMA_PLUGIN_ACTIVE_FILE_NAME, array($this, 'uninstall'));
			// Uninstall hook
		}

		function update_queue( $plugin, $network_wide = null ) {
			if ( ! $network_wide ) {
				return;
			}
			list( $action ) = explode( '_', current_filter(), 2 );

			$action = str_replace( 'activated', 'activate', $action );
			$queue  = get_site_option( "network_{$action}_queue", [] );

			$queue[ $plugin ] = ( has_filter( $action . '_' . $plugin ) || has_filter( $action . '_plugin' ) );
			update_site_option( "network_{$action}_queue", $queue );
		}

		function admin_enqueue_scripts() {
			global $pagenow;
			// validate page
			$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : null;

			if ( ( $pagenow == 'admin.php' && ( $page == 'wp-seo-schema' || $page == 'wp-seo-schema-settings' ) ) || $pagenow == 'term.php' ) {

				// scripts
				wp_enqueue_media();
				wp_enqueue_script(
					[
						'jquery',
						'kcseo-select2-js',
						'kcseo-tooltip-js',
						'kcseo-admin-js',
					]
				);

				// styles
				wp_enqueue_style(
					[
						'kcseo-select2-css',
						'kcseo-tooltip-css',
						'kcseo-admin-css',
					]
				);

				if ( $pagenow == 'term.php' ) {
					$custom_css = '.kc-top-toolbar { display: none; }';
					wp_add_inline_style( 'kcseo-admin-css', $custom_css );
				}
			}
		}

		function kcSeoScript() {
			global $KcSeoWPSchema;
			// register team scripts and styles
			$scripts = [];
			$styles  = [];
			if ( is_admin() ) {

				$scripts[] = [
					'handle' => 'kcseo-select2-js',
					'src'    => $KcSeoWPSchema->assetsUrl . 'js/select2.min.js',
					'deps'   => [ 'jquery' ],
					'footer' => true,
				];
				$scripts[] = [
					'handle' => 'kcseo-tooltip-js',
					'src'    => $KcSeoWPSchema->assetsUrl . 'js/jquery.qtip.js',
					'deps'   => [ 'jquery' ],
					'footer' => true,
				];
				$scripts[] = [
					'handle' => 'kcseo-admin-js',
					'src'    => $KcSeoWPSchema->assetsUrl . 'js/admin.js',
					'deps'   => [ 'jquery' ],
					'footer' => true,
				];

				$styles['kcseo-select2-css'] = $KcSeoWPSchema->assetsUrl . 'css/select2.min.css';
				$styles['kcseo-tooltip-css'] = $KcSeoWPSchema->assetsUrl . 'css/jquery.qtip.css';
				$styles['kcseo-admin-css']   = $KcSeoWPSchema->assetsUrl . 'css/admin.css';
			}
			foreach ( $scripts as $script ) {
				wp_register_script( $script['handle'], $script['src'], $script['deps'], $this->version, $script['footer'] );
			}
			foreach ( $styles as $k => $v ) {
				wp_register_style( $k, $v, false, $this->version );
			}
		}

		function newSocial() {
			$schemaModel = new KcSeoSchemaModel();
			$id          = ( $_REQUEST['id'] ? $_REQUEST['id'] + 1 : 0 );
			$html        = null;
			$html        = "<div class='sfield'>";
			$html       .= "<select name='social[$id][id]'>";
			foreach ( KcSeoOptions::getSocialList() as $skey => $social ) {
				$html .= "<option value='$skey'>$social</option>";
			}
			$html .= '</select>';
			$html .= "<input type='text' name='social[$id][link]'>";
			$html .= '<span class="dashicons dashicons-trash social-remove"></span>';
			$html .= '</div>';

			wp_send_json( [ 'data' => $html ] );
			die();
		}
        // phpcs:disable WordPress.Security.NonceVerification.Recommended
		function kcSeoWpSchemaSettings() {
			global $KcSeoWPSchema;
			$error = true;

			$msg = null;
			if ( $KcSeoWPSchema->verifyNonce() && current_user_can( 'manage_options' ) ) {
				unset(
					$_REQUEST['action'],
					$_REQUEST['_kcseo_nonce'],
					$_REQUEST['_wp_http_referer'],
					$_REQUEST['woocommerce-login-nonce'],
					$_REQUEST['_wpnonce'],
					$_REQUEST['woocommerce-reset-password-nonce']
				);
				$settings_data = [];

				// Top-level fields.
				$settings_data['web_url']             = isset( $_REQUEST['web_url'] ) ? esc_url_raw( wp_unslash( $_REQUEST['web_url'] ) ) : '';
				$settings_data['site_type']           = isset( $_REQUEST['site_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['site_type'] ) ) : '';
				$settings_data['type_name']           = isset( $_REQUEST['type_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type_name'] ) ) : '';
				$settings_data['site_image']          = isset( $_REQUEST['site_image'] ) ? absint( wp_unslash( $_REQUEST['site_image'] ) ) : 0;
				$settings_data['site_price_range']    = isset( $_REQUEST['site_price_range'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['site_price_range'] ) ) : '';
				$settings_data['site_telephone']      = isset( $_REQUEST['site_telephone'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['site_telephone'] ) ) : '';
				$settings_data['additionalType']      = isset( $_REQUEST['additionalType'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['additionalType'] ) ) : '';
				$settings_data['organization_logo']   = isset( $_REQUEST['organization_logo'] ) ? absint( wp_unslash( $_REQUEST['organization_logo'] ) ) : '';
				$settings_data['social_company_name'] = isset( $_REQUEST['social_company_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['social_company_name'] ) ) : '';
				$settings_data['disable_site_schema'] = isset( $_REQUEST['disable_site_schema'] ) ? absint( wp_unslash( $_REQUEST['disable_site_schema'] ) ) : 0;
				$settings_data['homeonly']            = isset( $_REQUEST['homeonly'] ) ? absint( wp_unslash( $_REQUEST['homeonly'] ) ) : 0;
				$settings_data['sitename']            = isset( $_REQUEST['sitename'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['sitename'] ) ) : '';
				$settings_data['siteaname']           = isset( $_REQUEST['siteaname'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['siteaname'] ) ) : '';
				$settings_data['siteurl']             = isset( $_REQUEST['siteurl'] ) ? esc_url_raw( wp_unslash( $_REQUEST['siteurl'] ) ) : '';

				// Nested: restaurant.
				$settings_data['restaurant']['servesCuisine'] = isset( $_REQUEST['restaurant']['servesCuisine'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['restaurant']['servesCuisine'] ) ) : '';

				// Nested: business_info.
				$settings_data['business_info']['description']  = isset( $_REQUEST['business_info']['description'] ) ? sanitize_textarea_field( wp_unslash( $_REQUEST['business_info']['description'] ) ) : '';
				$settings_data['business_info']['openingHours'] = isset( $_REQUEST['business_info']['openingHours'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['business_info']['openingHours'] ) ) : '';
				$settings_data['business_info']['latitude']     = isset( $_REQUEST['business_info']['latitude'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['business_info']['latitude'] ) ) : '';
				$settings_data['business_info']['longitude']    = isset( $_REQUEST['business_info']['longitude'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['business_info']['longitude'] ) ) : '';

				// Nested: person.
				$settings_data['person']['name']        = isset( $_REQUEST['person']['name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['person']['name'] ) ) : '';
				$settings_data['person']['worksFor']    = isset( $_REQUEST['person']['worksFor'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['person']['worksFor'] ) ) : '';
				$settings_data['person']['jobTitle']    = isset( $_REQUEST['person']['jobTitle'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['person']['jobTitle'] ) ) : '';
				$settings_data['person']['image']       = isset( $_REQUEST['person']['image'] ) ? esc_url_raw( wp_unslash( $_REQUEST['person']['image'] ) ) : '';
				$settings_data['person']['description'] = isset( $_REQUEST['person']['description'] ) ? sanitize_textarea_field( wp_unslash( $_REQUEST['person']['description'] ) ) : '';
				$settings_data['person']['birthDate']   = isset( $_REQUEST['person']['birthDate'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['person']['birthDate'] ) ) : '';

				// Nested: address.
				$settings_data['address']['country']    = isset( $_REQUEST['address']['country'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['address']['country'] ) ) : '';
				$settings_data['address']['locality']   = isset( $_REQUEST['address']['locality'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['address']['locality'] ) ) : '';
				$settings_data['address']['region']     = isset( $_REQUEST['address']['region'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['address']['region'] ) ) : '';
				$settings_data['address']['postalcode'] = isset( $_REQUEST['address']['postalcode'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['address']['postalcode'] ) ) : '';
				$settings_data['address']['street']     = isset( $_REQUEST['address']['street'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['address']['street'] ) ) : '';

				// Nested: contact.
				$settings_data['contact']['contactType']   = isset( $_REQUEST['contact']['contactType'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['contact']['contactType'] ) ) : '';
				$settings_data['contact']['telephone']     = isset( $_REQUEST['contact']['telephone'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['contact']['telephone'] ) ) : '';
				$settings_data['contact']['email']         = isset( $_REQUEST['contact']['email'] ) ? sanitize_email( wp_unslash( $_REQUEST['contact']['email'] ) ) : '';
				$settings_data['contact']['contactOption'] = isset( $_REQUEST['contact']['contactOption'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['contact']['contactOption'] ) ) : '';

				// Nested: social (array of entries).
				$settings_data['social'] = [];
				if ( isset( $_REQUEST['social'] ) && is_array( $_REQUEST['social'] ) ) {
					foreach ( $_REQUEST['social'] as $social_item ) {
						$settings_data['social'][] = [
							'id'   => isset( $social_item['id'] ) ? sanitize_text_field( wp_unslash( $social_item['id'] ) ) : '',
							'link' => isset( $social_item['link'] ) ? esc_url_raw( wp_unslash( $social_item['link'] ) ) : '',
						];
					}
				}

				// Nested: area_served (array of codes).
				$settings_data['area_served'] = [];
				if ( isset( $_REQUEST['area_served'] ) && is_array( $_REQUEST['area_served'] ) ) {
					foreach ( $_REQUEST['area_served'] as $area ) {
						$settings_data['area_served'][] = sanitize_text_field( wp_unslash( $area ) );
					}
				}

				// Nested: availableLanguage (array of names).
				$settings_data['availableLanguage'] = [];
				if ( isset( $_REQUEST['availableLanguage'] ) && is_array( $_REQUEST['availableLanguage'] ) ) {
					foreach ( $_REQUEST['availableLanguage'] as $lang ) {
						$settings_data['availableLanguage'][] = sanitize_text_field( wp_unslash( $lang ) );
					}
				}

				$settings_data['sameAs']                      = isset( $_REQUEST['sameAs'] ) ? esc_url_raw( wp_unslash( $_REQUEST['sameAs'] ) ) : '';
				$settings_data['business_info']['geo_radius'] = isset( $_REQUEST['business_info']['geo_radius'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['business_info']['geo_radius'] ) ) : '';
				$settings_data['_multiple_address']           = [];
				if ( isset( $_REQUEST['_multiple_address'] ) && is_array( $_REQUEST['_multiple_address'] ) ) {
					foreach ( $_REQUEST['_multiple_address'] as $addr ) {
						$settings_data['_multiple_address'][] = [
							'country'    => isset( $addr['country'] ) ? sanitize_text_field( wp_unslash( $addr['country'] ) ) : '',
							'locality'   => isset( $addr['locality'] ) ? sanitize_text_field( wp_unslash( $addr['locality'] ) ) : '',
							'region'     => isset( $addr['region'] ) ? sanitize_text_field( wp_unslash( $addr['region'] ) ) : '',
							'postalcode' => isset( $addr['postalcode'] ) ? sanitize_text_field( wp_unslash( $addr['postalcode'] ) ) : '',
							'street'     => isset( $addr['street'] ) ? sanitize_text_field( wp_unslash( $addr['street'] ) ) : '',
						];
					}
				}

				update_option( $KcSeoWPSchema->options['settings'], $settings_data );
				$error = false;
				$msg   = __( 'Settings successfully updated', 'wp-seo-structured-data-schema-pro' );
			} else {
				$msg = __( 'Session is expired !!', 'wp-seo-structured-data-schema-pro' );
			}
			$response = [
				'error' => $error,
				'msg'   => $msg,
			];
			wp_send_json( $response );
			die();
		}

		function kcSeoMainSettings_action() {
			global $KcSeoWPSchema;
			$error = true;
			$msg   = null;
			if ( $KcSeoWPSchema->verifyNonce() && current_user_can( 'manage_options' ) ) {
				unset(
					$_REQUEST['action'],
					$_REQUEST['_kcseo_nonce'],
					$_REQUEST['_wp_http_referer'],
					$_REQUEST['woocommerce-login-nonce'],
					$_REQUEST['_wpnonce'],
					$_REQUEST['woocommerce-reset-password-nonce']
				);
				$settings_data = [];
				// Text fields
				$settings_data['site_schema'] = isset( $_REQUEST['site_schema'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['site_schema'] ) ) : '';

				// Integer/checkbox fields
				$settings_data['yoast_wpseo_json_ld']        = isset( $_REQUEST['yoast_wpseo_json_ld'] ) ? absint( wp_unslash( $_REQUEST['yoast_wpseo_json_ld'] ) ) : 0;
				$settings_data['yoast_wpseo_json_ld_search'] = isset( $_REQUEST['yoast_wpseo_json_ld_search'] ) ? absint( wp_unslash( $_REQUEST['yoast_wpseo_json_ld_search'] ) ) : 0;
				$settings_data['wc_schema_disable']          = isset( $_REQUEST['wc_schema_disable'] ) ? absint( wp_unslash( $_REQUEST['wc_schema_disable'] ) ) : 0;
				$settings_data['edd_schema_microdata']       = isset( $_REQUEST['edd_schema_microdata'] ) ? absint( wp_unslash( $_REQUEST['edd_schema_microdata'] ) ) : 0;
				$settings_data['archive']                    = isset( $_REQUEST['archive'] ) ? absint( wp_unslash( $_REQUEST['archive'] ) ) : 0;
				$settings_data['product_archive']            = isset( $_REQUEST['product_archive'] ) ? absint( wp_unslash( $_REQUEST['product_archive'] ) ) : 0;
				$settings_data['delete-data']                = isset( $_REQUEST['delete-data'] ) ? absint( wp_unslash( $_REQUEST['delete-data'] ) ) : 0;

				// post-type (array of strings)
				$settings_data['post-type'] = isset( $_REQUEST['post-type'] ) && is_array( $_REQUEST['post-type'] ) ?
					array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['post-type'] ) ) : [];

				// site_nav (single int)
				$settings_data['site_nav'] = isset( $_REQUEST['site_nav'] ) ? absint( wp_unslash( $_REQUEST['site_nav'] ) ) : 0;

				// publisher (array with name and logo)
				$settings_data['publisher'] = [
					'name' => isset( $_REQUEST['publisher']['name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['publisher']['name'] ) ) : '',
					'logo' => isset( $_REQUEST['publisher']['logo'] ) ? absint( wp_unslash( $_REQUEST['publisher']['logo'] ) ) : 0,
				];
				update_option( $KcSeoWPSchema->options['main_settings'], $settings_data );
				$error = false;
				$msg   = __( 'Settings successfully updated', 'wp-seo-structured-data-schema-pro' );
			} else {
				$msg = __( 'Session is expired !!', 'wp-seo-structured-data-schema-pro' );
			}
			$response = [
				'error' => $error,
				'msg'   => $msg,
			];
			wp_send_json( $response );
			die();
		}
        // phpcs:enable WordPress.Security.NonceVerification.Recommended
		function wp_schema_page() {
			global $KcSeoWPSchema;
			$KcSeoWPSchema->render( 'schema-options' );
		}

		function wp_schema_setting_page() {
			global $KcSeoWPSchema;
			$KcSeoWPSchema->render( 'settings' );
		}

		function kcSeo_Wp_Schema_menu() {
			global $KcSeoWPSchema;
			add_menu_page(
				__( 'WP SEO Structured Data Schema', 'wp-seo-structured-data-schema-pro' ),
				__( 'WP SEO Schema', 'wp-seo-structured-data-schema-pro' ),
				'manage_options',
				'wp-seo-schema',
				[ $this, 'wp_schema_page' ],
				$KcSeoWPSchema->assetsUrl . 'images/wp-seo-schema.png'
			);
			add_submenu_page(
				'wp-seo-schema',
				__( 'WP SEO Schema settings', 'wp-seo-structured-data-schema-pro' ),
				__( 'Settings', 'wp-seo-structured-data-schema-pro' ),
				'manage_options',
				'wp-seo-schema-settings',
				[ $this, 'wp_schema_setting_page' ]
			);
		}


		function kcSeo_pluginInit() {
			load_plugin_textdomain( 'wp-seo-structured-data-schema-pro', false, KCSEO_WP_SCHEMA_LANGUAGE_PATH );
			$this->updateVariableAndFixIssue();
		}

		function activePlugin() {
			$this->updateVariableAndFixIssue();
		}

		function updateVariableAndFixIssue() {
			global $KcSeoWPSchema;
			$this->fix1_2_9_to_1_2_10();
			update_option( $KcSeoWPSchema->options['installed_version'], $KcSeoWPSchema->options['version'] );
		}

		function fix1_2_9_to_1_2_10() {
			global $KcSeoWPSchema;
			$installed_version = get_option( $KcSeoWPSchema->options['installed_version'] );

			if ( version_compare( $installed_version, '1.2.9', '<=' ) ) {
				$settings = get_option( $KcSeoWPSchema->options['settings'] );
				if ( isset( $settings['contact']['contactType'] ) ) {
					$settings['contact']['contactType'] = strtolower( $settings['contact']['contactType'] );
					update_option( $KcSeoWPSchema->options['settings'], $settings );
				}
				if ( isset( $settings['area_served'] ) && ! empty( $settings['area_served'] ) ) {

					$cList = KcSeoOptions::getCountryList();
					$areas = [];
					foreach ( $settings['area_served'] as $area ) {
						$key = array_search( $area, $cList );
						if ( $key ) {
							$areas[] = $key;
						}
					}
					if ( ! empty( $areas ) ) {
						$settings['area_served'] = $areas;
						update_option( $KcSeoWPSchema->options['settings'], $settings );
					}
				}
			}
		}
	}
endif;
