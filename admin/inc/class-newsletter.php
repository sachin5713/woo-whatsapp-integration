<?php
class WWN_Newsletter {
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_wwn_newsletter', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_wwn_newsletter', __CLASS__ . '::update_settings' );
        add_action( 'woocommerce_sections',__CLASS__ . '::get_sections');
    }

    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['wwn_newsletter'] = __( 'WhatsApp Newsletter', 'woocommerce-settings-tab-demo' );
        return $settings_tabs;
    }

    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }

    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }

    public static function get_settings() {

        $settings = array(
            'section_title' => array(
                'name'     => __( 'WooCommerce WhatsApp Newsletter' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wwn_setting_section_title'
            ),
            'title' => array(
                'name' => __( 'Newsletter Title' ),
                'type' => 'text',
                'desc' => __( 'This is some helper text' ),
                'id'   => 'wwn_setting_title'
            ),
            'description' => array(
                'name' => __( 'Description' ),
                'type' => 'textarea',
                'desc' => __( '' ),
                'id'   => 'wwn_setting_description',
                'class'         => 'template_field',
            ),
            'section_end' => array(
               'type' => 'sectionend',
               'id' => 'wwn_setting_section_end'
           )
        );
        return apply_filters( 'wwn_newsletter', $settings );
    }
}

WWN_Newsletter::init();