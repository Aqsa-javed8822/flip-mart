<?php
namespace Aepro;

class Rules {

	public function get_post_type_conditions() {

		$conditions = [];
		$post_types = get_post_types( [ 'public' => true ], 'objects' );

		foreach ( $post_types as $name => $post_type ) {
			$exluded_post_types = [ 'ae_global_templates' ];
			//phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			if ( in_array( $name, $exluded_post_types ) ) {
				continue;
			}

			$conditions[ $name . '_index' ] = [];
		}
		return;
		//phpcs:ignore Squiz.PHP.NonExecutableCode.Unreachable
		echo '<pre>';
		//phpcs:ignore Squiz.PHP.NonExecutableCode.Unreachable
		print_r( $conditions );
		//phpcs:ignore Squiz.PHP.NonExecutableCode.Unreachable
		die();
	}
}
