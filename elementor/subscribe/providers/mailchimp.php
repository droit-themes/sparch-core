<?php
namespace DROIT_ELEMENTOR_PRO\Providers;

if( ! defined( 'ABSPATH' )) die( 'Forbidden' );

Class DL_Mailchimp{
    
    private $url = 'https://%s.api.mailchimp.com/3.0/lists/%s/';

    protected $list_url = 'https://%s.api.mailchimp.com/3.0/lists/';

    protected $accessTokenUrl = 'https://login.mailchimp.com/oauth2/token';

    private $api_key;

    public function __construct( ){
        $settings = get_option('_key_el_settings_', true);
        $this->api_key = isset($settings['api_data']['mailchimp']) ? $settings['api_data']['mailchimp'] : '';
	}

    public function add_subscribe( $listed_id, $get, $data){
        
        if( empty($this->api_key) OR $this->api_key == ''){
			return esc_html__('Please Enter MailChimp API Key.', 'sparch-core');
        }
        
        if(strlen($this->api_key) > 1){

            if(empty(trim($listed_id))){
                return esc_html__('Please select any MailChimp listed.', 'sparch-core');
            }

            $email = isset($data['email']) ? $data['email'] : '';
            $postData = [];
            if( empty($email)){
                return;
            }
            $postData['email_address'] = $email;

            $additional = [];
            if(isset($data['fname'])){
                $additional['FNAME'] = $data['fname'];
            }

            if(isset($data['lname'])){
                $additional['LNAME'] = $data['lname'];
            }

            if(isset($data['phone'])){
                $additional['PHONE'] = $data['phone'];
            }

            if(isset($data['address'])){
                $additional['ADDRESS']['addr1'] = $data['address'];
            }

            if(isset($data['city'])){
                $additional['ADDRESS']['city'] = $data['city'];
            }

            if(isset($data['state'])){
                $additional['ADDRESS']['state'] = $data['state'];
            }
            
            if(isset($data['zip'])){
                $additional['ADDRESS']['zip'] = $data['zip'];
            }

            if(isset($data['country'])){
                $additional['ADDRESS']['country'] = $data['country'];
            }
            

            $postData['status'] =  'subscribed';
            if( is_array($additional) && sizeof($additional) > 0):
                $postData['merge_fields'] =  $additional;
            endif;
            $postData['status_if_new'] =  'subscribed';


            $api_data = explode( '-', $this->api_key );	
            $hosting = end($api_data);	

            $url = sprintf( $this->url.'members', $hosting, trim($listed_id)); 

            $response = wp_remote_post( $url, [
                'method' => 'POST',
                'data_format' => 'body',
                'timeout' => 45,
                'headers' => [
                    'Authorization' => 'apikey '.$this->api_key,
                    'Content-Type' => 'application/json; charset=utf-8'
                ],
                'body' => json_encode($postData	)
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
			return esc_html__('Please Enter MailChimp API Key.', 'sparch-core');
        }

		if(strlen($this->api_key) > 1){
			$tokenData   = get_transient( '_dl_pro_mailchimp_list', '' );
			$dataToken 	 = is_array($tokenData) ? $tokenData : '';
			$get_transient_time   = get_transient( 'timeout__dl_pro_mailchimp_list' );		
        	if( !empty($dataToken)){	
				if( $load ){
					return $dataToken;
				}
			}
			$header = [
				'Authorization' => 'apikey ' . $this->api_key,
				'Content-Type' => 'application/json; charset=utf-8',
			];
			
			$body = [];
			try {
				$api_data = explode( '-', $this->api_key );	
                $hosting = end($api_data);	
                $url = sprintf($this->url, $hosting, $listed);
                
				$respon = wp_remote_get( $url, array( 'timeout' => 120, 'httpversion' => '1.1', 'headers' => $header, 'body' => $body ) );
				if ( !is_array( $respon ) ) {
					return $dataToken;
				}
				
				$response = isset( $respon['body']) ?  $respon['body'] : [];
                $response = @json_decode($response, true);
                
				if(isset($response['lists'])){
                    $lists = isset($response['lists']) ?  $response['lists'] : [];
                    
					$data = array_map(function( $a ){
						$return['id'] = isset($a['id']) ? $a['id'] : '';
						$return['name'] = isset($a['name']) ? $a['name'] : '';
						$return['member_count'] = isset($a['stats']['member_count']) ? $a['stats']['member_count'] : 0;
						$return['unsubscribe_count'] = isset($a['stats']['unsubscribe_count']) ? $a['stats']['unsubscribe_count'] : 0;
						$return['campaign_count'] = isset($a['stats']['campaign_count']) ? $a['stats']['campaign_count'] : 0;
						return $return;
                    }, $lists);
                    
					set_transient( '_next_mail_mailchimp_list', $data , 60*60*60 );
					
					return $data;
				}

			}
			catch (Exception $e) {
				return esc_html__('Api Errors', 'sparch-core');
			}		
		}
		return esc_html__('Enter API Keys', 'sparch-core');
    }

}