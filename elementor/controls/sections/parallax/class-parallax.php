<?php
namespace DROIT_ELEMENTOR_PRO;

use \Elementor\Utils;
use \Elementor\Repeater;
use \Elementor\Controls_Manager;
use \Elementor\Control_Media;
use \Elementor\Controls_Stack;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Parallax{

	private static $instance;

	public static function url(){
        if (defined('DROIT_EL_PRO_FILE')) {
            $file = trailingslashit(plugin_dir_url( DROIT_EL_PRO_FILE )). 'modules/controls/sections/parallax/';
        } else {
            $file = trailingslashit(plugin_dir_url( __FILE__ ));
        }
		return $file;
	}

	public static function dir(){
		if (defined('DROIT_EL_PRO_FILE')) {
            $file = trailingslashit(plugin_dir_path( DROIT_EL_PRO_FILE )). 'modules/controls/sections/parallax/';
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

	public function init() {
		add_action('wp_head', [$this, 'inline_script']);
		add_action('wp_enqueue_scripts', [$this, 'register_frontend_scripts']);
		add_action('elementor/frontend/before_enqueue_scripts', [$this, 'editor_scripts'], 99);
		add_action( 'elementor/element/section/section_layout/after_section_end', [$this, 'droitregister_controls' ], 10 );
	}
	public function register_frontend_scripts() {
		wp_enqueue_style( 'dladdons-parallax-style', self::url() . 'assets/css/style.css' , null, self::version() );
		wp_enqueue_script( 'jarallax', self::url() . 'assets/js/jarallax.js', array('jquery'), self::version(), false );
        wp_enqueue_script( 'wow', self::url() . 'assets/js/wow.min.js', array('jquery'), self::version(), false );
		wp_enqueue_script( 'tweenmax', self::url() . 'assets/js/TweenMax.min.js', array('jquery'), self::version(), true );
        wp_enqueue_script( 'jquery-easing', self::url() . 'assets/js/jquery.easing.1.3.js', array('jquery'), self::version(), true );
		wp_enqueue_script( 'tilt', self::url() . 'assets/js/tilt.jquery.min.js', array('jquery'), self::version(), true );
		wp_enqueue_script( 'magician', self::url() . 'assets/js/magician.js', array('jquery'), self::version(), true );
	}

	public function editor_scripts(){
		wp_enqueue_script( 'dladdons-parallax-section-init', self::url() . 'assets/js/scripts.js', array( 'jquery', 'elementor-frontend' ), self::version(), true );
	}

	public function inline_script(){
		echo '
			<script type="text/javascript">
				var dladdons_module_parallax_url = "'.self::url().'"
			</script>
		';
	}
	public function droitregister_controls($control)
    {
        $control->start_controls_section(
            'droit_section_parallax',
            [
                'label' => __( 'Parallax Effect', 'droit-elementor-addons-pro' ) . _droit_get_icon(),
                'tab' => Controls_Manager::TAB_LAYOUT,
            ]
        );

        $control->add_control(
			'droit_section_parallax_multi',
			[
				'label'       => __( 'Type', 'droit-elementor-addons-pro' ),
				'type'        => Controls_Manager::SELECT,
                'frontend_available' => true,
				'options'     => [
					''          => __( 'Select', 'droit-elementor-addons-pro' ),
                    'multilayer'          => __( 'Multi Layer', 'droit-elementor-addons-pro' ),
                ],
				'label_block' => 'true',
				
			]
		);
       
        $repeater = new Repeater();
        $repeater->add_control(
	        'parallax_style',
	        [   
	            'label' => esc_html__('Type', 'droit-elementor-addons-pro'),
	            'type' => Controls_Manager::CHOOSE,
	            'label_block' => false,
	            'options' => [
	                'mousemove' => [
	                    'title' => esc_html__('Mouse Track', 'droit-elementor-addons-pro'),
	                    'icon' => 'eicon-cursor-move',
	                ],
	                'onscroll' => [
	                    'title' => esc_html__('On Scroll', 'droit-elementor-addons-pro'),
	                    'icon' => 'eicon-scroll',
	                ],
	                'tilt' => [
	                    'title' => esc_html__('Tilt Effect', 'droit-elementor-addons-pro'),
	                    'icon' => 'eicon-parallax',
	                ],
	            ],
	            'default' => 'mousemove',
	        ]
	    );
        $repeater->add_control(
            'item_source',
            [
                'label' => esc_html__( 'Item source', 'droit-elementor-addons-pro'  ),
                'type' => Controls_Manager::HIDDEN,
                'label_block' => false,
                'toggle' => false,
                'default' => 'image',
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',

            ]
        );
        $repeater->add_control(
            'image',[
                'label' => esc_html__('Choose Image', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::MEDIA,
                'condition' => [
                    'item_source' => 'image',
                ],
            ]
        );

        $repeater->add_control(
			'width_type',
			[
				'label'       => __( 'Image Width', 'droit-elementor-addons-pro' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on' => __( 'Custom', 'droit-elementor-addons-pro' ),
				'label_off' => __( 'Auto', 'droit-elementor-addons-pro' ),
				'return_value' => 'custom',
				'default' => '',
			]
		);
        $repeater->add_responsive_control(
            'custom_width',
            [
                'label' => esc_html__( 'Custom Width', 'droit-elementor-addons-pro'  ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'width_type' => 'custom',
                ],
                'size_units' => [ 'px', '%', 'vw' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .dladdons-parallax-graphic' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'source_rotate', [
                'label' => esc_html__('Rotate', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'deg' => [
                        'min' => -180,
                        'max' => 180,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 0,
                ],
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}} .dladdons-parallax-graphic" => 'transform: rotate({{SIZE}}{{UNIT}})',
                ],

            ]
        );

        $repeater->add_responsive_control(
			'parallax_blur_effect',
			[
				'label' => esc_html__( 'Blur', 'droit-elementor-addons-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => .5,
					],
					'rem' => [
						'min' => 0,
                        'max' => 2,
                        'step' => .1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .dladdons-parallax-graphic' => 'filter: blur({{SIZE}}{{UNIT}});',
                ],
			]
        );
        $repeater->add_control(
            'pos_x_y',
            [
                'label'       => __( 'Position', 'droit-elementor-addons-pro' ),
                'type'        => Controls_Manager::SWITCHER,
                'description' => __( 'Set the file position for the layer background', 'droit-elementor-addons-pro' ),
                'label_on' => __( 'Left', 'droit-elementor-addons-pro' ),
                'label_off' => __( 'Right', 'droit-elementor-addons-pro' ),
                'return_value' => 'left',
                'default' => 'left',
            ]
        );
        $repeater->add_responsive_control(
            'pos_x', [
                'label' => esc_html__('Horizontal Position Left', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'description' => __( 'Set the horizontal position for the layer background, default: 10%', 'droit-elementor-addons-pro' ),
                'size_units' => ['%','px'],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => -10000,
                        'max' => 10000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10,
                ],
                'condition' => [
                    'pos_x_y' => 'left',
                ],
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}}.droit-section-parallax-layer" => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'pos_r', [
                'label' => esc_html__('Horizontal Position Right', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'description' => __( 'Set the horizontal position for the layer background, default: 10%', 'droit-elementor-addons-pro' ),
                'size_units' => ['%','px'],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => -10000,
                        'max' => 10000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10,
                ],
                'condition' => [
                    'pos_x_y' => '',
                ],
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}}.droit-section-parallax-layer" => 'right: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'pos_y',[
                'label' => esc_html__('Vertical Position', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'description' => __( 'Set the vertical position for the layer background, default: 10%', 'droit-elementor-addons-pro' ),
                'size_units' => ['%','px'],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 300,
                    ],
                    'px' => [
                        'min' => -10000,
                        'max' => 10000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10,
                ],
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}}.droit-section-parallax-layer" => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $repeater->add_control(
            'item_opacity',
            [
                'label' => esc_html__( 'Opacity', 'droit-elementor-addons-pro'  ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Set the layer opacity', 'droit-elementor-addons-pro' ),
                'default' => '1',
                'min' => 0,
                'step' => 1,
                'render_type' => 'none',
                'frontend_available' => true,
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}}" => 'opacity:{{UNIT}}'
                ],
            ]
        );
        $repeater->add_control(
            '_mousemove_scroll_enable',
            [
                'label'       => __( 'Parallax Scroll', 'droit-elementor-addons-pro' ),
                'type'        => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'droit-elementor-addons-pro' ),
                'label_off' => __( 'No', 'droit-elementor-addons-pro' ),
                'return_value' => 'yes',
                'default' => '',
                'frontend_available' => true,
                'render_type' => 'ui',
                'separator' => 'before',
                'condition' => [
                    'parallax_style' => 'mousemove'
                ],
            ]
        );
        $repeater->add_control(
            '_mousemove_parallax_transform', [
                'label' => esc_html__( 'Parallax Style', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::SELECT,
                'description' => __( 'Choose parallax style', 'droit-elementor-addons-pro' ),
                'default' => '',
                'options' => [
                    'translateX' => esc_html__( 'X axis', 'droit-elementor-addons-pro' ),
                    'translateY' => esc_html__( 'Y axis', 'droit-elementor-addons-pro' ),
                    'rotate' => esc_html__( 'rotate', 'droit-elementor-addons-pro' ),
                    'rotateX' => esc_html__( 'rotateX', 'droit-elementor-addons-pro' ),
                    'rotateY' => esc_html__( 'rotateY', 'droit-elementor-addons-pro' ),
                    'scale' => esc_html__( 'scale', 'droit-elementor-addons-pro' ),
                    'scaleX' => esc_html__( 'scaleX', 'droit-elementor-addons-pro' ),
                    'scaleY' => esc_html__( 'scaleY', 'droit-elementor-addons-pro' ),
                ],
                'condition' => [
                    '_mousemove_scroll_enable' => 'yes',
                    'parallax_style' => 'mousemove'
                ],
            ]
        );
        $repeater->add_control(
            '_mousemove_parallax_transform_value', [
                'label' => esc_html__( 'Parallax Transition ', 'droit-elementor-addons-pro' ),
                'description' => __( 'Set the transition value, default 300', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '300',
                'condition' => [
                '_mousemove_scroll_enable' => 'yes',
                    'parallax_style' => 'mousemove'
                ]
            ]
        );
        $repeater->add_control(
            '_mousemove_smoothness', [
                'label' => esc_html__( 'Speed', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Set the speed, default 500.', 'droit-elementor-addons-pro' ),
                'default' => '500',
                'min' => 0,
                'max' => 1000,
                'step' => 100,
                'condition' => [
                    '_mousemove_scroll_enable' => 'yes',
                    'parallax_style' => 'mousemove'
                ]
            ]
        );
        $repeater->add_control(
            '_mousemove_offsettop',[
                'label' => esc_html__( 'Offset Top', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Set the offset top, default 0', 'droit-elementor-addons-pro' ),
                'default' => '0',
                'condition' => [
                '_mousemove_scroll_enable' => 'yes',
                    'parallax_style' => 'mousemove'
                ]
            ]
        );
        $repeater->add_control(
            '_mousemove_offsetbottom', [
                'label' => esc_html__( 'Offset Bottom', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Set the offset bottom, default 0', 'droit-elementor-addons-pro' ),
                'default' => '0',
                'separator' => 'after',
                'condition' => [
                '_mousemove_scroll_enable' => 'yes',
                    'parallax_style' => 'mousemove'
                ]
            ]
        );
        $repeater->add_control(
            'parallax_speed', [
                'label' => esc_html__('Speed', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Set the speed on mouse trac, default 50', 'droit-elementor-addons-pro' ),
                'default' => 50,
                'min' => 10,
                'max' => 150,
                'condition' => [
                    'parallax_style' => 'mousemove',
                ]
            ]
        );

        $repeater->add_control(
            'parallax_transform', [
                'label' => esc_html__( 'Parallax Style', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::SELECT,
                'description' => __( 'Choose parallax style', 'droit-elementor-addons-pro' ),
                'default' => 'translateY',
                'options' => [
                    'translateX' => esc_html__( 'X axis', 'droit-elementor-addons-pro' ),
                    'translateY' => esc_html__( 'Y axis', 'droit-elementor-addons-pro' ),
                    'rotate' => esc_html__( 'rotate', 'droit-elementor-addons-pro' ),
                    'rotateX' => esc_html__( 'rotateX', 'droit-elementor-addons-pro' ),
                    'rotateY' => esc_html__( 'rotateY', 'droit-elementor-addons-pro' ),
                    'scale' => esc_html__( 'scale', 'droit-elementor-addons-pro' ),
                    'scaleX' => esc_html__( 'scaleX', 'droit-elementor-addons-pro' ),
                    'scaleY' => esc_html__( 'scaleY', 'droit-elementor-addons-pro' ),
                ],
                'condition' => [
                    'parallax_style' => 'onscroll'
                ],
            ]
        );
        $repeater->add_control(
            'parallax_transform_value', [
                'label' => esc_html__( 'Parallax Transition ', 'droit-elementor-addons-pro' ),
                'description' => __( 'Set the transition value, default 300', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '300',
                'condition' => [
                    'parallax_style' => 'onscroll'
                ]
            ]
        );
        $repeater->add_control(
            'smoothness', [
                'label' => esc_html__( 'Speed', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Set the speed, default 500.', 'droit-elementor-addons-pro' ),
                'default' => '500',
                'min' => 0,
                'max' => 1000,
                'step' => 100,
                'condition' => [
                    'parallax_style' => 'onscroll'
                ]
            ]
        );
        $repeater->add_control(
            'offsettop',[
                'label' => esc_html__( 'Offset Top', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Set the offset top, default 0', 'droit-elementor-addons-pro' ),
                'default' => '0',
                'condition' => [
                    'parallax_style' => 'onscroll'
                ]
            ]
        );
        $repeater->add_control(
            'offsetbottom', [
                'label' => esc_html__( 'Offset Bottom', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Set the offset bottom, default 0', 'droit-elementor-addons-pro' ),
                'default' => '0',
                'condition' => [
                    'parallax_style' => 'onscroll'
                ]
            ]
        );
        $repeater->add_control(
            'maxtilt',[
                'label' => esc_html__( 'MaxTilt', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Set the MaxTilt, default 20', 'droit-elementor-addons-pro' ),
                'default' => '20',
                'condition' => [
                    'parallax_style' => 'tilt',
                ]
            ]
        );
        $repeater->add_control(
            'scale',[
                'label' => esc_html__( 'Image Scale', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Set the image scale, default 1', 'droit-elementor-addons-pro' ),
                'default' => '1',
                'condition' => [
                    'parallax_style' => 'tilt',
                ]
            ]
        );
        $repeater->add_control(
            'disableaxis', [
                'label' => esc_html__( 'Disable Axis', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::SELECT,
                'description' => __( 'Choose the disable axis.', 'droit-elementor-addons-pro' ),
                'default' => '',
                'options' => [
                    '' => esc_html__( 'None', 'droit-elementor-addons-pro' ),
                    'x' => esc_html__( 'X axis', 'droit-elementor-addons-pro' ),
                    'y' => esc_html__( 'Y axis', 'droit-elementor-addons-pro' ),
                ],

                'condition' => [
                    'parallax_style' => 'tilt',
                ]
            ]
        );
        $repeater->add_control(
            'wow_enable',
            [
                'label'       => __( 'Enable Wow', 'droit-elementor-addons-pro' ),
                'type'        => Controls_Manager::SWITCHER,
                // 'frontend_available' => true,
                // 'render_type' => 'ui',
                'label_on' => __( 'Yes', 'droit-elementor-addons-pro' ),
                'label_off' => __( 'No', 'droit-elementor-addons-pro' ),
                'return_value' => 'enable',
                'default' => '',
            ]
        );
        $repeater->add_control(
            'wow_animation',
            [
                'label' => esc_html__( 'Wow Animation', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::ANIMATION,
                'frontend_available' => true,
                'render_type' => 'ui',
                'default' => 'fadeIn',
                'condition' => [
                    'wow_enable' => 'enable',
                ]  
            ]
         );
   
        $repeater->add_control(
            'wow_delay',
            [
                'label' => esc_html__( 'Wow Delay', 'droit-elementor-addons-pro' ) . ' (ms)',
                'type' => Controls_Manager::NUMBER,
                // 'frontend_available' => true,
                // 'render_type' => 'ui',
                'default' => '',
                'min' => 1,
                'step' => 100,
                'condition' => [
                    'wow_enable' => 'enable',
                    'wow_animation!' => '',
                ],
            ]
        );
        $repeater->add_control(
            'wow_mobile',
            [
                'label'       => __( 'Wow Mobile', 'droit-elementor-addons-pro' ),
                'type'        => Controls_Manager::SWITCHER,
                // 'frontend_available' => true,
                // 'render_type' => 'ui',
                'label_on' => __( 'Yes', 'droit-elementor-addons-pro' ),
                'label_off' => __( 'No', 'droit-elementor-addons-pro' ),
                'return_value' => 'yes',
                'default' => 'yes',
                 'condition' => [
                    'wow_enable' => 'enable',
                ],
            ]
        );
        $repeater->add_control(
            '_anim_enable',
            [
                'label'       => __( 'Enable Animation', 'droit-elementor-addons-pro' ),
                'type'        => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'droit-elementor-addons-pro' ),
                'label_off' => __( 'No', 'droit-elementor-addons-pro' ),
                'return_value' => 'yes',
                'default' => '',
                'frontend_available' => true,
                'render_type' => 'ui',
            ]
        );
         $repeater->add_control(
            '_parallax_animation',
            [
                'label' => esc_html__( 'Animation', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::ANIMATION,
                'frontend_available' => true,
                'render_type' => 'ui',
                'default' => '',
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}} .dladdons-parallax-graphic" => '-webkit-animation-name:{{UNIT}}',
                    "{{WRAPPER}} {{CURRENT_ITEM}} .dladdons-parallax-graphic" => 'animation-name:{{UNIT}}',
                ],
                'condition' => [
                    '_anim_enable' => 'yes'
                ],
            ]
         );
         $repeater->add_control(
            'animation_speed',
            [
                'label' => esc_html__( 'Animation speed', 'droit-elementor-addons-pro' ) . ' (s)',
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'default' => '5',
                'min' => 1,
                'step' => 100,
                'render_type' => 'ui',
                'frontend_available' => true,
                'condition' => [
                    '_anim_enable' => 'yes',
                    '_parallax_animation!' => '',
                ],
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}} .dladdons-parallax-graphic" => '-webkit-animation-duration:{{UNIT}}s',
                    "{{WRAPPER}} {{CURRENT_ITEM}} .dladdons-parallax-graphic" => 'animation-duration:{{UNIT}}s'
                ],
            ]
        );
        $repeater->add_control(
            'animation_iteration_count',
            [
                'label' => esc_html__( 'Animation Iteration Count', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::SELECT,
                'frontend_available' => true,
                'default' => 'infinite',
                'options' => [
                    'infinite' => esc_html__( 'Infinite', 'droit-elementor-addons-pro' ),
                    'unset' => esc_html__( 'Unset', 'droit-elementor-addons-pro' ),
                ],
                'condition' => [
                    '_anim_enable' => 'yes',
                    '_parallax_animation!' => '',
                ],
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}} .dladdons-parallax-graphic" => 'animation-iteration-count:{{UNIT}}'
                ],
            ]
        );
        $repeater->add_control(
            'zindex',   [
                'label' => esc_html__('z-index', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'description' => __( 'Set z-index for the current layer, default 5', 'droit-elementor-addons-pro' ),
                'default' => esc_html__('5', 'droit-elementor-addons-pro'),
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}}" => 'z-index: {{UNIT}}',
                ],
            ]
        );
        $repeater->add_control(
            'parallax_on_mobile',
            [
                'label'       => __( 'Parallax On Mobile', 'droit-elementor-addons-pro' ),
                'type'        => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'droit-elementor-addons-pro' ),
                'label_off' => __( 'No', 'droit-elementor-addons-pro' ),
                'return_value' => 'mobile',
                'default' => 'mobile',
            ]
        );
        $control->add_control(
            'droit_section_parallax_multi_items',
            [
                'label' => esc_html__( 'Parallax', 'droit-elementor-addons-pro' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'frontend_available' => true,
                'title_field' => '{{{ parallax_style }}}',
                'condition' => [
                    'droit_section_parallax_multi' => 'multilayer',
                ],

            ]
        );
        
        $control->add_control(
            'droit_section_parallax_overflow',
            [
                'label' => esc_html__('Section Overflow', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::CHOOSE,
				'default' => 'visible',
                'options' => [
                    'visible' => [
                        'title' => esc_html__('Visible', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-preview-medium',
                    ],
                    'hidden' => [
                        'title' => esc_html__('Hidden', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-help-o',
                    ],
                ], 
                'selectors' => [
                    "{{WRAPPER}}" => 'overflow: {{VALUE}} !important'
                ]
            ]
        );

        $control->end_controls_section();
    }

	public static function instance(){
        if( is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
}
