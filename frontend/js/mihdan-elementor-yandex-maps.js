( function( $, ymaps ) {
	'use strict';
	let map = {
		unset: function ( arr ) {
			arr.filter(
				function( i ) {
					return i !== 'b';
				}
			);
		},
		init: function ( $scope, $ ) {

			let mapId                                = $scope.find( '.eb-map' ),
				maptype                              = $( mapId ).data( 'eb-map-type' ),
				zoom                                 = $( mapId ).data( 'eb-map-zoom' ),
				map_lat                              = $( mapId ).data( 'eb-map-lat' ),
				map_lng                              = $( mapId ).data( 'eb-map-lng' ),
				ruler_control                        = $( mapId ).data( 'eb-ruler-control' ),
				search_control                       = $( mapId ).data( 'eb-search-control' ),
				traffic_control                      = $( mapId ).data( 'eb-traffic-control' ),
				type_selector                        = $( mapId ).data( 'eb-type-selector' ),
				zoom_control                         = $( mapId ).data( 'eb-zoom-control' ),
				geolocation_control                  = $( mapId ).data( 'eb-geolocation-control' ),
				route_editor                         = $( mapId ).data( 'eb-route-editor' ),
				fullscreen_control                   = $( mapId ).data( 'eb-fullscreen-control' ),
				route_button_control                 = $( mapId ).data( 'eb-route-button-control' ),
				route_panel_control                  = $( mapId ).data( 'eb-route-panel-control' ),

				disable_scroll_zoom                  = $( mapId ).data( 'eb-disable-scroll-zoom' ),
				disable_dbl_click_zoom               = $( mapId ).data( 'eb-disable-dbl-click-zoom' ),
				disable_drag                         = $( mapId ).data( 'eb-disable-drag' ),
				disable_left_mouse_button_magnifier  = $( mapId ).data( 'eb-disable-left-mouse-button-magnifier' ),
				disable_right_mouse_button_magnifier = $( mapId ).data( 'eb-disable-right-mouse-button-magnifier' ),
				disable_multi_touch                  = $( mapId ).data( 'eb-disable-disable-multi-touch' ),
				disable_route_editor                 = $( mapId ).data( 'eb-disable-route-editor' ),
				disable_ruler                        = $( mapId ).data( 'eb-disable-ruler' ),

				enable_object_manager                = $( mapId ).data( 'eb-enable-object-manager' ),
				infowindow_max_width                 = parseInt( $( mapId ).data( 'eb-infowindow-max-width' ) ),
				controls                             = [],
				behaviors                            = [
					'scrollZoom',
					'dblClickZoom',
					'drag',
					'leftMouseButtonMagnifier',
					'rightMouseButtonMagnifier',
					'multiTouch',
					'routeEditor',
					'ruler'
				];

			ymaps.ready(
				function () {
					for ( var i in behaviors ) {

					}

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

					var map = new ymaps.Map( mapId.attr('id'), {
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

					var markersLocations = $( mapId ).data('eb-locations');

					// Если включена кластеризация.
					if ( 'yes' === enable_object_manager ) {
						// Создание менеджера объектов.
						var objectManager = new ymaps.ObjectManager( {
							clusterize: true,
							preset: markersLocations.clusterPreset
						} );

						// После создания менеджера ему следует передать JSON-описание объектов.
						objectManager.add( markersLocations );

						// Отобразим объекты на карте.
						map.geoObjects.add( objectManager );

					} else {

						$.each( markersLocations.features, function ( index, Element, content ) {

							var placemark = new ymaps.Placemark( [ parseFloat( Element.geometry.coordinates[0] ), parseFloat( Element.geometry.coordinates[1] ) ], {
								hintContent: Element.properties.hintContent,
								balloonContentHeader: Element.properties.balloonContentHeader,
								balloonContentBody: Element.properties.balloonContentBody,
								balloonContentFooter: Element.properties.balloonContentFooter,
								iconContent: Element.properties.iconContent,
								iconCaption: Element.properties.iconCaption,
							}, {
								preset: Element.options.preset,
								balloonMaxWidth: parseInt( infowindow_max_width )
							} );

							map.geoObjects.add( placemark );

							// Показать балун при загрузке метки.
							if ( 'yes' === Element.options.balloonIsOpened ) {
								placemark.balloon.open();
							}
						} );
					}
				}
			);
		}
	};

	// Make sure you run this code under Elementor..
	$( window ).on(
		'elementor/frontend/init',
		function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/yandex-maps.default', map.init );
			//elementorModules.frontend.handlers.hooks.Base.addAction( 'frontend/element_ready/yandex-maps.default', mihdan_elementor_yandex_maps );
		}
	);

} )( window.jQuery, window.ymaps );

// eof;
