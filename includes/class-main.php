<?php
/**
 * Main class of the plugin.
 *
 * @package mihdan-elementor-yandex-maps
 */

namespace Mihdan\ElementorYandexMaps;

use Elementor\Settings;
use Elementor\Plugin;
use Elementor\Widgets_Manager;
use WPTRT\AdminNotices\Notices;

/**
 * Class Main
 */
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
	private static $instance = null;

	/**
	 * Yandex Map API key
	 *
	 * @var string $api_key Key for Yandex Maps API.
	 */
	private $api_key;

	/**
	 * Notices instance
	 *
	 * @var Notices $notify Экземпляр класса.
	 */
	private $notify;

	/**
	 * Requirements instance
	 *
	 * @var Requirements $requirements
	 */
	private $requirements;

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
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
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
		$this->notify       = new Notices();
		$this->requirements = new Requirements( $this->notify );

		// Получить ключ из базы.
		$this->api_key = self::get_api_key();

		if ( ! $this->requirements->are_requirements_met() ) {
			return;
		}

		// Если не задан API ключ для карт.
		if ( ! $this->api_key ) {
			$this->admin_notice_invalid_api_key();
		}

		$this->init_hooks();
	}

	/**
	 * Get API key from database.
	 *
	 * @return bool|mixed|void
	 */
	public static function get_api_key() {
		return get_option( 'elementor_mihdan_elementor_yandex_maps_key' );
	}

	/**
	 * Hooks initialization.
	 */
	public function init_hooks() {

		add_action( 'plugins_loaded', array( $this, 'i18n' ) );

		// Add Plugin actions.
		add_action( 'elementor/init', array( $this, 'register_category' ) );
		add_action( 'elementor/admin/after_create_settings/elementor', array( $this, 'register_settings' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'frontend_styles' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'elementor/widgets/register', array( $this, 'init_widget' ) );
		add_filter( 'wp_resource_hints', array( $this, 'resource_hints' ), 10, 2 );
		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 10, 2 );

		// Просьба оценить плагин.
		add_action( 'admin_init', array( $this, 'admin_notice_star' ) );
	}

	/**
	 * Load Plugin Textdomain.
	 */
	public function i18n() {
		load_plugin_textdomain( 'mihdan-elementor-yandex-maps' );
	}

	/**
	 * Add plugin action links
	 *
	 * @param array  $actions Default actions.
	 * @param string $plugin_file Plugin file.
	 *
	 * @return array
	 */
	public function add_settings_link( $actions, $plugin_file ) {
		if ( MIHDAN_ELEMENTOR_YANDEX_MAPS_BASE_NAME === $plugin_file ) {
			$actions[] = sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'admin.php?page=elementor-settings#tab-integrations' ),
				esc_html__( 'Settings', 'mihdan-elementor-yandex-maps' )
			);
		}

		return $actions;
	}

	/**
	 * Add resource hints like prefetch, preload, preconnect.
	 *
	 * @param array  $urls          URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed for, e.g. 'preconnect' or 'prerender'.
	 *
	 * @return array
	 */
	public function resource_hints( $urls, $relation_type ) {

		if ( 'dns-prefetch' === $relation_type ) {
			$urls[] = '//api-maps.yandex.ru';
		}

		if ( 'preconnect' === $relation_type ) {
			$urls[] = '//api-maps.yandex.ru';
		}

		return $urls;
	}

	/**
	 * Enqueue scripts for editor.
	 */
	public function editor_scripts() {
		wp_enqueue_style( 'mihdan-elementor-yandex-maps-admin', plugins_url( '/admin/css/mihdan-elementor-yandex-maps-admin.css', MIHDAN_ELEMENTOR_YANDEX_MAPS_FILE ), array(), MIHDAN_ELEMENTOR_YANDEX_MAPS_VERSION );
		wp_enqueue_script( 'mihdan-elementor-yandex-maps-api-admin', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&source=admin&apikey=' . $this->api_key, array( 'jquery' ), MIHDAN_ELEMENTOR_YANDEX_MAPS_VERSION, true );
		wp_enqueue_script(
			'mihdan-elementor-yandex-maps-admin',
			plugins_url( '/admin/js/mihdan-elementor-yandex-maps-admin.js', MIHDAN_ELEMENTOR_YANDEX_MAPS_FILE ),
			array( 'jquery' ),
			filemtime( MIHDAN_ELEMENTOR_YANDEX_MAPS_DIR . '/admin/js/mihdan-elementor-yandex-maps-admin.js' ),
			true
		);
	}

	/**
	 * Enqueue styles for frontend.
	 */
	public function frontend_styles() {
		wp_enqueue_style(
			'mihdan-elementor-yandex-maps',
			plugins_url( '/frontend/css/mihdan-elementor-yandex-maps.css', MIHDAN_ELEMENTOR_YANDEX_MAPS_FILE ),
			array(),
			filemtime( MIHDAN_ELEMENTOR_YANDEX_MAPS_DIR . '/frontend/css/mihdan-elementor-yandex-maps.css' )
		);
	}

	/**
	 * Enqueue scripts for frontend.
	 */
	public function frontend_scripts() {
		wp_register_script(
			'mihdan-elementor-yandex-maps',
			plugins_url( '/frontend/js/mihdan-elementor-yandex-maps.js', MIHDAN_ELEMENTOR_YANDEX_MAPS_FILE ),
			array( 'jquery' ),
			filemtime( MIHDAN_ELEMENTOR_YANDEX_MAPS_DIR . '/frontend/js/mihdan-elementor-yandex-maps.js' ),
			true
		);

		wp_localize_script(
			'mihdan-elementor-yandex-maps',
			'mihdan_elementor_yandex_maps_config',
			array(
				'plugin_url' => MIHDAN_ELEMENTOR_YANDEX_MAPS_URL,
				'api_key'    => $this->api_key,
			)
		);
	}

	/**
	 * Create new category for widget.
	 */
	public function register_category() {
		Plugin::$instance->elements_manager->add_category(
			'mihdan',
			array(
				'title' => 'Mihdan Widgets',
				'icon'  => 'font',
			)
		);
	}

	/**
	 * Create Setting Tab
	 *
	 * @param Settings $settings Elementor "Settings" page in WordPress Dashboard.
	 *
	 * @since 1.3
	 *
	 * @access public
	 */
	public function register_settings( Settings $settings ) {
		$settings->add_section(
			Settings::TAB_INTEGRATIONS,
			'mihdan-elementor-yandex-maps',
			array(
				'label'    => __( 'Yandex Maps', 'mihdan-elementor-yandex-maps' ),
				'callback' => function() {
					$message = __( '<p>Go to the <a href="https://developer.tech.yandex.ru/" target="_blank">Developer\'s Dashboard</a> and press “Get key”. In the popup window, select the “JavaScript API, Geocoding API” option.</p><p>After you select the service, the form appears. In this form, you need to provide your contact information. After you fill in the form, the “Service successfully connected” text appears. The created key is now available in the “Keys” section. Use it when you enable the API.</p>', 'mihdan-elementor-yandex-maps' );
					printf(
						'%s',
						wp_kses(
							$message,
							array(
								'p' => array(),
								'a' => array(
									'href'   => true,
									'target' => true,
								),
							)
						)
					);
				},
				'fields'   => array(
					'mihdan_elementor_yandex_maps_key' => array(
						'label'      => __( 'API Key', 'mihdan-elementor-yandex-maps' ),
						'field_args' => array(
							'type' => 'text',
						),
					),
				),
			)
		);
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.3
	 * @access public
	 *
	 * @param Widgets_Manager $widgets_manager Widgets_Manager instance.
	 */
	public function init_widget( Widgets_Manager $widgets_manager ) {
		$widgets_manager->register( new Widget() );
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
	public function admin_notice_invalid_api_key() {
		/* translators: link to settings page. */
		$message = sprintf( __( 'To make plugin Mihdan: Elementor Yandex Maps work properly, you should <a href="%s">specify the Yandex Maps API key</a>.', 'mihdan-elementor-yandex-maps' ), admin_url( 'admin.php?page=elementor#tab-' . Settings::TAB_INTEGRATIONS ) );

		$this->notify->add(
			'mihdan_elementor_yandex_maps_invalid_api_key',
			'',
			$message,
			array(
				'type' => 'error',
			)
		);

		$this->notify->boot();
	}

	/**
	 * Admin notice.
	 */
	public function admin_notice_star() {
		/**
		 * Current user.
		 *
		 * \WP_User $current_user
		 */
		global $current_user;

		$message = sprintf(
			/* translators: link to plugin rating. */
			__(
				'Enjoyed Elementor Yandex Maps? Please leave us a <a href="%s" target="_blank">★★★★★</a> rating. We really appreciate your support!',
				'mihdan-elementor-yandex-maps'
			),
			'https://wordpress.org/support/plugin/mihdan-elementor-yandex-maps/reviews/#new-post'
		);

		$this->notify->add(
			'mihdan_elementor_yandex_maps_star',
			/* translators: display name for current user */
			sprintf( __( 'Hi, %s', 'mihdan-elementor-yandex-maps' ), $current_user->get( 'display_name' ) ),
			$message
		);

		$this->notify->boot();
	}
}

// eof.
