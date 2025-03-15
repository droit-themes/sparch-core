<?php
namespace Elementor;

use \WP_Query;


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class droit_portfolio_slider
 * @package droit_portfolioCore\Widgets
 */
class DRTH_ESS_Slider extends Widget_Base {

    public function get_name() {
        return 'droit-slider-theme';
    }

    public function get_title() {
        return __( 'Sparch Slider', 'droit_portfolio' );
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
		return ['droit-portfolio-script'];
	}


    protected function register_controls() {


	    $pricing_repeater = new \Elementor\Repeater();
        // -------------------------------------------- Filtering
        $this->start_controls_section(
            'droit_pricing_section', [
                'label' => __( 'slider Design', 'sparch-core' ),

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
        'slider_gallery', [
            'label' => __( 'Slider Gallery', 'kidzo-core' ),
        ]
    );

    /// --------------- Slider ----------------
    $image_fields = new \Elementor\Repeater();

    $image_fields->add_control(
        'slider_title', [
            'label' => __( 'Slider Title', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'House On Water'
        ]
    );
    $image_fields->add_control(
        'gallery_sub_title', [
            'label' => __( 'Slider Sub Title', 'sparch-core' ),
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
            'title_field' => '{{{ slider_title }}}',
            'fields' => $image_fields->get_controls(),
        ]
    );
    $this->end_controls_section(); // End Hero content 

   //(Style) Slider Title  Section
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
                '{{WRAPPER}} .dl_sp_project_slider_wrapper .dl_project_name a' => 'color: {{VALUE}};', 
                '{{WRAPPER}} .dl_project_box_col .dl_project_box .dl_title' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_slide_title',
            
            'selector' => '
            {{WRAPPER}} .dl_sp_project_slider_wrapper .dl_project_name a,
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
                '{{WRAPPER}} .dl_sp_project_slider_wrapper .dl_project_name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                '{{WRAPPER}} .dl_sp_project_slider_wrapper .dl_project_name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .dl_project_box_col .dl_project_box .dl_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    
    $this->end_controls_section();   
    /// End The Slider Title Section

    //(Style) Slider Title  Section
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
                '{{WRAPPER}} .dl_sp_project_slider_wrapper .dl_project_category' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dl_project_box_col .dl_project_box .dl_project_category' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_slide_sub_title',
            
            'selector' => '
            {{WRAPPER}} .dl_sp_project_slider_wrapper .dl_project_category,
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
                '{{WRAPPER}} .dl_sp_project_slider_wrapper .dl_project_category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                '{{WRAPPER}} .dl_sp_project_slider_wrapper .dl_project_category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .dl_project_box_col .dl_project_box .dl_project_category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this->end_controls_section();   
    /// End The Slider Title Section
    
    }
    
    // HTML Render Function --------------------------------
    protected function render() {
    $settings = $this->get_settings(); 
    $gallery_images  = !empty( $settings['gallery_images'] ) ? $settings['gallery_images'] : '';
    $blog_style = isset( $settings['_sparch_blog_skin']) ?  $settings['_sparch_blog_skin'] : '';  
    
?>
    <?php if($blog_style == '_skin_1'){ ?>

    <section class="latest_project_area pt-174">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 dl_position_relative">
                    <div class="dl_sp_project_slider">
                        <div class="swiper-container dl_sp_project_slider_wrapper" data-next=".slider_next_two" data-prev=".slider_prev_two" data-loop="true" data-pagination=".dl_sp_project_pagination" data-paginationtype="fraction"
                        data-autoplay="false" data-speed="1000" data-space="50" data-pag data-breakpoints='{"576": {"slidesPerView": 2}, "320": {"slidesPerView": 1}}'>
                            <div class="swiper-wrapper">
                                <?php
                                    if ( is_array( $gallery_images ) && count( $gallery_images ) > 0 ) {
                                    $i=1;
                                    foreach ( $gallery_images as $feature_imgs ) { 
                                ?>   
                                <div class="swiper-slide">
                                    <div class="dl_sp_project_slider_wrapper">
                                        <img class="dl_project_img" src="<?php echo esc_url($feature_imgs['image']['url']);  ?>" alt="#">
                                        <a href="#" class="dl_project_category"><?php echo $feature_imgs['gallery_sub_title']; ?></a>
                                        <h5 class="dl_project_name"><a href="#"><?php echo $feature_imgs['slider_title']; ?></a></h5>
                                    </div>
                                </div>
                                <?php 
                                 $i++;
                                 }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="dl_swiper_navigation">
                            <div class="slider_prev_two"><i class="fas fa-arrow-left"></i></div>
                            <div class="slider_next_two"><i class="fas fa-arrow-right"></i></div>
                        </div>
                        <div class="dl_sp_project_pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>    

    <?php }else{} ?>

<?php
    }


}
