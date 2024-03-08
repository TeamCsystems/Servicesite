<!-- Features -->
<?php   

$taxonomies = get_option('truelysell_single_taxonomies_checkbox_list', array('listing_feature') );

if(empty($taxonomies)){
	return;
}
foreach($taxonomies as $tax){
	$term_list = get_the_term_list( $post->ID, $tax );
	$tax_obj = get_taxonomy( $tax );
	$taxonomy = get_taxonomy_labels( $tax_obj );



}; 

?>