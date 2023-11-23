<?php

/**
 * Henri WPMUdev
 *
 * @package     HenriWPMUdev
 * @author      Henri Susanto
 * @copyright   2022 Henri Susanto
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 *  Plugin Name:        Henri WPMUdev
 *	Plugin URI:         https://github.com/susantohenri/henri-wpmudev
 *	Description:        Make Magic
 *	Version:            1.0.0
 *	Requires at least:  5.2
 *	Requires PHP:       7.2
 *  Author:             Henri Susanto
 *  Author URI:         https://github.com/susantohenri/
 *  License:            GPL v2 or later
 *  License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 *	Update URI:         https://github.com/susantohenri/henri-wpmudev
 *	Text Domain:        HenriWPMUdev
 *	Domain Path:        /wpmudev
 */

require_once (plugin_dir_path(__FILE__)) . 'henri-wpmudev.class.php';
$henriWPMUdev = new HenriWPMUDev();

register_activation_hook(__FILE__, [$henriWPMUdev, 'createTable']);
register_deactivation_hook(__FILE__, [$henriWPMUdev, 'dropTable']);
add_shortcode($henriWPMUdev::$shortcode, [$henriWPMUdev, 'index']);
