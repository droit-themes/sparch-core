<?php
namespace Elementor;

use \WP_Query;


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class droit_portfolio_team
 * @package droit_portfolioCore\Widgets
 */
class DRTH_ESS_team extends Widget_Base {

    public function get_name() {
        return 'droit-team-theme';
    }

    public function get_title() {
        return __( 'Sparch team Us', 'droit_team' );
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
		return ['droit-team-script'];
	}

    protected function register_controls() {

    $pricing_repeater = new \Elementor\Repeater();
    // -------------------------------------------- Filtering
    $this->start_controls_section(
        'droit_team_section', [
            'label' => __( 'Team Design', 'sparch-core' ),

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
        'team_centent_section', [
            'label' => __( 'Team Content', 'sparch-core' ),

        ]
    );
    /// --------------- team ----------------
    $image_fields = new \Elementor\Repeater();

    $image_fields->add_control(
        'team_title', [
            'label' => __( 'Name', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'Smith'
        ]
    );
    $image_fields->add_control(
        'team_designation', [
            'label' => __( 'Designation', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'Founder in'
        ]
    );

    $image_fields->add_control(
        'image', [
            'label' => __( 'Feature Images', 'sparch-core' ),
            'type' => Controls_Manager::MEDIA,
        ]
    );
    
    $image_fields->add_control(
        'team_facebook', [
            'label' => __( 'Faceboook', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '#'
        ]
    );

    $image_fields->add_control(
        'team_twitter', [
            'label' => __( 'Twitter', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '#'
        ]
    );

    $image_fields->add_control(
        'team_linkdin', [
            'label' => __( 'Linkedin', 'sparch-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '#'
        ]
    );

    $this->add_control(
        'gallery_images', [
            'label' => __( 'Team Information', 'sparch-core' ),
            'type' => Controls_Manager::REPEATER,
            'title_field' => '{{{ team_title }}}',
            'fields' => $image_fields->get_controls(),
        ]
    );
    $this->end_controls_section(); // End Hero content 

    }
    
    // HTML Render Function --------------------------------
    protected function render() {
    $settings = $this->get_settings(); 
    $team_information  = !empty( $settings['gallery_images'] ) ? $settings['gallery_images'] : '';
    $blog_style = isset( $settings['_sparch_blog_skin']) ?  $settings['_sparch_blog_skin'] : '';  
    
?>
    <?php if($blog_style == '_skin_1'){ ?>

    <section class="our_team_area pb-0">
        <div class="container">
            <div class="row team_inner">
                <?php
                    if(is_array( $team_information ) && count( $team_information) > 0 ){
                    $i= 1; 
                    foreach ($team_information as $team_info) {
                        
                ?>
                <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="300ms">
                    <div class="team_item">
                        <div class="team_img">
                            <img class="img-fluid" src="<?php echo esc_url( $team_info['image']['url']); ?>" alt="">
                        </div>
                        <div class="team_text">
                            <a href="#">
                                <h3><?php echo $team_info['team_title']; ?></h3>
                            </a>
                            <h5><?php echo $team_info['team_designation']; ?></h5>
                            <ul class="nav">
                                <li><a href="<?php echo $team_info['team_facebook']; ?>"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="<?php echo $team_info['team_linkdin']; ?>"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="<?php echo $team_info['team_linkdin']; ?>"><i class="fa fa-linkedin-square"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php 
                    $i++;
                    }
                    }
                ?>
            </div>
        </div>
    </section>
    

    <?php }else{} ?>

<?php
    }


}
