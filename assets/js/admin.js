;(function($) {
    
    // console.log('Admin js');

    jQuery(document).ready(function ($) {
        $('#refresh-data').on('click', function () {
            fetch('/wp-admin/admin-ajax.php?action=nhrst_get_table_data')
                .then(() => location.reload());
        });
    });    

})(jQuery);
