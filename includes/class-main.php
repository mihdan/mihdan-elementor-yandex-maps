<?php
namespace MihdanElementorYandexMaps;

use \Elementor\Settings;
use \Elementor\Plugin;

class Main {

	/**
	 * Instance
	 *
	 * @since 1.3
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * @var string $api_key Key for Yandex Maps API.
	 */
	private $api_key;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.3
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
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
	 * Init plugin.
	 */
	public function init() {
		add_action( 'plugins_loaded', [ $this, 'init_hooks' ] );
	}

	/**
	 * Hooks initialization.
	 */
	public function init_hooks() {

		// Получить ключ из базы.
		$this->api_key = get_option( 'elementor_mihdan_elementor_yandex_maps_key' );

		// Если не задан API ключ для карт.
		if ( '' == $this->api_key ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_invalid_api_key' ] );
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
	 * Enqueue scripts for editor.
	 */
	public function editor_scripts() {
		wp_enqueue_style( 'mihdan-elementor-yandex-maps-admin', plugins_url( '/frontend/css/mihdan-elementor-yandex-maps-admin.css', MIHDAN_ELEMENTOR_YANDEX_MAPS_FILE ) );
		wp_enqueue_script( 'mihdan-elementor-yandex-maps-api-admin', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&source=admin&apikey=' . $this->api_key, [ 'jquery' ], MIHDAN_ELEMENTOR_YANDEX_MAPS_VERSION, true );
		wp_localize_script( 'mihdan-elementor-yandex-maps-api-admin', 'EB_WP_URL', array( 'plugin_url' => plugin_dir_url( __FILE__ ) ) );
		wp_enqueue_script( 'mihdan-elementor-yandex-maps-admin', plugins_url( '/frontend/js/mihdan-elementor-yandex-maps-admin.js', MIHDAN_ELEMENTOR_YANDEX_MAPS_FILE ), [ 'mihdan-elementor-yandex-maps-api-admin' ], MIHDAN_ELEMENTOR_YANDEX_MAPS_VERSION, true );
	}

	/**
	 * Enqueue styles for frontend.
	 */
	public function frontend_styles() {
		wp_enqueue_style( 'mihdan-elementor-yandex-maps', plugins_url( '/frontend/css/mihdan-elementor-yandex-maps.css', MIHDAN_ELEMENTOR_YANDEX_MAPS_FILE ) );
	}

	/**
	 * Enqueue scripts for frontend.
	 */
	public function frontend_scripts() {
		wp_register_script( 'mihdan-elementor-yandex-maps-api', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&source=frontend&apikey=' . $this->api_key, [ 'elementor-frontend' ], MIHDAN_ELEMENTOR_YANDEX_MAPS_VERSION, true );
		wp_localize_script( 'mihdan-elementor-yandex-maps-api', 'EB_WP_URL', array( 'plugin_url' => plugin_dir_url( __FILE__ ) ) );
		wp_register_script( 'mihdan-elementor-yandex-maps', plugins_url( '/frontend/js/mihdan-elementor-yandex-maps.js', MIHDAN_ELEMENTOR_YANDEX_MAPS_FILE ), [ 'elementor-frontend', 'mihdan-elementor-yandex-maps-api' ], MIHDAN_ELEMENTOR_YANDEX_MAPS_VERSION, true );
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
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.3
	 * @access public
	 */
	public function require_widgets() {
		require_once MIHDAN_ELEMENTOR_YANDEX_MAPS_DIR . '/includes/class-widget.php';
		Plugin::instance()->widgets_manager->register_widget_type( new Widget\Widget() );
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
	function admin_notice_invalid_api_key() {
		// translators: %s ссылка на страницу настроек
		$message = sprintf( __( 'To make plugin Mihdan: Elementor Yandex Maps work properly, you should <a href="%s">specify the Yandex Maps API key</a>.', 'mihdan-elementor-yandex-maps' ), admin_url( 'admin.php?page=elementor#tab-' . Settings::TAB_INTEGRATIONS ) );

		echo '<div class="notice notice-warning is-dismissible"><p>' . $message . '</p></div>';
	}
}

Main::instance();

// eof;
