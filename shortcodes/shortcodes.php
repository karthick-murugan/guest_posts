<?php
class GUESTPOSTSShortcodesDefination
{

    function __construct()
    {

        #Guest Post Form by Author in frontend
        add_shortcode("guest_posts_form", array(
            $this,
            "guest_posts_form"
        ));

        #Pending Post List for admin approval
        add_shortcode("pending_post_list", array(
            $this,
            "pending_post_list"
        ));

    }

    /**
     *
     * @param string $content
     * @return string
     */
    function GuestPostsShortcodeHelper($content = null)
    {
        $content = do_shortcode(shortcode_unautop($content));
        $content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
        $content = preg_replace('#<br \/>#', '', $content);
        return trim($content);
    }

    /**
     * Shortcode : Guest Post Form 
     * @param array $attrs
     * @param string $content
     * @return string
     */
    function guest_posts_form($attrs, $content = null)
    {
        extract(shortcode_atts(array(
        ) , $attrs));

        $out = '';

        if ( is_user_logged_in() ) {

            //Guest Post Form Filter
            $out = apply_filters('guest_posts_form_filter','');

            //Alert Messages Filter
            $out.= apply_filters('guest_posts_info_alerts_filter','');

        } else {

            //Alert Messages Filter
            $out = apply_filters('guest_posts_messages_filter','');
        }

        return $out;
    }

    /**
     * Shortcode : Pending Post List
     * @param array $attrs
     * @param string $content
     * @return string
     */

    function pending_post_list($attrs, $content = null)
    {
        //Passing default post type value as guest_posts
        extract(shortcode_atts(array(
            'post_type' => 'guest_posts',
        ) , $attrs));

        if( current_user_can('administrator') ) {

            //Apply filter for Pending Post List
            $out = apply_filters('pending_post_list_filter', $post_type);
            
        } else {
            //Apply filter for error messages
            $out = apply_filters('guest_posts_messages_filter','');

        }
        wp_reset_postdata();

        return $out;

    }

}
new GUESTPOSTSShortcodesDefination(); ?>
