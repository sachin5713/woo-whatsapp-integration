<?php
if (!function_exists('send_newsletters')) {
    function send_newsletters() {
        $json = [];
        $message_body = $_POST['txt_message'];
        if (!empty($message_body) != '') {
           $orders = wc_get_orders( array('numberposts' => -1) );
            $newsletter_obj     = new WWN_Api_Settings();
            if(!empty($orders)){

                foreach( $orders as $order ){
                    $billing_country = $order->billing_country;
                    $calling_code    = WC()->countries->get_country_calling_code($billing_country);
                    $country_code    = str_replace('+', '', $calling_code);
                    $contact_number  = $country_code.$order->billing_phone;
                    $customer_name   = $order->billing_first_name.' '.$order->billing_last_name;
                    $send_message    = $newsletter_obj->send_newsletter($contact_number,$customer_name,$message_body);
                } 
            }
        } 
            exit;
        // wp_send_json($json);
    }
    add_action('wp_ajax_send_newsletters', 'send_newsletters');
    add_action('wp_ajax_nopriv_send_newsletters', 'send_newsletters');
}

if(!function_exists('wwn_register_template')){
    function wwn_register_template(){
        $json = [];
        $temp_title  = sanitize_text_field($_POST['txt_temp_title']);
        $temp_head   = sanitize_text_field($_POST['txt_temp_head']);
        $temp_body   = sanitize_text_field($_POST['txt_temp_body']);
        $temp_foot   = sanitize_text_field($_POST['txt_temp_foot']);
        $create_temp = [
           "name" => $temp_title,
           "language" => "en", 
           "category" => "MARKETING", 
           "components" => [
                ["type" => "HEADER","format" => "TEXT","text" => $temp_head], 
                ["type" => "BODY", "text"   =>  $temp_body], 
                ["type" => "FOOTER","text"  => $temp_foot] 
            ] 
        ]; 
        $wwn_obj  = new WWN_Api_Settings();
        $status   = $wwn_obj->request_to_register_template($create_temp);
        if($status->error){
            $json['type']    = 'error';
            $json['message'] = $status->error->error_user_msg;
        } else {
            $json['type']    = 'success';
            $json['message'] = 'Your template has been registered with '. $status->id .' this Template ID';
            update_option('order_template_data',$create_temp);
        }
        wp_send_json($json);
        exit;
    }
    add_action('wp_ajax_wwn_register_template', 'wwn_register_template');
    add_action('wp_ajax_nopriv_wwn_register_template', 'wwn_register_template');
}

if(!function_exists('wwn_delete_template')){
    function wwn_delete_template(){
        $json = [];
        $temp_title  = sanitize_text_field($_POST['temp_name']);
        $wwn_obj     = new WWN_Api_Settings();
        $status      = $wwn_obj->request_to_remove_template($temp_title);
        if($status->success == true){
            $json['type'] = 'success';
            $json['message'] = 'Template delete successfully';
            delete_option('order_template_data');
        } else {
            $json['type'] = 'error';
            $json['message'] = $status->error->error_user_msg;
        }
      wp_send_json($json);
      exit;
    }
    add_action('wp_ajax_wwn_delete_template', 'wwn_delete_template');
    add_action('wp_ajax_nopriv_wwn_delete_template', 'wwn_delete_template');
}