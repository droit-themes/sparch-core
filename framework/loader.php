<?php
namespace sparch_CORE\Framework;
defined( 'ABSPATH' ) || exit;


use \sparch_CORE\DRTH_Plugin as DR_Plugin;

class Loader{

	private static $instance;

	public static function framework_url(){
		return DR_Plugin::dtdr_th_url().'framework/';
	}

	public static function framework_dir(){
		return DR_Plugin::dtdr_th_dir().'framework/';
	}

	public function _init(){
		require_once self::framework_dir().'custom-postype.php';
		require_once self::framework_dir().'init.php';
	}

	public static function instance(){
        if ( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

}