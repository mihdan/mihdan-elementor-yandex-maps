( function( $, ymaps ) {
    var mihdan_elementor_yandex_maps = function( $scope, $ ) {
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
	        disable_scroll_zoom = $(mapid).data("eb-disable-scroll-zoom"),
	        disable_dbl_click_zoom = $(mapid).data("eb-disable-dbl-click-zoom"),
	        disable_drag = $(mapid).data("eb-disable-drag"),
	        disable_left_mouse_button_magnifier = $(mapid).data("eb-disable-left-mouse-button-magnifier"),
	        disable_right_mouse_button_magnifier = $(mapid).data("eb-disable-right-mouse-button-magnifier"),
	        disable_multi_touch = $(mapid).data("eb-disable-disable-multi-touch"),
	        disable_route_editor = $(mapid).data("eb-disable-route-editor"),
	        disable_ruler = $(mapid).data("eb-disable-ruler"),
            infowindow_max_width = parseInt( $(mapid).data("eb-infowindow-max-width") ),
            active_info,
            infowindow,
            map,
	        // @link https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/control.storage-docpage/
            controls = [];

        function init_map() {

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

	        // Отключить прокрутку колесом мыши
	        if ( 'yes' === disable_scroll_zoom ) {
		        map.behaviors.disable('scrollZoom');
	        }

	        // Отключить масштабирование карты двойным щелчком кнопки мыши
	        if ( 'yes' === disable_dbl_click_zoom ) {
		        map.behaviors.disable('dblClickZoom');
	        }

			// Отключить перетаскивание карты с помощью мыши либо одиночного касания
	        if ( 'yes' === disable_drag ) {
		        map.behaviors.disable('drag');
	        }

	        // Отключить масштабирование карты при выделении области левой кнопкой мыши
	        if ( 'yes' === disable_left_mouse_button_magnifier ) {
		        map.behaviors.disable('leftMouseButtonMagnifier');
	        }

	        // Отключить масштабирование карты при выделении области правой кнопкой мыши
	        if ( 'yes' === disable_right_mouse_button_magnifier ) {
		        map.behaviors.disable('rightMouseButtonMagnifier');
	        }

	        // Отключить масштабирование карты мультисенсорным касанием
	        if ( 'yes' === disable_multi_touch ) {
		        map.behaviors.disable('multiTouch');
	        }

	        // Отключить редактор маршрутов
	        if ( 'yes' === disable_route_editor ) {
		        map.behaviors.disable('routeEditor');
	        }

	        // Отключить линейку
	        if ( 'yes' === disable_ruler ) {
		        map.behaviors.disable('ruler');
	        }

            var markersLocations = $( mapid ).data('eb-locations');

            $.each( markersLocations, function( index, Element, content ) {
                var icon_color = '';
                if ( Element.pin_icon !== '' ) {
	                icon_color = Element.pin_icon;
                }

	            var placemark = new ymaps.Placemark( [ parseFloat( Element.lat ), parseFloat( Element.lng ) ], {
		            //iconCaption: 'dfwefwe',
		            hintContent: 'Нажмите, чтобы увидеть описание',
		            balloonContentHeader: Element.title,
		            balloonContentBody: Element.content,
		            balloonContentFooter: ''
	            }, {
		            iconColor: icon_color,
		            //preset: 'islands#circleIcon',
		            // Запретить сворачивать балун в панель на маленьком экране
		            //balloonPanelMaxMapArea: 0,
		            balloonMaxWidth: parseInt( infowindow_max_width )
	            } );
	            map.geoObjects.add( placemark );
            } );
        }

	    ymaps.ready( init_map );

    };

    // Make sure you run this code under Elementor..
    $( window ).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/yandex-maps.default', mihdan_elementor_yandex_maps );
    } );

} )( window.jQuery, window.ymaps );

// eof;
