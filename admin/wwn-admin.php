<?php
class WC_Settings_Tab_WWN {
    /*
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_wwn', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_wwn', __CLASS__ . '::update_settings' );
    }
    
    
    /*
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_wwn'] = __( 'WhatsApp Integration', 'woocommerce-settings-tab-wwn' );
        return $settings_tabs;
    }


    /*
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }


    /*
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }


    /*
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {

        $settings = array(
            'section_title' => array(
                'name'          => __( 'WooCommerce WhatsApp Notifications', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'title',
                'id'            => 'WC_Settings_Tab_WWN_section_title',
                'desc'          => 'Allows WooCommerce to send Whatsapp notifications on each order status change. 
                                    It can also notify the owner when a new order is received.
                                    <span class="g_variable">Dynamic Variables: {{Customer Name}}, {{Order Number}}, {{Order URL}}, {{Review URL}}</span>
                                    '
            ),
            'api_token'         => array(
                'name'          => __( 'User-Access-Token: ', 'woocommerce-settings-tab-wwn' ),
                'placeholder'   => 'User-Access-Token',
                'id'            => 'wc_setting_api_token',
                'type'          => 'text',
                'desc_tip'      => true,
                'desc'          => 'Get WhatsApp Authantication token: <b style="font-size:10px"><br><a href="https://developers.facebook.com/" target="_blank">WhasApp</a></b>'
            ),
            'phone_number_id'         => array(
                'name'          => __( 'Phone number ID: ', 'woocommerce-settings-tab-wwn' ),
                'placeholder'   => 'Phone-Number-ID',
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
            'thankyou_msg'   => array(
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
                'name'          => __( 'Order Refund Template: ', 'woocommerce-settings-tab-wwn' ),
                'type'          => 'textarea',
                'placeholder'   => 'Set pending payment message when admin can select the faild status.',
                'class'         => 'template_field',
                'id'            => 'wc_setting_faild'
            ),
            'status_section_end'   => array(
                 'type'     => 'sectionend',
                 'id'       => 'WC_Settings_Tab_WWN_status_section_end'
            )
        );

        return apply_filters( 'WC_Settings_Tab_WWN_settings', $settings );
    }

}

WC_Settings_Tab_WWN::init();