<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'jwr_custom_taxonomies', 0 );

/**
 * Summary
 * 
 * Adds taxonomies to site
 * 
 * Taxonomies: Difficulty, Code topics, Review categories
 * 
 * @author	Josh Robbs <josh@joshrobbs.com>
 */
 
function jwr_custom_taxonomies() {

	$labels = array(
    	'name' => 'Difficulty',
    	'singular_name' => 'Difficulty',
    	'search_items' =>  'Search Difficulty Levels',
    	'all_items' => 'All Difficulty Levels',
    	'edit_item' => __( 'Edit Difficulty' ), 
    	'update_item' => __( 'Update Difficulty' ),
    	'add_new_item' => __( 'Add New Difficulty' ),
    	'new_item_name' => __( 'New Difficulty' ),
    	'menu_name' => __( 'Difficulties' ),
  	);    
 
// Now register the taxonomy
  	register_taxonomy('difficulties',array('snippet','tutorial'), array(
		'hierarchical' => false,
		'labels' => $labels,
		'public'	=> true,
		'show_ui' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'difficulty' ),
  	));
	/*
	$labels = array(
    	'name' => 'Functions',
    	'singular_name' => 'Function',
    	'search_items' =>  'Search Functions',
    	'all_items' => 'All Functions',
    	'edit_item' => __( 'Edit Function' ), 
    	'update_item' => __( 'Update Function' ),
    	'add_new_item' => __( 'Add New Function' ),
    	'new_item_name' => __( 'New Function' ),
    	'menu_name' => __( 'Functions' ),
  	);    
 
// Now register the taxonomy
  	register_taxonomy('function',array('snippet', 'tutorital'), array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'functions' ),
  	));
	*/


	
	  $labels = array(
    	'name' => 'Code topics',
    	'singular_name' => 'Code topic',
    	'search_items' =>  'Search Code topics',
    	'all_items' => 'All Code topics',
    	'edit_item' => __( 'Edit Code topic' ), 
    	'update_item' => __( 'Update Code topic' ),
    	'add_new_item' => __( 'Add New Code topic' ),
    	'new_item_name' => __( 'New Code topic' ),
    	'menu_name' => __( 'Code topics' ),
  	);    
 
// Now register the taxonomy
  	register_taxonomy('code-topic',array('snippet','tutorital'), array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'code-topics' ),
  	));

	// Review category

	$labels = array(
    	'name' => 'Review categories',
    	'singular_name' => 'Review category',
    	'search_items' =>  'Search Review categories',
    	'all_items' => 'All Review categories',
    	'edit_item' => __( 'Edit Review category' ), 
    	'update_item' => __( 'Update Review category' ),
    	'add_new_item' => __( 'Add New Review category' ),
    	'new_item_name' => __( 'New Review category' ),
    	'menu_name' => __( 'Review categories' ),
  	);    
 
// Now register the taxonomy
  	register_taxonomy('review-category',array('review'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'review-category' ),
  	));

}

//// Add post_tag to CPTS
add_action( 'init', 'add_tags_to_cpts_fn' );
function add_tags_to_cpts_fn() {
	$cpts = array('tutorial','review','code-snippet');
	foreach($cpts as $cpt){
		register_taxonomy_for_object_type( 'post_tag', $cpt );
	}
};

//// Add CPTS to post_tag archive
// do I need to do this? is this a core issue or Elementor?
// based on code found at https://docs.pluginize.com/article/post-types-in-category-tag-archives/

// fixed the archive
//? didn't fix related items

function add_cpts_to_post_tag_archive_fn( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;    
	}

	if ( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {

		// Replace these slugs with the post types you want to include.
		$my_post_types = array('tutorial','review','code-snippet');

		$query->set(
	  		'post_type',
			array_merge(
				array( 'post' ),
				$my_post_types
			)
		);
	}
}
add_filter( 'pre_get_posts', 'add_cpts_to_post_tag_archive_fn' );