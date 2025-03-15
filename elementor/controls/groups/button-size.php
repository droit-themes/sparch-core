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

class Button_Size extends \Elementor\Group_Control_Base {

	
	protected static $fields;
	
	private static $_scheme_fields_keys = [ 'font_family', 'font_weight' ];

	public static function get_scheme_fields_keys() {
		return self::$_scheme_fields_keys;
	}
	public static function get_type() {
		return 'droit-button-size';
	}

	protected function init_fields() {
		$fields = [];

		$fields['button_width'] = [
			'label'      => esc_html__( 'Width', 'sparch-core' ),
			'type' => Controls_Manager::SLIDER,
			'desktop_default' => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'range' => [
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
				'em' => [
					'min' => 1,
					'max' => 1000,
				],
				'%' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'responsive' => true,
			'size_units' => [ 'px', '%','em' ],
			'selector_value' => 'width: {{SIZE}}{{UNIT}}',
		];
		$fields['button_height'] = [
			'label'      => esc_html__( 'Height', 'sparch-core' ),
			'type' => Controls_Manager::SLIDER,
			'desktop_default' => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'range' => [
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
				'em' => [
					'min' => 1,
					'max' => 1000,
				],
				'%' => [
					'min' => 1,
					'max' => 1000,
				],
			],
			'responsive' => true,
			'size_units' => [ 'px', '%','em' ],
			'selector_value' => 'height: {{SIZE}}{{UNIT}}',
		];
		
		return $fields;
	}

	protected function prepare_fields( $fields ) {
		array_walk(
			$fields, function( &$field, $field_name ) {

				if ( in_array( $field_name, [ 'typography', 'popover_toggle' ] ) ) {
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

	protected function add_group_args_to_field( $control_id, $field_args ) {
		$field_args = parent::add_group_args_to_field( $control_id, $field_args );

		$field_args['groupPrefix'] = $this->get_controls_prefix();
		$field_args['groupType'] = 'button_size';

		$args = $this->get_args();

		if ( in_array( $control_id, self::get_scheme_fields_keys() ) && ! empty( $args['scheme'] ) ) {
			$field_args['scheme'] = [
				'type' => self::get_type(),
				'value' => $args['scheme'],
				'key' => $control_id,
			];
		}

		return $field_args;
	}

	protected function get_default_options() {
		return [
			'popover' => [
				'starter_name' => 'button_size',
				'starter_title' => esc_html__( 'Size', 'sparch-core' ),
				'settings' => [
					'render_type' => 'ui',
					'groupType' => 'button_size',
					'global' => [
						'active' => true,
					],
				],
			],
		];
	}
}
