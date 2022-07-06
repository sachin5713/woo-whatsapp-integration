<?php
/** 
* Plugin Name: WooCommerce Whatsapp Notifications
* Version: 1.0.0
* Plugin URI: #
* Description: Sends Whatsapp notifications to your clients for order status changes. You can also receive an Whatsapp message when a new order is received.
* Author URI: https://www.silverwebbuzz.com
* Author: Silverwebbuzz
* Text Domain: woo-whatsapp-integration
**/

if (!defined('ABSPATH')) { die;}

/**
 * Active plugin
 *
 * @throws error
 * @author Silverwebbuzz <www.silverwebbuzz.com>
 * @return 
 */
if( !function_exists('wwn_activation') ) {
    register_activation_hook (__FILE__, 'wwn_activation');
    function wwn_activation() {   
        require plugin_dir_path( __FILE__ ).'includes/class-wwn-api-settings.php';
        require plugin_dir_path( __FILE__ ).'hooks.php';
        require plugin_dir_path( __FILE__ ).'ajax-hooks.php';
        require plugin_dir_path( __FILE__ ).'admin/index.php'; 
    }
    add_action('init', 'wwn_activation');
}

/**
 * Deactive plugin
 *
 * @throws error
 * @author Silverwebbuzz <www.silverwebbuzz.com>
 * @return 
 */
register_deactivation_hook (__FILE__, 'wwn_deactivate');

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wwn_settings_page');



