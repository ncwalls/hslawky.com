<?php

class MakespaceChild {

	function __construct(){
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array($this, 'msw_admin_enqueue_scripts') );
		add_action( 'acf/init', array( $this, 'msw_acf_init' ) );
		add_action( 'wp_loaded', array( $this, 'msw_loaded' ) );
		add_action( 'init', array( $this, 'msw_ajax_atc') );
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts') );

		add_filter( 'wpseo_breadcrumb_links', array( $this, 'add_cpt_archive_parent_breadcrumb' ), 10, 1);

		// add_shortcode( 'first_name_possessive', array( $this, 'fname_possessive') );
		// add_shortcode( 'first_name', array( $this, 'fname') );
		add_shortcode( 'child_pages', array( $this, 'sc_child_pages') );
		
		$this->custom_post_types();
		$this->modify_pt(); //may need Yoast Test Helper plugin - Reset Indexables tables & migrations

	}

	static function is_local(){
		if($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1'){
			return true;
		}
		else{
			return false;
		}
	}

	function modify_pt(){
		//may need Yoast Test Helper plugin - Reset Indexables tables & migrations

		/* filters */
		
		//staff
		add_filter( 'staff_module_slug', function(){ return 'our-attorneys'; }, 100, 1 );
		add_filter( 'staff_module_single_name', function(){ return 'Attorney'; }, 100, 1 );
		add_filter( 'staff_module_plural_name', function(){ return 'Our Attorneys'; }, 100, 1 );
		// add_filter( 'staff_module_menu_icon', function(){ return 'dashicons-businessman'; }, 100, 1 );
		// add_filter( 'staff_module_taxonomy_slug', function(){ return 'department'; }, 100, 1 );
		// add_filter( 'staff_module_taxonomy_single_name', function(){ return 'Department'; }, 100, 1 );
		// add_filter( 'staff_module_taxonomy_plural_name', function(){ return 'Departments'; }, 100, 1 );
		
		//case studies
		// add_filter( 'case_studies_module_slug', function(){ return 'portfolio'; }, 100, 1 );
		// add_filter( 'case_studies_module_single_name', function(){ return 'Portfolio'; }, 100, 1 );
		// add_filter( 'case_studies_module_plural_name', function(){ return 'Portfolio'; }, 100, 1 );
		// add_filter( 'case_studies_module_menu_icon', function(){ return 'dashicons-analytics'; }, 100, 1 );
		// add_filter( 'case_studies_module_taxonomy_slug', function(){ return 'industry'; }, 100, 1 );
		// add_filter( 'case_studies_module_taxonomy_single_name', function(){ return 'Industry'; }, 100, 1 );
		// add_filter( 'case_studies_module_taxonomy_plural_name', function(){ return 'Industries'; }, 100, 1 );
		


	}

	function add_cpt_archive_parent_breadcrumb( $crumbs ){
		$archive_crumbs = array();
		$post_type;


		// Section for adding the parent one level from the end
		if ( is_post_type_archive() || is_tax() ) {
			if ( is_tax() ) {
				$tax_name = get_queried_object()->taxonomy;
				$module_end = strpos($tax_name, "module") + strlen( "module" );
				$post_type = substr($tax_name, 0, $module_end );
			} else {
				$post_type = get_queried_object()->name;
			}
			$field_name = $post_type . '_parent';
			$archive_parent = get_field( $field_name, 'option' );

			if( $archive_parent ){
				array_push( $archive_crumbs, array('url' => get_permalink($archive_parent->ID), 'text' => $archive_parent->post_title, 'id' => $archive_parent->ID,), array_pop( $crumbs ) );
				$crumbs = array_merge( $crumbs, $archive_crumbs);
			}
		}

		// Section for adding the parent two levels from the end
		if ( is_singular() ) {
			$post_type = get_post_type();
			$field_name = $post_type . '_parent';
			$archive_parent = get_field( $field_name, 'option' );

			if( $archive_parent ){
				array_push( $archive_crumbs, array_pop( $crumbs ), array_pop( $crumbs ), array('url' => get_permalink($archive_parent->ID), 'text' => $archive_parent->post_title, 'id' => $archive_parent->ID) );
				$archive_crumbs = array_reverse( $archive_crumbs);
				$crumbs = array_merge( $crumbs, $archive_crumbs);
			}
		}
		return $crumbs;
	}

	function after_setup_theme(){

		// add_theme_support( 'case-studies-module' );
		// add_theme_support( 'locations-module' );
		add_theme_support( 'staff-module' );
		// add_theme_support( 'events-module' );
	}

	function wp_enqueue_scripts(){
		$msw_object = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'home_url' => home_url(),
			'show_dashboard_link' => current_user_can( 'manage_options' ) ? 1 : 0,
			'site_url' => site_url(),
			'stylesheet_directory' => get_stylesheet_directory_uri(),
		);
		if ( get_theme_support( 'locations-module' ) ) {
		 	$msw_object['google_map_data'] = get_google_map_data();
		}

		if ( get_field( 'default_google_map_api_key', 'option' ) ) :
			$google_api_key = 'https://maps.googleapis.com/maps/api/js?key=' . get_field( 'default_google_map_api_key', 'option' ) . '&callback=Function.prototype';
			wp_enqueue_script('google-maps', $google_api_key, true);
		endif;

		wp_enqueue_script( 'theme', get_stylesheet_directory_uri() . '/scripts.min.js', array( 'jquery' ), filemtime( get_stylesheet_directory() . '/scripts.min.js' ) );
		wp_localize_script( 'theme', 'MSWObject', $msw_object );

		//wp_enqueue_style( 'google-fonts', '', [], null );
		wp_enqueue_style( 'theme', get_stylesheet_uri(), array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
	}
	
	function msw_admin_enqueue_scripts() {
		wp_enqueue_style( 'msw-admin-css', get_theme_file_uri( 'admin.css' ) );
	}

	function msw_acf_init() {
		if ( get_field( 'default_google_map_api_key', 'option' ) ) :
			acf_update_setting('google_api_key', get_field( 'default_google_map_api_key', 'option' ));
		endif;

		acf_add_options_sub_page( array(
			'page_title'  => 'Practice Area Defaults',
			'menu_title'  => 'Practice Area Defaults',
			'menu_slug'   => 'practice-area-defaults',
			'parent_slug' => 'edit.php?post_type=page',
		) );
	}

	function msw_loaded() {
		// Custom Thumbnail Sizes
		add_theme_support( 'post-thumbnails' );
		// add_image_size( 'blog-image', 400, 300, true ); // Example
	}

	function msw_ajax_atc() {
		// Example use case for shop archive page
		/*remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		add_action( 'woocommerce_after_shop_loop_item', 'ms_ajax_shop', 10 );
		function ms_ajax_shop() {
			echo prepare_ajax_atc();
		}

		add_action( 'wp_ajax_ms_ajax_atc', 'ms_ajax_atc' );
		add_action( 'wp_ajax_nopriv_ms_ajax_atc', 'ms_ajax_atc' );
		function ms_ajax_atc() {
			do_ajax_atc( $_POST['woo_ajax_object'] );
		}*/
	}

	static function format_number_string( $input, $addcommas = false ){
		$num = preg_replace('/[^0-9]/', '', $input);
		if($addcommas == true){
			$numFormatted = number_format($num);
		}
		else{
			$numFormatted = $num;
			/*$numInt = intval($num);
			
			if($numInt >= 2147483647){ // http://php.net/manual/en/function.intval.php
				$numFormatted = $num;
			}
			else{
				$numFormatted = $numInt;
			}*/
		}
		return $numFormatted;
	}

	// for display no page
	static function hide_email($email){
		$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
		$key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);
		for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];
		$script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
		$script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
		$script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
		// $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")"; 
		$script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
		return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
	}
	
	//for href
	static function hide_email2($email){
		$email = $email;
		$crackme = "";
		for ($i=0; $i<strlen($email); $i++){
			$crackme .= "&#" . ord($email[$i]) . ";";
		}
		return $crackme;
	}
	
	static function get_primary_location(){
		$locations = get_posts(array(
			'post_type' => 'locations_module',
			'meta_key' => 'primary_location',
			'meta_value' => '1'
		));

		return $locations[0] ?? null;
	}

	static function get_google_directions_url( $destination ){
		$url = "https://www.google.com/maps/dir/?api=1&destination=" . urlencode( $destination );
		return $url;
	}

	static function slugify($string) {
		//Lower case everything
		$string = strtolower($string);
		//Make alphanumeric (removes all other characters)
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean up multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}

	function custom_post_types() {

		register_post_type( 'case_result', array(
			'label' => 'Case Results',
			'labels' => array(
				'name' => 'Case Results',
				'singular_name' => 'Case Result',
			),
			'has_archive' => 'case-results',
			'hierarchical' => true,
			'public' => true,
			'supports' => array( 'title', 'editor', 'excerpt', 'revisions', 'page-attributes' ),
			'menu_icon' => 'dashicons-portfolio',
			'show_in_rest' => true,
			'rewrite' => array(
				'slug' =>  'case-results'
			)
		) );

		register_taxonomy( 'case_category', 'case_result', array(
			'label' => 'Case Result Categories',
			'labels' => array(
				'name' => 'Case Result Categories',
				'singular_name' => 'Case Result Category',

				// 'search_items' => 'Search Categories',
				// 'popular_items' => 'Popular Categories', //This label is only used for non-hierarchical taxonomies.
				// 'all_items' => 'All Categories',
				// 'parent_item' => 'Parent Category', //This label is only used for hierarchical taxonomies.
				// 'parent_item_colon' => 'Parent Category:', //The same as parent_item, but with colon : in the end.
				// 'edit_item' => 'Edit Category',
				// 'view_item' => 'View Category',
				// 'update_item' => 'Update Category',
				// 'add_new_item' => 'Add New Category',
				// 'new_item_name' => 'New Category Name',
				// 'separate_items_with_commas' =>  'Separate tags with commas', //This label is only used for non-hierarchical taxonomies.
				// 'add_or_remove_items' => 'Add or remove tags', // This label is only used for non-hierarchical taxonomies. 
				// 'choose_from_most_used' => 'Choose from the most used tags', //This label is only used on non-hierarchical taxonomies.
				// 'not_found' => 'No categories found',
				// 'no_terms' => 'No categories',
				// 'filter_by_item' => 'Filter by category', //This label is only used for hierarchical taxonomies
				// 'item_link' => 'Category Link',
				// 'item_link_description' => 'A link to a category'
			),
			'hierarchical' => true,
			'show_admin_column' => true,
			'show_in_rest' => true
		) );

		register_taxonomy( 'case_outcome', 'case_result', array(
			'label' => 'Case Result Outcomes',
			'labels' => array(
				'name' => 'Case Result Outcomes',
				'singular_name' => 'Case Result Outcome',

				// 'search_items' => 'Search Categories',
				// 'popular_items' => 'Popular Categories', //This label is only used for non-hierarchical taxonomies.
				// 'all_items' => 'All Categories',
				// 'parent_item' => 'Parent Category', //This label is only used for hierarchical taxonomies.
				// 'parent_item_colon' => 'Parent Category:', //The same as parent_item, but with colon : in the end.
				// 'edit_item' => 'Edit Category',
				// 'view_item' => 'View Category',
				// 'update_item' => 'Update Category',
				// 'add_new_item' => 'Add New Category',
				// 'new_item_name' => 'New Category Name',
				// 'separate_items_with_commas' =>  'Separate tags with commas', //This label is only used for non-hierarchical taxonomies.
				// 'add_or_remove_items' => 'Add or remove tags', // This label is only used for non-hierarchical taxonomies. 
				// 'choose_from_most_used' => 'Choose from the most used tags', //This label is only used on non-hierarchical taxonomies.
				// 'not_found' => 'No categories found',
				// 'no_terms' => 'No categories',
				// 'filter_by_item' => 'Filter by category', //This label is only used for hierarchical taxonomies
				// 'item_link' => 'Category Link',
				// 'item_link_description' => 'A link to a category'
			),
			'hierarchical' => true,
			'show_admin_column' => true,
			'show_in_rest' => true
		) );

		add_action('acf/init', function() {
			acf_add_options_sub_page( array(
				'page_title' => 'Case Results Settings',
				'menu_title' => 'Case Results Settings',
				'menu_slug' => 'case_result-archive-settings',
				'parent_slug' => 'edit.php?post_type=case_result'
			) );
		});


		register_post_type( 'review', array(
			'label' => 'Reviews',
			'labels' => array(
				'name' => 'Reviews',
				'singular_name' => 'Review',
			),
			'has_archive' => 'reviews',
			'hierarchical' => true,
			'public' => true,
			'supports' => array( 'title', 'editor', 'excerpt', 'revisions', 'page-attributes' ),
			'menu_icon' => 'dashicons-testimonial',
			'show_in_rest' => true,
			'rewrite' => array(
				'slug' =>  'reviews'
			)
		) );

		register_taxonomy( 'review_category', 'review', array(
			'label' => 'Reviews Categories',
			'labels' => array(
				'name' => 'Reviews Categories',
				'singular_name' => 'Reviews Category',
			),
			'hierarchical' => true,
			'show_admin_column' => true,
			'show_in_rest' => true
		) );

		add_action('acf/init', function() {
			acf_add_options_sub_page( array(
				'page_title' => 'Reviews Settings',
				'menu_title' => 'Reviews Settings',
				'menu_slug' => 'review-archive-settings',
				'parent_slug' => 'edit.php?post_type=review'
			) );
		});


		register_post_type( 'faq', array(
			'label' => 'FAQ',
			'labels' => array(
				'name' => 'FAQ',
				'singular_name' => 'FAQ',
			),
			'has_archive' => 'faq',
			'hierarchical' => true,
			'public' => true,
			'supports' => array( 'title', 'editor', 'excerpt', 'revisions', 'page-attributes' ),
			'menu_icon' => 'dashicons-editor-help',
			'show_in_rest' => true,
			// 'rewrite' => array(
			// 	'slug' =>  'faqs'
			// )
		) );

		register_taxonomy( 'faq_category', 'faq', array(
			'label' => 'FAQ Categories',
			'labels' => array(
				'name' => 'FAQ Categories',
				'singular_name' => 'FAQ Category',
			),
			'hierarchical' => true,
			'show_admin_column' => true,
			'show_in_rest' => true
		) );

		add_action('acf/init', function() {
			acf_add_options_sub_page( array(
				'page_title' => 'FAQ Settings',
				'menu_title' => 'FAQ Settings',
				'menu_slug' => 'faq-archive-settings',
				'parent_slug' => 'edit.php?post_type=faq'
			) );
		});


	}

	function pre_get_posts( $query ){
		if( $query->is_main_query() && ! is_admin() ){

			// if ( is_post_type_archive( 'service' ) ){
			// 	$query->set( 'orderby', 'menu_order' );
			// 	$query->set( 'order', 'ASC' );
			// 	$query->set( 'posts_per_page', -1 );
			// }

		}
	}

	/* get first name of staff (first word of post title) and convert to possessive ( Bob -> Bob's, Kris -> Kris', etc )*/
	function fname_possessive($atts){
		
		if(isset($atts['id'])){
			$id = $atts['id'];
		}
		else{
			$id = get_the_ID();
		}

		$names = get_the_title($id);
		$names_arr = preg_split('/\s+/', $names);
		$first_name = $names_arr[0];
		
		if(substr($first_name, -1) == 's'){
			$first_name_possessive = $first_name . '\'';
		}
		else{
			$first_name_possessive = $first_name . '\'s';
		}

		return $first_name_possessive;
	}

	/* get first name of staff (first word of post title) */
	function fname($atts){
		
		if(isset($atts['id'])){
			$id = $atts['id'];
		}
		else{
			$id = get_the_ID();
		}

		$names = get_the_title($id);
		$names_arr = preg_split('/\s+/', $names);
		$first_name = $names_arr[0];
		return $first_name;
	}

	
	function sc_child_pages($atts){
		if(isset($atts['id'])){
			$id = $atts['id'];
		}
		else{
			$id = get_the_ID();
		}


		$output = '';

		$child_pages = get_posts(array(
			'post_type' => 'page',
			'post_parent' => $id,
			'posts_per_page' => -1,
			'fields' => 'ids',
			'orderby' => 'menu_order',
			'order' => 'ASC'
		));

		if($child_pages){
			if(isset($atts['layout']) && $atts['layout'] == 'sections'){
				$output .= '<div class="child-pages-list-sections">';
				foreach ($child_pages as $pg_id) {
					$output .= '<section class="child-page-section">';
						$output .= '<h2 class="section-title"><a href="' . get_permalink($pg_id) . '">' . get_the_title($pg_id) . '</a></h2>';
						$output .= '<div class="excerpt">' . get_the_excerpt($pg_id) . '</div>';
						$output .= '<a href="' . get_permalink($pg_id) . '" class="button" title="' . get_the_title($pg_id) . '">Learn More</a>';
					$output .= '</section>';
				}
				$output .= '</div>';
			}
			else{
				$output .= '<ul class="child-pages-list">';
				foreach ($child_pages as $pg_id) {
					$output .= '<li><a href="' . get_permalink($pg_id) . '">' . get_the_title($pg_id) . '</a></li>';
				}
				$output .= '</ul>';
			}
		}

		return $output;
	}



	/* get array of post info */
	static function get_post_info($id){

		$post_image = '';
		if( get_the_post_thumbnail_url($id) ){
			$post_image = get_the_post_thumbnail_url( $id, 'medium' );
		}
		elseif(get_field( 'default_placeholder_image', 'option' )){
			$post_image = get_field( 'default_placeholder_image', 'option' )['sizes']['medium'];
		}

		$blog_author_id = get_post($id)->post_author;
		$post_author = get_the_author_meta('display_name', $blog_author_id);

		$post_cat = array();
		if($post_categories = get_the_category($id)){
			// $first_cat = $post_categories[0]->name;
			foreach ($post_categories as $cat) {
				
				if($cat->name !== 'Uncategorized'){
					$post_cat[] = $cat;
				}
			}
		}

		// $post_obj = get_post($id);
		// $post_content = $post_obj->post_content;
		// $excerpt_content = strip_tags( $post_content, '<br>' );
		// $excerpt_content = substr( $excerpt_content, 0, 200 ) . ' [...]';
		// $post_excerpt = $excerpt_content;
		
		$post_excerpt = get_the_excerpt($id);

		$post_info = array(
			'title' => get_the_title($id),
			'date' => get_the_time( 'F j, Y', $id ),
			'permalink' => get_permalink($id),
			'read_time' => get_read_time($id),
			'image' => $post_image,
			'author' => $post_author,
			'category' => $post_cat,
			'excerpt' => $post_excerpt,
			'content' => get_the_content(null,false,$id)
		);

		return $post_info;
	}

}

$MakespaceChild = new MakespaceChild();

/*************************************************
 * MSW Calendar 
 *************************************************/
// require_once( 'msw-calendar/msw-calendar.php' );
/*************************************************/

/* Change excerpt more */
function new_excerpt_more($more) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');


/* Limit excerpt word count  */
function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


/* get excerpt with custom length */
function get_excerpt_trim($id = null, $num_words = 20, $more = '...'){
    $excerpt = get_the_excerpt($id);
    $excerpt = wp_trim_words( $excerpt, $num_words, $more );
    return $excerpt;
}

/**
 * Responsive Image Helper Function
 *
 * @param string $image_id the id of the image (from ACF or similar)
 * @param string $image_size the size of the thumbnail image or custom image size
 * @param string $max_width the max width this image will be shown to build the sizes attribute 
 * https://www.awesomeacf.com/responsive-images-wordpress-acf/
 *
 * example:
 * <img <?php awesome_acf_responsive_image(get_field( 'image_field' ),'xlarge','2049px'); ?> alt="">
 */

function awesome_acf_responsive_image($image_id,$image_size,$max_width){

	// check the image ID is not blank
	if($image_id != '') {

		if(is_array($image_id)){
			$image_id = $image_id['ID'];
		}

		// set the default src image size
		$image_src = wp_get_attachment_image_url( $image_id, $image_size );

		// set the srcset with various image sizes
		$image_srcset = wp_get_attachment_image_srcset( $image_id, $image_size );

		// generate the markup for the responsive image
		echo 'src="'.$image_src.'" srcset="'.$image_srcset.'" sizes="(max-width: '.$max_width.') 100vw, '.$max_width.'"';

	}
}

/*************************************************
 convert gform submit input to button
**************************************************/
/*
add_filter( 'gform_submit_button', 'input_to_button', 10, 2 );
function input_to_button( $button, $form ) {
    $dom = new DOMDocument();
    $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $button );
    $input = $dom->getElementsByTagName( 'input' )->item(0);
    $new_button = $dom->createElement( 'button' );
    $new_button_span = $dom->createElement( 'span' );
    $new_button->appendChild( $new_button_span );
    $new_button_span->appendChild( $dom->createTextNode( $input->getAttribute( 'value' ) ) );
    $input->removeAttribute( 'value' );
    foreach( $input->attributes as $attribute ) {
        $new_button->setAttribute( $attribute->name, $attribute->value );
    }
    $input->parentNode->replaceChild( $new_button, $input );
 
    return $dom->saveHtml( $new_button );
}
*/

/*************************************************
add field type class
**************************************************/
add_filter( 'gform_field_css_class', 'custom_class', 10, 3 );
function custom_class( $classes, $field, $form ) {
    $classes .= ' field_type_' . $field->type;

    return $classes;
}


/*************************************************
some spam blocks for the default contact form
************************************************/
add_filter( 'gform_validation', 'custom_validation' );
function custom_validation( $validation_result ) {
	$form = $validation_result['form'];

	if($form['id'] == 1){

		$firstname = rgpost( 'input_1' );
		$lastname = rgpost( 'input_2' );
		$email = rgpost( 'input_3' );
		$phone = rgpost( 'input_4' );
		$textarea = rgpost( 'input_5' );

		if ( $firstname == $lastname ) {

			// set the form validation to false
			$validation_result['is_valid'] = false;

			foreach( $form['fields'] as &$field ) {

				if ( $field->id == '1' || $field->id == '2' ) {
					$field->failed_validation = true;
					$field->validation_message = 'This field is invalid!';

					if ( $field->id == '2' ) {
						break;
					}
				}
			}
		}
		
		elseif(strpos($firstname, 'typodar') !== false){
			$validation_result['is_valid'] = false;

			foreach( $form['fields'] as &$field ) {

				if ( $field->id == '1' ) {
					$field->failed_validation = true;
					$field->validation_message = 'This field is invalid!';
					break;
				}
			}
		}

		elseif(strpos($lastname, 'typodar') !== false){
			$validation_result['is_valid'] = false;

			foreach( $form['fields'] as &$field ) {

				if ( $field->id == '2' ) {
					$field->failed_validation = true;
					$field->validation_message = 'This field is invalid!';
					break;
				}
			}
		}

		elseif(
			$email == "eric.jones.z.mail@gmail.com" ||
			$email == "waddoudszosense@gmail.com" ||
			$email == "fabianv8projection@gmail.com" ||
			$email == "kesleyxszqr73@gmail.com" ||
			strpos($email, 'sibicomail.com') !== false ||
			strpos($email, 'marketingguruco') !== false ||
			strpos($email, 'marketvalue') !== false ||
			strpos($email, 'waddoudszosense') !== false ||
			strpos($email, 'data-backup-store') !== false ||
			strpos($email, 'fabianv8projection') !== false
		){
			$validation_result['is_valid'] = false;

			foreach( $form['fields'] as &$field ) {
				if ( $field->id == '3' ) {
					$field->failed_validation = true;
					$field->validation_message = 'This email is invalid!';
					break;
				}
			}
		}

		elseif (
			strpos($textarea, '.ru') !== false ||
			strpos($textarea, 'youtube.com') !== false ||
			strpos($textarea, 'youtu.be') !== false ||
			strpos($textarea, 'porn') !== false ||
			strpos($textarea, 'sex') !== false ||
			strpos($textarea, 'SEO') !== false ||
			strpos($textarea, 'PPC') !== false ||
			strpos($textarea, 'crypto') !== false ||
			strpos($textarea, 'http') !== false ||
			strpos($textarea, 'www') !== false ||
			strpos($textarea, '@') !== false ||
			strpos($textarea, 'nutricompany') !== false ||
			strpos($textarea, 'marketingguruco') !== false || 
			strpos($textarea, 'marketvalue') !== false
		) {
			$validation_result['is_valid'] = false;
			
			foreach( $form['fields'] as &$field ) {
				if ( $field->id == '5' ) {
					$field->failed_validation = true;
					$field->validation_message = 'Contains invalid content! No spam, URLs, or email allowed';
					break;
				}
			}
		}
	}

	//Assign modified $form object back to the validation result
	$validation_result['form'] = $form;
	return $validation_result;
}




/*************************************************
Change inline font-size to rem
**************************************************/
add_filter( 'the_content', 'filter_the_content', 1 );
 
function filter_the_content( $content ) {

	if(strpos($content, 'style="font-size:')){
		preg_match('/font-size:(.+?)px/', $content, $edit);

		$content = preg_replace_callback(
			'/font-size:(.+?)px/',
	        function ($matches) {
	        	$edit = str_replace('font-size:', '', $matches[0]);
	        	$edit = str_replace('px', '', $edit);
	        	$edit = ($edit / 10);

				return 'font-size:' . $edit  . 'rem';
			},
			$content
		);
		return $content;
	}
 
    return $content;
}


/*************************************************
replace [] with span or something
**************************************************/
function text_replace_brackets($text, $replace = 'strong'){
	// $bracketed_text = array();


	$text = str_replace('[', '<'.$replace.'>', $text);
	$text = str_replace(']', '</'.$replace.'>', $text);

	return $text;

	// preg_match_all('/\[(.*?)\]/', $text, $bracketed_text);
	
	// // preg_replace(pattern, replacement, subject);
	// $bracketed_text_arr = $bracketed_text[0];
	
	// foreach($bracketed_text_arr as $txt){

	// 	$txt = str_replace('[', '<strong>', $txt);
	// 	$txt = str_replace(']', '</strong>', $txt);
	// 	// echo $txt;
	// }
	
	// echo '<pre>';
	// print_r($bracketed_text_arr);
	// echo '</pre>';
}

class sub_menu_walker extends Walker_Nav_Menu {

     function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '<div class="sub-menu-wrap"><ul class="sub-menu">';
    }

     function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '</ul></div>';
    }
}


/*************************************************
 * Practice Areas mega menu (desktop primary nav)
 *
 * Drops a panel under the "Practice Areas" menu item built from the page
 * hierarchy under the Practice Areas page. Top-level practice areas are
 * column headings; only the deepest (leaf) page in each branch is a link —
 * an area with no subpages links to itself.
 *************************************************/

/* ID of the "Practice Areas" page (the menu item links to it). Cached per request. */
function omythic_practice_areas_page_id(){
	static $id = null;
	if ( $id !== null ) return $id;
	$page = get_page_by_path( 'practice-areas' );
	$id = $page ? (int) $page->ID : 0;
	return $id;
}

/* True if the page has at least one child page. */
function omythic_page_has_children( $page_id ){
	return (bool) get_pages( array(
		'child_of' => $page_id,
		'parent'   => $page_id,
		'number'   => 1,
	) );
}

/* Left-hand promo feature block, populated from Theme Options → Practice Menu. */
function omythic_mega_feature_html(){
	$feature = get_field( 'mega_feature', 'option' );
	$contact = get_field( 'contact', 'option' );

	$has_feature = $feature && ( ! empty( $feature['eyebrow'] ) || ! empty( $feature['title'] ) || ! empty( $feature['content'] ) );
	$has_phone   = $contact && ! empty( $contact['phone'] );
	if ( ! $has_feature && ! $has_phone ) return '';

	$out = '<div class="mega-feature">';
	if ( ! empty( $feature['eyebrow'] ) ) {
		$out .= '<span class="mega-feature-eyebrow">' . esc_html( $feature['eyebrow'] ) . '</span>';
	}
	if ( ! empty( $feature['title'] ) ) {
		$out .= '<h3 class="mega-feature-title">' . esc_html( $feature['title'] ) . '</h3>';
	}
	if ( ! empty( $feature['content'] ) ) {
		$out .= '<p class="mega-feature-text">' . wp_kses_post( $feature['content'] ) . '</p>';
	}
	if ( ! empty( $feature['stamp'] ) ) {
		$out .= '<p class="mega-feature-stamp">' . esc_html( $feature['stamp'] ) . '</p>';
	}
	if ( ! empty( $feature['button'] ) ) {
		$b = $feature['button'];
		$out .= '<a class="mega-feature-cta" href="' . esc_url( $b['url'] ) . '" target="' . esc_attr( $b['target'] ) . '">';
		$out .= esc_html( $b['title'] );
		$out .= ' <span class="mega-feature-cta-arrow"><i class="fas fa-arrow-right"></i></span></a>';
	}
	if ( $has_phone ) {
		$tel = preg_replace( '/[^0-9+]/', '', $contact['phone'] );
		$out .= '<span class="mega-feature-call">Or call <a href="tel:' . esc_attr( $tel ) . '">' . esc_html( $contact['phone'] ) . '</a></span>';
	}
	$out .= '</div>';
	return $out;
}

/* Build the full-width mega menu panel for the Practice Areas item.
 * Layout: promo feature (left) + grid of practice-area categories (right).
 * Each category head links to the practice-area page; its direct child
 * pages are listed as links beneath it. */
function omythic_practice_areas_mega_menu( $parent_id ){
	$areas = get_pages( array(
		'child_of'    => $parent_id,
		'parent'      => $parent_id,
		'sort_column' => 'menu_order,post_title',
		'sort_order'  => 'ASC',
	) );
	if ( ! $areas ) return '';

	$out  = '<div class="mega-menu"><div class="mega-inner">';
	$out .= omythic_mega_feature_html();

	$out .= '<div class="mega-grid">';
	foreach ( $areas as $area ) {
		$children = get_pages( array(
			'child_of'    => $area->ID,
			'parent'      => $area->ID,
			'sort_column' => 'menu_order,post_title',
			'sort_order'  => 'ASC',
		) );

		$out .= '<div class="mega-cat">';
		// Practice-area name is a heading, not a link — only child pages link.
		$out .= '<div class="mega-cat-head">' . get_the_title( $area->ID ) . '</div>';
		if ( $children ) {
			$out .= '<ul class="mega-cat-list">';
			foreach ( $children as $child ) {
				$out .= '<li><a href="' . esc_url( get_permalink( $child->ID ) ) . '">' . get_the_title( $child->ID ) . '</a></li>';
			}
			$out .= '</ul>';
		}
		$out .= '</div>';
	}
	$out .= '</div>';

	$out .= '</div></div>';
	return $out;
}

/* Flag the Practice Areas primary-menu item so it gets the dropdown chevron + hover hooks. */
add_filter( 'nav_menu_css_class', 'omythic_pa_menu_classes', 10, 4 );
function omythic_pa_menu_classes( $classes, $item, $args, $depth ){
	if ( $depth !== 0 ) return $classes;
	if ( ! isset( $args->theme_location ) || $args->theme_location !== 'primary' ) return $classes;
	$pa_id = omythic_practice_areas_page_id();
	if ( $pa_id && $item->object === 'page' && (int) $item->object_id === $pa_id ) {
		$classes[] = 'menu-item-has-children';
		$classes[] = 'menu-item-mega';
	}
	return $classes;
}

/* Inject the mega menu panel inside the Practice Areas <li>. */
add_filter( 'walker_nav_menu_start_el', 'omythic_pa_menu_mega', 10, 4 );
function omythic_pa_menu_mega( $item_output, $item, $depth, $args ){
	if ( $depth !== 0 ) return $item_output;
	if ( ! isset( $args->theme_location ) || $args->theme_location !== 'primary' ) return $item_output;
	$pa_id = omythic_practice_areas_page_id();
	if ( $pa_id && $item->object === 'page' && (int) $item->object_id === $pa_id ) {
		$item_output .= omythic_practice_areas_mega_menu( $pa_id );
	}
	return $item_output;
}

/* ---------------------------------------------------------------
 * Off-canvas (pop-out) version of the Practice Areas tree.
 * Below the desktop-nav breakpoint the main menu is hidden, so the
 * same hierarchy is injected into the pop-out menu as an expandable
 * accordion. Markup mirrors what sub_menu_walker + the menu's
 * before/after args produce so the framework's toggle JS works.
 * ------------------------------------------------------------- */

/* Build the 2-level Practice Areas accordion for the pop-out: each practice
 * area is a non-linked toggle heading; its direct child pages are the links.
 * Mirrors the desktop mega menu (heads not linked, only child pages linked). */
function omythic_pa_ocn_tree( $parent_id ){
	$areas = get_pages( array(
		'child_of'    => $parent_id,
		'parent'      => $parent_id,
		'sort_column' => 'menu_order,post_title',
		'sort_order'  => 'ASC',
	) );
	if ( ! $areas ) return '';

	$out = '<div class="sub-menu-wrap"><ul class="sub-menu">';
	foreach ( $areas as $area ) {
		$children = get_pages( array(
			'child_of'    => $area->ID,
			'parent'      => $area->ID,
			'sort_column' => 'menu_order,post_title',
			'sort_order'  => 'ASC',
		) );

		$out .= '<li class="menu-item' . ( $children ? ' menu-item-has-children' : '' ) . '">';
		$out .= '<span class="ocn-link-wrap">';
		// Practice-area name is not a link — tap toggles its children.
		$out .= '<a href="#">' . get_the_title( $area->ID ) . '</a>';
		if ( $children ) {
			$out .= '<button aria-pressed="false" name="Menu item dropdown toggle" class="ocn-sub-menu-button"></button>';
		}
		$out .= '</span>';
		if ( $children ) {
			$out .= '<div class="sub-menu-wrap"><ul class="sub-menu">';
			foreach ( $children as $child ) {
				$out .= '<li class="menu-item"><span class="ocn-link-wrap">';
				$out .= '<a href="' . esc_url( get_permalink( $child->ID ) ) . '">' . get_the_title( $child->ID ) . '</a>';
				$out .= '</span></li>';
			}
			$out .= '</ul></div>';
		}
		$out .= '</li>';
	}
	$out .= '</ul></div>';
	return $out;
}

/* The Practice Areas pop-out <li>: non-link toggle heading + page-hierarchy accordion. */
function omythic_pa_ocn_li( $pa_id ){
	$li  = '<li class="menu-item menu-item-has-children ocn-primary-item ocn-pa-item">';
	$li .= '<span class="ocn-link-wrap">';
	$li .= '<a href="#">' . get_the_title( $pa_id ) . '</a>';
	$li .= '<button aria-pressed="false" name="Menu item dropdown toggle" class="ocn-sub-menu-button"></button>';
	$li .= '</span>';
	$li .= omythic_pa_ocn_tree( $pa_id );
	$li .= '</li>';
	return $li;
}

/* Render the primary (desktop) menu as pop-out accordion markup. The Practice
 * Areas item becomes the page-hierarchy accordion; every other item uses its
 * normal link and WP sub-menu. */
function omythic_primary_menu_ocn_html(){
	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['primary'] ) ? $locations['primary'] : 0;
	if ( ! $menu_id ) return '';
	$items = wp_get_nav_menu_items( $menu_id );
	if ( ! $items ) return '';

	$pa_id     = omythic_practice_areas_page_id();
	$by_parent = array();
	foreach ( $items as $it ) {
		$by_parent[ (int) $it->menu_item_parent ][] = $it;
	}
	$tops = isset( $by_parent[0] ) ? $by_parent[0] : array();

	$out = '';
	foreach ( $tops as $it ) {
		// Practice Areas → the page-hierarchy accordion.
		if ( $pa_id && $it->object === 'page' && (int) $it->object_id === $pa_id ) {
			$out .= omythic_pa_ocn_li( $pa_id );
			continue;
		}

		$kids     = isset( $by_parent[ (int) $it->ID ] ) ? $by_parent[ (int) $it->ID ] : array();
		$has_kids = ! empty( $kids );

		$out .= '<li class="menu-item ocn-primary-item' . ( $has_kids ? ' menu-item-has-children' : '' ) . '">';
		$out .= '<span class="ocn-link-wrap"><a href="' . esc_url( $it->url ) . '">' . $it->title . '</a>';
		if ( $has_kids ) {
			$out .= '<button aria-pressed="false" name="Menu item dropdown toggle" class="ocn-sub-menu-button"></button>';
		}
		$out .= '</span>';
		if ( $has_kids ) {
			$out .= '<div class="sub-menu-wrap"><ul class="sub-menu">';
			foreach ( $kids as $kid ) {
				$out .= '<li class="menu-item"><span class="ocn-link-wrap"><a href="' . esc_url( $kid->url ) . '">' . $kid->title . '</a></span></li>';
			}
			$out .= '</ul></div>';
		}
		$out .= '</li>';
	}
	return $out;
}

/* Inject the primary menu (incl. the Practice Areas accordion) at the top of the
 * pop-out — right after the first item (the "Contact / Case Reviews" CTA pill),
 * so the CTA keeps its pill styling. Only visible below the desktop-nav
 * breakpoint (see .ocn-primary-item in SCSS), where the main menu is hidden. */
add_filter( 'wp_nav_menu_items', 'omythic_pa_ocn_inject', 10, 2 );
function omythic_pa_ocn_inject( $items, $args ){
	if ( ! isset( $args->container_id ) || $args->container_id !== 'ocn-nav-popout' ) return $items;

	$block = omythic_primary_menu_ocn_html();
	if ( ! $block ) return $items;

	$pos = strpos( $items, '</li>' );
	if ( $pos !== false ) {
		$pos += strlen( '</li>' );
		return substr( $items, 0, $pos ) . $block . substr( $items, $pos );
	}
	return $block . $items;
}

/* single pagination custom order prev */
// function get_custom_previous_post( $post ) {
//     if ( !$post ) {
//         return null;
//     }
	
// 	global $wpdb;

//     if ( $post->post_type == 'staff_module' ) {
// 	    // Fetch the current post's last_name meta value
// 	    $last_name = get_post_meta( $post->ID, 'last_name', true );

// 	    // order by field "last_name"
// 	    $previous_post = $wpdb->get_row(
// 	        $wpdb->prepare( "
// 	            SELECT p.ID
// 	            FROM $wpdb->posts p
// 	            INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
// 	            WHERE pm.meta_key = 'last_name'
// 	            AND pm.meta_value < %s
// 	            AND p.post_type = 'staff_module'
// 	            AND p.post_status = 'publish'
// 	            ORDER BY pm.meta_value DESC, p.post_date DESC
// 	            LIMIT 1
// 	        ", $last_name )
// 	    );

// 	    return $previous_post ? get_post( $previous_post->ID ) : null;
// 	}
// 	elseif( $post->post_type == 'case_studies_module' ){
		
// 		$current_menu_order = $post->menu_order;

// 	    $previous_post = $wpdb->get_row(
// 	        $wpdb->prepare( "
// 	            SELECT p.ID
// 	            FROM $wpdb->posts p
// 	            WHERE p.menu_order < %d
// 	            AND p.post_type = 'case_studies_module'
// 	            AND p.post_status = 'publish'
// 	            ORDER BY p.menu_order DESC, p.post_date DESC
// 	            LIMIT 1
// 	        ", $current_menu_order )
// 	    );

// 	    return $previous_post ? get_post( $previous_post->ID ) : null;
// 	}
// 	else{
// 		return get_previous_post();
// 	}
// }

/* single pagination custom order next */
// function get_custom_next_post( $post ) {
//     if ( !$post  ) {
//         return null;
//     }

//     global $wpdb;

//     if ( $post->post_type == 'staff_module' ) {
// 	    // Fetch the current post's last_name meta value
// 	    $last_name = get_post_meta( $post->ID, 'last_name', true );

// 	    // order by field "last_name"
// 	    $next_post = $wpdb->get_row(
// 	        $wpdb->prepare( "
// 	            SELECT p.ID
// 	            FROM $wpdb->posts p
// 	            INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
// 	            WHERE pm.meta_key = 'last_name'
// 	            AND pm.meta_value > %s
// 	            AND p.post_type = 'staff_module'
// 	            AND p.post_status = 'publish'
// 	            ORDER BY pm.meta_value ASC, p.post_date ASC
// 	            LIMIT 1
// 	        ", $last_name )
// 	    );

// 	    return $next_post ? get_post( $next_post->ID ) : null;
// 	}
// 	elseif( $post->post_type == 'case_studies_module' ){

// 		$current_menu_order = $post->menu_order;

// 	    $next_post = $wpdb->get_row(
// 	        $wpdb->prepare( "
// 	            SELECT p.ID
// 	            FROM $wpdb->posts p
// 	            WHERE p.menu_order > %d
// 	            AND p.post_type = 'case_studies_module'
// 	            AND p.post_status = 'publish'
// 	            ORDER BY p.menu_order ASC, p.post_date ASC
// 	            LIMIT 1
// 	        ", $current_menu_order )
// 	    );

// 	    return $next_post ? get_post( $next_post->ID ) : null;
// 	}
// 	else{
// 		return get_next_post();
// 	}
// }
