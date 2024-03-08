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


//--------------Image Path Update on all pages-----------------------
function change_image_src_url_for_about_us_page( $content ) {
    // for Home page
    $old_url = 'https://truelysell-wp.dreamstechnologies.com/multipurpose/wp-content/uploads/2023/05';
    $new_url = 'https://truelysell-wordpress.dreamstechnologies.com/multipurpose/wp-content/uploads/2024/01';
    
    // Define your old and new URLs for other pages
        $old_url_pages = 'https://truelysell-wp.dreamstechnologies.com/multipurpose/wp-content/uploads/2023/';
        $new_url_pages = 'https://truelysell-wordpress.dreamstechnologies.com/multipurpose/wp-content/uploads/2024/';
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

// Your Custom code goes below

//------------------ Adding a Staff----------------
function handle_staff_form_submission() {
  if( isset( $_POST['submit'] ) ) {
      // Get the current user's ID
      $current_user_id = get_current_user_id();

      // Check if the user is logged in
      if ($current_user_id !== 0) {
          global $wpdb;
          $table_name = $wpdb->prefix . 'staffs'; // Add your table name here

          // Check if the table exists
          $table_exists = $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" );

          if ( $table_exists != $table_name ) {
              // Table doesn't exist, create it
              // Your table creation SQL here
              $sql = "CREATE TABLE `wp_staffs` (
                `id` int(11) NOT NULL,
                `current_userid` int(11) NOT NULL,
                `staff_name` varchar(255) NOT NULL,
                `staff_email` varchar(255) NOT NULL,
                `staff_pass` varchar(255) NOT NULL,
                `access_control` varchar(100) NOT NULL,
                `role` varchar(100) NOT NULL DEFAULT 'staff',
                `status` varchar(100) NOT NULL DEFAULT 'active'
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

              // Include dbDelta for table creation
              require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
              dbDelta( $sql );
          }

          // Sanitize form data
          $staff_name = sanitize_text_field( $_POST['staff_name'] );
          $staff_email = sanitize_email( $_POST['staff_email'] );
          $staff_pass = md5( sanitize_text_field( $_POST['staff_pass'] ) );
          $access_control = isset( $_POST['access_control'] ) ? sanitize_text_field( $_POST['access_control'] ) : '';

          // Insert data into the database
          $wpdb->insert( 
              $table_name, 
              array( 
                'current_userid' => $current_user_id,
                  'staff_name' => $staff_name, 
                  'staff_email' => $staff_email, 
                  'staff_pass' => $staff_pass,
                  //'access_control' => $access_control
              ) 
          );

          // Redirect after form submission
          //$redirect_url = add_query_arg('success', 'true', get_template_directory_uri() . '/template-parts/allStaff/');
          wp_redirect(home_url('/all-staffs'));
          exit();
      }
  }
}


add_action( 'init', 'handle_staff_form_submission' );
//--------------------END----------------------------


//------------------ Displaying All Staff------------
function get_user_data_list() {
  // Get the current user's ID
  $current_user_id = get_current_user_id();

  // Check if the user is logged in
  if ($current_user_id !== 0) {
      global $wpdb;
      $table_name = $wpdb->prefix . 'staffs'; 

      // Query the database for data associated with the current user
      $query = $wpdb->prepare("SELECT * FROM $table_name WHERE current_userid = %d", $current_user_id);
      $results = $wpdb->get_results( $query );

      // Check if there are results
      if ( $results ) {
          // Process the results
          $serial_number = 1;
          echo '<div style="color:blue;" id="status-message"></div><br>';
          echo '<table>';
          echo '<tr><th>S.no</th><th>Name</th><th>Email</th><th>Status</th><th>Action</th></tr>';
          foreach ( $results as $result ) {
              // Do something with each row of data
              echo '<tr><td>' .$serial_number++ . '</td>
              <td>' . esc_html( $result->staff_name ) . '</td>
              <td>' . esc_html( $result->staff_email ) . '</td>';
              // Inside your loop generating status toggle links
echo '<td><a href="#" class="status-toggle" data-id="' . esc_attr($result->id) . '" data-status="' . esc_attr($result->status) . '"><img src="' . esc_url($result->status == 'active' ? get_stylesheet_directory_uri().'/uploads/images/active-user.png' : get_stylesheet_directory_uri().'/uploads/images/delete.png') . '" width="40px" height="40px" /></a><input type="hidden" class="status-nonce" value="' . esc_attr(wp_create_nonce('update-status-nonce')) . '"></td>';


                // echo '<td>';
                // if ($result->access_control == '1') {
                //     echo "<span>Full Access</span>";
                // } else {
                //   echo "<span>Limited Access</span>";
                // }
                // echo '</td>';
                echo '<td>';
                echo'<a href="#" class="edit-user" data-id="' . $result->id . '"><img src="' . get_stylesheet_directory_uri().'/uploads/images/pen.png'. '" width="35px" height="35px" /></a>';
                echo' &nbsp;<a href="#" class="delete-user" data-user-id="' . $result->id . '"><img src="' . get_stylesheet_directory_uri().'/uploads/images/bin.png'. '" width="35px" height="35px" /></a>';
                echo '</td>';
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
                          <input type="text" id="staff_name" name="staff_name"><br>
                          <label for="staff_email">Email:</label><br>
                          <input type="email" id="staff_email" name="staff_email"><br>
                          <label for="staff_pass">Password:</label><br>
                          <input type="password" id="staff_pass" name="staff_pass"><br>
                        <!--<label for="access_control" style="display:none;">Access Control:</label><br>
                          <input type="radio" id="access_control_full" name="access_control" value="1" style="display:none;"><label style="display:none;">Full Access</label><br>
                          <input type="radio" id="access_control_lim" name="access_control" value="0" style="display:none;"><label style="display:none;">Limited Access</label><br>-->
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
      } else {
          echo '<p>No data found for the current user.</p>';
      }
  } else {
      echo '<p>User is not logged in.</p>';
  }
}

add_shortcode('get_all_staff_data', 'get_user_data_list');
//--------------------END----------------------------


//-------------------Status Update of Staff-------------------
add_action('wp_ajax_update_status', 'update_status_callback');
add_action('wp_ajax_nopriv_update_status', 'update_status_callback');

function update_status_callback() {
  // Verify nonce for security
  check_ajax_referer('update-status-nonce', 'security');

  // Get the ID and new status sent from the AJAX request
  $id = intval($_POST['id']);
  $new_status = sanitize_text_field($_POST['status']);
  if($new_status == 'active'){
    $new_staff_status = 'inactive';
  }else if($new_status == 'inactive'){
    $new_staff_status = 'active';
  }

  // Update the status in the database
  global $wpdb;
  $table_name = $wpdb->prefix . 'staffs'; // Replace with your table name

  $wpdb->update(
      $table_name,
      array('status' => $new_staff_status),
      array('id' => $id),
      array('%s'), // Data format
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
// print_r($_POST);
    $user_id = intval($_POST['user_id']);

    global $wpdb;
    $table_name = $wpdb->prefix . 'staffs'; // Replace with your table name

    $deleted = $wpdb->delete(
        $table_name,
        array('id' => $user_id),
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
    $table_name = $wpdb->prefix . 'staffs';

    $user_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $user_id), ARRAY_A);

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
    $staff_pass = md5(sanitize_text_field($_POST['staff_pass']));
    // $access_control = intval($_POST['access_control']);

    // Update the data in the database
    global $wpdb;
    $table_name = $wpdb->prefix . 'staffs';

    $updated = $wpdb->update(
        $table_name,
        array(
            'staff_name' => $staff_name,
            'staff_email' => $staff_email,
            'staff_pass' => $staff_pass,
            // 'access_control' => $access_control
        ),
        array('id' => $user_id),
        array('%s', '%s', '%s', '%d'),
        array('%d')
    );
    

    // Send response back to the client
    if ($updated !== false) {
        wp_send_json_success('updated Data Successfully');
    } else {
        wp_send_json_error('Failed to update Staff data');
    }
}
//----------------End----------------------------
//--------------Staff Login Hook----------------
function custom_login_function() {
    if (isset($_POST['submit'])) {
        $username = isset($_POST['email']) ? sanitize_user($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $password_hash = md5($password);
       

        // Check if username and password are not empty
        if (empty($username) || empty($password_hash)) {
            echo "Please enter both username and password.";
            return;
        }

        // Check username against custom table
        global $wpdb;
        $table_name = $wpdb->prefix . 'staffs';
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE staff_email = %s", $username), ARRAY_A);
       
        if ($user && $user['staff_pass'] === $password_hash) {
            echo $password_hash; 
            // Authentication successful
            echo "Authentication successful!";
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            // session_start();
            // Set the user ID in the session
            $_SESSION['user_id'] = $user['id']; 
            // Set success message in transient data
            set_transient('login_success_message', 'Login successful!', 10);
            wp_redirect(home_url('/staff-dashboard')); // Redirect to dashboard or any other page
            exit;
        } else { 
            // Authentication failed
            set_transient('login_success_message', 'Invalid  Email and Password!', 10);
            wp_redirect(home_url('/staff-login-page'));
            exit;
        }
    }
}
add_action('template_redirect', 'custom_login_function');

//---------------------------------------------------

// Redirect user to custom login page after logout
function custom_logout_redirect() {

        session_start();
        if(isset($_GET['id'])){

        session_unset();
        session_destroy();
	set_transient('login_success_message', 'Logout Successfull!', 10);
        wp_redirect(home_url('/staff-login-page'));
        exit;
        }
}
add_action( 'template_redirect', 'custom_logout_redirect' );
//---------------------------------------------------


//-----------Assigning Staff and fetch--------------------
add_action('wp_ajax_add_selected_staff', 'add_selected_staff_callback');
function add_selected_staff_callback() {
    if(isset($_POST['staff_id']) && isset($_POST['bookingid']) && isset($_POST['staff_name'])) {
        $staff_id = intval($_POST['staff_id']);
        $booking_id = intval($_POST['bookingid']);
        $staff_name = sanitize_text_field($_POST['staff_name']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'bookings_calendar';

        $wpdb->update(
        $table_name,
        array(
            'staff_id' => $staff_id,
            'staff_name' => $staff_name
        ), 
        array('ID' => $booking_id), 
        array('%d', '%s'), 
        array('%d') 
    );
        // Fetch updated staff list
        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE staff_id = %d AND ID = %d", $staff_id, $booking_id);
        $results = $wpdb->get_results($query);

        // Return updated list of staff members
        echo json_encode($results);
    }
    wp_die(); 
}
//---------------------------------------------------


