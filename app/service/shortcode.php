<?php
namespace STWT_Sheet_To_WP_Table\App\Service;

use STWT_Sheet_To_WP_Table\App\Core\Base;
use STWT_Sheet_To_WP_Table\App\Service\Standalone;
use STWT_Sheet_To_WP_Table\App\Http\Sheet;


class Shortcode extends Base
{

    use Standalone;

    public $atts;
    public $refresh;

    public $Sheet;
    public $CSV;

    public $table_row_limit = 1000; //this is normal limit. but it can change by filter. 
    public $final_row_limit = 2000; //It's final limit of a table. 


    public $datatable_options;

    /**
     * Specially for Footer admin area
     * means: post edit part did here
     *
     * @var boolean
     */
    public $frontend_login_user = false;

    
    public function __construct()
    {
        /**
         * Available param of Shortcode are:
         * id: 1tFEXb9IrxJh3aRGIYnExXZzBiqakfoWLDcghaUoaLus (sample Sheet ID)
         * sheet_name=Sheet2
         * 
         * Sheet Link: https://docs.google.com/spreadsheets/d/1tFEXb9IrxJh3aRGIYnExXZzBiqakfoWLDcghaUoaLus/edit#gid=957554013
         * 
         * 
         */
        add_shortcode( 'STWT_Sheet_Table', [$this, 'add_shortcode'] );

        add_action( 'stwt_table_footer', [$this, 'wp_enqueue_scripts'] );
    }

    /**
     * Table Generate from Sheet
     *
     * @param [type] $atts
     * @return void
     */
    public function add_shortcode( $atts )
    {
        $this->atts = $atts;
        
        ob_start();

        $this->Sheet = new Sheet( $this->atts );

        $this->frontend_login_user = is_user_logged_in() && $this->Sheet->post_id && ! is_admin();
        
        $csv = $this->Sheet->getCSV();

        if( ! empty($csv) && empty( $this->Sheet->get_errors() ) ){
            $this->display_table( $csv );
        }else{
            $this->Sheet->render_errors();
        }

        /**
         * This action Hook Should be bottom of Whole Shortcode
         * We also can add something using this hook actually.
         */
        $this->do_action('stwt_table_footer');

        return ob_get_clean();

    }

    public function display_table( $csv )
    {
        if( empty( $csv ) ) return;
        $rows = array_map('str_getcsv', explode("\n", $csv));
        $this->header = array_shift($rows);

        $this->datatable_options = apply_filters('stwt_datatable_options',[
            'pageLength' => 25,
            'aaSorting' => [
                // [0, 'asc'], //Only one can be active here actually
                // [1, 'desc'], //Column number wise sorting. Here 2ns column desc sorting korechi
            ],
            'ordering' =>  true, //Ordering feature on or off
            'aLengthMenu' =>  [5, 10, 25, 50, 100],
            // bPaginate: false, //Pagination on off korar jonno
            'layout' => [
                 //Customize Layout of top and bottom of table
                // 'bottomEnd' => ['info','paging', 'search'],
                // 'bottomStart' => ['info','paging', 'search'],
                // 'topEnd' => ['info','paging', 'search'],
                // 'topStart' => ['info','paging', 'search'],

                // 'topStart' => ['csv', 'excel', 'pdf', 'print']

            ],
            'oLanguage' => [
                'sEmptyTable' => __( 'No data available in table', 'sheet-to-wp-table-for-google-sheet' ),
                'sSearch' => __( 'Find Here', 'sheet-to-wp-table-for-google-sheet' ), //Search
                'sZeroRecords' => __( "No matching records found", 'sheet-to-wp-table-for-google-sheet' ),
                'sInfo' => __( 'Showing _START_ to _END_ of _TOTAL_ _ENTRIES-TOTAL_', 'sheet-to-wp-table-for-google-sheet' ), //_ENTRIES-TOTAL_ it's meaning here entries
                'sInfoEmpty' => __( 'Showing 0 to 0 of 0 _ENTRIES-TOTAL_', 'sheet-to-wp-table-for-google-sheet' ), //showing 0
                'sLoadingRecords' => __( 'Loading Table....', 'sheet-to-wp-table-for-google-sheet' ), //Loading...
                'sLengthMenu' => __( "_MENU_ _ENTRIES_ per page", 'sheet-to-wp-table-for-google-sheet' ),
                ]
        ], $this->Sheet->post_id, $this);
        if(is_array($this->datatable_options)){
            $this->datatable_options = array_filter( $this->datatable_options );
        }else{
            $this->datatable_options = [];
        }

        
        /**
         * By this Action Hook
         * User able to change Whole Class property
         * Action @Hook 'stwt_load'
         * 
         * Also for before Table, User can use this action hook too
         * 
         * @author Saiful Islam <codersaiful@gmail.com>
         * @since 1.0.0.2
         */
        $this->do_action( 'stwt_load' );
        ?>

        <table class="sheet-to-wp-table compact stripe" id="stwt-sheet-to-wp-table" data-tr_status="<?php echo esc_attr( $this->Sheet->tr_status ); ?>" style="width: 100%">
        <?php $this->get_header( $this->header ); ?>
        <tbody>
        <?php 
        $this->table_row_limit = $this->table_row_limit - 1;
        foreach ($rows as $row_id => $row) {

            /**
             * Table Row limit and Final Row limit
             * Actually $final_row_limit mean: maximum Row should this amount actually
             */
            if($row_id > $this->table_row_limit || $row_id > $this->final_row_limit ) break;
            $this->get_each_row( $row, $row_id );
        }
        ?>
        </tbody>

        </table>
        <?php
        $this->footer_admin_edit_area();
        
    }

    private function get_each_row( $row, $row_id )
    {

        ?>
        <tr class="stwt-row-id-<?php echo esc_attr( $row_id ); ?> stwt-content-row" data-index="<?php echo esc_attr( $row_id ); ?>">
        <?php
        foreach ($row as $cell_index => $cell) {
            $cell_class = $this->header[$cell_index] ?? '';
            $cell_class = sanitize_html_class( $cell_class );
            ?>
            <td class="tr-td <?php echo esc_attr( $cell_class ); ?>" data-index="<?php echo esc_attr( $cell_index ); ?>">
                <?php echo esc_html( htmlspecialchars( $cell ) ); ?>
            </td>
            <?php

        }
        ?>
        </tr>
        <?php
    }

    private function get_header( $header )
    {
        if( empty( $header ) ) return;
        ?>
        <thead>
        <tr class="stwt-header-row">
        <?php 
        foreach( $header as $index => $col ){
            $cell_class = sanitize_html_class( $col );
            echo '<th class=" tr-th ' . esc_attr( $cell_class ) . '" data-index="' . esc_attr( $index ) . '">' . esc_html( htmlspecialchars($col) ) . '</th>'; 
        }
        ?>
        </tr>
        </thead>
        <?php
    }

    public function footer_admin_edit_area()
    {
        if( $this->frontend_login_user ){
            $g_sheet_url = $this->Sheet->sheet_url;
            ?>
            <div class="stwt-admin-edit-area">
                <b>Admin Area:</b>
                <a href="<?php echo esc_url( admin_url( "post.php?post={$this->Sheet->post_id}&action=edit" ) ) ?>" target="_blank"><i class="stwt_icon-pencil"></i> Edit Sheet Table</a> | 
                <a href="<?php echo esc_url( $g_sheet_url ) ?>" target="_blank"><i class="stwt_icon-doc-text-inv-1"></i> See your Sheet from Google</a>
                
            </div>
            <?php 
        }
    }

    public function wp_enqueue_scripts()
    {

        //jquery
        wp_enqueue_script('jquery');
        /**
         * Datatable js file Load Here
         */
        $dataTables_name = $this->plugin_prefix . '-dataTables';
        wp_register_script( $dataTables_name, $this->base_url . 'assets/js/dataTables.js', false, $this->dev_version );
        wp_enqueue_script( $dataTables_name );

        
        $backend_js_name = $this->plugin_prefix . '-script';
        wp_register_script( $backend_js_name, $this->base_url . 'assets/js/script.js', false, $this->dev_version );
        wp_enqueue_script( $backend_js_name );

        $ajax_url = admin_url( 'admin-ajax.php' );
        $STWT_DATA = array( 
           'ajaxurl'        => $ajax_url,
           'ajax_url'       => $ajax_url,
           'site_url'       => site_url(),
           'plugin_url'     => plugins_url(),
           'content_url'    => content_url(),
           'include_url'    => includes_url(),
           'datatable_options' => $this->datatable_options,
           
           );
        $STWT_DATA = apply_filters( 'stwt_localize_data', $STWT_DATA );
        wp_localize_script( $backend_js_name, 'STWT_DATA', $STWT_DATA );

        //Font-Icon now only for Admin Area actually
        if( $this->frontend_login_user ){
            wp_register_style( $this->plugin_prefix . '-icon-font', $this->base_url . 'assets/fontello/css/stwt-icon.css', false, $this->dev_version );
            wp_enqueue_style( $this->plugin_prefix . '-icon-font' );
        }

        /**
         * Datatable css file Load Here
         */
        wp_register_style( $this->plugin_prefix . '-dataTables', $this->base_url . 'assets/css/dataTables.dataTables.css', false, $this->dev_version );
        wp_enqueue_style( $this->plugin_prefix . '-dataTables' );


        //For all type css editing, which is not related with dashboard Design
        //And also this is not common which need to whole wp
        wp_register_style( $this->plugin_prefix . '-frontend', $this->base_url . 'assets/css/frontend.css', false, $this->dev_version );
        wp_enqueue_style( $this->plugin_prefix . '-frontend' );

        
    }
    
}