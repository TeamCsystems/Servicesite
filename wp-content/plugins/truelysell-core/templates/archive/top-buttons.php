<?php 
$buttons = false;

if(isset($data)) :
$buttons        = (isset($data->buttons)) ? $data->buttons : false ;
endif; 
if(!$buttons || $buttons=='none') { return false; } 
$segments = explode('|',$buttons);
?>


<?php if ( in_array('layout', $segments) ) : ?>
	<div class="col-lg-8 col-md-8 col-sm-12 ">
	<div class="fullwidth-filters d-flex justify-content-md-end  <?php if(truelysell_fl_framework_getoptions('ajax_browsing') == 'on') { ?> ajax-search <?php } ?>">
	
							
								<div class="sortbyset" data-select2-id="15">
 									<div class="sorting-select">

<?php if ( in_array('order', $segments) ) : ?>
	<!-- Sort by -->
	<div class="sort-by">
		<div class="sort-by-select">
			<?php $default = isset( $_GET['truelysell_core_order'] ) ? (string) $_GET['truelysell_core_order']  :  truelysell_fl_framework_getoptions('sort_by' );  ?>
			<select form="truelysell_core-search-form" name="truelysell_core_order" data-placeholder="<?php esc_attr_e('Default order', 'truelysell_core'); ?>" class="sortby orderby form-control selectbox select form-select">
				<option <?php selected($default,'default'); ?> value="default"><?php esc_html_e( 'Default Order' , 'truelysell_core' ); ?></option>	
				<option <?php selected($default,'highest-rated'); ?> value="highest-rated"><?php esc_html_e( 'Highest Rated' , 'truelysell_core' ); ?></option>
				<?php if(!truelysell_fl_framework_getoptions('disable_reviews')) : ?>
				<option <?php selected($default,'reviewed'); ?> value="reviewed"><?php esc_html_e( 'Most Reviewed' , 'truelysell_core' ); ?></option>
				<?php endif; ?>
				<option <?php selected($default,'date-desc'); ?> value="date-desc"><?php esc_html_e( 'Newest Listings' , 'truelysell_core' ); ?></option>
				<option <?php selected($default,'date-asc'); ?> value="date-asc"><?php esc_html_e( 'Oldest Listings' , 'truelysell_core' ); ?></option>
				<option <?php selected($default,'title'); ?> value="title"><?php esc_html_e( 'Alphabetically' , 'truelysell_core' ); ?></option>
				<option <?php selected($default,'featured'); ?> value="featured"><?php esc_html_e( 'Featured' , 'truelysell_core' ); ?></option>
				<option <?php selected($default,'views'); ?> value="views"><?php esc_html_e( 'Most Views' , 'truelysell_core' ); ?></option>
				<option <?php selected($default,'rand'); ?> value="rand"><?php esc_html_e( 'Random' , 'truelysell_core' ); ?></option>
			</select>
		</div>
	</div>
	<!-- Sort by / End -->
	<?php endif; ?>

									</div>
								</div>
								<div class="layout-switcher grid-listview">
									<ul>
										<li>
										 
											<a href="#" data-layout="grid" class="grid active"><i class="feather-grid"></i></i></a>
										</li>
										<li>
											 
											<a href="#" data-layout="list" class="list"><i class="feather-list"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
  
<?php endif; ?>
