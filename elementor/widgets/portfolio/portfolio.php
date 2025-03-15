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
class DRTH_ESS_Portfolio extends Widget_Base {

    public function get_name() {
        return 'droit-portfolio-theme';
    }

    public function get_title() {
        return __( 'Sparch portfolio', 'droit_portfolio' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'drth_custom_theme' ];
    }


    public function get_style_depends() {
        return ['droit-partner-style'];
    }

	public function get_script_depends(){
		return ['jquery-isotope', 'isotope-mode', 'jquery-masonary'];
	}


    protected function register_controls() {


	    $pricing_repeater = new \Elementor\Repeater();
        // -------------------------------------------- Filtering
        $this->start_controls_section(
            'droit_portfolio_section', [
                'label' => __( 'Portfolio Design', 'sparch-core' ),

            ]
        );
		$this->add_control(
            'sparch_portfolio_skin',
            [
                'label' => esc_html__( 'Design Format', 'sparch-core' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options'   => [
                    '_skin_1' => 'Full Width',
                    '_skin_2' => 'Portfolio Wrapper',
                    '_skin_3' => 'Portfolio Masonry',
                    '_skin_4' => 'Full Grid',
                ],
                'default' => '_skin_1'
            ]
        );
        $this->add_control(
			'show_portfolio_category',
			[
				'label' => __( 'Portfolio Category', 'sparch-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'sparch-core' ),
				'label_off' => __( 'Hide', 'sparch-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->end_controls_section();

        // ---Start portfolio Setting
        $this->start_controls_section(
            'portfolio_filter', [
                'label' => __( 'Portfolio Settings', 'sparch-core' ),
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
        'portfolio_style', [
            'label' => __( 'Portfolio Title', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_control(
        'wraper_bg_color', [
            'label' => __( 'Background Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_portfolio_wrapper .dl_sp_portfolio_content' => 'background: {{VALUE}};', 
            ],
        ]
    );

    $this->add_control(
        'title_color', [
            'label' => __( 'Title Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_portfolio_wrapper .dl_sp_portfolio_content .dl_portfolio_title' => 'color: {{VALUE}};', 
            ],
        ]
    );

    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'sec_typography_title',
            
            'selector' => '{{WRAPPER}} .dl_sp_portfolio_wrapper .dl_sp_portfolio_content .dl_portfolio_title',
            
        ]
    );

    $this->end_controls_section();   ///(Style) End The portfolio Title Section

    ///(Style) Strat The portfolio Button Section
    $this->start_controls_section(
        'portfolio_content_style', [
            'label' => __( 'Portfolio Content', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_control(
        'portfolio_content_color', [
            'label' => __( 'Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_portfolio_wrapper .dl_sp_portfolio_content .dl_portfolio_desc' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_content_portfolio',
            
            'selector' => '{{WRAPPER}} .dl_sp_portfolio_wrapper .dl_sp_portfolio_content .dl_portfolio_desc',  
        ]
    );
    $this->end_controls_section();   
    /// End The portfolio Button Section


    ///(Style) Strat The portfolio Button Section
    $this->start_controls_section(
        'portfolio_button_style', [
            'label' => __( 'button Style', 'sparch-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_control(
        'portfolio_button_color', [
            'label' => __( 'Text Color', 'sparch-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dl_sp_portfolio_wrapper .dl_sp_portfolio_content .dl_portfolio_details_btn' => 'color: {{VALUE}};', 
            ],  
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(), [
            'label' => 'Typography',
            'name' => 'typography_button_portfolio',
            
            'selector' => '{{WRAPPER}} .dl_sp_portfolio_wrapper .dl_sp_portfolio_content .dl_portfolio_details_btn',  
        ]
    );
    $this->end_controls_section();   
    /// End The portfolio Button Section




    }
    
    // HTML Render Function --------------------------------
    protected function render() {
    $settings = $this->get_settings(); 
    $portfolio_style = isset( $settings['sparch_portfolio_skin']) ?  $settings['sparch_portfolio_skin'] : '';
    $show_portfolio_category = isset( $settings['show_portfolio_category']) ?  $settings['show_portfolio_category'] : '';  
    $portfolioPost = new WP_Query( array(
        'post_type'      => 'portfolio',
        'posts_per_page' => $settings['show_count'],
        'order'          => $settings['order'],
        'order_by'          => $settings['order_by'],
        'post__not_in'   => ! empty( $settings['exclude'] ) ? explode( ',', $settings['exclude'] ) : ''
    ) ); 

    $portfolio_cats = get_terms(array(
        'taxonomy' => 'Type',
        'hide_empty' => true
    ));

    $settingsProt = [
        'type' => 'metro',
        'ratio' => 1,
        'columns' => 4,
        'columnsTablet' => 2,
        'columnsMobile' => 1,
        'gutter' => 30,
    ];
?>
    <?php if($portfolio_style == '_skin_1'){ ?>
<!--================Portfolio Area =================-->
<div class="portfolio_area portfolio_full dl_sp_portfolio_section portfolio_grid_area" id="portfolio_area_<?php echo $this->get_id();?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <?php if($show_portfolio_category =='yes'): ?>
                <div class="dl_sp_masonry_filter">
                    <button class="dl_sp_masonry_filter_item current" data-filter="*">All</button>
                    <?php
                        if(is_array($portfolio_cats)) {
                            foreach ( $portfolio_cats as $portfolio_cat ) { 
                    ?>
                    <button class="dl_sp_masonry_filter_item" data-filter=".<?php echo $portfolio_cat->slug ?>"><?php echo $portfolio_cat->name ?></button>
                    <?php
                         }
                        }
                        wp_reset_postdata();
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="dl_addons_grid_wrapper dl_grid_metro" data-grid='<?php echo json_encode($settingsProt);?>'>
                    <div class="dl_addons_grid" >
                        <div class="grid-sizer"></div>
                        <?php while( $portfolioPost->have_posts() ){
                            $portfolioPost->the_post();
                            
                            $cats = get_the_terms(get_the_ID(), 'Type');
                            $cat_slug = '';
                            if(is_array($cats)) {
                                foreach ($cats as $cat) {
                                    $cat_slug .= $cat->slug.' ';
                                }
                            }
                        ?>
                        <div class="grid-item <?php echo esc_attr($cat_slug);?>" >
                            <div class="grid-item-height">
                                <a href="<?php the_permalink(); ?>" class="dl_sp_portfolio_wrapper dl_sp_border_wrapper">
                                    <?php if(has_post_thumbnail()): ?>
                                    <div class="dl_sp_portfolio_thumb">
                                        <?php the_post_thumbnail(); ?>
                                    </div>
                                    <?php endif; ?>
                                    <div class="dl_sp_portfolio_content">
                                        <h3 class="dl_portfolio_title"><?php the_title(); ?></h3>
                                        <p class="dl_portfolio_desc"><?php echo get_the_excerpt(); ?></p>
                                        <span class="dl_portfolio_details_btn"><?php echo esc_html__('Learn more','sparch-core'); ?> <i class="fas fa-angle-right"></i></span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php } ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--================End Portfolio Area =================-->
<?php } elseif( $portfolio_style == '_skin_2' ){ ?>

<!--================Portfolio Area =================-->
<section class="portfolio_area portfolio_grid_area pb-174 portfolio_grid_area" id="portfolio_area_<?php echo $this->get_id();?>">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="dl_addons_grid_wrapper dl_grid_metro" data-grid='{"type":"metro","ratio":1,"columns":4,
                "columnsTablet":2, "columnsMobile":1, "gutter":25}'>
                    <div class="dl_addons_grid dl_popup_gallery">
                        <div class="grid-sizer"></div>
                        <?php while( $portfolioPost->have_posts() ){
                            $portfolioPost->the_post();
                            
                            $cats = get_the_terms(get_the_ID(), 'Type');
                            $cat_slug = '';
                            if(is_array($cats)) {
                                foreach ($cats as $cat) {
                                    $cat_slug .= $cat->slug.' ';
                                }
                            }
                            $featured_img_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); 
                        ?>
                        <div class="grid-item" data-width="1" data-height="1">
                            <div class="dl_sp_portfolio_wrapper_style_02">
                                <?php if(has_post_thumbnail()): ?>
                                <div class="dl_sp_portfolio_thumb">
                                    <a href="#" class="dl_sp_portfolio_thumb_inner">
                                    <?php the_post_thumbnail(); ?>
                                    </a>
                                    <a class="dl_light_btn" href="<?php echo esc_url($featured_img_url); ?>"><i class="fas fa-plus"></i></a>
                                </div>
                                <?php endif; ?>
                                <div class="dl_sp_portfolio_content dl_sp_border_effect">
                                    <a href="#" class="dl_tag">Interior</a>
                                    <h3 class="dl_portfolio_title"> <a href="<?php the_permalink(); ?>"> <?php echo wp_trim_words( get_the_title(), 3 ); ?></a> </h3>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Portfolio Area =================-->
<?php } elseif( $portfolio_style == '_skin_3' ){ ?>
    <section class="portfolio_area portfolio_masonry pb-174 dl_popup_gallery" id="portfolio_area_<?php echo $this->get_id();?>">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <?php if($show_portfolio_category =='yes'): ?>
                    <div class="dl_sp_masonry_filter">
                        <button class="dl_sp_masonry_filter_item current" data-filter="*">All</button>
                        <?php
                        if(is_array($portfolio_cats)) {
                            foreach ( $portfolio_cats as $portfolio_cat ) { 
                        ?>
                        <button class="dl_sp_masonry_filter_item" data-filter=".<?php echo $portfolio_cat->slug ?>"><?php echo $portfolio_cat->name ?></button>
                        <?php
                        }
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dl_sparch_widget_masonry dl_addons_grid_wrapper dl_grid_metro" data-grid='{"type":"masonry","ratio": 0.53,"columns":2,
                    "columnsTablet":2, "columnsMobile":1, "gutter":25}'>
                        <div class="dl_addons_grid">
                            <div class="grid-sizer"></div>
                            <?php
                                $i=1;
                                while( $portfolioPost->have_posts() ){
                                $portfolioPost->the_post();
                                
                                $cats = get_the_terms(get_the_ID(), 'Type');
                                $cat_slug = '';
                                if(is_array($cats)) {
                                    foreach ($cats as $cat) {
                                        $cat_slug .= $cat->slug.' ';
                                    }
                                }
                                $featured_img_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); 
                                $height = 2;
                                if($i == 1 OR $i == 6 OR $i == 9){
                                  $height = 2;
                                }else{
                                    $height = 1;  
                                }
                            ?>
                            <div class="grid-item <?php echo esc_attr($cat_slug);?>" data-width="1" data-height="<?php echo $height; ?>">
                                <div class="grid-item-height">
                                    <div class="dl_sp_portfolio_wrapper_style_03 dl_sp_border_wrapper">
                                        <?php if(has_post_thumbnail()){ ?>
                                        <a href="<?php the_permalink(); ?>" class="dl_sp_portfolio_thumb">
                                            <?php the_post_thumbnail(); ?>
                                        </a>
                                        <?php } ?>
                                        <div class="dl_sp_portfolio_content dl_sp_border_effect">
                                            <h3 class="dl_portfolio_title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h3>
                                            <p class="dl_portfolio_desc"><?php echo get_the_excerpt(); ?></p>
                                            <a class="dl_light_btn" href="<?php echo esc_url( $featured_img_url ); ?>"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            $i++;
                            } 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php } elseif( $portfolio_style == '_skin_4' ){ ?>
    <section class="portfolio_area portfolio_full pb-174 dl_sp_portfolio_section" id="portfolio_area_<?php echo $this->get_id();?>">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="dl_sp_masonry_filter">
                        <button class="dl_sp_masonry_filter_item current" data-filter="*">All</button>
                        <?php
                        if(is_array($portfolio_cats)) {
                            foreach ( $portfolio_cats as $portfolio_cat ) { 
                        ?>
                        <button class="dl_sp_masonry_filter_item" data-filter=".<?php echo $portfolio_cat->slug ?>"><?php echo $portfolio_cat->name ?></button>
                        <?php
                        }
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="dl_addons_grid_wrapper dl_grid_metro" data-grid='{"type":"metro","ratio":1,"columns":3,
                    "columnsTablet":2, "columnsMobile":1, "gutter":30}'>
                        <div class="dl_addons_grid">
                            <div class="grid-sizer"></div>
                            <?php while( $portfolioPost->have_posts() ){
                            $portfolioPost->the_post();
                            
                            $cats = get_the_terms(get_the_ID(), 'Type');
                            $cat_slug = '';
                            if(is_array($cats)) {
                                foreach ($cats as $cat) {
                                    $cat_slug .= $cat->slug.' ';
                                }
                            }
                        ?>
                            <div class="grid-item <?php echo esc_attr($cat_slug);?>" data-width="1" data-height="1">
                                <div class="grid-item-height">
                                    <a href="<?php the_permalink(); ?>" class="dl_sp_portfolio_wrapper dl_sp_border_wrapper">
                                        <?php if(has_post_thumbnail()){ ?>
                                        <div class="dl_sp_portfolio_thumb">
                                           <?php the_post_thumbnail(); ?>
                                        </div>
                                        <?php } ?>
                                        <div class="dl_sp_portfolio_content">
                                            <h3 class="dl_portfolio_title"><?php echo the_title(); ?></h3>
                                            <p class="dl_portfolio_desc"><?php echo get_the_excerpt(); ?></p>
                                            <span class="dl_portfolio_details_btn"><?php echo esc_html__('Read More','sparch'); ?> <i class="fas fa-angle-right"></i></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php
                            }
                           
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php }else{ } ?>

                                          
<?php
    }
}
