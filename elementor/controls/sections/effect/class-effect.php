<?php
namespace DROIT_ELEMENTOR_PRO;

use \Elementor\Controls_Manager;
use \Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

if (!class_exists('Effect')) {
    class Effect
    {

        private static $instance;
        static $should_script_enqueue = false;
        public static function url(){
            if (defined('DROIT_EL_PRO_FILE')) {
                $file = trailingslashit(plugin_dir_url( DROIT_EL_PRO_FILE )). 'modules/controls/sections/effect/';
            } else {
                $file = trailingslashit(plugin_dir_url( __FILE__ ));
            }
            return $file;
        }

        public static function dir(){
            if (defined('DROIT_EL_PRO_FILE')) {
                $file = trailingslashit(plugin_dir_path( DROIT_EL_PRO_FILE )). 'modules/controls/sections/effect/';
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
            add_action('elementor/element/common/_section_style/after_section_end', [__CLASS__, 'add_controls_section'], 1);
            add_action( 'wp_enqueue_scripts', [ __CLASS__, 'dl_pro_effect_script' ] );
        }

        public static function dl_pro_effect_script(){
            wp_enqueue_script('anime', self::url() . 'assets/anime.min.js', [], self::version(), true);
            wp_enqueue_script('anime-script', self::url() . 'assets/scripts.js', [], self::version(), true);
            
        }
        
        public static function add_controls_section(Element_Base $el)
        {
            $el->start_controls_section(
                '_droit_section_effects',
                [
                    'label' => __('Droit Effect', 'droit-elementor-addons-pro'). _droit_get_icon(),
                    'tab' => Controls_Manager::TAB_ADVANCED,
                ]
            );

            self::add_floating_effects($el);

            $el->end_controls_section();
        }

        public static function add_floating_effects(Element_Base $el)
        {
            $el->add_control(
                'dl_floating_fx',
                [
                    'label' => __('Floating Effects', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'frontend_available' => true,
                ]
            );

            $el->add_control(
                'dl_floating_fx_translate_toggle',
                [
                    'label' => __('Translate', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                    'return_value' => 'yes',
                    'frontend_available' => true,
                    'condition' => [
                        'dl_floating_fx' => 'yes',
                    ],
                ]
            );

            $el->start_popover();

            $el->add_control(
                'dl_floating_fx_translate_x',
                [
                    'label' => __('Translate X', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 0,
                            'to' => 5,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'labels' => [
                        __('From', 'droit-elementor-addons-pro'),
                        __('To', 'droit-elementor-addons-pro'),
                    ],
                    'scales' => 1,
                    'handles' => 'range',
                    'condition' => [
                        'dl_floating_fx_translate_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->add_control(
                'dl_floating_fx_translate_y',
                [
                    'label' => __('Translate Y', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 0,
                            'to' => 5,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'labels' => [
                        __('From', 'droit-elementor-addons-pro'),
                        __('To', 'droit-elementor-addons-pro'),
                    ],
                    'scales' => 1,
                    'handles' => 'range',
                    'condition' => [
                        'dl_floating_fx_translate_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->add_control(
                'dl_floating_fx_translate_duration',
                [
                    'label' => __('Duration', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10000,
                            'step' => 100,
                        ],
                    ],
                    'default' => [
                        'size' => 1000,
                    ],
                    'condition' => [
                        'dl_floating_fx_translate_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->add_control(
                'dl_floating_fx_translate_delay',
                [
                    'label' => __('Delay', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 5000,
                            'step' => 100,
                        ],
                    ],
                    'condition' => [
                        'dl_floating_fx_translate_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->end_popover();

            $el->add_control(
                'dl_floating_fx_rotate_toggle',
                [
                    'label' => __('Rotate', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                    'return_value' => 'yes',
                    'frontend_available' => true,
                    'condition' => [
                        'dl_floating_fx' => 'yes',
                    ],
                ]
            );

            $el->start_popover();

            $el->add_control(
                'dl_floating_fx_rotate_x',
                [
                    'label' => __('Rotate X', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 0,
                            'to' => 45,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => -180,
                            'max' => 180,
                        ],
                    ],
                    'labels' => [
                        __('From', 'droit-elementor-addons-pro'),
                        __('To', 'droit-elementor-addons-pro'),
                    ],
                    'scales' => 1,
                    'handles' => 'range',
                    'condition' => [
                        'dl_floating_fx_rotate_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->add_control(
                'dl_floating_fx_rotate_y',
                [
                    'label' => __('Rotate Y', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 0,
                            'to' => 45,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => -180,
                            'max' => 180,
                        ],
                    ],
                    'labels' => [
                        __('From', 'droit-elementor-addons-pro'),
                        __('To', 'droit-elementor-addons-pro'),
                    ],
                    'scales' => 1,
                    'handles' => 'range',
                    'condition' => [
                        'dl_floating_fx_rotate_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->add_control(
                'dl_floating_fx_rotate_z',
                [
                    'label' => __('Rotate Z', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 0,
                            'to' => 45,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => -180,
                            'max' => 180,
                        ],
                    ],
                    'labels' => [
                        __('From', 'droit-elementor-addons-pro'),
                        __('To', 'droit-elementor-addons-pro'),
                    ],
                    'scales' => 1,
                    'handles' => 'range',
                    'condition' => [
                        'dl_floating_fx_rotate_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->add_control(
                'dl_floating_fx_rotate_duration',
                [
                    'label' => __('Duration', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10000,
                            'step' => 100,
                        ],
                    ],
                    'default' => [
                        'size' => 1000,
                    ],
                    'condition' => [
                        'dl_floating_fx_rotate_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->add_control(
                'dl_floating_fx_rotate_delay',
                [
                    'label' => __('Delay', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 5000,
                            'step' => 100,
                        ],
                    ],
                    'condition' => [
                        'dl_floating_fx_rotate_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->end_popover();

            $el->add_control(
                'dl_floating_fx_scale_toggle',
                [
                    'label' => __('Scale', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                    'return_value' => 'yes',
                    'frontend_available' => true,
                    'condition' => [
                        'dl_floating_fx' => 'yes',
                    ],
                ]
            );

            $el->start_popover();

            $el->add_control(
                'dl_floating_fx_scale_x',
                [
                    'label' => __('Scale X', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 1,
                            'to' => 1.2,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 5,
                            'step' => .1,
                        ],
                    ],
                    'labels' => [
                        __('From', 'droit-elementor-addons-pro'),
                        __('To', 'droit-elementor-addons-pro'),
                    ],
                    'scales' => 1,
                    'handles' => 'range',
                    'condition' => [
                        'dl_floating_fx_scale_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->add_control(
                'dl_floating_fx_scale_y',
                [
                    'label' => __('Scale Y', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 1,
                            'to' => 1.2,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 5,
                            'step' => .1,
                        ],
                    ],
                    'labels' => [
                        __('From', 'droit-elementor-addons-pro'),
                        __('To', 'droit-elementor-addons-pro'),
                    ],
                    'scales' => 1,
                    'handles' => 'range',
                    'condition' => [
                        'dl_floating_fx_scale_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->add_control(
                'dl_floating_fx_scale_duration',
                [
                    'label' => __('Duration', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10000,
                            'step' => 100,
                        ],
                    ],
                    'default' => [
                        'size' => 1000,
                    ],
                    'condition' => [
                        'dl_floating_fx_scale_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->add_control(
                'dl_floating_fx_scale_delay',
                [
                    'label' => __('Delay', 'droit-elementor-addons-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 5000,
                            'step' => 100,
                        ],
                    ],
                    'condition' => [
                        'dl_floating_fx_scale_toggle' => 'yes',
                        'dl_floating_fx' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $el->end_popover();

            $el->add_control(
                'dl_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );
        }


        public static function instance(){
            if( is_null(self::$instance)){
                self::$instance = new self();
            }
            return self::$instance;
        }
    }
}
