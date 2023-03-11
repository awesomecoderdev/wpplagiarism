<?php

namespace AwesomeCoder\Plugin\Plagiarism\Core;

use AwesomeCoder\Foundation\Application;

/**
 * Load core of the plugin.
 *
 * @link       https://awesomecoder.dev/
 * @since      1.0.0
 *
 * @package    Awesomecoder
 *
 */

// If this file is called directly, abort.
!defined('WPINC') ? die : include(__DIR__ . "/vendor/autoload.php");

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Awesomecoder
 * @subpackage Awesomecoder/controller
 * @author     Mohammad Ibrahim <awesomecoder.dev@gmail.com>
 *                                                              __
 *                                                             | |
 *    __ ___      _____  ___  ___  _ __ ___   ___  ___ ___   __| | ___ _ ____
 *   / _` \ \ /\ / / _ \/ __|/ _ \| '_ ` _ \ / _ \/ __/ _ \ / _` |/ _ \ ' __|
 *  | (_| |\ V  V /  __/\__ \ (_) | | | | | |  __/ (_| (_) | (_| |  __/	 |
 *  \__,_| \_/\_/ \___||___/\___/|_| |_| |_|\___|\___\___/ \__,_|\___|__|
 *
 */

class Plugin extends Application
{

	/**
	 *
	 * The code that runs during plugin activation.
	 * This action is documented in controller/class-awesomecoder-activator.php
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
	}

	/**
	 *
	 * The code that runs during plugin deactivation.
	 * This action is documented in controller/class-awesomecoder-deactivator.php
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
	}
}
