<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  

$errors = [];
$saved_data = get_option( $this->setting_key, [] );
include $this->topbar_file;

?>





<div class="wrap stwt_wrap stwt-content">

    <h1 class="wp-heading "></h1>
    <div class="fieldwrap">
        

        
        <form class="" action="" method="POST" id="stwt-main-configuration-form">
        <input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( plugin_basename( STWT_BASE_FILE ) ) ); ?>">


            <?php
            $pages = [
                'getting-start' => [
                    'icon'  => 'stwt_icon-home',
                    'file'  => '' //Actually if file empty, then it will search from steps folder
                ],
                
            ];
            $pages = apply_filters( 'stwt_admin_pages_arr', $pages, $this );
            foreach( $pages as $page_key => $page ){
                $icon = ! empty( $page['icon'] ) && is_string( $page['icon'] ) ? $page['icon'] : 'stwt_icon-link-ext';
                $file_loc = ! empty( $page['file'] ) && is_file( $page['file'] )  ? $page['file'] : __DIR__ . '/steps/' . $page_key . '.php' ;
                
                if( is_file( $file_loc ) ){
                ?>
                <div class="stwt-section-panel <?php echo esc_attr( $page_key ); ?>-settings" id="stwt-<?php echo esc_attr( $page_key ); ?>-settings" data-icon="<?php echo esc_attr( $icon ); ?>">
                    <?php include $file_loc; ?>
                </div>
                <?php
                }else{
                    printf(
                        /* translators: %s: file location uri */
                        esc_html__( '%s file is not founded!', 'sheet-to-wp-table-for-google-sheet' ),
                        esc_html( $file_loc )
                    );
                }

            }
            ?>

        
            
            
        
            <?php 

            /**
             * @Hook Action: stwt_form_panel
             * To add new panel in Forms
             * @since 1.8.6
             */
            do_action( 'stwt_form_panel', $saved_data );
            ?>
            
            
            

            <div id="stwt-form-submit-button" class="stwt-section-panel no-background stwt-full-form-submit-wrapper">

            </div>

            

                    
        </form>

    </div>
</div> 