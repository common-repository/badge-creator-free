<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              
 * @since             1.0.0
 * @package           BadgeCreator
 *
 * @wordpress-plugin
 * Plugin Name:       Badge Creator Free
 * Plugin URI:        http://wordpress.org/badge-creator
 * Description:       This plugin allows to easily create badges for all types of event in all simplicity! 
 * Version:           2.0.0
 * Author:            Yannick ZOHOU
 * Author URI:        https://www.linkedin.com/in/yannickzohou/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       badgeCreator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-badgecreator-activator.php
 */
function activate_badgeCreator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-badgecreator-activator.php';
	BadgeCreator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-badgecreator-deactivator.php
 */
function deactivate_badgeCreator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-badgecreator-deactivator.php';
	BadgeCreator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_badgeCreator' );
register_deactivation_hook( __FILE__, 'deactivate_badgeCreator' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-badgecreator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_badgeCreator() {

	$plugin = new BadgeCreator();
	$plugin->run();

}
run_badgeCreator();
