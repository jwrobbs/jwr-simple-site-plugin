<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// [] add toc

/**
 * Summary
 * 
 * Adds shortcodes to the site
 * 
 * @author  Josh Robbs <josh@joshrobbs.com> 
 */



 //# 1. SC: link to CPT archives
/**
 * Creates list of post type archives based on $cpt_array
 *
 * @return string
 */
//??? am i even keeping this?
function jwr_home_cpt_links_fn($atts = array(), $content = null){

    ob_start();
    // start output

    $cpt_array = array(
        'post',
        'tutorial',
        'review',
        'code-snippet',
    );

    foreach( $cpt_array as $cpt ){
        // get proper name and url
        $cpt_obj = get_post_type_object($cpt);
        $link = get_post_type_archive_link($cpt);
        
        //output
        $label = $cpt_obj->label;
        if( $label ){
            echo "<a href='$link'>$label</a>";
        }
    }

    //return output
    wp_reset_postdata();
    $thisOutput = ob_get_clean();
    if( $thisOutput ){
        $thisOutput = "<div class='home-pt-link-container'>$thisOutput</div>";
    }
    return $thisOutput;
}
    
add_shortcode( 'jwr_home_cpt_links' , 'jwr_home_cpt_links_fn' );

//# 2. SC: home page blog grid

function home_grid_fn($atts = array(), $content = null){
    // setup  
    $posts_per_page = 12;
    $cpts = array('tutorial','review','code-snippet', 'post');

    $args = array(
        'numberposts'   => 12,
        'post_type'     => $cpts,
        'post_status'   => 'publish',
    );
       
    $posts = get_posts( $args );

    // start output
    ob_start();

    if( $posts ){
        echo "<div class='archive-grid'>";
        foreach($posts as $post){

            $card = create_archive_card($post);
            echo $card;
       
        }
        echo "</div>";

    }else{
        echo "Something is wrong: no posts.";
    }

    // var_dump($posts);

    //return output
    wp_reset_postdata();
    $thisOutput = ob_get_clean();
    return $thisOutput;
}
    
add_shortcode( 'home-grid' , 'home_grid_fn' );

//# 3. SC: general archive

function archive_grid_fn($atts = array(), $content = null){
    // setup  

    // start output
    ob_start();
    global $wp_query;
    // var_dump( $wp_query->posts );
    $posts = $wp_query->posts;

    if( $posts ){
        echo "<div class='archive-grid'>";
        foreach($posts as $post){

            $card = create_archive_card($post);
            echo $card;
       
        }
        
        echo "</div>";

        echo "<hr>";
        echo "<div class='archive-pagination-container'>";
        posts_nav_link();
        echo "</div>";

    }else{
        echo "no posts :(";
    }

    //return output
    wp_reset_postdata();
    $thisOutput = ob_get_clean();
    return $thisOutput;
}


add_shortcode( 'archive-grid' , 'archive_grid_fn' );

//# 4 SC: archive title
function jwr_archive_title_fn($atts = array(), $content = null){ 
    // setup  

    // if is_post_type_archive() archive: pt or drop "archive"?
    //is_home(), Posts 

    // is_archive() && is_tag(), tagged: name
    // is_archive() && is_category(), Filed under:<br> name
    // is_archive() && is_tax(), TAX: name

    if( is_home() ){
        $title = "Posts";
    }elseif( is_post_type_archive() ){
        $title = post_type_archive_title( '', false );
    }elseif( is_archive() && is_tag() ){
        $title = "Tagged: " . single_tag_title( '', false);
    }elseif( is_archive() && is_category() ){
        $title = "Filed under:<br>" . single_cat_title( '', false );
    }elseif( is_archive() && is_tax() ){
        global $wp_query;
        $term_obj = $wp_query->get_queried_object();
        $term = $term_obj->name;
        $tax_obj = get_taxonomy( $term_obj->taxonomy );
        $tax_name = $tax_obj->label;
        $title = "$tax_name: $term";
    }else{
        $title = "This isn't an archive :(";
    }

    $post_type = get_post_type();

    // start output
    ob_start();

    echo "<h1 class='jwr-archive-title'>$title</h1>";
    global $wp_query;
    $this_obj = $wp_query->get_queried_object( get_the_ID() );
    if( $this_obj->taxonomy == 'difficulties' ){
        $desc = $this_obj->description;
        if( isset($desc) ){
            echo "<div class='archive-description'>$desc</div>";
        }
    }
    

    //return output
    wp_reset_postdata();
    $thisOutput = ob_get_clean();
    return $thisOutput;
}

add_shortcode( 'jwr-archive-title' , 'jwr_archive_title_fn' );