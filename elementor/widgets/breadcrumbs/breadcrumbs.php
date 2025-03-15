<?php
/**
 * @package droitelementoraddonspro
 * @developer DroitLab Team
 *
 */
namespace DROIT_ELEMENTOR_PRO\Widgets;

use \DROIT_ELEMENTOR_PRO\Modules\Widgets\Breadcrumbs\Breadcrumbs_Control as Control;
use \DROIT_ELEMENTOR_PRO\Modules\Widgets\Breadcrumbs\Breadcrumbs_Module as Module;
use \ELEMENTOR\Icons_Manager;

if (!defined('ABSPATH')) {exit;}

class Droit_Addons_Breadcrumbs extends Control
{

    public function get_name()
    {
        return Module::get_name();
    }

    public function get_title()
    {
        return Module::get_title();
    }

    public function get_icon()
    {
        return Module::get_icon();
    }

    public function get_categories()
    {
        return Module::get_categories();
    }

    public function get_keywords()
    {
        return Module::get_keywords();
    }

    protected function register_controls()
    {
        $this->_dl_pro_breadcrumbs_preset_controls();
        $this->_dl_pro_button_controls();
        do_action('dl_wdual_idget/section/style/custom_css', $this);
    }

    //Html render
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $query = $this->get_query();
        
        if ($query) {
            if ($query->have_posts()) {

                $this->render_breadcrumbs($query);

                wp_reset_postdata();
            }
        } else {
            $this->render_breadcrumbs();
        }
    }

    protected function get_query()
    {
        $settings = $this->get_settings_for_display();

        global $post;

        $post_type = 'any';

        $args = array(
            'post_type' => $post_type,
        );

        $post_query = new \WP_Query($args);

        return false;
    }

    protected function render_breadcrumbs($query = false)
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('breadcrumbs', 'class', array('dl-breadcrumbs', 'dl-breadcrumbs-powerpack'));
        $this->add_render_attribute('breadcrumbs-item', 'class', 'dl-breadcrumbs-item');

        // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
        $custom_taxonomy = 'product_cat';

        // Get the query & post information
        global $post, $wp_query;

        if (false === $query) {
            // Reset post data to parent query
            $wp_query->reset_postdata();

            // Set active query to native query
            $query = $wp_query;
        }

        // Do not display on the homepage
        if (!$query->is_front_page()) {

            // Build the breadcrums
            echo '<ul ' . $this->get_render_attribute_string('breadcrumbs') . '>';

            // Home page
            if ('yes' === $settings['show_home']) {
                $this->render_home_link();
            }

            if ($query->is_archive() && !$query->is_tax() && !$query->is_category() && !$query->is_tag()) {

                $this->add_render_attribute(
                    'breadcrumbs-item-archive',
                    'class',
                    array(
                        'dl-breadcrumbs-item',
                        'dl-breadcrumbs-item-current',
                        'dl-breadcrumbs-item-archive',
                    )
                );

                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-archive') . '><strong class="bread-current bread-archive">' . post_type_archive_title('', false) . '</strong></li>';

            } elseif ($query->is_archive() && $query->is_tax() && !$query->is_category() && !$query->is_tag()) {

                // If post is a custom post type
                $post_type = get_post_type();

                // If it is a custom post type display name and link
                if ('post' !== $post_type) {

                    $post_type_object = get_post_type_object($post_type);
                    $post_type_archive = get_post_type_archive_link($post_type);

                    $this->add_render_attribute(
                        array(
                            'breadcrumbs-item-cpt' => array(
                                'class' => array(
                                    'dl-breadcrumbs-item',
                                    'dl-breadcrumbs-item-cat',
                                    'dl-breadcrumbs-item-custom-post-type-' . $post_type,
                                ),
                            ),
                            'breadcrumbs-item-cpt-crumb' => array(
                                'class' => array(
                                    'dl-breadcrumbs-crumb',
                                    'dl-breadcrumbs-crumb-link',
                                    'dl-breadcrumbs-crumb-cat',
                                    'dl-breadcrumbs-crumb-custom-post-type-' . $post_type,
                                ),
                                'href' => $post_type_archive,
                                'title' => $post_type_object->labels->name,
                            ),
                        )
                    );

                    echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-cpt') . '><a ' . $this->get_render_attribute_string('breadcrumbs-item-cpt-crumb') . '>' . $post_type_object->labels->name . '</a></li>';

                    $this->render_separator();

                }

                $this->add_render_attribute(
                    array(
                        'breadcrumbs-item-tax' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-current',
                                'dl-breadcrumbs-item-archive',
                            ),
                        ),
                        'breadcrumbs-item-tax-crumb' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-current',
                            ),
                        ),
                    )
                );

                $custom_tax_name = get_queried_object()->name;

                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-tax') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-tax-crumb') . '>' . $custom_tax_name . '</strong></li>';

            } elseif ($query->is_single()) {

                $post_type = get_post_type();

                if ('post' !== $post_type) {

                    $post_type_object = get_post_type_object($post_type);
                    $post_type_archive = get_post_type_archive_link($post_type);

                    $this->add_render_attribute(
                        array(
                            'breadcrumbs-item-cpt' => array(
                                'class' => array(
                                    'dl-breadcrumbs-item',
                                    'dl-breadcrumbs-item-cat',
                                    'dl-breadcrumbs-item-custom-post-type-' . $post_type,
                                ),
                            ),
                            'breadcrumbs-item-cpt-crumb' => array(
                                'class' => array(
                                    'dl-breadcrumbs-crumb',
                                    'dl-breadcrumbs-crumb-link',
                                    'dl-breadcrumbs-crumb-cat',
                                    'dl-breadcrumbs-crumb-custom-post-type-' . $post_type,
                                ),
                                'href' => $post_type_archive,
                                'title' => $post_type_object->labels->name,
                            ),
                        )
                    );

                    echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-cpt') . '><a ' . $this->get_render_attribute_string('breadcrumbs-item-cpt-crumb') . '>' . $post_type_object->labels->name . '</a></li>';

                    $this->render_separator();

                }

                // Get post category info
                $category = get_the_category();

                if (!empty($category)) {

                    // Get last category post is in
                    $values = array_values($category);

                    $last_category = reset($values);

                    $categories = array();
                    $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','), ',');
                    $cat_parents = explode(',', $get_cat_parents);
                    foreach ($cat_parents as $parent) {
                        $categories[] = get_term_by('name', $parent, 'category');
                    }

                    // Loop through parent categories and store in variable $cat_display
                    $cat_display = '';

                    foreach ($categories as $parent) {
                        if (!is_wp_error(get_term_link($parent))) {
                            $cat_display .= '<li class="dl-breadcrumbs-item dl-breadcrumbs-item-cat"><a class="dl-breadcrumbs-crumb dl-breadcrumbs-crumb-link dl-breadcrumbs-crumb-cat" href="' . get_term_link($parent) . '">' . $parent->name . '</a></li>';
                            $cat_display .= $this->render_separator(false);
                        }
                    }
                }

                // If it's a custom post type within a custom taxonomy
                $taxonomy_exists = taxonomy_exists($custom_taxonomy);
                $taxonomy_terms = [];
                if (empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
                    $taxonomy_terms = get_the_terms($post->ID, $custom_taxonomy);
                }
                // Check if the post is in a category
                if (!empty($last_category)) {
                    echo $cat_display;

                    $this->add_render_attribute(
                        array(
                            'breadcrumbs-item-post-cat' => array(
                                'class' => array(
                                    'dl-breadcrumbs-item',
                                    'dl-breadcrumbs-item-current',
                                    'dl-breadcrumbs-item-' . $post->ID,
                                ),
                            ),
                            'breadcrumbs-item-post-cat-bread' => array(
                                'class' => array(
                                    'dl-breadcrumbs-crumb',
                                    'dl-breadcrumbs-crumb-current',
                                    'dl-breadcrumbs-crumb-' . $post->ID,
                                ),
                                'title' => get_the_title(),
                            ),
                        )
                    );

                    echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-post-cat') . '"><strong ' . $this->get_render_attribute_string('breadcrumbs-item-post-cat-bread') . '">' . get_the_title() . '</strong></li>';

                    // Else if post is in a custom taxonomy
                } elseif ($taxonomy_terms) {

                    foreach ($taxonomy_terms as $index => $taxonomy) {
                        $cat_id = $taxonomy->term_id;
                        $cat_nicename = $taxonomy->slug;
                        $cat_link = get_term_link($taxonomy->term_id, $custom_taxonomy);
                        $cat_name = $taxonomy->name;

                        $this->add_render_attribute(
                            array(
                                'breadcrumbs-item-post-cpt' => array(
                                    'class' => array(
                                        'dl-breadcrumbs-item',
                                        'dl-breadcrumbs-item-cat',
                                        'dl-breadcrumbs-item-cat-' . $cat_id,
                                        'dl-breadcrumbs-item-cat-' . $cat_nicename,
                                    ),
                                ),
                                'breadcrumbs-item-post-cpt-crumb' => array(
                                    'class' => array(
                                        'dl-breadcrumbs-crumb',
                                        'dl-breadcrumbs-crumb-link',
                                        'dl-breadcrumbs-crumb-cat',
                                        'dl-breadcrumbs-crumb-cat-' . $cat_id,
                                        'dl-breadcrumbs-crumb-cat-' . $cat_nicename,
                                    ),
                                    'href' => $cat_link,
                                    'title' => $cat_name,
                                ),
                            )
                        );

                        echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-post-cpt') . '"><a ' . $this->get_render_attribute_string('breadcrumbs-item-post-cpt-crumb') . '>' . $cat_name . '</a></li>';

                        $this->render_separator();
                    }

                    $this->add_render_attribute(
                        array(
                            'breadcrumbs-item-post' => array(
                                'class' => array(
                                    'dl-breadcrumbs-item',
                                    'dl-breadcrumbs-item-current',
                                    'dl-breadcrumbs-item-' . $post->ID,
                                ),
                            ),
                            'breadcrumbs-item-post-crumb' => array(
                                'class' => array(
                                    'dl-breadcrumbs-crumb',
                                    'dl-breadcrumbs-crumb-current',
                                    'dl-breadcrumbs-crumb-' . $post->ID,
                                ),
                                'title' => get_the_title(),
                            ),
                        )
                    );

                    echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-post') . '"><strong ' . $this->get_render_attribute_string('breadcrumbs-item-post-crumb') . '">' . get_the_title() . '</strong></li>';

                } else {

                    $this->add_render_attribute(
                        array(
                            'breadcrumbs-item-post' => array(
                                'class' => array(
                                    'dl-breadcrumbs-item',
                                    'dl-breadcrumbs-item-current',
                                    'dl-breadcrumbs-item-' . $post->ID,
                                ),
                            ),
                            'breadcrumbs-item-post-crumb' => array(
                                'class' => array(
                                    'dl-breadcrumbs-crumb',
                                    'dl-breadcrumbs-crumb-current',
                                    'dl-breadcrumbs-crumb-' . $post->ID,
                                ),
                                'title' => get_the_title(),
                            ),
                        )
                    );

                    echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-post') . '"><strong ' . $this->get_render_attribute_string('breadcrumbs-item-post-crumb') . '">' . get_the_title() . '</strong></li>';

                }
            } elseif ($query->is_category()) {

                $this->add_render_attribute(
                    array(
                        'breadcrumbs-item-cat' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-current',
                                'dl-breadcrumbs-item-cat',
                            ),
                        ),
                        'breadcrumbs-item-cat-bread' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-current',
                                'dl-breadcrumbs-crumb-cat',
                            ),
                        ),
                    )
                );

                // Category page
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-cat') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-cat-bread') . '>' . single_cat_title('', false) . '</strong></li>';

            } elseif ($query->is_page()) {

                // Standard page
                if ($post->post_parent) {

                    // If child page, get parents
                    $anc = get_post_ancestors($post->ID);

                    // Get parents in the right order
                    $anc = array_reverse($anc);

                    // Parent page loop
                    if (!isset($parents)) {
                        $parents = null;
                    }
                    foreach ($anc as $ancestor) {
                        $parents .= '<li class="dl-breadcrumbs-item dl-breadcrumbs-item-parent dl-breadcrumbs-item-parent-' . $ancestor . '"><a class="dl-breadcrumbs-crumb dl-breadcrumbs-crumb-link dl-breadcrumbs-crumb-parent dl-breadcrumbs-crumb-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';

                        $parents .= $this->render_separator(false);
                    }

                    // Display parent pages
                    echo $parents;

                }

                $this->add_render_attribute(
                    array(
                        'breadcrumbs-item-page' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-current',
                                'dl-breadcrumbs-item-' . $post->ID,
                            ),
                        ),
                        'breadcrumbs-item-page-crumb' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-current',
                                'dl-breadcrumbs-crumb-' . $post->ID,
                            ),
                            'title' => get_the_title(),
                        ),
                    )
                );

                // Just display current page if not parents
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-page') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-page-crumb') . '>' . get_the_title() . '</strong></li>';

            } elseif ($query->is_tag()) {

                // Tag page

                // Get tag information
                $term_id = get_query_var('tag_id');
                $taxonomy = 'post_tag';
                $args = 'include=' . $term_id;
                $terms = get_terms($taxonomy, $args);
                $get_term_id = $terms[0]->term_id;
                $get_term_slug = $terms[0]->slug;
                $get_term_name = $terms[0]->name;

                $this->add_render_attribute(
                    array(
                        'breadcrumbs-item-tag' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-current',
                                'dl-breadcrumbs-item-tag-' . $get_term_id,
                                'dl-breadcrumbs-item-tag-' . $get_term_slug,
                            ),
                        ),
                        'breadcrumbs-item-tag-bread' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-current',
                                'dl-breadcrumbs-crumb-tag-' . $get_term_id,
                                'dl-breadcrumbs-crumb-tag-' . $get_term_slug,
                            ),
                            'title' => get_the_title(),
                        ),
                    )
                );

                // Display the tag name
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-tag') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-tag-bread') . '>' . $get_term_name . '</strong></li>';

            } elseif ($query->is_day()) {

                $this->add_render_attribute(
                    array(
                        'breadcrumbs-item-year' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-year',
                                'dl-breadcrumbs-item-year-' . get_the_time('Y'),
                            ),
                        ),
                        'breadcrumbs-item-year-crumb' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-link',
                                'dl-breadcrumbs-crumb-year',
                                'dl-breadcrumbs-crumb-year-' . get_the_time('Y'),
                            ),
                            'href' => get_year_link(get_the_time('Y')),
                            'title' => get_the_time('Y'),
                        ),
                        'breadcrumbs-item-month' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-month',
                                'dl-breadcrumbs-item-month-' . get_the_time('m'),
                            ),
                        ),
                        'breadcrumbs-item-month-crumb' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-link',
                                'dl-breadcrumbs-crumb-month',
                                'dl-breadcrumbs-crumb-month-' . get_the_time('m'),
                            ),
                            'href' => get_month_link(get_the_time('Y'), get_the_time('m')),
                            'title' => get_the_time('M'),
                        ),
                        'breadcrumbs-item-day' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-current',
                                'dl-breadcrumbs-item-' . get_the_time('j'),
                            ),
                        ),
                        'breadcrumbs-item-day-crumb' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-current',
                                'dl-breadcrumbs-crumb-' . get_the_time('j'),
                            ),
                        ),
                    )
                );

                // Year link
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-year') . '><a ' . $this->get_render_attribute_string('breadcrumbs-item-year-crumb') . '>' . get_the_time('Y') . ' ' . __('Archives', 'droit-elementor-addons-pro') . '</a></li>';

                $this->render_separator();

                // Month link
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-month') . '><a ' . $this->get_render_attribute_string('breadcrumbs-item-month-crumb') . '>' . get_the_time('M') . ' ' . __('Archives', 'droit-elementor-addons-pro') . '</a></li>';

                $this->render_separator();

                // Day display
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-day') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-day-crumb') . '> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' ' . __('Archives', 'droit-elementor-addons-pro') . '</strong></li>';

            } elseif ($query->is_month()) {

                $this->add_render_attribute(
                    array(
                        'breadcrumbs-item-year' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-year',
                                'dl-breadcrumbs-item-year-' . get_the_time('Y'),
                            ),
                        ),
                        'breadcrumbs-item-year-crumb' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-year',
                                'dl-breadcrumbs-crumb-year-' . get_the_time('Y'),
                            ),
                            'href' => get_year_link(get_the_time('Y')),
                            'title' => get_the_time('Y'),
                        ),
                        'breadcrumbs-item-month' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-month',
                                'dl-breadcrumbs-item-month-' . get_the_time('m'),
                            ),
                        ),
                        'breadcrumbs-item-month-crumb' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-month',
                                'dl-breadcrumbs-crumb-month-' . get_the_time('m'),
                            ),
                            'title' => get_the_time('M'),
                        ),
                    )
                );

                // Year link
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-year') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-year-crumb') . '>' . get_the_time('Y') . ' ' . __('Archives', 'droit-elementor-addons-pro') . '</strong></li>';

                $this->render_separator();

                // Month display
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-month') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-month-crumb') . '>' . get_the_time('M') . ' ' . __('Archives', 'droit-elementor-addons-pro') . '</strong></li>';

            } elseif ($query->is_year()) {

                $this->add_render_attribute(
                    array(
                        'breadcrumbs-item-year' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-current',
                                'dl-breadcrumbs-item-current-' . get_the_time('Y'),
                            ),
                        ),
                        'breadcrumbs-item-year-crumb' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-current',
                                'dl-breadcrumbs-crumb-current-' . get_the_time('Y'),
                            ),
                            'title' => get_the_time('Y'),
                        ),
                    )
                );

                // Display year archive
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-year') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-year-crumb') . '>' . get_the_time('Y') . ' ' . __('Archives', 'droit-elementor-addons-pro') . '</strong></li>';

            } elseif ($query->is_author()) {

                // Get the author information
                global $author;
                $userdata = get_userdata($author);

                $this->add_render_attribute(
                    array(
                        'breadcrumbs-item-author' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-current',
                                'dl-breadcrumbs-item-current-' . $userdata->user_nicename,
                            ),
                        ),
                        'breadcrumbs-item-author-bread' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-current',
                                'dl-breadcrumbs-crumb-current-' . $userdata->user_nicename,
                            ),
                            'title' => $userdata->display_name,
                        ),
                    )
                );

                // Display author name
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-author') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-author-bread') . '>' . __('Author:', 'droit-elementor-addons-pro') . ' ' . $userdata->display_name . '</strong></li>';

            } elseif (get_query_var('paged')) {

                $this->add_render_attribute(
                    array(
                        'breadcrumbs-item-paged' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-current',
                                'dl-breadcrumbs-item-current-' . get_query_var('paged'),
                            ),
                        ),
                        'breadcrumbs-item-paged-bread' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-current',
                                'dl-breadcrumbs-crumb-current-' . get_query_var('paged'),
                            ),
                            'title' => __('Page', 'droit-elementor-addons-pro') . ' ' . get_query_var('paged'),
                        ),
                    )
                );

                // Paginated archives
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-paged') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-paged-bread') . '>' . __('Page', 'droit-elementor-addons-pro') . ' ' . get_query_var('paged') . '</strong></li>';

            } elseif ($query->is_search()) {

                // Search results page
                $this->add_render_attribute(
                    array(
                        'breadcrumbs-item-search' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-current',
                                'dl-breadcrumbs-item-current-' . get_search_query(),
                            ),
                        ),
                        'breadcrumbs-item-search-crumb' => array(
                            'class' => array(
                                'dl-breadcrumbs-crumb',
                                'dl-breadcrumbs-crumb-current',
                                'dl-breadcrumbs-crumb-current-' . get_search_query(),
                            ),
                            'title' => __('Search results for:', 'droit-elementor-addons-pro') . ' ' . get_search_query(),
                        ),
                    )
                );

                // Search results page
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-search') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-search-crumb') . '>' . __('Search results for:', 'droit-elementor-addons-pro') . ' ' . get_search_query() . '</strong></li>';

            } elseif ($query->is_home()) {
                $blog_label = $settings['blog_text'];

                if ($blog_label) {
                    $this->add_render_attribute(
                        array(
                            'breadcrumbs-item-blog' => array(
                                'class' => array(
                                    'dl-breadcrumbs-item',
                                    'dl-breadcrumbs-item-current',
                                ),
                            ),
                            'breadcrumbs-item-blog-crumb' => array(
                                'class' => array(
                                    'dl-breadcrumbs-crumb',
                                    'dl-breadcrumbs-crumb-current',
                                ),
                                'title' => $blog_label,
                            ),
                        )
                    );

                    // Just display current page if not parents
                    echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-blog') . '><strong ' . $this->get_render_attribute_string('breadcrumbs-item-blog-crumb') . '>' . $blog_label . '</strong></li>';
                }
            } elseif ($query->is_404()) {
                $this->add_render_attribute(
                    array(
                        'breadcrumbs-item-error' => array(
                            'class' => array(
                                'dl-breadcrumbs-item',
                                'dl-breadcrumbs-item-current',
                            ),
                        ),
                    )
                );

                // 404 page
                echo '<li ' . $this->get_render_attribute_string('breadcrumbs-item-error') . '>' . __('Error 404', 'droit-elementor-addons-pro') . '</li>';
            }

            echo '</ul>';

        }

    }
    protected function get_separator()
    {
        $settings = $this->get_settings_for_display();

        ob_start();
        if ('icon' === $settings['separator_type']) {

            if (!isset($settings['separator_icon']) && !Icons_Manager::is_migration_allowed()) {
                // add old default
                $settings['separator_icon'] = 'fa fa-angle-right';
            }

            $has_icon = !empty($settings['separator_icon']);

            if ($has_icon) {
                $this->add_render_attribute('separator-icon', 'class', $settings['separator_icon']);
                $this->add_render_attribute('separator-icon', 'aria-hidden', 'true');
            }

            if (!$has_icon && !empty($settings['select_separator_icon']['value'])) {
                $has_icon = true;
            }
            $migrated = isset($settings['__fa4_migrated']['select_separator_icon']);
            $is_new = !isset($settings['separator_icon']) && Icons_Manager::is_migration_allowed();

            if ($has_icon) {
                ?>
                    <span class='dl-separator-icon dl-icon'>
                        <?php
                        if ($is_new || $migrated) {
                                            Icons_Manager::render_icon($settings['select_separator_icon'], array('aria-hidden' => 'true'));
                                        } elseif (!empty($settings['separator_icon'])) {
                                            ?>
                                                <i <?php echo $this->get_render_attribute_string('separator-icon'); ?>></i>
                                                <?php
                        }
                                        ?>
                                        </span>
                                        <?php
                        }
            } else {

            $this->add_inline_editing_attributes('separator_text');
            $this->add_render_attribute('separator_text', 'class', 'dl-breadcrumbs-separator-text');

            echo '<span ' . $this->get_render_attribute_string('separator_text') . '>' . $settings['separator_text'] . '</span>';

        }
        $separator = ob_get_contents();
        ob_end_clean();

        return $separator;
    }

    protected function render_separator($output = true)
    {
        $settings = $this->get_settings_for_display();

        $html = '<li class="dl-breadcrumbs-separator">';
        $html .= $this->get_separator();
        $html .= '</li>';

        if (true === $output) {
            echo $html;
            return;
        }

        return $html;
    }

    protected function render_home_link()
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute(
            array(
                'home_item' => array(
                    'class' => array(
                        'dl-breadcrumbs-item',
                        'dl-breadcrumbs-item-home',
                    ),
                ),
                'home_link' => array(
                    'class' => array(
                        'dl-breadcrumbs-crumb',
                        'dl-breadcrumbs-crumb-link',
                        'dl-breadcrumbs-crumb-home',
                    ),
                    'href' => get_home_url(),
                    'title' => $settings['home_text'],
                ),
                'home_text' => array(
                    'class' => array(
                        'dl-breadcrumbs-text',
                    ),
                ),
            )
        );

        if (!isset($settings['home_icon']) && !Icons_Manager::is_migration_allowed()) {
            // add old default
            $settings['home_icon'] = 'fa fa-home';
        }

        $has_home_icon = !empty($settings['home_icon']);

        if ($has_home_icon) {
            $this->add_render_attribute('i', 'class', $settings['home_icon']);
            $this->add_render_attribute('i', 'aria-hidden', 'true');
        }

        if (!$has_home_icon && !empty($settings['select_home_icon']['value'])) {
            $has_home_icon = true;
        }
        $migrated_home_icon = isset($settings['__fa4_migrated']['select_home_icon']);
        $is_new_home_icon = !isset($settings['home_icon']) && Icons_Manager::is_migration_allowed();
        ?>
        <li <?php echo $this->get_render_attribute_string('home_item'); ?>>
            <a <?php echo $this->get_render_attribute_string('home_link'); ?>>
                <span <?php echo $this->get_render_attribute_string('home_text'); ?>>
                    <?php if (!empty($settings['home_icon']) || (!empty($settings['select_home_icon']['value']) && $is_new_home_icon)) {?>
                        <span class="dl-icon">
                            <?php
                                if ($is_new_home_icon || $migrated_home_icon) {
                                            Icons_Manager::render_icon($settings['select_home_icon'], array('aria-hidden' => 'true'));
                                        } elseif (!empty($settings['home_icon'])) {
                                            ?>
                                                                <i <?php echo $this->get_render_attribute_string('i'); ?>></i>
                                                                <?php
                                }
                                            ?>
                        </span>
                    <?php }?>
                    <?php echo $settings['home_text']; ?>
                </span>
            </a>
        </li>
        <?php

        $this->render_separator();
    }

    protected function content_template()
    {}
}