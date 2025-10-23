<?php

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME VERSION
-----------------------------------------------------*/
if ( site_url() === 'http://localhost:8080/development' ) {
    define( 'AROLAX_VERSION', time() );
} else {
    define( 'AROLAX_VERSION', 2.0 );
    
}

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME ASSETS URL
-----------------------------------------------------*/
define( 'AROLAX_THEME_URI', get_template_directory_uri() );
define( 'AROLAX_ASSETS', AROLAX_THEME_URI . '/assets/' );
define( 'AROLAX_IMG', AROLAX_THEME_URI . '/assets/imgs' );
define( 'AROLAX_CSS', AROLAX_THEME_URI . '/assets/css' );
define( 'AROLAX_JS', AROLAX_THEME_URI . '/assets/js' );

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME ASSETS DIRECTORY PATH
-----------------------------------------------------*/
define( 'AROLAX_THEME_DIR', get_template_directory() );
define( 'AROLAX_IMG_DIR', AROLAX_THEME_DIR . '/assets/imgs' );
define( 'AROLAX_CSS_DIR', AROLAX_THEME_DIR . '/assets/css' );
define( 'AROLAX_JS_DIR', AROLAX_THEME_DIR . '/assets/js' );



/*----------------------------------------------------
LOAD Classes
-----------------------------------------------------*/
if ( file_exists( dirname( __FILE__ ) . '/app/loader.php' ) ):
    require_once dirname( __FILE__ ) . '/app/loader.php';    
endif;
/*----------------------------------------------------
SET UP THE CONTENT WIDTH VALUE BASED ON THE THEME'S DESIGN
-----------------------------------------------------*/
if ( !isset( $content_width ) ) {
    $content_width = 800;
}

add_filter( 'use_block_editor_for_post', '__return_false' );

// Disable Gutenberg for widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );


//Woocommerce Supports
function arolex_add_woocommerce_support() {
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 350,
		'single_image_width'    => 350,
		'product_grid'          => array(
			'default_rows'    => 3,
			'min_rows'        => 2,
			'max_rows'        => 8,
			'default_columns' => 4,
			'min_columns'     => 2,
			'max_columns'     => 5,
		),
	) );

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );


}

add_action( 'after_setup_theme', 'arolex_add_woocommerce_support' );




