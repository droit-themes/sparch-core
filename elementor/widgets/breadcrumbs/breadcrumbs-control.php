<?php
/**
 * @package droitelementoraddonspro
 * @developer DroitLab Team
 *
 */
namespace DROIT_ELEMENTOR_PRO\Modules\Widgets\Breadcrumbs;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;

if (!defined('ABSPATH')) {exit;}

abstract class Breadcrumbs_Control extends Widget_Base
{

    // Get Control ID
    protected function get_control_id($control_id)
    {
        return $control_id;
    }
    public function get_pro_breadcrumbs_settings($control_key)
    {
        $control_id = $this->get_control_id($control_key);
        return $this->get_settings($control_id);
    }

    //Preset
    public function _dl_pro_breadcrumbs_preset_controls()
    {
        $this->start_controls_section(
            '_dl_pr_buttons_layout_section',
            [
                'label' => __('Layout', 'droit-elementor-addons-pro'),
            ]
        );

        $this->add_control(
            '_dl_pro_breadcrumbs_skin',
            [
                'label' => esc_html__('Preset', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => apply_filters('dl_widgets/pro/breadcrumbs/control_presets', [
                    '' => 'Default',
                ]),
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    public function _dl_pro_button_controls()
    {
        $this->_dl_pro_breadcrumbs_content_controls();
    }
    public function _dl_pro_breadcrumbs_content_controls()
    {
        $this->start_controls_section(
            'section_breadcrumbs',
            [
                'label' => __('Breadcrumbs', 'droit-elementor-addons-pro'),
            ]
        );

        $this->add_control(
            'show_home',
            [
                'label' => __('Show Home', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('On', 'droit-elementor-addons-pro'),
                'label_off' => __('Off', 'droit-elementor-addons-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'home_text',
            [
                'label' => __('Home Text', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Home', 'droit-elementor-addons-pro'),
                'dynamic' => [
                    'active' => true,
                    'categories' => array(TagsModule::POST_META_CATEGORY),
                ],
                'condition' => [
                    'show_home' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'select_home_icon',
            [
                'label' => __('Home Icon', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::ICONS,
                'label_block' => false,
                'skin' => 'inline',
                'fa4compatibility' => 'home_icon',
                'default' => [
                    'value' => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_home' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'blog_text',
            [
                'label' => __('Blog Text', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Blog', 'droit-elementor-addons-pro'),
                'dynamic' => [
                    'active' => true,
                    'categories' => array(TagsModule::POST_META_CATEGORY),
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __('Alignment', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'left' => [
                        'title' => __('Left', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'droit-elementor-addons-pro'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'flex-start',
                    'center' => 'center',
                    'right' => 'flex-end',
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_separator',
            [
                'label' => __('Separator', 'droit-elementor-addons-pro'),
            ]
        );

        $this->add_control(
            'separator_type',
            [
                'label' => __('Separator Type', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'text' => __('Text', 'droit-elementor-addons-pro'),
                    'icon' => __('Icon', 'droit-elementor-addons-pro'),
                ],
            ]
        );

        $this->add_control(
            'separator_text',
           [
                'label' => __('Separator', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('>', 'droit-elementor-addons-pro'),
                'condition' => [
                    'separator_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'select_separator_icon',
            [
                'label' => __('Separator', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::ICONS,
                'label_block' => false,
                'skin' => 'inline',
                'fa4compatibility' => 'separator_icon',
                'default' => [
                    'value' => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-regular' => [
                        'circle',
                        'square',
                        'window-minimize',
                    ],
                    'fa-solid' => [
                        'angle-right',
                        'angle-double-right',
                        'caret-right',
                        'chevron-right',
                        'bullseye',
                        'circle',
                        'dot-circle',
                        'genderless',
                        'greater-than',
                        'grip-lines',
                        'grip-lines-vertical',
                        'minus',
                    ],
                ],
                'condition' => [
                    'separator_type' => 'icon',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_breadcrumbs_style',
            [
                'label' => __('Items', 'droit-elementor-addons-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'breadcrumbs_items_spacing',
            [
                'label' => __('Spacing', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs' => 'margin-left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .dl-breadcrumbs.dl-breadcrumbs-droit > li' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) a, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) span:not(.separator)' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_breadcrumbs_style');

        $this->start_controls_tab(
            'tab_breadcrumbs_normal',
            [
                'label' => __('Normal', 'droit-elementor-addons-pro'),
            ]
        );

        $this->add_control(
            'breadcrumbs_color',
            [
                'label' => __('Color', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs-crumb, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) a, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) span:not(.separator)' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .dl-breadcrumbs-crumb .dl-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'breadcrumbs_background_color',
            [
                'label' => __('Background Color', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs-crumb, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) a, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) span:not(.separator)' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'breadcrumbs_typography',
                'label' => __('Typography', 'droit-elementor-addons-pro'),
                'selector' => '{{WRAPPER}} .dl-breadcrumbs-crumb, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) a, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) span:not(.separator)',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'breadcrumbs_border',
                'label' => __('Border', 'droit-elementor-addons-pro'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .dl-breadcrumbs-crumb, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) a, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) span:not(.separator)',
            ]
        );

        $this->add_control(
            'breadcrumbs_border_radius',
            [
                'label' => __('Border Radius', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs-crumb, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) a, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) span:not(.separator)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_breadcrumbs_hover',
            [
                'label' => __('Hover', 'droit-elementor-addons-pro'),
            ]
        );

        $this->add_control(
            'breadcrumbs_color_hover',
            [
                'label' => __('Color', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs-crumb-link:hover, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .dl-breadcrumbs-crumb-link:hover .dl-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'breadcrumbs_background_color_hover',
            [
                'label' => __('Background Color', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs-crumb-link:hover, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) a:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'breadcrumbs_border_color_hover',
            [
                'label' => __('Border Color', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs-crumb-link:hover, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) a:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'breadcrumbs_padding',
            [
                'label' => __('Padding', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs-crumb, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) a, {{WRAPPER}} .dl-breadcrumbs:not(.dl-breadcrumbs-droit) span:not(.separator)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

      
        $this->start_controls_section(
            'section_separators_style',
            [
                'label' => __('Separators', 'droit-elementor-addons-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'separators_color',
           [
                'label' => __('Color', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' =>[
                    '{{WRAPPER}} .dl-breadcrumbs-separator, {{WRAPPER}} .dl-breadcrumbs .separator' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .dl-breadcrumbs-separator svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'separators_background_color',
           [
                'label' => __('Background Color', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs-separator, {{WRAPPER}} .dl-breadcrumbs .separator' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'separators_typography',
                'label' => __('Typography', 'droit-elementor-addons-pro'),
                'selector' => '{{WRAPPER}} .dl-breadcrumbs-separator, {{WRAPPER}} .dl-breadcrumbs .separator',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'separators_border',
                'label' => __('Border', 'droit-elementor-addons-pro'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .dl-breadcrumbs-separator, {{WRAPPER}} .dl-breadcrumbs .separator',
            ]
        );

        $this->add_control(
            'separators_border_radius',
            [
                'label' => __('Border Radius', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs-separator, {{WRAPPER}} .dl-breadcrumbs .separator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separators_padding',
           [
                'label' => __('Padding', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' =>['px', '%'],
                'selectors' =>[
                    '{{WRAPPER}} .dl-breadcrumbs-separator, {{WRAPPER}} .dl-breadcrumbs .separator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_current_style',
           [
                'label' => __('Current', 'droit-elementor-addons-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'current_color',
           [
                'label' => __('Color', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' =>[
                    '{{WRAPPER}} .dl-breadcrumbs-crumb-current' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'current_background_color',
           [
                'label' => __('Background Color', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs-crumb-current' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'current_typography',
                'label' => __('Typography', 'droit-elementor-addons-pro'),
                'selector' => '{{WRAPPER}} .dl-breadcrumbs-crumb-current',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'current_border',
                'label' => __('Border', 'droit-elementor-addons-pro'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .dl-breadcrumbs-crumb-current',
            ]
        );

        $this->add_control(
            'current_border_radius',
            [
                'label' => __('Border Radius', 'droit-elementor-addons-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dl-breadcrumbs-crumb-current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }
}
