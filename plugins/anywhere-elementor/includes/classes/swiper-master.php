<?php

namespace Aepro\Classes;

class SwiperMaster {

	protected $data;

	/**
	 * SwiperMaster constructor.
	 */
	public function __construct( $data ) {
		$this->data = $data;
	}

	public function open_wrapper() {
		?>

		<div class="ae-swiper-outer-wrapper" data-swiper="<?php wp_json_encode( $this->data ); ?>">
			<div class="ae-swiper-container swiper-container">

		<?php
	}

	public function close_wrapper() {
		?>
			</div>
		</div>
		<?php
	}

	public function open_slide_wrapper( $tag = 'div', $classes = '' ) {

		echo esc_html( '<' . $tag . ' class="ae-swiper-slide swiper-slide' . $classes . '">' );
	}

	public function close_slide_wrapper( $tag ) {
		echo esc_html( '</' . $tag . '>' );
	}

	public function pagination() {
		?>
		<div class="ae-swiper-pagination swiper-pagination"></div>
		<?php
	}

	public function scrollbar() {
		?>
		<div class="ae-swiper-scrollbar swiper-scrollbar"></div>
		<?php
	}

	public function get_defaults() {
	}
}
