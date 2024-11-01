
jQuery(function($) {
    'use strict';
    $(document).ready(function() {
        //Has come from enqueue of frontend page loader
        
        var ajax_url = STWT_DATA_ADMIN.ajax_url;
        var ajaxurl = STWT_DATA_ADMIN.ajax_url;
        
        $(document.body).on('change', '#stwt_refresh_key', function(){
            var val = $(this).val();
            val = parseInt(val);
            if(val < 15){
                $(this).val(86400);
            }
        });

    });
});