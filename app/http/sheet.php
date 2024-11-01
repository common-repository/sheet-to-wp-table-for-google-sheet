<?php
namespace STWT_Sheet_To_WP_Table\App\Http;

use STWT_Sheet_To_WP_Table\App\Service\Post\Post_Settings;

/**
 * Whole Sheet manage and Create Access Tocket
 * update, edit Sheet related all things
 * will manage from here
 * 
 * ###  IMPORTANT ###### 
 * We will not use Standaloe to this class,
 * Because, We have to create multiple table on sampe page - sometime.
 * So we will not use Standalone Feature.
 * 
 * Standalone create a instance for a class, don't generate new instance,
 * 
 * 
 * 
 * @author Saiful Islam <codersaiful@gmail.com>
 */
class Sheet
{
    public $atts = [];
    public $refresh;

    public $sheet_id;
    public $gid;
    public $sheet_info =  [];

    public $post_id;



    public $sheet_url;
    public $errors = [];

    public $transient_key;
    public $tr_status = false;

    public $csv_file_url;

    public function __construct( $atts )
    {
        $this->atts = $atts;
        if( empty($this->atts) || ! is_array( $this->atts ) ){
            $this->errors[] = 'Empty atts';
        }

        if( ! empty( $this->atts['id'] ) && is_numeric( $this->atts['id'] )){
            /**
             * Post ID wise, we will generate other atts actually
             * such: refresh or any other
             * Also we will generate this class property
             */
            $this->generate_atts_and_property();
        }


        $this->refresh = $this->atts['refresh'] ?? DAY_IN_SECONDS;
        if( empty( $this->refresh ) || ! is_numeric( $this->refresh )){
            $this->refresh = DAY_IN_SECONDS;
        }

        $this->sheet_url = $this->atts['sheet_url'] ?? '';

        if( empty( $this->sheet_url ) ){
            $this->errors['sheet_url'] = 'Sheet URL not founded';
        }


        $this->sheet_info = $this->extractSheetInfo();

        if( empty( $this->sheet_info ) ){
            $this->errors['sheet_url'] = 'Sheet URL not founded';
        }

        $this->sheet_id = $this->sheet_info['sheet_id'] ?? '';
        $this->gid = $this->sheet_info['gid'] ?? '';

        if( empty( $this->sheet_id ) ){
            $this->errors['sheet_id'] = 'Sheet ID not founded';
        }

        /**
         * To modify Sheet Class
         * Sheet Object,
         * We can use this action hook
         * @Hook 'stwt_sheet_obj'
         */
        do_action( 'stwt_sheet_obj' );
    }

    public function getCSV()
    {
        if( ! empty($this->errors) ) return;

        $this->transient_key = apply_filters( 'stwt_transient_key', $this->sheet_id . '_' . $this->gid, $this->atts );

        $csv = get_transient( $this->transient_key );

        if( ! empty( $csv ) ){
            return $csv;
        }

        $response_csv = $this->response();
        if( ! empty($this->errors) ) return;

        set_transient( sanitize_text_field( $this->transient_key ), $response_csv, $this->refresh );
        $this->tr_status = true;
        
        return $response_csv;
    }


    /**
     * Specially for Generate Atts from ID
     * Specially for Generate Class property from ID
     *
     * @return void
     */
    public function generate_atts_and_property()
    {
        $post_id = $this->atts['id'] ?? $this->post_id;
        if( empty( $post_id ) ) return;
        $this->post_id = $post_id;
        $post_settings = Post_Settings::init();
        $post_type = $post_settings->post_type;
        $post = get_post( $this->post_id );
        
        if($post->post_type !== $post_type){
            $this->errors['post_type'] = 'Post Type is not matchng.';
        }
        if($post->post_status !== 'publish'){
            $this->errors['post_status'] = 'Post is not publish.';
        }
        $this->atts['sheet_url'] = get_post_meta($this->post_id, $post_settings->sheet_url_key, true);
        $this->atts['refresh'] = get_post_meta($this->post_id, $post_settings->refresh_key, true);
    }

    public function response()
    {

        $root_url      = 'https://docs.google.com/spreadsheets/d/%1$s/export?format=csv&id=%1$s';
		
        $url = sprintf( $root_url, $this->sheet_id );


        if( ! empty( $this->gid )){
            $url .= '&gid=' . $this->gid;
        }
        
        $response = wp_remote_get( $url );

        if ( is_wp_error( $response ) ) {
            $this->errors['private_sheet'] = __( 'You are offline.', 'sheetstowptable' );
			return '';
		}

		$headers = $response['headers'];

		if ( ! isset( $headers['X-Frame-Options'] ) || 'DENY' === $headers['X-Frame-Options'] ) {
            $this->errors['private_sheet_xframe'] = __( 'Sheet is not public or shared', 'sheetstowptable' );
            return '';
		}
        $response_msg = $response['response']['message'] ?? '';
        if( $response_msg == 'Bad Request' || $response_msg !== 'OK' ){
            $this->errors['bad_request'] = __( 'Bad Request', 'sheetstowptable' );
            return '';
        }
		$respose_body = wp_remote_retrieve_body( $response );

        return $respose_body;

    }

    public function get_errors()
    {
        return is_array( $this->errors ) ? $this->errors : [];
    }
    public function render_errors()
    {
        $render_errors_list = apply_filters( 'stwt_render_errors', $this->get_errors(), $this );
        if( empty( $render_errors_list ) ) return;
        ?>
        <div class="stwt-error-handle">
            <ul class="stwt-error-list">
        <?php
            foreach( $render_errors_list as $key => $error ){
                if( ! is_string( $error ) ) continue;
            ?>
            <li class="each-error each-eror-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $error ); ?></li>
            <?php
            }
        ?>
            </ul>
        </div> <!-- ./stwt-error-handle -->
        <?php
    }

    /**
     * Extract sheet information based on the provided URL, sheet ID, and gid.
     *
     * @return array ['sheet_id' => 'sheet_id', 'gid' => 'gid']
     */
    public function extractSheetInfo() {
        $url = $this->sheet_url;
        if( empty( $url ) && ! empty( $this->sheet_id ) ){
            return array('sheet_id' => $this->sheet_id, 'gid' => $this->gid);
        }

        return stwt_get_sheet_info( $url );
    }
}