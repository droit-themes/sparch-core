<?php
/**
 * @package droitelementoraddonspro
 * @developer DroitLab Team
 *
 */
namespace DROIT_ELEMENTOR_PRO;

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Position extends \Elementor\Group_Control_Base {

	protected static $fields;
	
	public static function get_type() {
		return 'droit-position';
	}

	protected function init_fields() {
		$fields = [];

		$fields['box_horizontal'] = [
			'label' => esc_html__( 'Horizontal', 'sparch-core' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px','%', 'em', 'rem' ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                ],
                '%' => [
                    'min' => -1000,
                    'max' => 1000,
                ],
                'em' => [
                    'min' => -1000,
                    'max' => 1000,
                ],
                'rem' => [
                    'min' => -1000,
                    'max' => 1000,
                ],
            ],
            'responsive' => true,
            'render_type' => 'ui',
            'selector_value' => 'transform: translate();',
		];

		$fields['box_vertical'] = [
			'label' => esc_html__( 'Vertical', 'sparch-core' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px','%', 'em', 'rem' ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                ],
                '%' => [
                    'min' => -1000,
                    'max' => 1000,
                ],
                'em' => [
                    'min' => -1000,
                    'max' => 1000,
                ],
                'rem' => [
                    'min' => -1000,
                    'max' => 1000,
                ],
            ],
            
            'render_type' => 'ui',
            'responsive' => true,
			'selector_value' => 'transform: translate({{SIZE}}{{UNIT}}, {{box_horizontal.SIZE}}{{box_horizontal.UNIT}});',
		];
		
		return $fields;
	}

	protected function prepare_fields( $fields ) {
		array_walk(
			$fields, function( &$field, $field_name ) {

				if ( in_array( $field_name, [ 'box_position_type', 'popover_toggle' ] ) ) {
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
				'starter_name' => 'box_position_type',
				'starter_title' => esc_html__( 'Position', 'sparch-core' ),
				'settings' => [
					'render_type' => 'ui',
					'groupType' => 'box_position_type',
					'global' => [
						'active' => true,
					],
				],
			],
		];
	}
}
