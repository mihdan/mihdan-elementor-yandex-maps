<?php
/**
 * Утилиты
 *
 * @package mihdan-elementor-yandex-maps
 */

namespace Mihdan\ElementorYandexMaps;

class Utils {
	/**
	 * Get public post types.
	 *
	 * @param   array|null  $args  Arguments for get_post_types().
	 *
	 * @return array
	 */
	public static function get_public_post_types( ?array $args = [] ): array {
		$post_type_args = array(
			'show_in_nav_menus' => true,
		);

		$post_type_args = wp_parse_args( $post_type_args, $args );

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = [];

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		return $post_types;
	}

	/**
	 * Get public taxonomies.
	 *
	 * @param string $post_type Post type.
	 *
	 * @return array
	 */
	public static function get_taxonomies( string $post_type ): array {

		$results = [];

		$args = array(
			'public'      => true,
			'object_type' => [ $post_type ],
		);

		$taxonomies = get_taxonomies( $args, 'objects' );

		if ( ! $taxonomies ) {
			return $results;
		}

		foreach ( $taxonomies as $taxonomy => $object ) {
			$results[ $taxonomy ] = $object->label;
		}

		return $results;
	}

	/**
	 * Get public taxonomies.
	 *
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return array
	 */
	public static function get_terms( string $taxonomy ): array {

		$results = [];

		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'orderby'    => 'name',
			'fields'     => 'id=>name',
		);

		$terms = get_terms( $args );

		if ( is_wp_error( $terms ) ) {
			return $results;
		}

		return $terms;
	}

	/**
	 * Ищет ключи в массиве по регулярному выражению.
	 *
	 * @param   array   $data    Входящий массив.
	 * @param   string  $pattern Паттерн для поиска.
	 *
	 * @return array
	 */
	public static function array_keys_by_regex( array $data, string $pattern ): array {
		return array_intersect_key( $data, array_flip( preg_grep( $pattern, array_keys( $data ) ) ) );
	}
}
