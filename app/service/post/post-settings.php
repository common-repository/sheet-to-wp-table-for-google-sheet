<?php
namespace STWT_Sheet_To_WP_Table\App\Service\Post;

use STWT_Sheet_To_WP_Table\App\Service\Standalone;
use STWT_Sheet_To_WP_Table\App\Core\Admin_Base;


class Post_Settings extends Admin_Base
{
    use Standalone;
    public $post_type = 'stwt_shortcode_post';
    public $shortcode_key = 'stwt_shortcode';
    public $refresh_min_time = DAY_IN_SECONDS;

    public $refresh_key = 'refresh_key';
    public $sheet_url_key = 'stwt_sheet_url';
    
}