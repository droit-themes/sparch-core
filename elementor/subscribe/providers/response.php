<?php
namespace DROIT_ELEMENTOR_PRO\Providers;

if( ! defined( 'ABSPATH' )) die( 'Forbidden' );

Class DL_Response{
    
    private $url = 'https://api.getresponse.com/';

    protected $accessTokenUrl = '';

    private $api_key;

    private $private_key;
    
    public function __construct( ){
        $settings = get_option('_key_el_settings_', true);
        $this->api_key = isset($settings['api_data']['response']) ? $settings['api_data']['response'] : '';
	}

    public function add_subscribe( $listed_id, $get, $data){

        if( empty($this->api_key) OR $this->api_key == ''){
			return esc_html__('Please Enter getResponse API Key.', 'sparch-core');
        }
        

        if(strlen($this->api_key) > 1){

            if(empty(trim($listed_id))){
                return esc_html__('Please select any getResponse listed.', 'sparch-core');
            }

            $email = isset($data['email']) ? $data['email'] : '';
            $postData = [];
            if( empty($email)){
                return;
            }
            $postData['email'] = $email;

            $name = isset($data['fname']) ? $data['fname'] : '';
			if(isset($data['lname']) && !empty(isset($data['lname']))){
				$name .= ' '.$data['lname'];
			}
			
			$postData['name'] = $name;
			
			$postData['campaign'] = (object) ['campaignId' => $listed_id];
			$postData['ipAddress'] = self::get_ip();
            $postData['dayOfCycle'] = 0;
            
            $additional = [];
            if(isset($data['phone'])){
                $additional['phone'] = $data['phone'];
            }

            if(isset($data['address'])){
                $additional['address']['addr1'] = $data['address'];
            }

            if(isset($data['city'])){
                $additional['address']['city'] = $data['city'];
            }

            if(isset($data['state'])){
                $additional['address']['state'] = $data['state'];
            }
            
            if(isset($data['zip'])){
                $additional['address']['zip'] = $data['zip'];
            }

            if(isset($data['country'])){
                $additional['address']['country'] = $data['country'];
            }

			if( isset($data['custom_fields']) && is_array($data['custom_fields']) ){
				$postData['customFieldValues'] = $data['custom_fields'];
			}else if( !empty($additional) ) {
                $postData['customFieldValues'] = (object) $additional;
            }
			
			if( isset($data['tags']) && is_array($data['tags']) ){
				$postData['tags'] = (object) $data['tags'];
			}

            $url = $this->url.'v3/contacts/';
            
            $response = wp_remote_post( $url, [
                    'method' => 'POST',
                    'data_format' => 'body',
                    'timeout' => 45,
                    'headers' => [
                        'X-Auth-Token' => 'api-key ' .$this->api_key,
                        'Content-Type' => 'application/json; charset=utf-8'
                    ],
                    'body' => json_encode( $postData	)
                ]
            );
           
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                $return['error'] = "Something went wrong: $error_message";
             } else {
                $msg = '';
                $body = isset($response['body']) ? $response['body'] : '';
                $return['success'] = $body;
             }
            
			return $return;
			
		}
		return;
    }

    public function get_lists($load = true, $listed = ''){
        
        if( empty($this->api_key) OR $this->api_key == ''){
			return esc_html__('Please Enter getResponse API Key.', 'sparch-core');
        }
        
		if(strlen($this->api_key) > 1){
            
            $tokenData   = get_transient( '_dl_pro_response_list', '' );
			$dataToken 	 = is_array($tokenData) ? $tokenData : '';
			$get_transient_time   = get_transient( 'timeout__dl_pro_response_list' );		
        	if( !empty($dataToken)){	
				if( $load ){
					return $dataToken;
				}
            }
            
			$header = [
				'X-Auth-Token' => 'api-key ' .$this->api_key,
                'Content-Type' => 'application/json; charset=utf-8'
			];
			
			$body = [ 
               
			];
			try {
				$url = $this->url.'v3/campaigns'; 
				$respon = wp_remote_get( $url, array( 'timeout' => 120, 'httpversion' => '1.1', 'headers' => $header, 'body' => $body ) );
				if ( !is_array( $respon ) ) {
					return $dataToken;
				}
				
				$response = isset( $respon['body']) ?  $respon['body'] : [];
                $response = @json_decode($response, true);
                
				if(isset($response)){
                    
					$data = array_map(function( $a ){
						$return['id'] = isset($a['campaignId']) ? $a['campaignId'] : '';
						$return['name'] = isset($a['name']) ? $a['name'] : '';
						$return['subscribers'] = isset($a['total_subscribers']) ? $a['total_subscribers'] : '';
						$return['unconfirmed_subscribers'] = isset($a['total_unconfirmed_subscribers']) ? $a['total_unconfirmed_subscribers'] : '';
						$return['unsubscribed_subscribers'] = isset($a['total_unsubscribed_subscribers']) ? $a['total_unsubscribed_subscribers'] : '';
						return $return;
                    }, $response);
                    
					set_transient( '_next_mail_response_list', $data , 60*60*60 );
					
					return $data;
				}

			}
			catch (Exception $e) {
				return esc_html__('Api Errors', 'sparch-core');
			}		
		}
		return;
    }

    public static function get_ip(){
        
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			return $_SERVER['HTTP_CLIENT_IP'];
		}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			return $_SERVER['REMOTE_ADDR'];
		}
		return '';
	}
}