<?php
/**
 * Виджет карты
 *
 * @package mihdan-elementor-yandex-maps
 */

namespace MihdanElementorYandexMaps\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Widget
 */
class Widget extends Widget_Base {

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

	/**
	 * Get script depends
	 *
	 * @return array
	 */
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
				'raw'         => '<form onsubmit="mihdan_elementor_yandex_maps_find_address( this );" action="javascript:void(0);"><input type="text" id="eb-map-find-address" class="eb-map-find-address" style="margin-top:10px; margin-bottom:10px;" placeholder="' . __( 'Enter Search Address', 'mihdan-elementor-yandex-maps' ) . '" /><input type="submit" value="Search" class="elementor-button elementor-button-default" onclick="mihdan_elementor_yandex_maps_find_address( this )"></form><div id="eb-output-result" class="eb-output-result" style="margin-top:10px; line-height: 1.3; font-size: 12px;"></div>',
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
			'map_language',
			[
				'label'        => __( 'Map Language', 'mihdan-elementor-yandex-maps' ),
				'description'   => __( 'Задает язык объектов на карте (топонимов, элементов управления).', 'mihdan-elementor-yandex-maps' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'ru' => __( 'Russian', 'mihdan-elementor-yandex-maps' ),
					'uk' => __( 'Ukrainian', 'mihdan-elementor-yandex-maps' ),
					'en' => __( 'English', 'mihdan-elementor-yandex-maps' ),
					'tr' => __( 'Turkish', 'mihdan-elementor-yandex-maps' ),
				],
				'default'     => 'ru',
			]
		);

		$this->add_control(
			'map_region',
			[
				'label'   => __( 'Map Region', 'mihdan-elementor-yandex-maps' ),
				'description'   => __( 'Определяет региональные особенности, например единицу измерения (для обозначения расстояния между объектами или скорости движения по маршруту). Для регионов RU, UA и TR расстояние показывается в километрах, для US — в милях.', 'mihdan-elementor-yandex-maps' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'RU' => __( 'Russia', 'mihdan-elementor-yandex-maps' ),
					'UA' => __( 'Ukraine', 'mihdan-elementor-yandex-maps' ),
					'US' => __( 'USA', 'mihdan-elementor-yandex-maps' ),
					'TR' => __( 'Turkey', 'mihdan-elementor-yandex-maps' ),
				],
				'default' => 'ru',
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

		$this->add_control(
			'cluster_color',
			[
				'label'   => __( 'Cluster Color', 'mihdan-elementor-yandex-maps' ),
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
						'pin_notice'             => __( 'Find Latitude & Longitude', 'mihdan-elementor-yandex-maps' ),
						'point_lat'              => __( '55.7522200', 'mihdan-elementor-yandex-maps' ),
						'point_lng'              => __( '37.6155600', 'mihdan-elementor-yandex-maps' ),
						'icon_color'             => 'blue',
						'icon_type'              => 'Circle',
						'icon_caption'           => '',
						'icon_content'           => '',
						'hint_content'           => '',
						'balloon_content_header' => __( 'Balloon Content Header Default', 'mihdan-elementor-yandex-maps' ),
						'balloon_content_body'   => '',
						'balloon_content_footer' => '',
						'balloon_is_opened'      => 'no',
					],
				],
				'fields'      => [
					[
						'name'        => 'pin_notice',
						'label'       => __( 'Find Latitude & Longitude', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::RAW_HTML,
						'raw'         => '<form onsubmit="mihdan_elementor_yandex_maps_find_pin_address( this );" action="javascript:void(0);"><input type="text" id="eb-map-find-address" class="eb-map-find-address" style="margin-top:10px; margin-bottom:10px;" placeholder="' . __( 'Enter Search Address', 'mihdan-elementor-yandex-maps' ) . '" /><input type="submit" value="' . __( 'Search', 'mihdan-elementor-yandex-maps' ) . '" class="elementor-button elementor-button-default" onclick="mihdan_elementor_yandex_maps_find_pin_address( this )"></form><div id="eb-output-result" class="eb-output-result" style="margin-top:10px; line-height: 1.3; font-size: 12px;"></div>',
						'label_block' => true,
					],
					[
						'name'        => 'point_lat',
						'label'       => __( 'Latitude', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '55.7522200',
						'placeholder' => '55.7522200',
						'dynamic'     => [
							'active' => true,
						],
					],
					[
						'name'        => 'point_lng',
						'label'       => __( 'Longitude', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '37.6155600',
						'placeholder' => '37.6155600',
						'dynamic'     => [
							'active' => true,
						],
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
						'type'    => Controls_Manager::SELECT2,
						'options' => [
							''                          => __( 'Default Icon', 'mihdan-elementor-yandex-maps' ),
							'Stretchy'                  => __( 'Stretchy Icon', 'mihdan-elementor-yandex-maps' ),
							'Dot'                       => __( 'Dot Icon', 'mihdan-elementor-yandex-maps' ),
							'Circle'                    => __( 'Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'CircleDot'                 => __( 'Circle Dot Icon', 'mihdan-elementor-yandex-maps' ),
							'Airport'                   => __( 'Airport Icon', 'mihdan-elementor-yandex-maps' ),
							'Attention'                 => __( 'Attention Icon', 'mihdan-elementor-yandex-maps' ),
							'Auto'                      => __( 'Auto Icon', 'mihdan-elementor-yandex-maps' ),
							'Bar'                       => __( 'Bar Icon', 'mihdan-elementor-yandex-maps' ),
							'Barber'                    => __( 'Barber Icon', 'mihdan-elementor-yandex-maps' ),
							'Beach'                     => __( 'Beach Icon', 'mihdan-elementor-yandex-maps' ),
							'Bicycle'                   => __( 'Bicycle Icon', 'mihdan-elementor-yandex-maps' ),
							'Bicycle2'                  => __( 'Bicycle2 Icon', 'mihdan-elementor-yandex-maps' ),
							'Book'                      => __( 'Book Icon', 'mihdan-elementor-yandex-maps' ),
							'CarWash'                   => __( 'CarWash Icon', 'mihdan-elementor-yandex-maps' ),
							'Christian'                 => __( 'Book Icon', 'mihdan-elementor-yandex-maps' ),
							'Cinema'                    => __( 'Cinema Icon', 'mihdan-elementor-yandex-maps' ),
							'Circus'                    => __( 'Circus Icon', 'mihdan-elementor-yandex-maps' ),
							'Court'                     => __( 'Court Icon', 'mihdan-elementor-yandex-maps' ),
							'Delivery'                  => __( 'Delivery Icon', 'mihdan-elementor-yandex-maps' ),
							'Discount'                  => __( 'Discount Icon', 'mihdan-elementor-yandex-maps' ),
							'Dog'                       => __( 'Dog Icon', 'mihdan-elementor-yandex-maps' ),
							'Education'                 => __( 'Education Icon', 'mihdan-elementor-yandex-maps' ),
							'EntertainmentCenter'       => __( 'EntertainmentCenter Icon', 'mihdan-elementor-yandex-maps' ),
							'Factory'                   => __( 'Factory Icon', 'mihdan-elementor-yandex-maps' ),
							'Family'                    => __( 'Family Icon', 'mihdan-elementor-yandex-maps' ),
							'Fashion'                   => __( 'Fashion Icon', 'mihdan-elementor-yandex-maps' ),
							'Food'                      => __( 'Food Icon', 'mihdan-elementor-yandex-maps' ),
							'FuelStation'               => __( 'FuelStation Icon', 'mihdan-elementor-yandex-maps' ),
							'Garden'                    => __( 'Garden Icon', 'mihdan-elementor-yandex-maps' ),
							'Government'                => __( 'Government Icon', 'mihdan-elementor-yandex-maps' ),
							'Heart'                     => __( 'Heart Icon', 'mihdan-elementor-yandex-maps' ),
							'Home'                      => __( 'Home Icon', 'mihdan-elementor-yandex-maps' ),
							'Hotel'                     => __( 'Hotel Icon', 'mihdan-elementor-yandex-maps' ),
							'Hydro'                     => __( 'Hydro Icon', 'mihdan-elementor-yandex-maps' ),
							'Info'                      => __( 'Info Icon', 'mihdan-elementor-yandex-maps' ),
							'Laundry'                   => __( 'Laundry Icon', 'mihdan-elementor-yandex-maps' ),
							'Leisure'                   => __( 'Leisure Icon', 'mihdan-elementor-yandex-maps' ),
							'MassTransit'               => __( 'MassTransit Icon', 'mihdan-elementor-yandex-maps' ),
							'Medical'                   => __( 'Medical Icon', 'mihdan-elementor-yandex-maps' ),
							'Money'                     => __( 'Money Icon', 'mihdan-elementor-yandex-maps' ),
							'Mountain'                  => __( 'Mountain Icon', 'mihdan-elementor-yandex-maps' ),
							'NightClub'                 => __( 'NightClub Icon', 'mihdan-elementor-yandex-maps' ),
							'Observation'               => __( 'Observation Icon', 'mihdan-elementor-yandex-maps' ),
							'Park'                      => __( 'Park Icon', 'mihdan-elementor-yandex-maps' ),
							'Parking'                   => __( 'Parking Icon', 'mihdan-elementor-yandex-maps' ),
							'Person'                    => __( 'Person Icon', 'mihdan-elementor-yandex-maps' ),
							'Pocket'                    => __( 'Pocket Icon', 'mihdan-elementor-yandex-maps' ),
							'Pool'                      => __( 'Pool Icon', 'mihdan-elementor-yandex-maps' ),
							'Post'                      => __( 'Post Icon', 'mihdan-elementor-yandex-maps' ),
							'Railway'                   => __( 'Railway Icon', 'mihdan-elementor-yandex-maps' ),
							'RapidTransit'              => __( 'RapidTransit Icon', 'mihdan-elementor-yandex-maps' ),
							'RepairShop'                => __( 'RepairShop Icon', 'mihdan-elementor-yandex-maps' ),
							'Run'                       => __( 'Run Icon', 'mihdan-elementor-yandex-maps' ),
							'Science'                   => __( 'Science Icon', 'mihdan-elementor-yandex-maps' ),
							'Shopping'                  => __( 'Shopping Icon', 'mihdan-elementor-yandex-maps' ),
							'Souvenirs'                 => __( 'Souvenirs Icon', 'mihdan-elementor-yandex-maps' ),
							'Sport'                     => __( 'Sport Icon', 'mihdan-elementor-yandex-maps' ),
							'Star'                      => __( 'Star Icon', 'mihdan-elementor-yandex-maps' ),
							'Theater'                   => __( 'Theater Icon', 'mihdan-elementor-yandex-maps' ),
							'Toilet'                    => __( 'Toilet Icon', 'mihdan-elementor-yandex-maps' ),
							'Underpass'                 => __( 'Underpass Icon', 'mihdan-elementor-yandex-maps' ),
							'Vegetation'                => __( 'Vegetation Icon', 'mihdan-elementor-yandex-maps' ),
							'Video'                     => __( 'Video Icon', 'mihdan-elementor-yandex-maps' ),
							'Waste'                     => __( 'Waste Icon', 'mihdan-elementor-yandex-maps' ),
							'WaterPark'                 => __( 'WaterPark Icon', 'mihdan-elementor-yandex-maps' ),
							'Waterway'                  => __( 'Waterway Icon', 'mihdan-elementor-yandex-maps' ),
							'Worship'                   => __( 'Worship Icon', 'mihdan-elementor-yandex-maps' ),
							'Zoo'                       => __( 'Zoo Icon', 'mihdan-elementor-yandex-maps' ),
							'AirportCircle'             => __( 'Airport Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'AttentionCircle'           => __( 'Attention Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'AutoCircle'                => __( 'Auto Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'BarCircle'                 => __( 'Bar Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'BarberCircle'              => __( 'Barber Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'BeachCircle'               => __( 'Beach Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'BicycleCircle'             => __( 'Bicycle Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'Bicycle2Circle'            => __( 'Bicycle2 Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'BookCircle'                => __( 'Book Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'CarWashCircle'             => __( 'CarWash Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'ChristianCircle'           => __( 'Book Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'CinemaCircle'              => __( 'Cinema Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'CircusCircle'              => __( 'Circus Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'CourtCircle'               => __( 'Court Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'DeliveryCircle'            => __( 'Delivery Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'DiscountCircle'            => __( 'Discount Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'DogCircle'                 => __( 'Dog Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'EducationCircle'           => __( 'Education Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'EntertainmentCenterCircle' => __( 'EntertainmentCenter Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'FactoryCircle'             => __( 'Factory Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'FamilyCircle'              => __( 'Family Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'FashionCircle'             => __( 'Fashion Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'FoodCircle'                => __( 'Food Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'FuelStationCircle'         => __( 'FuelStation Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'GardenCircle'              => __( 'Garden Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'GovernmentCircle'          => __( 'Government Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'HeartCircle'               => __( 'Heart Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'HomeCircle'                => __( 'Home Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'HotelCircle'               => __( 'Hotel Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'HydroCircle'               => __( 'Hydro Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'InfoCircle'                => __( 'Info Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'LaundryCircle'             => __( 'Laundry Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'LeisureCircle'             => __( 'Leisure Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'MassTransitCircle'         => __( 'MassTransit Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'MedicalCircle'             => __( 'Medical Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'MoneyCircle'               => __( 'Money Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'MountainCircle'            => __( 'Mountain Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'NightClubCircle'           => __( 'NightClub Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'ObservationCircle'         => __( 'Observation Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'ParkCircle'                => __( 'Park Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'ParkingCircle'             => __( 'Parking Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'PersonCircle'              => __( 'Person Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'PocketCircle'              => __( 'Pocket Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'PoolCircle'                => __( 'Pool Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'PostCircle'                => __( 'Post Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'RailwayCircle'             => __( 'Railway Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'RapidTransitCircle'        => __( 'RapidTransit Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'RepairShopCircle'          => __( 'RepairShop Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'RunCircle'                 => __( 'Run Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'ScienceCircle'             => __( 'Science Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'ShoppingCircle'            => __( 'Shopping Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'SouvenirsCircle'           => __( 'Souvenirs Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'SportCircle'               => __( 'Sport Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'StarCircle'                => __( 'Star Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'TheaterCircle'             => __( 'Theater Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'ToiletCircle'              => __( 'Toilet Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'UnderpassCircle'           => __( 'Underpass Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'VegetationCircle'          => __( 'Vegetation Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'VideoCircle'               => __( 'Video Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'WasteCircle'               => __( 'Waste Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'WaterParkCircle'           => __( 'WaterPark Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'WaterwayCircle'            => __( 'Waterway Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'WorshipCircle'             => __( 'Worship Circle Icon', 'mihdan-elementor-yandex-maps' ),
							'ZooCircle'                 => __( 'Zoo Circle Icon', 'mihdan-elementor-yandex-maps' ),
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
				'title_field' => '{{{ balloon_content_header }}}',
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
			'type'          => 'FeatureCollection',
			'clusterPreset' => sprintf( 'islands#%sClusterIcons', $settings['cluster_color'] ),
			'features'      => array(),
		);

		foreach ( $settings['tabs'] as $index => $item ) {
			// Для старых версий.
			$point_lat              = ( isset( $item['pin_lat'] ) ) ? $item['pin_lat'] : $item['point_lat'];
			$point_lng              = ( isset( $item['pin_lng'] ) ) ? $item['pin_lng'] : $item['point_lng'];
			$balloon_content_header = ( isset( $item['pin_title'] ) ) ? $item['pin_title'] : $item['balloon_content_header'];
			$balloon_content_body   = ( isset( $item['pin_content'] ) ) ? $item['pin_content'] : $item['balloon_content_body'];

			$geo_json['features'][] = array(
				'type'       => 'Feature',
				'id'         => 'id_' . $index,
				'geometry'   => array(
					'type'        => 'Point',
					'coordinates' => array(
						$point_lat,
						$point_lng,
					),
				),
				'properties' => array(
					'iconCaption'          => $item['icon_caption'],
					'iconContent'          => $item['icon_content'],
					'hintContent'          => $item['hint_content'],
					'balloonContentHeader' => $balloon_content_header,
					'balloonContentFooter' => $item['balloon_content_footer'],
					'balloonContentBody'   => htmlspecialchars( $balloon_content_body, ENT_QUOTES & ~ENT_COMPAT ),
				),
				'options'    => array(
					'preset'          => sprintf( 'islands#%s%sIcon', $item['icon_color'], $item['icon_type'] ),
					'balloonIsOpened' => $item['balloon_is_opened'],
				),
			);
		}
		?>

		<div id="eb-map-<?php echo esc_attr( $this->get_id() ); ?>"
		    class="eb-map"
		    data-eb-map-lat="<?php echo esc_attr( $settings['map_lat'] ); ?>"
		    data-eb-map-lng="<?php echo esc_attr( $settings['map_lng'] ); ?>"
		    data-eb-map-zoom="<?php echo esc_attr( $settings['zoom']['size'] ); ?>"
		    data-eb-map-type="<?php echo esc_attr( $settings['map_type'] ); ?>"
		    data-eb-ruler-control="<?php echo esc_attr( $settings['ruler_control'] ); ?>"
		    data-eb-search-control="<?php echo esc_attr( $settings['search_control'] ); ?>"
		    data-eb-traffic-control="<?php echo esc_attr( $settings['traffic_control'] ); ?>"
		    data-eb-type-selector="<?php echo esc_attr( $settings['type_selector'] ); ?>"
		    data-eb-zoom-control="<?php echo esc_attr( $settings['zoom_control'] ); ?>"
		    data-eb-geolocation-control="<?php echo esc_attr( $settings['geolocation_control'] ); ?>"
		    data-eb-route-editor="<?php echo esc_attr( $settings['route_editor'] ); ?>"
		    data-eb-fullscreen-control="<?php echo esc_attr( $settings['fullscreen_control'] ); ?>"
		    data-eb-route-button-control="<?php echo esc_attr( $settings['route_button_control'] ); ?>"
		    data-eb-route-panel-control="<?php echo esc_attr( $settings['route_panel_control'] ); ?>"
		    data-eb-disable-scroll-zoom="<?php echo esc_attr( $settings['disable_scroll_zoom'] ); ?>"
		    data-eb-disable-dbl-click-zoom="<?php echo esc_attr( $settings['disable_dbl_click_zoom'] ); ?>"
		    data-eb-disable-drag="<?php echo esc_attr( $settings['disable_drag'] ); ?>"
		    data-eb-disable-left-mouse-button-magnifier="<?php echo esc_attr( $settings['disable_left_mouse_button_magnifier'] ); ?>"
		    data-eb-disable-right-mouse-button-magnifier="<?php echo esc_attr( $settings['disable_right_mouse_button_magnifier'] ); ?>"
		    data-eb-disable-multi-touch="<?php echo esc_attr( $settings['disable_multi_touch'] ); ?>"
		    data-eb-disable-route-editor="<?php echo esc_attr( $settings['disable_route_editor'] ); ?>"
		    data-eb-disable-ruler="<?php echo esc_attr( $settings['disable_ruler'] ); ?>"
		    data-eb-enable-object-manager="<?php echo esc_attr( $settings['enable_object_manager'] ); ?>"
		    data-eb-infowindow-max-width="<?php echo esc_attr( $settings['infowindow_max_width'] ); ?>"
		    data-eb-locations='<?php echo wp_json_encode( $geo_json ); ?>'
		    style="height: <?php echo esc_attr( $settings['height']['size'] ); ?><?php echo esc_attr( $settings['height']['unit'] ); ?>;"></div>
		<?php
	}
}

// eof.
