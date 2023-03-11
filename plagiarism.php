<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://awesomecoder.dev/
 * @since             1.0.0
 * @package           Awesomecoder
 *
 * @wordpress-plugin
 * Plugin Name:       WP Plagiarism
 * Plugin URI:        https://awesomecoder.dev/
 * Description:       This is custom plugin that check plagiarism.
 * Version:           1.0.0
 * Author:            Mohammad Ibrahim
 * Author URI:        https://awesomecoder.dev/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       awesomecoder
 * Domain Path:       /languages
 *                                                              __
 *                                                             | |
 *    __ ___      _____  ___  ___  _ __ ___   ___  ___ ___   __| | ___ _ ____
 *   / _` \ \ /\ / / _ \/ __|/ _ \| '_ ` _ \ / _ \/ __/ _ \ / _` |/ _ \ ' __|
 *  | (_| |\ V  V /  __/\__ \ (_) | | | | | |  __/ (_| (_) | (_| |  __/	 |
 *  \__,_| \_/\_/ \___||___/\___/|_| |_| |_|\___|\___\___/ \__,_|\___|__|
 *
 */


// If this file is called directly, abort.
!defined('WPINC') ? die : include("plugin.php");

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PLAGIARISM_VERSION', '1.0.0');
define('PLAGIARISM_URL', plugin_dir_url(__FILE__));
define('PLAGIARISM_PATH', plugin_dir_path(__FILE__));
define('PLAGIARISM_BASENAME', plugin_basename(__FILE__));

/**
 * The activate and deactivation action of the plugin.
 *
 * @link       https://awesomecoder.dev/
 * @since      1.0.0
 *
 * @package    Awesomecoder
 */

register_activation_hook(__FILE__, [AwesomeCoder\Plugin\Plagiarism\Core\Plugin::class, 'activate']);
register_deactivation_hook(__FILE__, [AwesomeCoder\Plugin\Plagiarism\Core\Plugin::class, 'deactivate']);

/**
 * Load core of the plugin.
 *
 * @link       https://awesomecoder.dev/
 * @since      1.0.0
 *
 * @package    Awesomecoder
 */
new AwesomeCoder\Plugin\Plagiarism\Core\Plugin();
