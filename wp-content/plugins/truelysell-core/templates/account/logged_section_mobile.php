 

 
<ul class="mobile_login_menu main-nav">

 		<?php   

 		$submit_page = truelysell_fl_framework_getoptions('submit_page');
		if (function_exists('Truelysell_Core')) :
		
		 ?>
			 
			<?php
				$login_page = truelysell_fl_framework_getoptions('profile_page');
				$loginnew_page = truelysell_fl_framework_getoptions('login_page');
				$register_page = truelysell_fl_framework_getoptions('register_page'); ?>
				<li class="nav-item">
				<a href="<?php echo esc_url(get_permalink($register_page)); ?>" class="nav-link header-reg"><?php esc_html_e('Register', 'truelysell_core'); ?></a>
				</li>

				<li class="nav-item">
							<a class="nav-link header-login" href="<?php echo esc_url(get_permalink($loginnew_page)); ?>"><?php esc_html_e('Login', 'truelysell_core'); ?></a>
			 </li>

		<?php 
		endif; ?>
</ul>
 
 
						
 						 
				

 
