<?php
namespace Mihdan\ElementorYandexMaps;
use WPTRT\AdminNotices\Notices;

class Requirements {
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
	const MINIMUM_PHP_VERSION = '5.6.20';

	/**
	 * @var Notices $notify
	 */
	private $notify;

	/**
	 * Requirements constructor.
	 *
	 * @param Notices $notify
	 */
	public function __construct( Notices $notify ) {
		$this->notify = $notify;
	}

	/**
	 * @return bool
	 */
	public function are_requirements_met() {

		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor */
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'mihdan-elementor-yandex-maps' ),
				'<strong>' . esc_html__( 'Mihdan: Elementor Yandex Maps', 'mihdan-elementor-yandex-maps' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'mihdan-elementor-yandex-maps' ) . '</strong>'
			);

			$this->notify->add(
				'mihdan_elementor_yandex_maps_missing_main_plugin',
				'',
				$message,
				array(
					'type' => 'error',
				)
			);

			$this->notify->boot();

			return false;
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mihdan-elementor-yandex-maps' ),
				'<strong>' . esc_html__( 'Mihdan: Elementor Yandex Maps', 'mihdan-elementor-yandex-maps' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'mihdan-elementor-yandex-maps' ) . '</strong>',
				self::MINIMUM_ELEMENTOR_VERSION
			);

			$this->notify->add(
				'mihdan_elementor_yandex_maps_minimum_elementor_version',
				'',
				$message,
				array(
					'type' => 'error',
				)
			);

			$this->notify->boot();

			return false;
		}

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			$message = sprintf(
				/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mihdan-elementor-yandex-maps' ),
				'<strong>' . esc_html__( 'Mihdan: Elementor Yandex Maps', 'mihdan-elementor-yandex-maps' ) . '</strong>',
				'<strong>' . esc_html__( 'PHP', 'mihdan-elementor-yandex-maps' ) . '</strong>',
				self::MINIMUM_PHP_VERSION
			);

			$this->notify->add(
				'mihdan_elementor_yandex_maps_minimum_php_version',
				'',
				$message,
				array(
					'type' => 'error',
				)
			);

			$this->notify->boot();

			return false;
		}

		return true;
	}
}
