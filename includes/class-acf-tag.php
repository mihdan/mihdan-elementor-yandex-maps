<?php
/**
 * ACF_Tag class.
 *
 * @package mihdan-elementor-yandex-maps
 */

namespace Mihdan\ElementorYandexMaps;

//use Elementor\Core\DynamicTags\Tag;
use ElementorPro\Modules\DynamicTags\Tags\Base\Tag;
use Elementor\Modules\DynamicTags\Module;

/**
 * Class ACF_Tag
 */
class ACF_Tag extends Tag {

	public function get_name() {
		return 'server-variable';
	}

	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}

	public function get_group() {
		return 'request-variables';
	}

	public function get_title() {
		return __( 'Test', 'mihdan-elementor-yandex-maps' );
	}

	/**
	 * Register controls.
	 */
	protected function _register_controls() {
		$this->add_control(
			'fields',
			array(
				'label' => __( 'Fields', 'mihdan-elementor-yandex-maps' ),
				'type'  => 'text',
			)
		);
	}

	public function render() {
		$fields = $this->get_settings( 'fields' );
		$sum = 0;
		$count = 0;
		$value = 0;

		// Make sure that ACF if installed and activated
		if ( ! function_exists( 'get_field' ) ) {
			echo 0;
			return;
		}

		foreach ( explode( ',', $fields ) as $index => $field_name ) {
			$field = get_field( $field_name );
			if ( (int) $field > 0 ) {
				$sum += (int) $field;
				$count++;
			}
		}

		if ( 0 !== $count ) {
			$value = $sum / $count;
		}

		echo $value;
	}
}
