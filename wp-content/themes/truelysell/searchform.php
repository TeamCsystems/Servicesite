<?php
/**
 * The template for displaying search forms in truelysell
 *
 * @package truelysell
 * @since truelysell 1.0
 */
?>
<div class="search-blog-input">
    <form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
        <div class="input-group"><input class="search-field form-control" type="text" name="s" required="" placeholder="<?php esc_attr_e('Search...','truelysell') ?>" value="" />
        <span class="input-group-btn"> <button type="submit" class="btn btn-sm btn-search"><i class="fa fa-search"></i></button> </span>
    </div>
	<div class="clearfix"></div>
    </form>
</div>
<div class="clearfix"></div>