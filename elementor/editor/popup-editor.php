<?php
namespace DROIT_ELEMENTOR_PRO;

use \Elementor\TemplateLibrary\Source_Base as Source_Base;

if ( ! defined( 'ABSPATH' ) ) {exit;}

class Dl_Editor{

    private static $instance = null;

    public $posttype = 'dladd_contents';
    
    public static function url(){
		if (defined('DROIT_EL_PRO_FILE')) {
			$file = trailingslashit(plugin_dir_url( DROIT_EL_PRO_FILE )). 'modules/editor/';
		} else {
			$file = trailingslashit(plugin_dir_url( __FILE__ ));
		}
		return $file;
	}

	public static function dir(){
		if (defined('DROIT_EL_PRO_FILE')) {
			$file = trailingslashit(plugin_dir_path( DROIT_EL_PRO_FILE )). 'modules/editor/';
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

    public function init(){
        // register widgets
        add_action('elementor/controls/controls_registered', array( $this, 'editor_widgets' ), 11 );

        add_action( 'init', [ $this, 'custom_posttype' ] );

        add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'modal_content_style' ) );
        
        // add js file in editor mode
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'editor_enqueue_script' ], 100);
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'editor_enqueue_style' ] );

        add_action('elementor/editor/after_enqueue_styles', array( $this, 'modal_content' ) );

        // save content
        add_action( 'wp_ajax_dtaddons_editor', [ $this, 'dtaddons_editor'] );

        add_action( 'elementor/editor/before_enqueue_scripts', function() {
            wp_enqueue_script('dl-editor-refresh', self::url() . 'js/editor-refresh.js', ['elementor-editor'], self::version(), true);
        } );

        // before save
        //add_action('elementor/editor/after_save', [ $this, 'after_save'], 10, 2);
    }

    public function after_save( $post_id, $editor_data ){
        //echo '<pre>'; print_r($editor_data); echo '</pre>'; 
    }

    public function custom_posttype(){
        $labels = [
			'name'               => __( 'Droit Addons Templates', 'droit-elementor-addons-pro' ),
		];
		$args = [
			'labels'              => $labels,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_icon'           => 'dashicons-editor-kitchensink',
            'supports'            => [ 'title', 'thumbnail', 'elementor' ],
            'show_in_nav_menus'     => false,
        ];
        flush_rewrite_rules();
		register_post_type( $this->posttype, $args );
    }

    public function dtaddons_editor(){
        $post = wp_slash($_POST);
        $contentStatus = true;
        $contentId = 0;
        $parent_id = $post['parent_id'];
        $content = '';

        if( !isset( $post['template_id'] ) || empty( $post['template_id'] )){
            wp_send_json_error(0);
        }

        if( $post['post_id'] != 0){
            $postCheck = get_post($post['post_id'], OBJECT);
        } else{
            $postname = 'dladdons-content-widget-' . $post['template_id'];
            $posttitle = 'dladdons-content-widget-' . $post['repeater_id'];
            $postCheck = $this->get_page_by_name($postname, OBJECT, $this->posttype);
            if( !isset($postCheck->ID) ){
                $postCheck = get_page_by_title($posttitle, OBJECT, $this->posttype);
                $contentStatus = isset($postCheck->ID) ? false : $contentStatus;
                $contentId = isset($postCheck->ID) ? $postCheck->ID : $contentId;
                $parent_id = isset($postCheck->post_parent) ? $postCheck->post_parent : $parent_id;
            }
        } 

        if( isset($postCheck->ID) && $contentStatus){
            wp_send_json_success($postCheck->ID);
        }else{
            // insert post for elementor
            $postData = array(
                'post_title'    => $posttitle,
                'post_name'    => $postname,
                'post_status'   => 'publish',
                'post_type' => $this->posttype,
                'post_parent' => $post['parent_id']
            );
               
            $post_id = wp_insert_post($postData);
            if(!is_wp_error($post_id)){
                update_post_meta($post_id, '_elementor_edit_mode', 'builder');
                update_post_meta($post_id, '_elementor_template_type', 'page');
                update_post_meta($post_id, '_wp_page_template', 'elementor_canvas');

                $content = get_post_meta($parent_id, '_elementor_data__'.$post['template_id'], true);

                if( $contentStatus ){
                    update_post_meta($post_id, '_elementor_data', wp_slash($content));
                } else {
                    if( $contentId != 0){
                        $content = get_post_meta($contentId, '_elementor_data', true);
                    }
                    update_post_meta($post_id, '_elementor_data',  wp_slash($content));
                }
                
            } else{
                $post_id = 0;
            }
            wp_send_json_success( $post_id );
        }
    }
    
    public function render( $postid = 0, $widgetsid = '', $repeaterid = ''){ 
        $id = $widgetsid.$repeaterid;
        ob_start();
        ?>
        <i class="dl-editor-icon" aria-hidden="true" data-templateid="<?php echo esc_attr($id);?>" data-widgetsid="<?php echo esc_attr($widgetsid);?>" data-repeaterid="<?php echo esc_attr($repeaterid);?>" data-postid="<?php echo esc_attr($postid);?>"></i>
        <div class="dl-editor-content">
        <?php
       
        if( $postid != 0){
            $post = get_post($postid, OBJECT);
        } else {
            $postname = 'dladdons-content-widget-' . $id;
            $post = $this->get_page_by_name($postname, OBJECT, $this->posttype);
            if( !isset($post->ID) ){
                $posttitle = 'dladdons-content-widget-' . $repeaterid;
                $post = get_page_by_title($posttitle, OBJECT, $this->posttype);
            }
        }
        
        if(isset($post->ID)){
            $parentid = isset($post->post_parent) ? $post->post_parent : 0;

            $data = $this->get_json_meta($post->ID, '_elementor_data');
            if( empty($data) ){
                $data = $this->get_json_meta($parentid, '_elementor_data__'.$id);
            }
            
            if ( !empty( $data ) ) {

                //$this->content_modifier($parentid, $post->ID, $id);

                if( ! get_post_meta( $parentid, '_elementor_data__'.$id, true ) ) {
                    update_post_meta($parentid, '_elementor_data__'.$id, wp_slash(get_post_meta($post->ID, '_elementor_data', true)) );
                }
                
                $data = $this->replace_elements_ids( $data );
                $data = $this->process_export_import_content( $data, 'on_import' );
                $document = \Elementor\Plugin::$instance->documents->get( $post->ID );
                if ( $document ) {
                    echo $document->get_content( false );
                }
            } else {
                echo esc_html__('Click to Edit icon for add content.', 'droit-elementor-addons-pro');
            }

        }else{
            echo esc_html__('Click to Edit icon for add content.', 'droit-elementor-addons-pro');
        }
      
        ?>
        </div>
        <?php
        $result = ob_get_contents();
		ob_end_clean();
		return $result;
    }
    

    private function content_modifier( $parentid, $post, $id = '', $settings = '_dl_pro_tab_list_'){
        
        $data = $this->get_json_meta($parentid, '_elementor_data');

        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    

    public function get_json_meta($ID, $key ) {
		$meta = get_post_meta( $ID, $key, true );
		if ( is_string( $meta ) && ! empty( $meta ) ) {
			$meta = json_decode( $meta, true );
		}
		if ( empty( $meta ) ) {
			$meta = [];
		}

		return $meta;
	}
    protected function replace_elements_ids( $content ) {
		return \Elementor\Plugin::$instance->db->iterate_data( $content, function( $element ) {
			$element['id'] = \Elementor\Utils::generate_random_string();

			return $element;
		} );
	}

    protected function process_export_import_content( $content, $method ) {
		return \Elementor\Plugin::$instance->db->iterate_data(
			$content, function( $element_data ) use ( $method ) {
				$element = \Elementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );
                if ( ! $element ) {
					return null;
				}
				return $this->process_element_export_import_content( $element, $method );
			}
		);
	}

    protected function process_element_export_import_content( \Elementor\Controls_Stack $element, $method ) {
		$element_data = $element->get_data();
		if ( method_exists( $element, $method ) ) {
			$element_data = $element->{$method}( $element_data );
		}

		foreach ( $element->get_controls() as $control ) {
			$control_class = \Elementor\Plugin::$instance->controls_manager->get_control( $control['type'] );
            if ( ! $control_class ) {
				return $element_data;
			}

			if ( method_exists( $control_class, $method ) ) {
				$element_data['settings'][ $control['name'] ] = $control_class->{$method}( $element->get_settings( $control['name'] ), $control );
			}

			if ( 'on_export' === $method && isset( $control['export'] ) && false === $control['export'] ) {
				unset( $element_data['settings'][ $control['name'] ] );
			}
		}

		return $element_data;
	}

    protected function get_page_by_name($page_title, $output = OBJECT, $post_type = 'page'){
        global $wpdb;
 
        if ( is_array( $post_type ) ) {
            $post_type           = esc_sql( $post_type );
            $post_type_in_string = "'" . implode( "','", $post_type ) . "'";
            $sql = $wpdb->prepare(
                    "
                    SELECT ID
                    FROM $wpdb->posts
                    WHERE post_name = %s
                    AND post_type IN ($post_type_in_string)
                ",
                    $page_title
                );
        } else {
            $sql = $wpdb->prepare(
                "
                SELECT ID
                FROM $wpdb->posts
                WHERE post_name = %s
                AND post_type = %s
            ",
                $page_title,
                $post_type
            );
        }
        $page = $wpdb->get_var( $sql );
        if ( $page ) {
            return get_post( $page, $output );
        }
    }

    public function editor_enqueue_script(){
        // editor popup
        wp_enqueue_script("dlpro-editor", self::url() . 'js/editor-popup.js', ['jquery', 'elementor-frontend'], self::version(), true);
        wp_localize_script(
            'dlpro-editor',
            'dlproeditor',
            array(
                'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
                'posturl' => esc_url( admin_url( 'post.php' ) ),
                'nonce'   => wp_create_nonce( 'droitpro-editor-nonce' ),
            )
        );   
        
    }

    public function editor_enqueue_style(){
        // editor popup js loader
        wp_enqueue_style('dlpro-editor', self::url() . 'css/editor-popup.css', [], self::version());     
    }

    public function modal_content_style(){
        wp_enqueue_style('dlpro-frontent', self::url() . 'css/frontent-popup.css', [], self::version()); 
    }
    
    public function modal_content(){
        ob_start();
        ?>
        <div class="dlpopup-main" style="display:none;" data-render-status="false">
            <div class="dlpopup-modal-back"></div>
            <div class="dlpopup-modal">
                <div class="dlmodal-content-all">
                    <div class="modal-header">
                        <h3><?php echo esc_html__('Droit Editor Area', 'droit-elementor-addons-pro');?></h3>
                        <i class="eicon-close" aria-hidden="true" title="Close"></i>
                    </div>
                    <div class="modal-body-content">
                        <iframe allowfullscreen="1"></iframe>
                    </div>
                </div>
            </div>

        </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        echo $output ;
    }

    public function editor_widgets( $controls_manager ){
        if( !class_exists('\DROIT_ELEMENTOR_PRO\DL_Editor_Widgets')){
            include_once( __DIR__ . '/control-manager.php');
            include_once( __DIR__ . '/editor-widgets.php');
        }
        $controls_manager->register_control( 'dleditor', new DL_Editor_Widgets());
    }
    public static function instance(){
        if( is_null(self::$instance) ){
            self::$instance = new self;
        }
        return self::$instance;
    }

}
