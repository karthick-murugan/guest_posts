<?php
if (! class_exists ( 'GUESTPOSTSCustomPostTypes' )) {
	
	/**
	 *
	 * @author Karthick
	 *        
	 */
	class GUESTPOSTSCustomPostTypes {

		function __construct() {
			
			/* Guest Posts Custom Post Type */
			require_once plugin_dir_path ( __FILE__ ) . '/guest-posts-type.php';
			if (class_exists ( 'GUESTPOSTSPostType' )) {
				new GUESTPOSTSPostType ();
			}

		}
		
	}
}
?>