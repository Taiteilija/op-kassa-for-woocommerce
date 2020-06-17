jQuery(document).ready($ => {
    // This is a temporary solution to always sync products and stocks to same direction
    $('#kis_product_sync_direction').on('change', () => {
        $('#kis_stock_sync_direction').val($('#kis_product_sync_direction').val());
    })
});