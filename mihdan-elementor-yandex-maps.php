<?php
/**
 * Plugin Name: Mihdan: Elementor Yandex Maps
 * Description: Elementor Yandex Maps Widget - Easily add multiple address pins onto the same map with support for different map types (Road Map/Satellite/Hybrid/Terrain) and custom map style. Freely edit info window content of your pins with the standard Elementor text editor. And many more custom map options.
 * Plugin URI:  https://github.com/mihdan/mihdan-elementor-yandex-maps
 * Version:     1.3.5
 * Author:      Mikhail Kobzarev
 * Author URI:  https://www.kobzarev.com/
 * Text Domain: mihdan-elementor-yandex-maps
 * GitHub Plugin URI: https://github.com/mihdan/mihdan-elementor-yandex-maps
 *
 * @package mihdan-elementor-yandex-maps
 */

namespace Mihdan\ElementorYandexMaps;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MIHDAN_ELEMENTOR_YANDEX_MAPS_FILE', __FILE__ );
define( 'MIHDAN_ELEMENTOR_YANDEX_MAPS_DIR', __DIR__ );
define( 'MIHDAN_ELEMENTOR_YANDEX_MAPS_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Plugin Version
 *
 * @since 1.3
 */
define( 'MIHDAN_ELEMENTOR_YANDEX_MAPS_VERSION', '1.3.5' );

static $plugin;

if ( ! isset( $plugin ) ) {
	require_once MIHDAN_ELEMENTOR_YANDEX_MAPS_DIR . '/vendor/autoload.php';

	$plugin = new Main();
}

// eof.
