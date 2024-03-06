/* ----------------- Start Document ----------------- */
(function($){
"use strict";

$(document).ready(function(){ 
    $('input[name=password]').keypress(function() {
      $('.pwstrength_viewport_progress').addClass("password-strength-visible").animate({ opacity: 1 });
    });
    

    var options = {};
    options.ui = {
        viewports: {
            progress: ".pwstrength_viewport_progress",
        },     
        colorClasses: ["bad", "short", "normal", "good", "good", "strong"],
        showVerdicts: false,
        minChar: 8,
    };
    options.common = {
        debug: true,
        onLoad: function () {
            $('#messages').text('Start typing password');
        }
    };
    $(':password').pwstrength(options);

    // Perform AJAX login on form submit
    $('#sign-in-dialog form#login').on('submit', function(e){
        var redirecturl = $('input[name=_wp_http_referer]').val();
        var success;
        $('form#login .notification').removeClass('error').addClass('notice').show().text(truelysell_login.loadingmessage);
        
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: truelysell_login.ajaxurl,
                data: { 
                    'action': 'truelysellajaxlogin', 
                    'username': $('form#login #user_login').val(), 
                    'password': $('form#login #user_pass').val(), 
                    'login_security': $('form#login #login_security').val()
                   },
             
                }).done( function( data ) {
                    if (data.loggedin == true){
                        $('form#login .notification').show().removeClass('error').removeClass('notice').addClass('success').text(data.message);
                        success = true;
                    } else {
                        $('form#login .notification').show().addClass('error').removeClass('notice').removeClass('success').text(data.message);
                    }
            } )
            .fail( function( reason ) {
                // Handles errors only
                console.debug( 'reason'+reason );
            } )
            
            .then( function( data, textStatus, response ) {
                if(success){
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: truelysell_login.ajaxurl,
                        data: { 
                            'action': 'get_logged_header', 
                        },
                        success: function(new_data){
                            $('body').removeClass('user_not_logged_in');                        
                            $('.header-widget').html(new_data.data.output);
                            var magnificPopup = $.magnificPopup.instance; 
                              if(magnificPopup) {
                                  magnificPopup.close();   
                              }
                        }
                    });
                    var post_id = $('#form-booking').data('post_id');
                    var owner_widget_id = $('.widget_listing_owner').attr('id');
                    var freeplaces = $('.book-now-notloggedin').data('freeplaces');
                    
                    if(post_id) {
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: truelysell_login.ajaxurl,
                            data: { 
                                'action': 'get_booking_button',
                                'post_id' : post_id,
                                'owner_widget_id' : owner_widget_id,
                                'freeplaces' : freeplaces

                            },
                            success: function(new_data){
                                var freeplaces = $('.book-now-notloggedin').data('freeplaces');
                                $('.book-now-notloggedin').replaceWith(new_data.data.booking_btn);
                                $('.like-button-notlogged').replaceWith(new_data.data.bookmark_btn);
                                $('#owner-widget-not-logged-in').replaceWith(new_data.data.owner_data);
                            }
                        });
                    }
                }
                
             
                // In case your working with a deferred.promise, use this method
                // Again, you'll have to manually separates success/error
            }) 
        e.preventDefault();
    });

    // Perform AJAX login on form submit
    $('#sign-in-dialog form#register').on('submit', function(e){

  		$('form#register .notification').removeClass('error').addClass('notice').show().text(truelysell_login.loadingmessage);

        var form = $('form#register').serializeArray();
        var action_key = {
              name: "action",
              value: 'truelysellajaxregister'
        }; 
        var privacy_key = {
              name: "privacy_policy",
              value: $('form#register #privacy_policy:checked').val()
        };   
      
        form.push(action_key);
        form.push(privacy_key);

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: truelysell_login.ajaxurl,
            data: form,
            success: function(data){

                if (data.registered == true){
				    $('form#register .notification').show().removeClass('error').removeClass('notice').addClass('success').text(data.message);
                    $('#register').find('input:text').val(''); 
                    $('#register input:checkbox').removeAttr('checked');
                    if(truelysell_core.autologin){
                        setTimeout(function(){
                            window.location.reload(); // you can pass true to reload function to ignore the client cache and reload from the server
                        },2000);    
                    }
                    

				} else {
					$('form#register .notification').show().addClass('error').removeClass('notice').removeClass('success').text(data.message);
                      
                    if(truelysell_core.recaptcha_status){
                        if(truelysell_core.recaptcha_version == 'v3'){
                            getRecaptcha();        
                        }
                    }
				}

            }
        });
        e.preventDefault();
    });


});



})(this.jQuery);