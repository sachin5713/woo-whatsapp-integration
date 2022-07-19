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

        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
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
        $template_name = get_option('data_order_created')['name'];
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
if(!function_exists('wwn_order_tracking_update')){
    function wwn_order_tracking_update( $order_id, $old_status, $new_status, $order ){
        $temp_hold     = get_option('data_temp_hold')['name'];
        $temp_process  = get_option('data_temp_processing')['name'];
        $temp_pending  = get_option('data_temp_pending')['name'];
        $temp_complete = get_option('data_temp_complete')['name'];
        $temp_refund   = get_option('data_temp_refund')['name'];
        $temp_faild    = get_option('data_temp_faild')['name'];
        $temp_cancel   = get_option('data_temp_cancelled')['name'];
        $template_name = '';
        switch ($new_status) {
          case "on-hold":
            if(!empty($temp_hold)){
                $template_name = $temp_hold;
            }
            break;
          case "pending":
            if(!empty($temp_pending)){
                $template_name = $temp_pending;
            }
            break;
          case "processing":
            if(!empty($temp_process)){
                $template_name = $temp_process;
            }
            break;
          case "completed":
           if(!empty($temp_complete)){
                $template_name = $temp_complete;
            }
            break;
          case "cancelled":
            if(!empty($temp_cancel)){
                $template_name = $temp_cancel;
            }
            break;
          case "refunded":
            if(!empty($temp_refund)){
                $template_name = $temp_refund;
            }
            break;
          case "failed":
            if(!empty($temp_faild)){
                $template_name = $temp_faild;
            }
            break;
          default:
        }
        $wwn_obj = new WWN_Api_Settings();
        $wwn_obj->send_message($order_id, $template_name);
    }
    add_action('woocommerce_order_status_changed', 'wwn_order_tracking_update', 20, 4 );
}

