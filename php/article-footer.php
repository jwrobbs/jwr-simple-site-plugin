<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Adds shortcode used as post footer
 * 
 * @author	Josh Robbs <josh@joshrobbs.com>
 * 
 * @return string
 */

 //# article footer shortcode
function jwr_article_footer_fn($atts = array(), $content = null){
    ob_start();
    // start output ----------
	$id = get_the_ID();
	$post_type = get_post_type(  );
	
	if( 'post' == $post_type){ // return on post
		// nothing yet
	}elseif( 'tutorial' == $post_type){ // tutorial
		// nothing yet
	}elseif( 'review' == $post_type){ // review
		echo do_shortcode( '[jwr-review-footer]' );
	}elseif( 'code-snippet' == $post_type){ // code
		//x echo "<h2>The Code</h2>";
		// echo jwr_get_snippet_footer(); // this will need updating for other languages
		// replaced with shortcode in the_content
		
	}else{
		echo "No post type match for '$post_type'";
	}

	// check for post_tag
	$my_tags = get_the_term_list($id,'post_tag', "Topics: ",', ');
	//display
	
	if($my_tags) {
		echo "<div class='jwr-tags' style='margin: 1rem 0'>$my_tags</div>";
	}

    //return output ----------
    wp_reset_postdata();
    $thisOutput = ob_get_clean();
    return $thisOutput;
}
    
add_shortcode( 'jwr-article-footer' , 'jwr_article_footer_fn' );