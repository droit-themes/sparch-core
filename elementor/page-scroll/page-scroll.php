<?php
namespace DROIT_ELEMENTOR_PRO;

use \Elementor\Controls_Manager;
use \Elementor\Controls_Stack;
use \Elementor\Core\DynamicTags\Dynamic_CSS;
use \Elementor\Core\Files\CSS\Post;
use \Elementor\Element_Base;

defined('ABSPATH') || die();

if (!class_exists('DL_Page_Scroll')) {
    class DL_Page_Scroll
    {
        private static $instance = null;

        public static function url(){
            if (defined('DROIT_EL_PRO_FILE')) {
                $file = trailingslashit(plugin_dir_url( DROIT_EL_PRO_FILE )). 'modules/page-scroll/';
            } else {
                $file = trailingslashit(plugin_dir_url( __FILE__ ));
            }
            return $file;
        }
    
        public static function dir(){
            if (defined('DROIT_EL_PRO_FILE')) {
                $file = trailingslashit(plugin_dir_path( DROIT_EL_PRO_FILE )). 'modules/page-scroll/';
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
            add_action( 'wp_enqueue_scripts', function() {       
                wp_enqueue_style( "dl-scrolling-css", self::url() . 'js/scrolling.css' , null, self::version() );       
                wp_enqueue_style( "dl-fullpage", self::url() . 'fullpage/fullpage.css' , null, self::version() );       
                wp_enqueue_script("dl-fullpage", self::url() . 'fullpage/fullpage.js', ['jquery'], self::version(), true);
                wp_enqueue_script("dl-scroll-overflow", self::url() . 'fullpage/scroll-overflow.js', ['jquery', 'dl-fullpage'], self::version(), true);
                wp_enqueue_script("dl-scrolling", self::url() . 'js/scrolling.js', ['jquery', 'dl-fullpage'], self::version(), true);
             } 
            );

            add_action( 'elementor/documents/register_controls', [$this, '_settings'] );

            add_action('elementor/element/section/section_layout/after_section_end', [$this, '_settings_page'], 999, 1);


            add_action('elementor/frontend/section/before_render', [ $this, '__add_content_render'], 1 );

        }

        public function _settings_page($element) {
            $post_id = get_the_ID();
            $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
            $page_settings_model = $page_settings_manager->get_model( $post_id );
            $dl_onepage_enable = $page_settings_model->get_settings( 'dl_onepage_enable' );

            $id = $element->get_id();
            if ( 'section' === $element->get_name() && $dl_onepage_enable == 'yes') {

                $element->start_controls_section(
                    'dl_onepage_section',
                    [
                        'label' => __( 'One Page Scroll', 'droit-elementor-addons-pro' ) . _droit_get_icon() ,
                        'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
                        
                    ]
                );
         
                $element->add_control(
                    'dl_onepage_section_enable',
                    [
                        'label' => __( 'Enable Scroll', 'droit-elementor-addons-pro' ),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => __( 'Yes', 'droit-elementor-addons-pro' ),
                        'label_off' => __( 'No', 'droit-elementor-addons-pro' ),
                        'return_value' => 'yes',
                        'default' => 'no',
                    ]
                );
                $element->add_control(
                    'dl_onepage_anchor_id',
                    [
                        'label' => __( 'Anchor ID', 'droit-elementor-addons-pro' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => '',
                        'placeholder' => 'section1',
                        'condition' => ['dl_onepage_section_enable' => 'yes']
                    ]
                );
         
                $element->end_controls_section();

            }

        }

        public function _settings( $page  ){
            $settings = $page->get_settings_for_display();
          
            $page->start_controls_section(
                'dl_onepage_settings',
                [
                    'label' => __( 'Full Screen Slider Settings', 'droit-elementor-addons-pro' ) . _droit_get_icon(),
                    'tab' => Controls_Manager::TAB_SETTINGS,
                ]
            );
    
            $page->add_control(
                'dl_onepage_enable',
                [
                    'label' => __( 'Display Scroll', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'droit-elementor-addons-pro' ),
                    'label_off' => __( 'Off', 'droit-elementor-addons-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $page->add_control(
                'dl_onepage_navi_enable',
                [
                    'label' => __( 'Display Navigation', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'droit-elementor-addons-pro' ),
                    'label_off' => __( 'Off', 'droit-elementor-addons-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $page->add_control(
                'dl_onepage_navi_posi',
                [
                    'label' => __( 'Navigation Position', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'left' => __( 'Left', 'droit-elementor-addons-pro' ),
                        'right' => __( 'Right', 'droit-elementor-addons-pro' ),
                    ],
                    'condition' => ['dl_onepage_navi_enable' => 'yes']
                ]
            );
    
            $page->add_control(
                'dl_onepage_autoscroll',
                [
                    'label' => __( 'Auto Scroll', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'droit-elementor-addons-pro' ),
                    'label_off' => __( 'Off', 'droit-elementor-addons-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );
            $page->add_control(
                'dl_onepage_scrollbar',
                [
                    'label' => __( 'ScrollBar', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'droit-elementor-addons-pro' ),
                    'label_off' => __( 'Off', 'droit-elementor-addons-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );
            $page->add_control(
                'dl_onepage_overflow',
                [
                    'label' => __( 'Overflow Scroll', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'droit-elementor-addons-pro' ),
                    'label_off' => __( 'Off', 'droit-elementor-addons-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );
            $page->add_control(
                'dl_onepage_animation_anchor',
                [
                    'label' => __( 'Animate Anchor', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'droit-elementor-addons-pro' ),
                    'label_off' => __( 'Off', 'droit-elementor-addons-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );
            $page->add_control(
                'dl_onepage_animation_css3',
                [
                    'label' => __( 'Animate Css3', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'droit-elementor-addons-pro' ),
                    'label_off' => __( 'Off', 'droit-elementor-addons-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );
            $page->add_control(
                'dl_onepage_verticalcenter',
                [
                    'label' => __( 'Verticla Center', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'droit-elementor-addons-pro' ),
                    'label_off' => __( 'Off', 'droit-elementor-addons-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );
            $page->add_control(
                'dl_onepage_lazyLoading',
                [
                    'label' => __( 'LazyLoading', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'droit-elementor-addons-pro' ),
                    'label_off' => __( 'Off', 'droit-elementor-addons-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $page->add_control(
                'dl_onepage_menu_heading',
                [
                    'label' => __( 'Menu options', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $page->add_control(
                'dl_onepage_menu_enable',
                [
                    'label' => __( 'Enable Menu', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'droit-elementor-addons-pro' ),
                    'label_off' => __( 'Off', 'droit-elementor-addons-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $repeater = new \Elementor\Repeater();

            $repeater->add_control(
                'dlmenu_title', [
                    'label' => __( 'Title', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __( 'Menu Title' , 'droit-elementor-addons-pro' ),
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                'dlmenu_id', [
                    'label' => __('Anchor ID', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => 'section1',
                    'show_label' => true,
                ]
            );

            $repeater->add_control(
                'dlmenu_icon',
                [
                    'label' => __( 'Icon', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::ICONS,
                ]
            );

            $page->add_control(
                'dl_onepage_menus',
                [
                    'label' => __( 'Menus', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'dlmenu_title' => __( 'Section 1', 'droit-elementor-addons-pro' ),
                            'dlmenu_id' => 'section1',
                        ],
                       
                    ],
                    'title_field' => '{{{ dlmenu_title }}}',
                    'condition' => ['dl_onepage_menu_enable' => 'yes']
                ]
            );

    
            $page->add_control(
                'dl_onepage_toggle_heading',
                [
                    'label' => __( 'Preview & Next Button', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $page->add_control(
                'dl_onepage_toggle_preview', [
                    'label' => __( 'Preview Text', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __( 'Preview' , 'droit-elementor-addons-pro' ),
                    'label_block' => true,
                ]
            );
            $page->add_control(
                'dl_onepage_toggle_next', [
                    'label' => __( 'Next Text', 'droit-elementor-addons-pro' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __( 'Next' , 'droit-elementor-addons-pro' ),
                    'label_block' => true,
                ]
            );

            $page->end_controls_section();

        }
        
        public function __add_content_render( Element_Base $el ){
            $post_id = get_the_ID();
            $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
            $page_settings_model = $page_settings_manager->get_model( $post_id );
            $dl_onepage_enable = $page_settings_model->get_settings( 'dl_onepage_enable' );

            $settings = $el->get_settings_for_display();
            $id = $el->get_id();
            $sctionEnable = isset($settings['dl_onepage_section_enable']) ? $settings['dl_onepage_section_enable'] : 'no';
            $idAnchor = isset($settings['dl_onepage_anchor_id']) ? $settings['dl_onepage_anchor_id'] : $id;
            $idAnchor = empty($idAnchor) ? $id : $idAnchor;

            if ( 'section' === $el->get_name() &&  $dl_onepage_enable == 'yes' && $sctionEnable == 'yes') {

                $menuenable = $page_settings_model->get_settings( 'dl_onepage_menu_enable' );
                $menu = $page_settings_model->get_settings( 'dl_onepage_menus' );
                $navigation = $page_settings_model->get_settings( 'dl_onepage_navi_enable' );
                $navigationPosition = $page_settings_model->get_settings( 'dl_onepage_navi_posi' );
                $autoScrolling = $page_settings_model->get_settings( 'dl_onepage_autoscroll' );
                $scrollBar = $page_settings_model->get_settings( 'dl_onepage_scrollbar' );
                $scrollOverflow = $page_settings_model->get_settings( 'dl_onepage_overflow' );
                $animateAnchor = $page_settings_model->get_settings( 'dl_onepage_animation_anchor' );
                $css3 = $page_settings_model->get_settings( 'dl_onepage_animation_css3' );
                $verticalCentered = $page_settings_model->get_settings( 'dl_onepage_verticalcenter' );
                $lazyLoading = $page_settings_model->get_settings( 'dl_onepage_lazyLoading' );
                
                
                $settingsScroll['navigation'] = ($navigation == 'yes') ? true : false;
                $settingsScroll['navigationPosition'] = $navigationPosition;
                $settingsScroll['autoScrolling'] = ($autoScrolling == 'yes') ? true : false;
                $settingsScroll['scrollBar'] = ($scrollBar == 'yes') ? true : false;
                $settingsScroll['scrollOverflow'] = ($scrollOverflow == 'yes') ? true : false;
                $settingsScroll['animateAnchor'] = ($animateAnchor == 'yes') ? true : false;
                $settingsScroll['css3'] = ($css3 == 'yes') ? true : false;
                $settingsScroll['verticalCentered'] = ($verticalCentered == 'yes') ? true : false;
                $settingsScroll['lazyLoading'] = ($lazyLoading == 'yes') ? true : false;
               

                
                $attr['class'] = 'dl-section';
                if( $menuenable == 'yes'){
                    $attr['data-fp-styles'] = 'null';
                    $attr['data-anchor'] = $idAnchor;
                    $attr['data-dlmenus'] = wp_json_encode($menu);

                    $settingsScroll['menu'] = '#dlonpage-menu';
                }

                //$settingsScroll['afterResponsive'] = 'function (isResponsive) {}';
                //$settingsScroll['afterLoad'] = 'function (anchorLink, index) {}';
                $settingsScroll['sectionSelector'] = '.dl-section';
                $settingsScroll['slideSelector'] = '.dl-slide';

                $attr['data-onpage-scroll'] = wp_json_encode($settingsScroll);

                $preview = $page_settings_model->get_settings( 'dl_onepage_toggle_preview' );
                $next = $page_settings_model->get_settings( 'dl_onepage_toggle_next' );
                $attr['data-onpage-preview'] = $preview;
                $attr['data-onpage-next'] = $next;
                
                $el->add_render_attribute(
                    '_wrapper',
                    $attr
                );
            }
        }

        public static function instance(){
            if( is_null(self::$instance) ){
                self::$instance = new self();
            }
            return self::$instance;
        }
    }
}
