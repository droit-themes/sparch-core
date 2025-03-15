<?php
namespace DROIT_ELEMENTOR_PRO;

use \Elementor\Controls_Manager;
use \Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit;
}
class CSS_Transform
{

    private static $instance;

    public static function url(){
        if (defined('DROIT_EL_PRO_FILE')) {
            $file = trailingslashit(plugin_dir_url( DROIT_EL_PRO_FILE )). 'modules/controls/sections/transform/';
        } else {
            $file = trailingslashit(plugin_dir_url( __FILE__ ));
        }
		return $file;
	}

	public static function dir(){
		if (defined('DROIT_EL_PRO_FILE')) {
            $file = trailingslashit(plugin_dir_path( DROIT_EL_PRO_FILE )). 'modules/controls/sections/transform/';
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

    public function init()
    {
        add_action( 'wp_enqueue_scripts', [ $this, 'script' ] );
        add_action('elementor/element/common/_section_style/after_section_end', [$this, 'register'], 1);
    }
    public function script(){
       wp_enqueue_style('droit-transform', self::url() . 'assets/transform.min.css', [], self::version());  
    }
    public function register(Element_Base $el)
    {
        $el->start_controls_section(
            '_section_css_transform',
            [
                'label' => __('CSS Transform', 'droit-elementor-addons-pro') . _droit_get_icon(),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $el->add_control(
            '_dl_transform_fx',
            [
                'label' => __('Enable', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'enable',
                'prefix_class' => 'dl-transform-',
            ]
        );

        $el->start_controls_tabs(
            '_tabs_dl_transform',
            [
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
            ]
        );

        $el->start_controls_tab(
            '_tabs_dl_transform_normal',
            [
                'label' => __('Normal', 'droit-elementor-addons-pro'),
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
            ]
        );

        $el->add_control(
            '_dl_transform_fx_translate_toggle',
            [
                'label' => __('Translate', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
            ]
        );

        $el->start_popover();

        $el->add_responsive_control(
            '_dl_transform_fx_translate_x',
            [
                'label' => __('Translate X', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_translate_toggle' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-translate-x: {{SIZE}}px;',
                ],
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_translate_y',
            [
                'label' => __('Translate Y', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_translate_toggle' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-translate-y: {{SIZE}}px;',
                ],
            ]
        );

        $el->end_popover();

        $el->add_control(
            '_dl_transform_fx_rotate_toggle',
            [
                'label' => __('Rotate', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
            ]
        );

        $el->start_popover();

        $el->add_control(
            '_dl_transform_fx_rotate_mode',
            [
                'label' => __('Mode', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'compact' => [
                        'title' => __('Compact', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-plus-circle',
                    ],
                    'loose' => [
                        'title' => __('Loose', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-minus-circle',
                    ],
                ],
                'default' => 'loose',
                'toggle' => false,
            ]
        );

        $el->add_control(
            '_dl_transform_fx_rotate_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_rotate_x',
            [
                'label' => __('Rotate X', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_rotate_toggle' => 'yes',
                    '_dl_transform_fx' => 'enable',
                    '_dl_transform_fx_rotate_mode' => 'loose',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-rotate-x: {{SIZE}}deg;',
                ],
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_rotate_y',
            [
                'label' => __('Rotate Y', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_rotate_toggle' => 'yes',
                    '_dl_transform_fx' => 'enable',
                    '_dl_transform_fx_rotate_mode' => 'loose',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-rotate-y: {{SIZE}}deg;',
                ],
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_rotate_z',
            [
                'label' => __('Rotate (Z)', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_rotate_toggle' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-rotate-z: {{SIZE}}deg;',
                ],
            ]
        );

        $el->end_popover();

        $el->add_control(
            '_dl_transform_fx_scale_toggle',
            [
                'label' => __('Scale', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
            ]
        );

        $el->start_popover();

        $el->add_control(
            '_dl_transform_fx_scale_mode',
            [
                'label' => __('Mode', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'compact' => [
                        'title' => __('Compact', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-plus-circle',
                    ],
                    'loose' => [
                        'title' => __('Loose', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-minus-circle',
                    ],
                ],
                'default' => 'loose',
                'toggle' => false,
            ]
        );

        $el->add_control(
            '_dl_transform_fx_scale_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_scale_x',
            [
                'label' => __('Scale (X)', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => .1,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_scale_toggle' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-scale-x: {{SIZE}}; --dl-scale-y: {{SIZE}};',
                ],
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_scale_y',
            [
                'label' => __('Scale Y', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => .1,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_scale_toggle' => 'yes',
                    '_dl_transform_fx' => 'enable',
                    '_dl_transform_fx_scale_mode' => 'loose',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-scale-y: {{SIZE}};',
                ],
            ]
        );

        $el->end_popover();

        $el->add_control(
            '_dl_transform_fx_skew_toggle',
            [
                'label' => __('Skew', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
            ]
        );

        $el->start_popover();

        $el->add_responsive_control(
            '_dl_transform_fx_skew_x',
            [
                'label' => __('Skew X', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_skew_toggle' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-skew-x: {{SIZE}}deg;',
                ],
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_skew_y',
            [
                'label' => __('Skew Y', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_skew_toggle' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-skew-y: {{SIZE}}deg;',
                ],
            ]
        );

        $el->end_popover();

        $el->end_controls_tab();

        $el->start_controls_tab(
            '_tabs_dl_transform_hover',
            [
                'label' => __('Hover', 'droit-elementor-addons-pro'),
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
            ]
        );

        $el->add_control(
            '_dl_transform_fx_translate_toggle_hover',
            [
                'label' => __('Translate', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
            ]
        );

        $el->start_popover();

        $el->add_responsive_control(
            '_dl_transform_fx_translate_x_hover',
            [
                'label' => __('Translate X', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_translate_toggle_hover' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-translate-x-hover: {{SIZE}}px;',
                ],
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_translate_y_hover',
            [
                'label' => __('Translate Y', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_translate_toggle_hover' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-translate-y-hover: {{SIZE}}px;',
                ],
            ]
        );

        $el->end_popover();

        $el->add_control(
            '_dl_transform_fx_rotate_toggle_hover',
            [
                'label' => __('Rotate', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
            ]
        );

        $el->start_popover();

        $el->add_control(
            '_dl_transform_fx_rotate_mode_hover',
            [
                'label' => __('Mode', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'compact' => [
                        'title' => __('Compact', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-plus-circle',
                    ],
                    'loose' => [
                        'title' => __('Loose', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-minus-circle',
                    ],
                ],
                'default' => 'loose',
                'toggle' => false,
            ]
        );

        $el->add_control(
            '_dl_transform_fx_rotate_hr_hover',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_rotate_x_hover',
            [
                'label' => __('Rotate X', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_rotate_toggle_hover' => 'yes',
                    '_dl_transform_fx' => 'enable',
                    '_dl_transform_fx_rotate_mode_hover' => 'loose',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-rotate-x-hover: {{SIZE}}deg;',
                ],
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_rotate_y_hover',
            [
                'label' => __('Rotate Y', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_rotate_toggle_hover' => 'yes',
                    '_dl_transform_fx' => 'enable',
                    '_dl_transform_fx_rotate_mode_hover' => 'loose',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-rotate-y-hover: {{SIZE}}deg;',
                ],
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_rotate_z_hover',
            [
                'label' => __('Rotate (Z)', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_rotate_toggle_hover' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-rotate-z-hover: {{SIZE}}deg;',
                ],
            ]
        );

        $el->end_popover();

        $el->add_control(
            '_dl_transform_fx_scale_toggle_hover',
            [
                'label' => __('Scale', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
            ]
        );

        $el->start_popover();

        $el->add_control(
            '_dl_transform_fx_scale_mode_hover',
            [
                'label' => __('Mode', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'compact' => [
                        'title' => __('Compact', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-plus-circle',
                    ],
                    'loose' => [
                        'title' => __('Loose', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-minus-circle',
                    ],
                ],
                'default' => 'loose',
                'toggle' => false,
            ]
        );

        $el->add_control(
            '_dl_transform_fx_scale_hr_hover',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_scale_x_hover',
            [
                'label' => __('Scale (X)', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => .1,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_scale_toggle_hover' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-scale-x-hover: {{SIZE}}; --dl-scale-y-hover: {{SIZE}};',
                ],
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_scale_y_hover',
            [
                'label' => __('Scale Y', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => .1,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_scale_toggle_hover' => 'yes',
                    '_dl_transform_fx' => 'enable',
                    '_dl_transform_fx_scale_mode_hover' => 'loose',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-scale-y-hover: {{SIZE}};',
                ],
            ]
        );

        $el->end_popover();

        $el->add_control(
            '_dl_transform_fx_skew_toggle_hover',
            [
                'label' => __('Skew', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
            ]
        );

        $el->start_popover();

        $el->add_responsive_control(
            '_dl_transform_fx_skew_x_hover',
            [
                'label' => __('Skew X', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_skew_toggle_hover' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-skew-x-hover: {{SIZE}}deg;',
                ],
            ]
        );

        $el->add_responsive_control(
            '_dl_transform_fx_skew_y_hover',
            [
                'label' => __('Skew Y', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx_skew_toggle_hover' => 'yes',
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-skew-y-hover: {{SIZE}}deg;',
                ],
            ]
        );

        $el->end_popover();

        $el->add_control(
            '_dl_transform_fx_transition_duration',
            [
                'label' => __('Transition Duration', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'separator' => 'before',
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => .1,
                    ],
                ],
                'condition' => [
                    '_dl_transform_fx' => 'enable',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dl-transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $el->end_controls_tab();

        $el->end_controls_tabs();

        $el->end_controls_section();
    }

    public static function instance(){
        if( is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

}