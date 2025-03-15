<?php
namespace Elementor;

use \WP_Query;


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class droit_portfolio_testimonials
 * @package droit_portfolioCore\Widgets
 */
class DRTH_ESS_Testimonials extends Widget_Base {

    public function get_name() {
        return 'droit-testimonials-theme';
    }

    public function get_title() {
        return __( 'Sparch Testimonials', 'droit_portfolio' );
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
            'droit_testimonisla_section', [
                'label' => __( 'Testimonials Design', 'sparch-core' ),

            ]
        );
		$this->add_control(
            '_sparch_testimonials_skin',
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

    // -------------------------------------------- Filtering
    $this->start_controls_section(
        'droit_testimonials_section', [
            'label' => __( 'Testimonials Section', 'sparch-core' ),

        ]
    );
    
    $this->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
            'name' => 'testimonials_background',
            'label' => __( 'Background', 'sparch-core' ),
            'types' => [ 'classic', 'gradient', 'video' ],
            'selector' => '{{WRAPPER}} .dl_sp_testimonial_section',
        ]
    );
    $this->add_control(
        'testimonials_image', [
            'label' => __( 'Featurre Images', 'sparch-core' ),
            'type' => Controls_Manager::MEDIA,
        ]
    );
    $this->add_control(
        'testimonials_video_icon', [
            'label' => __( 'Icon Images', 'sparch-core' ),
            'type' => Controls_Manager::MEDIA,
        ]
    );
    $this->add_control(
        'testimonials_video_url', [
            'label' => __( 'Video URL', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'https://www.youtube.com/watch?v=_naXMUKyy_M'
        ]
    );
    
    $this->end_controls_section();

    //---------------- Style Section --------------- // 

    $this->start_controls_section(
        'testimonials_gallery', [
            'label' => __( 'Testimonials Information', 'kidzo-core' ),
        ]
    );

    /// --------------- testimonials ----------------
    $image_fields = new \Elementor\Repeater();

    $image_fields->add_control(
        'testimonials_title', [
            'label' => __( 'Name', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'Smith'
        ]
    );
    $image_fields->add_control(
        'testimonials_designation', [
            'label' => __( 'Designation', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'Founder in'
        ]
    );
    $image_fields->add_control(
        'testimonials_company', [
            'label' => __( 'Company Name', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'DroitThemes'
        ]
    );
    $image_fields->add_control(
        'testimonials_content', [
            'label' => __( 'Feedback Content', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'Projects for many large domestic and foreign corporations, enterprises in many elds such as nance, banking, F&B, education, communication.'
        ]
    );
    
    $image_fields->add_control(
        'image', [
            'label' => __( 'Feature Images', 'sparch-core' ),
            'type' => Controls_Manager::MEDIA,
        ]
    );

    $this->add_control(
        'gallery_images', [
            'label' => __( 'Testimonials Information', 'sparch-core' ),
            'type' => Controls_Manager::REPEATER,
            'title_field' => '{{{ testimonials_title }}}',
            'fields' => $image_fields->get_controls(),
        ]
    );
    $this->end_controls_section(); // End Hero content 

   //(Style) testimonials Title  Section

   $this->start_controls_section(
        'sec_style', [
            'label' => __( 'Slider Padding ', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

   $this->add_responsive_control(
        'slide_sec_padding',
            [
                'label' => esc_html__('Padding', 'sparch-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

    $this->end_controls_section();   


    $this->start_controls_section(
        'title_style', [
            'label' => __( 'Name Style ', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
        
    $this->add_responsive_control(
        'slide_title_color', [
            'label' => __( 'Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_name' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_slide_title',
            
            'selector' => '
            {{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_name',  
        ]
    );
    $this->add_responsive_control(
        'slide_title_margin',
        [
            'label' => esc_html__('Margin', 'sparch-core'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                '{{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    
    $this->end_controls_section();   
    /// End The testimonials Title Section

    //(Style) testimonials Designation  Section
    $this->start_controls_section(
        'designation_style', [
            'label' => __( 'Designation Style ', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
        
    $this->add_responsive_control(
        'slide_designation_color', [
            'label' => __( 'Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_position' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_slide_designation',
            
            'selector' => '
            {{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_position',  
        ]
    );
    $this->add_responsive_control(
        'slide_designation_margin',
        [
            'label' => esc_html__('Margin', 'sparch-core'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_position' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this->add_responsive_control(
        'slide_designation_padding',
        [
            'label' => esc_html__('Padding', 'sparch-core'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_position' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this->end_controls_section();   
    //(Style) testimonials Company Name Section
    $this->start_controls_section(
        'company_name_style', [
            'label' => __( 'Company Name Style ', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
        
    $this->add_responsive_control(
        'slide_company_color', [
            'label' => __( 'Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_position a' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_slide_company',
            
            'selector' => '
            {{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_position a',  
        ]
    );
    
    
    $this->end_controls_section();   
    /// End The testimonials Title Section

    //(Style) testimonials Content  Section
    $this->start_controls_section(
        'content_style', [
            'label' => __( 'Content Style ', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
        
    $this->add_responsive_control(
        'slide_content_color', [
            'label' => __( 'Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_speces' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_slide_content',
            
            'selector' => '
            {{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_speces',  
        ]
    );
    $this->add_responsive_control(
        'slide_content_margin',
        [
            'label' => esc_html__('Margin', 'sparch-core'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_speces' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this->add_responsive_control(
        'slide_content_padding',
        [
            'label' => esc_html__('Padding', 'sparch-core'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dl_sp_testimonial_section .dl_sp_testimonial_content .dl_client_speces' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this->end_controls_section();   
    //(Style) testimonials Company Name Section

    }
    
    // HTML Render Function --------------------------------
    protected function render() {
    $settings = $this->get_settings(); 
    $gallery_images  = !empty( $settings['gallery_images'] ) ? $settings['gallery_images'] : '';
    $blog_style = isset( $settings['_sparch_testimonials_skin']) ?  $settings['_sparch_testimonials_skin'] : ''; 
    $testimonials_background  = !empty( $settings['testimonials_image']['url'] ) ? $settings['testimonials_image']['url'] : ''; 
    $testimonials_video_icon  = !empty( $settings['testimonials_video_icon']['url'] ) ? $settings['testimonials_video_icon']['url'] : ''; 
    
?>
    <?php if($blog_style == '_skin_1'){ ?>

     <!-- testimonial area part -->
    <section class="video_big_image dl_sp_testimonial_section dl_mb_100 dl_mt_100">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6 dl_p_0">
                    <div class="dl_sp_testimonial_content">
                        <div class="swiper-container" data-perpage="1" data-next=".slider_next_one"
                            data-prev=".slider_prev_one" data-loop="true" data-autoplay="true" data-speed="1000"
                            data-space="50">
                            <div class="swiper-wrapper">
                                <?php
                                 if(is_array( $gallery_images ) && count( $gallery_images) > 0 ){
                                  $i= 1; 
                                  foreach ($gallery_images as $testimonials_info) {
                                     
                                ?>
                                <div class="swiper-slide">
                                    <img class="dl_client_img" src="<?php echo esc_url( $testimonials_info['image']['url']); ?>" alt="<?php echo $testimonials_info['testimonials_title']; ?>">
                                    <h4 class="dl_client_name"><?php echo $testimonials_info['testimonials_title']; ?></h4>
                                    <h5 class="dl_client_position"><?php echo $testimonials_info['testimonials_designation']; ?> 
                                        <a href="#"><?php echo $testimonials_info['testimonials_company']; ?></a>
                                    </h5>
                                    <p class="dl_client_speces"><?php echo $testimonials_info['testimonials_content']; ?></p>
                                </div>
                                <?php 
                                 $i++;
                                 }
                                 }
                                ?>
                            </div>
                            <div class="dl_swiper_navigation">
                                <div class="slider_prev_one" tabindex="0" role="button" aria-label="Previous slide"><i class="fas fa-chevron-left"></i></div>
                                <div class="slider_next_one" tabindex="0" role="button" aria-label="Next slide"><i class="fas fa-chevron-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-lg-6 col-sm-8">
                    <div class="dl_sp_testimonial_video">
                        <?php if(!empty($testimonials_background)): ?>
                        <img src="<?php echo esc_url( $testimonials_background ); ?>" alt="#" class="dl_sp_testimonial_video_bg">
                        <?php endif; ?>
                        <?php if(!empty($testimonials_video_icon)): ?>
                        <a class="popup-youtube dl_video_popup_btn" href="<?php echo $settings['testimonials_video_url']; ?>">
                        <img src="<?php echo esc_url( $testimonials_video_icon ); ?>" alt="#"></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- testimonial area part end-->

    <?php }else{} ?>

<?php
    }


}
