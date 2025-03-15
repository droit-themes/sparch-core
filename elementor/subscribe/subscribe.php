<?php
namespace DROIT_ELEMENTOR_PRO;

if ( ! defined( 'ABSPATH' ) ) {exit;}

class Dl_Subscribe{

    private static $instance = null;

    public static function url(){
		if (defined('DROIT_EL_PRO_FILE')) {
			$file = trailingslashit(plugin_dir_url( DROIT_EL_PRO_FILE )). 'modules/subscribe/';
		} else {
			$file = trailingslashit(plugin_dir_url( __FILE__ ));
		}
		return $file;
	}

	public static function dir(){
		if (defined('DROIT_EL_PRO_FILE')) {
			$file = trailingslashit(plugin_dir_path( DROIT_EL_PRO_FILE )). 'modules/subscribe/';
		} else {
			$file = trailingslashit(plugin_dir_path( __FILE__ ));
		}
		return $file;
	}

	public static function version(){
		if( defined('DROIT_EL_PRO_VERSION') ){
			return DROIT_EL_PRO_VERSION;
		} else {
			return apply_filters('dladdons_pro_version', '1.0.0');
		}
		
	}

    public function init(){

        // load providers
        include_once( __DIR__ . '/providers/mailchimp.php');
        include_once( __DIR__ . '/providers/response.php');

        // load js
        add_action( 'wp_enqueue_scripts', [ $this, 'load_js' ] );

        // load ajax
        add_action('wp_ajax_dtsubscribe_add', [ $this, 'dtsubscribe_add']);
        add_action('wp_ajax_nopriv_dtsubscribe_add', [ $this, 'dtsubscribe_add']);
    }

    /**
    * Name: load_js
    * Desc: load JS files for subscribe
    * Params: no params
    * Return: @void
    * version: 1.0.0
    * Package: @droitedd
    * Author: DroitThemes
    * Developer: Hazi
    */

    public function load_js(){
        // load subscribe js files
        wp_enqueue_script("dl_addons_subscriber", self::url() . 'js/subscribe.min.js', ['jquery'], self::version(), true);

        wp_localize_script(
            'dl_addons_subscriber',
            'dl_subscribe',
            array(
                'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
                'posturl' => esc_url( admin_url( 'post.php' ) ),
                'nonce'   => wp_create_nonce( 'droitpro-subscribe-nonce' ),
            )
        );   

    }

    /**
    * Name: dtsubscribe_add
    * Desc: Action for AJAX Request
    * Params: no params
    * Return: @void
    * version: 1.0.0
    * Package: @droitedd
    * Author: DroitThemes
    * Developer: Hazi
    */

    public function dtsubscribe_add(){
        $post = wp_slash($_POST);

        if( !isset( $post['form_data'] )){
            wp_send_json_error( 'Couldn\'t found any data');
        }
        wp_parse_str( $_POST['form_data'], $forms_data);
        $settings = isset($_POST['form_settings']) ? $_POST['form_settings'] : [];

        $status = 'no';
        $message = '';
            
        $providers = ($settings['providers']) ?? [];
        $require = ($settings['require']) ?? [];
        $setup = ($settings['setup']) ?? [];

        if( !empty($require) ){
            foreach($require as $k=>$v){
                if( $v == 'no'){
                    continue;
                }
                if( isset($forms_data[$k]) ){
                    if($k == 'email'){
                        $value = sanitize_email($forms_data[$k]);
                    } else {
                        $value = sanitize_text_field($forms_data[$k]);
                    }
                    if( empty($value) ){
                        $status = 'yes';
                        $message = isset($setup['error']) ? $setup['error'] : 'Please enter correct info.';
                    }
                }
            }
            if( $status == 'yes' ){
                wp_send_json_error( $message );
            }
        }

        // form data
        $postData = [];

        $postData['fname'] = isset($forms_data['first_name']) ? sanitize_text_field($forms_data['first_name']) : '';
		$postData['lname'] = isset($forms_data['last_name']) ? sanitize_text_field($forms_data['last_name']) : '';
		$postData['email'] = isset($forms_data['email']) ? trim( sanitize_email($forms_data['email']) ) : '';
		$postData['phone'] = isset($forms_data['phone']) ? sanitize_text_field($forms_data['phone']) : '';
		$postData['address'] = isset($forms_data['address']) ? sanitize_text_field($forms_data['address']) : '';
		$postData['city'] = isset($forms_data['city']) ? sanitize_text_field($forms_data['city']) : '';
		$postData['state'] = isset($forms_data['state']) ? sanitize_text_field($forms_data['state']) : '';
		$postData['zip'] = isset($forms_data['zip']) ? sanitize_text_field($forms_data['zip']) : '';
		$postData['country'] = isset($forms_data['country']) ? sanitize_text_field($forms_data['country']) : '';

        // for mailchimp connect
        if( isset($providers['mailchimp']) && $providers['mailchimp']['enable'] == 'yes'){
            $listId = isset($providers['mailchimp']['listid']) ? $providers['mailchimp']['listid'] : '';
            if( !empty($listId) ){
                $this->get_adapter('mailchimp')->add_subscribe($listId, 'subscribe', $postData);
                $message = isset($setup['success']) ? $setup['success'] : 'Successfully listed.';
            } else {
                $message = 'Please select mailchimp any list.';
            }
        }
        // get response
        if( isset($providers['response']) && $providers['response']['enable'] == 'yes'){
            $listId = isset($providers['response']['listid']) ? $providers['response']['listid'] : '';
            if( !empty($listId) ){
                $this->get_adapter('response')->add_subscribe($listId, 'subscribe', $postData);
                $message = isset($setup['success']) ? $setup['success'] : 'Successfully listed.';
            } else {
                $message = 'Please select get response any list.';
            }
        }
        
        wp_send_json_success( $message);
    }

    /**
    * Name: get_adapter
    * Desc: Get Adapter object for dynamic
    * Params: @string
    * Return: @object
    * version: 1.0.0
    * Package: @droitedd
    * Author: DroitThemes
    * Developer: Hazi
    */

    public function get_adapter( $providers = 'mailchimp'){

		if( $providers == 'sendin' ){
			$this->adapter = New Providers\DL_Sendin();
		}else if( $providers == 'response' ){
			$this->adapter = New Providers\DL_Response();
		}else if( $providers == 'mailchimp' ){
			$this->adapter = New Providers\DL_Mailchimp();
		}else if( $providers == 'active' ){
			$this->adapter = New Providers\DL_Active();
		}else{
			$this->adapter = New Providers\DL_Mailchimp();
		}
		return $this->adapter;
    }
    /**
    * Name: _get_list
    * Desc: get list for each provider
    * Params: $providers
    * Return: @array
    * version: 1.0.0
    * Package: @droitedd
    * Author: DroitThemes
    * Developer: Hazi
    */
    public function _get_list( $providers = 'mailchimp' ){
        $list = [];
        $lists = $this->get_adapter($providers)->get_lists( true );
        if( empty($lists) || !is_array($lists) ){
            return [ '' => 'No list found'];
        }

        foreach($lists as $v){
            $id = isset($v['id']) ? $v['id'] : '';
            $name = isset($v['name']) ? $v['name'] : '';
            $list[$id] = $name;
        }
        return $list;
    }

    public static function instance(){
        if( is_null(self::$instance) ){
            self::$instance = new self;
        }
        return self::$instance;
    }

}