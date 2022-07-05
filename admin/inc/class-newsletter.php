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
        error_reporting(0);

        $html = '';
        $html .= '<div class="newsletter_header">';
        $html .= '<h2>Newsletter</h2>';
        $html .= '</div>';
        $html .= '<div class="newsletter_wrapper">';

        $html .= '<div class="newsletter_message">';
        $html .= '<div class="editor_icon">';

        $html .= '<input class="inputs" type="checkbox" id="bold" name="method" value="bold" />
                    <label class="dashicons dashicons-editor-bold" for="bold"></label>';

        $html .= '<input class="inputs" type="checkbox" id="italic" name="method" value="italic">
                    <label class="dashicons dashicons-editor-italic" for="italic"></label>';

        $html .= '<input class="inputs" type="checkbox" id="strike" name="method" value="strikethrough">
                    <label class="dashicons dashicons-editor-strikethrough" for="strike"></label>';

        $html .= '<label class="dashicons dashicons-paperclip" for="upload_file"></label>
                    <input class="inputs" type="file" id="upload_file" name="upload_file">';

        $html .= '</div>';

        $html .= '<div class="editor_input">';
        $html .= '<textarea id="textArea" name="txt_message"></textarea>';
        $html .= '<button type="submit" class="btn_send">Send</button>';
        $html .= '</div>';

        $html .= '</div>';

        $html .= '<div class="newsletter_preview">';
        $html .= '</div>';

        $html .= '</div>';
        print_r($html);
        return apply_filters( 'wwn_newsletter', $html );
    }
}

WWN_Newsletter::init();