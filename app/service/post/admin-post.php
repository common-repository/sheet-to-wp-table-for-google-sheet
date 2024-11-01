<?php
namespace STWT_Sheet_To_WP_Table\App\Service\Post;

use STWT_Sheet_To_WP_Table\App\Service\Standalone;

use STWT_Sheet_To_WP_Table\App\Service\Post\Meta_Box;
use STWT_Sheet_To_WP_Table\App\Service\Post\Post_Settings;


class Admin_Post extends Post_Settings
{
    use Standalone;

    public function __construct()
    {
        add_action( 'init', [$this, 'custom_post_type'], 0 );
        Meta_Box::init();
    }
    // Register Custom Post Type
    public function custom_post_type() {

        $labels = array(
            'name'                  => _x( 'Sheet Tables', 'Post Type General Name', 'text_domain' ),
            'singular_name'         => _x( 'Sheet Table', 'Post Type Singular Name', 'text_domain' ),
            'menu_name'             => __( 'Sheet Tables', 'text_domain' ),
            'name_admin_bar'        => __( 'Sheet Tables', 'text_domain' ),
            'archives'              => __( 'Table Archives', 'text_domain' ),
            'attributes'            => __( 'Table Attributes', 'text_domain' ),
            'parent_item_colon'     => __( 'Parent Table:', 'text_domain' ),
            'all_items'             => __( 'All Tables', 'text_domain' ),
            'add_new_item'          => __( 'Add New Table', 'text_domain' ),
            'add_new'               => __( 'Add New', 'text_domain' ),
            'new_item'              => __( 'New Table', 'text_domain' ),
            'edit_item'             => __( 'Edit Table', 'text_domain' ),
            'update_item'           => __( 'Update Table', 'text_domain' ),
            'view_item'             => __( 'View Table', 'text_domain' ),
            'view_items'            => __( 'View Tables', 'text_domain' ),
            'search_items'          => __( 'Search Table', 'text_domain' ),
            'not_found'             => __( 'Not found', 'text_domain' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
            'featured_image'        => __( 'Featured Image', 'text_domain' ),
            'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
            'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
            'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
            'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
            'items_list'            => __( 'Tables list', 'text_domain' ),
            'items_list_navigation' => __( 'Tables list navigation', 'text_domain' ),
            'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
        );

        $args = array(
            'label'                 => __( 'Sheet Table', 'text_domain' ),
            'description'           => __( 'Post Type Description', 'text_domain' ),
            'labels'                => $labels,
            'supports'              => array( 'title' ),
            'hierarchical'          => true,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => $this->main_slug,//'stwt-sync', //it will false actually
            'menu_position'         => 21,
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => false,
            'can_export'            => false,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'rewrite'               => null,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
            'rest_base'             => 'stwt_shortcode/v2',
        );
        register_post_type( $this->post_type, $args ); //'stwp_shortcode_post'

    }

}