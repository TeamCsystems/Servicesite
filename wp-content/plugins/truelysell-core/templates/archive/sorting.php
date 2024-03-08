<div class="col-md-6 col-xs-6">
	<!-- Sort by -->
	<div class="sort-by">
		<div class="sort-by-select">
			<?php $default = isset( $_GET['truelysell_core_order'] ) ? (string) $_GET['truelysell_core_order']  : ''; ?>
			<select name="truelysell_core_order" data-placeholder="<?php esc_attr_e('Default order', 'truelysell_core'); ?>" class="chosen-select-no-single orderby" >
				<option <?php selected($default,'default'); ?> value="default"><?php esc_html_e( 'Default Order' , 'truelysell_core' ); ?></option>	
				<option <?php selected($default,'price-asc'); ?> value="price-asc"><?php esc_html_e( 'Price Low to High' , 'truelysell_core' ); ?></option>
				<option <?php selected($default,'price-desc'); ?> value="price-desc"><?php esc_html_e( 'Price High to Low' , 'truelysell_core' ); ?></option>
				<option <?php selected($default,'date-desc'); ?> value="date-desc"><?php esc_html_e( 'Newest Properties' , 'truelysell_core' ); ?></option>
				<option <?php selected($default,'date-asc'); ?> value="date-asc"><?php esc_html_e( 'Oldest Properties' , 'truelysell_core' ); ?></option>
				<option <?php selected($default,'featured'); ?> value="featured"><?php esc_html_e( 'Featured' , 'truelysell_core' ); ?></option>
				<option <?php selected($default,'rand'); ?> value="rand"><?php esc_html_e( 'Random' , 'truelysell_core' ); ?></option>
			</select>
		</div>
	</div>
</div>