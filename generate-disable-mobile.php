<?php
/*
Plugin Name: Generate Disable Mobile
Plugin URI: http://generatepress.com
Description: Disable mobile responsive features in GeneratePress
Version: 0.1
Author: Thomas Usborne
Author URI: http://edge22.com
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! function_exists( 'generate_disable_mobile_scripts' ) ) :
/** 
 * Destroy mobile responsive functionality
 * @since 0.1
 */
add_action( 'wp_enqueue_scripts', 'generate_disable_mobile_scripts', 100 );
function generate_disable_mobile_scripts() {
	
	if ( function_exists( 'generate_get_defaults' ) ) :
		$generate_settings = wp_parse_args( 
			get_option( 'generate_settings', array() ), 
			generate_get_defaults() 
		);
	endif;

	// Remove mobile stylesheets and scripts
	wp_dequeue_style( 'generate-mobile-style' );
	wp_dequeue_style( 'generate-style-grid' );
	wp_dequeue_script( 'generate-navigation' );
	
	// If we're using the legacy mobile menu, dequeue those scripts
	if ( function_exists( 'generate_legacy_mobile_scripts' ) ) :
		wp_dequeue_script( 'generate-navigation-legacy' );
		wp_dequeue_style( 'generate-navigation-legacy' );
	endif;
	
	// If we're using the Secondary Nav add-on, dequeue those scripts
	if ( function_exists( 'generate_secondary_nav_enqueue_scripts' ) ) :
		wp_dequeue_script( 'generate-secondary-nav' );
		wp_dequeue_style( 'generate-secondary-nav-mobile' );
	endif;
	
	// Add in mobile grid (no min-width on line 100)
	wp_enqueue_style( 'generate-style-grid-no-mobile', plugin_dir_url( __FILE__ ) . 'css/unsemantic-grid-no-mobile.css' );
	
	// Add necessary styles to kill mobile resposive features
	$styles = 'body .grid-container {width:' . $generate_settings['container_width'] . 'px;max-width:' . $generate_settings['container_width'] . 'px}.secondary-nav-float-right .secondary-navigation .grid-container, .nav-float-right .main-navigation .grid-container{width:auto;}';
	$styles .= '.menu-toggle {display:none;}';
	wp_add_inline_style( 'generate-style', $styles );
}
endif;

if ( ! function_exists( 'generate_disable_mobile' ) ) :
/** 
 * Remove default viewport
 * @since 0.1
 */
add_action( 'after_setup_theme','generate_disable_mobile' );
function generate_disable_mobile()
{		
	remove_action('wp_head','generate_add_viewport');
}
endif;

if ( ! function_exists( 'generate_disable_mobile_viewport' ) ) :
/** 
 * Add non-mobile viewport to wp_head
 * @since 0.1
 */
add_action('wp_head','generate_disable_mobile_viewport');
function generate_disable_mobile_viewport()
{
	if ( function_exists( 'generate_get_defaults' ) ) :
		$generate_settings = wp_parse_args( 
			get_option( 'generate_settings', array() ), 
			generate_get_defaults() 
		);
	endif;
	
	echo '<meta name="viewport" content="width=' . $generate_settings['container_width'] . 'px">';

}
endif;