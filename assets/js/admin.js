;(function($) {
    
    // console.log('Admin js');

    jQuery(document).ready(function ($) {
        $('#nhrst-table-refresh-button').on('click', function () {
            fetch(`${nhrstSmartSyncTableCommonObj.ajax_url}?action=nhrst_refresh_api_data&nonce=${nhrstSmartSyncTableCommonObj.nonce}`)
            .then(() => location.reload());
        });
    });    

})(jQuery);
