<?php
class WWN_Config {

    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_wwn_integration', __CLASS__ . '::settings_tab' );
    }
    
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['wwn_integration'] = __( 'WhatsApp Integration', 'woocommerce-settings-tab-wwn' );
        return $settings_tabs;
    }

    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }

    public static function get_settings() {
        ob_start();
        $html         =  '';
        $settings     =  array('section_title' => array('type'=> 'title','id'=>'wc_setting_title_main'));
        $get_config   =  get_option('wwn_config_data');
        $temp_data    =  get_option('data_order_created');
        $temp_name    =  !empty($temp_data['name']) ? $temp_data['name'] : '';
        $temp_head    =  !empty($temp_data['components'][0]['text']) ? $temp_data['components'][0]['text'] : '';
        $temp_body    =  !empty($temp_data['components'][1]['text']) ? $temp_data['components'][1]['text'] : '';
        $temp_foot    =  !empty($temp_data['components'][2]['text']) ? $temp_data['components'][2]['text'] : '';
        $get_obj      =  new WWN_Api_Settings();
        $status       =  json_decode($get_obj->get_approved_templates($temp_name))->data[0]->status;

        if(!empty($temp_name)){ $approval = 'disabled';  }
        if($_GET['tab'] == 'wwn_integration'){ $html .='<style>.woocommerce-save-button{display:none !important;}</style>'; }
        $class  = 'text-input';
        $versions = ['v13.0','v14.0'];
        $setting  = ['name'=>['wc_setting_api_token','wc_setting_version','wc_setting_phone_number_id','wc_setting_business_id'], 
                     'value'=>[$get_config['token'],$get_config['version'],$get_config['phone_id'],$get_config['business_id']],
                     'label'=>['User Access Token','API Version','Phone number ID','Business-ID']];

        $template_settings = ['name' =>['txt_temp_title','txt_temp_head','txt_temp_body','txt_temp_foot'],
                              'value'=>[$temp_name,$temp_head,$temp_body,$temp_foot],
                              'label'=>['Template Title','Template Heading','Template Body','Template Footer']];

            $html .="<div class='wwn_configuration_main'>";
                $html .= '<h2>WooCommerce WhatsApp Notification</h2>';
                $html .= '<p>Allows WooCommerce to send Whatsapp Notification on their orders.</p>';
                $html .="<div class='wwn_config_wrap'>";
                    $html .="<table class='config_table'>";
                        $html .= "<tr><th colspan='2'><h3>Settings</h3></th></tr>";
                        $count = count($setting['name']);
                        for ($i=0; $i < $count; $i++) { 
                            if($i == 1){
                                $html .="<tr>";
                                $html .="<th>".$setting['label'][$i].":</th>";
                                $html .="<td><select class='".$class."' name=".$setting['name'][$i]." id=".$setting['name'][$i].">";
                                    for($v = 0; $v < count($versions); $v++){
                                        if($setting['value'][$i] === $versions[$v]){
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                       $html .= "<option value=".$versions[$v]." ".$selected.">".$versions[$v]."</option>";
                                    }
                                $html .="</select></td>";
                                $html .="</tr>";
                            } else {
                            $html .="<tr>";
                            $html .="<th>".$setting['label'][$i].":</th>";
                            $html .="<td>
                                        <input type='text' 
                                                class='".$class."'
                                                value='".$setting['value'][$i]."' 
                                                name='".$setting['name'][$i]."' 
                                                id='".$setting['name'][$i]."' 
                                                placeholder='".$setting['label'][$i]."'/></td>";
                            $html .="</tr>"; 
                            }
                        }
                        $html .= "<tr><td colspan='2'><button class='button-primary' id='btn_save_settings'>Save Settings</button></td></tr>";
                    $html .="</table>";
                $html .="</div>";

                $html .="<div class='wwn_first_template'>";
                    $html .="<table class='wwn_first_template'>";
                        $html .= "<tr><th colspan='2'><h3>Create templete for each new order</h3></th></tr>";
                        if($status){
                            $html .="<tr class='status_indicater'><th>Status</th>
                                        <td><div class='status'><span class=".$status."></span><p>".$status."<p></div>
                                            <a href='#' id='remove_template' data-name='".$temp_name."' data-key='data_order_created'>
                                            <span class='dashicons dashicons-trash' title='Delete Template'></span></span></a>
                                            <span>
                                        </td>
                                    </tr>";
                        }
                            for ($t=0; $t < count($template_settings['name']); $t++) { 
                                if($t != 2){
                                    $html .="<tr>
                                                <th class='template_title'>".$template_settings['label'][$t]."</th>
                                                <td><input type='text' 
                                                        class='".$class."'
                                                        value='".$template_settings['value'][$t]."' 
                                                        name='".$template_settings['name'][$t]."' 
                                                        id='".$template_settings['name'][$t]."'
                                                        placeholder='".$template_settings['label'][$t]."' ".$approval."/>
                                                </td>
                                            </tr>";
                                } else {
                                    $html .="<tr>
                                                <th>".$template_settings['label'][$t]."</th>
                                                <td>
                                                    <textarea 
                                                    class='".$class."'
                                                    name='".$template_settings['name'][$t]."'
                                                    id='".$template_settings['name'][$t]."'
                                                    placeholder='".$template_settings['label'][$t]."'
                                                    ".$approval.">".$template_settings['value'][$t]."</textarea> 
                                                </td>
                                            </tr>";
                                }
                            }
                        $html .= "<tr><td colspan='2'><button class='button-primary' id='btn_submit' ".$approval.">Submit</button></td></tr>";
                    $html .="</table>";
                $html .="</div>";
            $html .="</div>";
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
