<?php

namespace RadiusTheme\SB\Helpers;

use RadiusTheme\SB\Traits\SingletonTrait;

class ElementorDataMap {
	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * @var array
	 */
	private static $cache = [];

	/***
	 * Elementor Data key
	 */
	private const ELEMENTOR_DATA_KEY = '_elementor_data';
	/***
	 * @var array
	 */
	private array $eldata = [];

	/***
	 * @param int $post_id
	 *
	 * @return array|null
	 */
	public function get_elementor_data( int $post_id ): ?array {
		$data = get_post_meta( $post_id, self::ELEMENTOR_DATA_KEY, true );

		return json_decode( $data, true );
	}

	/***
	 * @param string $widget_name
	 * @param array|null $data
	 * @param int|null $post_id
	 *
	 * @return array
	 */
	public function get_widget_data( string $widget_name, ?array $data = null, ?int $post_id = null ): array {
		$cache_key = 'get_widget_data_' . $widget_name . '_' . $post_id;
		if ( isset( self::$cache[ $cache_key ] ) ) {
			return self::$cache[ $cache_key ];
		}
		if ( ! $data && $post_id ) {
			$data = $this->get_elementor_data( $post_id );
		}

		if ( empty( $data ) || ! is_array( $data ) ) {
			return [];
		}

		$this->search_el( $data, $widget_name );
		self::$cache[ $cache_key ] = $this->eldata;
		return $this->eldata;
	}

	/***
	 * @param array $data
	 * @param string $name
	 *
	 * @return void
	 */
	private function search_el( array $data, string $name ): void {
		foreach ( $data as $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}

			if ( ! empty( $value['elements'] ) && is_array( $value['elements'] ) ) {
				$this->search_el( $value['elements'], $name );
			} else {
				if ( 'widget' === $value['elType'] && $value['widgetType'] === $name ) {
					$this->eldata[] = $value;
				}
			}
		}
	}
}
