<?php
/**
 * Виджет карты
 *
 * @package mihdan-elementor-yandex-maps
 * @link https://developers.elementor.com/docs/editor-controls/conditional-display/
 */

namespace Mihdan\ElementorYandexMaps;

use Elementor\Core\DynamicTags\Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Widget
 */
class Widget extends Widget_Base {

	/**
	 * Широта по умолчанию.
	 *
	 * @var string $default_lat
	 */
	private $default_lat = '55.7522200';

	/**
	 * Долгота по умолчанию.
	 *
	 * @var string $default_lng
	 */
	private $default_lng = '37.6155600';

	/**
	 * Цвета иконок.
	 *
	 * @var array $icon_colors
	 */
	private $icon_colors = array();

	/**
	 * Типы иконок.
	 *
	 * @var array $icon_types
	 */
	private $icon_types = array();

	/**
	 * Widget constructor.
	 *
	 * @param array $data Data.
	 * @param null  $args Arguments.
	 *
	 * @throws Exception Exception.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		$this->setup();
	}

	/**
	 * Setup variables.
	 */
	public function setup() {
		$this->icon_colors = array(
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
		);

		$this->icon_types = array(
			''                          => __( 'Default Icon', 'mihdan-elementor-yandex-maps' ),
			'Custom'                    => __( 'Custom', 'mihdan-elementor-yandex-maps' ),
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
		);
	}

	/**
	 * Get default latitude.
	 *
	 * @return string
	 */
	public function get_default_lat() {
		return $this->default_lat;
	}

	/**
	 * Get default longitude.
	 *
	 * @return string
	 */
	public function get_default_lng() {
		return $this->default_lng;
	}

	/**
	 * Get icon colors array.
	 *
	 * @return array
	 */
	public function get_icon_colors() {
		return $this->icon_colors;
	}

	/**
	 * Get icon types array.
	 *
	 * @return array
	 */
	public function get_icon_types() {
		return $this->icon_types;
	}

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
		return 'elementor-yandex-map-icon';
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
		return array( 'mihdan' );
	}

	/**
	 * Get script depends
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'mihdan-elementor-yandex-maps' );
	}


	/**
	 * Register yandex maps widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		/**
		 * Настройки карты
		 */
		$this->start_controls_section(
			'section_map',
			array(
				'label' => __( 'Map', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'map_notice',
			array(
				'label'       => __( 'Find Latitude & Longitude', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => '<form onsubmit="return mihdan_elementor_yandex_maps_find_address( this );"><input type="search" style="margin-top:10px; margin-bottom:10px;" placeholder="' . __( 'Enter Search Address', 'mihdan-elementor-yandex-maps' ) . '" /><input type="submit" value="Search" class="elementor-button elementor-button-default"></form><div class="mihdan-elementor-yandex-maps-output-result"></div>',
				'label_block' => true,
			)
		);

		$this->add_control(
			'map_lat',
			array(
				'label'       => __( 'Latitude', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => $this->get_default_lat(),
				'default'     => $this->get_default_lat(),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'map_lng',
			array(
				'label'       => __( 'Longitude', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => $this->get_default_lng(),
				'default'     => $this->get_default_lng(),
				'dynamic'     => array(
					'active' => true,
				),
				'separator'   => true,
			)
		);

		$this->add_responsive_control(
			'zoom',
			array(
				'type'                 => Controls_Manager::SLIDER,
				'label'                => __( 'Zoom Level', 'mihdan-elementor-yandex-maps' ),
				'dynamic'              => array(
					'active' => true,
				),
				'range'                => array(
					'px' => array(
						'min' => 1,
						'max' => 19,
					),
				),
				'default'              => [ 'size' => 10 ],
				'tablet_default'       => [ 'size' => 10 ],
				'tablet_extra_default' => [ 'size' => 10 ],
				'mobile_default'       => [ 'size' => 10 ],
				'mobile_extra_default' => [ 'size' => 10 ],
				'laptop_default'       => [ 'size' => 10 ],
				'widescreen_default'   => [ 'size' => 10 ],
			)
		);

		$this->add_responsive_control(
			'height',
			[
				'type'                 => Controls_Manager::SLIDER,
				'label'                => __( 'Height', 'mihdan-elementor-yandex-maps' ),
				'dynamic'              => [
					'active' => true,
				],
				'range'                => [
					'px'   => [
						'min' => 300,
						'max' => 1400,
					],
					'vmin' => [
						'min' => 1,
						'max' => 100,
					],
					'vmax' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'size_units'           => [ 'px', 'vw', 'vh', 'vmin', 'vmax', 'custom' ],
				'default'              => [
					'size' => 300,
					'unit' => 'px',
				],
				'tablet_default'       => [
					'size' => 400,
					'unit' => 'px',
				],
				'tablet_extra_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'mobile_default'       => [
					'size' => 400,
					'unit' => 'px',
				],
				'mobile_extra_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'laptop_default'       => [
					'size' => 400,
					'unit' => 'px',
				],
				'widescreen_default'   => [
					'size' => 600,
					'unit' => 'px',
				],
				'selectors'            => [
					'{{WRAPPER}} .mihdan-elementor-yandex-maps' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'map_type',
			array(
				'label'   => __( 'Map Type', 'mihdan-elementor-yandex-maps' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'map'       => __( 'Road Map', 'mihdan-elementor-yandex-maps' ),
					'satellite' => __( 'Satellite', 'mihdan-elementor-yandex-maps' ),
					'hybrid'    => __( 'Hybrid', 'mihdan-elementor-yandex-maps' ),
				),
				'default' => 'map',
			)
		);

		$this->add_control(
			'map_language',
			array(
				'label'       => __( 'Map Language', 'mihdan-elementor-yandex-maps' ),
				'description' => __( 'Задает язык объектов на карте (топонимов, элементов управления).', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'ru' => __( 'Russian', 'mihdan-elementor-yandex-maps' ),
					'uk' => __( 'Ukrainian', 'mihdan-elementor-yandex-maps' ),
					'en' => __( 'English', 'mihdan-elementor-yandex-maps' ),
					'tr' => __( 'Turkish', 'mihdan-elementor-yandex-maps' ),
				),
				'default'     => 'ru',
			)
		);

		$this->add_control(
			'map_region',
			array(
				'label'       => __( 'Map Region', 'mihdan-elementor-yandex-maps' ),
				'description' => __( 'Определяет региональные особенности, например единицу измерения (для обозначения расстояния между объектами или скорости движения по маршруту). Для регионов RU, UA и TR расстояние показывается в километрах, для US — в милях.', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'RU' => __( 'Russia', 'mihdan-elementor-yandex-maps' ),
					'UA' => __( 'Ukraine', 'mihdan-elementor-yandex-maps' ),
					'US' => __( 'USA', 'mihdan-elementor-yandex-maps' ),
					'TR' => __( 'Turkey', 'mihdan-elementor-yandex-maps' ),
				),
				'default'     => 'RU',
			)
		);

		$this->add_control(
			'map_filter',
			array(
				'label'       => __( 'Map Filter', 'mihdan-elementor-yandex-maps' ),
				'description' => __( 'Позволяет перекрасить карту.', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'none'       => __( 'None', 'mihdan-elementor-yandex-maps' ),
					'grayscale'  => __( 'Grayscale', 'mihdan-elementor-yandex-maps' ),
					'sepia'      => __( 'Sepia', 'mihdan-elementor-yandex-maps' ),
					'brightness' => __( 'Brightness', 'mihdan-elementor-yandex-maps' ),
					'green'      => __( 'Green', 'mihdan-elementor-yandex-maps' ),
					'blue'       => __( 'Blue', 'mihdan-elementor-yandex-maps' ),
					'purple'     => __( 'Purple', 'mihdan-elementor-yandex-maps' ),
					'invert'     => __( 'Invert', 'mihdan-elementor-yandex-maps' ),
				),
				'default'     => 'none',
			)
		);

		$this->add_control(
			'view',
			array(
				'label'   => __( 'View', 'mihdan-elementor-yandex-maps' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			)
		);

		$this->end_controls_section();

		/**
		 * Контроллы карты
		 */
		$this->start_controls_section(
			'map_controls',
			array(
				'label' => __( 'Map Controls', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'ruler_control',
			array(
				'label'       => __( 'Ruler Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Линейка и масштабный отрезок', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'search_control',
			array(
				'label'       => __( 'Search Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Поиск на карте', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'traffic_control',
			array(
				'label'       => __( 'Traffic Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Панель пробок', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'type_selector',
			array(
				'label'       => __( 'Type Selector', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'Панель переключения типа карт', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'zoom_control',
			array(
				'label'       => __( 'Zoom Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'Ползунок масштаба', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'geolocation_control',
			array(
				'label'       => __( 'Geolocation Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Элемент управления геолокацией', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'route_editor',
			array(
				'label'       => __( 'Route Editor', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Редактор маршрутов', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'fullscreen_control',
			array(
				'label'       => __( 'Fullscreen Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Элемент управления «полноэкранным режимом»', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'route_button_control',
			array(
				'label'       => __( 'Route Button Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Панель для построения маршрутов', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'route_panel_control',
			array(
				'label'       => __( 'Route Panel Control', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Панель маршрутизации', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->end_controls_section();

		/**
		 * Поведение карты
		 */
		$this->start_controls_section(
			'map_behavior',
			array(
				'label' => __( 'Map Behavior', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'enable_save_map',
			array(
				'label'       => __( 'Enable map saving', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Enables the ability to save the current map state and share a link to it', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'disable_scroll_zoom',
			array(
				'label'       => __( 'Disable Scroll Zoom', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить прокрутку карты колесом мыши', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'disable_dbl_click_zoom',
			array(
				'label'       => __( 'Disable Dbl Click Zoom', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить масштабирование карты двойным щелчком кнопки мыши', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'disable_drag',
			array(
				'label'       => __( 'Disable Drag', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить перетаскивание карты с помощью мыши либо одиночного касания', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'disable_left_mouse_button_magnifier',
			array(
				'label'       => __( 'Disable Left Mouse Button Magnifier', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить масштабирование карты при выделении области левой кнопкой мыши', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'disable_right_mouse_button_magnifier',
			array(
				'label'       => __( 'Disable Right Mouse Button Magnifier', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить масштабирование карты при выделении области правой кнопкой мыши', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'disable_multi_touch',
			array(
				'label'       => __( 'Disable Multi Touch', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить масштабирование карты мультисенсорным касанием', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'disable_route_editor',
			array(
				'label'       => __( 'Disable Route Editor', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить редактор маршрутов', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'disable_ruler',
			array(
				'label'       => __( 'Disable Ruler', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Отключить линейку', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'disable_lazy_load',
			array(
				'label'       => __( 'Disable Lazy Load', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Completely disable lazy loading of map', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->end_controls_section();

		/**
		 * Object Manager
		 */
		$this->start_controls_section(
			'map_object_manager',
			array(
				'label' => __( 'Object Manager', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'enable_object_manager',
			array(
				'label'       => __( 'Enable Object Manager', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Включить кластеризацию', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'cluster_color',
			array(
				'label'   => __( 'Cluster Color', 'mihdan-elementor-yandex-maps' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_icon_colors(),
				'default' => 'blue',
			)
		);

		$this->end_controls_section();

		/**
		 * Pins Option
		 */
		$this->start_controls_section(
			'map_marker_pin',
			array(
				'label' => __( 'Marker Pins', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->start_controls_tabs(
			'points_source'
		);

			$this->start_controls_tab(
				'points_source_manual',
				array(
					'label' => __( 'Manual', 'mihdan-elementor-yandex-maps' ),
				)
			);

				$pin_repeater = new Repeater();

				$pin_repeater->add_control(
					'pin_notice',
					array(
						'label' => __( 'Find Latitude & Longitude', 'mihdan-elementor-yandex-maps' ),
						'type'  => Controls_Manager::RAW_HTML,
						'raw'   => sprintf(
							'<form onsubmit="mihdan_elementor_yandex_maps_find_pin_address( this, \'%s\' );" action="javascript:void(0);"><input type="search" class="mihdan-elementor-yandex-maps-find-address" placeholder="%s" /><input type="submit" value="%s" class="elementor-button elementor-button-default"></form><div class="mihdan-elementor-yandex-maps-output-result"></div>',
							$this->get_id(),
							__( 'Enter Search Address', 'mihdan-elementor-yandex-maps' ),
							__( 'Search', 'mihdan-elementor-yandex-maps' )
						),
					)
				);

				$pin_repeater->add_control(
					'point_lat',
					array(
						'label'       => __( 'Latitude', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => $this->get_default_lat(),
						'placeholder' => $this->get_default_lat(),
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$pin_repeater->add_control(
					'point_lng',
					array(
						'label'       => __( 'Longitude', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => $this->get_default_lng(),
						'placeholder' => $this->get_default_lng(),
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$pin_repeater->add_control(
					'icon_color',
					array(
						'label'   => __( 'Icon Color', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::SELECT,
						'options' => $this->get_icon_colors(),
						'default' => 'blue',
					)
				);

				$pin_repeater->add_control(
					'icon_type',
					array(
						// @link https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage-docpage/
						'label'   => __( 'Icon Type', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::SELECT2,
						'options' => $this->get_icon_types(),
						'default' => 'Circle',
					)
				);

				$pin_repeater->add_control(
					'icon_size',
					array(
						'label'     => __( 'Icon Size', 'mihdan-elementor-yandex-maps' ),
						'type'      => Controls_Manager::NUMBER,
						'condition' => array(
							'icon_type' => 'Custom',
						),
						'min'       => 16,
						'max'       => 100,
						'step'      => 2,
						'default'   => 32,
					)
				);

				$pin_repeater->add_control(
					'icon_image',
					array(
						'label'     => __( 'Image', 'mihdan-elementor-yandex-maps' ),
						'type'      => Controls_Manager::MEDIA,
						'condition' => array(
							'icon_type' => 'Custom',
						),
						'dynamic'   => array(
							'active' => true,
						),
					)
				);

				$pin_repeater->add_control(
					'icon_caption',
					array(
						'label'       => __( 'Icon Caption', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '',
						'label_block' => true,
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$pin_repeater->add_control(
					'icon_content',
					array(
						'label'       => __( 'Icon Content', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '',
						'label_block' => true,
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$pin_repeater->add_control(
					'hint_content',
					array(
						'label'       => __( 'Hint Content', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '',
						'label_block' => true,
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$pin_repeater->add_control(
					'balloon_content_header',
					array(
						'label'       => __( 'Balloon Content Header', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => __( 'Balloon Content Header Default', 'mihdan-elementor-yandex-maps' ),
						'label_block' => true,
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$pin_repeater->add_control(
					'balloon_content_body',
					array(
						'label'   => __( 'Balloon Content Body', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::WYSIWYG,
						'default' => '',
						'dynamic' => array(
							'active' => true,
						),
					)
				);

				$pin_repeater->add_control(
					'balloon_content_footer',
					array(
						'label'   => __( 'Balloon Content Footer', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::TEXTAREA,
						'default' => '',
						'dynamic' => array(
							'active' => true,
						),
					)
				);

				$pin_repeater->add_control(
					'balloon_is_opened',
					array(
						'label'   => __( 'Balloon Is Opened', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::SWITCHER,
						'default' => 'no',
					)
				);

				$this->add_control(
					'tabs',
					array(
						'label'       => __( 'Pin Item', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::REPEATER,
						'default'     => array(
							array(
								'pin_notice'             => __( 'Find Latitude & Longitude', 'mihdan-elementor-yandex-maps' ),
								'point_lat'              => $this->get_default_lat(),
								'point_lng'              => $this->get_default_lng(),
								'icon_color'             => 'blue',
								'icon_type'              => 'Circle',
								'icon_caption'           => '',
								'icon_content'           => '',
								'hint_content'           => '',
								'balloon_content_header' => __( 'Balloon Content Header Default', 'mihdan-elementor-yandex-maps' ),
								'balloon_content_body'   => '',
								'balloon_content_footer' => '',
								'balloon_is_opened'      => 'no',
							),
						),
						'fields'      => $pin_repeater->get_controls(),
						'title_field' => '{{{ balloon_content_header }}}',
					)
				);

			$this->end_controls_tab(); // End manual tab.

			$this->start_controls_tab(
				'points_source_post_types',
				array(
					'label' => __( 'Post Type', 'mihdan-elementor-yandex-maps' ),
				)
			);

				// Получаем все зарегистрированные типы записей.
				$post_types = Utils::get_public_post_types();

				$this->add_control(
					'points_source_post_type',
					array(
						'label'   => __( 'Post Type', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::SELECT,
						'options' =>
							array_merge(
								[
									'none' => __( 'Not select', 'mihdan-elementor-yandex-maps' ),
								],
								$post_types
							),
						'default' => 'none',
					)
				);

				// Для каждого типа записей находим привязанные таксономии.
				foreach ( $post_types as $post_type_slug => $post_type_name ) {

					$taxonomies = Utils::get_taxonomies( $post_type_slug );

					// Для каждой таксономии создаем список
					// со множественным выбором термов.
					foreach ( $taxonomies as $taxonomy_slug => $taxonomy_name ) {
						$this->add_control(
							'points_source_post_type_taxonomy_' . $taxonomy_slug,
							array(
								'label'     => $taxonomy_name,
								'type'      => Controls_Manager::SELECT2,
								'options'   => Utils::get_terms( $taxonomy_slug ),
								'default'   => 'none',
								'multiple'  => true,
								'condition' => [
									'points_source_post_type' => $post_type_slug,
								],
							)
						);
					}
				}

				$this->add_control(
					'points_source_post_type_limit',
					array(
						'label'   => __( 'Limit', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::NUMBER,
						'default' => 100,
						'min'     => 1,
						'max'     => 10000,
					)
				);

				$this->add_control(
					'points_source_post_type_lat',
					array(
						'label'       => __( 'Latitude', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => $this->get_default_lat(),
						'placeholder' => $this->get_default_lat(),
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$this->add_control(
					'points_source_post_type_lng',
					array(
						'label'       => __( 'Longitude', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => $this->get_default_lng(),
						'placeholder' => $this->get_default_lng(),
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$this->add_control(
					'points_source_post_type_icon_color',
					array(
						'label'   => __( 'Icon Color', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::SELECT,
						'options' => $this->get_icon_colors(),
						'default' => 'blue',
					)
				);

				$this->add_control(
					'points_source_post_type_icon_type',
					array(
						'label'   => __( 'Icon Type', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::SELECT2,
						'options' => $this->get_icon_types(),
						'default' => 'Circle',
					)
				);

				$this->add_control(
					'points_source_post_type_icon_size',
					array(
						'label'     => __( 'Icon Size', 'mihdan-elementor-yandex-maps' ),
						'type'      => Controls_Manager::NUMBER,
						'condition' => array(
							'points_source_post_type_icon_type' => 'Custom',
						),
						'min'       => 16,
						'max'       => 100,
						'step'      => 2,
						'default'   => 32,
					)
				);

				$this->add_control(
					'points_source_post_type_icon_image',
					array(
						'label'     => __( 'Icon Image', 'mihdan-elementor-yandex-maps' ),
						'type'      => Controls_Manager::MEDIA,
						'condition' => array(
							'points_source_post_type_icon_type' => 'Custom',
						),
						'dynamic'   => array(
							'active' => true,
						),
					)
				);

				$this->add_control(
					'points_source_post_type_icon_caption',
					array(
						'label'       => __( 'Icon Caption', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '',
						'label_block' => true,
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$this->add_control(
					'points_source_post_type_icon_content',
					array(
						'label'       => __( 'Icon Content', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '',
						'label_block' => true,
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$this->add_control(
					'points_source_post_type_hint_content',
					array(
						'label'       => __( 'Hint Content', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '',
						'label_block' => true,
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$this->add_control(
					'points_source_post_type_balloon_content_header',
					array(
						'label'       => __( 'Balloon Content Header', 'mihdan-elementor-yandex-maps' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => __( 'Balloon Content Header Default', 'mihdan-elementor-yandex-maps' ),
						'label_block' => true,
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$this->add_control(
					'points_source_post_type_balloon_content_body',
					array(
						'label'   => __( 'Balloon Content Body', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::WYSIWYG,
						'default' => '',
						'dynamic' => array(
							'active' => true,
						),
					)
				);

				$this->add_control(
					'points_source_post_type_balloon_content_footer',
					array(
						'label'   => __( 'Balloon Content Footer', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::TEXTAREA,
						'default' => '',
						'dynamic' => array(
							'active' => true,
						),
					)
				);

				$this->add_control(
					'points_source_post_type_show_post_thumbnail',
					array(
						'label'   => __( 'Show Post Thumbnail', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::SWITCHER,
						'default' => 'no',
					)
				);

				$this->add_control(
					'points_source_post_type_show_post_permalink',
					array(
						'label'   => __( 'Show Post Permalink', 'mihdan-elementor-yandex-maps' ),
						'type'    => Controls_Manager::SWITCHER,
						'default' => 'no',
					)
				);

			$this->end_controls_tab(); // End CPT tab.

			$this->start_controls_tab(
				'points_source_kml',
				array(
					'label' => __( 'KML', 'mihdan-elementor-yandex-maps' ),
				)
			);

				$this->add_control(
					'zalupa',
					array(
						'type' => Controls_Manager::RAW_HTML,
						'raw'  => '<p>Ожидайте в следующей версии плагина. Помочь в его развитии вы всегда можете <a href="https://www.kobzarev.com/donate/" target="_blank">здесь</a>.</p>',
					)
				);

			$this->end_controls_tab(); // End KML tab.

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Balloon Options.
		 */
		$this->start_controls_section(
			'map_balloon',
			array(
				'label' => __( 'Balloon', 'mihdan-elementor-yandex-maps' ),
			)
		);

		$this->add_control(
			'infowindow_max_width',
			array(
				'label'       => __( 'Max Width', 'mihdan-elementor-yandex-maps' ),
				'description' => __( 'Set the maximum width of the balloon', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '300',
				'default'     => '300',
			)
		);

		$this->add_control(
			'enable_balloon_panel',
			array(
				'label'       => __( 'Enable Balloon Panel', 'mihdan-elementor-yandex-maps' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'description' => __( 'Enable the ability to collapse the balloon into a panel on small screens', 'mihdan-elementor-yandex-maps' ),
			)
		);
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
		$map_id = $this->get_map_id();

		$breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();
		$settings    = $this->get_settings_for_display();

		$geo_json = array(
			'type'          => 'FeatureCollection',
			'clusterPreset' => sprintf( 'islands#%sClusterIcons', $settings['cluster_color'] ),
			'features'      => array(),
		);

		/**
		 * Откуда тянуть точки для карты: KML, CPT, ручками.
		 */
		if ( ! empty( $settings['points_source_post_type'] ) && 'none' !== $settings['points_source_post_type'] ) {
			$args = array(
				'post_type'      => $settings['points_source_post_type'],
				'post_status'    => 'publish',
				'no_found_rows'  => true,
				'posts_per_page' => $settings['points_source_post_type_limit'],
				'tax_query'      => [],
			);

			// Фильтр по выбранному терму указанной таксономии.
			$terms = Utils::array_keys_by_regex( $settings, '/points_source_post_type_taxonomy_/' );

			foreach ( $terms as $taxonomy => $term ) {
				if ( ! is_array( $term ) ) {
					continue;
				}

				// phpcs:ignore Detected usage of tax_query, possible slow query.
				$args['tax_query'][] =
					[
						'taxonomy' => str_replace( 'points_source_post_type_taxonomy_', '', $taxonomy ),
						'terms'    => array_map( 'intval', $term ),
					];
			}

			$args = apply_filters( 'mihdan_elementor_yandex_maps_posts_args', $args, $map_id );

			$points = get_posts( $args );

			if ( $points ) {
				$i                = 0;
				$settings['tabs'] = array();

				foreach ( $points as $point ) {

					$balloon_content_body   = $this->calculate_dynamic_content( 'points_source_post_type_balloon_content_body', $settings, $point->ID );
					$balloon_content_footer = $this->calculate_dynamic_content( 'points_source_post_type_balloon_content_footer', $settings, $point->ID );
					$icon_caption           = $this->calculate_dynamic_content( 'points_source_post_type_icon_caption', $settings, $point->ID );
					$icon_content           = $this->calculate_dynamic_content( 'points_source_post_type_icon_content', $settings, $point->ID );
					$hint_content           = $this->calculate_dynamic_content( 'points_source_post_type_hint_content', $settings, $point->ID );

					if ( 'yes' === $settings['points_source_post_type_show_post_thumbnail'] ) {
						$balloon_content_body = sprintf( '<img src="%s" width="280" />', get_the_post_thumbnail_url( $point->ID, 'small' ) ) . $balloon_content_body;
					}

					if ( 'yes' === $settings['points_source_post_type_show_post_permalink'] ) {
						$balloon_content_footer = $balloon_content_footer . sprintf( '<a href="%s">Подробнее</a>', get_permalink( $point->ID ) );
					}

					$settings['tabs'][ $i ] = array(
						'point_lat'              => $this->calculate_dynamic_content( 'points_source_post_type_lat', $settings, $point->ID ),
						'point_lng'              => $this->calculate_dynamic_content( 'points_source_post_type_lng', $settings, $point->ID ),
						'balloon_content_header' => $this->calculate_dynamic_content( 'points_source_post_type_balloon_content_header', $settings, $point->ID ),
						'balloon_content_body'   => apply_shortcodes( $balloon_content_body ),
						'balloon_content_footer' => $balloon_content_footer,
						'icon_caption'           => $icon_caption,
						'icon_content'           => $icon_content,
						'hint_content'           => $hint_content,
						'icon_color'             => $settings['points_source_post_type_icon_color'],
						'icon_type'              => $settings['points_source_post_type_icon_type'],
						'icon_image'             => $this->calculate_dynamic_content( 'points_source_post_type_icon_image', $settings, $point->ID ),
						'icon_size'              => $settings['points_source_post_type_icon_size'],
						'balloon_is_opened'      => 'no',
					);

					$i ++;
				}
			}

			wp_reset_postdata();
		}

		foreach ( $settings['tabs'] as $index => $item ) {
			// Для старых версий.
			$point_lat              = $item['pin_lat'] ?? $item['point_lat'];
			$point_lng              = $item['pin_lng'] ?? $item['point_lng'];
			$balloon_content_header = $item['pin_title'] ?? $item['balloon_content_header'];
			$balloon_content_body   = $item['pin_content'] ?? $item['balloon_content_body'];
			$icon_image             = ( 'Custom' === $item['icon_type'] && isset( $item['icon_image'] ) )
				? $item['icon_image']
				: '';

			$icon_image_url    = '';
			$icon_image_width  = '';
			$icon_image_height = '';

			// Custom marker.
			if ( $icon_image ) {
				$icon_image_width  = $item['icon_size'];
				$icon_image_height = $item['icon_size'];
				$icon_image_url    = $icon_image['url'];
			}

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
					'balloonContentBody'   => apply_shortcodes( $balloon_content_body ),
				),
				'options'    => array(
					'preset'          => sprintf( 'islands#%s%sIcon', $item['icon_color'], $item['icon_type'] ),
					'balloonIsOpened' => $item['balloon_is_opened'],
					'iconImage'       => $icon_image_url,
					'iconImageWidth'  => $icon_image_width,
					'iconImageHeight' => $icon_image_height,
				),
			);
		}

		$classes = array(
			'mihdan-elementor-yandex-maps',
		);

		// Класс с фильтрами.
		if ( 'none' !== $settings['map_filter'] ) {
			$classes[] = 'mihdan-elementor-yandex-maps_filter_' . $settings['map_filter'];
		}

		// Отзывчивый масштаб карты.
		$zoom = [
			'desktop' => $settings['zoom']['size'],
		];

		foreach ( $breakpoints as $breakpoint_id => $breakpoint_data ) {

			if ( ! empty( $settings[ 'zoom_' . $breakpoint_id ] ) ) {
				$zoom[ $breakpoint_id ] = $settings[ 'zoom_' . $breakpoint_id ]['size'];
			} else {
				$zoom[ $breakpoint_id ] = 10;
			}
		}

		// Генерируем конфиг для JS.
		$config = [
			'id'                               => $map_id,
			'language'                         => $settings['map_language'],
			'region'                           => $settings['map_region'],
			'lat'                              => $settings['map_lat'],
			'lng'                              => $settings['map_lng'],
			'zoom'                             => $zoom,
			'type'                             => $settings['map_type'],
			'rulerControl'                     => $settings['ruler_control'],
			'searchControl'                    => $settings['search_control'],
			'trafficControl'                   => $settings['traffic_control'],
			'traffitypeSelectorcControl'       => $settings['type_selector'],
			'zoomControl'                      => $settings['zoom_control'],
			'geolocationControl'               => $settings['geolocation_control'],
			'routeEditor'                      => $settings['route_editor'],
			'fullscreenControl'                => $settings['fullscreen_control'],
			'routeButtonControl'               => $settings['route_button_control'],
			'routePanelControl'                => $settings['route_panel_control'],
			'enableSaveMap'                    => $settings['enable_save_map'],
			'disableScrollZoom'                => $settings['disable_scroll_zoom'],
			'disableDblClickZoom'              => $settings['disable_dbl_click_zoom'],
			'disableDrag'                      => $settings['disable_drag'],
			'disableLeftMouseButtonMagnifier'  => $settings['disable_left_mouse_button_magnifier'],
			'disableRightMouseButtonMagnifier' => $settings['disable_right_mouse_button_magnifier'],
			'disableMultiTouch'                => $settings['disable_multi_touch'],
			'disableRouteEditor'               => $settings['disable_route_editor'],
			'disableRuler'                     => $settings['disable_ruler'],
			'disableLazyLoad'                  => $settings['disable_lazy_load'],
			'enableObjectManager'              => $settings['enable_object_manager'],
			'infoWindowMaxWidth'               => $settings['infowindow_max_width'],
			'enableBalloonPanel'               => $settings['enable_balloon_panel'] ?? 'no',
			'locations'                        => $geo_json,
		];

		$this->add_render_attribute(
			'map',
			[
				'id'              => 'mihdan_elementor_yandex_map_' . esc_attr( $map_id ),
				'data-map_id'     => esc_attr( $map_id ),
				'data-map_config' => wp_json_encode( $config, JSON_UNESCAPED_UNICODE ),
				'class'           => esc_attr( implode( ' ', $classes ) ),
			]
		);
		?>
		<div <?php $this->print_render_attribute_string( 'map' ); ?>></div>
		<?php
	}

	/**
	 * Calculate dynamic conntent for key.
	 *
	 * @param string $key      Key name for search.
	 * @param array  $settings Array of settings.
	 * @param int    $post_id  Post ID.
	 *
	 * @return mixed|string
	 */
	private function calculate_dynamic_content( $key, $settings, $post_id ) {

		if ( ! isset( $settings[ Manager::DYNAMIC_SETTING_KEY ][ $key ] ) ) {
			return $settings[ $key ];
		}

		$settings = $settings[ Manager::DYNAMIC_SETTING_KEY ][ $key ];
		$data     = Plugin::instance()->dynamic_tags->tag_text_to_tag_data( $settings );

		switch ( $data['name'] ) {
			case 'acf-text':
				$value = get_field( explode( ':', $data['settings']['key'] )[0], $post_id );
				break;
			case 'post-custom-field':
				$value = get_post_meta( $post_id, $data['settings']['custom_key'], true );
				break;
			case 'jet-post-custom-field':
				$value = get_post_meta( $post_id, $data['settings']['meta_field'], true );
				break;
			case 'post-title':
				$value = get_the_title( $post_id );
				break;
			case 'post-excerpt':
				$value = get_the_excerpt( $post_id );
				break;
			case 'post-featured-image':
				$value = array(
					'url' => get_the_post_thumbnail_url( $post_id, 'thumbnail' ),
					'id'  => get_post_thumbnail_id( $post_id ),
				);
				break;
			case 'pods-text':
				list( $pod_name, $pod_id, $meta_key ) = explode( ':', $data['settings']['key'] );

				$value = pods( $pod_name, $post_id )->field( $meta_key );
				break;
			default:
				$value = $settings[ $key ];
		}

		return $value;
	}

	/**
	 * Генерирует уникальный идентификатор карты.
	 *
	 * @return string
	 */
	private function get_map_id(): string {
		return str_replace( '.', '', uniqid( '', true ) );
	}
}

// eof.
