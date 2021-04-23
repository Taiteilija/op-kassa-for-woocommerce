jQuery(document).ready($ => {
    // This is a temporary solution to always sync products and stocks to same direction
    $('#kis_product_sync_direction').on('change', () => {
        $('#kis_stock_sync_direction').val($('#kis_product_sync_direction').val());
    });

    // Show warning of Kassa disconnect when attempting to change the Kassa target environment
    const orig_env = $('#kis_test_environment_enabled').prop('checked');
    $('#kis_test_environment_enabled').on('change', (event) => {
        const current_env = $(event.target).prop('checked');

        if ( orig_env !== current_env ) {
            $('#kis_environment_settings-description .setting-warning').addClass('show');
        } else {
            $('#kis_environment_settings-description .setting-warning').removeClass('show');
        }
    });

    // Show warning of Kassa disconnect when attempting to change the Woo Auth Method
    const orig_auth_method = $('#kis_woo_auth_params_enabled').prop('checked');
    $('#kis_woo_auth_params_enabled').on('change', (event) => {
        const current_auth_method = $(event.target).prop('checked');

        if ( orig_auth_method !== current_auth_method ) {
            $('#kis_auth_settings-description .setting-warning').addClass('show');
        } else {
            $('#kis_auth_settings-description .setting-warning').removeClass('show');
        }
    });

    if ( $('input[name="kis_has_custom_environment"]').val() ) {
        $('#kis_test_environment_enabled').prop('disabled', true);
    }
});