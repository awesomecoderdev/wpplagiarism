<?php

namespace AwesomeCoder\Plugin\Plagiarism\Core;

use AwesomeCoder\Foundation\Application;
use AwesomeCoder\Plugin\Wp\AdminPage;
use AwesomeCoder\Plugin\Wp\Asset;
use AwesomeCoder\Plugin\Wp\Menu;

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

	public function __construct()
	{
		add_action('admin_enqueue_scripts', [$this, "scripts"], 999999);
		add_action('admin_menu', [$this, "admin_menu"], 999999);
	}


	/**
	 *
	 * The code that runs during plugin activation.
	 *
	 * @since    1.0.0
	 */
	public function scripts($page)
	{
		if (in_array($page, ["toplevel_page_plagiarism"])) {
			Asset::script("backend.js");
			Asset::style("backend.css");
			add_action('admin_notices', fn () => pl_resource());
		} else {
			Asset::style("plagiarism.css");
		}
	}


	/**
	 *
	 * The code that runs during plugin activation.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu()
	{
		$icon = "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBzdHlsZT0iZmlsbDojYTdhYWFkIj48cGF0aCBkPSJNOSA5aDZ2Nkg5eiI+PC9wYXRoPjxwYXRoIGQ9Ik0yMCA2YzAtMS4xMDMtLjg5Ny0yLTItMmgtMlYyaC0ydjJoLTRWMkg4djJINmMtMS4xMDMgMC0yIC44OTctMiAydjJIMnYyaDJ2NEgydjJoMnYyYzAgMS4xMDMuODk3IDIgMiAyaDJ2Mmgydi0yaDR2Mmgydi0yaDJjMS4xMDMgMCAyLS44OTcgMi0ydi0yaDJ2LTJoLTJ2LTRoMlY4aC0yVjZ6TTYgMThWNmgxMmwuMDAyIDEySDZ6Ij48L3BhdGg+PC9zdmc+";
		Menu::register(new Menu(new AdminPage(__("Wp Plagiarism", 'wp-plagiarism'), fn () => pl_resource()), 'plagiarism', __("Wp Plagiarism", 'wp-plagiarism'), "manage_options", $icon, 50));
	}

	/**
	 *
	 * The code that runs during plugin activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
	}

	/**
	 *
	 * The code that runs during plugin deactivation.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
	}
}
