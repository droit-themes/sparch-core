<?php
/**
 * @package droitelementoraddonspro
 * @developer DroitLab Team
 *
 */
namespace DROIT_ELEMENTOR_PRO\Module\Query;

use \Elementor\Group_Control_Image_Size;
use \Elementor\Plugin;

if (!defined('ABSPATH')) {exit;}

class Grid_Query
{
    protected static $instance;
    /**
     * Pages Limit
     * @since 1.0.0
     * @var integer $page_limit
     * Feature added by : DroitLab Team
     */
    public static $page_limit;

    public static $settings;
    /**
     * Constructor
     * @since 1.0.0
     * @access private
     * Feature added by : DroitLab Team
     */
    public function __construct()
    {
        add_action('pre_get_posts', array($this, 'fix_query_offset'), 1);
        add_filter('found_posts', array($this, 'fix_found_posts_query'), 1, 2);

        add_action('wp_ajax_droit_pro_get_grid_posts', array($this, 'get_grid_posts_query'));
        add_action('wp_ajax_nopriv_droit_pro_get_grid_posts', array($this, 'get_grid_posts_query'));
    }
    /**
     * Get instance of this class
     * @since 1.0.0
     * @access public
     * Feature added by : DroitLab Team
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new self();
        }
        return static::$instance;
    }
    /**
     * Get categories.
     * Retrieve the source of the categories post.
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     * @access public
     * @static
     *
     * @return string The source of the default placeholder image used by Elementor.
     */
    public static function droit_pro_categories($id = '')
    {
        $category_terms = get_categories(array('hide_empty' => true));
        //$categories     = array('' => __('All Items', 'droit-elementor-addons-pro'));
        foreach ($category_terms as $term) {
            $categories[$term->term_id] = $term->name;
        }
        if (!empty($id) and $id != 0) {
            return ($categories[$id]) ?? '';
        }
        return $categories;
    }
    /**
     * Get posts.
     * Retrieve the source of the post.
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     * @access public
     * @static
     *
     * @return string The source of the default placeholder image used by Elementor.
     */
    public static function droit_pro_posts($args = [])
    {
        $post_args = get_posts(array(
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ));
        $_posts = get_posts($post_args);

        $posts_list = [];

        foreach ($_posts as $_key => $object) {
            $posts_list[$object->ID] = $object->post_title;
        }

        return $posts_list;
    }
    /**
     * Get All Posts.
     * Returns an array of posts/pages
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     * @access public
     * @static
     */
    public static function get_all_posts()
    {

        $all_posts = get_posts(
            array(
                'posts_per_page' => -1,
                'post_type' => array('page', 'post'),
            )
        );

        if (!empty($all_posts) && !is_wp_error($all_posts)) {
            foreach ($all_posts as $post) {
                $this->options[$post->ID] = strlen($post->post_title) > 20 ? substr($post->post_title, 0, 20) . '...' : $post->post_title;
            }
        }
        return $this->options;
    }
    /**
     * Get types
     * Get posts tags array
     * Droit Pro Version
     * @return array
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public static function get_posts_types()
    {

        $post_types = get_post_types(
            array(
                'public' => true,
            ),
            'objects'
        );

        $options = array();

        foreach ($post_types as $post_type) {
            $options[$post_type->name] = $post_type->label;
        }

        return $options;
    }
    public static function _get_post_types($args = [], $array_diff_key = [])
    {
        $post_type_args = [
            'public' => true,
            'show_in_nav_menus' => true,
        ];

        // Keep for backwards compatibility
        if (!empty($args['post_type'])) {
            $post_type_args['name'] = $args['post_type'];
            unset($args['post_type']);
        }

        $post_type_args = wp_parse_args($post_type_args, $args);

        $_post_types = get_post_types($post_type_args, 'objects');

        $post_types = [];
        $post_types = array(
            'by_id' => __('Manual Selection', 'droit-elementor-addons-pro'),
        );

        foreach ($_post_types as $post_type => $object) {
            $post_types[$post_type] = $object->label;
        }
        if (!empty($array_diff_key)) {
            $post_types = array_diff_key($post_types, $array_diff_key);
        }

        return $post_types;
    }
    /**
     * Get taxnomies
     * Get post taxnomies for post type
     * Droit Pro Version
     * @return
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public static function get_taxnomies($type)
    {

        $taxonomies = get_object_taxonomies($type, 'objects');
        $data = array();

        foreach ($taxonomies as $tax_slug => $tax) {

            if (!$tax->public || !$tax->show_ui) {
                continue;
            }

            $data[$tax_slug] = $tax;
        }

        return $data;

    }

    /**
     * Get authors
     * Get posts author array
     * Droit Pro Version
     * @return
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public static function get_authors()
    {
        $users = get_users();

        $options = array();

        if (!empty($users) && !is_wp_error($users)) {
            foreach ($users as $user) {
                if ('wp_update_service' !== $user->display_name) {
                    $options[$user->ID] = $user->display_name;
                }
            }
        }

        return $options;
    }

    /**
     * Get tags
     * Get posts tags array
     * Droit Pro Version
     * @return
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public static function get_tags()
    {
        $tags = get_tags();

        $options = array();

        if (!empty($tags) && !is_wp_error($tags)) {
            foreach ($tags as $tag) {
                $options[$tag->term_id] = $tag->name;
            }
        }

        return $options;
    }

    /**
     * Get posts list
     * Get posts list  array
     * Droit Pro Version
     * @return
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public static function get_posts_list()
    {
        
        $list = get_posts(
            array(
                'post_type' => 'post',
                'posts_per_page' => -1,
            )
        );

        $options = array();

        if (!empty($list) && !is_wp_error($list)) {
            foreach ($list as $post) {
                $options[$post->ID] = $post->post_title;
            }
        }

        return $options;
    }
    /**
     * Get categories
     * Get posts categories array
     * Droit Pro Version
     * @return
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public static function get_categories()
    {

        $terms = get_terms(
            array(
                'taxonomy' => 'category',
                'hide_empty' => false,
            )
        );

        $options = array();

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }
        }

        return $options;
    }
    /**
     * Get ID By Title
     * Get Elementor Template ID by title
     * @param string $title template title.
     *
     * @return string $template_id template ID.
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     * @access public
     * @static
     */
    public static function get_id_by_title($title)
    {

        $template = get_page_by_title($title, OBJECT, 'elementor_library');

        $template_id = isset($template->ID) ? $template->ID : $title;

        return $template_id;
    }

    /**
     * Get query args
     * Get query arguments array
     * Droit Pro Version
     * @return
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public function get_query_args()
    {

        $settings = self::$settings;

        $paged = self::get_paged();
        $tax_count = 0;

        $post_type = $settings['_dl_pro_blog_grid_filter'];

        $post_args = array(
            'post_status' => 'publish',
        );
        if ( !empty($post_type) && 'by_id' !== $post_type ) {
            $post_types = array(
                'post_type' => 'post',
                'paged' => $paged,
            );
            $post_args = wp_parse_args($post_args, $post_types);
        }

        if ( !empty($post_type) && 'by_id' === $post_type ) {
            if (!empty($settings['_dl_pro_blog_grid_manual_include'])) {
                $post__in = array(
                    'post__in' => $settings['_dl_pro_blog_grid_manual_include'],
                );
                $post_args = wp_parse_args($post_args, $post__in);
            }
        }
        if (!empty($settings['_dl_pro_blog_grid_order_by']) && 'by_id' !== $post_type ) {
            $orderby = array(
                'orderby' => $settings['_dl_pro_blog_grid_order_by'],
            );
            $post_args = wp_parse_args($post_args, $orderby);
        }
        if (!empty($settings['_dl_pro_blog_grid_order']) && 'by_id' !== $post_type) {
            $order = array(
                'order' => $settings['_dl_pro_blog_grid_order'],
            );
            $post_args = wp_parse_args($post_args, $order);
        }
        if ( !empty($post_type) && 'by_id' !== $post_type ) {
            if ('yes' == $settings['_dl_pro_blog_grid_ignore_sticky_posts']) {
                $ignore_sticky = array(
                    'ignore_sticky_posts' => 1,
                );
                $post_args = wp_parse_args($post_args, $ignore_sticky);
            } else {
                $ignore_sticky = array(
                    'ignore_sticky_posts' => 0,
                );
                $post_args = wp_parse_args($post_args, $ignore_sticky);
            }
        }
        
        if (0 < $settings['_dl_pro_blog_grid_offset'] && 'by_id' !== $post_type) {
            $grid_offset = array(
                'offset_to_fix' => $settings['_dl_pro_blog_grid_offset'],
            );
            $post_args = wp_parse_args($post_args, $grid_offset);
        }
        if ( !empty($post_type) && 'by_id' !== $post_type ) {
            $posts_per_page = array(
                'posts_per_page' => empty($settings['_dl_pro_blog_grid_posts_per_page']) ? 3 : $settings['_dl_pro_blog_grid_posts_per_page'],
            );
            $post_args = wp_parse_args($post_args, $posts_per_page);
        }
        if ( !empty($post_type) && 'by_id' !== $post_type ) {
            $tax_query[] = array(
                'taxonomy' => 'post_format',
                'field'    => 'slug',
                'terms' => array( 'post-format-quote', 'post-format-link' ),
                'operator' => 'NOT IN'
            );
        }
        if ( !empty($post_type) && 'by_id' !== $post_type && 'category' == $post_type ) {
            $post_category = !empty($settings['_dl_pro_blog_grid_category']) ? $settings['_dl_pro_blog_grid_category'] : '';
                if (!empty($post_category)) {
                $tax_query[] = array(
                    'taxonomy' => 'category',
                    'field' => 'term_taxonomy_id',
                    'terms' => $post_category,
                    'operator' => 'IN',
                );
             }
         }
         if (!empty($tax_query)) {
            $tax_query = wp_parse_args(array('relation' => 'AND'), $tax_query);
            $post_args = wp_parse_args($post_args, array('tax_query' => $tax_query));
        }

        return $post_args;
    }
    /**
     * Get query posts
     *
     * Droit Pro Version
     * @return array query args
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public function get_grid_posts_query()
    {

        check_ajax_referer('droit-pro-blog-widget-nonce', 'nonce');

        if (!isset($_POST['page_id']) || !isset($_POST['widget_id'])) {
            return;
        }

        $doc_id = isset($_POST['page_id']) ? sanitize_text_field($_POST['page_id']) : '';
        $elem_id = isset($_POST['widget_id']) ? sanitize_text_field($_POST['widget_id']) : '';
        $active_cat = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';

        $elementor = Plugin::$instance;
        $meta = $elementor->documents->get($doc_id)->get_elements_data();

        $widget_data = $this->find_element_recursive($meta, $elem_id);

        $data = array(
            'ID' => '',
            'posts' => '',
            'paging' => '',
        );

        if (null !== $widget_data) {

            $widget = $elementor->elements_manager->create_element_instance($widget_data);

            $posts = $this->inner_render($widget);

            $pagination = $this->inner_pagination_render();

            $data['ID'] = $widget->get_id();
            $data['posts'] = $posts;
            $data['paging'] = $pagination;
        }

        wp_send_json_success($data);
    }
    /**
     * Get paged
     * Returns the paged number for the query.
     * Droit Pro Version
     * @return int
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public static function get_paged()
    {

        global $wp_the_query, $paged;

        if (isset($_POST['page_number']) && '' !== $_POST['page_number']) {
            return $_POST['page_number'];
        }

        // Check the 'paged' query var.
        $paged_qv = $wp_the_query->get('paged');

        if (is_numeric($paged_qv)) {
            return $paged_qv;
        }

        // Check the 'page' query var.
        $page_qv = $wp_the_query->get('page');

        if (is_numeric($page_qv)) {
            return $page_qv;
        }

        // Check the $paged global?
        if (is_numeric($paged)) {
            return $paged;
        }

        return 0;
    }

    /**
     * Set Widget Settings
     * Returns the paged number for the query.
     * Droit Pro Version
     * @return int
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public function set_widget_settings($settings)
    {
        self::$settings = $settings;
    }
    /**
     * Set Pagination Limit
     * Returns the paged number for the query.
     * Droit Pro Version
     * @return integer
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public function set_pagination_limit($pages)
    {
        self::$page_limit = $pages;
    }

    /**
     * Render Pagination
     * Written in PHP and used to generate the final HTML for pagination
     * Droit Pro Version
     * @return array query args
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public function render_pagination()
    {

        $settings = self::$settings;

        $pages = self::$page_limit;

        if (!empty($settings['_dl_pro_blog_max_pages'])) {
            $pages = min($settings['_dl_pro_blog_max_pages'], $pages);
        }

        $paged = self::get_paged();

        $current_page = $paged;
        if (!$current_page) {
            $current_page = 1;
        }

        $nav_links = paginate_links(
            array(
                'current' => $current_page,
                'total' => $pages,
                'prev_next' => 'yes' === $settings['_dl_pro_blog_pagination_strings'] ? true : false,
                'prev_text' => sprintf('« %s', $settings['_dl_pro_blog_prev_text']),
                'next_text' => sprintf('%s »', $settings['_dl_pro_blog_next_text']),
                'type' => 'array',
            )
        );

        ?>
		<nav class="droitp-blog-pagination-container" role="navigation" aria-label="<?php echo esc_attr(__('Pagination', 'droit-elementor-addons-pro')); ?>">
            <?php echo wp_kses_post(implode(PHP_EOL, $nav_links)); ?>
		</nav>
		<?php
}
    /**
     * Inner Pagination Render
     * Used to generate the pagination to be used with the AJAX call
     * Droit Pro Version
     * @return array query args
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */

    public function inner_pagination_render()
    {

        ob_start();

        $this->render_pagination();

        return ob_get_clean();

    }
    /**
     * Get query posts
     * Get query arguments array
     * Droit Pro Version
     * @return array query args
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public function get_query_posts()
    {

        $post_args = $this->get_query_args();

        $defaults = [
            
        ];

        $query_args = wp_parse_args($post_args, $defaults);
        $query = new \WP_Query($query_args);

        $total_pages = $query->max_num_pages;

        $this->set_pagination_limit($total_pages);

        return $query;
    }
    public function get_post_thumbnail()
    {

        $settings = self::$settings;
        if ( ! $settings['_dl_pro_blog_grid_show_thumb'] ) {
			return;
		}
        $settings['thumbnail'] = array(
            'id' => get_post_thumbnail_id(),
        );

        $thumbnail_html = Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail');

        if (empty($thumbnail_html)) {
            return;
        }
        if ('yes' === $settings['_dl_pro_blog_grid_read_more_new_tab']) {
            $link_target = '_blank';
        } else {
            $link_target = '_self';
        }
        ?>
        <a href="<?php the_permalink();?>" target="<?php echo esc_attr($link_target); ?>" class="dl_single_blog_img">
            <?php echo wp_kses_post($thumbnail_html); ?>
        </a>

        <?php

    }
    public function render_post_title()
    {
        $settings = self::$settings;
        if ( ! $settings['_dl_pro_blog_grid_show_title'] ) {
			return;
		}
        if (!empty($settings['_dl_pro_blog_grid_title_length'])) {
            $title_content = $this->_shorten_text(get_the_title(), $settings['_dl_pro_blog_grid_title_length'], false);
        } else {
            $title_content = get_the_title();
        }
        $this->add_render_attribute('title', 'class', 'droit-blog-grid-entry-title dl_title');
        if ('yes' === $settings['_dl_pro_blog_grid_read_more_new_tab']) {
            $link_target = '_blank';
        } else {
            $link_target = '_self';
        }

        ?>
        <a href="<?php the_permalink();?>" target="<?php echo esc_attr($link_target); ?>">
            <<?php echo wp_kses_post($settings['_dl_pro_blog_grid_title_tag'] . ' ' . $this->get_render_attribute_string('title')); ?>>
				<?php echo esc_html($title_content); ?>
                </<?php echo wp_kses_post($settings['_dl_pro_blog_grid_title_tag']); ?>>
			</a>
        <?php
}
    public function render_post_excerpt_link()
    {
        $settings = self::$settings;
        if ( ! $settings['_dl_pro_blog_grid_show_read_more'] ) {
			return;
		}
        $read_more = $settings['_dl_pro_blog_grid_read_more_text'];

        if (empty($read_more)) {
            return;
        }
        if ('yes' === $settings['_dl_pro_blog_grid_read_more_new_tab']) {
            $link_target = '_blank';
        } else {
            $link_target = '_self';
        }
        $this->add_render_attribute('_dl_pro_read_more', [
            'class' => ['dl_grid_btn', 'droit-blog-grid-entry-read-more'],
        ]);
        $get_attribute = $this->get_render_attribute_string("_dl_pro_read_more");
        echo '<a href="' . esc_url(get_permalink()) . '" target="' . $link_target . '" ' . $get_attribute . '>';
            echo wp_kses_post($read_more);
        echo '</a>';

    }
    public function render_post_content()
    {
        $settings = self::$settings;
        if ( ! $settings['_dl_pro_blog_grid_show_excerpt'] ) {
			return;
		}
        $content_type = !empty($settings['_dl_pro_blog_grid_content_type']) ? $settings['_dl_pro_blog_grid_content_type'] : 'excerpt';
        $excerpt_content = strip_shortcodes($this->_shorten_text(get_the_excerpt(), $settings['_dl_pro_blog_grid_excerpt_length'], false));
        $content = strip_shortcodes($this->_shorten_text(get_the_content(), $settings['_dl_pro_blog_grid_excerpt_length'], false));
        

        if('excerpt' === $content_type ){
           if (!has_excerpt()) {
                echo '<p class="droit__post--content dl_description">' . $content . '</p>';
            }else {
                echo '<p class="droit__post--content dl_description">' . $excerpt_content . '</p>';
            } 
        }else{
            echo '<p class="droit__post--content dl_description">' . $content . '</p>';
        }
    }
    public function get_post_meta()
    {
        $settings = self::$settings;
        if ( ! $settings['_dl_pro_blog_grid_show_meta'] ) {
			return;
		}
        $meta_data = $settings['_dl_pro_blog_grid_meta_data'];
        if (empty($meta_data)) {
            return;
        }
    ?>
        <div class="droit__blog-grid-meta dl_post_meta">
            <?php 
                if (in_array('author', $meta_data)) {
                    $this->render_author();
                }
        
                if (in_array('date', $meta_data)) {
                    $this->render_date_by_type();
                }
        
                if (in_array('time', $meta_data)) {
                    $this->render_time();
                }
        
                if (in_array('comments', $meta_data)) {
                    $this->render_comments();
                }
        
                if (in_array('modified', $meta_data)) {
                    $this->render_date_by_type('modified');
                }
            ?>
        </div>
        <?php
    }
   
    protected function render_author()
    { 
        $settings = self::$settings;
        $meta_data = $settings['_dl_pro_blog_grid_meta_data'];
        if (empty($meta_data)) {
            return;
        }
        if ( ! empty( $settings['_dl_pro_blog_grid_auth_type'] ) && 'icon' === $settings['_dl_pro_blog_grid_auth_type'] ) {
                $migrated = isset( $settings['__fa4_migrated']['_dl_pro_blog_grid_auth_selected_icon'] );

                if (  !empty( $settings['icon'] ) && ! \Elementor\Icons_Manager::is_migration_allowed() ) {
                    
                    $settings['icon'] = 'far fa-user';
                }


                $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
                $has_icon = ( ! $is_new || ! empty( $settings['_dl_pro_blog_grid_auth_selected_icon']['value'] ) );
            }

        ?>
        <span class="droit-blog-entry-author dl_post_author">
            <?php 
            if('icon' === $settings['_dl_pro_blog_grid_auth_type']){
                if ( $is_new || $migrated ) { ?>
                    <div class="droit-blog-grid-auth-media droit-blog-grid-auth_icon">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['_dl_pro_blog_grid_auth_selected_icon'] ); ?>
                    </div>
                <?php }
            }else{
                ?>
                <span class="droit-blog-grid-auth-media dl-avater-img droit-blog-grid-auth_image">
                    <?php echo get_avatar( get_the_author_meta( "ID" )); ?>
                </span>
            <?php } ?>
			<span class="dl-author-name" ><?php echo get_the_author(); ?></span>
        </span>
        <?php
    }
    
    protected function render_date()
    {
        $settings = self::$settings;
        $meta_data = $settings['_dl_pro_blog_grid_meta_data'];
        if (empty($meta_data)) {
            return;
        }
        ?>
        <span class="droit-blog-entry-date">
            <?php $this->render_date_by_type(); ?>
        </span>
        <?php
    }
    protected function render_date_by_type($type = 'publish')
    {
        $settings = self::$settings;
        
        if ( ! empty( $settings['_dl_pro_blog_grid_date_type'] ) && 'icon' === $settings['_dl_pro_blog_grid_date_type'] ) {
                $migrated = isset( $settings['__fa4_migrated']['_dl_pro_blog_grid_date_selected_icon'] );

                if (  !empty( $settings['icon'] ) && ! \Elementor\Icons_Manager::is_migration_allowed() ) {
                    
                    $settings['icon'] = 'far fa-calendar-alt';
                }


                $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
                $has_icon = ( ! $is_new || ! empty( $settings['_dl_pro_blog_grid_date_selected_icon']['value'] ) );
            }
        switch ($type):
        case 'modified':
            $date = get_the_modified_date();
            break;
        default:
            $date = get_the_date();
            endswitch;
            ?>

            <span class="droit-blog-entry-date">
                <?php 
                    if('icon' === $settings['_dl_pro_blog_grid_date_type']){
                        if ( $is_new || $migrated ) { ?>
                            <div class="droit-blog-grid-date-media droit-blog-grid-date_icon">
                                <?php \Elementor\Icons_Manager::render_icon( $settings['_dl_pro_blog_grid_date_selected_icon'] ); ?>
                            </div>
                        <?php }
                    }else{
                        ?>
                        <span class="droit-blog-grid-date-media dl-avater-img droit-blog-grid-date_image">
                            <img src="<?php echo esc_url($settings['_dl_pro_blog_grid_date_image']['url']); ?>" alt="date">
                        </span>
                    <?php } ?>
                <span class="dl-grid-date"><?php echo apply_filters('the_date', $date, get_option('date_format'), '', ''); ?></span>
            </span>
            <?php

    }
    protected function render_time()
    { 
        $settings = self::$settings;
        
        if ( ! empty( $settings['_dl_pro_blog_grid_time_type'] ) && 'icon' === $settings['_dl_pro_blog_grid_time_type'] ) {
            $migrated = isset( $settings['__fa4_migrated']['_dl_pro_blog_grid_time_selected_icon'] );

            if (  !empty( $settings['icon'] ) && ! \Elementor\Icons_Manager::is_migration_allowed() ) {
                
                $settings['icon'] = 'far fa-clock';
            }


            $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
            $has_icon = ( ! $is_new || ! empty( $settings['_dl_pro_blog_grid_time_selected_icon']['value'] ) );
        }
        ?>
        <span class="droit-blog-entry-time">
            <?php 
                if('icon' === $settings['_dl_pro_blog_grid_time_type']){
                    if ( $is_new || $migrated ) { ?>
                        <div class="droit-blog-grid-time-media droit-blog-grid-time_icon">
                            <?php \Elementor\Icons_Manager::render_icon( $settings['_dl_pro_blog_grid_time_selected_icon'] ); ?>
                        </div>
                    <?php }
                }else{
                    ?>
                    <span class="droit-blog-grid-time-media dl-avater-img droit-blog-grid-time_image">
                        <img src="<?php echo esc_url($settings['_dl_pro_blog_grid_time_image']['url']); ?>" alt="time">
                    </span>
                <?php } ?>
                <span class="dl-grid-time"><?php echo get_the_time(); ?></span>
            </span>
        <?php
    }

    protected function render_comments()
    {
        $settings = self::$settings;
        
        if ( ! empty( $settings['_dl_pro_blog_grid_comments_type'] ) && 'icon' === $settings['_dl_pro_blog_grid_comments_type'] ) {
            $migrated = isset( $settings['__fa4_migrated']['_dl_pro_blog_grid_comments_selected_icon'] );

            if (  !empty( $settings['icon'] ) && ! \Elementor\Icons_Manager::is_migration_allowed() ) {
                
                $settings['icon'] = 'far fa-comment-dots';
            }


            $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
            $has_icon = ( ! $is_new || ! empty( $settings['_dl_pro_blog_grid_comments_selected_icon']['value'] ) );
        }
        ?>
        <span class="droit-blog-entry-comments">
            <?php 
                if('icon' === $settings['_dl_pro_blog_grid_comments_type']){
                    if ( $is_new || $migrated ) { ?>
                        <div class="droit-blog-grid-comments-media droit-blog-grid-comments_icon">
                            <?php \Elementor\Icons_Manager::render_icon( $settings['_dl_pro_blog_grid_comments_selected_icon'] ); ?>
                        </div>
                    <?php }
                }else{
                    ?>
                    <span class="droit-blog-grid-comments-media dl-avater-img droit-blog-grid-comments_image">
                        <img src="<?php echo esc_url($settings['_dl_pro_blog_grid_comments_image']['url']); ?>" alt="comments">
                    </span>
                <?php } ?>
                <span class="dl-grid-comments"><?php echo comments_number(); ?></span>
            </span>
        <?php
    }
    public function render_category()
    {
        $settings = self::$settings;
        if ( ! $settings['_dl_pro_blog_grid_show_cat'] ) {
            return;
        }
        ?>
        <span class="droit-blog-entry-category dl_tag">
        <?php 

        $post_id = get_the_ID();
        $filter_category = 'category';

        $taxonomies = 'category' === $filter_category ? get_the_category( $post_id ) : get_the_tags( $post_id );
        if ( ! empty( $taxonomies ) ) {
                foreach ( $taxonomies as $index => $taxonomy ) {

                    $taxonomy_key = 'category' === $filter_category ? $taxonomy->slug : $taxonomy->name;

                    $attr_key = str_replace( ' ', '-', $taxonomy_key );

                    echo  $attr_key;
                }
            }
        ?>
        </span>
        <?php
    }
     public function ordering(){
        $settings = self::$settings;
        $_ordering = $settings['_dl_pro_blog_grid_ordering_data'];
        foreach( $_ordering as $order ) :
            
            switch( $order['_dl_pro_blog_grid_order_id'] ):
                case 'category':
                    $this->render_category();
                    break;
                case 'title':
                    $this->render_post_title();
                    break;
                case 'content':
                    $this->render_post_content();
                    break;  
                case 'meta':
                    $this->get_post_meta();
                    break;   
                case 'read_more':
                    $this->render_post_excerpt_link();
                    break;   
                
            endswitch;
        endforeach; 
    }
    /**
     * Renders post skin
     * Droit Pro Version
     * @return array query args
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public function get_post_layout()
    {
        $settings = self::$settings;

        ?>

        <div class="blog-grid-item">
            <div class="dl_single_blog_post style_8 zoom_in_effect <?php echo $settings['_dl_pro_blog_grid_content_layout']?>">
                <?php $this->get_post_thumbnail();?>
                <div class="dl_single_info_box_content">
                    <?php $this->ordering(); ?>
                </div>
            </div>
        </div>
    <?php
}
    /**
     * Renders Posts
     * Droit Pro Version
     * @return array query args
     * Feature added by : DroitLab Team
     * @since : 1.0.0
     */
    public function render_posts()
    {

        $query = $this->get_query_posts();

        $posts = $query->posts;

        if (count($posts)) {
            global $post;

            foreach ($posts as $post) {
                setup_postdata($post);
                $this->get_post_layout();
            }
        }

        wp_reset_postdata();
    }
    /**
     * Inner Render
     * Droit Pro Version
     * @since 1.0.0
     * @access public
     */
    public function inner_render($widget)
    {

        ob_start();

        $settings = $widget->get_settings();

        $this->set_widget_settings($settings);

        $this->render_posts();

        return ob_get_clean();
    }
    /**
     * Get Widget Setting data.
     *
     * @since 1.0.0
     * @param array  $elements Element array.
     * @param string $id Element ID.
     * @return Boolean True/False.
     *
     */
    public function find_element_recursive($elements, $id)
    {

        foreach ($elements as $element) {
            if ($id === $element['id']) {
                return $element;
            }

            if (!empty($element['elements'])) {
                $element = $this->find_element_recursive($element['elements'], $id);

                if ($element) {
                    return $element;
                }
            }
        }

        return false;
    }
    /**
     * Fix Query Offset.
     * Droit Pro Version
     * @since 1.0.0
     * @access public
     * @param object $query query object
     */
    public function fix_query_offset(&$query)
    {

        if (!empty($query->query_vars['offset_to_fix'])) {
            if ($query->is_paged) {
                $query->query_vars['offset'] = $query->query_vars['offset_to_fix'] + (($query->query_vars['paged'] - 1) * $query->query_vars['posts_per_page']);
            } else {
                $query->query_vars['offset'] = $query->query_vars['offset_to_fix'];
            }
        }
    }
    /**
     * Fix Found Posts Query.
     * Droit Pro Version
     * @since 1.0.0
     * @access public
     * @param int    $found_posts found posts.
     * @param object $query query object.
     */
    public function fix_found_posts_query($found_posts, $query)
    {

        $offset_to_fix = $query->get('offset_to_fix');

        if ($offset_to_fix) {
            $found_posts -= $offset_to_fix;
        }

        return $found_posts;
    }
    /**
     * Add render attribute.
     * Used to add attributes to a specific HTML element.
     * Droit Pro Version
     * @since 1.0.0
     * @access public
     */
    public function add_render_attribute($element, $key = null, $value = null, $overwrite = false)
    {
        if (is_array($element)) {
            foreach ($element as $element_key => $attributes) {
                $this->add_render_attribute($element_key, $attributes, null, $overwrite);
            }

            return $this;
        }

        if (is_array($key)) {
            foreach ($key as $attribute_key => $attributes) {
                $this->add_render_attribute($element, $attribute_key, $attributes, $overwrite);
            }

            return $this;
        }

        if (empty($this->_render_attributes[$element][$key])) {
            $this->_render_attributes[$element][$key] = array();
        }

        settype($value, 'array');

        if ($overwrite) {
            $this->_render_attributes[$element][$key] = $value;
        } else {
            $this->_render_attributes[$element][$key] = array_merge($this->_render_attributes[$element][$key], $value);
        }

        return $this;
    }

    /**
     * Get render attribute string.
     * Used to retrieve the value of the render attribute.
     * Droit Pro Version
     * @since 1.0.0
     * @access public
     */
    public function get_render_attribute_string($element)
    {
        if (empty($this->_render_attributes[$element])) {
            return '';
        }

        $render_attributes = $this->_render_attributes[$element];

        $attributes = array();

        foreach ($render_attributes as $attribute_key => $attribute_values) {
            $attributes[] = sprintf('%1$s="%2$s"', $attribute_key, esc_attr(implode(' ', $attribute_values)));
        }

        return implode(' ', $attributes);
    }
    /**
     * Get render attribute string.
     * Used to retrieve the value of the render attribute.
     * Droit Pro Version
     * @since 1.0.0
     * @access public
     * @param int    $found_posts found posts.
     * @param object $query query object.
     */
    protected function _shorten_text($text, $no_of__limit, $dot = false)
    {
        $chars_limit = $no_of__limit;
        $chars_text = strlen($text);
        $text = $text . " ";
        $text = substr($text, 0, $chars_limit);
        $text = substr($text, 0, strrpos($text, ' '));
        if ($dot == true) {

            $text = $text . "...";

        }
        return $text;
    }
}
