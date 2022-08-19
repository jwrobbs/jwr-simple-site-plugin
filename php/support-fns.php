<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*
ToC
1. Create archive card

*/

//# 1. Create archive card
/**
 * Create archive card
 * 
 * @author Josh Robbs<josh@joshrobbs.com>
 *
 * @param [obj] $post
 * @param integer $version
 * @return string
 */
function create_archive_card( $post, $version = 1 ){
	if( !$post ){
		return "error - no post data submitted to card fn";
	}
	if( 1 == $version ){
		$link = get_permalink($post->ID);
		$post_type = $post->post_type;
		
		// define excerpts

		//in general, the 2 excerpts are the same
		// and if the excerpt is missing, the title will be used for the "fi" excerpt

		//if code-snippet, the "fi" excerpt needs pulled from get_the_excerpt
		

		$title = $post->post_title;
		$excerpt = $post->post_excerpt;
		
		
		if( $post_type == 'code-snippet' ){
			// $excerpt_2 = get_the_excerpt( $post->ID );
			$excerpt_2 = wp_trim_excerpt( '', $post->ID );
		}elseif( $excerpt == "" || $excerpt == null ){ 
			$excerpt_2 = $title;
		}else{
			$excerpt_2 = $excerpt;
		}

		// output

		echo "<div class='archive-card type-$post_type'>";
		$post_type = strtoupper($post_type);// want it UC for display but not for class name

		//container
			echo "<div class='styled-excerpt-container'><a href='$link'>";
				echo "<div class='styled-excerpt'>$excerpt_2</div>"; // excerpt
			echo "</a></div>";
		
		//title
			echo "<div class='title'>";
				echo "<h2><a href='$link'><span class='post_type'>$post_type:</span> $title</a></h2>";
			echo "</div>";		

			if( $excerpt ){
				echo "<div class='excerpt-container'>";
					echo "<div class='excerpt'>$excerpt</div>"; // excerpt
				echo "</div>";	
			}

			echo "<div class='byline-container'>";
				echo "<div class='byline'>";
					$date = date_create( $post->post_date );
					echo "<div class='date'>" . date_format($date, 'M j, Y') . "</div>";
				echo "</div>";
			echo "</div>";

		echo "</div>";    
	}else{
		return "error - version requested does not exist";
	}
             
}
