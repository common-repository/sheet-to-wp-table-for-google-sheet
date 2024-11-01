<?php
namespace STWT_Sheet_To_WP_Table\Admin;

use STWT_Sheet_To_WP_Table\App\Core\Admin_Base;
use STWT_Sheet_To_WP_Table\Admin\Page_Loader;
use STWT_Sheet_To_WP_Table\App\Service\Post\Admin_Post;
class Admin_Loader extends Admin_Base
{
    public function __construct()
    {
        
    }

    public function init()
    {


        $page_loader = new Page_Loader();
        $page_loader->run();

        Admin_Post::init();

        //Plugin Page menu handle
        add_filter('plugin_action_links_' . $this->base_file, [$this,'plugin_action_links']);

    }

    public function plugin_action_links( $links )
    {
        $my_links = [];
        $setting_link = admin_url( 'edit.php?post_type=stwt_shortcode_post' );
        $my_links[] =  '<a href="' . esc_url( $setting_link ) . '" title="' . esc_attr__( 'Add New Table', 'sheet-to-wp-table-for-google-sheet' ) . '" target="_blank">' . esc_html__( 'Add New Table','sheet-to-wp-table-for-google-sheet' ).'</a>';
        return array_merge( $my_links, $links );
    }


}