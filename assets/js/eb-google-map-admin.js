function ebMapFindAddress(ob) {
    var address = $(ob).parent().find('input').attr('value');
    if(address!=''){
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': address }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var output = $(ob).parent().find('.eb-output-result');
                var latiude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();
                $(output).html("Latitude: " + latiude + "<br>Longitude: " + longitude + "<br>(Copy and Paste your Latitude & Longitude value below)");
                $(ob).parents('.elementor-control-map_notice').nextAll('.elementor-control-map_lat').find("input").val(latiude).trigger("input");
                $(ob).parents('.elementor-control-map_notice').nextAll('.elementor-control-map_lng').find("input").val(longitude).trigger("input");
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
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