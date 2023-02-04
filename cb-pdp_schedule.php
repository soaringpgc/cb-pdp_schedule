<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://pgcsoaring.com
 * @since             1.0.0
 * @package           CB_PDP_schedule
 *
 * @wordpress-plugin
 * Plugin Name:       Cloud Base -PGC PDP Instruction Scheduling. 
 * Plugin URI:        http://pgcsoaring.com/pdp_instruction_schedule-uri/
 * Description:       The is an extension to plugin Cloud Base. This adds the PDP calendar duty managment and instructing scheduling. If CloudBase is deactivate, this plugin will auto deactivate. 
 * Version:           1.0.0
 * Author:            Philadelphia Glider Council -- Dave Johnson
 * Author URI:        http://pgcsoaring.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cb-pdp_schedule
 * Domain Path:       /languages
 */

namespace CB_PDP_schedule;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// if dependent plugin is not active - stop here and return. 
// odd way of checking however because this is loaded before cloudbase, we can not 
// check for a cloudbase class as it is not loaded yet. However, it is in the active plugins 
// list attempts to self disable falied. 
// 
if(!in_array('cloudbase/cloud-base.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	deactivate_plugins( plugin_basename( __FILE__ ) );
		return; 
}

/**
 * Define Constants
 */

define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );

define( NS . 'PLUGIN_NAME', 'cb-pdp_schedule' );

define( NS . 'PLUGIN_VERSION', '1.0.0' );

define( NS . 'PLUGIN_NAME_DIR', plugin_dir_path( __FILE__ ) );

define( NS . 'PLUGIN_NAME_URL', plugin_dir_url( __FILE__ ) );

define( NS . 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

define( NS . 'PLUGIN_TEXT_DOMAIN', 'cb-pdp_schedule' );


/**
 * Autoload Classes
 */

require_once( PLUGIN_NAME_DIR . 'inc/libraries/autoloader.php' );

/**
*  connections information for the external (to wordpress) data base for the PDP is stored 
*  in a file external (and several directories above) this plugin. The handels for this
* 	external is created in that file, which must be loaded independly of the plugin. 
*/

require_once( dirname(__DIR__, 4) . '/Connections/PGC.php' );

/**
 * Register Activation and Deactivation Hooks
 * This action is documented in inc/core/class-activator.php
 */

register_activation_hook( __FILE__, array( NS . 'Inc\Core\Activator', 'activate' ) );

/**
 * The code that runs during plugin deactivation.
 * This action is documented inc/core/class-deactivator.php
 */

register_deactivation_hook( __FILE__, array( NS . 'Inc\Core\Deactivator', 'deactivate' ) );


/**
 * Plugin Singleton Container
 *
 * Maintains a single copy of the plugin app object
 *
 * @since    1.0.0
 */
class CB_PDP_schedule {

	/**
	 * The instance of the plugin.
	 *
	 * @since    1.0.0
	 * @var      Init $init Instance of the plugin.
	 */
	private static $init;
	/**
	 * Loads the plugin
	 *
	 * @access    public
	 */
	public static function init() {

		if ( null === self::$init ) {
			self::$init = new Inc\Core\Init();
			self::$init->run();
		}

		return self::$init;
	}

}

/**
 * Begins execution of the plugin
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * Also returns copy of the app object so 3rd party developers
 * can interact with the plugin's hooks contained within.
 **/
function wp_plugin_name_init() {

		return CB_PDP_schedule::init();
		
}

$min_php = '7.4.0';

// Check the minimum required PHP version and run the plugin.
if ( version_compare( PHP_VERSION, $min_php, '>=' ) ) {

	add_action( 'plugins_loaded', 'CB_PDP_schedule\wp_plugin_name_init' );
//		wp_plugin_name_init();
}
