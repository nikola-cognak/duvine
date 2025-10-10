<?php
if (! class_exists('KcSeoMigration')):
	class KcSeoMigration {
		public function __construct() {
			add_action( 'admin_init', function () { 
				$notice = false;
				if ( is_admin() && post_type_exists( 'ranna_recipe' ) ) {
					$notice = true;
				}
				if ( $notice ) {
					if (get_option('kcseo_migration_dismiss') != '1') {
						if (! isset($GLOBALS['kcseo_migration_dismiss_notice'])) {
							$GLOBALS['kcseo_migration_dismiss_notice'] = 'kcseo_migration_dismiss';
							self::notice();
						}
					}
				}
			} );
		}

		/**
		 * Undocumented function.
		 *
		 * @return void
		 */
		public static function notice() {

			add_action( 'admin_enqueue_scripts', function () {
				wp_enqueue_script('jquery');
			} );

			add_action( 'admin_notices', function () { 
				$plugin_name = 'Migrate data with latest the version'; ?>
				<div class="notice notice-info is-dismissible" data-kcseodismissable="kcseo_migration_dismiss" style="padding-top: 25px; padding-bottom: 22px;"> 
					<h3 style="margin:0;"><?php echo sprintf('%s', $plugin_name); ?></h3> 
					<p style="margin:0 0 2px;">
						<?php esc_html_e("We've updated some schema code with latest Google structure, That's why we need to migrate some field with the latest plugin.", 'wp-seo-structured-data-schema'); ?> 
					</p> 
					<p style="margin:0;">
						<a class="button button-primary migration-button" href="#" target="_blank">Migrate Now</a>
						<a class="button migration-button-dismiss" href="#">Dismiss</a>
					</p>
				</div>
				<?php
			} );

			add_action( 'admin_footer', function () { ?>
				<script type="text/javascript">
					(function ($) {
						$(function () { 
							$(document).on("click", 'div[data-kcseodismissable] .migration-button', function(e) { 
								e.preventDefault(); 
								$.post(ajaxurl, {
									'action': 'kcseo_migration',
									'nonce': <?php echo json_encode(wp_create_nonce('kcseo-migration-notice')); ?>
								}, function(data, status){
									if ( status ) {  
										$(e.target).closest('.is-dismissible').find('h3').html('<?php esc_html_e("Successfully migrated :)", 'wp-seo-structured-data-schema'); ?>');
										$(e.target).remove();
									}
								}); 
							});
							
							//dismiss
							$(document).on("click", 'div[data-kcseodismissable] .notice-dismiss, div[data-kcseodismissable] .migration-button-dismiss', function(e) { 
								e.preventDefault();  
								$.post(ajaxurl, {
									'action': 'kcseo_migration_dismiss',
									'nonce': <?php echo json_encode(wp_create_nonce('kcseo-migration-notice')); ?>
								});
								$(e.target).closest('.is-dismissible').remove();
							}); 
						});
					})(jQuery);
				</script>
				<?php
			} );

			add_action( 'wp_ajax_kcseo_migration', function () {
				check_ajax_referer('kcseo-migration-notice', 'nonce');
				
				if ( ! get_option('kcseo_migration') ) {
					$allRecipe = get_posts(
						array(
							'post_type'      => 'ranna_recipe',
							'posts_per_page' => -1,
							'fields'         => 'ids',
						)
					);  

					if ( is_array( $allRecipe ) && ! empty( $allRecipe ) ) {
						foreach ( $allRecipe as $recipe_id ) { 
							$default = get_post_meta( $recipe_id, '_schema_recipe', true ); 
							if ( isset( $default['recipeInstructions'] ) && $default['recipeInstructions'] ) {   
								if ( ! ( isset( $default['recipe_instructions'][0]['text'] ) && $default['recipe_instructions'][0]['text'] ) ) {
									$default['recipe_instructions'][0]['text'] = $default['recipeInstructions'];
								} 
							}
							
							if ( isset( $default['video'] ) && $default['video'] ) {  
								if ( ! ( isset( $default['video_info'][0]['contentUrl'] ) && $default['video_info'][0]['contentUrl'] ) ) {
									$default['video_info'][0]['contentUrl'] = $default['video']; 
								} 
							}

							update_post_meta( $recipe_id, '_schema_recipe', $default );
						}
					} 
					update_option('kcseo_migration', '1', false);
					update_option('kcseo_migration_dismiss', '1', false);
					wp_send_json_success();
				}	 
				
			} );

			add_action( 'wp_ajax_kcseo_migration_dismiss', function () {
				check_ajax_referer('kcseo-migration-notice', 'nonce');
				update_option('kcseo_migration_dismiss', '1', false);
				wp_die();
			} );
		}
	}
endif;
