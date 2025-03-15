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

class Button_Hover_Advanced_Second extends \Elementor\Group_Control_Base {

	protected static $fields;
	
	public static function get_type() {
		return 'button-hover-advanced-second';
	}

	protected function init_fields() {
		$fields = [];

		$fields['button_direction'] = [
			'label' => esc_html__('Direction', 'sparch-core'),
			'type' => Controls_Manager::CHOOSE,
			'options'     => [
				'left' => [
					'title' => esc_html__( 'Left', 'sparch-core' ),
					'icon' => 'eicon-h-align-left',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'sparch-core' ),
					'icon' => 'eicon-h-align-right',
				],
				'top' => [
					'title' => esc_html__( 'Top', 'sparch-core' ),
					'icon' => 'eicon-v-align-top',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'sparch-core' ),
					'icon' => ' eicon-v-align-bottom',
				],
			],
			'default' => 'left',
			'render_type' => 'ui',
			'selector_value' => 'transform-origin: {{VALUE}};',
		];
		$fields['button_transform'] = [
			'label' => esc_html__( 'Transform', 'sparch-core' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'range' => [
				'px' => [
					'max' => 10,
					'step' => 0.1,
				],
			],
			'default' => [
				'unit' => '%',
				'size' => 1,
			],
			'render_type' => 'ui',
			'condition' => [
				'button_direction' => [ 'left', 'right' ],
			],
			'selector_value' => 'transform: scaleX({{SIZE}});',
		];
		$fields['button_transform_s'] = [
			'label' => esc_html__( 'Transform', 'sparch-core' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'range' => [
				'px' => [
					'max' => 10,
					'step' => 0.1,
				],
			],
			'default' => [
				'unit' => '%',
				'size' => 0,
			],
			'render_type' => 'ui',
			'condition' => [
				'button_direction' => [ 'top', 'bottom' ],
			],
			'selector_value' => 'transform: scaleY({{SIZE}});',
		];
		return $fields;
	}

	protected function prepare_fields( $fields ) {
		array_walk(
			$fields, function( &$field, $field_name ) {

				if ( in_array( $field_name, [ 'after_hover', 'popover_toggle' ] ) ) {
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
				'starter_name' => 'after_hover',
				'starter_title' => esc_html__( 'After Hover', 'sparch-core' ),
				'settings' => [
					'render_type' => 'ui',
					'groupType' => 'after_hover',
					'global' => [
						'active' => true,
					],
				],
			],
		];
	}
}
