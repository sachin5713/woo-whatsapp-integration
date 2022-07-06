<?php
class WWN_Newsletter {
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_wwn_newsletter', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_wwn_newsletter', __CLASS__ . '::update_settings' );
        add_action( 'woocommerce_settings_customer_list',__CLASS__ . '::newsletter_structure');
    }

    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['wwn_newsletter'] = __( 'WhatsApp Newsletter');
        return $settings_tabs;
    }

    public static function settings_tab() {woocommerce_admin_fields( self::newsletter_structure() );}
    public static function update_settings() {woocommerce_update_options( self::newsletter_structure() );}

    public function newsletter_structure() {
        // error_reporting(0);
        $settings = array('section_title' => array('type'=> 'title','id'=>'wc_setting_title_main'));
        $html = '';
        if($_GET['tab'] == 'wwn_newsletter'){ $html .='<style>.woocommerce-save-button{display:none !important;}</style>'; }
        $html .= '<div class="newsletter_header">';
        $html .= '<h2>WooCommerce Newsletter</h2>';
        $html .= '<p>Allows WooCommerce to send Whatsapp Newsletter on new offers.<span class="g_variable">Dynamic Variables: {{Customer Name}}, {{Order Number}}, {{Order URL}}, {{Review URL}}</span></p>';
        $html .= '</div>';

        $html .= '<div class="newsletter_wrapper">
                    <div class="newsletter_message"><div class="editor_icon">
                        <a href="javascript:" id="bold"><i class="dashicons dashicons-editor-bold"></i></a>
                        <a href="javascript:" id="italic"><i class="dashicons dashicons-editor-italic"></i></a>
                        <a href="javascript:" id="strike"><i class="dashicons dashicons-editor-strikethrough"></i></a>
                        <a href="javascript:" id="monospace"><i class="dashicons dashicons-format-quote"></i></a>';
        $html .= '</div>';

        $html .= '<div class="editor_input"><textarea id="textArea" name="txt_message"></textarea></div>';
        $html .= '<button type="submit" class="button-primary btn_msgsend">Send</button>';
        $html .= '</div>';

        $html .= '<div class="newsletter_preview"></div>';

        $html .= '</div>';
        echo $html;
        return apply_filters( 'wwn_newsletter', $settings );
    }
}

WWN_Newsletter::init();