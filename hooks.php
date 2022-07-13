<?php
/**
 * Register admin scripts and styles
 *
 * @throws error
 * @author Silverwebbuzz <www.silverwebbuzz.com>
 * @return 
 */
if(!function_exists('wwn_load_admin_scripts')){
    function wwn_load_admin_scripts(){ 
        wp_register_script('wwn-admin-script',plugin_dir_url( __FILE__ ).'admin/js/functions.js', array('jquery'), '1.0.0', true);
        wp_register_style('wwn-admin-style', plugin_dir_url( __FILE__ ).'admin/css/admin-style.css');
        wp_register_style('wwn-loader-style', plugin_dir_url( __FILE__ ).'admin/css/loader.css');
        wp_register_style('wwn-responsive-style', plugin_dir_url( __FILE__ ).'admin/css/responsive.css');
        wp_enqueue_script('wwn-admin-script'); 
        wp_enqueue_style('wwn-admin-style');
        wp_enqueue_style('wwn-loader-style');
        wp_enqueue_style('wwn-responsive-style');

        $script_params = array('gif_url' => plugin_dir_url( __FILE__ ).'/admin/img/loader.gif',);
        wp_localize_script( 'wwn-admin-script', 'ajax_obj', $script_params ); 
    }
    add_action( 'admin_enqueue_scripts', 'wwn_load_admin_scripts' );
}


/**
 * Send Whatsapp message to every new order to the registered number
 *
 * @throws error
 * @author Silverwebbuzz <www.silverwebbuzz.com>
 * @return 
 */
if(!function_exists('wwn_send_every_new_order')){
    function wwn_send_every_new_order($order_id){
        $wwn_obj       = new WWN_Api_Settings();
        $template_name = get_option('order_template_data')['name'];
        $wwn_obj->send_message($order_id, $template_name);
    }
    add_action('woocommerce_new_order','wwn_send_every_new_order');
}

/**
 * Send Whatsapp message while changing the order status
 *
 * @throws error
 * @author Silverwebbuzz <www.silverwebbuzz.com>
 * @return 
 */
// if(!function_exists('wwn_order_tracking_update')){
//     function wwn_order_tracking_update( $order_id, $old_status, $new_status, $order ){
//         $customer_name      = get_post_meta($order_id,'_billing_first_name',true).' '.get_post_meta($order_id,'_billing_last_name',true);
//         $billing_country    = get_post_meta($order_id,'_billing_country',true);
//         $order_mobile       = get_post_meta($order_id,'_billing_phone',true);
//         $calling_code       = WC()->countries->get_country_calling_code($billing_country);
//         $country_code       = str_replace('+', '', $calling_code);
//         $wwn_obj            = new WWN_Api_Settings();
//         $wwn_status_param   =   [   'order_id'       => $order_id,
//                                     'current_status' => $new_status,
//                                     'customer_name'  => $customer_name,
//                                     'customer_mobile'=> $country_code.$order_mobile,
//                                 ];
//        $wwn_obj->send_message_by_changing_status($wwn_status_param);
//     }
//     add_action('woocommerce_order_status_changed', 'wwn_order_tracking_update', 20, 4 );
// }

