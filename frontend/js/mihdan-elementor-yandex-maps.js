( function( $, w, d ) {
	'use strict';
	var map = {
		unset: function ( arr ) {
			arr.filter(
				function( i ) {
					return i !== 'b';
				}
			);
		},
		init: function ( $scope, $ ) {

			var $map   = $scope.find( '.mihdan-elementor-yandex-maps' ),
				map_id = $map.attr( 'id' ).substr( 28 ),
				config = w[ 'mihdan_elementor_yandex_map_' + map_id ];

			var	mapType                              = config.type,
				zoom                                 = config.zoom,
				api_key                              = w.mihdan_elementor_yandex_maps_config.api_key,
				map_lat                              = config.lat,
				map_lng                              = config.lng,
				language                             = config.language || 'ru',
				region                               = config.region || 'RU',
				ruler_control                        = config.rulerControl,
				search_control                       = config.searchControl,
				traffic_control                      = config.trafficControl,
				type_selector                        = config.typeSelector,
				zoom_control                         = config.zoomControl,
				geolocation_control                  = config.geolocationControl,
				route_editor                         = config.routeEditor,
				fullscreen_control                   = config.fullscreenControl,
				route_button_control                 = config.routeButtonControl,
				route_panel_control                  = config.routePanelControl,
				disable_scroll_zoom                  = config.disableScrollZoom,
				disable_dbl_click_zoom               = config.disableDblClickZoom,
				disable_drag                         = config.disableDrag,
				disable_left_mouse_button_magnifier  = config.disableLeftMouseButtonMagnifier,
				disable_right_mouse_button_magnifier = config.disableRightMouseButtonMagnifier,
				disable_multi_touch                  = config.disableMultiTouch,
				disable_route_editor                 = config.disableRouteEditor,
				disable_ruler                        = config.disableRuler,
				enable_object_manager                = config.enableObjectManager,
				infowindow_max_width                 = parseInt( config.infoWindowMaxWidth, 10 ),
				controls                             = [];

			// Неймспейс для карты.
			var ns  = 'mihdan_elementor_yandex_maps_ns_' + map_id;
			var map = 'mihdan_elementor_yandex_maps_map_' + map_id;

			// Загружаем API.
			var f = d.getElementsByTagName( 'script' )[0],
				j = d.createElement( 'script' );

			j.async = true;
			j.src   = 'https://api-maps.yandex.ru/2.1/?lang=' + language + '_' + region + '&source=admin&apikey=' + api_key + '&onload=ymaps_ready_' + map_id + '&ns=' + ns;
			f.parentNode.insertBefore( j, f );

			w['ymaps_ready_' + map_id] = function () {

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

				w[ map ] = new w[ ns ].Map(
					$map.attr( 'id' ),
					{
						center: [ parseFloat( map_lat ), parseFloat( map_lng ) ],
						zoom: zoom,
						type: 'yandex#' + mapType,
						controls: controls
					},
					{
						searchControlProvider: 'yandex#search'
					}
				);

				// Отключить прокрутку колесом мыши
				if ( 'yes' === disable_scroll_zoom ) {
					w[ map ].behaviors.disable( 'scrollZoom' );
				}

				// Отключить масштабирование карты двойным щелчком кнопки мыши
				if ( 'yes' === disable_dbl_click_zoom ) {
					w[ map ].behaviors.disable( 'dblClickZoom' );
				}

				// Отключить перетаскивание карты с помощью мыши либо одиночного касания
				if ( 'yes' === disable_drag ) {
					w[ map ].behaviors.disable( 'drag' );
				}

				// Отключить масштабирование карты при выделении области левой кнопкой мыши
				if ( 'yes' === disable_left_mouse_button_magnifier ) {
					w[ map ].behaviors.disable( 'leftMouseButtonMagnifier' );
				}

				// Отключить масштабирование карты при выделении области правой кнопкой мыши
				if ( 'yes' === disable_right_mouse_button_magnifier ) {
					w[ map ].behaviors.disable( 'rightMouseButtonMagnifier' );
				}

				// Отключить масштабирование карты мультисенсорным касанием
				if ( 'yes' === disable_multi_touch ) {
					w[ map ].behaviors.disable( 'multiTouch' );
				}

				// Отключить редактор маршрутов
				if ( 'yes' === disable_route_editor ) {
					w[ map ].behaviors.disable( 'routeEditor' );
				}

				// Отключить линейку
				if ( 'yes' === disable_ruler ) {
					w[ map ].behaviors.disable( 'ruler' );
				}

				var markersLocations = config.locations;

				// Если включена кластеризация.
				if ( 'yes' === enable_object_manager ) {
					// Создание менеджера объектов.
					var objectManager = new w[ ns ].ObjectManager(
						{
							clusterize: true,
							preset: markersLocations.clusterPreset
						}
					);

					// После создания менеджера ему следует передать JSON-описание объектов.
					objectManager.add( markersLocations );

					// Отобразим объекты на карте.
					w[ map ].geoObjects.add( objectManager );

				} else {
					/**
					 * @link https://tech.yandex.ru/maps/jsbox/2.1/icon_customImage
					 * @link https://tech.yandex.ru/maps/jsapi/doc/2.1/dg/concepts/geoobjects-docpage/#geoobjects__icon-style
					 */
					$.each(
						markersLocations.features,
						function ( index, Element, content ) {
							var options = {};

							// Custom icon.
							if ( Element.options.iconImage ) {
								options.iconLayout    = 'default#imageWithContent';
								options.iconImageHref = Element.options.iconImage;
								options.iconImageSize = [
									parseInt( Element.options.iconImageWidth, 10 ),
									parseInt( Element.options.iconImageHeight, 10 )
								];
							} else {
								options.preset          = Element.options.preset;
								options.balloonMaxWidth = parseInt( infowindow_max_width, 10 );
							}

							var placemark = new w[ ns ].Placemark(
								[
									parseFloat( Element.geometry.coordinates[0] ),
									parseFloat( Element.geometry.coordinates[1] )
								],
								{
									hintContent: Element.properties.hintContent,
									balloonContentHeader: Element.properties.balloonContentHeader,
									balloonContentBody: Element.properties.balloonContentBody,
									balloonContentFooter: Element.properties.balloonContentFooter,
									iconContent: Element.properties.iconContent,
									iconCaption: Element.properties.iconCaption
								},
								options
							);

							w[ map ].geoObjects.add( placemark );

							// Показать балун при загрузке метки.
							if ( 'yes' === Element.options.balloonIsOpened ) {
								placemark.balloon.open();
							}
						}
					);
				}
			};
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

} )( window.jQuery, window, document );

// eof;
