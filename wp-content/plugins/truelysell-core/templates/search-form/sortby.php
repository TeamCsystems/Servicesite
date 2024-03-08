<div class="sort-by">
	<div class="sort-by-select">
		<?php $default = isset( $_GET['truelysell_core_order'] ) ? (string) $_GET['truelysell_core_order']  : truelysell_fl_framework_getoptions('sort_by' ); ?>
		<select form="truelysell_core-search-form" name="truelysell_core_order" data-placeholder="<?php esc_attr_e('Default order', 'truelysell_core'); ?>" class="select2-single orderby" >
			<option <?php selected($default,'default'); ?> value="default"><?php esc_html_e( 'Default Order' , 'truelysell_core' ); ?></option>	
			<option <?php selected($default,'highest-rated'); ?> value="highest-rated"><?php esc_html_e( 'Highest Rated' , 'truelysell_core' ); ?></option>
			<option <?php selected($default,'reviewed'); ?> value="reviewed"><?php esc_html_e( 'Most Reviewed' , 'truelysell_core' ); ?></option>
			<option <?php selected($default,'date-desc'); ?> value="date-desc"><?php esc_html_e( 'Newest Listings' , 'truelysell_core' ); ?></option>
			<option <?php selected($default,'date-asc'); ?> value="date-asc"><?php esc_html_e( 'Oldest Listings' , 'truelysell_core' ); ?></option>

			<option <?php selected($default,'featured'); ?> value="featured"><?php esc_html_e( 'Featured' , 'truelysell_core' ); ?></option>
			<option <?php selected($default,'views'); ?> value="views"><?php esc_html_e( 'Most Views' , 'truelysell_core' ); ?></option>
			<option <?php selected($default,'rand'); ?> value="rand"><?php esc_html_e( 'Random' , 'truelysell_core' ); ?></option>
		</select>
	</div>
</div>