<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_separate', trailingslashit( get_stylesheet_directory_uri() ) . 'ctc-style.css', array( 'storefront-gutenberg-blocks','storefront-style','storefront-style','storefront-icons','storefront-woocommerce-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 30 );

// END ENQUEUE PARENT ACTION

define('IAVC_MODS', get_stylesheet_directory() . '/modules/' );

// check for  learndash then add hooks - just check for  any learndash class 
if ( class_exists( 'LearnDash_Theme_Register' ) && file_exists( IAVC_MODS . 'learndash/Iav_Elementor_Wrappers.php' ) ) :
    require_once IAVC_MODS . 'learndash/Iav_Elementor_Wrappers.php';
endif;

if ( file_exists( IAVC_MODS . 'shortcodes/Iav_Shortcodes.php' ) ) :
    require_once IAVC_MODS . 'shortcodes/Iav_Shortcodes.php';
endif;

// register custom post type for' Research Posts'
if ( file_exists( IAVC_MODS . 'post_types/research_post_type.php' ) ) :
    require_once  IAVC_MODS . 'post_types/research_post_type.php';
endif;

// dirty trick to disable rewrite if on search shortcode
// https://wordpress.stackexchange.com/questions/66273/disable-wordpress-pagination-url-rewrite-for-specific-page
/* function wpa66273_disable_canonical_redirect( $query ) {
if( $_REQUEST["paged"] && $_REQUEST["keyword"] && $_REQUEST["category"] )
        remove_filter( 'template_redirect', 'redirect_canonical' ); 
}
add_action( 'parse_query', 'wpa66273_disable_canonical_redirect' ); */