<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 1. Set average review score
 * 2. Display shortcode for reviews
 * 
 * @author	Josh Robbs <josh@joshrobbs.com>
 */



//# 1. Set average review score
//? also add the subcriteria
// add_action( 'save_post_review', 'jwr_set_review_average', 20, 3 );
add_action( 'acf/save_post', 'jwr_set_review_average', 11);

 /**
  * Averages values of $fields_array and saves it as reivew_average_score on save_post_reviews
  *
  * @return void
  */
//   function jwr_set_review_average( $post_id, $post, $update ) {
function jwr_set_review_average( $post_id ) {

	// bail out if this is an autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

	$values = get_fields($post_id);
	$fields_array = array('impact', 'ease_of_use', 'value');
	foreach($fields_array as $field){
		$score = $values[$field];
		if ($score>0){
			$score_array[]=$score;
		}
	}
	$score_count = count($score_array);
	if($score_count > 0){
		$average_score = array_sum($score_array)/$score_count;
		$average_score = round($average_score,1);
		update_post_meta( $post_id, 'review_average_score', $average_score );
	}
}

//# 2. Display shortcode for reviews
//? next version add pricing and advanced schema

/**
 * Displays the review's custom data
 *
 * @return string
 */
function jwr_review_footer_fn($atts = array(), $content = null){
    ob_start();
    // start output

	// gather data
	$id = get_the_ID(  );
	$raw_data = get_fields($id);
	//var_dump($raw_data);

	// data
	$item_name = $raw_data['item_name']; // required item - will always be present
	$pub_dateISO = get_the_date( 'Y-m-d', $id );
	$pub_date = get_the_date( );
	$mod_dateISO = get_the_modified_date( 'Y-m-d', $id );
	$mod_date = get_the_modified_date( );
	// am I seeing double?
	$link = $raw_data['link'];
	$affiliate_link = $raw_data['affiliate_link'];

		
	$impact = $raw_data['impact'];
	$ease_of_use = $raw_data['ease_of_use'];
	$value = $raw_data['value'];

	$score = get_post_meta( $id, 'review_average_score', true );

	$review_summary = $raw_data['review_summary'];

	if( $score < 0 ){
		$color = '#820000';
	}else{
		$color = '#002244';
	}
	// style
	//[] move this to theme!
	?><style>
		.jwr-review-summary {
			border: 1px solid #333;
			padding: 1.5rem;
		}
		.jwr-review-summary h2 {
			margin: 0;
		}
		.review-details-container {
			display: flex;
			/*justify-content: space-between;*/
			justify-content: space-evenly;
			padding: .5rem;
		}
		.review-details strong {
			font-size: 1.3rem;
		}
		.jwr-review-summary .review-score {
			<?php echo "background-color: $color;"; ?>
			max-width: 150px;
			color: #fff;
			padding: .75rem;
			text-align: center;
			max-width: 
		}
		.jwr-review-summary .review-score .score {
			
		}
		.jwr-review-summary .review-score .score-value {
			font-size: 2rem;
		}
		.review-summary {
			width: 80%;
			margin-left: auto;
			margin-right: auto;
			margin-top: 2rem;
			font-style: italic;
		}
		.pro-con-lists {
			display: flex;
		}
		.pro-con-lists > div {
			flex: 0 0 50%;
		}
		.jwr-review-summary .review-button,
		.jwr-review-summary .review-button:hover {
			text-decoration: none;
		}
		
		.jwr-review-summary .review-button button{
			display: block;
			margin-left: auto;
			margin-right: auto;
			border-color: #002244;
			background-color: #002244;
			color: #fff;
		}
		.jwr-review-summary .review-button button:hover{
			text-decoration: none !important;
			background-color: transparent;
			color: #333;
		}
		@media all and (max-width: 768px) {
			.review-details-container {
				flex-wrap: wrap;
				justify-content: center;
			}
			.pro-con-lists {
				flex-wrap: wrap;

			}
			.review-details-container > div,
			.pro-con-lists > div {
				flex: 0 0 100%;
			}
			.jwr-review-summary h2, .jwr-review-summary h3, .review-details {
				text-align: center;
			}
		}
	</style><?php
	// output
	echo "<div class='jwr-review-summary'>";
		echo "<h2>Review Summary</h2>";
		if($item_name){
			echo "<div><strong>$item_name</strong></div>";
		}else{
			echo "<div><em>You really broke something.</em></div>";
		}
		echo "<div class='review-details-container'>";
			echo "<div class='review-details'>";
			/* Moved into header
			x	if($item_name){
			x		echo "<div><strong>$item_name</strong></div>";
			x	}else{
			x		echo "<div><strong>You really broke something.</strong></div>";
			x	}
			*/
				if($pub_date){
					echo "<div>Published: $pub_date</div>";
				}
				if($mod_date){
					echo "<div>Last updated: $mod_date</div>";
				}
				if($link){
					echo "<div><a href='$link'>Get it</a></div>";
				}
				if($affiliate_link){
					echo "<div><a href='$affiliate_link'>Get it (affiliate link)</a></div>";
				}
			echo "</div>";
			
			if( $impact || $ease_of_use || $value ){
				echo "<div class='score-breakdown'>";
					echo "<div class='score-breakdown-inner'>";
					if( $impact ){
						echo "<div class='individual-score score-impact'>";
							echo "Impact: $impact";
						echo "</div>";
					}
					if( $ease_of_use ){
						echo "<div class='individual-score score-ease-of-use'>";
							echo "Ease of use: $ease_of_use";
						echo "</div>";
					}
					if( $value ){
						echo "<div class='individual-score score-value'>";
							echo "Value: $value";
						echo "</div>";
					}
		
					echo "</div>";
				echo "</div>";
			}
			
			if($score){
				echo "<div class='review-score'>";
					echo "<div class='score'><div class='score-header'>Rating:</div><div class='score'><span class='score-value'>$score</span><div class='score-legend'>Range: -5 to 5</div></div></div>";
				echo "</div>";
			}
		echo "</div>";
		if($review_summary){
			echo "<div class='review-summary'>";
				echo $review_summary;
			echo "</div>";
		}
		if(have_rows('pro_list') || have_rows('con_list') ){
			echo "<div class='pro-con-lists'>";
				if(have_rows('pro_list')){
					echo "<div class='pro-list'>";
						echo "<h3>Pros</h3>";
						echo "<ul>";
						while( have_rows('pro_list') ) : the_row();
							$sub_value = get_sub_field('pro_text');
							echo "<li>$sub_value</li>";
						endwhile;
						echo "</ul>";
					echo "</div>";
				}
				if(have_rows('con_list')){
					echo "<div class='con-list'>";
						echo "<h3>Cons</h3>";
						echo "<ul>";
						while( have_rows('con_list') ) : the_row();
							$sub_value = get_sub_field('con_text');
							echo "<li>$sub_value</li>";
						endwhile;
						echo "</ul>";
					echo "</div>";
				}
			echo "</div>";
		}
		// add final button
		if( ($link || $affiliate_link) && $score > 0 ){
			$button_link = $affiliate_link ? $affiliate_link : $link;

			?><a class='review-button' href='<?php echo $button_link; ?>' target='_blank'><button class='' type='button'>Get <?php echo $item_name; ?></button></a><?php
		}
	echo "</div>";
	// json
	// <?php echo $adssdf; 
	/*
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "<?php echo $item_name; ?>",
  "review": {
    "@type": "Review",
    "reviewRating": {
      "@type": "Rating",
	  "datePublished": "<?php echo $pub_dateISO; ?>",
      "ratingValue": "<?php echo $score; ?>"
    },
    "author": {
      "@type": "Person",
      "name": "Josh Robbs"
    },
    "reviewBody": "<?php echo $review_summary; ?>"
  }
}
</script>
*/

    //return output
    wp_reset_postdata();
    $thisOutput = ob_get_clean();
    return $thisOutput;
}
    
add_shortcode( 'jwr-review-footer' , 'jwr_review_footer_fn' );