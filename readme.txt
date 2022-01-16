Guest Post Submission Plugin

The below 3 video links, explains the Visual Demo of the Guest Post Plugin task which I have done.

http://recordit.co/WExC0WuBmb
http://recordit.co/wJx9tMDfC2
http://recordit.co/FFIw6eoBi9

1) Create a new user with Author role.

2) Create custom post "Guest Posts".

3) Post Creation form in frontend using shortcode. The shortcode to create Post Creation form is [guest_posts_form] Please have a look at this screenshot https://prnt.sc/26edfdc for the dropdown option in the editor.

4) Form should be visible only to logged in authors.

5) Form Fields

    a) Post Title
    b) Custom Post Type Dropdown
    c) Description
    d) Excerpt
    e) Featured Image

6) Submit the form via ajax and save the post in draft status.

7) Admin should receive an email for post moderation.

8) Admin publishes the post.

9) Create another shortcode to show list of post which are in pending status for admin approval. The shortcode to list the post for admin approval is [pending_post_list post_type="guest_posts"]. You can pass post_type value of different custom post type and can list the draft items of that particular custom post type. Please have a look at this screenshot https://prnt.sc/26edgdx for the dropdown option in the editor.

10) Paginate the post entries if more than 10 posts.

11) Setup private GIT repository for this project and share.