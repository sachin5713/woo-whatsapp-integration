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
        wp_enqueue_script('wwn-admin-script'); 
        wp_enqueue_style('wwn-admin-style'); 
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
if(!function_exists('wwn_registration_update')){
    function wwn_registration_update($order_id){
        $wwn_obj         = new WWN_Api_Settings();
        $billing_country = get_post_meta($order_id,'_billing_country',true);
        $customer_name   = get_post_meta($order_id,'_billing_first_name',true).' '.get_post_meta($order_id,'_billing_last_name',true);
        $order           = wc_get_order( $order_id );
        $country_obj     = new WWN_Api_Settings_Country($billing_country);
        $order_mobile    = get_post_meta($order_id,'_billing_phone',true);
        $wwn_obj->send_welcome_message($country_obj->country.$order_mobile,$order_id,$customer_name);
    }
    add_action('woocommerce_new_order','wwn_registration_update');
}

/**
 * Send Whatsapp message while changing the order status
 *
 * @throws error
 * @author Silverwebbuzz <www.silverwebbuzz.com>
 * @return 
 */
if(!function_exists('wwn_order_tracking_update')){
    function wwn_order_tracking_update( $order_id, $old_status, $new_status, $order ){
        $customer_name      = get_post_meta($order_id,'_billing_first_name',true).' '.get_post_meta($order_id,'_billing_last_name',true);
        $billing_country    = get_post_meta($order_id,'_billing_country',true);
        $order_mobile       = get_post_meta($order_id,'_billing_phone',true);
        $country_obj        = new WWN_Api_Settings_Country($billing_country);
        $wwn_obj            = new WWN_Api_Settings();
        $wwn_status_param   =   [   'order_id'       => $order_id,
                                    'current_status' => $new_status,
                                    'customer_name'  => $customer_name,
                                    'customer_mobile'=> $country_obj->country.$order_mobile,
                                ];
       $wwn_obj->send_message_by_changing_status($wwn_status_param);
    }
    add_action('woocommerce_order_status_changed', 'wwn_order_tracking_update', 20, 4 );
}
