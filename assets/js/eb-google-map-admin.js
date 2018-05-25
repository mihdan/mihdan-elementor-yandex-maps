function ebMapFindAddress(ob) {
    var address = $(ob).parent().find('input').attr('value');
    if( address !='' ){

	    ymaps.geocode( address, {
		    /**
		     * Опции запроса
		     * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/geocode.xml
		     */
		    // Сортировка результатов от центра окна карты.
		    // boundedBy: myMap.getBounds(),
		    // strictBounds: true,
		    // Вместе с опцией boundedBy будет искать строго внутри области, указанной в boundedBy.
		    // Если нужен только один результат, экономим трафик пользователей.
		    results: 1
	    } ).then(function ( res ) {

		    var firstGeoObject = res.geoObjects.get(0),
			    // Координаты геообъекта.
			    coords = firstGeoObject.geometry.getCoordinates(),
			    // Область видимости геообъекта.
			    bounds = firstGeoObject.properties.get('boundedBy');

		    var output = $(ob).parent().find('.eb-output-result');

		    $(output).html("Latitude: " + coords[0] + "<br>Longitude: " + coords[1] + "<br>(Copy and Paste your Latitude & Longitude value below)<br />Полный адрес: " + firstGeoObject.getAddressLine() );

		    $(ob).parents('.elementor-control-map_notice').nextAll('.elementor-control-map_lat').find("input").val( coords[0] ).trigger("input");
		    $(ob).parents('.elementor-control-map_notice').nextAll('.elementor-control-map_lng').find("input").val( coords[1] ).trigger("input");
        } );
    } else {
        alert( 'Не указан адрес для поиска' );
    }
}
function ebMapFindPinAddress(ob) {
    var address = $(ob).parent().find('input').attr('value');
    if(address!=''){
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': address }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var output = $(ob).parent().find('.eb-output-result');
                var latiude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();
                $(output).html("Latitude: " + latiude + "<br>Longitude: " + longitude + "<br>(Copy and Paste your Latitude & Longitude value below)");
                $(ob).parents('.elementor-control-pin_notice').nextAll('.elementor-control-pin_lat').find("input").val(latiude).trigger("input");
                $(ob).parents('.elementor-control-pin_notice').nextAll('.elementor-control-pin_lng').find("input").val(longitude).trigger("input");
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
    }
}

(function($) {
    $('.repeater-fields').each(function() {
        $(this).click(function() {
            $('.eb-output-result').empty();
        });
    });
});