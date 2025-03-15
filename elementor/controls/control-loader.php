<?php
namespace sparch_CORE\Elementor;
defined( 'ABSPATH' ) || exit;

use \sparch_CORE\DRTH_Plugin as DR_Plugin;
use \sparch_CORE\Elementor\Loader as DR_Elementor;

class Control_Loader{

    private static $instance;

    private $controls = [];

    public static function controls_url(){
		return DR_Plugin::dtdr_th_url().'elementor/controls/';
	}

	public static function controls_dir(){
		return DR_Plugin::dtdr_th_dir().'elementor/controls/';
	}

    public static function version(){
		return DR_Plugin::version();
	}

    public function load(){

        // change version
        add_filter('dladdons_pro_version', [__CLASS__, 'version']);

        // load control
		add_action( 'elementor/controls/controls_registered', [$this, 'load_controls'] , 10 );

        // load paralax
        if( !class_exists('\DROIT_ELEMENTOR_PRO\Parallax')){
            DR_Elementor::_elementor_autoload('\DROIT_ELEMENTOR_PRO\Parallax');
            \DROIT_ELEMENTOR_PRO\Parallax::instance()->init();
        }
       

        // load Effect
        if( !class_exists('\DROIT_ELEMENTOR_PRO\Effect')){
            DR_Elementor::_elementor_autoload('\DROIT_ELEMENTOR_PRO\Effect');
            \DROIT_ELEMENTOR_PRO\Effect::instance()->init();
        }
       
        // load Lottie
        if( !class_exists('\DROIT_ELEMENTOR_PRO\Lottie')){
            DR_Elementor::_elementor_autoload('\DROIT_ELEMENTOR_PRO\Lottie');
            \DROIT_ELEMENTOR_PRO\Lottie::instance()->init();
        }

        // load transform
        if( !class_exists('\DROIT_ELEMENTOR_PRO\CSS_Transform')){
            DR_Elementor::_elementor_autoload('\DROIT_ELEMENTOR_PRO\CSS_Transform');
            \DROIT_ELEMENTOR_PRO\CSS_Transform::instance()->init();
        }
    }

    

	public function load_controls( $controls_manager ){
		$grouped = array(
            'droit-content-typography' => '\DROIT_ELEMENTOR_PRO\Content_Typography',
            'droit-image' => '\DROIT_ELEMENTOR_PRO\Image',
            'droit-icon' => '\DROIT_ELEMENTOR_PRO\Icon',
            'droit-svg' => '\DROIT_ELEMENTOR_PRO\Icon_SVG',
            'droit-position' => '\DROIT_ELEMENTOR_PRO\Position',
            'droit-button' => '\DROIT_ELEMENTOR_PRO\Button',
            'droit-button-size' => '\DROIT_ELEMENTOR_PRO\Button_Size',
            'droit-button-hover' => '\DROIT_ELEMENTOR_PRO\Button_Hover',
            'droit-button-hover-advanced' => '\DROIT_ELEMENTOR_PRO\Button_Hover_Advanced',
            'button-hover-advanced-second' => '\DROIT_ELEMENTOR_PRO\Button_Hover_Advanced_Second',
        );

        foreach ( $grouped as $control_id => $class_name ) {
 
            if( !class_exists($class_name) ){
                DR_Elementor::_elementor_autoload($class_name);
            }
            if( class_exists($class_name) ){
                $controls_manager->add_group_control( $control_id, new $class_name() );
            }
            
        }
	}

    public static function _instance(){
        if( is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
}