<?php
if(!function_exists('wwn_register_template')){
    function wwn_register_template(){
        $json        = [];
        $create_temp = [];
        $temp_title  = sanitize_text_field($_POST['txt_temp_title']);
        $temp_head   = sanitize_text_field($_POST['txt_temp_head']);
        $temp_body   = sanitize_text_field($_POST['txt_temp_body']);
        $temp_foot   = sanitize_text_field($_POST['txt_temp_foot']);

        if(empty($temp_title)){
            $json['type'] = 'error';
            $json['message'] = 'Please enter template name';
            wp_send_json($json);
            exit;
        }
        if(empty($temp_head)){
            $json['type'] = 'error';
            $json['message'] = 'Please enter template title';
            wp_send_json($json);
            exit;
        }
        if(empty($temp_body)){
            $json['type'] = 'error';
            $json['message'] = 'Please enter template body';
            wp_send_json($json);
            exit;
        }

        $create_temp['name']     = $temp_title; 
        $create_temp['language'] = 'en'; 
        $create_temp['category'] = 'MARKETING';
        $create_temp['components'][] = ["type" => "HEADER","format"=>"TEXT","text"=>$temp_head];
        $create_temp['components'][] = ["type" => "BODY","text"=>$temp_body];

        if(!empty($temp_foot)){
            $create_temp['components'][] = ["type" => "FOOTER","text"  => $temp_foot];
        }

        $wwn_obj  = new WWN_Api_Settings();
        $status   = $wwn_obj->request_to_register_template($create_temp);
        if($status->error){
            $json['type']    = 'error';
            $json['message'] = $status->error->error_user_msg;
        } else {
            $json['type']    = 'success';
            $json['message'] = 'Your template has been registered with '. $status->id .' this Template ID';
            update_option('data_order_created',$create_temp);
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
        $temp_key    = sanitize_text_field($_POST['key_name']);
        $wwn_obj     = new WWN_Api_Settings();
        $status      = $wwn_obj->request_to_remove_template($temp_title);
        if($status->success == true){
            $json['type'] = 'success';
            $json['message'] = 'Template delete successfully';
            delete_option($temp_key);
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

if(!function_exists('wwn_configure_settings')){
    function wwn_configure_settings(){
        $json = [];
        $config = ['token'       => sanitize_text_field($_POST['wc_setting_api_token']),
                   'version'     => sanitize_text_field($_POST['wc_setting_version']),
                   'phone_id'    => sanitize_text_field($_POST['wc_setting_phone_number_id']),
                   'business_id' => sanitize_text_field($_POST['wc_setting_business_id'])];
        update_option('wwn_config_data',array_filter($config));           
        $json['type'] = 'success';
        $json['message'] = 'Your settings are saved';
        wp_send_json($json);
        exit;
    }
    add_action('wp_ajax_wwn_configure_settings', 'wwn_configure_settings');
    add_action('wp_ajax_nopriv_wwn_configure_settings', 'wwn_configure_settings');
}