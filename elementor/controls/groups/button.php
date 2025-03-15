<?php
/**
 * @package droitelementoraddonspro
 * @developer DroitLab Team
 *
 */
namespace DROIT_ELEMENTOR_PRO;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Repeater;


use \Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Button extends \Elementor\Group_Control_Base {

	protected static $fields;
	
	public static function get_type() {
		return 'droit-button';
	}

	protected function init_fields() {
		$fields = [];

		$fields['background'] = [
			'label'       => esc_html__( 'Background Type', 'sparch-core' ),
			'type'        => Controls_Manager::CHOOSE,
			'options'     => [
				'classic' => [
					'title' => esc_html__( 'Classic', 'sparch-core' ),
					'icon' => 'eicon-paint-brush',
				],
				'gradient' => [
					'title' => esc_html__( 'Gradient', 'sparch-core' ),
					'icon' => 'eicon-barcode',
				],
			],
			'label_block' => false,
			'render_type' => 'ui',
		];

		$fields['color'] = [
			'label' => esc_html__( 'Color', 'sparch-core' ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'title' => esc_html__( 'Background Color', 'sparch-core' ),
			'selector_value' => 'background-color: {{VALUE}};',
			'condition' => [
				'background' => [ 'classic', 'gradient' ],
			],
		];

		$fields['color_stop'] = [
			'label' => esc_html__( 'Location', 'sparch-core' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'default' => [
				'unit' => '%',
				'size' => 0,
			],
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['color_b'] = [
			'label' => esc_html__( 'Second Color', 'sparch-core' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#f2295b',
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['color_b_stop'] = [
			'label' => esc_html__( 'Location', 'sparch-core' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'default' => [
				'unit' => '%',
				'size' => 100,
			],
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_type'] = [
			'label' => esc_html__( 'Type', 'sparch-core' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'linear' => esc_html__( 'Linear', 'sparch-core' ),
				'radial' => esc_html__( 'Radial', 'sparch-core' ),
			],
			'default' => 'linear',
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_angle'] = [
			'label' => esc_html__( 'Angle', 'sparch-core' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'deg' ],
			'default' => [
				'unit' => 'deg',
				'size' => 180,
			],
			'range' => [
				'deg' => [
					'step' => 10,
				],
			],
			'selector_value' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			'condition' => [
				'background' => [ 'gradient' ],
				'gradient_type' => 'linear',
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_position'] = [
			'label' => esc_html__( 'Position', 'sparch-core' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'center center' => esc_html__( 'Center Center', 'sparch-core' ),
				'center left' => esc_html__( 'Center Left', 'sparch-core' ),
				'center right' => esc_html__( 'Center Right', 'sparch-core' ),
				'top center' => esc_html__( 'Top Center', 'sparch-core' ),
				'top left' => esc_html__( 'Top Left', 'sparch-core' ),
				'top right' => esc_html__( 'Top Right', 'sparch-core' ),
				'bottom center' => esc_html__( 'Bottom Center', 'sparch-core' ),
				'bottom left' => esc_html__( 'Bottom Left', 'sparch-core' ),
				'bottom right' => esc_html__( 'Bottom Right', 'sparch-core' ),
			],
			'default' => 'center center',
			'selector_value' =>'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			'condition' => [
				'background' => [ 'gradient' ],
				'gradient_type' => 'radial',
			],
			'of_type' => 'gradient',
		];

		$fields['image'] = [
			'label' => esc_html__( 'Image', 'sparch-core' ),
			'type' => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'responsive' => true,
			'title' => esc_html__( 'Background Image', 'sparch-core' ),
			'selector_value' =>'background-image: url("{{URL}}");',
			'render_type' => 'template',
			'condition' => [
				'background' => [ 'classic' ],
			],
		];

		$fields['position'] = [
			'label' => esc_html__( 'Position', 'sparch-core' ),
			'type' => Controls_Manager::SELECT,
			'default' => '',
			'responsive' => true,
			'options' => [
				'' => esc_html__( 'Default', 'sparch-core' ),
				'center center' => esc_html__( 'Center Center', 'sparch-core' ),
				'center left' => esc_html__( 'Center Left', 'sparch-core' ),
				'center right' => esc_html__( 'Center Right', 'sparch-core' ),
				'top center' => esc_html__( 'Top Center', 'sparch-core' ),
				'top left' => esc_html__( 'Top Left', 'sparch-core' ),
				'top right' => esc_html__( 'Top Right', 'sparch-core' ),
				'bottom center' => esc_html__( 'Bottom Center', 'sparch-core' ),
				'bottom left' => esc_html__( 'Bottom Left', 'sparch-core' ),
				'bottom right' => esc_html__( 'Bottom Right', 'sparch-core' ),
				'initial' => esc_html__( 'Custom', 'sparch-core' ),

			],
			'selector_value' => 'background-position: {{VALUE}};',
			'condition' => [
				'background' => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['xpos'] = [
			'label' => esc_html__( 'X Position', 'sparch-core' ),
			'type' => Controls_Manager::SLIDER,
			'responsive' => true,
			'size_units' => [ 'px', 'em', '%', 'vw' ],
			'default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'tablet_default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'mobile_default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'range' => [
				'px' => [
					'min' => -800,
					'max' => 800,
				],
				'em' => [
					'min' => -100,
					'max' => 100,
				],
				'%' => [
					'min' => -100,
					'max' => 100,
				],
				'vw' => [
					'min' => -100,
					'max' => 100,
				],
			],
			'selector_value' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
			'condition' => [
				'background' => [ 'classic' ],
				'position' => [ 'initial' ],
				'image[url]!' => '',
			],
			'required' => true,
			'device_args' => [
				\Elementor\Controls_Stack::RESPONSIVE_TABLET => [
					'selector_value' => 'background-position: {{SIZE}}{{UNIT}} {{ypos_tablet.SIZE}}{{ypos_tablet.UNIT}}',
					'condition' => [
						'background' => [ 'classic' ],
						'position_tablet' => [ 'initial' ],
					],
				],
				\Elementor\Controls_Stack::RESPONSIVE_MOBILE => [
					'selector_value' => 'background-position: {{SIZE}}{{UNIT}} {{ypos_mobile.SIZE}}{{ypos_mobile.UNIT}}',
					'condition' => [
						'background' => [ 'classic' ],
						'position_mobile' => [ 'initial' ],
					],
				],
			],
		];

		$fields['ypos'] = [
			'label' => esc_html__( 'Y Position', 'sparch-core' ),
			'type' => Controls_Manager::SLIDER,
			'responsive' => true,
			'size_units' => [ 'px', 'em', '%', 'vh' ],
			'default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'tablet_default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'mobile_default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'range' => [
				'px' => [
					'min' => -800,
					'max' => 800,
				],
				'em' => [
					'min' => -100,
					'max' => 100,
				],
				'%' => [
					'min' => -100,
					'max' => 100,
				],
				'vh' => [
					'min' => -100,
					'max' => 100,
				],
			],
			'selector_value' => 'background-position: {{xpos.SIZE}}{{xpos.UNIT}} {{SIZE}}{{UNIT}}',
			'condition' => [
				'background' => [ 'classic' ],
				'position' => [ 'initial' ],
				'image[url]!' => '',
			],
			'required' => true,
			'device_args' => [
				\Elementor\Controls_Stack::RESPONSIVE_TABLET => [
					'selector_value' => 'background-position: {{xpos_tablet.SIZE}}{{xpos_tablet.UNIT}} {{SIZE}}{{UNIT}}',
					'condition' => [
						'background' => [ 'classic' ],
						'position_tablet' => [ 'initial' ],
					],
				],
				\Elementor\Controls_Stack::RESPONSIVE_MOBILE => [
					'selector_value' => 'background-position: {{xpos_mobile.SIZE}}{{xpos_mobile.UNIT}} {{SIZE}}{{UNIT}}',
					'condition' => [
						'background' => [ 'classic' ],
						'position_mobile' => [ 'initial' ],
					],
				],
			],
		];

		$fields['attachment'] = [
			'label' => esc_html__( 'Attachment', 'sparch-core' ),
			'type' => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				'' => esc_html__( 'Default', 'sparch-core' ),
				'scroll' => esc_html__( 'Scroll', 'sparch-core' ),
				'fixed' => esc_html__( 'Fixed', 'sparch-core' ),
			],
			'selector_value' => [
				'(desktop+){{SELECTOR}}' => 'background-attachment: {{VALUE}};',
			],
			'condition' => [
				'background' => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['attachment_alert'] = [
			'type' => Controls_Manager::RAW_HTML,
			'content_classes' => 'elementor-control-field-description',
			'raw' => __( 'Note: Attachment Fixed works only on desktop.', 'sparch-core' ),
			'separator' => 'none',
			'condition' => [
				'background' => [ 'classic' ],
				'image[url]!' => '',
				'attachment' => 'fixed',
			],
		];

		$fields['repeat'] = [
			'label' => esc_html__( 'Repeat', 'sparch-core' ),
			'type' => Controls_Manager::SELECT,
			'default' => '',
			'responsive' => true,
			'options' => [
				'' => esc_html__( 'Default', 'sparch-core' ),
				'no-repeat' => esc_html__( 'No-repeat', 'sparch-core' ),
				'repeat' => esc_html__( 'Repeat', 'sparch-core' ),
				'repeat-x' => esc_html__( 'Repeat-x', 'sparch-core' ),
				'repeat-y' => esc_html__( 'Repeat-y', 'sparch-core' ),
			],
			'selector_value' => 'background-repeat: {{VALUE}};',
			'condition' => [
				'background' => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['size'] = [
			'label' => esc_html__( 'Size', 'sparch-core' ),
			'type' => Controls_Manager::SELECT,
			'responsive' => true,
			'default' => '',
			'options' => [
				'' => esc_html__( 'Default', 'sparch-core' ),
				'auto' => esc_html__( 'Auto', 'sparch-core' ),
				'cover' => esc_html__( 'Cover', 'sparch-core' ),
				'contain' => esc_html__( 'Contain', 'sparch-core' ),
				'initial' => esc_html__( 'Custom', 'sparch-core' ),
			],
			'selector_value' => 'background-size: {{VALUE}};',
			'condition' => [
				'background' => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['bg_width'] = [
			'label' => esc_html__( 'Width', 'sparch-core' ),
			'type' => Controls_Manager::SLIDER,
			'responsive' => true,
			'size_units' => [ 'px', 'em', '%', 'vw' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
				'%' => [
					'min' => 0,
					'max' => 100,
				],
				'vw' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default' => [
				'size' => 100,
				'unit' => '%',
			],
			'required' => true,
			'selector_value' => 'background-size: {{SIZE}}{{UNIT}} auto',
			'condition' => [
				'background' => [ 'classic' ],
				'size' => [ 'initial' ],
				'image[url]!' => '',
			],
			'device_args' => [
				\Elementor\Controls_Stack::RESPONSIVE_TABLET => [
					'selector_value' => 'background-size: {{SIZE}}{{UNIT}} auto',
					'condition' => [
						'background' => [ 'classic' ],
						'size_tablet' => [ 'initial' ],
					],
				],
				\Elementor\Controls_Stack::RESPONSIVE_MOBILE => [
					'selector_value' => 'background-size: {{SIZE}}{{UNIT}} auto',
					'condition' => [
						'background' => [ 'classic' ],
						'size_mobile' => [ 'initial' ],
					],
				],
			],
		];
		$fields['button_border'] = [
			'label'   => esc_html__( 'Border Type', 'sparch-core' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''       => __( 'None', 'sparch-core' ),
				'solid'  => esc_html__( 'Solid', 'sparch-core' ),
				'double' => esc_html__( 'Double', 'sparch-core' ),
				'dotted' => esc_html__( 'Dotted', 'sparch-core' ),
				'dashed' => esc_html__( 'Dashed', 'sparch-core' ),
			],
			'selector_value' => 'border-style: {{VALUE}};',
		];

		$fields['button_border_width'] = [
			'label'     => esc_html__( 'Width', 'sparch-core' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'selector_value' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'condition' => [
				'button_border!' => '',
			],
		];

		$fields['button_border_color'] = [
			'label' => esc_html__( 'Color', 'sparch-core' ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selector_value' => 'border-color: {{VALUE}};',
			'condition' => [
				'button_border!' => '',
			],
		];

		$fields['button_border_radius'] = [
			'label'     => esc_html__( 'Border Radius', 'sparch-core' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selector_value' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		];
		$fields['button_padding'] = [
			'label'      => esc_html__( 'Padding', 'sparch-core' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'responsive' => true,
			'size_units' => [ 'px', '%' ],
			'selector_value' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		];
		$fields['button_margin'] = [
			'label'      => esc_html__( 'Margin', 'sparch-core' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'responsive' => true,
			'size_units' => [ 'px', '%' ],
			'selector_value' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		];
		$fields['allow_box_shadow'] = [
			'label' => esc_html__( 'Shadow', 'sparch-core' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Yes', 'sparch-core' ),
			'label_off' => esc_html__( 'No', 'sparch-core' ),
			'return_value' => 'yes',
			'separator' => 'before',
		];

		$fields['button_shadow'] = [
			'label'     => esc_html__( 'Button Shadow', 'sparch-core' ),
			'type'      => Controls_Manager::BOX_SHADOW,
			'condition' => [
				'allow_box_shadow!' => '',
			],
			'selector_value' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{button_shadow_position.VALUE}};',
		];

		$fields['button_shadow_position'] = [
			'label' => esc_html__( 'Position', 'sparch-core' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				' '     => esc_html__( 'Outline', 'sparch-core' ),
				'inset' => esc_html__( 'Inset', 'sparch-core' ),
			],
			'condition' => [
				'allow_box_shadow!' => '',
			],
			'default' => ' ',
			'render_type' => 'ui',
		];
		return $fields;
	}

	protected function prepare_fields( $fields ) {
		array_walk(
			$fields, function( &$field, $field_name ) {

				if ( in_array( $field_name, [ 'button_settings', 'popover_toggle' ] ) ) {
					return;
				}

				$selector_value = ! empty( $field['selector_value'] ) ? $field['selector_value'] : str_replace( '_', '-', $field_name ) . ': {{VALUE}};';

				$field['selectors'] = [
					'{{SELECTOR}}' => $selector_value,
				];
			}
		);

		return parent::prepare_fields( $fields );
	}

	protected function get_default_options() {
		return [
			'popover' => [
				'starter_name' => 'button_settings',
				'starter_title' => esc_html__( 'Button Setting', 'sparch-core' ),
				'settings' => [
					'render_type' => 'ui',
					'groupType' => 'button_settings',
					'global' => [
						'active' => true,
					],
				],
			],
		];
	}
}
