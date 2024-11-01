<?php
namespace STWT_Sheet_To_WP_Table\App\Core;

use STWT_Sheet_To_WP_Table\App\Core\Base;

class Admin_Base extends Base
{

    public $main_slug = 'stwt-settings';

    public $admin_dir = STWT_BASE_DIR . 'admin/';
    public $base_file = STWT_BASE_FILE;

    public $setting_key = 'stwt_settings';
    public $settings;

    public $submit_errors_key = '_submit_errors';

    public $is_premium = false;

    public function __construct()
    {
       $this->is_premium = class_exists('PSSGP_Init');
       $this->settings = get_option( $this->setting_key, [] );
       $this->submit_errors_key = $this->plugin_prefix . $this->submit_errors_key;
    }


}