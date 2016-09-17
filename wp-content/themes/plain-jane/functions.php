<?php

// Content width
if ( ! isset( $content_width ) )
	$content_width = 828;
	
function plainjane_widgets_init() {

register_sidebar( array(

'name'=>'Primary Sidebar',

'id'   => 'primary_sidebar',

'description'   => 'Default Left Side Bar',

'before_widget' => '<div class="well">',

'after_widget'  => '</div>',

'before_title'  => '<h4>',

'after_title'   => '</h4>'

));
register_sidebar( array(

'name'=>'Footer Area1',

'id'   => 'footer_area1',

'description'   => 'Footer widget1',

'before_widget' => '<div class="well">',

'after_widget'  => '</div>',

'before_title'  => '<h4>',

'after_title'   => '</h4>'

));
register_sidebar( array(

'name'=>'Footer Area2',

'id'   => 'footer_area2',

'description'   => 'Footer widget2',

'before_widget' => '<div class="well">',

'after_widget'  => '</div>',

'before_title'  => '<h4>',

'after_title'   => '</h4>'

));
register_sidebar( array(

'name'=>'Footer Area3',

'id'   => 'footer_area3',

'description'   => 'Footer widget3',

'before_widget' => '<div class="well">',

'after_widget'  => '</div>',

'before_title'  => '<h4>',

'after_title'   => '</h4>'

));
register_sidebar( array(

'name'=>'Footer Area Home 1',

'id'   => 'footer_area_home1',

'description'   => 'Home Footer widget1',

'before_widget' => '<div class="well">',

'after_widget'  => '</div>',

'before_title'  => '<h4>',

'after_title'   => '</h4>'

));
register_sidebar( array(

'name'=>'Footer Area Home 2',

'id'   => 'footer_area_home2',

'description'   => 'Home Footer widget2',

'before_widget' => '<div class="well">',

'after_widget'  => '</div>',

'before_title'  => '<h4>',

'after_title'   => '</h4>'

));
}
add_action( 'widgets_init', 'plainjane_widgets_init' );

/**
* Sets up theme defaults and registers support for various WordPress features.
*
* Declare textdomain for this child theme.
* Translations can be filed in the /languages/ directory.
*/
function plainjane_setup() {
load_child_theme_textdomain( 'plainjane', get_stylesheet_directory() . '/languages' );

// custom header
$args = array(
'flex-width'    => true,
'width'         => 1170,
'flex-height'    => true,
);
add_theme_support( 'custom-header', $args );


// add featured image sizes
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 150, 150 ); // default Post Thumbnail dimensions   
}
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( '4-column-gallery-thumb', 240, 108, true ); //(cropped)
}

// Custom background image
add_theme_support( 'custom-background' );

// Register Custom Navigation Walker
require_once('wp_bootstrap_navwalker.php');

// This theme uses wp_nav_menu() in 3 locations.
register_nav_menus( array(
'top-right' => __( 'Top Right Navigation', 'plainjane' ),
'primary' => __( 'Primary Navigation', 'plainjane' ),
'vertical' => __( 'Vertical Navigation', 'plainjane' )
) );
}
add_action( 'after_setup_theme', 'plainjane_setup' );

// Unregister some of the Default Theme Sidebars
function plainjane_remove_some_widgets(){
unregister_sidebar( 'sidebar-1' );
unregister_sidebar( 'sidebar-2' );
}
add_action( 'widgets_init', 'plainjane_remove_some_widgets', 11 );

// list subpages
if(!function_exists('plainjane_get_post_top_ancestor_id')){
/**
* Gets the id of the topmost ancestor of the current page. Returns the current
* page's id if there is no parent.
*
* @uses object $post
* @return int
*/
function plainjane_get_post_top_ancestor_id(){
global $post;

if($post->post_parent){
$ancestors = array_reverse(get_post_ancestors($post->ID));
return $ancestors[0];
}

return $post->ID;
}}

// Stylesheets
function plainjane_styles() {

// Register stylesheets
wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array(), '3.1.1', 'all' );
wp_enqueue_style( 'plainjane-main', get_stylesheet_directory_uri() . '/style.css', array(), '1.5', 'all' );
}
add_action( 'wp_enqueue_scripts', 'plainjane_styles' );

// Loads Bootstrap JavaScript file.
function plainjane_scripts_styles() {

wp_enqueue_script( 'plainjane-bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '3.1.1', true );
if ( preg_match( '/MSIE [6-8]/', $_SERVER['HTTP_USER_AGENT'] ) ) {
wp_enqueue_script( 'plainjane-html5shiv', get_stylesheet_directory_uri() . '/js/html5shiv.js', array( 'jquery' ), '3.1.1', true );
wp_enqueue_script( 'plainjane-respond', get_stylesheet_directory_uri() . '/js/respond.min.js', array( 'jquery' ), '3.1.1', true );
}
}
add_action( 'wp_enqueue_scripts', 'plainjane_scripts_styles' );

?>