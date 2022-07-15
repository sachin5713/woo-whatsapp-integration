<?php
class WWN_Templates {
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_wwn_templates', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_wwn_templates', __CLASS__ . '::update_settings' );
        add_action( 'woocommerce_settings_customer_list',__CLASS__ . '::templates_structure');
    }

    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['wwn_templates'] = __( 'Templates (Pro Features)');
        return $settings_tabs;
    }

    public static function settings_tab() {woocommerce_admin_fields( self::templates_structure() );}
    public static function update_settings() {woocommerce_update_options( self::templates_structure() );}

    public function templates_structure() {
        ob_start();
        $settings     = ['section_title' => ['type'=> 'title','id'=>'wc_setting_title_main']];
        $get_obj      =  new WWN_Api_Settings();
        $status       =  json_decode($get_obj->get_approved_templates($temp_name))->data[0]->status;
        $html   = '';
        $class  = 'text-input';
        if($_GET['tab'] == 'wwn_templates'){ $html .='<style>.woocommerce-save-button{display:none !important;}</style>'; }
            $struct =  ['temp_hold'     => ['title' => 'Template for Order Hold.',
                                            'sub_title' => 'This template works when order is on hold'],

                        'temp_processing'=> ['title' => 'Template for Order Processing.',
                                                'sub_title' => 'This template works when order is on processing'],

                        'temp_cancelled'=> ['title' => 'Template for cancelled Order.',
                                              'sub_title' => 'This template works when order is cancelled'],                                           

                        'temp_pending'  => ['title' => 'Template for Pending Order.',
                                            'sub_title' => 'This template works when order is Pending'],    

                        'temp_complete' => ['title'  => 'Template for Order Completion',
                                            'sub_title' => 'This template works when order is Completed'],    

                        'temp_refund'   => ['title' => 'Template for Refund Order.',
                                            'sub_title' => 'This template works when order is procide to Refund'], 
                        
                        'temp_faild'    => ['title' => 'Template for Faild Order.',
                                            'sub_title' => 'This template works when order is Faild']
                        ];

                $html .="<div class='wwn_configuration_main'>"; 
                if(!empty($struct)) {
                        foreach ($struct as $key => $tmp) {
                            $temp_data   =  get_option('data_'.$key);
                            $temp_name   =  !empty($temp_data['name']) ? $temp_data['name'] : '';
                            $temp_head   =  !empty($temp_data['components'][0]['text']) ? $temp_data['components'][0]['text'] : '';
                            $temp_body   =  !empty($temp_data['components'][1]['text']) ? $temp_data['components'][1]['text'] : '';
                            $temp_foot   =  !empty($temp_data['components'][2]['text']) ? $temp_data['components'][2]['text'] : '';
                            $status      =  json_decode($get_obj->get_approved_templates($temp_name))->data[0]->status;
                            $approval    = !empty($status) ? 'disabled' : '';
                            $html .= "<div class='wwn_first_template'><table data-title='".$key."'>";
                            if($status){
                                $html .= "<tr>
                                            <th colspan='2'><span>".$tmp['title']."</span>
                                                <div class='status'><span class=".$status."></span><p>(".$status.")</p>
                                                <a href='#' id='remove_template' data-name=".$temp_name." data-key='data_".$key."'>
                                                    <span class='dashicons dashicons-trash' title='Delete Template'></span>
                                                </a>
                                                </div>  
                                            </th>
                                        </tr>";
                            } else {
                                $html .= "<tr><th colspan='2'>".$tmp['title']."</th></tr>";
                            }

                            $html .= "<tr><th colspan='2'><p>".$tmp['sub_title']."</p></th></tr>";
                            $html .= "<tr><th>Template Title:</th>
                                        <td>
                                            <input type='text' 
                                            value='".$temp_name."' 
                                            name='txt_temp_title' 
                                            id='".$key."_title'
                                            class='".$class."' 
                                            placeholder='Template Title' ".$approval."/>
                                        </td>
                                     </tr>"; 
                            $html .= "<tr><th>Template Header:</th>
                                        <td>
                                            <input type='text' 
                                            value='".$temp_head."' 
                                            name='txt_temp_head' 
                                            id='".$key."_head' 
                                            class='".$class." template_header_text' 
                                            placeholder='Template Header' ".$approval."/>
                                            <span class='text_for_header'>Dynamic Variables Customer Name: {{1}}</span>
                                            <span class='text_for_header'>eg. Hello {{1}}. Output: Hello John Doe</span>
                                        </td>
                                     </tr>"; 
                             $html .= "<tr><th>Template Body:</th>
                                        <td>
                                            <textarea name='txt_temp_body' 
                                            id='".$key."_body' 
                                            class='".$class."' 
                                            placeholder='Template Body' ".$approval.">".$temp_body."</textarea>
                                            <span class='text_for_company_name'>Dynamic Variables Company Name: {{1}} Order ID: {{2}}</span>
                                        </td>

                                     </tr>";
                            $html .= "<tr><th>Template Footer:</th>
                                        <td>
                                            <input type='text' 
                                            value='".$temp_foot."' 
                                            name='txt_temp_foot'
                                            id='".$key."_foot' 
                                            class='".$class."' 
                                            placeholder='Template Footer' ".$approval."/>
                                        </td>
                                     </tr>";
                             $html .= "<tr><td colspan='2'>
                                        <button class='button-primary temp_btn btn_save_temp' id='".$key."_btn' ".$approval.">Submit</button></td>
                                       </tr>";
                       
                            $html .= "</table></div>";                      
                        }
                }
                $html .= "</div>";
            echo $html;
        return apply_filters( 'wwn_templates', $settings );
        ob_get_clean();
    }
}

WWN_Templates::init();