<?php
if ( ! defined( 'ABSPATH' ) ) exit;


// Reviews, Tutorials, Code Snippets

 // Code snippet
/**
 * Creates custom post type
 * 
 * They're in separate functions because of copy/paste 
 * 
 * @return void
 * 
 * @author  Josh Robbs <josh@joshrobbs.com>
 */
//# 1. register snippet
 function jwr_register_snippet_type() {

    $labels = array(
        'name'                  => 'Code Snippets',
        'singular_name'         => 'Code Snippet',
        'menu_name'             => 'Code Snippets',
        'name_admin_bar'        => 'Code Snippets',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Code Snippet',
        'all_items'             => 'All Code Snippets',
        'search_items'          => 'Search Code Snippets',
    );
    $args = array(
        'labels'                 => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'               => array( 'slug' => 'code-snippet', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'         => 4,
        'supports'              => array( 'title', 'editor', 'author', 'excerpt' ),
        'show_in_nav_menus'     => true,
        'menu_icon'             => 'dashicons-desktop',
    );

    register_post_type( 'code-snippet', $args );

}
add_action('init', 'jwr_register_snippet_type');

//# 2. register tutorials
function jwr_register_tutorial_type() {

    $labels = array(
        'name'                  => 'Tutorials',
        'singular_name'         => 'Tutorial',
        'menu_name'             => 'Tutorials',
        'name_admin_bar'        => 'Tutorials',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Tutorial',
        'all_items'             => 'All Tutorials',
        'search_items'          => 'Search Tutorials',
    );
    $args = array(
        'labels'                 => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'               => array( 'slug' => 'tutorial' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'         => 6,
        'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
        'show_in_nav_menus'     => true,
        'menu_icon'             => 'dashicons-welcome-learn-more',
    );

    register_post_type( 'tutorial', $args );

}
add_action('init', 'jwr_register_tutorial_type');

//# 3. register reviews
 function jwr_register_reviews_type() {

        $labels = array(
            'name'                  => 'Reviews',
            'singular_name'         => 'Review',
            'menu_name'             => 'Reviews',
            'name_admin_bar'        => 'Reviews',
            'add_new'               => 'Add New',
            'add_new_item'          => 'Add New Review',
            'all_items'             => 'All Reviews',
            'search_items'          => 'Search Reviews',
        );
        $args = array(
            'labels'                 => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
           // 'rewrite'               => array( 'slug' => 'review' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'         => 7,
            'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
            'show_in_nav_menus'     => true,
            'menu_icon'             => 'dashicons-star-half',
        );

        register_post_type( 'review', $args );

}
add_action('init', 'jwr_register_reviews_type');