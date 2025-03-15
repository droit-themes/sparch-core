<?php
/**
 * @package droitelementoraddonspro
 * @developer DroitLab team
 *
 */
namespace DROIT_ELEMENTOR_PRO\Modules\Widgets\Breadcrumbs;

if (!defined('ABSPATH')) {exit;}

class Breadcrumbs_Module
{

    public static function get_name()
    {
        return 'droit-breadcrumbs';
    }

    public static function get_title()
    {
        return esc_html__('Breadcrumbs', 'droit-elementor-addons-pro');
    }

    public static function get_icon()
    {
        return 'eicon-product-breadcrumbs addons-icon';
    }

    public static function get_keywords()
    {
        return [
            'breadcrumbs',
            'bread',
            'droit',
            'dl',
            'droit elementor addons',
            'pro',
        ];
    }

    public static function get_categories()
    {
        return ['droit_addons_pro'];
    }

}
