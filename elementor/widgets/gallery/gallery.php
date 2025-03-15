<?php
namespace Elementor;

use \WP_Query;


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class droit_portfolio_gallery
 * @package droit_portfolioCore\Widgets
 */
class DRTH_ESS_Gallery extends Widget_Base {

    public function get_name() {
        return 'droit-gallery-theme';
    }

    public function get_title() {
        return __( 'Sparch Gallery', 'droit_gallery' );
    }

    public function get_icon() {
        return 'dlicons-blog-post';
    }

    public function get_categories() {
        return [ 'drth_custom_theme' ];
    }


    public function get_style_depends() {
        return ['droit-partner-style'];
    }

	public function get_script_depends(){
		return ['droit-gallery-script'];
	}


    protected function register_controls() {


	    $pricing_repeater = new \Elementor\Repeater();
        // -------------------------------------------- Filtering
        $this->start_controls_section(
            'droit_pricing_section', [
                'label' => __( 'gallery Design', 'sparch-core' ),

            ]
        );
		$this->add_control(
            '_sparch_blog_skin',
            [
                'label' => esc_html__( 'Design Format', 'sparch-core' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options'   => [
                    '_skin_1' => 'Style 01',
                    '_skin_2' => 'Style 02',
                ],
                'default' => '_skin_1'
            ]
        );
    $this->end_controls_section();

    //---------------- Style Section --------------- // 

    $this->start_controls_section(
        'gallery_gallery', [
            'label' => __( 'Hover Gallery', 'kidzo-core' ),
        ]
    );

    /// --------------- gallery ----------------
    $image_fields = new \Elementor\Repeater();

    $image_fields->add_control(
        'gallery_title', [
            'label' => __( 'gallery Title', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'House On Water'
        ]
    );
    $image_fields->add_control(
        'gallery_sub_title', [
            'label' => __( 'gallery Sub Title', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'Interior'
        ]
    );

    $image_fields->add_control(
        'image', [
            'label' => __( 'Gallery Images', 'sparch-core' ),
            'type' => Controls_Manager::MEDIA,
        ]
    );

    $this->add_control(
        'gallery_images', [
            'label' => __( 'Slide Gallery', 'sparch-core' ),
            'type' => Controls_Manager::REPEATER,
            'title_field' => '{{{ gallery_title }}}',
            'fields' => $image_fields->get_controls(),
        ]
    );
    $this->end_controls_section(); // End Hero content 

   //(Style) gallery Title  Section
    $this->start_controls_section(
        'title_style', [
            'label' => __( 'Title Style ', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_responsive_control(
        'slide_hover_bg', [
            'label' => __( 'Hover Background Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_project_box_col .dl_project_box:hover' => 'background-color: {{VALUE}};', 
            ],  
        ]
    );
    
    $this->add_responsive_control(
        'slide_title_color', [
            'label' => __( 'Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_project_gallery_wrapper .dl_project_name a' => 'color: {{VALUE}};', 
                '{{WRAPPER}} .dl_project_box_col .dl_project_box .dl_title' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_slide_title',
            
            'selector' => '
            {{WRAPPER}} .dl_sp_project_gallery_wrapper .dl_project_name a,
            {{WRAPPER}} .dl_project_box_col .dl_project_box .dl_title',  
        ]
    );
    $this->add_responsive_control(
        'slide_title_margin',
        [
            'label' => esc_html__('Margin', 'sparch-core'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dl_sp_project_gallery_wrapper .dl_project_name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .dl_project_box_col .dl_project_box .dl_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this->add_responsive_control(
        'slide_title_padding',
        [
            'label' => esc_html__('Padding', 'sparch-core'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dl_sp_project_gallery_wrapper .dl_project_name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .dl_project_box_col .dl_project_box .dl_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    
    $this->end_controls_section();   
    /// End The gallery Title Section

    //(Style) gallery Title  Section
    $this->start_controls_section(
        'sub_title_style', [
            'label' => __( 'Sub Title Style ', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_control(
        'slide_sub_title_color', [
            'label' => __( 'Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_project_gallery_wrapper .dl_project_category' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dl_project_box_col .dl_project_box .dl_project_category' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_slide_sub_title',
            
            'selector' => '
            {{WRAPPER}} .dl_sp_project_gallery_wrapper .dl_project_category,
            {{WRAPPER}} .dl_project_box_col .dl_project_box .dl_project_category',  
        ]
    );
    
    $this->add_responsive_control(
        'slide_sub_title_margin',
        [
            'label' => esc_html__('Margin', 'sparch-core'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dl_sp_project_gallery_wrapper .dl_project_category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .dl_project_box_col .dl_project_box .dl_project_category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this->add_responsive_control(
        'slide_sub_title_padding',
        [
            'label' => esc_html__('Padding', 'sparch-core'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dl_sp_project_gallery_wrapper .dl_project_category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .dl_project_box_col .dl_project_box .dl_project_category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this->end_controls_section();   
    /// End The gallery Title Section
    
    }
    
    // HTML Render Function --------------------------------
    protected function render() {
    $settings = $this->get_settings(); 
    $gallery_images  = !empty( $settings['gallery_images'] ) ? $settings['gallery_images'] : '';
    $blog_style = isset( $settings['_sparch_blog_skin']) ?  $settings['_sparch_blog_skin'] : '';  
    
?>
    <?php if($blog_style == '_skin_1'){ ?>

        <div class="section dl_sp_section dl_is_navbar_white" id="dl_sp_section_4">
            <div class="dl_bg_changer">
                <?php
                    if ( is_array( $gallery_images ) && count( $gallery_images ) > 0 ) {
                    $i=1;
                    foreach ( $gallery_images as $feature_imgs ) { 
                ?>
                <div class="dl_section_bg <?php if($i==1){ echo 'dl_active'; } ?>" data-bg-img="<?php echo esc_url($feature_imgs['image']['url']);  ?>"></div>
                <?php  
                $i++;
                }
                }
                ?>
            </div>
            <div class="intro">
                <div class="container">
                    <div class="dl_project_box_row row">
                        <?php
                            if ( is_array( $gallery_images ) && count( $gallery_images ) > 0 ) {
                            $i=1;
                            foreach ( $gallery_images as $feature_imgs ) { 
                        ?> 
                        <div class="dl_project_box_col col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                            <a href="#" class="dl_project_box">
                                <div class="dl_project_box_inner">
                                    <h4 class="dl_title"><?php echo $feature_imgs['gallery_title']; ?></h4>
                                    <div class="dl_project_category"><?php echo $feature_imgs['gallery_sub_title']; ?></div>
                                </div>
                            </a>
                        </div>
                        <?php 
                        $i++;
                         }
                         }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    

    <?php }else{} ?>

<?php
    }


}
