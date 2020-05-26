/**
 * Geocoder map center by Yandex Maps.
 *
 * @param ob
 * @returns {boolean}
 */
function mihdan_elementor_yandex_maps_find_address( ob ) {
	'use strict';

	var $       = jQuery,
		$form   = $( ob ),
		$output = $form.next(),
		address = $form.find( 'input[type="search"]' ).val(),
		ymaps   = window.ymaps;

	if ( ymaps && address !== '' ) {
		ymaps.geocode(
			address,
			{
				results: 1
			}
		).then(
			function ( res ) {
				var firstGeoObject = res.geoObjects.get( 0 );

				if ( firstGeoObject ) {
					var coords = firstGeoObject.geometry.getCoordinates();

					$output.html( 'Latitude: ' + coords[ 0 ] + '<br>Longitude: ' + coords[ 1 ] + '<br />Address: ' + firstGeoObject.getAddressLine() );

					$form.parents( '.elementor-control-map_notice' ).nextAll( '.elementor-control-map_lat' ).find( 'input' ).val( coords[ 0 ] ).trigger( 'input' );
					$form.parents( '.elementor-control-map_notice' ).nextAll( '.elementor-control-map_lng' ).find( 'input' ).val( coords[ 1 ] ).trigger( 'input' );
				} else {
					$output.html( 'Address not found.' );
				}
			},
			function ( err ) {
				console.log( err );
			}
		);
	} else {
		$output.html( 'Please enter a search address.' );
	}

	return false;
}

/**
 * Geocoder map point by Yandex Maps.
 *
 * @param ob
 * @param map_id
 */
function mihdan_elementor_yandex_maps_find_pin_address( ob, map_id ) {

	var $       = jQuery,
		ns      = 'mihdan_elementor_yandex_maps_ns_' + map_id,
		address = $( ob ).parent().find('input').attr('value');

	if ( address !== '' ) {
		ymaps.geocode( address, {
			results: 1
		} ).then(function ( res ) {

			var firstGeoObject = res.geoObjects.get(0),
				// Координаты геообъекта.
				coords = firstGeoObject.geometry.getCoordinates(),
				// Область видимости геообъекта.
				bounds = firstGeoObject.properties.get('boundedBy');

			var output = $(ob).parent().find('.eb-output-result');

			$(output).html("Latitude: " + coords[0] + "<br>Longitude: " + coords[1] + "<br>Address: " + firstGeoObject.getAddressLine() );

			$(ob).parents('.elementor-control-pin_notice').nextAll('.elementor-control-point_lat').find("input").val( coords[0] ).trigger("input");
			$(ob).parents('.elementor-control-pin_notice').nextAll('.elementor-control-point_lng').find("input").val( coords[1] ).trigger("input");
		} );
	} else {
		alert( 'Не указан адрес для поиска' );
	}

}

// eol.
