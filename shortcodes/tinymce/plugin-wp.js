(function() {

	// add GUESTPostShortcodePlugin plugin
	tinymce.PluginManager.add("GUESTPostShortcodePlugin",function( editor , url ) {
	
		//Adds a button to the tinymce editor
		editor.addButton('guest_posts_button', {
			title : "Guest Post Shortcodes",
			icon : "guest-post-icon",
			type: 'menubutton',
			menu: [
					//Inserting the Guest Post Form shortcode to the content area in editor 
					{ text: 'Guest Post Form', onclick: function(e){
						e.stopPropagation();
						editor.insertContent('[guest_posts_form]');
					}},

					//Inserting the Pending Post List shortcode to the content area in editor 
					{ text: 'Pending Post List', onclick: function(e){
						e.stopPropagation();
						editor.insertContent('[pending_post_list post_type="guest_posts"]');
					}},

			]
		});
		
	});
})();