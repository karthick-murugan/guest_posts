<?php
if (! class_exists ( 'GUESTPOSTSPostType' )) {
	class GUESTPOSTSPostType {
		
		/**
		 * Constructor
		 */
		function __construct() {
			// Add Hook into the 'init()' action
			add_action ( 'init', array (
					$this,
					'gp_init' 
			) );	
		}

		/**
		 * A function hook that the WordPress core launches at 'init' points
		 */
		function gp_init() {
			$this->createPostType ();
		}
				
		/**
		 * Registering the custom post type "Guest Posts"
		 */
		function createPostType() {
			$labels = array (
					'name' => __ ( 'Guest Posts', 'guest_posts' ),
					'all_items' => __ ( 'All Guest Posts', 'guest_posts' ),
					'singular_name' => __ ( 'Guest Post', 'guest_posts' ),
					'add_new' => __ ( 'Add New', 'guest_posts' ),
					'add_new_item' => __ ( 'Add New Guest Post', 'guest_posts' ),
					'edit_item' => __ ( 'Edit Guest Post', 'guest_posts' ),
					'new_item' => __ ( 'New Guest Post', 'guest_posts' ),
					'view_item' => __ ( 'View Guest Post', 'guest_posts' ),
					'search_items' => __ ( 'Search Guest Posts', 'guest_posts' ),
					'not_found' => __ ( 'No Guest Posts found', 'guest_posts' ),
					'not_found_in_trash' => __ ( 'No Guest Posts found in Trash', 'guest_posts' ),
					'parent_item_colon' => __ ( 'Parent Guest Post:', 'guest_posts' ),
					'menu_name' => __ ( 'Guest Posts', 'guest_posts' ) 
			);
			
			$args = array (
					'labels' => $labels,
					'hierarchical' => false,
					'description' => 'This is a Custom Post Type Guest Posts',
					'supports' => array (
						'title',
						'editor',
						'author',
						'excerpt',
						'comments',
						'thumbnail',
						'revisions'
					),
					
					'show_in_rest' => true,
					'public' => true,
					'show_ui' => true,
					'show_in_menu' => true,
					'menu_position' => 5,
					'menu_icon' => 'dashicons-welcome-write-blog',
					
					'show_in_nav_menus' => true,
					'publicly_queryable' => true,
					'exclude_from_search' => false,
					'has_archive' => true,
					'query_var' => true,
					'can_export' => true,
					'rewrite' => true,
					'capability_type' => 'post' 
			);
			
			register_post_type ( 'guest_posts', $args );

			// Registering the category taxonomy
			
			register_taxonomy ( "guest_posts_categories", array (
					"guest_posts" 
			), array (
					"hierarchical" => true,
					"label" => esc_html__( "Categories",'guest_posts' ),
					"singular_label" => esc_html__( "Category",'guest_posts' ),
					"show_admin_column" => true,
					"rewrite" => true,
					'show_in_rest' => true,
					"query_var" => true 
			) );

			// Registering the tags taxonomy

			register_taxonomy ( 'guest_posts_tags', array (
				'guest_posts' 
			), array (
					"label" => esc_html__( 'Tags','guest_posts' ),
					"singular_label" => esc_html__( 'Tag','guest_posts' ),
					"show_admin_column" => true,
					"rewrite" => true,
					'show_in_rest' => true,
					"query_var" => true 
			) );

		}
				
	}
}
?>