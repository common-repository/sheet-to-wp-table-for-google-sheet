<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  

$stwt_img = STWT_BASE_URL . 'assets/images/brand/social/sheet-to-table.png';



$topbar_sub_title = __( 'Dashboard', 'sheet-to-wp-table-for-google-sheet' );
if( isset( $this->topbar_sub_title ) && ! empty( $this->topbar_sub_title ) ){
    $topbar_sub_title = $this->topbar_sub_title;
}
?>
<div class="stwt-header stwt-clearfix">
    <div class="container-flued">
        <div class="col-lg-7">
            <div class="stwt-logo-wrapper-area">
                <div class="stwt-logo-area">
                    <img src="<?php echo esc_url( $stwt_img ); ?>" class="stwt-brand-logo">
                </div>
                <div class="stwt-main-title">
                    <h2 class="stwt-ntitle">
                        <?php esc_html_e("Sheet to Table", 'sheet-to-wp-table-for-google-sheet');?>
                        
                        <?php
                        if( $this->is_premium ){
                        ?>
                        <span><?php esc_html_e( "Premium", 'sheet-to-wp-table-for-google-sheet' ); ?></span>
                        <?php
                        }
                        ?>
                    </h2>
                </div>
                
                <div class="stwt-main-title stwt-main-title-secondary">
                    <h2 class="stwt-ntitle"><?php echo esc_html( $topbar_sub_title );?></h2>
                </div>

            </div>
        </div>
        <div class="col-lg-5">
            <div class="header-button-wrapper">
                
                <a class="stwt-button reset" 
                    href="https://codeastrology.com/sheet-to-table-live-sync-from-google-sheet/" 
                    target="_blank">
                    <i class="stwt_icon-note"></i><?php echo esc_html__( 'Documentation', 'sheet-to-wp-table-for-google-sheet' ); ?>
                </a>
            </div>
        </div>
    </div>
</div>