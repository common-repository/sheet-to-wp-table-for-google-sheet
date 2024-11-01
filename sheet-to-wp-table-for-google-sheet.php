<?php
/**
 * Plugin Name: Sheet to Table Live Sync for Google Sheet
 * Plugin URI: https://codeastrology.com/sheet-to-table-live-sync-from-google-sheet/
 * Description: Sync Google Sheets live on WordPress. Lightning-fast, cached tables using shortcodes or the intuitive Dashboard interface. Effortlessly display dynamic data, enhancing your site's functionality.
 * Author: Saiful Islam
 * Author URI: https://profiles.wordpress.org/codersaiful/#content-plugins
 * Tags: stock sync with google sheet, google sheet sync, bulk edit product
 * 
 * Version: 1.0.2
 * Requires at least:    4.0.0
 * Requires PHP:         7.2
 * Tested up to:         6.6.1
 * 
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 * @package STWT_Sheet_To_WP_Table
 * 
 * Text Domain: sheet-to-wp-table
 * Domain Path: /languages/
 * 
 * 
 * Sheet to Table Live Sync for Google Sheet is free software: 
 * you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * Sheet to Table Live Sync for Google Sheet is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  

if( ! defined( 'STWT_DEV_VERSION' ) ){
    define( "STWT_DEV_VERSION", '1.0.2.0' );
}
if( ! defined( 'STWT_PLUGIN_NAME' ) ){
    define( "STWT_PLUGIN_NAME", __( 'Sheet to Table Live Sync for Google Sheet', 'sheet-to-wp-table-for-google-sheet' ) );
}

if( ! defined( 'STWT_BASE_URL' ) ){
    define( "STWT_BASE_URL", plugins_url() . '/'. plugin_basename( dirname( __FILE__ ) ) . '/' );
}

if( ! defined( 'STWT_BASE_FILE' ) ){
    define( "STWT_BASE_FILE", plugin_basename( __FILE__ ) );
}
if( ! defined( 'STWT_BASE_FOLDER_NAME' ) ){
    define( "STWT_BASE_FOLDER_NAME", plugin_basename(__DIR__) );
}

if( ! defined( 'STWT_ASSETS_URL' ) ){
    define( "STWT_ASSETS_URL", STWT_BASE_URL . 'assets/' );
}

if( ! defined( 'STWT_DIR_BASE' ) ){
    define( "STWT_DIR_BASE", dirname( __FILE__ ) . '/' );
}
if( ! defined( 'STWT_BASE_DIR' ) ){
    define( "STWT_BASE_DIR", str_replace( '\\', '/', STWT_DIR_BASE ) );
}
if( ! defined( 'STWT_PREFIX' ) ){
    define( "STWT_PREFIX", 'stwt' );
}

class STWT_Sheet_To_WP_Table_Init
{
    public static $instance;
    public $textdomain_load = false;
    public static function instance()
    {
        if( is_null( self::$instance ) ){
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'init']);

        //Text domain to be load before init() method call.
        add_action('plugins_loaded', [$this, 'load_textdomain'], 0);
    }
    /**
     * Initializes the plugin by including the necessary files and initializing the admin and frontend functionality.
     *
     * This function includes the autoloader.php file and the functions.php file from the app directory. It then creates an instance of the Admin_Loader class and calls its init() method. Finally, it initializes the Shortcode service and triggers the 'stwt_init' action.
     *
     * @return void
     */
    public function init()
    {

        
        include_once STWT_BASE_DIR . 'autoloader.php';
        include_once STWT_BASE_DIR . 'app/functions.php';

        //Not only Admin/But also frontend handle also added over there, such: API - it's not for login user.
        $admin = new STWT_Sheet_To_WP_Table\Admin\Admin_Loader();
        $admin->init();


        STWT_Sheet_To_WP_Table\App\Service\Shortcode::init();

        do_action( 'stwt_init' );
    }

    /**
     * Loads the text domain for the plugin.
     *
     * @return void
     */
    public function load_textdomain() {
        if( $this->textdomain_load ) return;
        load_plugin_textdomain( 'sheet-to-wp-table-for-google-sheet', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
        $this->textdomain_load = true;
    }


}

STWT_Sheet_To_WP_Table_Init::instance();

