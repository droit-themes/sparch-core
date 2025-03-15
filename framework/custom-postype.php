<?php

/**
 *
 * Custom Post Type And Taxonomies Class
 *
 */

namespace Dt_custom_postype;

if ( ! defined( 'ABSPATH' ) ) exit;

class Dt_CustomPosttype {

  private $dt_posts;

    public function __construct( ){

        $this->dt_posts = array();

        add_action('init', array($this, 'register_custom_post'));
    }

    public function dt_postype( $type, $singular_label, $plural_label, $settings = array() ){
        
        $default_settings = array(
            'labels' => array(
                'name' => __($plural_label, 'sparch-core'),
                'singular_name' => __($singular_label, 'sparch-core'),
                'add_new_item' => __('Add New '.$singular_label, 'sparch-core'),
                'edit_item'=> __('Edit '.$singular_label, 'sparch-core'),
                'new_item'=>__('New '.$singular_label, 'sparch-core'),
                'view_item'=>__('View '.$singular_label, 'sparch-core'),
                'search_items'=>__('Search '.$plural_label, 'sparch-core'),
                'not_found'=>__('No '.$plural_label.' found', 'sparch-core'),
                'not_found_in_trash'=>__('No '.$plural_label.' found in trash', 'sparch-core'),
                'parent_item_colon'=>__('Parent '.$singular_label, 'sparch-core'),
                'menu_name' => __($plural_label,'sparch-core')
            ),
            'public'=>true,
            'has_archive' => true,
            'menu_position'=>20,
            'supports'=>array(
                'title',
                'editor',
                'thumbnail',
                'excerpt'
            ),
            'rewrite' => array(
                'slug' => sanitize_title_with_dashes($plural_label)
            )
        );
        $this->dt_posts[$type] = array_merge($default_settings, $settings);
    }

    public function register_custom_post(){
        foreach($this->dt_posts as $key=>$value) {
            register_post_type($key, $value);
            flush_rewrite_rules( false );
        }
    }
    
}

class Dt_Taxonomies {

    protected $taxonomies;

    public function __construct ( ){

        $this->taxonomies = array();
        add_action('init', array($this, 'register_taxonomy'));

    }

    public function dt_taxonomy( $type, $singular_label, $plural_label, $post_types, $settings = array() ){
        $default_settings = array(
            'labels' => array(
                'name' => __($plural_label, 'sparch-core'),
                'singular_name' => __($singular_label, 'sparch-core'),
                'add_new_item' => __('New '.$singular_label.' Name', 'sparch-core'),
                'new_item_name' => __('Add New '.$singular_label, 'sparch-core'),
                'edit_item'=> __('Edit '.$singular_label, 'sparch-core'),
                'update_item'=> __('Update '.$singular_label, 'sparch-core'),
                'add_or_remove_items'=> __('Add or remove '.strtolower($plural_label), 'sparch-core'),
                'search_items'=>__('Search '.$plural_label, 'sparch-core'),
                'popular_items'=>__('Popular '.$plural_label, 'sparch-core'),
                'all_items'=>__('All '.$plural_label, 'sparch-core'),
                'parent_item'=>__('Parent '.$singular_label, 'sparch-core'),
                'choose_from_most_used'=> __('Choose from the most used '.strtolower($plural_label), 'sparch-core'),
                'parent_item_colon'=>__('Parent '.$singular_label, 'sparch-core'),
                'menu_name'=>__($singular_label, 'sparch-core'),
            ),

            'public'=>true,
            'show_in_nav_menus' => true,
            'show_admin_column' => false,
            'hierarchical'      => true,
            'show_tagcloud'     => false,
            'show_ui'           => true,
            'rewrite' => array(
                'slug' => sanitize_title_with_dashes($plural_label)
            )
        );

        $this->taxonomies[$type]['post_types'] = $post_types;
        $this->taxonomies[$type]['args'] = array_merge($default_settings, $settings);       
    }

    public function register_taxonomy(){
        foreach($this->taxonomies as $key => $value) {
            register_taxonomy($key, $value['post_types'], $value['args']);
        }
    }

}