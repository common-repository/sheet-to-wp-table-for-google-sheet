<?php
namespace STWT_Sheet_To_WP_Table\Admin;

use STWT_Sheet_To_WP_Table\App\Core\Base;
use STWT_Sheet_To_WP_Table\App\Core\Admin_Base;
use STWT_Sheet_To_WP_Table\App\Service\Products;
use STWT_Sheet_To_WP_Table\App\Http\Sheet;
use STWT_Sheet_To_WP_Table\App\Http\Api;
use STWT_Sheet_To_WP_Table\App\Handle\Quick_Table;

class Page_Loader extends Admin_Base
{

    //Has transferred to Admin_Base
    // public $main_slug = 'stwt-sync';
    public $page_folder_dir;
    public $topbar_file;
    public $topbar_sub_title;


    public $Products;
    public $Sheet;
    public $Api;
    public $Quick_Table;

    public function __construct()
    {
        parent::__construct();



        $this->page_folder_dir = $this->base_dir . 'admin/pages/';
        $this->topbar_file = $this->page_folder_dir . 'topbar.php';
        $this->topbar_sub_title = __("Dashboard", 'sheet-to-wp-table-for-google-sheet');
    }

    public function run()
    {

        add_action('admin_menu', [$this,'admin_menu']);

        add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'] );

    }

    public function admin_menu()
    {

        $capability = apply_filters( 'stwt_menu_capability', 'manage_options' );

        // $icon_url = $this->base_url . 'assets/images/stwt-icon.png';

        $page_title = __( 'Sheet to Table', 'sheet-to-wp-table-for-google-sheet' );
        $menu_title = __( 'Sheet to Table', 'sheet-to-wp-table-for-google-sheet' ); 
        $menu_slug = $this->main_slug;
        $callback = [$this,'main_page']; 
        $icon_url = 'dashicons-media-text';
        $position = 22;

        //Adding premium word at the title actually
        if( $this->is_premium ){
            $page_title .=  __( ' Premium', 'sheet-to-wp-table-for-google-sheet' );
        }

        //On main page, We will show Post of Sheet_To_Table, that's why, I set it '__return_false'
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, '__return_false', $icon_url, $position);
        //Now it's the main page menu actually
        $page_title = __( 'Getting Start', 'sheet-to-wp-table-for-google-sheet' );
        $menu_title = __( 'Getting Start', 'sheet-to-wp-table-for-google-sheet' ); 
        add_submenu_page( $menu_slug, $page_title, $menu_title, $capability, 'stwt-doc-link', $callback );
    }

    public function main_page()
    {
        
        $main_page_file = $this->page_folder_dir . 'main-page.php';
        if( ! is_file( $main_page_file ) ) return;
        include $main_page_file;
    }


    public function admin_enqueue_scripts()
    {
        global $current_screen;


        wp_register_style( $this->plugin_prefix . '-common', $this->base_url . 'assets/css/stwt-common.css', false, $this->dev_version );
        wp_enqueue_style( $this->plugin_prefix . '-common' );
        
        $s_id = isset( $current_screen->id ) ? $current_screen->id : '';
        if( strpos( $s_id, $this->plugin_prefix ) === false ) return;

        if( $current_screen->post_type !== 'stwt_shortcode_post' ){
            add_filter('admin_body_class', [$this, 'admin_body_class']);
        };

        

        //jquery
        wp_enqueue_script('jquery');

        add_filter('admin_footer_text',[$this, 'admin_footer_text']);


        //For media upload
        wp_enqueue_media();

        $backend_js_name = $this->plugin_prefix . '-admin';
        wp_register_script( $backend_js_name, $this->base_url . 'assets/js/backend.js', false, $this->dev_version );
        wp_enqueue_script( $backend_js_name );

       $ajax_url = admin_url( 'admin-ajax.php' );
       $STWT_DATA = array( 
           'ajaxurl'        => $ajax_url,
           'ajax_url'       => $ajax_url,
           'site_url'       => site_url(),
           'plugin_url'     => plugins_url(),
           'content_url'    => content_url(),
           'include_url'    => includes_url(),
           
           );
       $STWT_DATA = apply_filters( 'stwt_localize_data_admin', $STWT_DATA );
       wp_localize_script( $backend_js_name, 'STWT_DATA_ADMIN', $STWT_DATA );


        wp_register_style( $this->plugin_prefix . '-icon-font', $this->base_url . 'assets/fontello/css/stwt-icon.css', false, $this->dev_version );
        wp_enqueue_style( $this->plugin_prefix . '-icon-font' );

        
        wp_register_style( $this->plugin_prefix . '-icon-animation', $this->base_url . 'assets/fontello/css/animation.css', false, $this->dev_version );
        wp_enqueue_style( $this->plugin_prefix . '-icon-animation' );




        wp_register_style( $this->plugin_prefix . '-admin', $this->base_url . 'assets/css/dashboard.css', false, $this->dev_version );
        wp_enqueue_style( $this->plugin_prefix . '-admin' );

        //For all type css editing, which is not related with dashboard Design
        //And also this is not common which need to whole wp
        wp_register_style( $this->plugin_prefix . '-backend', $this->base_url . 'assets/css/backend.css', false, $this->dev_version );
        wp_enqueue_style( $this->plugin_prefix . '-backend' );



    }



    
    public function admin_footer_text($text)
    {
        
        return __( 'Thank you for using our Plugin', 'sheet-to-wp-table-for-google-sheet' );
    }

    public function admin_body_class( $classes )
    {
        if( is_string( $classes ) ) return $classes . ' stwt-custom-dash';
        if( is_array( $classes ) ){
            $classes[] = 'stwt-custom-dash';
            return $classes;
        };
    }
}