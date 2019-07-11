<?php
/**
 * Виджет карты
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Yandex_Maps extends Widget_Base {

	/**
	 * Retrieve heading widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'yandex-maps';
	}

	/**
	 * Retrieve heading widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Yandex Maps', 'mihdan-elementor-yandex-maps' );
	}

	/**
	 * Retrieve heading widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-google-maps';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'mihdan' ];
	}

	public function get_script_depends() {
		return [ 'mihdan-elementor-yandex-maps-api', 'mihdan-elementor-yandex-maps' ];
	}


	/**
	 * Register yandex maps widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		/**
		 * Настройки карты
		 */
		$this->start_controls_section(
			'section_map',
			[
				'label' => __( 'Map', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'map_notice',
			[
				'label'       => __( 'Find Latitude & Longitude', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => '<form onsubmit="mihdan_elementor_yandex_maps_find_address( this );" action="javascript:void(0);"><input type="text" id="eb-map-find-address" class="eb-map-find-address" style="margin-top:10px; margin-bottom:10px;"><input type="submit" value="Search" class="elementor-button elementor-button-default" onclick="mihdan_elementor_yandex_maps_find_address( this )"></form><div id="eb-output-result" class="eb-output-result" style="margin-top:10px; line-height: 1.3; font-size: 12px;"></div>',
				'label_block' => true,
			]
		);

		$this->add_control(
			'map_lat',
			[
				'label'       => __( 'Latitude', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '55.7522200',
				'default'     => '55.7522200',
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'map_lng',
			[
				'label'       => __( 'Longitude', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '37.6155600',
				'default'     => '37.6155600',
				'dynamic'     => [
					'active' => true,
				],
				'separator'   => true,
			]
		);

		$this->add_control(
			'zoom',
			[
				'label'   => __( 'Zoom Level', 'mihdan-elementor-yandex-maps' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range'   => [
					'px' => [
						'min' => 1,
						'max' => 19,
					],
				],
			]
		);

		$this->add_control(
			'height',
			[
				'label'   => __( 'Height', 'mihdan-elementor-yandex-maps' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min' => 100,
						'max' => 1440,
					],
				],
				'default' => [
					'size' => 300,
				],
			]
		);

		$this->add_control(
			'map_type',
			[
				'label'   => __( 'Map Type', 'mihdan-elementor-yandex-maps' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'map'       => __( 'Road Map', 'mihdan-elementor-yandex-maps' ),
					'satellite' => __( 'Satellite', 'mihdan-elementor-yandex-maps' ),
					'hybrid'    => __( 'Hybrid', 'mihdan-elementor-yandex-maps' ),
				],
				'default' => 'map',
			]
		);

		$this->add_control(
			'view',
			[
				'label'   => __( 'View', 'mihdan-elementor-yandex-maps' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		/**
		 * Контроллы карты
		 */
		$this->start_controls_section(
			'map_controls',
			[
				'label' => __( 'Map Controls', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'ruler_control',
			[
				'label'       => __( 'Ruler Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Линейка и масштабный отрезок', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'search_control',
			[
				'label'       => __( 'Search Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Поиск на карте', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'traffic_control',
			[
				'label'       => __( 'Traffic Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Панель пробок', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'type_selector',
			[
				'label'       => __( 'Type Selector', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'Панель переключения типа карт', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'zoom_control',
			[
				'label'       => __( 'Zoom Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'Ползунок масштаба', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'geolocation_control',
			[
				'label'       => __( 'Geolocation Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Элемент управления геолокацией', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'route_editor',
			[
				'label'       => __( 'Route Editor', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Редактор маршрутов', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'fullscreen_control',
			[
				'label'       => __( 'Fullscreen Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Элемент управления «полноэкранным режимом»', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'route_button_control',
			[
				'label'       => __( 'Route Button Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Панель для построения маршрутов', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'route_panel_control',
			[
				'label'       => __( 'Route Panel Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Панель маршрутизации', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->end_controls_section();

		/**
		 * Поведение карты
		 */
		$this->start_controls_section(
			'map_behavior',
			[
				'label' => __( 'Map Behavior', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'disable_scroll_zoom',
			[
				'label'       => __( 'Disable Scroll Zoom', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить прокрутку карты колесом мыши', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'disable_dbl_click_zoom',
			[
				'label'       => __( 'Disable Dbl Click Zoom', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить масштабирование карты двойным щелчком кнопки мыши', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'disable_drag',
			[
				'label'       => __( 'Disable Drag', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить перетаскивание карты с помощью мыши либо одиночного касания', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'disable_left_mouse_button_magnifier',
			[
				'label'       => __( 'Disable Left Mouse Button Magnifier', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить масштабирование карты при выделении области левой кнопкой мыши', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'disable_right_mouse_button_magnifier',
			[
				'label'       => __( 'Disable Right Mouse Button Magnifier', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить масштабирование карты при выделении области правой кнопкой мыши', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'disable_multi_touch',
			[
				'label'       => __( 'Disable Multi Touch', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить масштабирование карты мультисенсорным касанием', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'disable_route_editor',
			[
				'label'       => __( 'Disable Route Editor', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить редактор маршрутов', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'disable_ruler',
			[
				'label'       => __( 'Disable Ruler', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить линейку', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->end_controls_section();

		/**
		 * Object Manager
		 */
		$this->start_controls_section(
			'map_object_manager',
			[
				'label' => __( 'Object Manager', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'enable_object_manager',
			[
				'label'       => __( 'Enable Object Manager', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Включить кластеризацию', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->end_controls_section();

		/**
		 * Pins Option
		 */
		$this->start_controls_section(
			'map_marker_pin',
			[
				'label' => __( 'Marker Pins', 'mihdan-elementor-yandex-maps' ),
			]
		);

		$this->add_control(
			'infowindow_max_width',
			[
				'label'       => __( 'InfoWindow Max Width', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '300',
				'default'     => '300',
			]
		);

		$this->add_control(
			'tabs',
			[
				'label'       => __( 'Pin Item', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => [
					[
						'pin_title'   => __( 'Pin #1', 'mihdan-elementor-yandex-maps' ),
						'pin_notice'  => __( 'Find Latitude & Longitude', 'mihdan-elementor-yandex-maps' ),
						'pin_lat'     => __( '55.7522200', 'mihdan-elementor-yandex-maps' ),
						'pin_lng'     => __( '37.6155600', 'mihdan-elementor-yandex-maps' ),
						'pin_content' => __( 'I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'mihdan-elementor-yandex-maps' ),
					],
				],
				'fields'      => [
					[
						'name'        => 'pin_notice',
						'label'       => __( 'Find Latitude & Longitude', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::RAW_HTML,
						'raw'         => '<form onsubmit="mihdan_elementor_yandex_maps_find_pin_address( this );" action="javascript:void(0);"><input type="text" id="eb-map-find-address" class="eb-map-find-address" style="margin-top:10px; margin-bottom:10px;"><input type="submit" value="' . __( 'Search', 'mihdan-elementor-yandex-maps' ) . '" class="elementor-button elementor-button-default" onclick="mihdan_elementor_yandex_maps_find_pin_address( this )"></form><div id="eb-output-result" class="eb-output-result" style="margin-top:10px; line-height: 1.3; font-size: 12px;"></div>',
						'label_block' => true,
					],
					[
						'name'        => 'point_lat',
						'label'       => __( 'Latitude', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '55.7522200',
						'placeholder' => '55.7522200',
					],
					[
						'name'        => 'point_lng',
						'label'       => __( 'Longitude', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '37.6155600',
						'placeholder' => '37.6155600',
					],
					[
						'name'    => 'icon_color',
						'label'   => __( 'Icon Color', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::SELECT,
						'options' => [
							'blue'       => __( 'Blue', 'mihdan-elementor-yandex-maps' ),
							'red'        => __( 'Red', 'mihdan-elementor-yandex-maps' ),
							'darkOrange' => __( 'Dark Orange', 'mihdan-elementor-yandex-maps' ),
							'night'      => __( 'Night', 'mihdan-elementor-yandex-maps' ),
							'darkBlue'   => __( 'Dark Blue', 'mihdan-elementor-yandex-maps' ),
							'pink'       => __( 'Pink', 'mihdan-elementor-yandex-maps' ),
							'gray'       => __( 'Gray', 'mihdan-elementor-yandex-maps' ),
							'brown'      => __( 'Brown', 'mihdan-elementor-yandex-maps' ),
							'darkGreen'  => __( 'Dark Green', 'mihdan-elementor-yandex-maps' ),
							'violet'     => __( 'Violet', 'mihdan-elementor-yandex-maps' ),
							'black'      => __( 'Black', 'mihdan-elementor-yandex-maps' ),
							'yellow'     => __( 'Yellow', 'mihdan-elementor-yandex-maps' ),
							'green'      => __( 'Green', 'mihdan-elementor-yandex-maps' ),
							'orange'     => __( 'Orange', 'mihdan-elementor-yandex-maps' ),
							'lightBlue'  => __( 'Light Blue', 'mihdan-elementor-yandex-maps' ),
							'olive'      => __( 'Olive', 'mihdan-elementor-yandex-maps' ),
						],
						'default' => 'blue',
					],
					[
						// @link https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage-docpage/
						'name'    => 'icon_type',
						'label'   => __( 'Icon Type', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::SELECT,
						'options' => [
							''          => __( 'Default Icon', 'mihdan-elementor-yandex-maps' ),
							'Stretchy'  => __( 'Stretchy Icon', 'mihdan-elementor-yandex-maps' ),
							'Dot'       => __( 'Dot Icon', 'mihdan-elementor-yandex-maps' ),
							'Circle'    => __( 'Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'CircleDot' => __( 'Circle Dot Icon', 'mihdan-elementor-yandex-maps' ),
						],
						'default' => 'Circle',
					],
					[
						'name'        => 'icon_caption',
						'label'       => __( 'Icon Caption', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '',
						'label_block' => true,
					],
					[
						'name'        => 'icon_content',
						'label'       => __( 'Icon Content', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '',
						'label_block' => true,
					],
					[
						'name'        => 'hint_content',
						'label'       => __( 'Hint Content', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '',
						'label_block' => true,
					],
					[
						'name'        => 'balloon_content_header',
						'label'       => __( 'Balloon Content Header', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => __( 'Balloon Content Header Default', 'mihdan-elementor-yandex-maps' ),
						'label_block' => true,
					],
					[
						'name'    => 'balloon_content_body',
						'label'   => __( 'Balloon Content Body', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::WYSIWYG,
						'default' => '',
					],
					[
						'name'    => 'balloon_content_footer',
						'label'   => __( 'Balloon Content Footer', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::TEXTAREA,
						'default' => '',
					],
					[
						'name'    => 'balloon_is_opened',
						'label'   => __( 'Balloon Is Opened', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::SWITCHER,
						'default' => 'no',
					],
				],
				'title_field' => '{{{ pin_title }}}',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render yandex maps widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
	}

	/**
	 * Render yandex maps widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( 0 === absint( $settings['zoom']['size'] ) ) {
			$settings['zoom']['size'] = 10;
		}

		$geo_json = array(
			'type'     => 'FeatureCollection',
			'features' => array(),
		);

		foreach ( $settings['tabs'] as $index => $item ) :
			$geo_json['features'][] = array(
				'type'       => 'Feature',
				'id'         => 'id_' . $index,
				'geometry'   => array(
					'type'        => 'Point',
					'coordinates' => array(
						$item['point_lat'],
						$item['point_lng'],
					),
				),
				'properties' => array(
					'iconCaption'          => $item['icon_caption'],
					'iconContent'          => $item['icon_content'],
					'hintContent'          => $item['hint_content'],
					'balloonContentHeader' => $item['balloon_content_header'],
					'balloonContentFooter' => $item['balloon_content_footer'],
					'balloonContentBody'   => htmlspecialchars( $item['balloon_content_body'], ENT_QUOTES & ~ENT_COMPAT ),
				),
				'options'    => array(
					'preset'          => sprintf( 'islands#%s%sIcon', $item['icon_color'], $item['icon_type'] ),
					'balloonIsOpened' => $item['balloon_is_opened'],
				),
			);
		endforeach;
		?>

		<div id="eb-map-<?php echo esc_attr( $this->get_id() ); ?>"
		    class="eb-map"
		    data-eb-map-lat="<?php echo $settings['map_lat']; ?>"
		    data-eb-map-lng="<?php echo $settings['map_lng']; ?>"
		    data-eb-map-zoom="<?php echo $settings['zoom']['size']; ?>"
		    data-eb-map-type="<?php echo $settings['map_type']; ?>"
		    data-eb-ruler-control="<?php echo $settings['ruler_control']; ?>"
		    data-eb-search-control="<?php echo $settings['search_control']; ?>"
		    data-eb-traffic-control="<?php echo $settings['traffic_control']; ?>"
		    data-eb-type-selector="<?php echo $settings['type_selector']; ?>"
		    data-eb-zoom-control="<?php echo $settings['zoom_control']; ?>"
		    data-eb-geolocation-control="<?php echo $settings['geolocation_control']; ?>"
		    data-eb-route-editor="<?php echo $settings['route_editor']; ?>"
		    data-eb-fullscreen-control="<?php echo $settings['fullscreen_control']; ?>"
		    data-eb-route-button-control="<?php echo $settings['route_button_control']; ?>"
		    data-eb-route-panel-control="<?php echo $settings['route_panel_control']; ?>"
		    data-eb-disable-scroll-zoom="<?php echo $settings['disable_scroll_zoom']; ?>"
		    data-eb-disable-dbl-click-zoom="<?php echo $settings['disable_dbl_click_zoom']; ?>"
		    data-eb-disable-drag="<?php echo $settings['disable_drag']; ?>"
		    data-eb-disable-left-mouse-button-magnifier="<?php echo $settings['disable_left_mouse_button_magnifier']; ?>"
		    data-eb-disable-right-mouse-button-magnifier="<?php echo $settings['disable_right_mouse_button_magnifier']; ?>"
		    data-eb-disable-multi-touch="<?php echo $settings['disable_multi_touch']; ?>"
		    data-eb-disable-route-editor="<?php echo $settings['disable_route_editor']; ?>"
		    data-eb-disable-ruler="<?php echo $settings['disable_ruler']; ?>"
		    data-eb-enable-object-manager="<?php echo $settings['enable_object_manager']; ?>"
		    data-eb-infowindow-max-width="<?php echo $settings['infowindow_max_width']; ?>"
		    data-eb-locations='<?php echo json_encode( $geo_json ); ?>'
		    style="height: <?php echo $settings['height']['size']; ?><?php echo $settings['height']['unit']; ?>;"></div>
	<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Yandex_Maps() );

// eof;
