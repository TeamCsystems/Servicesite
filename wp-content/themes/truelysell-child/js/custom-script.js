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
                console.log(userData.staff_name);
                $('#staff_name').val(userData.staff_name);
                $('#staff_email').val(userData.staff_email);
                $('#staff_pass').val(userData.staff_pass);
                $('#staff_user_id').val(userId);
                // if (userData.access_control === '1') {
                //     $('#access_control_full').prop('checked', true);
                // } else {
                //     $('#access_control_lim').prop('checked', true);
                // }
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
                location.reload();
                $('#status-message').alert('Details Updated successfully');
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

    $('#staff_select').change(function() {
        var selectedStaffId = $(this).val(); // Get the selected staff ID
        var selectedBookingId = $(this).find(':selected').data('booking-id');
        var selectedStaffName = $(this).find(':selected').data('staff-name');
        console.log(selectedStaffName);
        var data = {
            'action': 'add_selected_staff',
            'staff_id': selectedStaffId,
            'bookingid':selectedBookingId,
            'staff_name':selectedStaffName
            
        };

        // AJAX call to send selected staff ID to the server
        $.post(my_ajax_object.ajax_url, data, function(response) {
            // Parse the JSON response
            var updatedStaffList = JSON.parse(response);

            // Update the dropdown list with the new staff members
            $('#staff_select').empty(); // Clear existing options

            // Add new options from the updated staff list
            $('#staff_select').append('<option>Choose Staff..</option>');
            updatedStaffList.forEach(function(staff) {
                $('#staff_select').append('<option value="' + staff.staff_id + '" selected>' + staff.staff_name + '</option>');
            });
        });
    });
//-----------------------END----------------------------

});

