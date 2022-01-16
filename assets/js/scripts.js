jQuery(document).ready(function($) {

   // Form validation using jQuery Validation Plugin
   $("#guest-post-submit-form").validate({
       messages: {
           guest_post_title: "Please enter the Post title",
           guest_post_custom_post_name: "Please Select the Custom Post Type",
           guest_post_excerpt: "Please enter the excerpt content",
           guest_post_featured_image: "Please choose a featured image",
       },
       submitHandler: function() {

            // Guest Post form sending data via ajax
            var myGuestPostsForm = $('#guest-post-submit-form');
            $(myGuestPostsForm).submit(function(e) {
                
                //Prevent normal form submission
                e.preventDefault();

                //Get the form data and store in a variable
                var myGuestPostsFormData = new FormData(myGuestPostsForm[0]);

                //Add our own action to the data via append 
                //action is where we will be hooking our php function
                myGuestPostsFormData.append('action', 'guest_post_form_submit');

                //Prepare and send the call
                $.ajax({
                    type: "POST",
                    data: myGuestPostsFormData,
                    dataType: "json",
                    url: ajaxurl,
                    cache: false,
                    processData: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    beforeSend: function() {
                        $('.button_loading').show();
                    },
                    complete: function(data) {
                        $('.button_loading').hide();
                    },
                    success: function(response) {
                        // Empty the field values after form submit
                        $('#guest-post-submit-form').each(function() {
                            this.reset();
                        });
                        //Success Message
                        $(".alert-success").css("display", "block");
                        setTimeout(function() {
                            $('.alert-success').fadeOut();
                        }, 2000);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        //Error Message
                        $(".alert-warning").css("display", "block");
                        setTimeout(function() {
                            $('.alert-warning').fadeOut();
                        }, 2000);
                    }
                });

            });
       }
   });


   // Admin publish the post via ajax method
   $('.publish-post').on('click', function () {     

         var $this = $(this);
         var post_id = $this.data('postid');
         $.ajax({
            url: guest_posts_plupload.ajaxurl,
            type:"POST",
            data: {
               action : 'publish_post_by_admin',
               post_id: post_id,
            }, 
            beforeSend: function() {
               $('#publish-post-'+post_id).append('<div class="spinner-grow spinner-grow-sm"></div>');
            },
            complete: function(data) {
               $(".spinner-grow").remove();
            },
            success: function(response){
                  //Success Message
                  $(".alert-success").css("display","block");
                  setTimeout(function(){
                  $('.alert-success').fadeOut();
                  },2000);
                  setTimeout(location.reload.bind(location), 300);
            }, error: function(data){
                  //Error Message
                  $(".alert-warning").css("display","block"); 
                  setTimeout(function(){
                     $('.alert-warning').fadeOut();
                  },2000);     
            }
         });
      });
});