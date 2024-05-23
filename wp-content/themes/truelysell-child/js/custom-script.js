jQuery(document).ready(function($) {

//----------Active or Inactive User-------------------
    $('.status-toggle').click(function(e) {
  
        e.preventDefault();
        var id = $(this).data('id');
        var newStatus = $(this).data('status');
        $('#status-message').text('Please wait...');
        $.ajax({
            url: my_ajax_object.ajax_url,
            type: 'post',
            data: {
                action: 'update_status',
                id: id,
                status: newStatus,
                security: $('.status-nonce').val() // Pass nonce for security verification
            },
            success: function(response) {
            console.log(response);
                // Handle the response from the server
                $('#status-message').text('Status updated successfully');
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error updating status: ' + error);
            }
        });
    });
//-----------------Delete Staff------------------
    jQuery(document).ready(function($) {
        $('.delete-user').click(function(e) {
            e.preventDefault();
            var userId = $(this).data('user-id');
            if (confirm('Are you sure you want to delete this Staff?')) {
                $.ajax({
                    url: my_ajax_object.ajax_url,
                    type: 'post',
                    data: {
                        action: 'delete_user',
                        user_id: userId,
                    },
                    success: function(response) {
                            // Optionally, update UI to reflect the deletion
                            $('#status-message').text('Staff deleted successfully');
                            location.reload(); // Reload the page after deletion
                        
                    },
                    error: function(xhr, status, error) {
                        alert('Error deleting user: ' + error);
                    }
                });
            }
        });
    });

//----------------Edit or Update Staff Details-----------
    // Close button click event

    $('.close').click(function() {
        $(this).closest('.modal').modal('hide');
    });

    // Edit user click event
    $('.edit-user').click(function(e) {
        e.preventDefault();
        
        var userId = $(this).data('id');

        // Fetch user data and populate the form fields
        $.ajax({
            url: my_ajax_object.ajax_url,
            type: 'post',
            data: {
                action: 'fetch_user',
                user_id: userId
            },
            dataType: "json",
            success: function(response) {
            console.log(response.data);
                var userData = JSON.parse(JSON.stringify(response.data));
                console.log(userData.display_name);
                $('#staff_name').val(userData.display_name);
                $('#staff_email').val(userData.user_email);
                $('#staff_pass').val(userData.user_pass);
                $('#staff_user_id').val(userId);
                 if (userData.access_control_value === '1') {
                     $('#access_control_full').prop('checked', true);
                 } else {
                     $('#access_control_lim').prop('checked', true);
                 }
            },
            error: function(xhr, status, error) {
                console.log('sfsdf');
                alert('Error fetching user data: ' + error);
            }
        });

        // Show the modal
        $('#editStaffModal').modal('show');
    });

    // Submit button event binding
    $('#update-staff-form').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        $('#status-message').text('Please wait...');
        $.ajax({
            url: my_ajax_object.ajax_url,
            type: 'post',
            data: formData + '&action=update_user_data',
            success: function(response) {
                $('#editStaffModal').modal('hide');
                $('#status-message').text('Details Updated successfully');
                setTimeout(function() {
		location.reload();
	    }, 2000);
            },
            error: function(xhr, status, error) {
                alert('Error updating user data: ' + error);
            }
        });
    });
    
//-----------------End----------------------------
  
 //-------------Start Login mesage function--------------- 
   var timeoutDuration = 3000;
    function hideSuccessMessage() {
        $('.success-message').fadeOut(); 
    }
    setTimeout(hideSuccessMessage, timeoutDuration);

//-------------Login Messages End-------

//-----------Assigning Staff and fetch--------------------

    $('.staff_select').change(function() {
        var selectedStaffId = $(this).val(); // Get the selected staff ID
        var selectedBookingId = $(this).find(':selected').data('booking-id');
        var selectedStaffName = $(this).find(':selected').data('staff-name');
        var selectedBusinessId = $(this).find(':selected').data('business-id');
        console.log(selectedStaffName);
        var data = {
            'action': 'add_selected_staff',
            'staff_id': selectedStaffId,
            'bookingid':selectedBookingId,
            'staff_name':selectedStaffName,
            'business_id':selectedBusinessId
            
        };

        // AJAX call to send selected staff ID to the server
        $.post(my_ajax_object.ajax_url, data, function(response) {
            // Parse the JSON response
            var updatedStaffList = JSON.parse(response);
            console.log(updatedStaffList);
	    if(updatedStaffList){
            // Update the dropdown list with the new staff members
            //$('.staff_select').empty(); // Clear existing options
            // Add new options from the updated staff list
            //$('.staff_select').append('<option>Choose Staff..</option>');
            // updatedStaffList.forEach(function(staff) {
            //   // $('.staff_select').append('<option value="' + staff.staff_id + '" selected="selected">' + staff.staff_name + '</option>');
                
            // });
            var messageContainer = $('.message-container');
            messageContainer.html('<div class="alert alert-success">Staff Assigned successfully!</div>');
             setTimeout(function() {
                messageContainer.empty();
                window.location.reload();
            }, 3000);
            }else{
            var messageContainer = $('.message-container');
            messageContainer.html('<div class="alert alert-danger">Error: Could not change staff.</div>');
             setTimeout(function() {
                messageContainer.empty();
            }, 3000);
            }
            
           
        });
    });
//-----------------------END----------------------------
//------------------Share Services With Social Link-----
    jQuery(document).ready(function($) {
    $('#share-button').click(function(event) {
        event.preventDefault(); // Prevent the default anchor behavior
        $('#social-icons').toggle(); // Toggle the display of social icons
    });

    $('.social-icon').click(function(event) {
        event.preventDefault(); // Prevent the default anchor behavior

        var postTitle = $('.single-listing .service_title').text(); // Get the post title
        var postUrl = window.location.href; // Get the post URL

        var network = $(this).data('network'); // Get the selected social network

        var shareUrl;

        // Construct the sharing URL based on the selected social network
        switch (network) {
            case 'twitter':
                shareUrl = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(postTitle) + '&url=' + encodeURIComponent(postUrl);
                break;
            case 'facebook':
                shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(postUrl);
                break;
            case 'linkedin':
                shareUrl = 'https://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(postUrl) + '&title=' + encodeURIComponent(postTitle);
                break;
            case 'whatsapp':
                shareUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(postTitle + ': ' + postUrl);
                break;
            case 'waze':
                shareUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(postTitle + ': ' + postUrl);
                break;
            default:
                return; // Do nothing if an unsupported network is selected
        }

        // Open the sharing URL in a new window
        window.open(shareUrl, '_blank');

        // Hide the social icons after sharing
        $('#social-icons').hide();
    });
});
//-----------------------END----------------

//----------Booking Availability Popup--------------
  $('.show-modal').click(function(e) {
        e.preventDefault();
        var staffId = $(this).data('id');

        $.ajax({
            url: my_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'fetch_booking',
                staff_id: staffId
            },
            success: function(response) {
                // Append the fetched content to the modal
                $('.modal-body').html(response);
                // Show the modal
                $("#testmodal").modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error updating status:', error);
            }
        });
    });
  
//----------End-----------------------
// Change input type from text to email on Add listing Service
    $('#_email').attr('type', 'email');
    $('#_phone').attr('type', 'tel');
     // Adding custom validation for email format
  $('#submit-listing-form input[name="_email"]').on('input', function() {
    var email = $(this).val();
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regex.test(email)) {
      this.setCustomValidity('Please enter a valid email address.');
    } else {
      this.setCustomValidity('');
    }
  });
  
  // Adding custom validation for phone number (maximum 10 digits)
  $('#submit-listing-form input[name="_phone"]').on('input', function() {
    var phone = $(this).val();
    var regex = /^\d{10}$/;
    if (!regex.test(phone)) {
      this.setCustomValidity('Please enter a valid phone number (up to 10 digits).');
    } else {
      this.setCustomValidity('');
    }
  });
//Make required field on Customer Profile Section
$('#edit_user input[name="first-name"]').prop('required', true);
$('#edit_user input[name="last-name"]').prop('required', true);
  $('#edit_user input[name="email"]').prop('required', true).attr('type', 'email');
  $('#edit_user input[name="phone"]').prop('required', true).attr('type', 'tel');
  $('#edit_user input[name="email"]').on('input', function() {
    var email = $(this).val();
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regex.test(email)) {
      this.setCustomValidity('Please enter a valid email address.');
    } else {
      this.setCustomValidity('');
    }
  });
  
  // Adding custom validation for phone number (maximum 10 digits)
  $('#edit_user input[name="phone"]').on('input', function() {
    var phone = $(this).val();
    var regex = /^\d{10}$/;
    if (!regex.test(phone)) {
      this.setCustomValidity('Please enter a valid phone number (up to 10 digits).');
    } else {
      this.setCustomValidity('');
    }
  });
  //----------End-----------------------


});
