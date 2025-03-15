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
class DRTH_ESS_Blogs extends Widget_Base {

    public function get_name() {
        return 'droit-blogs-theme';
    }

    public function get_title() {
        return __( 'Sparch Blogs', 'droit_portfolio' );
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
                'label' => __( 'Blogs Design', 'sparch-core' ),

            ]
        );
		$this->add_control(
            '_sparch_blog_skin',
            [
                'label' => esc_html__( 'Design Format', 'sparch-core' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options'   => [
                    '_skin_1' => 'Blog Grid',
                    '_skin_2' => 'Blog List',
                    '_skin_3' => 'Blog Masonry',
                ],
                'default' => '_skin_1'
            ]
        );
        $this->end_controls_section();

        // ---Start Blog Setting
        $this->start_controls_section(
            'Blog_filter', [
                'label' => __( 'Blog Settings', 'sparch-core' ),
            ]
        );

        
        $this->add_control(
            'all_label', [
                'label' => esc_html__( 'All filter label', 'sparch-core' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'See All'
            ]
        );
        $this->add_control(
            'show_count', [
                'label' => esc_html__( 'Show count', 'sparch-core' ),
                'type' => Controls_Manager::NUMBER,
                'label_block' => true,
                'default' => 8
            ]
        );
        $this->add_control(
            'order_by', [
                'label' => esc_html__( 'Order By', 'sparch-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'modified'   => __('Modified', 'sparch-core'),
                    'date'       => __('Date', 'sparch-core'),
                    'rand'       => __('Rand', 'sparch-core'),
                    'ID'         => __('ID', 'sparch-core'),
                    'title'      => __('Title', 'sparch-core'),
                    'author'     => __('Author', 'sparch-core'),
                    'name'       => __('Name', 'sparch-core'),
                    'parent'     => __('Parent', 'sparch-core'),
                    'menu_order' => __('Menu Order', 'sparch-core'),
                ],
                'default' => 'ID'
            ]
        );
        $this->add_control(
            'order', [
                'label' => esc_html__( 'Order', 'sparch-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC'   => __('ASC', 'sparch-core'),
                    'DESC'   => __('DESC', 'sparch-core'),
                ],
                'default' => 'ASC'
            ]
        );
    $this->end_controls_section();

    //---------------- Style Section --------------- // 

    $this->start_controls_section(
        'blog_style', [
            'label' => __( 'Blog Title', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );

    $this->add_control(
        'title_color', [
            'label' => __( 'Title Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_blog_wrapper .dl_sp_blog_content .dl_blog_title a' => 'color: {{VALUE}};',
                '{{WRAPPER}} .l_news_item .flipbox_text h4' => 'color: {{VALUE}};', 
            ],
        ]
    );

    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'sec_typography_title',
            
            'selector' => '
            {{WRAPPER}} .dl_sp_blog_wrapper .dl_sp_blog_content .dl_blog_title,
            {{WRAPPER}} .l_news_item .flipbox_text h4'
        ]
    );

    $this->end_controls_section();   ///(Style) End The Blog Title Section

  ///(Style) Strat The Blog Date  Section
    $this->start_controls_section(
        'blog_sub_style', [
            'label' => __( 'Blog Date & Author ', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_control(
        'sub_title_color', [
            'label' => __( 'Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_blog_wrapper .dl_sp_blog_content .dl_blog_date' => 'color: {{VALUE}};',
                '{{WRAPPER}} .l_news_item .flipbox_text h5' => 'color: {{VALUE}};',
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_sub_title',
            
            'selector' => '
            {{WRAPPER}} .dl_sp_blog_wrapper .dl_sp_blog_content .dl_blog_date,
            {{WRAPPER}} .l_news_item .flipbox_text h5',  
        ]
    );
    $this->end_controls_section();   
    /// End The Blog date  Title Section


    ///(Style) Strat The Blog Button Section
    $this->start_controls_section(
        'blog_content_style', [
            'label' => __( 'Blog Content', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_control(
        'blog_content_color', [
            'label' => __( 'Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_blog_wrapper .dl_sp_blog_content .dl_blog_desc' => 'color: {{VALUE}};',
                '{{WRAPPER}} .l_news_item .flipbox_text p' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_content_blog',
            
            'selector' => '
            {{WRAPPER}} .dl_sp_blog_wrapper .dl_sp_blog_content .dl_blog_desc,
            {{WRAPPER}} .l_news_item .flipbox_text p',  
        ]
    );
    $this->end_controls_section();   
    /// End The Blog Button Section


    ///(Style) Strat The Blog Button Section
    $this->start_controls_section(
        'blog_button_style', [
            'label' => __( 'Read More button', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_control(
        'blog_button_color', [
            'label' => __( 'Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_blog_wrapper .dl_sp_blog_content .dl_blog_details_btn' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_button_blog',
            
            'selector' => '{{WRAPPER}} .dl_sp_blog_wrapper .dl_sp_blog_content .dl_blog_details_btn',  
        ]
    );
    $this->end_controls_section();   
    /// End The Blog Button Section




    }
    
    // HTML Render Function --------------------------------
    protected function render() {
    $settings = $this->get_settings(); 
    $blog_style = isset( $settings['_sparch_blog_skin']) ?  $settings['_sparch_blog_skin'] : '';  
    $blogPost = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => $settings['show_count'],
        'order'          => $settings['order'],
        'order_by'          => $settings['order_by'],
        'post__not_in'   => ! empty( $settings['exclude'] ) ? explode( ',', $settings['exclude'] ) : ''
    ) ); 
?>
    <?php if($blog_style == '_skin_1'){ ?>

    <section class="latest_news_area pb-174">
        <div class="container">
            <div class="row">
                <?php while( $blogPost->have_posts() ){
                    $blogPost->the_post();    
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="dl_sp_blog_wrapper dl_sp_border_wrapper">
                        <?php if(has_post_thumbnail()): ?>
                        <div class="dl_sp_blog_thumb">
                           <?php the_post_thumbnail('sparch-blog-grid'); ?>
                        </div>
                        <?php endif; ?>
                        <div class="dl_sp_blog_content dl_sp_border_effect">
                            <a href="#" class="dl_blog_date"><?php echo get_the_date(); ?></a>
                            <h3 class="dl_blog_title"><a href="<?php the_permalink(); ?>"><?php echo  wp_trim_words( get_the_title(), 3, false ); ?></a></h3>
                            <p class="dl_blog_desc"><?php echo  wp_trim_words( get_the_content(), 20, false ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="dl_blog_details_btn"><?php echo esc_html__('Learn More','sparch-core'); ?>  <i class="fas fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php
                } 
                wp_reset_postdata();
                ?>
                
            </div>
        </div>
    </section>
    <?php }elseif($blog_style == '_skin_2'){ ?>
    <section class="blog_grid_area">
        <div class="container">
            <div class="row">
                <?php while( $blogPost->have_posts() ){
                    $blogPost->the_post();    
                ?>
                <div class="col-lg-12">
                    <div class="l_news_item blog_list_item wow fadeInUp" data-wow-delay="300ms">
                        <?php if(has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>" class="l_news_img">
                            <?php the_post_thumbnail('sparch-blog-list', array('class' => 'img-fluid')); ?>
                        </a>
                        <?php endif; ?> 
                        <div class="flipbox_text">
                            <h5><?php echo get_the_date(); ?></h5>
                            <a href="<?php the_permalink(); ?>">
                                <h4><?php echo wp_trim_words( get_the_title(), 3, false ); ?></h4>
                            </a>
                            <p><?php echo wp_trim_words( get_the_content(), 20, false ); ?></p>
                            <a class="arrow_btn" href="<?php the_permalink(); ?>"><?php echo esc_html__('Learn More', 'sparch-core'); ?>
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php }
                  wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
   <?php }elseif($blog_style == '_skin_3'){ ?>
    
    <section class="blog_masonry_area pb-174">       
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="dl_addons_grid_wrapper dl_grid_metro" data-grid='{"type":"masonry","ratio":null,"columns":4,
                    "columnsTablet":2, "columnsMobile":1, "gutter":25}'>
                        <div class="dl_addons_grid dl_popup_gallery">
                            <div class="grid-sizer"></div>
                            <?php while( $blogPost->have_posts() ){
                                $blogPost->the_post();    
                            ?>
                            <div class="grid-item">
                                <div class="dl_sp_blog_masonry_item">
                                    <?php if(has_post_thumbnail()): ?>
                                    <div class="dl_sp_blog_thumb">
                                        <a href="<?php the_permalink(); ?>" class="dl_sp_blog_thumb_inner">
                                           <?php the_post_thumbnail(); ?>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    <div class="dl_sp_blog_content">
                                        <a href="#" class="dl_blog_date"><?php echo get_the_date(); ?></a>
                                        <h3 class="dl_blog_title"> <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words( get_the_title(), 3, false ); ?></a> </h3>
                                    </div>
                                </div>
                            </div>
                            <?php }  
                                  wp_reset_postdata();
                            ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <?php  }else{ } ?>


<?php
}


}
