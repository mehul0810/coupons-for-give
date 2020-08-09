jQuery( document ).ready( function( $ ) {
    let selectedGateway = $( '.give-gateway:checked' ).val();

    if ( 'coupon' === selectedGateway ) {
        $( '.give-total-wrap' ).hide();
        $( '.give-donation-levels-wrap' ).hide();
        $( '#give-final-total-wrap' ).hide();
    } else {
        $( '.give-total-wrap' ).show();
        $( '.give-donation-levels-wrap' ).show();
        $( '#give-final-total-wrap' ).show();
    }

    $( document ).on( 'give_gateway_loaded', function( e ) {
        selectedGateway = $( '.give-gateway:checked' ).val();

        if ( 'coupon' === selectedGateway ) {
            $( '.give-total-wrap' ).hide();
            $( '.give-donation-levels-wrap' ).hide();
            $( '#give-final-total-wrap' ).hide();
        } else {
            $( '.give-total-wrap' ).show();
            $( '.give-donation-levels-wrap' ).show();
            $( '#give-final-total-wrap' ).show();
        }
    } );
} );