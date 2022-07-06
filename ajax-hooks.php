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
                 echo '<pre>';
                 print_r($send_message);
                 echo '</pre>';
                } 
            }
        } 
            exit;
        // wp_send_json($json);
    }
    add_action('wp_ajax_send_newsletters', 'send_newsletters');
    add_action('wp_ajax_nopriv_send_newsletters', 'send_newsletters');
}