<?php

/**
 * Guestaba Hospitality Plugin
 *
 * The Hospitality plugin is tailored to the needs of the hotels and resorts. It facilates the management of information about
 * rooms and meeting spaces, along with their amenities and rates, that you would like to display on your website. 
 *
 * @link              http://guestaba.com
 * @since             1.0.0
 * @package           Hospitality
 *
 * @wordpress-plugin
 * Plugin Name:       Hospitality
 * Plugin URI:        http://guestaba.com
 * Description:       The Hospitality plugin is tailored to the needs of the hotels and resorts. It facilates the management of information about rooms and meeting spaces, along with their amenities and rates, that you would like to display on your website. 
 * Version:           1.0.2
 * Author:            Guestaba Team
 * Author URI:        http://guestaba.com/team
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hospitality
 * Domain Path:       /languages
 */

if (!defined('GUESTABA_HSP_PLUGIN_FILE'))
	define('GUESTABA_HSP_PLUGIN_FILE', plugin_basename(__FILE__) );

if (!defined('GUESTABA_HSP_TEXTDOMAIN'))
	define('GUESTABA_HSP_TEXTDOMAIN', 'hospitality');

if (!defined('GUESTABA_HOSPITALITY_VERSION_KEY'))
    define('GUESTABA_HOSPITALITY_VERSION_KEY', 'guestaba_hospitality_version');

if (!defined('GUESTABA_ROOMS_LISTING_PAGE_NAME')) {
	define('GUESTABA_ROOMS_LISTING_PAGE_NAME', 'rooms-listing');
}

if (!defined('GUESTABA_ROOM_DETAIL_PAGE_NAME')) {
	define('GUESTABA_ROOM_DETAIL_PAGE_NAME', 'room-detail');
}

if (!defined('GUESTABA_ROOM_DETAIL_ID_VAR')) {
	define('GUESTABA_ROOM_DETAIL_ID_VAR', 'hsp-rooms-id');
}

if (!defined('GUESTABA_ROOM_DETAIL_NAME_VAR')) {
	define('GUESTABA_ROOM_DETAIL_NAME_VAR', 'hsp-rooms-name');
}

if (!defined('GUESTABA_HSP_OPTIONS_NAME')) {
	define('GUESTABA_HSP_OPTIONS_NAME', 'guestaba_hsp_settings');
}

if (!defined('GUESTABA_SLIDER_PREFIX')) {
	define('GUESTABA_SLIDER_PREFIX', 'guestaba_slider-');
}

if (!defined('GUESTABA_HSP_SLIDER_WIDTH'))
	define('GUESTABA_HSP_SLIDER_WIDTH', 940);

if (!defined('GUESTABA_HSP_SLIDER_HEIGHT'))
	define('GUESTABA_HSP_SLIDER_HEIGHT', 248);

if (!defined('GUESTABA_HOSPITALITY_VERSION_NUM'))
	define('GUESTABA_HOSPITALITY_VERSION_NUM', '1.0.2');

update_option(GUESTABA_HOSPITALITY_VERSION_KEY, GUESTABA_HOSPITALITY_VERSION_NUM);

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hospitality-activator.php
 */
function activate_hospitality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hospitality-activator.php';
	Hospitality_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hospitality-deactivator.php
 */
function deactivate_hospitality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hospitality-deactivator.php';
	Hospitality_Deactivator::deactivate();
}



register_activation_hook( __FILE__, 'activate_hospitality' );
register_deactivation_hook( __FILE__, 'deactivate_hospitality' );


/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hospitality.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_hospitality() {

	$plugin = new Hospitality();
	$plugin->run();

}
run_hospitality();
