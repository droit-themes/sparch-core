<?php
namespace DROIT_ELEMENTOR_PRO;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Lottie{

	private static $instance;

	public static function url(){
		if (defined('DROIT_EL_PRO_FILE')) {
			$file = trailingslashit(plugin_dir_url( DROIT_EL_PRO_FILE )). 'modules/controls/sections/lottie/';
		} else {
			$file = trailingslashit(plugin_dir_url( __FILE__ ));
		}
		return $file;
	}

	public static function dir(){
		if (defined('DROIT_EL_PRO_FILE')) {
			$file = trailingslashit(plugin_dir_path( DROIT_EL_PRO_FILE )). 'modules/controls/sections/lottie/';
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
		add_action( 'wp_enqueue_scripts', [ $this, '_script' ] );
		add_action( 'elementor/element/section/section_layout/after_section_end', [ $this, 'dlregister_controls' ], 10 );
		add_action( 'elementor/section/print_template', [ $this, 'dl_print_template' ], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'dl_before_render' ], 10, 1 );
	}
	
	public function _script() {	
		

		if ( ( function_exists( 'elementor_location_exits' ) && ( elementor_location_exits( 'archive', true ) || elementor_location_exits( 'single', true ) ) ) ) {
			wp_enqueue_style('lottie-style', self::url() . 'assets/lottie-style.css', [], self::version());
			wp_localize_script(
				'elementor-frontend',
				'dlLottie',
				array(
					'lottie_url' => esc_url( self::url() . 'assets/lottie.min.js'),
				)
			);
			
			wp_add_inline_script(
				'elementor-frontend',
				'window.scopes_array = {};
                window.backend = 0;
                jQuery( window ).on( "elementor/frontend/init", function() {
                    
                    elementorFrontend.hooks.addAction( "frontend/element_ready/section", function( $scope, $ ){
                        if ( "undefined" == typeof $scope ) {
                                return;
                        }
                        if ( $scope.hasClass( "droit_pro-lottie-yes" ) ) {
                            var id = $scope.data("id");
                            window.scopes_array[ id ] = $scope;
                        }
                        if(elementorFrontend.isEditMode()){
                            
                            var url = dlLottie.lottie_url;
                            jQuery.cachedAssets = function( url, options ) {
                                // Allow user to set any option except for dataType, cache, and url.
                                options = jQuery.extend( options || {}, {
                                    dataType: "script",
                                    cache: true,
                                    url: url
                                });
                                // Return the jqXHR object so we can chain callbacks.
                                return jQuery.ajax( options );
                            };
                            jQuery.cachedAssets( url );
                            window.backend = 1;
                        }
                    });
                });
                jQuery(document).ready(function(){
                    if ( jQuery.find( ".droit_pro-lottie-yes" ).length < 1 ) {
                        return;
                    }
                    var url = dlLottie.lottie_url;
                    
                    jQuery.cachedAssets = function( url, options ) {
                        // Allow user to set any option except for dataType, cache, and url.
                        options = jQuery.extend( options || {}, {
                            dataType: "script",
                            cache: true,
                            url: url
                        });
                        
                        // Return the jqXHR object so we can chain callbacks.
                        return jQuery.ajax( options );
                    };
                    jQuery.cachedAssets( url );
                });	'
			);
		}	
	}
	public function dlregister_controls( $element ) {

		$element->start_controls_section(
			'section_droit_pro_lottie',
			array(
				'label' => __( 'Lottie Effect', 'droit-elementor-addons-pro' ) . _droit_get_icon(),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			)
		);

		$element->add_control(
			'droit_pro_lottie_switcher',
			array(
				'label'        => __( 'Lottie Animations', 'droit-elementor-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'	=> __( 'Enable lottie animation for animate your site.', 'droit-elementor-addons-pro' ),
				'return_value' => 'yes',
				'prefix_class' => 'droit_pro-lottie-',
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'source',
			array(
				'label'   => __( 'Source', 'droit-elementor-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'url'  => __( 'External URL', 'droit-elementor-addons-pro' ),
					'file' => __( 'Media File', 'droit-elementor-addons-pro' ),
				),
				'default' => 'url',
			)
		);

		$repeater->add_control(
			'lottie_url',
			array(
				'label'       => __( 'Animation JSON URL', 'droit-elementor-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'description' => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
				'label_block' => true,
				'condition'   => array(
					'source' => 'url',
				),
			)
		);

		$repeater->add_control(
			'lottie_file',
			array(
				'label'              => __( 'Upload JSON File', 'elementor-pro' ),
				'type'               => Controls_Manager::MEDIA,
				'media_type'         => 'application/json',
				'frontend_available' => true,
				'condition'          => array(
					'source' => 'file',
				),
			)
		);

		$repeater->add_control(
			'lottie_loop',
			array(
				'label'        => __( 'Loop', 'droit-elementor-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$repeater->add_control(
			'lottie_reverse',
			array(
				'label'        => __( 'Reverse', 'droit-elementor-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
			)
		);

		$repeater->add_control(
			'lottie_speed',
			array(
				'label'   => __( 'Speed', 'droit-elementor-addons-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 0.1,
				'max'     => 3,
				'step'    => 0.1,
			)
		);

		$repeater->add_control(
			'hover_action',
			array(
				'label'   => __( 'Hover Action', 'droit-elementor-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'  => __( 'None', 'droit-elementor-addons-pro' ),
					'play'  => __( 'Play', 'droit-elementor-addons-pro' ),
					'pause' => __( 'Pause', 'droit-elementor-addons-pro' ),
				),
				'default' => 'none',
			)
		);

		$repeater->add_control(
			'start_on_visible',
			array(
				'label'        => __( 'Viewport Animation', 'droit-elementor-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __( 'Enable this option if you want the animation to start when the element is visible on the viewport', 'droit-elementor-addons-pro' ),
				'return_value' => 'true',
				'condition'    => array(
					'hover_action!'      => 'play',
					'animate_on_scroll!' => 'true',
				),
			)
		);

		$repeater->add_control(
			'animate_on_scroll',
			array(
				'label'        => __( 'Animate On Scroll', 'droit-elementor-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'condition'    => array(
					'hover_action!'     => 'play',
					'start_on_visible!' => 'true',
					'lottie_reverse!'   => 'true',
				),
			)
		);

		$repeater->add_control(
			'droit_pro_lottie_animate_speed',
			array(
				'label'     => __( 'Animate Speed', 'droit-elementor-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 4,
				),
				'range'     => array(
					'px' => array(
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'condition' => array(
					'hover_action!'     => 'play',
					'animate_on_scroll' => 'true',
					'lottie_reverse!'   => 'true',
				),
			)
		);

		$repeater->add_control(
			'droit_pro_lottie_animate_view',
			array(
				'label'     => __( 'Animate Viewport', 'droit-elementor-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'sizes' => array(
						'start' => 0,
						'end'   => 100,
					),
					'unit'  => '%',
				),
				'labels'    => array(
					__( 'Bottom', 'droit-elementor-addons-pro' ),
					__( 'Top', 'droit-elementor-addons-pro' ),
				),
				'scales'    => 1,
				'handles'   => 'range',
				'condition' => array(
					'hover_action!'     => 'play',
					'animate_on_scroll' => 'true',
					'lottie_reverse!'   => 'true',
				),
			)
		);

		$repeater->add_control(
			'lottie_renderer',
			array(
				'label'       => __( 'Render As', 'droit-elementor-addons-pro' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'svg'    => __( 'SVG', 'droit-elementor-addons-pro' ),
					'canvas' => __( 'Canvas', 'droit-elementor-addons-pro' ),
				),
				'default'     => 'svg',
				'classes'     => 'editor-pa-spacer',
				'render_type' => 'template',
				'label_block' => true,
			)
		);

		$repeater->add_responsive_control(
			'droit_pro_lottie_hor',
			array(
				'label'       => __( 'Horizontal Position (%)', 'droit-elementor-addons-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 50,
				),
				'min'         => 0,
				'max'         => 100,
				'label_block' => true,
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}%',
				),
			)
		);

		$repeater->add_responsive_control(
			'droit_pro_lottie_ver',
			array(
				'label'       => __( 'Vertical Position (%)', 'droit-elementor-addons-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 50,
				),
				'min'         => 0,
				'max'         => 100,
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}%',
				),
			)
		);

		$repeater->add_responsive_control(
			'droit_pro_lottie_size',
			array(
				'label'       => __( 'File Size', 'droit-elementor-addons-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em' ),
				'range'       => array(
					'px' => array(
						'min' => 1,
						'max' => 600,
					),
					'em' => array(
						'min' => 1,
						'max' => 60,
					),
				),
				'label_block' => true,
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.droit_pro-lottie-canvas, {{WRAPPER}} {{CURRENT_ITEM}}.droit_pro-lottie-svg svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$repeater->add_control(
			'droit_pro_lottie_opacity',
			array(
				'label'     => __( 'Opacity', 'droit-elementor-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => .1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'opacity: {{SIZE}};',
				),
			)
		);

		$repeater->add_control(
			'droit_pro_lottie_rotate',
			array(
				'label'       => __( 'Rotate', 'droit-elementor-addons-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => __( 'Set rotation value in degrees', 'droit-elementor-addons-pro' ),
				'range'       => array(
					'px' => array(
						'min' => -180,
						'max' => 180,
					),
				),
				'default'     => array(
					'size' => 0,
				),
				'selectors'   => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'transform: rotate({{SIZE}}deg)',
				),
			)
		);

		$repeater->add_control(
			'droit_pro_lottie_background',
			array(
				'label'     => __( 'Background Color', 'droit-elementor-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'droit_pro_lottie_border',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			)
		);

		$repeater->add_control(
			'droit_pro_lottie_radius',
			array(
				'label'      => __( 'Border Radius', 'droit-elementor-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'droit_pro_lottie_shadow',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			)
		);

		$repeater->add_responsive_control(
			'droit_pro_lottie_padding',
			array(
				'label'      => __( 'Padding', 'droit-elementor-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$repeater->add_control(
			'droit_pro_lottie_parallax',
			array(
				'label'       => __( 'Scroll Parallax', 'droit-elementor-addons-pro' ),
				'description' => __( 'Enable or disable vertical scroll parallax', 'droit-elementor-addons-pro' ),
				'type'        => Controls_Manager::SWITCHER,
			)
		);

		$repeater->add_control(
			'droit_pro_lottie_parallax_direction',
			array(
				'label'     => __( 'Direction', 'droit-elementor-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'up'   => __( 'Up', 'droit-elementor-addons-pro' ),
					'down' => __( 'Down', 'droit-elementor-addons-pro' ),
				),
				'default'   => 'down',
				'condition' => array(
					'droit_pro_lottie_parallax' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'droit_pro_lottie_parallax_speed',
			array(
				'label'     => __( 'Speed', 'droit-elementor-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 4,
				),
				'range'     => array(
					'px' => array(
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'condition' => array(
					'droit_pro_lottie_parallax' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'droit_pro_lottie_parallax_view',
			array(
				'label'     => __( 'Viewport', 'droit-elementor-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'sizes' => array(
						'start' => 0,
						'end'   => 100,
					),
					'unit'  => '%',
				),
				'labels'    => array(
					__( 'Bottom', 'droit-elementor-addons-pro' ),
					__( 'Top', 'droit-elementor-addons-pro' ),
				),
				'scales'    => 1,
				'handles'   => 'range',
				'condition' => array(
					'droit_pro_lottie_parallax' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'droit_pro_lottie_zindex',
			array(
				'label'       => __( 'z-index', 'droit-elementor-addons-pro' ),
				'description' => __( 'Set z-index for the current layer', 'droit-elementor-addons-pro' ),
				'type'        => Controls_Manager::NUMBER,
				'classes'     => 'editor-pa-spacer',
				'default'     => 2,
				'selectors'   => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'show_layer_on',
			array(
				'label'       => __( 'Show Layer On', 'droit-elementor-addons-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => array(
					'desktop' => __( 'Desktop', 'droit-elementor-addons-pro' ),
					'tablet'  => __( 'Tablet', 'droit-elementor-addons-pro' ),
					'mobile'  => __( 'Mobile', 'droit-elementor-addons-pro' ),
				),
				'default'     => array( 'desktop', 'tablet', 'mobile' ),
				'multiple'    => true,
				'separator'   => 'before',
				'label_block' => true,
			)
		);

		$element->add_control(
			'droit_pro_lottie_repeater',
			array(
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => false,
				'condition'     => array(
					'droit_pro_lottie_switcher' => 'yes',
				),
			)
		);

		$element->end_controls_section();
	}
	public function dl_print_template( $template, $widget ) {

		if ( $widget->get_name() !== 'section' ) {
			return $template;
		}
		$old_template = $template;
		ob_start();
		?>
		<# if( 'yes' === settings.droit_pro_lottie_switcher ) {

			view.addRenderAttribute( 'lottie_data', 'id', 'droit_pro-lottie-' + view.getID() );
			view.addRenderAttribute( 'lottie_data', 'class', 'droit_pro-lottie-wrapper' );

			view.addRenderAttribute( 'lottie_data', 'data-pa-lottie', JSON.stringify( settings.droit_pro_lottie_repeater ) );

		#>
			<div {{{ view.getRenderAttributeString( 'lottie_data' ) }}}></div>
		<# } #>
		<?php
		$slider_content = ob_get_contents();
		ob_end_clean();
		$template = $slider_content . $old_template;
		return $template;
	}
	public function dl_before_render( $element ) {

		$data = $element->get_data();

		$type = $data['elType'];

		if ( 'section' !== $type ) {
			return;
		}

		$settings = $element->get_settings_for_display();

		$lottie = $settings['droit_pro_lottie_switcher'];

		if ( 'yes' !== $lottie ) {
			return;
		}

		$repeater = $settings['droit_pro_lottie_repeater'];

		if ( ! count( $repeater ) ) {
			return;
		}

		$layers = array();

		foreach ( $repeater as $layer ) {

			array_push( $layers, $layer );

		}

		$lottie_settings = array(
			'layers' => $layers,
		);

		$element->add_render_attribute( '_wrapper', 'data-pa-lottie', wp_json_encode( $lottie_settings ) );
	}
	public static function instance(){
        if( is_null(self::$instance) ){
            self::$instance = new self;
        }
        return self::$instance;
    }
}
