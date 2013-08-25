<?php
/**
 * Plugin Name: Essential SEO
 * Plugin URI: http://seamlessthemes.com/
 * Description: A very essential, yet powerful, SEO Plugin.
 * Version: 0.1.0
 * Author: James Geiger
 * Author URI: http://seamlessthemes.com
 *
 * @version 0.1.0
 * @author James Geiger <james@seamlessthemes.com>
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2013, James Geiger and Justin Tadlock
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/* Constants
------------------------------------------ */

/* Set plugin version constant. */
define( 'ESSENTIAL_SEO_VERSION', '0.1.0' );

/* Set constant path to the plugin directory. */
define( 'ESSENTIAL_SEO_PATH', trailingslashit( plugin_dir_path(__FILE__) ) );

/* Set the constant path to the plugin directory URI. */
define( 'ESSENTIAL_SEO_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );



/* Plugins Loaded
------------------------------------------ */

/* add to plugins loaded hook */
add_action( 'plugins_loaded', 'essential_seo_plugins_loaded' );


/**
 * Load all plugins functions
 * 
 * @since 0.1.0
 * @return null
 */
function essential_seo_plugins_loaded(){

	/* Load settings and functions */
	require_once( ESSENTIAL_SEO_PATH . 'inc/essential-seo.php' );
	require_once( ESSENTIAL_SEO_PATH . 'inc/meta-box-post-seo.php' );
}