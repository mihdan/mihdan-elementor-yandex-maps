( function( $, ymaps ) {
    var EBGoogleMapHandler = function( $scope, $ ) {
        var mapid = $scope.find('.eb-map'),
            maptype = $(mapid).data("eb-map-type"),
            zoom = $(mapid).data("eb-map-zoom"),
            map_lat = $(mapid).data("eb-map-lat"),
            map_lng = $(mapid).data("eb-map-lng"),
	        ruler_control = $(mapid).data("eb-ruler-control"),
	        search_control = $(mapid).data("eb-search-control"),
	        traffic_control = $(mapid).data("eb-traffic-control"),
	        type_selector = $(mapid).data("eb-type-selector"),
	        zoom_control = $(mapid).data("eb-zoom-control"),
	        geolocation_control = $(mapid).data("eb-geolocation-control"),
	        route_editor = $(mapid).data("eb-route-editor"),
	        fullscreen_control = $(mapid).data("eb-fullscreen-control"),
	        route_button_control = $(mapid).data("eb-route-button-control"),
	        route_panel_control = $(mapid).data("eb-route-panel-control"),


            infowindow_max_width = $(mapid).data("eb-map-infowindow-width"),
            active_info,
            infowindow,
            map,
	        // @link https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/control.storage-docpage/
            controls = [];

        function initMap() {

	        if ( 'yes' === ruler_control ) {
		        controls.push( 'rulerControl' );
	        }

	        if ( 'yes' === search_control ) {
		        controls.push( 'searchControl' );
	        }

	        if ( 'yes' === traffic_control ) {
		        controls.push( 'trafficControl' );
	        }

	        if ( 'yes' === type_selector ) {
		        controls.push( 'typeSelector' );
	        }

	        if ( 'yes' === zoom_control ) {
		        controls.push( 'zoomControl' );
	        }

	        if ( 'yes' === geolocation_control ) {
		        controls.push( 'geolocationControl' );
	        }

	        if ( 'yes' === route_editor ) {
		        controls.push( 'routeEditor' );
	        }

	        if ( 'yes' === fullscreen_control ) {
		        controls.push( 'fullscreenControl' );
	        }

	        if ( 'yes' === route_button_control ) {
		        controls.push( 'routeButtonControl' );
	        }

	        if ( 'yes' === route_panel_control ) {
		        controls.push( 'routePanelControl' );
	        }

	        var map = new ymaps.Map( mapid.attr('id'), {
		        center: [ parseFloat( map_lat ), parseFloat( map_lng ) ],
		        zoom: zoom,
                type: 'yandex#' + maptype,
		        controls: controls
	        }, {
		        searchControlProvider: 'yandex#search'
	        } );

            var markersLocations = $( mapid ).data('eb-locations');

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
		            balloonMaxWidth: 300
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