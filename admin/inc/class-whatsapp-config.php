<?php
class WWN_Config {

    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_wwn_integration', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_wwn_integration', __CLASS__ . '::update_settings' );
    }
    
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['wwn_integration'] = __( 'WhatsApp Integration', 'woocommerce-settings-tab-wwn' );
        return $settings_tabs;
    }

    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }

    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }
    public static function get_settings() {
        ob_start();
        $temp_data = get_option('order_template_data');
        $temp_name = !empty($temp_data['name']) ? $temp_data['name'] : '';
        $temp_head = !empty($temp_data['components'][0]['text']) ? $temp_data['components'][0]['text'] : '';
        $temp_body = !empty($temp_data['components'][1]['text']) ? $temp_data['components'][1]['text'] : '';
        $temp_foot = !empty($temp_data['components'][2]['text']) ? $temp_data['components'][2]['text'] : '';

        if(!empty($temp_name)){ $approval = 'disabled';  }
        $get_obj = new WWN_Api_Settings();
        $status  = $get_obj->get_approved_templates($temp_name);
        if($status === 'APPROVED'){
            $status_src = plugin_dir_url( __FILE__ ).'/img/green.png';
        } elseif($status === 'PENDING'){
            $status_src = plugin_dir_url( __FILE__ ).'/img/orange.png';
        } elseif($status === 'REJECTED'){
            $status_src = plugin_dir_url( __FILE__ ).'/img/red.png';
        } 

        $html = '';
            $settings = array(
            'section_title' => array(
                'name'          => __( 'WooCommerce WhatsApp Notifications', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'title',
                'id'            => 'wc_setting_title_main',
                'desc'          => 'Allows WooCommerce to send Whatsapp notifications on each order status change. 
                It can also notify the owner when a new order is received.
                <span class="g_variable">Dynamic Variables: {{Customer Name}}, {{Order Number}}, {{Order URL}}, {{Review URL}}</span>
                '
            ),
            'api_token'         => array(
                'name'          => __( 'User Access Token: ', 'woocommerce-settings-tab-wwn' ),
                'placeholder'   => 'User Access Token',
                'id'            => 'wc_setting_api_token',
                'type'          => 'text',
                'desc_tip'      => true,
                'desc'          => 'Get WhatsApp Authantication token: <b style="font-size:10px"><br><a href="https://developers.facebook.com/" target="_blank">WhasApp</a></b>'
            ),
            'phone_number_id'         => array(
                'name'          => __( 'Phone number ID: ', 'woocommerce-settings-tab-wwn' ),
                'placeholder'   => 'Phone Number ID',
                'type'          => 'text',
                'desc_tip'      => true,
                'desc'          => 'Enter Phone number ID',
                'id'            => 'wc_setting_phone_number_id'
            ),
            'business_id'         => array(
                'name'          => __( 'Business-ID: ', 'woocommerce-settings-tab-wwn' ),
                'placeholder'   => 'Business-ID (Optional)',
                'type'          => 'text',
                'desc_tip'      => true,
                'desc'          => 'Enter WhatsApp Business Account ID',
                'id'            => 'wc_setting_business_id'
            ),
            'version'         => array(
                'name'          => __( 'Version: ', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'select',
                'id'            => 'wc_setting_version',
                'desc_tip'      => true,
                'desc'          => 'Select API Version',
                'options'       => array(
                  'v13.0' => __( 'v13.0' ),
                  'v14.0' => __('v14.0')
              )
            ),
/*            'thankyou_msg'   => array(
                'name'          => __( 'Welcome Template: ', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'textarea',
                'placeholder'   => 'Set thank you message when customer purchase any order.',
                'class'         => 'template_field',
                'id'            => 'wc_setting_thank_template'
            ),
            'section_end'   => array(
               'type'         => 'sectionend',
               'id'           => 'WC_Settings_Tab_WWN_section_end'
           ),
            'status_section' => array(
                'name'          => __( 'Order Status Templates', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'title',
                'id'            => 'WC_Settings_Tab_WWN_status_section'
            ),
            'pending_payment'   => array(
                'name'          => __( 'Pending Payment Template: ', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'textarea',
                'placeholder'   => 'Set pending payment message when admin can select the Pending payment status.',
                'class'         => 'template_field',
                'id'            => 'wc_setting_pending_payment'
            ),
            'order_processing'   => array(
                'name'          => __( 'Order Processing Template: ', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'textarea',
                'placeholder'   => 'Set pending payment message when admin can select the Processing status.',
                'class'         => 'template_field',
                'id'            => 'wc_setting_order_processing'
            ),
            'on_hold'   => array(
                'name'          => __( 'Order on hold Template: ', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'textarea',
                'placeholder'   => 'Set pending payment message when admin can select the On-Hold status.',
                'class'         => 'template_field',
                'id'            => 'wc_setting_on_hold'
            ),
            'completed'   => array(
                'name'          => __( 'Order Completed Template: ', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'textarea',
                'placeholder'   => 'Set pending payment message when admin can select the Completed status.',
                'class'         => 'template_field',
                'id'            => 'wc_setting_completed'
            ),
            'cancelled'   => array(
                'name'          => __( 'Order Cancelled Template: ', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'textarea',
                'placeholder'   => 'Set pending payment message when admin can select the Cancelled status.',
                'class'         => 'template_field',
                'id'            => 'wc_setting_cancelled'
            ),
            'refund'   => array(
                'name'          => __( 'Order Refund Template: ', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'textarea',
                'placeholder'   => 'Set pending payment message when admin can select the faild status.',
                'class'         => 'template_field',
                'id'            => 'wc_setting_refund'
            ),
            'faild'   => array(
                'name'          => __( 'Order Faild Template: ', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'textarea',
                'placeholder'   => 'Set pending payment message when admin can select the faild status.',
                'class'         => 'template_field',
                'id'            => 'wc_setting_faild'
            ),*/
            'status_section_end'   => array(
               'type'     => 'sectionend',
               'id'       => 'wc_setting_section_end'
           )
        );
        
        $html .="<div class='wwn_message_wrapper'><table>";
            if($status){
                $html .="<tr>
                            <th>Status</th>
                                <td class='status'><img src=".$status_src." height='10' width='10'>
                                    <span>
                                        <p>".$status."<p>
                                        <a href='#' id='remove_template' data-name=".$temp_name."><span class='dashicons dashicons-dismiss'></span></a>
                                    <span>
                                </td>
                            </tr>";
                }
                $html .="<tr>
                            <th colspan='2'>Order Message <br>
                            <span class='g_variable'>This message only send while new order generated</span>
                            </th>
                        </tr>
                        <tr>
                            <th>Template Title</th>
                            <td><input type='text' value='".$temp_name."' name='txt_temp_title' id='txt_temp_title' placeholder='Template Title' ".$approval."/>
                        </tr>
                        <tr>
                            <th>Template Heading</th>
                            <td><input type='text' value='".$temp_head."' name='txt_temp_head' id='txt_temp_head' placeholder='Template Title' ".$approval."/></td>
                        </tr>
                        <tr>
                            <th>Template Body</th>
                            <td><textarea class='txt_temp_body' name='txt_temp_body' id='txt_temp_body' placeholder='Template Body' ".$approval." >".$temp_body."</textarea></td>
                        </tr>
                        <tr>
                            <th>Template Footer</th>
                            <td><input type='text' name='txt_temp_foot' value='".$temp_foot."' id='txt_temp_foot' placeholder='Template Footer' ".$approval." /></td>
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
