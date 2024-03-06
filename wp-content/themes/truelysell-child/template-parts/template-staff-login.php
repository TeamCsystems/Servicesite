<?php
/*
Template Name: Staff Login Page
*/

?>

<?php get_header(); ?>
<style>

form {border: 3px solid #f1f1f1;}

input[type=email], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.btn {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

.btn:hover {
  opacity: 0.8;
}

form .container {
  padding: 16px;
  max-width: 500px;
}



/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  
  
}
</style>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <div class="login-form">
            <h4 style="text-align:center;"><?php esc_html_e('Fill the credentials', 'truelysell'); ?></h4>
            <p style="text-align:center;"><?php esc_html_e('*( If you want to register account as a Staff so please contact with your Business Owner)', 'truelysell'); ?></p>
            <?php if (isset($error_message)) : ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php
$login_success_message = get_transient('login_success_message');
if ($login_success_message) {
    echo '<div class="success-message" style="color: red; text-align:center;">' . esc_html($login_success_message) . '</div>';
    delete_transient('login_success_message'); 
}
?>
            <!-- Form with action to the current page -->
            <form method="post" action="<?php echo esc_url( home_url( '/staff-login-page' ) ); ?>">
            <div class="container">
                <label for="email"><b><?php esc_html_e('Email', 'truelysell'); ?></b></label>
                 <input type="email" name="email" id="email" placeholder="Enter Email" required>

                <label for="password"><b><?php esc_html_e('Password', 'truelysell'); ?></b></label>
                <input type="password" name="password" id="password" placeholder="Enter Password" required>
            <input class="btn btn-primary" type="submit" name="submit" value="Login">
            </div>
            </form>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->


<?php get_footer(); ?>
