( function( $, ymaps ) {
    var EBGoogleMapHandler = function( $scope, $ ) {
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

        function initMap() {

	        var map = new ymaps.Map( mapid.attr('id'), {
		        center: [ parseFloat( map_lat ), parseFloat( map_lng ) ],
		        zoom: zoom,
                type: 'yandex#' + maptype
	        });

            //var myLatLng = { lat: parseFloat(map_lat), lng: parseFloat(map_lng) };

//            var map = new google.maps.Map(mapid[0], {
//                center: myLatLng,
//                zoom: zoom,
//                disableDefaultUI: defaultui,
//                zoomControl: zoomcontrol,
//                zoomControlOptions: {
//                    position: zoomcontrolposition
//                },
//                mapTypeId: maptype,
//                mapTypeControl: maptypecontrol,
//                mapTypeControlOptions: {
//                    style: maptypecontrolstyle,
//                    position: maptypecontrolposition
//                },
//                streetViewControl: streetview,
//                streetViewControlOptions: {
//                    position: streetviewposition
//                },
//                styles: styles,
//                gestureHandling: gesture_handling,
//            });

            var markersLocations = $( mapid ).data('eb-locations');
//
            $.each( markersLocations, function( index, Element, content ) {
                var icon_color = '';
                if ( Element.pin_icon !== '' ) {
	                icon_color = Element.pin_icon;
                }

	            var placemark = new ymaps.Placemark( [ Element.lat, Element.lng ], {
		            //iconCaption: 'dfwefwe',
		            hintContent: 'Нажмите, чтобы увидеть описание',
		            balloonContentHeader: Element.title,
		            balloonContentBody: Element.content,
		            balloonContentFooter: ''
	            }, {
		            iconColor: icon_color,
		            balloonContentMaxWidth: 50
	            } );
	            map.geoObjects.add( placemark );



            });
        }

	    ymaps.ready( initMap );

    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/yandex-maps.default', EBGoogleMapHandler);
//	    elementorFrontend.hooks.addAction('frontend/element_ready/widget', function (  ) {
//		    console.log( 'widget.init');
//	    });
    });

} )( window.jQuery, window.ymaps );