<?php
/** Plugin Name: WooCommerce Whatsapp Notifications
* Version: 1.0.0
* Plugin URI: #
* Description: Sends Whatsapp notifications to your clients for order status changes. You can also receive an Whatsapp message when a new order is received.
* Author URI: https://www.silverwebbuzz.com
* Author: Silverwebbuzz
* Text Domain: woo-whatsapp-integration
**/

if (!defined('ABSPATH')) { die;}
require plugin_dir_path( __FILE__ ).'includes/class-wwn-api-settings.php';
require plugin_dir_path( __FILE__ ).'includes/class-wwn-api-settings-country.php';
require plugin_dir_path( __FILE__ ).'hooks.php';
require plugin_dir_path( __FILE__ ).'admin/index.php';

register_activation_hook(__FILE__, 'plugin_activate');
register_deactivation_hook(__FILE__,'plugin_deactivate'); 



