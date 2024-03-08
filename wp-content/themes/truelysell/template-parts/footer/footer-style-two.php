<footer id="colophon" class="footer  footer-two">
<?php if(in_array('truelysell-core/truelysell-core.php', apply_filters('active_plugins', get_option('active_plugins'))))
{ ?>
<div class="footer-top aos aos-init aos-animate" data-aos="fade-up">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-6 about_footer">
						<?php
						if ( is_active_sidebar( 'footerareastwo-1' ) ) {
							dynamic_sidebar( 'footerareastwo-1' );
						}
						?>
					</div>
					<div class="col-lg-2 col-md-6 footer-menu">
						<?php
						if ( is_active_sidebar( 'footerareastwo-2' ) ) {
							dynamic_sidebar( 'footerareastwo-2' );
						}
						?>
					</div>
					<div class="col-lg-2 col-md-6 footer-menu">
					<?php
						if ( is_active_sidebar( 'footerareastwo-3' ) ) {
							dynamic_sidebar( 'footerareastwo-3' );
						}
						?>
					</div>
					<div class="col-lg-2 col-md-6 footer-menu">
					<?php
						if ( is_active_sidebar( 'footerareastwo-4' ) ) {
							dynamic_sidebar( 'footerareastwo-4' );
						}
						?>
					</div>
					<div class="col-lg-3 col-md-6">
					<?php
						if ( is_active_sidebar( 'footerareastwo-5' ) ) {
							dynamic_sidebar( 'footerareastwo-5' );
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>