<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Adds meta data to single posts (shortcode)
 * Post types depend on template assignment
 *
 * @return string
 * 
 * @author	Josh Robbs <josh@joshrobbs.com>
 */
function jwr_post_meta_fn($atts = array(), $content = null){
    ob_start();
    // start output

	// get post data
	$id = get_the_ID();
	$post_type = get_post_type($id);
	if( $post_type ){
		$link = get_post_type_archive_link( $post_type );
		$post_type = "<a href='$link'>$post_type</a>";
	}

	// get relevant data
	// author, dates, category, tag, special items

	$author = get_the_author_meta('nickname');
	$author_id = get_the_author_meta('ID');
	$author_link = get_author_posts_url($author_id);

	$post_date = get_the_date('F j, Y');
	$mod_date = get_the_modified_date('F j, Y');

	$category = get_the_term_list($id,'category', "Filed under: ",', ');
	//x $my_tags = get_the_term_list($id,'post_tag', "Topics: ",', '); moved to article footer
	
	$difficulty = get_the_term_list($id,'difficulties', "Difficulty: ",', ');
	$required_plugins = get_field('required_software');
	$related_code_snippets = get_field('related_code_snippets');
	$code_topics = get_the_term_list($id,'code-topic', "Topics: ",', ');
	$review_categories = get_the_term_list($id,'review-category', "Groups: ",', ');


	// display data with style
	?>
	<style>
		.custom-meta {
			text-align: right;
		}
		.custom-meta hr {
			margin: 1rem 0;
		}

		@media all and (max-width: 767px){
			.custom-meta {
				text-align: center;
			}
		}
	</style>
	<?php
	echo "<div class='custom-meta'>";
	echo "This $post_type was";
	echo " published&nbsp;by&nbsp;<a href='$author_link'>$author</a>";
	echo "<br />on&nbsp;$post_date";
	if($mod_date != $post_date) {
		echo "<br>updated:&nbsp;$mod_date";
	}

	// if( $category || $my_tags ){
		if( $category ){
		echo "<hr>";
	}
	if($category) {
		echo "<div>$category</div>";
	}
	// xif($my_tags) {
	//x 	echo "<div>$my_tags</div>";
	//x }
	// moved to article footer

	if($difficulty) {
		echo "<hr>";
		echo "<div>$difficulty</div>";
	}

	if($code_topics) {
		echo "<hr><div>$code_topics</div>";
	}

	if($required_plugins) {
		echo "<hr>";
		echo "<div>Required plugins:<br />";
		foreach($required_plugins as $plugin){
			$link = get_permalink($plugin->ID);
			$plugin_list_array[] = "<a href='$link'>$plugin->post_title</a>";
		}
		$plugin_list = implode(", ",$plugin_list_array);
		echo $plugin_list."</div>";
	}

	if($related_code_snippets) {
		echo "<hr>";
		echo "<div>Related code snippets:<br />";
		foreach($related_code_snippets as $snippet){
			$link = get_permalink($snippet->ID);
			$snippet_list_array[] = "<a href='$link'>$snippet->post_title</a>";
		}
		$snippet_list = implode(", ",$snippet_list_array);
		echo $snippet_list."</div>";
	}
	
	if( $review_categories ){
		echo "<hr><div>$review_categories</div>";
	}

	echo "</div>";
    //return output
    wp_reset_postdata();
    $thisOutput = ob_get_clean();
    return $thisOutput;
}
    
add_shortcode( 'jwr-post-meta' , 'jwr_post_meta_fn' );