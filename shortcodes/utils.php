<?php

    /**
    * Ajax call back function for post creation in frontend
    */
    add_action( 'wp_ajax_guest_post_form_submit', 'guest_post_form_submit' );
    add_action( 'wp_ajax_nopriv_guest_post_form_submit', 'guest_post_form_submit' );

    /**
    * Create post from frontend
    */
    function guest_post_form_submit() {
     
        /**
        * Do the nonce security check
        */
        if ( !isset($_POST['mynonce']) || !wp_verify_nonce( $_POST['mynonce'], 'myuploadnonce' ) ) {
            //Send the security check failed message
            esc_html__( 'Security Check Failed', 'guest_posts' ); 
        } else {

            //Security check cleared, let's proceed
            //Get Values from ajax
            $p_title        = sanitize_text_field($_POST['guest_post_title']);
            $p_post_type    = sanitize_text_field($_POST['guest_post_custom_post_name']);
            $p_content      = sanitize_text_field($_POST['guest_post_frontend_editor']);
			$p_excerpt      = sanitize_text_field($_POST['guest_post_excerpt']);
            $p_author       = get_current_user_id();

            //Add the post as draft in database
            $current_post = array(
                'post_title'    => $p_title,
                'post_type'     => $p_post_type,
                'post_content'  => $p_content,
				'post_excerpt'  => $p_excerpt,
                'post_author'   => $p_author,
                'post_status'   => 'draft'
            );

            $guest_post_insert_post = wp_insert_post($current_post);

            //Post Featured image upload section
            if(!function_exists('wp_generate_attachment_metadata')){
                require_once(ABSPATH . "wp-admin" . '/includes/image.php' );
                require_once(ABSPATH . "wp-admin" . '/includes/file.php' );
                require_once(ABSPATH . "wp-admin" . '/includes/media.php' );
            }

            if($_FILES) {
                foreach($_FILES as $file => $array){
                    if($_FILES[$file]['error'] !== UPLOAD_ERR_OK){
                        return "upload error :" . $_FILES[$file]['error'];
                    }
                    $attach_id = media_handle_upload( $file, $guest_post_insert_post);
                }
            }

            //After image upload, linking the thumbnail and post
            if($attach_id > 0){
                update_post_meta($guest_post_insert_post, '_thumbnail_id', $attach_id);
            }
             
            //Send the sucess message
            if($guest_post_insert_post){
                     
                //Notify admin by email
                $guest_post_author_name = get_the_author_meta('display_name', $p_author);
                $to          = get_option('admin_email');
                $subject     = esc_html__( "A New Author has submitted a post.","guest_posts" );
                $message     = $guest_post_author_name." ".esc_html__( "has submitted a new post in your site. Please review it","guest_posts" );
                $headers     = 'MIME-Version: 1.0' . "\r\n";
                $headers    .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
                $headers    .= 'To: '.$to."\r\n";
                $headers    .= 'From: '.$from."\r\n".' Return-Path: '.$from."\r\n";
                
                wp_mail($to, $subject, $message, $headers);

                echo "1";
            }
        }
        wp_die();
    }

    //Ajax call back function for publish the post by admin.
    add_action( 'wp_ajax_publish_post_by_admin', 'publish_post_by_admin' );
    add_action( 'wp_ajax_nopriv_publish_post_by_admin', 'publish_post_by_admin' );

    // Update the post from draft status to publish status by admin
    function publish_post_by_admin() {

        $post_id  = sanitize_text_field($_POST['post_id']);
        $post = array( 'ID' => $post_id, 'post_status' => 'publish' );
        if(wp_update_post($post)){
            echo "1"; 
        }

    }

    //Filter for getting all post types
    function guest_post_post_types(){

        // Get Custom Post type values
        $args = array(
            'public' => true,
            '_builtin' => false
        );
        $post_types = get_post_types($args);
        $out = '<option value="" selected disabled>'.esc_html__( "Select the Custom Posts","guest_posts" ).'</option>';
        foreach ($post_types as $post_type) {
            $post_type_name = get_post_type_object($post_type);
            $out .= '<option value="' . $post_type . '">' . $post_type_name->labels->name . '</option>';
        }
        return $out;
    }

    add_filter('guest_post_post_types_filter','guest_post_post_types');

    //Filter for loading the frontend editor
    function guest_posts_front_end_editor() {

        $content = '';
        $editor_id = 'guest_post_frontend_editor';
        $settings = array(
            'wpautop' => true, // use wpautop
            'media_buttons' => true, // show insert/upload button(s)
            'textarea_name' => $editor_id, // set the textarea name
            'textarea_rows' => get_option('default_post_edit_rows', 10) ,
            'tinymce' => true, // load TinyMCE 
        );

            // Turn on the output buffer
            ob_start();

            // echo the editor to the buffer
            wp_editor($content, $editor_id, $settings = array());

            //Store the content of the buffer in a variable
            $guest_post_editor = ob_get_clean();

        return $guest_post_editor;
    }

    add_filter('guest_posts_front_end_editor_filter','guest_posts_front_end_editor');

    //Filter for displaying the post creation form
    function guest_posts_form_template(){

        $out = '<form name="guest-post-submit-form" id="guest-post-submit-form"  action="" enctype="multipart/form-data" method="post">
			<div class="container contact">
				<div class="row">
					<div class="col-md-10">
						<div class="contact-form">
							<div class="form-group">
							<label class="control-label col-sm-10" for="fname">'.esc_html__( "Post Title:","guest_posts" ).'</label>
							<div class="col-sm-10">          
								<input type="text" class="form-control" placeholder="'.esc_html__( "Enter the Post Title","guest_posts" ).'" name="guest_post_title" id="guest_post_title" required>
							</div>
							</div>
							<div class="col-md-10">
								<div class="form-group"> <label for="form_need">'.esc_html__( "Select the Custom Posts","guest_posts" ).'</label>
								<select class="form-control" name="guest_post_custom_post_name" id="guest_post_custom_post_name" required>'.apply_filters('guest_post_post_types_filter','').'</select>
								</div>
							</div>
							<div class="form-group">
							<label class="control-label col-sm-10" for="guest-post-editor">'.esc_html__( "Description:","guest_posts" ).'</label>
							<div class="col-sm-10">' . apply_filters('guest_posts_front_end_editor_filter','') . '</div>
							</div>
							
                            <div class="form-group">
							<label class="control-label col-sm-10" for="fname">'.esc_html__( "Post Featured Image:","guest_posts" ).'</label>
							<div class="col-sm-10">          
								<input type="file" class="form-control" name="guest_post_featured_image" id="guest_post_featured_image" required>
                                '. wp_nonce_field( 'myuploadnonce', 'mynonce' ).'
							</div>
							</div>
                            <div class="form-group">
							<label class="control-label col-sm-10" for="excerpt">'.esc_html__( "Excerpt:","guest_posts" ).'</label>
							<div class="col-sm-10">
								<textarea class="form-control" rows="3" name="guest_post_excerpt" id="guest_post_excerpt" required></textarea>
							</div>
							</div>
							<div class="form-group">        
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-default guest-posts-submit"> '.esc_html__("Submit Post", "guest_posts").'</button>
                                <div class="button_loading" style="display:none;">'.esc_html__( "Please wait...","guest_posts" ).'</div>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>';
        return $out;
    }

    add_filter('guest_posts_form_filter','guest_posts_form_template');

    // Filter for listing the pending posts
    function pending_post_list_for_approval($post_type){

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args = array('post_type' => $post_type,'posts_per_page' => '10','post_status' => 'draft','paged' => $paged);
        // Define our WP Query Parameters
        $the_query = new WP_Query( $args); 
        $max_num_pages = $the_query->max_num_pages;
        if($the_query->have_posts()): 
            $out = '<p class="h3">'.esc_html__("List of Posts for Admin Approval", "guest_posts").'</p>
            <div class="alert alert-success" role="alert" style="display:none;">
            '.esc_html__("The selected post has been published.", "guest_posts").'
            </div>
            <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">'.esc_html__("Title", "guest_posts").'</th>
                                <th scope="col">'.esc_html__("Action", "guest_posts").'</th>
                            </tr>
                        </thead>
                    <tbody>
                        ';
            $i = 1;

            // Start our WP Query 
            while ($the_query -> have_posts()) : $the_query -> the_post(); 
                $out .= '<tr style="text-align:center">';
                    $out .= '<th scope="col">'.$i.'</th>';
                    // Display the Post Title with Hyperlink
                    $out .= '<td scope="col">'.get_the_title().'</td>';
                    $out .= '<td scope="col"><a href="javascript:void(0);" class="publish-post"  id="publish-post-'.get_the_id().'" data-postid="'.get_the_id().'">'.esc_html__("Publish Now", "guest_posts").'</a></td>';
                $out .= '</tr>';
            $i++;
            endwhile;
            $out .= '</tbody>
            </table>';
            $out .= apply_filters('guest_posts_pagination_filter',$max_num_pages);
        else:
            $out = '<div class="alert alert-danger" role="alert">'.esc_html__("No draft items found right now...", "guest_posts").'';
        endif;

        return $out;
    }

    add_filter('pending_post_list_filter','pending_post_list_for_approval');

    //Filter for adding pagination for the draft posts
    function guest_posts_pagination($max_num_pages) {

        $out = '<div class="guest_post_pagination">';
            $out .= '<div class="column one pager_wrapper">';
                    $big = 999999999;
                    $out .=  paginate_links( array(
                        'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
                        'format' => '?paged=%#%',
                        'current' => max( 1, get_query_var('paged') ),
                        'total' => $max_num_pages,
                        'type'  => 'list',
                        'prev_text' => esc_html__("<", "guest_posts"),
                        'next_text' => esc_html__(">", "guest_posts"),
                    ) );
            $out .= '</div>';
        $out .= '</div>';

        return $out;
    }
    add_filter('guest_posts_pagination_filter','guest_posts_pagination');

    //Filter for Warning messages
    function guest_posts_messages(){

        $out = '';
        if (!is_user_logged_in() ) {
            $out .= '<div class="alert alert-danger" role="alert">
            '.esc_html__("Only logged in Authors can submit the post. Please login by ", "guest_posts").'';
            $out .= '<a target="_blank" href='.wp_login_url().'>'.esc_html__("Clicking Here", "guest_posts").'</a></div>';
        } else {
            $out .= '<div class="alert alert-danger" role="alert">
            '.esc_html__("Only Administrators can view and publish the post. Please login by ", "guest_posts").'';
            $out .= '<a target="_blank" href='.wp_login_url().'>'.esc_html__("Clicking Here", "guest_posts").'</a></div>';
        }

        return $out;
    }
    add_filter('guest_posts_messages_filter','guest_posts_messages');

    //Filter for Info messages
    function guest_posts_info_alerts(){

        $out = '<div class="alert alert-success" role="alert" style="display:none;">
            '.esc_html__("Post has been Submitted. Please wait for admin approval", "guest_posts").'
            </div>
            <div class="alert alert-danger" role="alert" style="display:none;">
            '.esc_html__("Sorry, cannot submit your post. Please try again later", "guest_posts").'
            </div>';
        return $out;
    }
    add_filter('guest_posts_info_alerts_filter','guest_posts_info_alerts');
?>