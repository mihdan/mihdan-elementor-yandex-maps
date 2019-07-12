<?php
/**
 * Plugin Name: Mihdan: Elementor Yandex Maps
 * Description: Elementor Yandex Maps Widget - Easily add multiple address pins onto the same map with support for different map types (Road Map/Satellite/Hybrid/Terrain) and custom map style. Freely edit info window content of your pins with the standard Elementor text editor. And many more custom map options.
 * Plugin URI:  https://github.com/mihdan/mihdan-elementor-yandex-maps
 * Version:     1.3
 * Author:      Mikhail Kobzarev
 * Author URI:  https://www.kobzarev.com/
 * Text Domain: mihdan-elementor-yandex-maps
 * GitHub Plugin URI: https://github.com/mihdan/mihdan-elementor-yandex-maps
 */

namespace MihdanElementorYandexMaps;

use \Elementor\Settings;
use \Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MIHDAN_YANDEX_MAPS_FILE', __FILE__ );

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.3
 */
class Core {
	/**
	 * Plugin Version
	 *
	 * @since 1.3
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.3';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.3
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.3
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.6';

	/**
	 * Instance
	 *
	 * @since 1.3
	 * @access private
	 * @static
	 *
	 * @var Core The single instance of the class.
	 */
	private static $_instance = null;
	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.3
	 * @access public
	 *
	 * @return Core An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.3
	 * @access public
	 */
	public function require_widgets() {
		require_once __DIR__ . '/widgets/yandex-maps-widget.php';
		Plugin::instance()->widgets_manager->register_widget_type( new Widget\Yandex_Maps() );
	}

	/**
	 * Load Plugin Textdomain.
	 */
	public function i18n() {
		load_plugin_textdomain( 'mihdan-elementor-yandex-maps' );
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.3
	 * @access public
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Hooks initialization.
	 */
	public function init_hooks() {

		add_action( 'plugins_loaded', [ $this, 'i18n' ] );

		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Если не задан API ключ для карт.
		if ( '' == get_option( 'elementor_mihdan_elementor_yandex_maps_key' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_api_key_filed' ] );
		}

		// Add Plugin actions
		add_action( 'elementor/init', [ $this, 'register_category' ] );
		add_action( 'elementor/admin/after_create_settings/elementor', [ $this, 'register_settings' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'frontend_styles' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'frontend_scripts' ] );
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'require_widgets' ] );
	}

	/**
	 * Init plugin.
	 */
	public function init() {
		add_action( 'plugins_loaded', [ $this, 'init_hooks' ] );
	}

	/**
	 * Enqueue scripts for editor.
	 */
	public function editor_scripts() {
		$api_key = get_option( 'elementor_mihdan_elementor_yandex_maps_key' );
		wp_enqueue_style( 'mihdan-elementor-yandex-maps-admin', plugins_url( '/frontend/css/mihdan-elementor-yandex-maps-admin.css', MIHDAN_YANDEX_MAPS_FILE ) );
		wp_enqueue_script( 'mihdan-elementor-yandex-maps-api-admin', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&source=admin&apikey=' . $api_key, [ 'jquery' ], self::VERSION, true );
		wp_localize_script( 'mihdan-elementor-yandex-maps-api-admin', 'EB_WP_URL', array( 'plugin_url' => plugin_dir_url( __FILE__ ) ) );
		wp_enqueue_script( 'mihdan-elementor-yandex-maps-admin', plugins_url( '/frontend/js/mihdan-elementor-yandex-maps-admin.js', MIHDAN_YANDEX_MAPS_FILE ), [ 'mihdan-elementor-yandex-maps-api-admin' ], self::VERSION, true );
	}

	/**
	 * Enqueue styles for frontend.
	 */
	public function frontend_styles() {
		wp_enqueue_style( 'mihdan-elementor-yandex-maps', plugins_url( '/frontend/css/mihdan-elementor-yandex-maps.css', MIHDAN_YANDEX_MAPS_FILE ) );
	}

	/**
	 * Enqueue scripts for frontend.
	 */
	public function frontend_scripts() {
		$api_key = get_option( 'elementor_mihdan_elementor_yandex_maps_key' );
		wp_register_script( 'mihdan-elementor-yandex-maps-api', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&source=frontend&apikey=' . $api_key, [ 'elementor-frontend' ], self::VERSION, true );
		wp_localize_script( 'mihdan-elementor-yandex-maps-api', 'EB_WP_URL', array( 'plugin_url' => plugin_dir_url( __FILE__ ) ) );
		wp_register_script( 'mihdan-elementor-yandex-maps', plugins_url( '/frontend/js/mihdan-elementor-yandex-maps.js', MIHDAN_YANDEX_MAPS_FILE ), [ 'elementor-frontend', 'mihdan-elementor-yandex-maps-api' ], self::VERSION, true );
	}

	/**
	 * Create new category for widget.
	 */
	public function register_category() {
		Plugin::$instance->elements_manager->add_category(
			'mihdan',
			[
				'title' => 'Mihdan Widgets',
				'icon'  => 'font',
			]
		);
	}

	/**
	 * Create Setting Tab
	 *
	 * @param Settings $settings
	 *
	 * @since 1.3
	 *
	 * @access public
	 */
	public function register_settings( Settings $settings ) {
		$settings->add_section(
			Settings::TAB_INTEGRATIONS,
			'mihdan-elementor-yandex-maps',
			[
				'label'    => __( 'Yandex Maps', 'mihdan-elementor-yandex-maps' ),
				'callback' => function() {
					echo __( '<p>Go to the <a href="https://developer.tech.yandex.ru/" target="_blank">Developer\'s Dashboard</a> and press “Get key”. In the popup window, select the “JavaScript API, Geocoding API” option.</p><p>After you select the service, the form appears. In this form, you need to provide your contact information. After you fill in the form, the “Service successfully connected” text appears. The created key is now available in the “Keys” section. Use it when you enable the API.</p>', 'mihdan-elementor-yandex-maps' );
				},
				'fields'   => [
					'mihdan_elementor_yandex_maps_key' => [
						'label'      => __( 'API Key', 'mihdan-elementor-yandex-maps' ),
						'field_args' => [
							'type' => 'text',
						],
					],
				],
			]
		);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.3
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'mihdan-elementor-yandex-maps' ),
			'<strong>' . esc_html__( 'Mihdan: Elementor Yandex Maps', 'mihdan-elementor-yandex-maps' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor Page Builder', 'mihdan-elementor-yandex-maps' ) . '</strong>',
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.3
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mihdan-elementor-yandex-maps' ),
			'<strong>' . esc_html__( 'Mihdan: Elementor Yandex Maps', 'mihdan-elementor-yandex-maps' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'mihdan-elementor-yandex-maps' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.3
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mihdan-elementor-yandex-maps' ),
			'<strong>' . esc_html__( 'Mihdan: Elementor Yandex Maps', 'mihdan-elementor-yandex-maps' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'mihdan-elementor-yandex-maps' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a api key.
	 *
	 * @since 1.3
	 *
	 * @access public
	 */
	function admin_notice_api_key_filed() {
		// translators: %s ссылка на страницу настроек
		$message = sprintf( __( 'To complete the work plugin must be Mihdan: Elementor Yandex Maps, you must specify the API key for Yandex.Maps. <a href="%s">Specified key</a>.', 'mihdan-elementor-yandex-maps' ), admin_url( 'admin.php?page=elementor#tab-' . Settings::TAB_INTEGRATIONS ) );

		echo '<div class="notice notice-warning is-dismissible"><p>' . $message . '</p></div>';
	}
}
// Instantiate Plugin Class
Core::instance();

// eof;
