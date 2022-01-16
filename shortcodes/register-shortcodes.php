<?php
if (! class_exists ( 'GUESTPOSTSShortcodes' )) {

	/**
	 * Used to load the shortcodes created
	 *
	 * @author Karthick
	 */
	class GUESTPOSTSShortcodes {

		/**
		 * Constructor for GUESTPOSTSShortcodes
		 */
		function __construct() {
			define ( 'GUESTPOSTS_TINYMCE_URL', plugin_dir_url ( __FILE__ ) . 'tinymce' );
			define ( 'GUESTPOSTS_TINYMCE_PATH', plugin_dir_path ( __FILE__ ) . 'tinymce' );

			require_once plugin_dir_path ( __FILE__ ) . 'shortcodes.php';
			require_once plugin_dir_path ( __FILE__ ) . 'utils.php';

			// Add Hook into the 'init()' action
			add_action ( 'init', array (
					$this,
					'guestpost_init'
			) );

			// Add Hook into the 'admin_init()' action
			add_action ( 'admin_init', array (
				$this,
				'guest_posts_admin_init'
		) );
			
		}

		/**
		 * A function hook that the WordPress core launches at 'init' points
		 */
		function guestpost_init() {

			// Css files enqueue
			wp_enqueue_style( 'custom-styles', plugin_dir_url( __FILE__) . '../assets/css/styles.css' );
			wp_enqueue_style( 'bootstrap-css', plugin_dir_url( __FILE__) . '../assets/css/bootstrap.min.css' );
			
			// Js files enqueue
			wp_enqueue_script('jquery-validation', plugin_dir_url( __FILE__) . '../assets/js/jquery.validate.min.js',array('jquery'),false,true );
			wp_enqueue_script( 'custom-scripts', plugin_dir_url( __FILE__) . '../assets/js/scripts.js');

			//Ajaxurl used for creating the post using ajax method
			wp_localize_script( 'custom-scripts', 'guest_posts_plupload', array(
				'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' )),
			));	
			
			if (! current_user_can ( 'edit_posts' ) && ! current_user_can ( 'edit_pages' )) {
				return;
			}

			if( is_admin() ) { 
				
				if ("true" === get_user_option ( 'rich_editing' )) {
					//Initialize tinymice filter for adding custom icons
					add_filter ( 'mce_buttons', array (
							$this,
							'guest_posts_register_rich_buttons'
					) );
					//Add the 2 custom shortcodes via plugin
					add_filter ( 'mce_external_plugins', array (
							$this,
							'guest_posts_add_external_plugins'
					) );
				}
			}
		}

		/**
		 * A function hook that the WordPress core launches at 'admin_init' points
		 */
		function guest_posts_admin_init() {

			// tinymcs css
			wp_enqueue_style ( 'GUESTPostPlugin-tinymce-style', GUESTPOSTS_TINYMCE_URL . '/css/styles.css', false, '1.0', 'all' );
		}

		/**
		 * Adds the Guest Post rich buttons to TinyMCE
		 *
		 * @param unknown $buttons
		 * @return unknown
		 */
		function guest_posts_register_rich_buttons($buttons) {
			array_push ( $buttons, "|", "guest_posts_button" );
			return $buttons;
		}

		/**
		 * Adds Custom JS to TinyMCE
		 *
		 * @param unknown $plugins
		 * @return unknown
		 */
		function guest_posts_add_external_plugins($plugins) {

			global $wp_version;
				
			$url = GUESTPOSTS_TINYMCE_URL . '/plugin-wp.js';

			$plugins ['GUESTPostShortcodePlugin'] = $url;

			return $plugins;
		}

	}
}
?>