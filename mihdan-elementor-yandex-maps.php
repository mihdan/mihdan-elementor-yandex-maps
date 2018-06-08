<?php
/**
 * Plugin Name: Mihdan: Elementor Yandex Maps
 * Description: Elementor Yandex Maps Widget - Easily add multiple address pins onto the same map with support for different map types (Road Map/Satellite/Hybrid/Terrain) and custom map style. Freely edit info window content of your pins with the standard Elementor text editor. And many more custom map options.
 * Plugin URI:  https://github.com/mihdan/mihdan-elementor-yandex-maps
 * Version:     1.1.7
 * Author:      Mikhail Kobzarev
 * Author URI:  https://www.kobzarev.com/
 * Text Domain: mihdan-elementor-yandex-maps
 * GitHub Plugin URI: https://github.com/mihdan/mihdan-elementor-yandex-maps
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EB_YANDEX_MAPS_FILE', __FILE__ );
define( 'EB_YANDEX_MAPS_VERSION', '1.1.7' );

require_once __DIR__ . '/elementor-helper.php';

/**
 * Загрузка плагина
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function mihdan_elementor_yandex_maps() {
	// Load localization file
	load_plugin_textdomain( 'mihdan-elementor-yandex-maps' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'mihdan_elementor_yandex_maps_fail_load' );
		return;
	}

	// Check required version
	$elementor_version_required = '1.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'mihdan_elementor_yandex_maps_load_out_of_date' );
		return;
	}

	add_action( 'elementor/widgets/widgets_registered', function () {
		require_once __DIR__ . '/widgets/yandex-maps-widget.php';
	} );
}
add_action( 'plugins_loaded', 'mihdan_elementor_yandex_maps' );

/**
 * Уведомление админу, что плагин зависит от Elementor
 */
function mihdan_elementor_yandex_maps_fail_load() {

	$message = '<p>' . __( 'You do not have Elementor Page Builder on your WordPress. Mihdan: Elementor Yandex Maps require Elementor in order to work.', 'mihdan-elementor-yandex-maps' ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

/**
 * Уведомление админу, что пора бы обновить Elementor
 */
function mihdan_elementor_yandex_maps_load_out_of_date() {

	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );

	// Текст сообщения
	$message  = '<p>' . __( 'Elementor Google Map Extended may not work or is not compatible because you are using an old version of Elementor.', 'mihdan-elementor-yandex-maps' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'mihdan-elementor-yandex-maps' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

/**
 * Get the value of a settings field
 *
 * @param string $option settings field name
 * @param string $section the section name this field belongs to
 * @param string $default default text if it's not found
 *
 * @return mixed
 */
function mihdan_elementor_yandex_maps_get_option( $option, $section, $default = '' ) {

	$options = get_option( $section );

	if ( isset( $options[ $option ] ) ) {
		return $options[ $option ];
	}

	return $default;
}

/**
 * Register and enqueue a custom stylesheet in the Elementor.
 */
add_action( 'elementor/editor/before_enqueue_scripts', function() {
	wp_enqueue_style( 'mihdan-elementor-yandex-maps-admin', plugins_url( '/assets/css/mihdan-elementor-yandex-maps-admin.css', EB_YANDEX_MAPS_FILE ) );
	wp_enqueue_script( 'mihdan-elementor-yandex-maps-api-admin', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&source=admin', [ 'jquery' ], EB_YANDEX_MAPS_VERSION, true );
	wp_localize_script( 'mihdan-elementor-yandex-maps-api-admin', 'EB_WP_URL', array( 'plugin_url' => plugin_dir_url( __FILE__ ) ) );
	wp_enqueue_script( 'mihdan-elementor-yandex-maps-admin', plugins_url( '/assets/js/mihdan-elementor-yandex-maps-admin.js', EB_YANDEX_MAPS_FILE ), [ 'mihdan-elementor-yandex-maps-api-admin' ], EB_YANDEX_MAPS_VERSION, true );
} );

add_action( 'elementor/frontend/after_enqueue_styles', function() {
	wp_enqueue_style( 'mihdan-elementor-yandex-maps', plugins_url( '/assets/css/mihdan-elementor-yandex-maps.css', EB_YANDEX_MAPS_FILE ) );
} );

add_action( 'elementor/frontend/after_register_scripts', function() {
	wp_register_script( 'mihdan-elementor-yandex-maps-api', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&source=frontend', [], EB_YANDEX_MAPS_VERSION, true );
	wp_localize_script( 'mihdan-elementor-yandex-maps-api', 'EB_WP_URL', array( 'plugin_url' => plugin_dir_url( __FILE__ ) ) );
	wp_register_script( 'mihdan-elementor-yandex-maps', plugins_url( '/assets/js/mihdan-elementor-yandex-maps.js', EB_YANDEX_MAPS_FILE ), [ 'mihdan-elementor-yandex-maps-api' ], EB_YANDEX_MAPS_VERSION, true );
} );

// eof;
