<?php
class WWN_Api_Settings{
	public $token = '', $message_api = '' , $api_args = '', $curl_args;
	public function __construct() {
        $config_data		= get_option( 'wwn_config_data' );
        $this->token 		= $config_data['token'];
        $this->message_api 	= 'https://graph.facebook.com/'.$config_data['version'] .'/'.$config_data['phone_id'].'/messages';
        $this->template_api = 'https://graph.facebook.com/'.$config_data['version'] .'/'.$config_data['phone_id'].'/message_templates';
        $this->template_get = 'https://graph.facebook.com/'.$config_data['version'] .'/'.$config_data['business_id'].'/message_templates';
    }

    public function request_to_register_template($param = null){
    	$args = [
			'timeout' => 10,'headers'    => ['content-type' => 'application/json','Authorization' => 'Bearer ' . $this->token],
			'body'    => json_encode($param),'sslverify'  => false ];
		return json_decode(wp_remote_post( $this->template_get,$args )['body']);
    }

    public function get_approved_templates($template_name){
    	$args 	= ['timeout' => 10,'headers'    => ['content-type' => 'application/json','Authorization' => 'Bearer ' . $this->token],'sslverify'  => false ];
    	$status = wp_remote_get( $this->template_get.'?name='.$template_name, $args)['body'];
    	if(!empty($template_name)){
    		return $status;	
    	} else {
    		return false;
    	}
    }

    public function request_to_remove_template($template_name){
    	$args 	= ['timeout'=>10,'method'=>'DELETE','headers'=>['content-type'=>'application/json','Authorization' =>'Bearer '.$this->token],'sslverify' => false ];
    	return json_decode(wp_remote_request( $this->template_get.'?name='.$template_name, $args)['body']);
    }

	public function send_message($order_id, $template_name){	 
		if(!empty($this->token)){
			$message_body	 = 	[];
			$billing_country = 	get_post_meta($order_id,'_billing_country',true);
	        $customer_name   = 	get_post_meta($order_id,'_billing_first_name',true).' '.get_post_meta($order_id,'_billing_last_name',true);
	        $calling_code    = 	WC()->countries->get_country_calling_code($billing_country);
	        $order_mobile    = 	str_replace('+', '', $calling_code).get_post_meta($order_id,'_billing_phone',true);
	        $get_template 	 = 	$this->get_approved_templates($template_name);
	        $get_components	 =  json_decode($get_template)->data[0]->components;
	       	$params 		 = [];

	       	$message_body['messaging_product'] = "whatsapp";
	       	$message_body['recipient_type']    = "individual";
	       	$message_body['to'] 			   = $order_mobile;
	       	$message_body['type'] 		       = "template";
	       	$message_body['template'] 		   = ["name" => $template_name, "language" => ["code" => "en"]];

	       	for ($i = 0; $i < count($get_components) ; $i++) { 
	       		$content = $get_components[$i]->text;
	       		if(str_contains($content, '{{1}}') && $get_components[$i]->type === "HEADER"){
       				$head_content = explode(' ', preg_replace('/[.,]/', '', $content));
				    $params['head_param'][] = $head_content[array_search('{{1}}', $head_content)];
	       		}
	       		if($get_components[$i]->type === "BODY" && (str_contains($content, '{{1}}') || str_contains($content, '{{2}}'))){
	       			$body_content = explode(' ', preg_replace('/[.,]/', '', $get_components[$i]->text));
	       			$params['body_param'][] = $body_content[array_search('{{1}}', $body_content)];
	       			$params['body_param'][] = $body_content[array_search('{{2}}', $body_content)];
	       		}
	       	}
	       	if(!empty($params)){
		       	if(!empty($params['head_param'])){
		       		$message_body['template']['components'][] = ["type" => "header", "parameters" => [["type" => "text", "text" => $customer_name]]];
		       	}
		       	if(!empty($params['body_param'])){
					$param_count = count($params['body_param']);
					if($param_count > 1){
						$message_body['template']['components'][] = ["type" => "BODY", "parameters" => 
																	[["type" => "text", "text" => "*".get_bloginfo( 'name' )."*"],
																	["type" => "text", "text" => "*#".$order_id.'*' ]]];
					} elseif($param_count < 2) {
						$message_body['template']['components'][] = ["type" => "BODY", "parameters" => 
																	["type" => "text", "text" => get_bloginfo( 'name' )]];
					}
		       	}
	       	}
			$args = ['timeout'=> 10,'headers'=> ['content-type'=>'application/json','Authorization'=>'Bearer '.$this->token],
				 	 'body'=> json_encode($message_body),'sslverify'  => false ];
			return json_decode(wp_remote_post($this->message_api,$args )['body']);
		}
	}



/*    private function create_message_body($mobile, $body, $customer_name, $order_id = null){
    	$message_body   = str_replace(array('{{Customer Name}}', '{{Order Number}}'), array('*'.$customer_name.'*', '*#'.$order_id.'*'), $body);
    	$this->api_args = [ "messaging_product" => "whatsapp", 
					   	  	"preview_url" => true, 
					   	  	"recipient_type" => "individual", 
					   	  	"to" => $mobile, 
					   	  	"type" => "text", 
					   	  	"text" => [ "body" => $message_body ] 
					   	  ];
		return $this->api_args; 
    }

    private function get_whatsapp_args($message_body){
    	$this->curl_args = array(
				'timeout'    => 10,
				'headers'    => array(	'content-type' => 'application/json','Authorization' => 'Bearer ' . $this->token),
				'body'       => json_encode($message_body),
				'sslverify'  => false
			);
    	return $this->curl_args;
    }

    public function send_newsletter($user_mobile, $customer_name, $message_body){	 
		if(!empty($this->token)){
			$get_message_body 	= $this->create_message_body($user_mobile,$message_body,$customer_name);
			$args = $this->get_whatsapp_args($get_message_body,$oder_id,$customer_name);
			return json_decode(wp_remote_post( $this->api_url,$args )['body']);
		}
	}

	public function send_welcome_message($user_mobile,$oder_id,$customer_name){	 
		if(!empty($this->token)){
			$welcome_message 	= get_option( 'wc_setting_thank_template' );
			$get_message_body 	= $this->create_message_body($user_mobile,$welcome_message,$customer_name,$oder_id);
			$args = $this->get_whatsapp_args($get_message_body,$oder_id,$customer_name);
			return json_decode(wp_remote_post( $this->api_url,$args )['body']);
		}
	}
	

	public function send_message_by_changing_status($params = []){
		$order_status = $params['current_status'];
		switch ($order_status) {
		  case "on-hold":
		    $message_body = get_option( 'wc_setting_on_hold' );
		    break;
		  case "pending":
		    $message_body = get_option( 'wc_setting_pending_payment' );
		    break;
		  case "processing":
		    $message_body = get_option( 'wc_setting_order_processing' );
		    break;
		  case "completed":
		    $message_body = get_option( 'wc_setting_completed' );
		    break;
		  case "cancelled":
		    $message_body = get_option( 'wc_setting_cancelled' );
		    break;
		  case "refunded":
		    $message_body = get_option( 'wc_setting_refund' );
		    break;
		  case "failed":
		    $message_body = get_option( 'wc_setting_faild' );
		    break;
		  default:
		    $message_body = 'Nothing to Send';
		}
		
		$get_message_body = $this->create_message_body($params['customer_mobile'], $message_body, $params['customer_name'], $params['order_id']);
		$args = $this->get_whatsapp_args($get_message_body);
		return json_decode(wp_remote_post( $this->api_url,$args )['body']);
	}*/
}