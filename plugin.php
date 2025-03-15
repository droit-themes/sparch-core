<?php
namespace sparch_CORE;
defined( 'ABSPATH' ) || exit;

final class DRTH_Plugin{

    private static $instance;

    public function __construct(){
        self::_run(); 
    }
    
    public static function version(){
        return time();
        return '1.0.0';
    }
 
    public static function php_version(){
        return '5.6';
    }

    public static function dtdr_th_file(){
        return  DRO_TH_ESS;
    }
  
    public static function dtdr_th_url(){
        return trailingslashit(plugin_dir_url( self::dtdr_th_file() ));
    }

    public static function dtdr_th_dir(){
        return trailingslashit(plugin_dir_path( self::dtdr_th_file() ));
    }

 
    public function load(){  
        
        if ( version_compare( PHP_VERSION, self::php_version(), '<' ) ) {
            add_action( 'admin_notices', function(){
                $class = 'notice notice-error';
                $message = sprintf( __( '<b>Droit dark</b> requires PHP version %1$s+, which is currently NOT RUNNING on this server.', 'droit-dark' ), '5.6' );
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message); 
            } );
            return;
        }
        // Check if Droit Elementor Addons installed and activated
        if (!did_action('droitAddons/loaded')) {
            add_action('admin_notices', [$this, 'th_missing_free_plugin']);
            return;
        }
        
        add_action('wp_enqueue_scripts', [$this, 'global_script_load'], 998);
        add_action( 'elementor/frontend/before_register_scripts', [ $this, 'global_script_load' ], 998 );

        // load Elementor
        Elementor\Loader::instance()->_init();


        // load framework
        Framework\Loader::instance()->_init();
        //load custom post type
        

    }

    public function global_script_load(){

       wp_enqueue_script('jquery-isotope', self::dtdr_th_url() . 'assets/js/isotope.pkgd.min.js', ['jquery'], self::version(), true);
       wp_enqueue_script('isotope-mode', self::dtdr_th_url() . 'assets/js/packery-mode.pkgd.min.js', ['jquery'], self::version(), true);
       wp_enqueue_script('jquery-masonary', self::dtdr_th_url() . 'assets/js/masonry_grid.js', ['jquery'], self::version(), true);
    }

    public function th_missing_free_plugin(){

            $screen = get_current_screen();
            if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
                return;
            }

            $plugin = 'droit-elementor-addons/plugins.php';
            $droit_plugins_name = 'Droit Essential';

            $installed_plugins = get_plugins();

            $is_elementor_installed = isset( $installed_plugins[ $plugin ] );

            if ( $is_elementor_installed ) {

                if ( ! current_user_can( 'activate_plugins' ) ) {
                    return;
                }

                $button_text = __( 'Activate Droit Elementor Addons', 'sparch-core' );
                $button_link = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

                $message = __('<strong>'.$droit_plugins_name.'</strong> requires <strong>Droit Elementor Addons Free Version</strong> plugin to be active. Please activate Droit Elementor Addons Free Version to continue.', 'sparch-core');
            } else {
                if ( ! current_user_can( 'install_plugins' ) ) {
                    return;
                }

                $button_text = __( 'Install Droit Elementor Addons', 'sparch-core' );
                $button_link = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=droit-elementor-addons'), 'install-plugin_droit-elementor-addons');
                 $message = sprintf(__('<strong>'.$droit_plugins_name.'</strong> requires <strong>Droita Elementor Addons Free Version</strong> plugin to be installed and activated. Please install Droit Elementor Addons Free Version to continue.', 'sparch-core'), '<strong>', '</strong>');
            }
            ?>
            <style>
            .notice.droit-essentoal-elementor-notice {
                border-left-color: #574ff7 !important;
                padding: 20px;
            }
            .rtl .notice.droit-essentoal-elementor-notice {
                border-right-color: #574ff7 !important;
            }
            .notice.droit-essentoal-elementor-notice .droit-essentoal-elementor-notice-inner {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .notice.droit-essentoal-elementor-notice .droit-essentoal-elementor-notice-inner .droit-essentoal-elementor-notice-icon,
            .notice.droit-essentoal-elementor-notice .droit-essentoal-elementor-notice-inner .droit-essentoal-elementor-notice-content,
            .notice.droit-essentoal-elementor-notice .droit-essentoal-elementor-notice-inner .droit-essentoal-elementor-install-now {
                display: table-row;
                align-items: center;
                justify-content: space-between;        }
            .notice.droit-essentoal-elementor-notice .droit-essentoal-elementor-notice-icon {
                color: #574ff7;
                font-size: 50px;
                width: 50px;
            }
            .notice.droit-essentoal-elementor-notice .droit-essentoal-elementor-notice-content {
                padding: 0 0px;
            }
            .notice.droit-essentoal-elementor-notice p {
                padding: 0;
                margin: 0;
            }
            .notice.droit-essentoal-elementor-notice h3 {
                margin: 0 0 5px;
            }
            .notice.droit-essentoal-elementor-notice .droit-essentoal-elementor-install-now {
                text-align: center;
            }
            .notice.droit-essentoal-elementor-notice .droit-essentoal-elementor-install-now .droit-essentoal-elementor-install-button {
                padding: 5px 30px;
                height: auto;
                line-height: 20px;
                text-transform: capitalize;
                border-color: #574ff7 !important;
                background-image: linear-gradient( 85.05deg, #574ff7 6.33%, #a533ff 102.21% )!important;
                background-image: -moz-linear-gradient( 85.05deg, #574ff7 6.33%, #a533ff 102.21%)!important;
                background-image: -webkit-linear-gradient( 85.05deg, #574ff7 6.33%, #a533ff 102.21%)!important;
                background-image: -ms-linear-gradient( 85.05deg, #574ff7 6.33%, #a533ff 102.21%)!important;
            }
            .notice.droit-essentoal-elementor-notice .droit-essentoal-elementor-install-now .droit-essentoal-elementor-install-button i {
                padding-right: 5px;
            }
            .rtl .notice.droit-essentoal-elementor-notice .droit-essentoal-elementor-install-now .droit-essentoal-elementor-install-button i {
                padding-right: 0;
                padding-left: 5px;
            }
            .notice.droit-essentoal-elementor-notice .droit-essentoal-elementor-install-now .droit-essentoal-elementor-install-button:active {
                transform: translateY(1px);
            }
            @media (max-width: 767px) {
                .notice.droit-essentoal-elementor-notice {
                    padding: 10px;
                }
            }
        </style>
            <div class="notice updated droit-essentoal-elementor-notice droit-essentoal-elementor-install-elementor">
                <div class="droit-essentoal-elementor-notice-inner">
                    <div class="droit-essentoal-elementor-notice-content">
                        <h3><?php esc_html_e( 'Thanks for installing Droit Essential!', 'sparch-core' ); ?></h3>
                        <p><?php echo $message; ?></p>
                    </div>

                    <div class="droit-essentoal-elementor-install-now">
                        <a class="button button-primary droit-essentoal-elementor-install-button" href="<?php echo esc_attr( $button_link ); ?>"><i class="dashicons dashicons-download"></i><?php echo esc_html( $button_text ); ?></a>
                    </div>
                </div>
            </div>
            <?php
    }
    public static function _run() {
        spl_autoload_register( [ __CLASS__, 'autoloading' ] );
    }

    private static function autoloading( $ld ) {
        if ( 0 !== strpos( $ld, __NAMESPACE__ ) ) {
            return;
        }
        // get map setup data
        $map = self::class_map();
        $relative_class_name = preg_replace( '/^' . __NAMESPACE__ . '\\\/', '', $ld );
        if( isset( $map[ $relative_class_name ] ) ){
            $name = $map[ $relative_class_name ];
        } else {
            $name = strtolower(preg_replace([ '/\b'.__NAMESPACE__.'\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ], [ '', '$1-$2', '-', DIRECTORY_SEPARATOR], $ld) );
            $name = str_replace('dtth-', '', $name). '.php';    
        }
        $filename = self::dtdr_th_dir() . $name;

        if ( is_readable( $filename ) ) {
           require_once( $filename );
        }
    }

     // class map
     public static function class_map(){
        return apply_filters('drth_class_mapping', [
            'Elementor\Widgets_Loader' => 'elementor/widgets/widgets-loader.php',
            'Elementor\Control_Loader' => 'elementor/controls/control-loader.php',
        ]);
    } 


    public static function css_minify($css){
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        // Remove remaining whitespace
        $css = str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '), '', $css);
        return $css;
    }

    public static function instance(){
        if ( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

}