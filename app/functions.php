<?php


if( ! function_exists('stwt_doc_link') ){
    /**
     * This function will add helper doc
     * @since 3.3.6.1
     * @author Fazle Bari <fazlebarisn@gmail.com>
     */
    function stwt_doc_link( $url, $title = '', $icon = 'stwt_icon-link' ){
        if( empty( $title ) ){
            $title = __( 'Helper doc', 'sheet-to-wp-table-for-google-sheet' );
        }
        ?>
            <a href="<?php echo esc_url( $url ); ?>" target="_blank" class="stwt-doc-link">
                <i class="<?php echo esc_attr( $icon ); ?>"></i>
                <?php echo esc_html( $title ); ?>
            </a>
        <?php
    }
}




/**
 * Error will handle by jQuery
 * we will add here a p tag just to insert error here
 *
 * @param string $error_key
 * @return string 
 */
function stwt_error_msg($error_key = '')
{
    if( empty( $error_key ) ) return;

?>
<div class="stwt-error-message stwt-error-message-<?php echo esc_attr( $error_key ); ?>"></div>
<?php 

}


/**
 * Finding Sheet info from full URL of Google sheet.
 *
 * @param string|null $sheet_url
 * @return array ['sheet_id => 'sheet_id', gid=> 'gid']
 */
function stwt_get_sheet_info( $sheet_url ) {
    $url = $sheet_url;
    // Define the regex pattern to match the sheet ID and gid
    $pattern = '/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)\/.*(gid=([0-9]+)|edit\?usp=sharing)/';

    // Perform the regex match
    preg_match($pattern, $url, $matches);

    // Check if the matches were found
    if (count($matches) >= 3) {
        $sheet_id = $matches[1];
        $gid = $matches[2] ?? 0;
        if(! empty( $gid )  && ! is_numeric( $gid )){
            $gid = 0;
        }

        return array('sheet_id' => $sheet_id, 'gid' => $gid);
    }

    // Return null if no matches were found
    return [];
}