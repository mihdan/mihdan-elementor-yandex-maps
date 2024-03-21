/**
 * Frontend for maps.
 *
 * @author Mikhail Kobzarev <mikhail@kobzarev.com>
 * @package mihdan-elementor-yandex-maps
 */

( function( $, w, d ) {
	'use strict';
	var map = {
		init: function ( $scope, $ ) {

			let
				$map                                 = $scope.find( '.mihdan-elementor-yandex-maps' ),
				map_id                               = $map.data( 'map_id' ),
				config                               = $map.data( 'map_config' ),
				device                               = w.elementorFrontend.getCurrentDeviceMode() || 'desktop',
				timeout                              = w.elementorFrontend.isEditMode() ? 100 : 5000,
				mapType                              = config.type,
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
				enable_save_map                      = config.enableSaveMap,
				disable_scroll_zoom                  = config.disableScrollZoom,
				disable_dbl_click_zoom               = config.disableDblClickZoom,
				disable_drag                         = config.disableDrag,
				disable_left_mouse_button_magnifier  = config.disableLeftMouseButtonMagnifier,
				disable_right_mouse_button_magnifier = config.disableRightMouseButtonMagnifier,
				disable_multi_touch                  = config.disableMultiTouch,
				disable_route_editor                 = config.disableRouteEditor,
				disable_ruler                        = config.disableRuler,
				disable_lazy_load                    = config.disableLazyLoad,
				enable_object_manager                = config.enableObjectManager,
				enable_balloon_panel                 = config.enableBalloonPanel === 'yes',
				infowindow_max_width                 = parseInt( config.infoWindowMaxWidth, 10 ),
				controls                             = [],
				ns                                   = 'mihdan_elementor_yandex_maps_ns_' + map_id, // Неймспейс для карты.
				map                                  = 'mihdan_elementor_yandex_maps_map_' + map_id,
				loaded                               = false;

			// Отключает ленивую загрузку карты,
			// когда карта в шапке сайта или на фоне секции.
			if ( disable_lazy_load === 'yes' ) {
				timeout = 0;
			}

			// Ленивая загрузка API карт.
			const lazyLoad = function () {

				if ( loaded ) {
					return;
				}

				const
					script_id = 'mihdan_elementor_yandex_maps_script_' + map_id,
					script    = document.getElementById( script_id );

				// Скрипт уже подгружен для этой карты.
				if ( script ) {
					script.parentNode.removeChild( script );
				}

				const
					f = d.getElementsByTagName( 'script' )[0],
					j = d.createElement( 'script' );

				j.async = true;
				j.id    = script_id;
				j.src   = 'https://api-maps.yandex.ru/2.1/?lang=' + language + '_' + region + '&source=admin&apikey=' + api_key + '&onload=ymaps_ready_' + map_id + '&ns=' + ns;
				f.parentNode.insertBefore( j, f );

				loaded = true;
			};

			const event_options = {
				once: true,
				passive: true,
				capture: true
			};

			// Подключим API под действием пользователя.
			document.addEventListener( 'scroll', lazyLoad, event_options );
			document.addEventListener( 'mouseover', lazyLoad, event_options );
			document.addEventListener( 'mousemove', lazyLoad, event_options );
			document.addEventListener( 'touchstart', lazyLoad, event_options );
			document.addEventListener( 'touchmove', lazyLoad, event_options );

			// Фолбек загрузки карты, если юзер не сделал никаких событий.
			setTimeout( lazyLoad, timeout );

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
						zoom: parseInt( zoom[ device ] || 10 ),
						type: 'yandex#' + mapType,
						controls: controls
					},
					{
						searchControlProvider: 'yandex#search'
					}
				);

				let lastOpenedBalloon = false;

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
							preset: markersLocations.clusterPreset,
							geoObjectBalloonMaxWidth: infowindow_max_width,
						}
					);

					// Отключить схлопывание балуна в панель
					// на маленьком экране.
					if ( enable_balloon_panel === false ) {
						objectManager.objects.options.set( 'balloonPanelMaxMapArea', 0 );
					}

					// После создания менеджера ему следует передать JSON-описание объектов.
					objectManager.add( markersLocations );

					// Отобразим объекты на карте.
					w[ map ].geoObjects.add( objectManager );

				} else {
					/**
					 * Метки без кластеров.
					 *
					 * @link https://tech.yandex.ru/maps/jsbox/2.1/icon_customImage
					 * @link https://tech.yandex.ru/maps/jsapi/doc/2.1/dg/concepts/geoobjects-docpage/#geoobjects__icon-style
					 */
					$.each(
						markersLocations.features,
						function ( index, Element ) {
							var options = {};

							// Custom icon.
							if ( Element.options.iconImage ) {
								const icon_size = parseInt( Element.options.iconImageWidth, 10 );

								options.iconLayout      = 'default#imageWithContent';
								options.iconImageHref   = Element.options.iconImage;
								options.iconImageSize   = [
									icon_size,
									icon_size
								];
								options.iconImageOffset = [
									-( icon_size / 2 ),
									-( icon_size / 2 )
								];
							} else {
								options.preset = Element.options.preset;
							}

							options.balloonMaxWidth = infowindow_max_width;

							// Отключить схлопывание балуна в панель
							// на маленьком экране.
							if ( enable_balloon_panel === false ) {
								options.balloonPanelMaxMapArea = 0;
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

				/**
				 * Получает значение параметра name из адресной строки браузера.
				 *
				 * @param {string} name Имя параметра для поиска.
				 * @param location
				 * @returns {string|boolean}
				 */
				const getParam = function ( name, location ) {
					location  = location || window.location.hash;
					const res = location.match( new RegExp( '[#&]' + name + '=([^&]*)', 'i' ) );
					return ( res && res[1] ? res[1] : false );
				};

				/**
				 * Передача параметров, описывающих состояние карты, в адресную строку браузера.
				 */
				const setLocationHash = function () {
					var params = [
						'type=' + w[ map ].getType().split( '#' )[1],
						'center=' + w[ map ].getCenter(),
						'zoom=' + w[ map ].getZoom()
					];

					if ( w[ map ].balloon.isOpen() ) {
						params.push( 'open=' + lastOpenedBalloon );
					}

					window.location.hash = params.join( '&' );
				};

				/**
				 * Установка состояния карты в соответствии с переданными в адресной строке браузера параметрами.
				 */
				const setMapStateByHash = function () {
					const hashType   = getParam( 'type' ),
						  hashCenter = getParam( 'center' ),
						  hashZoom   = getParam( 'zoom' ),
						  open       = getParam( 'open' );

					if (hashType) {
						w[ map ].setType( 'yandex#' + hashType );
					}
					if (hashCenter) {
						w[ map ].setCenter( hashCenter.split( ',' ) );
					}
					if (hashZoom) {
						w[ map ].setZoom( hashZoom );
					}
					if (open) {
						/*markersLocations.each(function (geoObj) {
							var id = geoObj.properties.get('myId');
							if (id == open) {
								geoObj.balloon.open();

							}
						});*/
					}
				};

				// Включить сохранение карты.
				if ( 'yes' === enable_save_map ) {
					w[ map ].events.add(
						[ 'boundschange', 'typechange', 'balloonclose' ],
						setLocationHash
					);

					setMapStateByHash();
				}
			};
		}
	};

	// Make sure you run this code under Elementor..
	$( w ).on(
		'elementor/frontend/init',
		function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/yandex-maps.default', map.init );
		}
	);

} )( window.jQuery, window, document );
