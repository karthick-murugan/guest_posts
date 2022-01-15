(function() {

	// add GUESTPostShortcodePlugin plugin
	tinymce.PluginManager.add("GUESTPostShortcodePlugin",function( editor , url ) {
	
		editor.addButton('guest_posts_button', {
			title : "Guest Post Shortcodes",
			icon : "guest-post-icon",
			type: 'menubutton',
			menu: [

					{ text: 'Guest Post Form', onclick: function(e){
						e.stopPropagation();
						editor.insertContent('[guest_posts_form]');
					}},

					{ text: 'Pending Post List', onclick: function(e){
						e.stopPropagation();
						editor.insertContent('[pending_post_list post_type="guest_posts"]');
					}},

			]
		});
		
	});
})();