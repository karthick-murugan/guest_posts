<?php
/*
 * Plugin Name:	Guest Post Submission Plugin
 * URI: 		http://localhost/guest_posts/
 * Description: A simple wordpress plugin to add guest posts for authors
 * Version: 	1.0
 * Author: 		Karthick
 * Author URI:	https://www.linkedin.com/in/karthick-m-a4221b201/
 */
if (! class_exists ( 'Guest_Posts_Plugin' )) {

	/**
	 * Basic class to load Shortcodes & Custom Posts
	 *
	 * @author Karthick
	 */
	class Guest_Posts_Plugin {

		function __construct() {

			$this->plugin_dir_path = plugin_dir_path ( __FILE__ );

			// Add Hook into the 'init()' action
			add_action ( 'init', array (
					$this,
					'GuestPostTextDomain'
			) );

			// Register Shortcodes
			require_once plugin_dir_path ( __FILE__ ) . '/shortcodes/register-shortcodes.php';

			if (class_exists ( 'GUESTPOSTSShortcodes' )) {
				$guest_posts_shortcodes = new GUESTPOSTSShortcodes();
			}

			// Register Custom Post Types
			require_once plugin_dir_path ( __FILE__ ) . '/custom-post-types/register-post-types.php';

			if (class_exists ( 'GUESTPOSTSCustomPostTypes' )) {
				$guest_posts_custom_posts = new GUESTPOSTSCustomPostTypes();
			}

			
		}

		/**
		 * To load text domain
		 */
		function GuestPostTextDomain() {
			load_plugin_textdomain ( 'guest_posts', false, dirname ( plugin_basename ( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Fired during plugin activation.
		 */
		public static function guestPostsPluginActivate() {
		}

		/**
		 * Fired during plugin de-activation.
		 */
		public static function guestPostsPluginDectivate() {
		}
	}
}

if (class_exists ( 'Guest_Posts_Plugin' )) {

	//Setting the activation hook for this plugin.

	register_activation_hook ( __FILE__, array (
			'Guest_Posts_Plugin',
			'guestPostsPluginActivate'
	) );

	//Setting the de-activation hook for this plugin.
	register_deactivation_hook ( __FILE__, array (
			'Guest_Posts_Plugin',
			'guestPostsPluginDectivate'
	) );

	$guest_posts_plugin = new Guest_Posts_Plugin ();
}

?>