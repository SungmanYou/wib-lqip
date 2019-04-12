<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/SungmanYou
 * @since             1.0.0
 * @package           Wib_Lqip
 *
 * @wordpress-plugin
 * Plugin Name:       WIB - LQIP
 * Plugin URI:        https://github.com/SungmanYou/wib-lqip
 * Description:       WIB - LQIP (Low Quality Image Placeholder) Support
 * Version:           1.0.0
 * Author:            Sungman You
 * Author URI:        https://github.com/SungmanYou
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wib-lqip
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WIB_LQIP_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wib-lqip-activator.php
 */
function activate_wib_lqip()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wib-lqip-activator.php';
    Wib_Lqip_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wib-lqip-deactivator.php
 */
function deactivate_wib_lqip()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wib-lqip-deactivator.php';
    Wib_Lqip_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wib_lqip');
register_deactivation_hook(__FILE__, 'deactivate_wib_lqip');

require plugin_dir_path(__FILE__) . 'includes/class-wib-lqip.php';

function run_wib_lqip()
{
    new Wib_Lqip();
}
run_wib_lqip();