<?php
namespace STWT_Sheet_To_WP_Table\App\Service\Post;

use STWT_Sheet_To_WP_Table\App\Service\Standalone;
use STWT_Sheet_To_WP_Table\App\Service\Post\Post_Settings;

class Meta_Box extends Post_Settings
{
    use Standalone;
    public $post_id;
    public $sheet_url;
    private $refresh;

    public $regen_transient = true;


    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_custom_metabox']);
        add_action('save_post', [$this,'save_post_data']);
    }

    public function add_custom_metabox() {
        add_meta_box(
            'stwt_p_shortcode',        // Metabox ID
            __( 'Setting', 'text-domain' ),     // Metabox Title
            [$this, 'main_content_box'],   // Callback function to display the metabox content
            $this->post_type,         // Custom post type where the metabox should appear
            'normal',                   // Context (normal, advanced, side)
            'high'                      // Priority (high, core, default, low)
        );
        add_meta_box(
            'stwt_p_preview',        // Metabox ID
            __( 'Preview', 'text-domain' ),     // Metabox Title
            [$this, 'table_preview_box'],   // Callback function to display the metabox content
            $this->post_type,         // Custom post type where the metabox should appear
            'normal',                   // Context (normal, advanced, side)
            'high'                      // Priority (high, core, default, low)
        );
    }

    public function main_content_box($post)
    {
        
        $post_id = $post->ID;
        $this->post_id = $post_id;
        $this->sheet_url = get_post_meta( $post->ID, $this->sheet_url_key, true );
        $this->refresh = get_post_meta( $post->ID, $this->refresh_key, true );

        //refresh will be greter than 12 second
        if( empty( $this->refresh ) || ! is_numeric( $this->refresh ) || $this->refresh < 12 ){
            $this->refresh = $this->refresh_min_time;
        }

        $shortcode = "[STWT_Sheet_Table id='{$post_id}']";
        $title = get_the_title($post_id);
        if( ! empty( $title ) ){
            $shortcode = "[STWT_Sheet_Table id='{$post_id}' name='{$title}']";
        }
        ?>
        
        <input type="hidden" name="stwt_nonce" value="<?php echo esc_attr( wp_create_nonce('stwt_nonce') ); ?>">
        <div class="wrap stwt_wrap stwt-content stwt-content-post-meta">

        
        
        <table class="stwt-table universal-setting">

            <tbody>
                

            <tr>
                <td>
                    <div class="stwt-form-control">
                        <div class="form-label col-lg-4">
                            <label for="edit_table_title"> <?php echo esc_html__( 'Your Shortcode', 'sheet-to-wp-table-for-google-sheet' ); ?></label>
                        </div>
                        <div class="form-field col-lg-8">
                            <?php if(empty($this->sheet_url)){ ?>
                            <b>Sample Shortcode</b><br>
                            <code>[STWT_Sheet_Table sheet_url='google_sheet_url' refresh='86400']</code>
                            
                            <?php }else{ ?>
                            <input type="text" class="ua_input" name="" id="" value="<?php echo esc_attr($shortcode); ?>" readonly>
                            <p>Copy Shortcode and Paste to anywhere.</p>    
                            <?php } ?>
                            
                        </div>
                    </div>
                </td>
                <td>
                    <div class="stwt-form-info">
                        <p>Copy Shortcode and Paste to anywhere.</p>
                    </div> 
                </td>
            </tr>
            <tr>
                <td>
                    <div class="stwt-form-control">
                        <div class="form-label col-lg-4">
                            <label for="edit_table_title"> <?php echo esc_html__( 'Sheet URL', 'sheet-to-wp-table-for-google-sheet' ); ?></label>
                        </div>
                        <div class="form-field col-lg-8">
                        
                            <input name="<?php echo esc_attr( $this->sheet_url_key ); ?>" id="edit_table_title" class="ua_input_number" value="<?php echo esc_attr( $this->sheet_url ); ?>"  type="text" step=any>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="stwt-form-info">
                        <p>Input your Google Sheet link Here. <?php stwt_doc_link( 'https://codeastrology.com/sheet-to-table-live-sync-from-google-sheet/', __( 'Tutorial', 'sheet-to-wp-table-for-google-sheet' ) ); ?></p>
                        <p>Set General access by link(minimum Viewer)</p>
                        
                    </div> 
                </td>
            </tr>
            <tr>
                <td>
                    <div class="stwt-form-control">
                        <div class="form-label col-lg-4">
                            <label for="edit_table_title"> <?php echo esc_html__( 'Refresh time(seconds)', 'sheet-to-wp-table-for-google-sheet' ); ?></label>
                        </div>
                        <div class="form-field col-lg-8">
                        
                            <input name="<?php echo esc_attr( $this->refresh_key ); ?>" id="stwt_refresh_key" class="ua_input_number" value="<?php echo esc_attr( $this->refresh ); ?>"  type="number" step='1' min='10'>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="stwt-form-info">
                        <p>Cache time as seconds. By default, we use 1 days as second. But you can set any time.</p>
                    </div> 
                </td>
            </tr>

        </tbody>

            
        </table>

        </div>
        <?php 
    }



    public function save_post_data( $post_id )
    {
        $this->post_id = $post_id;
        
        $nonce = sanitize_text_field( wp_unslash( $_POST['stwt_nonce'] ?? '' ) );
        if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'stwt_nonce' )) return;
        
        $this->sheet_url = sanitize_url( $_POST[$this->sheet_url_key] ?? '' );
        $this->refresh = sanitize_text_field( $_POST[$this->refresh_key] ?? '' );


        $sheet_info = stwt_get_sheet_info( sanitize_url($this->sheet_url) );
        // dd($sheet_info);
        // die();
        $existing_sheet_url = get_post_meta($this->post_id, $this->sheet_url_key, true);

        /**
         * Currently it will regen 33% time 
         * by redefault, but in future,
         * I will add a button with 
         * Regen Sheet [On Off Button]
         * and if pressed on that check, then transient will regen
         * 
         * Then I will removed rand(1,3) == 3 from bellow
         * if statement
         * 
         * @since 1.0.0.3
         * @author Saiful Islam <codersaiful@gmail.com>
         */
        // if($existing_sheet_url !== $this->sheet_url || rand(1,3) == 3){
        if( isset( $sheet_info['sheet_id']) ){
            $gid = $sheet_info['gid'] ?? '';
            $transient_key = $sheet_info['sheet_id'] . '_' . $gid;
            set_transient( sanitize_text_field( $transient_key ), null, 0 );
        }
            

        // }
        
        if( empty( $sheet_info ) ){
            $this->sheet_url = '';
        }
        update_post_meta( $post_id, $this->sheet_url_key, sanitize_url( $this->sheet_url ) );
        update_post_meta( $post_id, $this->refresh_key, sanitize_text_field( $this->refresh ) );
        
    }

    public function table_preview_box( $post )
    {
        add_filter( 'stwt_render_errors', [$this, 'hide_render_error'] );
        $post_id = $post->ID;
        echo wp_kses_post( do_shortcode( "[STWT_Sheet_Table id='{$post_id}']" ) );
    }

    public function hide_render_error()
    {
        return [];
    }

}