<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Returns a code block
 *
 * @return void
 * 
 * @author	Josh Robbs <josh@joshrobbs.com>
 */

// this fn calls an Elementor template that's just a code block that is dynamically populated with the_code field
 
// function jwr_get_snippet_footer(){
// 	$the_code = get_field('the_code');
// 	if( $the_code ){
// 		$output = "<h2>The Code</h2>";
// 		$output .= $the_code;
// 		// $output .= do_shortcode( '[elementor-template id="257"]' ); // this will need updating for other languages
// 	}

// 	return $output;
// }