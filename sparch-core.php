<?php
/**
 * @package droitelementoraddons
 * @developer DroitLab Team
 */
/*
Plugin Name: sparch Core
Plugin URI: https://droitthemes.com/droit-elementor-addons/
Description: sparch Core plugin,  a plugin for sparch theme assistance 
Version: 1.0.3
Author: DroitThemes
Author URI: https://droitthemes.com/
License: GPLv3
Text Domain: sparch-core
Domain Path: /languages
 */

// If this file is called firectly, abort!!!
defined('ABSPATH') or die('Hey, what are you doing here? You silly human!');

/**
 * Constant
 * Feature added by : Droit  Team
 * @since 1.0.0
 */

define('DRO_TH_ESS', __FILE__);
define('DRO_TH_ESS_CSS_RENDER', true);

include 'plugin.php';

// load plugin
add_action( 'plugins_loaded', function(){
    // load text domain
    load_plugin_textdomain( 'sparch-core', false, basename( dirname( __FILE__ ) ) . '/languages'  );
    
    // load plugin instance
    \sparch_CORE\DRTH_Plugin::instance()->load();
}); 