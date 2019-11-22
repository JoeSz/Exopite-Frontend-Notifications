<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.joeszalai.org
 * @since             1.0.0
 * @package           Exopite_Frontend_Notifications
 *
 * @wordpress-plugin
 * Plugin Name:       Exopite Frontend Notifications
 * Plugin URI:        https://www.joeszalai.org/exopite/frontend-notifications
 * Description:       Display frontend notifications thru hooks with lobibox (PHP & AJAX).
 * Version:           20191122
 * Author:            Joe Szalai
 * Author URI:        https://www.joeszalai.org
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       exopite-frontend-notifications
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * ToDos:
 * - AJAX to check notifications
 *   - check je 1 - 5 min?
 * - Filter to add nofitications (2 filter)
 *   - first is for "standard" notificaitons, from other plugins or the themes
 *   - second for AJAX
 * - DB? CPT?
 * - Notification to "dismiss", dismiss is by user
 * - Notifications for roles and users
 */

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EXOPITE_FRONTEND_NOTIFICATIONS_VERSION', '20191122' );
define( 'EXOPITE_FRONTEND_NOTIFICATIONS_PLUGIN_NAME', 'exopite-frontend-notifications' );
define( 'EXOPITE_FRONTEND_NOTIFICATIONS_PATH', plugin_dir_path( __FILE__ ) );
define( 'EXOPITE_FRONTEND_NOTIFICATIONS_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Update
 */
if ( is_admin() ) {

    /**
     * A custom update checker for WordPress plugins.
     *
     * Useful if you don't want to host your project
     * in the official WP repository, but would still like it to support automatic updates.
     * Despite the name, it also works with themes.
     *
     * @link http://w-shadow.com/blog/2011/06/02/automatic-updates-for-commercial-themes/
     * @link https://github.com/YahnisElsts/plugin-update-checker
     * @link https://github.com/YahnisElsts/wp-update-server
     */
    if( ! class_exists( 'Puc_v4_Factory' ) ) {

        require_once join( DIRECTORY_SEPARATOR, array( EXOPITE_FRONTEND_NOTIFICATIONS_PATH, 'vendor', 'plugin-update-checker', 'plugin-update-checker.php' ) );

    }

    $MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
        'https://update.joeszalai.org/?action=get_metadata&slug=' . EXOPITE_FRONTEND_NOTIFICATIONS_PLUGIN_NAME, //Metadata URL.
        __FILE__, //Full path to the main plugin file.
        EXOPITE_FRONTEND_NOTIFICATIONS_PLUGIN_NAME //Plugin slug. Usually it's the same as the name of the directory.
    );

    /**
     * Add plugin upgrade notification
     * https://andidittrich.de/2015/05/howto-upgrade-notice-for-wordpress-plugins.html
     *
     * This version add an extra <p> after the notice.
     * I want that to remove later.
     */
    add_action('in_plugin_update_message-' . EXOPITE_FRONTEND_NOTIFICATIONS_PLUGIN_NAME . '/' . EXOPITE_FRONTEND_NOTIFICATIONS_PLUGIN_NAME . '.php', 'show_upgrade_notification_exopite_frontend_notifications', 10, 2);
    function show_upgrade_notification_exopite_frontend_notifications( $current_plugin_metadata, $new_plugin_metadata ){
       // check "upgrade_notice"
       if (isset( $new_plugin_metadata->upgrade_notice ) && strlen( trim( $new_plugin_metadata->upgrade_notice ) )  > 0 ) {

            echo '<span style="background-color:#d54e21;padding:6px;color:#f9f9f9;margin-top:10px;display:block;"><strong>' . esc_html( 'Upgrade Notice', 'exopite-frontend-notifications' ) . ':</strong><br>';
            echo esc_html( $new_plugin_metadata->upgrade_notice );
            echo '</span>';

       }
    }

}
// End Update

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-exopite-frontend-notifications.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_exopite_frontend_notifications() {

	$plugin = new Exopite_Frontend_Notifications();
	$plugin->run();

}
run_exopite_frontend_notifications();
