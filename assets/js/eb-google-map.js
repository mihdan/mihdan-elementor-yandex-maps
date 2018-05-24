(function($) {
    var EBGoogleMapHandler = function($scope, $) {
        var mapid = $scope.find('.eb-map'),
            maptype = $(mapid).data("eb-map-type"),
            zoom = $(mapid).data("eb-map-zoom"),
            map_lat = $(mapid).data("eb-map-lat"),
            map_lng = $(mapid).data("eb-map-lng"),
            //scrollwheel = $(mapid).data("eb-map-scrollwheel"),
            gesture_handling = $(mapid).data("eb-map-gesture-handling"),
            defaultui = $(mapid).data("eb-map-defaultui"),
            zoomcontrol = $(mapid).data("eb-map-zoom-control"),
            zoomcontrolposition = $(mapid).data("eb-map-zoom-control-position"),
            maptypecontrol = $(mapid).data("eb-map-type-control"),
            maptypecontrolstyle = $(mapid).data("eb-map-type-control-style"),
            maptypecontrolposition = $(mapid).data("eb-map-type-control-position"),
            streetview = $(mapid).data("eb-map-streetview-control"),
            streetviewposition = $(mapid).data("eb-map-streetview-position"),
            styles = ($(mapid).data("eb-map-style") != '') ? $(mapid).data("eb-map-style") : '',
            infowindow_max_width = $(mapid).data("eb-map-infowindow-width"),
            active_info,
            infowindow,
            map;

        if (maptypecontrolstyle == 'DROPDOWN_MENU') {
            maptypecontrolstyle = google.maps.MapTypeControlStyle.DROPDOWN_MENU;
        } else if (maptypecontrolstyle == 'HORIZONTAL_BAR') {
            maptypecontrolstyle = google.maps.MapTypeControlStyle.HORIZONTAL_BAR;
        } else {
            maptypecontrolstyle = google.maps.MapTypeControlStyle.DEFAULT;
        }

        if (maptypecontrolposition == 'TOP_CENTER') {
            maptypecontrolposition = google.maps.ControlPosition.TOP_CENTER;
        } else if (maptypecontrolposition == 'TOP_RIGHT') {
            maptypecontrolposition = google.maps.ControlPosition.TOP_RIGHT;
        } else if (maptypecontrolposition == 'LEFT_CENTER') {
            maptypecontrolposition = google.maps.ControlPosition.LEFT_CENTER;
        } else if (maptypecontrolposition == 'RIGHT_CENTER') {
            maptypecontrolposition = google.maps.ControlPosition.RIGHT_CENTER;
        } else if (maptypecontrolposition == 'BOTTOM_CENTER') {
            maptypecontrolposition = google.maps.ControlPosition.BOTTOM_CENTER;
        } else if (maptypecontrolposition == 'BOTTOM_RIGHT') {
            maptypecontrolposition = google.maps.ControlPosition.BOTTOM_RIGHT;
        } else if (maptypecontrolposition == 'BOTTOM_LEFT') {
            maptypecontrolposition = google.maps.ControlPosition.BOTTOM_LEFT;
        } else {
            maptypecontrolposition = google.maps.ControlPosition.TOP_LEFT;
        }

        if (zoomcontrolposition == 'TOP_CENTER') {
            zoomcontrolposition = google.maps.ControlPosition.TOP_CENTER;
        } else if (zoomcontrolposition == 'TOP_RIGHT') {
            zoomcontrolposition = google.maps.ControlPosition.TOP_RIGHT;
        } else if (zoomcontrolposition == 'LEFT_CENTER') {
            zoomcontrolposition = google.maps.ControlPosition.LEFT_CENTER;
        } else if (zoomcontrolposition == 'RIGHT_CENTER') {
            zoomcontrolposition = google.maps.ControlPosition.RIGHT_CENTER;
        } else if (zoomcontrolposition == 'BOTTOM_CENTER') {
            zoomcontrolposition = google.maps.ControlPosition.BOTTOM_CENTER;
        } else if (zoomcontrolposition == 'BOTTOM_RIGHT') {
            zoomcontrolposition = google.maps.ControlPosition.BOTTOM_RIGHT;
        } else if (zoomcontrolposition == 'BOTTOM_LEFT') {
            zoomcontrolposition = google.maps.ControlPosition.BOTTOM_LEFT;
        } else if (zoomcontrolposition == 'TOP_LEFT') {
            zoomcontrolposition = google.maps.ControlPosition.TOP_LEFT;
        } else {
            zoomcontrolposition = google.maps.ControlPosition.RIGHT_BOTTOM;
        }

        if (streetviewposition == 'TOP_CENTER') {
            streetviewposition = google.maps.ControlPosition.TOP_CENTER;
        } else if (streetviewposition == 'TOP_RIGHT') {
            streetviewposition = google.maps.ControlPosition.TOP_RIGHT;
        } else if (streetviewposition == 'LEFT_CENTER') {
            streetviewposition = google.maps.ControlPosition.LEFT_CENTER;
        } else if (streetviewposition == 'RIGHT_CENTER') {
            streetviewposition = google.maps.ControlPosition.RIGHT_CENTER;
        } else if (streetviewposition == 'BOTTOM_CENTER') {
            streetviewposition = google.maps.ControlPosition.BOTTOM_CENTER;
        } else if (streetviewposition == 'BOTTOM_RIGHT') {
            streetviewposition = google.maps.ControlPosition.BOTTOM_RIGHT;
        } else if (streetviewposition == 'BOTTOM_LEFT') {
            streetviewposition = google.maps.ControlPosition.BOTTOM_LEFT;
        } else if (streetviewposition == 'TOP_LEFT') {
            streetviewposition = google.maps.ControlPosition.TOP_LEFT;
        } else {
            streetviewposition = google.maps.ControlPosition.RIGHT_BOTTOM;
        }

        function initMap() {
            var myLatLng = { lat: parseFloat(map_lat), lng: parseFloat(map_lng) };

            var map = new google.maps.Map(mapid[0], {
                center: myLatLng,
                zoom: zoom,
                disableDefaultUI: defaultui,
                zoomControl: zoomcontrol,
                zoomControlOptions: {
                    position: zoomcontrolposition
                },
                mapTypeId: maptype,
                mapTypeControl: maptypecontrol,
                mapTypeControlOptions: {
                    style: maptypecontrolstyle,
                    position: maptypecontrolposition
                },
                streetViewControl: streetview,
                streetViewControlOptions: {
                    position: streetviewposition
                },
                styles: styles,
                gestureHandling: gesture_handling,
            });

            var markersLocations = $(mapid).data('eb-locations');

            $.each(markersLocations, function(index, Element, content) {
                var content = '<div class="eb-map-container"><h6>' + Element.title + '</h6>' + Element.content + '</div>';
                var icon = '';
                if (Element.pin_icon !== '') {
                    if (Element.pin_icon == 'red') {
                        icon = EB_WP_URL.plugin_url + 'assets/images/marker_red.png';
                    } else if (Element.pin_icon == 'yellow') {
                        icon = EB_WP_URL.plugin_url + 'assets/images/marker_yellow.png';
                    } else if (Element.pin_icon == 'blue') {
                        icon = EB_WP_URL.plugin_url + 'assets/images/marker_blue.png';
                    } else if (Element.pin_icon == 'black') {
                        icon = EB_WP_URL.plugin_url + 'assets/images/marker_black.png';
                    } else if (Element.pin_icon == 'white') {
                        icon = EB_WP_URL.plugin_url + 'assets/images/marker_white.png';
                    } else if (Element.pin_icon == 'purple') {
                        icon = EB_WP_URL.plugin_url + 'assets/images/marker_purple.png';
                    } else if (Element.pin_icon == 'green') {
                        icon = EB_WP_URL.plugin_url + 'assets/images/marker_green.png';
                    } else if (Element.pin_icon == 'orange') {
                        icon = EB_WP_URL.plugin_url + 'assets/images/marker_orange.png';
                    } else if (Element.pin_icon == 'grey') {
                        icon = EB_WP_URL.plugin_url + 'assets/images/marker_grey.png';
                    } else {
                        icon = '';
                    }
                }

                marker = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(parseFloat(Element.lat), parseFloat(Element.lng)),
                    icon: icon,
                });

                if (Element.title !== '') {
                    addInfoWindow(marker, content)
                } else if (Element.content !== '') {
                    addInfoWindow(marker, content)
                }
                
            });
        }
        
        function addInfoWindow(marker, content) {
            google.maps.event.addListener(marker, 'click', function() {
                if (!infowindow) {
                    infowindow = new google.maps.InfoWindow({
                        maxWidth: infowindow_max_width
                    });
                }
                infowindow.setContent(content);
                infowindow.open(map, marker);
            });
        }

        initMap();

    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/eb-google-map-extended.default', EBGoogleMapHandler);
    });

})(jQuery);