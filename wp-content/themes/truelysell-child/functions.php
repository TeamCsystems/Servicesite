<?php
//
// Recommended way to include parent theme styles.
//   


add_action( 'wp_enqueue_scripts', 'truelysell_child_enqueue_styles', 10 );
function truelysell_child_enqueue_styles() {

  $parenthandle = 'truelysell-style';
  $theme = wp_get_theme();

  wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css',
      array('bootstrap'),  // if the parent theme code has a dependency, copy it to here
      $theme->parent()->get('Version')
  );
  wp_enqueue_style( 'truelysell-child-style', get_stylesheet_directory_uri() . '/style.css',
      array( $parenthandle ),
      $theme->get('Version')
  );
 
}
function enqueue_child_theme_scripts() {
  // Get parent theme version
  $theme = wp_get_theme();
  $parent_theme_version = $theme->parent()->get('Version');

  // Enqueue child theme script
  wp_enqueue_script( 'child-theme-script', get_stylesheet_directory_uri() . '/js/custom-script.js', array(), $parent_theme_version, true );
  wp_localize_script( 'child-theme-script', 'my_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
    );
}
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_scripts' );
//-----------New Role------------
add_role('staff', 'Staff', array(
'read' => true,
'create_posts' => true,
'edit_posts' => true,
'edit_others_posts' => true,
'publish_posts' => true,
'manage_categories' => true,
));
//--------------Image Path Update on all pages-----------------------
function change_image_src_url_for_about_us_page( $content ) {
    // for Home page
    $old_url = 'https://truelysell-wp.dreamstechnologies.com/multipurpose/wp-content/uploads/2023/05';
    $new_url = 'https://truelysell-wp.dreamstechnologies.com/multipurpose/wp-content/uploads/2024/01';
    
    // Define your old and new URLs for other pages
        $old_url_pages = 'https://truelysell-wp.dreamstechnologies.com/multipurpose/wp-content/uploads/2023/';
        $new_url_pages = 'https://truelysell-wp.dreamstechnologies.com/multipurpose/wp-content/uploads/2024/';
    if ( !is_front_page() ) {
        
        $content = str_replace( $old_url_pages, $new_url_pages, $content );
    }
    else{
    $content = str_replace( $old_url, $new_url, $content );
    }

    return $content;
}
add_filter( 'the_content', 'change_image_src_url_for_about_us_page' );
add_filter( 'the_excerpt', 'change_image_src_url_for_about_us_page' );

//-----------------------END------------------------------------------


function hide_admin_for_staff() {
    if (is_user_logged_in() && current_user_can('staff')) {
        // Redirect staff users to the front-end of the site
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'hide_admin_for_staff');
// Your Custom code goes below
function custom_login_redirect( $redirect_to, $requested_redirect_to, $user ) {
    // Check if the user is not an administrator
    
    if ( !is_wp_error( $user ) && user_can( $user, 'staff' ) ) {
        // Replace '/your-template-slug/' with the slug of your desired template
        $redirect_to = home_url( '/staff-dashboard/' );
    }

    return $redirect_to;
}
add_filter( 'login_redirect', 'custom_login_redirect', 10, 3 );

//------------------ Adding a Staff----------------

function staff_form_submission() {
    if (isset($_POST['submit'])) {
        // Sanitize form data
        $staff_name = sanitize_text_field($_POST['staff_name']);
        $staff_email = sanitize_email($_POST['staff_email']);
        $staff_pass = sanitize_text_field($_POST['staff_pass']);
        $access_control = isset($_POST['access_control']) ? sanitize_text_field($_POST['access_control']) : '';

        // Check if the email already exists
        $existing_user_id = email_exists($staff_email);
        if ($existing_user_id) {
            // Email already exists, handle accordingly (e.g., show error message)
            //echo 'Email already exists. Please choose a different email address.';
             ?><script>alert('Email already exists. Please choose a different email address.');</script><?php
        }

        // Get the current user's ID
        $current_user_id = get_current_user_id();
       $current_user_email = get_userdata($current_user_id)->user_email;
       $current_user_name = get_userdata($current_user_id)->display_name;
       $site_url = site_url();
    

        // Check if the user is logged in
        if ($current_user_id !== 0) {
            global $wpdb;

            // Insert data into the database
            $user_id = wp_insert_user(array(
                'user_login' => $staff_email,
                'user_email' => $staff_email,
                'user_pass'  => $staff_pass,
                'first_name' => $staff_name,
                // You can add more fields here as needed
            ));

            if (!is_wp_error($user_id)) {
                $staff_role = 'staff';
                $user = new WP_User($user_id);
                $user->set_role($staff_role);
                // Add custom meta data for the user
                update_user_meta($user_id, 'access_control', $access_control);
                update_user_meta($user_id, 'business_id', $current_user_id);
                
                
                // Send success email to newly added staff member
                $staff_subject = 'Welcome to kaltime Service Portal!';
                // $staff_message = 'Hello ' . $staff_name . ', your account has been successfully created.';
                $staff_message = "Dear $staff_name,\n\n";
$staff_message .= "We're thrilled to welcome you aboard as a staff of our team! Your account has been successfully created by $current_user_name, and you now have access to our staff portal.\n\n";
$staff_message .= "At kaltime, we value your contributions and look forward to working together to achieve our goals. The staff portal provides you with valuable resources, tools, and information to support you in your role.\n\n";
$staff_message .= "Please find your account login details:\n\n";
$staff_message .= "Website URL's: $site_url\n";
$staff_message .= "Username: $staff_email\n";
$staff_message .= "Password: $staff_pass\n\n";
$staff_message .= "If you have any questions or need assistance navigating the portal, please don't hesitate to reach out to our support team.\n\n";
$staff_message .= "Once again, welcome to the team! We're excited to have you with us.\n\n";
$staff_message .= "Best regards,\n";
$staff_message .= "$current_user_name\n";
$staff_message .= "kaltime";
                wp_mail($staff_email, $staff_subject, $staff_message);

                // Send success email to owner
                $owner_email = $current_user_email;
                $owner_subject = 'Staff Account Created';
                $owner_message = 'Hello, a new staff account has been created for ' . $staff_name . ' (' . $staff_email . ').';
                wp_mail($owner_email, $owner_subject, $owner_message);
                // Redirect after form submission
                wp_redirect(home_url('/all-staffs'));
                exit();
            } else {
                // Error occurred during user insertion, handle accordingly
                ?><script>//alert('Error occurred during user creation. Please try again.');</script><?php
               // echo 'Error occurred during user creation. Please try again.';
            }
        }
    }
}



add_action( 'template_redirect', 'staff_form_submission' );

//--------------------END----------------------------


//------------------ Displaying All Staff------------

function get_user_data_list() {
  // Get the current user's ID
  $current_user_id = get_current_user_id();

  // Check if the user is logged in
  if ($current_user_id !== 0) {
      global $wpdb;
     // $table_name = $wpdb->prefix . 'staffs'; 

      // Query the database for data associated with the current user
  $query = $wpdb->prepare("SELECT u.ID,u.display_name, u.user_email,u.user_status, um1.meta_key AS business_id_key, um1.meta_value AS business_id_value, um2.meta_key AS access_control_key, um2.meta_value AS access_control_value
    FROM {$wpdb->users} AS u
    LEFT JOIN {$wpdb->usermeta} AS um1 ON u.ID = um1.user_id AND um1.meta_key = 'business_id'
    LEFT JOIN {$wpdb->usermeta} AS um2 ON u.ID = um2.user_id AND um2.meta_key = 'access_control'
    WHERE um1.meta_value = %d", $current_user_id);
      $results = $wpdb->get_results( $query );

      // Check if there are results
      if ( $results ) {
          // Process the results
          $serial_number = 1;
          echo '<div style="color:blue;" id="status-message"></div><br>';
          echo '<table>';
          echo '<tr><th>S.no</th><th>Name</th><th>Email</th><th>Status</th><th>Access</th><th>Action</th><th>Booking Assigned</th></tr>';
          foreach ( $results as $result ) {
              // Do something with each row of data
              echo '<tr><td>' .$serial_number++ . '</td>
              <td>' . esc_html( $result->display_name ) . '</td>
              <td>' . esc_html( $result->user_email ) . '</td>';
              // Inside your loop generating status toggle links
echo '<td><a href="#" class="status-toggle" data-id="' . esc_attr($result->ID) . '" data-status="' . esc_attr($result->user_status) . '"><img src="' . esc_url($result->user_status == '0' ? get_stylesheet_directory_uri().'/uploads/images/active-user.png' : get_stylesheet_directory_uri().'/uploads/images/delete.png') . '" width="40px" height="40px" /></a><input type="hidden" class="status-nonce" value="' . esc_attr(wp_create_nonce('update-status-nonce')) . '"></td>';


                 echo '<td>';
                 if ($result->access_control_value == '1') {
                     echo "<span>Full Access</span>";
                 } else {
                   echo "<span>Limited Access</span>";
                 }
                 echo '</td>';
                echo '<td>';
                echo'<a href="#" class="edit-user" data-id="' . $result->ID . '"><img src="' . get_stylesheet_directory_uri().'/uploads/images/pen.png'. '" width="35px" height="35px" /></a>';
                echo' &nbsp;<a href="#" class="delete-user" data-user-id="' . $result->ID . '"><img src="' . get_stylesheet_directory_uri().'/uploads/images/bin.png'. '" width="35px" height="35px" /></a>';
                echo '</td>';
                echo'<td><div id="element" class="btn btn-default show-modal"data-id="' . $result->ID . '">See Availability</div></td>';
              echo'</tr>'; 
          }
          echo '<table>';

          echo'<!-- Modal For Edit or Update Staff-->
          <div class="modal fade" id="editStaffModal" tabindex="-1" role="dialog" aria-labelledby="editStaffModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="editStaffModalLabel">Edit Staff</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <form id="update-staff-form">
                          <label for="staff_name">Name:</label><br>
                          <input type="text" id="staff_name" name="staff_name" required><br>
                          <label for="staff_email">Email:</label><br>
                          <input type="email" id="staff_email" name="staff_email" required><br>
                          <label for="staff_pass">Password:</label><br>
                          <input type="password" id="staff_pass" name="staff_pass" required><br>
                        <label for="access_control">Access Control:</label><br>
                          <input type="radio" id="access_control_full" name="access_control" value="1" ><label >Full Access</label><br>
                          <input type="radio" id="access_control_lim" name="access_control" value="0" ><label >Limited Access</label><br>
                          <input type="hidden" id="staff_user_id" name="staff_user_id">
                          </form>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" form="update-staff-form" class="btn btn-primary">Save changes</button>
                      </div>
                  </div>
              </div>
          </div>';

echo '<div id="testmodal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                <h4 class="modal-title">Booking Availabilty</h4>
            </div>
            <div class="modal-body">
                
                </div>
            <div class="modal-footer">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
        </div>
    </div>
</div>';




      } else {
          echo '<p>No data found for the current user.</p>';
      }
  } else {
      echo '<p>User is not logged in.</p>';
  }
}

add_shortcode('get_all_staff_data', 'get_user_data_list');

//--------------------END----------------------------
//--------------Booking Availabilty----------------------------
add_action('wp_ajax_fetch_booking', 'fetch_booking');
add_action('wp_ajax_nopriv_fetch_booking', 'fetch_booking');

function fetch_booking() {
    global $wpdb;

    // Get the staff ID from the AJAX request
    $staff_ID = intval($_POST['staff_id']);
    
    // Rest of your code
    $static_times = array(
        array('07:00', '07:30'),
                    array('07:31', '08:00'),
                    array('08:01', '08:30'),
                    array('08:31', '09:00'),
                    array('09:01', '09:30'),
                    array('09:31', '10:00'),
                    array('10:31', '11:00'),
                    array('11:01', '11:30'),
                    array('11:31', '12:00'),
                    array('12:01', '12:30'),
                    array('12:31', '13:00'),
                    array('13:01', '13:30'),
                    array('13:31', '14:00'),
                    array('14:01', '14:30'),
                    array('14:31', '15:00'),
                    array('15:01', '15:30'),
                    array('15:31', '16:00'),
                    array('16:01', '16:30'),
                    array('16:31', '17:00')
        
    );

    $table_booking = $wpdb->prefix . 'bookings_calendar'; 
    $query = $wpdb->prepare("SELECT date_start, date_end FROM $table_booking WHERE staff_id = %d", $staff_ID);
    $results = $wpdb->get_results($query);

    ob_start(); // Start output buffering
    ?>
    <div class="time-ranges-container">
        <?php foreach ($static_times as $static_time) : ?>
            <?php
            $disabled = false;
                    foreach($results as $data){
                        $start_time = date('H:i', strtotime($data->date_start));
                        $end_time = date('H:i', strtotime($data->date_end));
                        // Check for overlap
                        if (($static_time[0] >= $start_time && $static_time[0] < $end_time) || 
                            ($static_time[1] > $start_time && $static_time[1] <= $end_time) ||
                            ($static_time[0] <= $start_time && $static_time[1] >= $end_time)) {
                            $disabled = true;
                            break;
                        }
                    }

                    // Determine class for styling
                    $class = $disabled ? 'booking-time-disabled' : 'booking-time-enabled';

                    // Output time range as text
                    $time_range = '<div class="time-range ' . $class . '">' . $static_time[0] . '-' . $static_time[1] . '</div>';
                    echo $time_range;

                    // Increment counter
                    $count++;

                    // Add line break after every third time range
                    if ($count % 3 == 0) {
                        echo '<br>';
                    }
            ?>
        <?php endforeach; ?>
    </div>
    <?php
    $output = ob_get_clean(); // Get the output buffer contents and clear it

    echo $output;
    die(); // Always end with die() in AJAX functions
}

//-------------------End-------------------

//-------------------Status Update of Staff-------------------
add_action('wp_ajax_update_status', 'update_status_callback');
add_action('wp_ajax_nopriv_update_status', 'update_status_callback');

function update_status_callback() {
  // Verify nonce for security
  check_ajax_referer('update-status-nonce', 'security');

  // Get the ID and new status sent from the AJAX request
  $id = intval($_POST['id']);
  $new_status = intval($_POST['status']);
  
  // Update the status in the database
  global $wpdb;
  $table_name = $wpdb->prefix . 'users'; // Use wp_users table

echo $new_user_status = ($new_status == 0) ? 1 : 0;
    // Update the user_status column in wp_users table
   echo $wpdb->update(
        $table_name,
        array('user_status' => $new_user_status),
        array('ID' => $id),
        array('%d'), // Data format
        array('%d') // Where format
    );

  // Send a response back to the client
  echo 'success';

  // Don't forget to exit
  wp_die();
}
// ---------------Delete Staff-----------------------

add_action('wp_ajax_delete_user', 'delete_user_callback');

function delete_user_callback() {
    $user_id = intval($_POST['user_id']);

    global $wpdb;
    $table_name = $wpdb->prefix . 'users'; // Replace with your table name

    $deleted = $wpdb->delete(
        $table_name,
        array('ID' => $user_id),
        array('%d') // Where format
    );

        echo 'success';
    
    wp_die();
}

// -----------------------End-----------------

//---------------- Fetch user data AJAX action---------------------
add_action('wp_ajax_fetch_user', 'fetch_user_callback');

function fetch_user_callback() {
    // Check nonce for security
    // check_ajax_referer('fetch_user_nonce', 'security');

    // Get the data from the AJAX request
    $user_id = intval($_POST['user_id']);

    // Retrieve user data from the database
    global $wpdb;

  $user_data = $wpdb->get_row($wpdb->prepare("SELECT u.ID,u.display_name, u.user_email,u.user_pass, u.user_status, um1.meta_key AS business_id_key, um1.meta_value AS business_id_value, um2.meta_key AS access_control_key, um2.meta_value AS access_control_value
    FROM {$wpdb->users} AS u
    LEFT JOIN {$wpdb->usermeta} AS um1 ON u.ID = um1.user_id AND um1.meta_key = 'business_id'
    LEFT JOIN {$wpdb->usermeta} AS um2 ON u.ID = um2.user_id AND um2.meta_key = 'access_control'
    WHERE u.ID = %d", $user_id), ARRAY_A);

    // Return user data as JSON response
    if ($user_data) {
        wp_send_json_success($user_data);
    } else {
        wp_send_json_error('User data not found');
    }
}

//-------------------- Update user data AJAX action------------------------
add_action('wp_ajax_update_user_data', 'update_user_data_callback');

function update_user_data_callback() {
    // Check nonce for security
    // check_ajax_referer('update_user_data_nonce', 'security');

    // Get the data from the AJAX request
    $user_id = intval($_POST['staff_user_id']);
    $staff_name = sanitize_text_field($_POST['staff_name']);
    $staff_email = sanitize_email($_POST['staff_email']);
    if (preg_match('/^[a-f0-9]{32}$/', $_POST['staff_pass'])) {
    // Password is hashed (MD5)
    $staff_pass = sanitize_text_field($_POST['staff_pass']);
} else {
    // Password is plain text
    $staff_pass = md5(sanitize_text_field($_POST['staff_pass']));
}
     $access_control = sanitize_text_field($_POST['access_control']);

    // Update the data in the database
    global $wpdb;
	$table_name = $wpdb->prefix . 'users';
    $updated = $wpdb->update(
        $table_name,
        array(
            'display_name' => $staff_name,
            'user_email' => $staff_email,
            'user_pass' => $staff_pass
        ),
        array('ID' => $user_id),
        array('%s', '%s', '%s'),
        array('%d')
    );
    
    $usermeta_table = $wpdb->prefix . 'usermeta';
    $wpdb->update(
        $usermeta_table,
        array('meta_value' => $access_control),
        array('user_id' => $user_id, 'meta_key' => 'access_control'),
        array('%s'), // Data format
        array('%d', '%s') // Where format
    );
    

    // Send response back to the client
    if ($updated !== false) {
        wp_send_json_success('updated Data Successfully');
    } else {
        wp_send_json_error('Failed to update Staff data');
    }
}
//----------------End----------------------------

//---------------Staff Profile------------------------------------
function get_staff_profile(){
$current_user_id = get_current_user_id() ;
    
global $wpdb;

// Query the database for data associated with the current user
$query = $wpdb->prepare("SELECT u.ID,u.display_name, u.user_email,u.user_pass, u.user_status, um1.meta_key AS business_id_key, um1.meta_value AS business_id_value, um2.meta_key AS access_control_key, um2.meta_value AS access_control_value
    FROM {$wpdb->users} AS u
    LEFT JOIN {$wpdb->usermeta} AS um1 ON u.ID = um1.user_id AND um1.meta_key = 'business_id'
    LEFT JOIN {$wpdb->usermeta} AS um2 ON u.ID = um2.user_id AND um2.meta_key = 'access_control'
    WHERE u.ID = %d", $current_user_id);
$results = $wpdb->get_results( $query );
foreach($results as $result ){
    $resultArray = (array) $result;
    $staff_id = $resultArray['ID'];
  $current_business_id = $resultArray['business_id_value'];
    $staff_email = $resultArray['user_email'];
    $staff_name = $resultArray['display_name'];
    $staff_access = $resultArray['access_control_value'];
	$staff_status = $resultArray['user_status'];
	$staff_booking_id=  $resultArray['booking_id'];
	$staff_assign=  $resultArray['assign_status'];
	
    ?>
    <div id="profile-show">
		<h4 style="color:#000000;">Personal Details</h4></br>
         <span style="color:#000000;"><b>Name:</b> <?php echo esc_html( $staff_name ) ?></span></br>
         <span style="color:#000000;"><b>Email:</b> <?php echo esc_html( $staff_email ) ?></span></br>
<div>
    
    <?php
    $business_query = $wpdb->prepare("SELECT display_name FROM wp_users WHERE ID = %d", $current_business_id);
	$business_data = $wpdb->get_results( $business_query );
	foreach($business_data as $business_name){
	   $business_name = $business_name->display_name;
	    ?>
         <span style="color:#000000;"><b>Business Owner:</b> <?php echo esc_html( $business_name ) ?></span>
       <?php
	    
	}
}

}
add_shortcode('get_staff_datas', 'get_staff_profile');

//-----------Assigning Staff and fetch--------------------
add_action('wp_ajax_add_selected_staff', 'add_selected_staff_callback');
function add_selected_staff_callback() {
    
    
    if(isset($_POST['staff_id']) && isset($_POST['bookingid']) && isset($_POST['staff_name'])) {
        $staff_id = intval($_POST['staff_id']);
        $booking_id = intval($_POST['bookingid']);
        $staff_name = sanitize_text_field($_POST['staff_name']);
        $business_id = sanitize_text_field($_POST['business_id']);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'bookings_calendar';
        $staff_table_name = $wpdb->prefix . 'staffs';

        // Fetch existing staff data
        $wpdb->get_row($wpdb->prepare("SELECT * FROM $staff_table_name WHERE staff_id = %d", $staff_id));
        
           $wpdb->insert(
                $staff_table_name,
                array(
                    'staff_id' => $staff_id,
                    'business_id' => $business_id,
                    'booking_id' => $booking_id,
                    'assign_status' => 'assigned'
                ),
                array('%d','%d','%d','%s')
            );
            $staff_query = $wpdb->prepare("SELECT * FROM wp_users WHERE ID = %d", $staff_id);
	        $staff_data = $wpdb->get_results( $staff_query );
        	foreach($staff_data as $bs_data){
        	    //print_r($bs_name->user_login);
        	 $bs_name = $bs_data->display_name;
        	  $bs_email = $bs_data->user_login;
        	}  
        	$business_query = $wpdb->prepare("SELECT * FROM wp_users WHERE ID = %d", $business_id);
        	$business_data = $wpdb->get_results( $business_query );
        	foreach($business_data as $b_name){
        	  $bus_name = $b_name->display_name;
        	}
            $staff_subject = 'Assigned New Service From kaltime Service Portal!';
                $staff_message = "Dear $bs_name,\n\n";
            $staff_message .= "You got assigned a New Service request by your owner. Please check your Dashboard and Confirm your Approval or status.\n\n";
            $staff_message .= "Best regards,\n";
            $staff_message .= "$bus_name\n";
            $staff_message .= "kaltime";
            wp_mail($bs_email, $staff_subject, $staff_message);
            
            
            // $order = wc_get_order($booking_id);
            // if ($order) {
            //     $customer_email = $order->get_billing_email();

            //     // Prepare email subject and message
            //     $subject = 'Your booking has been completed';
            //     $message = 'Dear customer,<br><br>';
            //     $message .= 'We are pleased to inform you that your booking with booking ID ' . $booking_id . ' has been completed.<br>';
            //     $message .= 'Thank you for choosing us!<br><br>';
            //     $message .= 'Best regards,<br>';
            //     $message .= 'Your Business Name';

            //     // Send the email
            //     wp_mail($customer_email, $subject, $message);
            // }
            
        

        // Update bookings_calendar table
         $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE ID = %d", $booking_id));
            $wpdb->update(
                $table_name,
                array(
                    'booking_status' => 'assigned',
                    'staff_id' => $staff_id,
                    'staff_name' => $staff_name
                ), 
                array('ID' => $booking_id), 
                array('%s', '%d', '%s'), 
                array('%d') 
            );
         

        // Fetch updated staff list
        $query = $wpdb->prepare("SELECT * FROM $staff_table_name WHERE staff_id = %d", $staff_id);
        $results = $wpdb->get_results($query);

        // Return updated list of staff members
        echo json_encode($results);
    }
    wp_die(); 
}
// --------------END------------------

add_action('wp_ajax_get_table_data', 'get_table_data_callback');
add_action('wp_ajax_nopriv_get_table_data', 'get_table_data_callback'); // Allow non-logged-in users to access the endpoint

function get_table_data_callback() {
    global $wpdb;

    // Perform your database query to retrieve table data
    $booking_table = $wpdb->prefix . 'bookings_calendar'; // Replace 'your_booking_table_name' with your actual table name
    $booking_data = $wpdb->get_results("SELECT * FROM $booking_table", ARRAY_A);

    // Return the data as JSON
    wp_send_json($booking_data);
}

//----Add Menu For Earning Reports----
add_action('admin_menu', 'my_menu_pages');
function my_menu_pages(){
    add_menu_page('Earning Reports', 'Earning Reports', 'manage_options', 'earning-reports', 'my_menu_output', 'dashicons-admin-page', 10  );
}
function my_menu_output() {
    ?>
    <div class="wrap">
        <?php
        // Include custom template file
        $template_path = get_stylesheet_directory() . '/template-parts/earning-reports-template.php';
        if ($template_path != '') {
            include $template_path;
        } else {
            echo '<p>No template file found.</p>';
        }
        ?>
    </div>
    <?php
}
//-----------END-----------------
add_action('wp_ajax_get_commissions', 'get_user_commissions');
add_action('wp_ajax_nopriv_get_commissions', 'get_user_commissions');

function get_user_commissions() {
    if (isset($_GET['user_id'])) {
        $user_id = intval($_GET['user_id']);
        global $wpdb;
        
        $owners = $wpdb->get_results("
    SELECT u.ID, u.display_name 
    FROM {$wpdb->users} u 
    INNER JOIN {$wpdb->usermeta} m ON u.ID = m.user_id 
    WHERE m.meta_key = 'wp_capabilities' 
    AND m.meta_value LIKE '%owner%' AND u.ID = $user_id");
if ($owners) {
    foreach($owners as $owner){
        $ownerName = $owner->display_name;
    }
}
        $site_url = home_url(); 
        
     $commissions = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_truelysell_core_commissions AS bc INNER JOIN wp_bookings_calendar AS cc ON bc.booking_id = cc.ID WHERE bc.user_id = %d", $user_id));
        if ($commissions) {
            $serial_number = 1;
            $amount_earned = 0;
            
            foreach ($commissions as $commission) {
                $amount = $commission->price;
                
                //----
                $decimal = $commission->rate;
                $percentage = $decimal * 100;
                
                $rate_decimal = $percentage / 100;

                // Calculate amount earned after deducting commissions
                $commiss = $amount * $rate_decimal;
                $ttlamount = $amount - $commiss;
                $amount_earned = $amount_earned + $ttlamount;
              
            }
            
            
            echo'<h5>Business User: '.$ownerName.'</h5><br>';
            echo 'Total Earnings after deducting commissions: <b> $' . $amount_earned .'</b></br>';
            echo'<div class="table-responsive">';
            echo '<table id="earning-bus">';
            echo '<tr><th>S.no</th><th>Business ID</th><th>Booking ID</th><th>Order ID</th><th>Amount</th><th>Commission</th><th>Date-Time</th><th>Status</th></tr>';
            
            foreach ($commissions as $commission) {
                $amount = $commission->price;
                
                //----
                $decimal = $commission->rate;
                $percentage = $decimal * 100;
                echo '<tr>';
                echo '<td>' . $serial_number++ . '</td>';
                echo '<td>' . $commission->user_id . '</td>';
                echo '<td>' . $commission->booking_id . '</td>';
                echo '<td><a href="'.$site_url.'/wp-admin/post.php?post=' . $commission->order_id .'&action=edit" target="_blank">'. $commission->order_id .'</a></td>';
                echo '<td>' . $commission->price . '</td>';
                echo '<td>' . $percentage. '%</td>';
                echo '<td>' . $commission->date . '</td>';
                echo '<td>' . $commission->status . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo'</div></br></br>';
           
            
            echo'<style>
            #earning-bus {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#earning-bus td, #earning-bus th {
  border: 1px solid #ddd;
  padding: 8px;
}

#earning-bus tr:nth-child(even){background-color: #f2f2f2;}

#earning-bus tr:hover {background-color: #ddd;}

#earning-bus th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
         </style>';
        } else {
            echo 'No commissions found for this user.';
        }

        wp_die();
    }
}
//-------------------------Update service working status by Staff---------------------------

// Function to handle form submission
function change_bookstatus() {
    if (isset($_POST['update_status_booking'])) {
        global $wpdb;

        // Get data from form submission
    $staff_id = isset($_POST['staff_id']) ? intval($_POST['staff_id']) : 0;
        $status = 'processing'; // Assuming you have some logic to determine the status

        if ($staff_id) {
            // Update database table
            $table_name = $wpdb->prefix . 'staffs'; // Replace 'your_table_name' with your actual table name
            $result = $wpdb->update(
                $table_name,
                array('assign_status' => $status),
                array('staff_id' => $staff_id),
                array('%s'),
                array('%d')
            );

            if ($result !== false) {
                // Status updated successfully
                wp_redirect(home_url('staff-dashboard/'));
            } else {
                // Failed to update status
                // You can redirect or display an error message here
                echo 'Failed to update status';
            }
        } else {
            // Missing staff ID
            // You can redirect or display an error message here
            echo 'Missing staff ID';
        }
    }
}

// Hook the function to the form submission
add_action('template_redirect', 'change_bookstatus');




//-------------------------END-------------------------------------
//-------------------------Update service completed status by Staff---------------------------

function update_booking_complete() {
    
    if (isset($_POST['complete_status_booking'])) {
        global $wpdb;

        // Get data from form submission
    $staff_id = isset($_POST['staff_id']) ? intval($_POST['staff_id']) : 0;
        $status = 'completed'; // Assuming you have some logic to determine the status

        if ($staff_id) {
            // Update the status
            $table_names = $wpdb->prefix . 'staffs';
        $wpdb->update(
            $table_names,
            array(
                'assign_status' => $status,
                'booking_id' => 0,
            ),
            array('staff_id' => $staff_id),
            array('%s', '%d'), // Assuming 'assign_status' is a string column
            array('%d')
        );
        $table_name = $wpdb->prefix . 'bookings_calendar'; // Add your table name here
       
        // Update the status
        $wpdb->update(
            $table_name,
            array(
                'booking_status' => 'completed',
                'staff_id' => 0,
                'staff_name' => ''
            ),
            array('staff_id' => $staff_id),
            array('%s', '%d', '%s'), // Assuming 'assign_status' is a string column
            array('%d')
        );
//----------MAIL to Owner After complete-----------------------
        $current_user_id = get_current_user_id() ;

  $query = $wpdb->prepare("SELECT u.ID,u.display_name, u.user_email, um1.meta_key AS business_id_key, um1.meta_value AS business_id_value
    FROM {$wpdb->users} AS u
    LEFT JOIN {$wpdb->usermeta} AS um1 ON u.ID = um1.user_id AND um1.meta_key = 'business_id'
    WHERE u.ID = %d", $current_user_id);
$results = $wpdb->get_results( $query );
foreach($results as $result ){
    $resultArray = (array) $result;
    print_r($resultArray);
 $staff_id = $resultArray['ID'];
  $current_business_id = $resultArray['business_id_value'];
    $staff_email = $resultArray['user_email'];
    $staff_name = $resultArray['display_name'];
    $staff_access = $resultArray['access_control_value'];
	$staff_status = $resultArray['user_status'];
	$staff_booking_id=  $resultArray['booking_id'];
	$staff_assign=  $resultArray['assign_status'];
	

    $business_query = $wpdb->prepare("SELECT user_email,display_name FROM wp_users WHERE ID = %d", $current_business_id);
	$business_data = $wpdb->get_results( $business_query );
	foreach($business_data as $business_name){
	    $business_email = $business_name->user_email;
	   $business_name = $business_name->display_name; 
	$staff_subject = 'Assigned Service Completed '.$staff_name.'!';
               $staff_message = "Dear $business_name,\n\n";
            $staff_message .= "$staff_name Completed their assigned Service request by you. Please check your Dashboard and Confirm your Approval or status.\n\n";
            $staff_message .= "Best regards,\n";
         echo   $staff_message .= "kaltime";
            wp_mail($business_email, $staff_subject, $staff_message);
  
	    
	}
  }
 //--------------------END MAIL-----------------------------
        
                // Status updated successfully
                wp_redirect(home_url('staff-dashboard/'));
            
        } else {
            // You can redirect or display an error message here
            ?>
            <script>window.alert('Missing staff ID');</script>
            <?php
        }
    }
    
}
add_action('template_redirect', 'update_booking_complete');
// ----------------Custom Posttype for Pricing---------------------
// Register Custom Post Type
function custom_post_type() {
    $args = array(
        'public' => true,
        'label'  => 'Business Owner Plans',
        'supports' => array( 'title', 'editor', 'custom-fields' ), // Add support for editor and custom fields
    );
    register_post_type('dynamic_content', $args);
}
add_action('init', 'custom_post_type');
// -----------------Customer Email Notification------------------------
// Shortcode to display notification settings form
function email_notification_settings() {
    ?>
    <div class="notification-settings">
        <h2>Notification Settings</h2><br>
        <div id="notification-message"></div></br>
        <form id="notification-settings-form" method="post" action="">
            <?php wp_nonce_field('save_notification_settings', 'notification_settings_nonce'); ?>
            <p>
                <label><input type="checkbox" name="send_advance_notification" value="1" <?php checked(get_option('send_advance_notification'), 1); ?>> Send advance notification for each appointment</label>
            </p>
            <p>
                <label><input type="checkbox" name="send_daily_notification" value="1" <?php checked(get_option('send_daily_notification'), 1); ?>> Send daily notification at 08:00 AM to all customers</label>
            </p>
            <p>
                <!-- Changed the input type to select and added the 'multiple' attribute -->
                <label for="selected_users">Select Users to Send Notification 5 hours before :</label>
                <select name="selected_users[]" id="selected_users" multiple>
                    <?php
                    // Query to retrieve all users
                     $current_user_id = get_current_user_id() ;
                    global $wpdb;
                    $users_query = $wpdb->prepare("SELECT * FROM wp_users AS u INNER JOIN wp_bookings_calendar AS wp ON u.ID = wp.bookings_author WHERE wp.owner_id = %d ",$current_user_id); // Change role as needed
                    $users_que = $wpdb->get_results($users_query);
                    // Check if users are found
                    
                        foreach ($users_que as $user) {
                            ?>
                            <option value="<?php echo $user->bookings_author; ?>"><?php echo $user->user_login; ?></option>
                            <?php
                        }
                    
                    ?>
                </select>
            </p>
            <p><input type="submit" name="save_notification_settings" value="Save Settings"></p>
        </form>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('notification-settings-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission

            // Perform AJAX request to submit the form data
            var formData = new FormData(this);

            // Example: Send AJAX request
            // Replace this with your actual AJAX code
            fetch('', {
                method: 'POST',
                body: formData,
            })
            .then(function(response) {
                return response.json(); // Parse response as JSON
            })
            .then(function(data) {
                var messageDiv = document.getElementById('notification-message');
                if (data.success) {
                    // If successful, show success message
                    messageDiv.innerHTML = '<div class="success-message" style="color:green;">Settings saved successfully!</div>';
                } else {
                    // If there's an error, show error message
                    messageDiv.innerHTML = '<div class="error-message" style="color:red;">Error occurred. Please try again.</div>';
                }

                // Remove message after 5 seconds
                setTimeout(function() {
                    messageDiv.innerHTML = '';
                }, 3000);
            })
            .catch(function(error) {
                // If there's an error, show error message
                document.getElementById('notification-message').innerHTML = '<div class="error-message" style="color:blue;">Error occurred. Please try again.</div>';
            });
        });
    });
    </script>
    <?php
}
add_shortcode('email_notification_settings', 'email_notification_settings');

// Handle form submission
function process_notification_settings_form() {
    if (isset($_POST['save_notification_settings']) && wp_verify_nonce($_POST['notification_settings_nonce'], 'save_notification_settings')) {
        // Get current user ID
        $user_id = get_current_user_id();

        // Update user meta for the current user
        update_user_meta($user_id, 'send_advance_notification', isset($_POST['send_advance_notification']) ? 1 : 0);
        update_user_meta($user_id, 'send_daily_notification', isset($_POST['send_daily_notification']) ? 1 : 0);

        // Get selected user IDs
        $selected_users = isset($_POST['selected_users']) ? $_POST['selected_users'] : array();

        // Concatenate selected user IDs with comma
        $selected_users_string = implode(',', $selected_users);

        // Update user meta for the current user with selected user IDs
        update_user_meta($user_id, 'selected_users', $selected_users_string);

        wp_redirect(home_url('customer-email-notification'));
        exit();
    }
}
add_action('init', 'process_notification_settings_form');




// Function to send advance notification for each appointment
function send_advance_notification() {
        $user_id = get_current_user_id();
        global $wpdb;
        $send_daily_notification = get_user_meta($user_id, 'send_advance_notification', true);

    // If daily notifications are enabled
    if ($send_daily_notification == 1) {
        $customer_que =$wpdb->prepare("SELECT * FROM wp_users AS u INNER JOIN wp_bookings_calendar AS wp ON u.ID = wp.bookings_author Where owner_id = %d", $user_id);
        $customer_data = $wpdb->get_results($customer_que);
        foreach ($customer_data as $customer_datas) {
            $custom_email = $customer_datas->user_email;
            $start_end = $customer_datas->date_start;
  // Get the current time
        $current_time = current_time('timestamp');
            $appointment_time = $start_end;
        // Convert appointment time to a Unix timestamp
        $appointment_timestamp = strtotime($appointment_time);

        // Calculate the time difference between the current time and the appointment time
        $time_difference = $appointment_timestamp - $current_time;

        // Define the notification time threshold (e.g., 24 hours before the appointment)
        $notification_threshold = 24 * 60 * 60; // 24 hours in seconds

        // If the time difference is less than the notification threshold, send the advance notification
         if ($time_difference <= $notification_threshold) {
            // Code to send advance notification email
            $to = $custom_email;
            $subject = 'Advance Appointment Notification';
            $message = 'You have an appointment scheduled at ' . $start_end . ' at Kaltime.';
            wp_mail($to, $subject, $message);
        }
        }
    }
}

// Function to send daily notification at 08:00 AM to all customers
function send_daily_notification() {
     global $wpdb;
    $user_id = get_current_user_id();
    $send_daily_notification = get_user_meta($user_id, 'send_daily_notification', true);

    // If daily notifications are enabled
    if ($send_daily_notification == 1) {
        $customer_que =$wpdb->prepare("SELECT * FROM wp_users AS u INNER JOIN wp_bookings_calendar AS wp ON u.ID = wp.bookings_author Where owner_id = %d", $user_id);
        $customer_data = $wpdb->get_results($customer_que);
        foreach ($customer_data as $customer_datas) {
            $custom_email = $customer_datas->user_email;
    $current_time = current_time('timestamp');

        // Calculate the timestamp for 08:00 AM of the current day
        $eight_am_today = strtotime('today 08:00:00');

        // Calculate the timestamp for 08:00 AM of the next day
        $eight_am_tomorrow = strtotime('tomorrow 08:00:00');

        // If the current time is after 08:00 AM today but before 08:00 AM tomorrow,
        // send the daily notification
        if ($current_time >= $eight_am_today && $current_time < $eight_am_tomorrow) {
            // Code to send daily notification email
            $to = $custom_email;
            $subject = 'Daily Notification';
            $message = 'This is a daily notification reminder of your booking which are sent by Kaltime.';
            wp_mail($to, $subject, $message);
        }
      }
    }

}

// Function to send notification 5 hours before each appointment individually
function send_individual_notification() {
    global $wpdb;
    $user_id = get_current_user_id();
    // Retrieve selected user IDs from user meta
    $selected_users = get_user_meta($user_id, 'selected_users', true);
    $selected_user_ids = explode(',', $selected_users);
    // Loop through each selected user ID
    foreach($selected_user_ids as $custom_id) {
        // Retrieve user data for the selected user
        echo $customer_que = $wpdb->prepare("SELECT * FROM wp_users AS u INNER JOIN wp_bookings_calendar AS wp ON u.ID = wp.bookings_author WHERE wp.bookings_author = %d", $custom_id);
        $customer_data = $wpdb->get_results($customer_que);
        foreach ($customer_data as $customer_datas) {
            $custom_email = $customer_datas->user_email;
            $start_end = $customer_datas->date_start;

            $appointment_time = strtotime($start_end);
            $notification_time = $appointment_time - (5 * 3600);
            $current_time = time();

            // Code to send individual notification email
            if ($current_time >= $notification_time) {
            $to = $custom_email;
            $subject = 'Individual Appointment Notification';
            $message = 'You have an appointment scheduled at ' . $start_end . '. This is a notification sent 5 hours before your appointment.';
            wp_mail($to, $subject, $message);
            }
        }
    }
}



// ------------------End Customer Booking Notification-----------------