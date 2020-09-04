jQuery(document).ready($ => {
    // This is a temporary solution to always sync products and stocks to same direction
    $('#kis_product_sync_direction').on('change', () => {
        $('#kis_stock_sync_direction').val($('#kis_product_sync_direction').val());
    });

    const orig_env = $('#kis_environment').val();
    // Force setting page save on environment change
    $('#kis_environment').on('change', (event) => {
        const current_env = $(event.target).val();

        if ( orig_env !== current_env ) {
            $('#kis_environment_settings-description .env-change-warning').addClass('show');
        } else {
            $('#kis_environment_settings-description .env-change-warning').removeClass('show');
        }
    });

    if ( $('input[name="kis_has_custom_environment"]').val() ) {
        $('#kis_environment').prop('disabled', true);
    }
});