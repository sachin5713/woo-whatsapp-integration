<?php
class WWN_Config {

    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_wwn_integration', __CLASS__ . '::settings_tab' );
        // add_action( 'woocommerce_update_options_wwn_integration', __CLASS__ . '::update_settings' );
    }
    
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['wwn_integration'] = __( 'WhatsApp Integration', 'woocommerce-settings-tab-wwn' );
        return $settings_tabs;
    }

    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }

    public static function update_settings() {
        // woocommerce_update_options( self::get_settings() );
    }
    public static function get_settings() {
        ob_start();
        $html         =  '';
        $settings     =  array('section_title' => array('type'=> 'title','id'=>'wc_setting_title_main'));
        $get_config   =  get_option('wwn_config_data');
        $temp_data    =  get_option('order_template_data');
        $temp_name    =  !empty($temp_data['name']) ? $temp_data['name'] : '';
        $temp_head    =  !empty($temp_data['components'][0]['text']) ? $temp_data['components'][0]['text'] : '';
        $temp_body    =  !empty($temp_data['components'][1]['text']) ? $temp_data['components'][1]['text'] : '';
        $temp_foot    =  !empty($temp_data['components'][2]['text']) ? $temp_data['components'][2]['text'] : '';
        $get_obj      =  new WWN_Api_Settings();
        $status       =  $get_obj->get_approved_templates($temp_name);

        if(!empty($temp_name)){ $approval = 'disabled';  }
        if($_GET['tab'] == 'wwn_integration'){ $html .='<style>.woocommerce-save-button{display:none !important;}</style>'; }

        if($status === 'APPROVED'){
            $status_src = plugin_dir_url( __FILE__ ).'/img/green.png';
        } elseif($status === 'PENDING'){
            $status_src = plugin_dir_url( __FILE__ ).'/img/orange.png';
        } elseif($status === 'REJECTED'){
            $status_src = plugin_dir_url( __FILE__ ).'/img/red.png';
        }
        $html .="<div class='wwn_configuration_main'>
                <div class='wwn_config_wrap'>
                <table>
                    <tr>
                        <th>User Access Token:</th>
                        <td><input type='text' value='".$get_config['token']."' name='wc_setting_api_token' id='wc_setting_api_token' placeholder='User Access Token'/>
                            <br><span>Get WhatsApp Authantication token: <b><a href='https://developers.facebook.com/' target='_blank'>WhatsApp</a></b></span>
                        </td>
                        <td>
                            <select name='wc_setting_version' id='wc_setting_version'>
                                <option value='v13.0' selected>v13.0</option>
                                <option value='v14.0'>v14.0</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Phone number ID:</th>
                        <td><input type='text' value='".$get_config['phone_id']."' name='wc_setting_phone_number_id' id='wc_setting_phone_number_id' placeholder='Phone number ID'/></td>
                    </tr>
                    <tr>
                        <th>Business-ID:</th>
                        <td><input type='text' value='".$get_config['business_id']."' name='wc_setting_business_id' id='wc_setting_business_id' placeholder='Business-ID (Optional)'/></td>
                    </tr>
                    <tr>
                        <td colspan='2'><button class='button-primary' id='btn_save_settings'>Save Settings</button></td>
                    </tr>
                </table>
                </div>
                <div class='wwn_first_template'>
                <table>";
                $html .= "<tr>
                            <th colspan='2'>Order Message: <br>
                            <span class='g_variable'>This message only send while new order generated</span>
                            </th>
                        </tr>";
                if($status){
                $html .="<tr><th>Status</th>
                            <td class='status'><img src=".$status_src." height='10' width='10'>
                                <span><p>".$status."<p>
                                <a href='#' id='remove_template' data-name=".$temp_name." title='Delete Template'><span class='dashicons dashicons-dismiss'></span></a>
                                <span>
                            </td>
                        </tr>";
                }
                $html .="<tr>
                            <th>Template Title</th>
                            <td><input type='text' value='".$temp_name."' name='txt_temp_title' id='txt_temp_title' placeholder='Template Title' ".$approval."/>
                        </tr>
                        <tr>
                            <th>Template Heading:</th>
                            <td><input type='text' value='".$temp_head."' name='txt_temp_head' id='txt_temp_head' placeholder='Template Title' ".$approval."/></td>
                        </tr>
                        <tr>
                            <th>Template Body: </th>
                            <td><textarea class='txt_temp_body' name='txt_temp_body' id='txt_temp_body' placeholder='Template Body' ".$approval." >".$temp_body."</textarea></td>
                        </tr>
                        <tr>
                            <th>Template Footer: </th>
                            <td><input type='text' name='txt_temp_foot' value='".$temp_foot."' id='txt_temp_foot' placeholder='Template Footer (Optional)' ".$approval." /></td>
                        </tr>
                        <tr>
                            <td colspan='2'><button class='button-primary' id='btn_submit' ".$approval.">Submit</button></td>
                        </tr>
                    </table>
                </div>";
        echo $html;
        return apply_filters( 'wwn_configuration', $settings );
        ob_get_clean();
    }
}

WWN_Config::init();

function wwn_settings_page($links) {
    $links[] = '<a href="'.admin_url( 'admin.php' ).'?page=wc-settings&tab=wwn_integration'.'">' .esc_html('Settings', 'settings_tab_wwn'). '</a>';
    return $links;
}
