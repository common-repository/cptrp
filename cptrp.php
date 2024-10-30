<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://persaper.it/custom-post-type-related-post
 * @since             1.0.0
 * @package           CPTRP
 *
 * @wordpress-plugin
 * Plugin Name:       CPTRP - Custom Post Type Related Posts
 * Plugin URI:        https://wordpress.org/plugins/cptrp/
 * Description:       Adding related posts to custom post type.
 * Version:           1.0.0
 * Author:            Davide de Mattia
 * Author URI:        http://persaper.it/davide
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cptrp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cptrp-activator.php
 */
function cptrp_activate_cptrp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cptrp-activator.php';
	cptrp_Activator::cptrp_activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cptrp-deactivator.php
 */
function cptrp_deactivate_cptrp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cptrp-deactivator.php';
	cptrp_Deactivator::cptrp_deactivate();
}

register_activation_hook( __FILE__, 'cptrp_activate_cptrp' );
register_deactivation_hook( __FILE__, 'cptrp_deactivate_cptrp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cptrp.php';

/**
 * Add link to settings page,
 * in the plugin list.
 */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'cptrp_add_action_links' );

function cptrp_add_action_links( $links ) {
	$mylinks = array(
		'<a href="' . admin_url( 'options-general.php?page=cptrp' ) . '">'.esc_html__( 'Impostazioni', 'cptrp' ).'</a>',
	);
	return array_merge( $mylinks, $links );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function cptrp_run_cptrp() {

	$plugin = new cptrp();
	$plugin->cptrp_run();

}
cptrp_run_cptrp();