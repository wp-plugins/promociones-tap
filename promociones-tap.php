<?php
/**
 * @package   Promociones_TAP
 * @author    Alain Sanchez <luka.ghost@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.linkedin.com/in/mrbrazzi/
 * @copyright 2014 Alain Sanchez
 *
 * @wordpress-plugin
 * Plugin Name:       Promociones TAP
 * Plugin URI:       http://www.todoapuestas.org
 * Description:       Plugin para publicacion de promociones y/o publicidad.
 * Version:           1.0.0
 * Author:       Alain Sanchez
 * Author URI:       http://www.linkedin.com/in/mrbrazzi/
 * Text Domain:       promociones-tap
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/class-promociones-tap.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'Promociones_TAP', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Promociones_TAP', 'deactivate' ) );

/*
 */
add_action( 'plugins_loaded', array( 'Promociones_TAP', 'get_instance' ) );

if ( !class_exists('Promociones_TAP_Options_Framework') ) {
    define( 'PROMOCIONES_TAP_OPTIONS_FRAMEWORK_DIRECTORY', plugin_dir_url(__FILE__) . 'admin/includes/options/' );
    require_once plugin_dir_path( __FILE__ ) . 'admin/includes/options/class-options-framework.php';
    add_action( 'plugins_loaded', array( 'Promociones_TAP_Options_Framework', 'get_instance' ) );
}

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-promociones-tap-admin.php' );
	add_action( 'plugins_loaded', array( 'Promociones_TAP_Admin', 'get_instance' ) );

    if( !class_exists('Promociones_TAP_Meta_Boxes_Post_Type')){
        require_once plugin_dir_path( __FILE__ ) . 'admin/includes/meta-boxes.php';
        add_action( 'plugins_loaded', array( 'Promociones_TAP_Meta_Boxes_Post_Type', 'get_instance' ) );
    }
}

if( !class_exists('Promocion_Post_Type')){
    require_once plugin_dir_path( __FILE__ ) . 'includes/post-type-promocion.php';
    add_action( 'plugins_loaded', array( 'Promocion_Post_Type', 'get_instance' ) );
}
