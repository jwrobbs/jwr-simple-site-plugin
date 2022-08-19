<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function jwr_related_posts_fn( $args ){

	$defaults = array(
		'count' => 4,
	);
	$args = wp_parse_args( $args, $defaults );

	$count = $args['count'];
	if( !is_int($count) ){ // if count isn't an interger
		$count = 4;
	}
	$query_max = $count;
	/* I don't think I need this. Will leave to test once the site is more populated
	if( $count < 6 ){ //if count too low, use 10 
		// do I still need this malarkey?
		// YES, the initial taxonomy term query cannot separate published posts
		// max query provides padding so that... wait I might be wrong
		$query_max = 10;
	}else{
		$query*/


	// get data
	global $wpdb;
	$post_id = get_the_ID();
	$post_tag_objects = get_the_terms( $post_id, 'post_tag' ); // term objs of this post's tags
	if(is_wp_error( $post_tag_objects ) || $post_tag_objects == false ){ // bail if no tags
		return;
	}
	
	// $post_tags = array(); // term ids of this posts's tags - I don't think this is used any longer
	
	$term_count = 0;
	$term_string = "("; // term_string is basically a comma separated version of $post_tags for use in a custom query
	// final format ("3", "345", "123")
	foreach( $post_tag_objects as $post_tag_object ){
		if( $term_count != 0 ){
			$term_string .= ", "; // prepend id with comma unless 1st element
		}else{
			$term_count = 1;
		}
		$term_string .= '"' . $post_tag_object->term_id . '"';
	}
	$term_string .= ")";

	//query for matches to term_id
	
	$query = "SELECT * FROM `$wpdb->term_relationships` WHERE `term_taxonomy_id` IN $term_string";
	// this query only uses values querried for and generated within this fn

	$results = $wpdb->get_results( $query );
	$tally = array(); 

	// counting the results. give query results. get array with id as key and count as value
	foreach( $results as $result ){
		$this_post_id = $result->object_id;
		// $this_term_id = $result['term_taxonomy_id'];

		if( $this_post_id == $post_id ){
			continue; //skip if is current post
		}
		$this_post_id = (string) $this_post_id; // no matter what I do I get undefined offset grrr
		$tally[$this_post_id] = $tally[$this_post_id] + 1; // increment on match
	}


	//order array
	arsort($tally);
	$related_items = array_keys($tally);

	// get related items

	$related_query_results = new WP_Query( array( 
		'post__in' 			=> $related_items, 
		'post_status'		=> 'publish',
		'post_type'			=> 'any', // this won't be an issue because query is limited by post_ids
		'posts_per_page'	=>	$query_max,
		) 
	);

	$related_loop = $related_query_results->posts;
	if( !$related_loop ){
		return;
	}
	ob_start();
	// start output
	echo "<div class='related-items'>";
	echo "<h2>Related articles</h3>";


	echo "<ul>";
	$this_count = 0;

	// create simpler list of values
	$column_ids = array_column( $related_loop, 'ID' );

	foreach( $related_items as $related_item ){	
		if( $this_count >= $count ){ //$count defined at start of fn
			break;
		}

		$this_key = array_search( $related_item, $column_ids );
		$this_post = $related_loop[$this_key];

		$this_post_type = $this_post->post_type;
		$this_title = $this_post->post_title;
		$this_link = get_the_permalink($this_post->ID);
		echo "<li><a href='$this_link'><span>$this_post_type: </span>$this_title</a></li>";

		$this_count++;
	}
	//!!! ISSUE: Items in trash still have data in term_relationships. They will affect the score. And they will trigger additional loops. 
	//!!! Current solution: keep the trash empty
	echo "</ul>";

	//return output
	wp_reset_postdata();
	$thisOutput = ob_get_clean();
	return $thisOutput;
	}
	
	
	add_shortcode( 'jwr-related-posts' , 'jwr_related_posts_fn' );
