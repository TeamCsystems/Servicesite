<?php
/**
 * Template Name: Earning Report
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Truelysell
 */
?>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"/>
<h2>Business User Earning Reports</h2>

<?php 
global $wpdb; 

// Fetch users with 'owner' role
$owners = $wpdb->get_results("
    SELECT u.ID, u.display_name 
    FROM {$wpdb->users} u 
    INNER JOIN {$wpdb->usermeta} m ON u.ID = m.user_id 
    WHERE m.meta_key = 'wp_capabilities' 
    AND m.meta_value LIKE '%owner%'
");

if ($owners) {
    echo '<table id="business_earning">';
    echo '<tr><th>User ID</th><th>Name</th><th>Earning list</th></tr>';
    foreach ($owners as $owner) {
        echo '<tr>';
        echo '<td>' . esc_html($owner->ID) . '</td>';
        echo '<td>' . esc_html($owner->display_name) . '</td>';
        echo'<td><div class="btn btn-default show-modal-earning" data-id="' . $owner->ID . '">See Earning</div></td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '<div id="earning-report" class="modal fade" role="dialog">
    <div class="modal-dialog" style="max-width: 950px;">
        <div class="modal-content">
            <div class="modal-header" style="    display: block;">
                <h4 class="modal-title" style="text-align: center;">All Earnings Reports</h4>
            </div>
            <div class="modal-body">
                
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>';
} else {
    echo 'No business owners found.';
}


?>
<style>
            #business_earning {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#business_earning td, #business_earning th {
  border: 1px solid #ddd;
  padding: 8px;
}

#business_earning tr:nth-child(even){background-color: #f2f2f2;}

#earning tr:hover {background-color: #ddd;}

#business_earning th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
         </style>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script>
jQuery(document).ready(function($) {
    
    
    
      $('.show-modal-earning').click(function(e) {
        e.preventDefault();
        var businessId = $(this).data('id');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                method: 'GET',
                data: {
                    action: 'get_commissions',
                  user_id: businessId
                },
            success: function(response) {
                // Append the fetched content to the modal
                $('.modal-body').html(response);
                // Show the modal
                $("#earning-report").modal('show');

            },
            error: function(xhr, status, error) {
                console.error('Error updating status:', error);
            }
        });
    });
  
});
</script>
<?php
?>
