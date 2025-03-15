<?php
namespace Elementor;

use \WP_Query;


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class droit_portfolio_about
 * @package droit_portfolioCore\Widgets
 */
class DRTH_ESS_About extends Widget_Base {

    public function get_name() {
        return 'droit-about-theme';
    }

    public function get_title() {
        return __( 'Sparch About Us', 'droit_about' );
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
		return ['droit-about-script'];
	}


    protected function register_controls() {


    $pricing_repeater = new \Elementor\Repeater();
    // -------------------------------------------- Filtering
    $this->start_controls_section(
        'droit_about_section', [
            'label' => __( 'About Design', 'sparch-core' ),

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
        'about_centent_section', [
            'label' => __( 'About Content', 'sparch-core' ),

        ]
    );

    $this->add_control(
        'about_title', [
            'label' => esc_html__( 'About Title Text', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'Years Experience Working'
        ]
    );

    $this->add_control(
        'about_experience', [
            'label' => esc_html__( 'Years Of Experiences', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '20'
        ]
    );
    $this->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
            'name' => 'experience_background',
            'label' => __( 'Background', 'sparch-core' ),
            'types' => [ 'classic', 'gradient', 'video' ],
            'selector' => '{{WRAPPER}} .text_bg_img',
        ]
    );
    $this->add_control(
        'about_contents', [
            'label' => esc_html__( 'About Contents', 'sparch-core' ),
            'type' => Controls_Manager::WYSIWYG,
            'label_block' => true,
        ]
    );
    $this->add_control(
        'about_signature',
        [
            'label' => __( 'signature Image', 'sparch-core' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => [
                'url' => \Elementor\Utils::get_placeholder_image_src(),
            ],
        ]
    );
    $this->end_controls_section();
    
    $this->start_controls_section(
        'about_us_style', [
            'label' => __( 'About Style', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_control(
        'title_color', [
            'label' => __( 'Title Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_experiance_title_wrapper .dl_title' => 'color: {{VALUE}};', 
            ],
        ]
    );

    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'sec_typography_title',
            
            'selector' => '{{WRAPPER}} .dl_sp_experiance_title_wrapper .dl_title',
            
        ]
    );

    $this->add_control(
        'number_color', [
            'label' => __( 'Number Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_experiance_title_wrapper .dl_sp_experiance_title' => 'color: {{VALUE}};', 
            ],
        ]
    );

    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Number Typography',
            'name' => 'sec_typography_number',
            
            'selector' => '{{WRAPPER}} .dl_sp_experiance_title_wrapper .dl_sp_experiance_title',
            
        ]
    );

    $this->add_control(
        'content_color', [
            'label' => __( 'Content Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_experiance_content_wrapper .dl_sp_experiance_content .dl_description' => 'color: {{VALUE}};', 
            ],
        ]
    );

    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Content Typography',
            'name' => 'sec_typography_content',
            
            'selector' => '{{WRAPPER}} .dl_sp_experiance_content_wrapper .dl_sp_experiance_content .dl_description',
            
        ]
    );

    $this->end_controls_section();   ///(Style) End The portfolio Title Section



    }
    
    // HTML Render Function --------------------------------
    protected function render() {
    $settings = $this->get_settings(); 
    $about_images  = !empty( $settings['about_images'] ) ? $settings['about_images'] : '';
    $blog_style = isset( $settings['_sparch_blog_skin']) ?  $settings['_sparch_blog_skin'] : '';  
    
?>
    <?php if($blog_style == '_skin_1'){ ?>

        <section class="experience_design_area p-170">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-md-6 col-xl-6">
                            <div class="dl_sp_experiance_title_wrapper dl_sp_border_wrapper">
                                <?php if(!empty($settings['about_experience'])) : ?>
                                <h3 class="dl_sp_experiance_title text_bg_img"><?php echo esc_html__($settings['about_experience'],'sparch-core'); ?></h3>
                                <?php endif; ?>
                                <?php if(!empty($settings['about_title'])) : ?>
                                <h4 class="dl_title"><?php echo esc_html__($settings['about_title'],'sparch-core'); ?></h4>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-6">
                            <div class="dl_sp_experiance_content_wrapper">
                                <div class="dl_sp_experiance_quote">
                                    <i>“</i>
                                </div>
                                <div class="dl_sp_experiance_content">
                                    <?php if(!empty($settings['about_contents'])) : ?>
                                    <h5 class="dl_description"><?php echo $settings['about_contents']; ?></h5>
                                    <?php endif; ?>
                                    <?php if(!empty($settings['about_signature']['url'])): ?>
                                    <img src="<?php echo esc_url( $settings['about_signature']['url'] ); ?>" alt="#">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    

    <?php }else{} ?>

<?php
    }


}
