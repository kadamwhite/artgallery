/**
 * ArtGallery
 *
 * Copyright (c) 2013 K.Adam White
 * Licensed under the GPLv2+ license.
 */
 
( function( $, undefined ) {
    'use strict';
    var $availabilitySelector = $('#acf-artwork_availability');
    var $availabilityProxy = $('#acf-artwork_availability_proxy');

    // Persist changes from the taxonomy selector to the proxy radiobox selector
    $availabilitySelector.on('change', 'input', function(evt) {
        var $input = $( evt.target );
        var name = $input
            .parent()
            .text()
            .split(' ')
            .join('')
            .toLowerCase();

        // Trigger a selection on the corresponding proxy, to fire off conditional logic
        $availabilityProxy.find('input[value="' + name + '"]').trigger('click');
    });

    // Initialize the state by updating the proxy to match the selected item
    $availabilitySelector.find(':checked').trigger('change');

} )( jQuery );