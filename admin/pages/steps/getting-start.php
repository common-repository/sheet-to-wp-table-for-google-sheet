<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly  

$section_title = esc_html__('Getting Start', 'sheet-to-wp-table-for-google-sheet');

?>
<table class="stwt-table universal-setting">
    <thead>
        <tr>
            <th class="stwt-inside">
                <div class="stwt-table-header-inside">
                    <h3><?php echo esc_html($section_title); ?></h3>
                </div>

            </th>
            <th class="stwt-emty-element">
            </th>
        </tr>
    </thead>

    <tbody>


        <?php
        /**
         * Action @hook for getting-start page
         */
        do_action('stwt_admin_section_top', $saved_data, 'getting-start'); ?>
        <tr>
            <td>
                <div class="stwt-form-control">
                    <div class="form-label col-lg-12">
                        <div class="getting-start-part-wrapper">
                            <div class="getting-header">

                            </div>
                            <div class="getting-body">


                                <h3>Checkout <a href="https://codeastrology.com/sheet-to-table-live-sync-from-google-sheet/" target="_blank"><?php echo esc_html__('Setup guide - step by step', 'sheet-to-wp-table-for-google-sheet'); ?></a></h3>
                                <p><strong>Two Easy Ways to Display Google Sheets on WordPress:</strong></p>
                                <ol>
                                    <li><strong>Shortcode Magic:</strong> Use the <code>[STWT_Sheet_Table]</code> shortcode with your Google Sheet URL and customize synchronization intervals with the refresh attribute. It’s as simple as <code>[STWT_Sheet_Table sheet_url='google_sheet_url' refresh='120']</code>.</li>
                                    <li><strong>Dashboard Delight:</strong> Head to [Dashboard -&gt; Sheet to Table -&gt; Add New], insert your Google Sheet URL in the <b>Sheet URL</b> field, and explore various settings with a live table preview. Once satisfied, publish your post and copy the shortcode like <code>[STWT_Sheet_Table id='1234' name='Table Post title']</code> is generated.</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
            <td class="stwt-emty-element"></td>
        </tr>
        <?php
        /**
         * Action @hook for getting-start page
         */
        do_action('stwt_admin_section_bottom', $saved_data, 'getting-start'); ?>


    </tbody>


</table>