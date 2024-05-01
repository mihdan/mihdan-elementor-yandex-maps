<?php
/**
 * Plugin Name: Yandex Maps for Elementor
 * Description: Easily add multiple address pins onto the same map with support for different map types (Road Map/Satellite/Hybrid/Terrain) and custom map style. Freely edit info window content of your pins with the standard Elementor text editor. And many more custom map options.
 * Plugin URI:  https://wordpress.org/plugins/mihdan-elementor-yandex-maps/
 * Version:     1.6.11
 * Author:      Mikhail Kobzarev
 * Author URI:  https://www.kobzarev.com/
 * Text Domain: mihdan-elementor-yandex-maps
 * GitHub Plugin URI: https://github.com/mihdan/mihdan-elementor-yandex-maps
 * Elementor tested up to: 3.21.4
 * Elementor Pro tested up to: 3.21.2
 * Requires Plugins: elementor
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Donate link: https://www.kobzarev.com/donate/
 *
 * @package mihdan-elementor-yandex-maps
 * @link https://elementor.com/pro/changelog/
 * @link https://elementor-addon-components.com/how-to-integrate-the-new-breakpoints-with-elementor/
 */

namespace Mihdan\ElementorYandexMaps;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MIHDAN_ELEMENTOR_YANDEX_MAPS_FILE', __FILE__ );
define( 'MIHDAN_ELEMENTOR_YANDEX_MAPS_DIR', __DIR__ );
define( 'MIHDAN_ELEMENTOR_YANDEX_MAPS_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'MIHDAN_ELEMENTOR_YANDEX_MAPS_BASE_NAME', plugin_basename( __FILE__ ) );

/**
 * Plugin Version
 *
 * @since 1.3
 */
define( 'MIHDAN_ELEMENTOR_YANDEX_MAPS_VERSION', '1.6.11' );

static $mihdan_elementor_yandex_maps;

if ( ! isset( $mihdan_elementor_yandex_maps ) ) {
	require_once MIHDAN_ELEMENTOR_YANDEX_MAPS_DIR . '/vendor/autoload.php';

	$mihdan_elementor_yandex_maps = new Main();
}
