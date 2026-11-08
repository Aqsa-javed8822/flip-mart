<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\TeamMember;

use DateTime;
use DateTimeZone;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Render\GeneralAddons;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Render class.
 *
 * @package RadiusTheme\SB
 */
class Render extends GeneralAddons {
	/**
	 * Main render function for displaying content.
	 *
	 * @param array $data     Data to be passed to the template.
	 * @param array $settings Widget settings.
	 *
	 * @return string
	 */
	public function display_content( $data, $settings ) {
		$data['content'] = '';
		$this->settings  = $settings;
		$data            = wp_parse_args( $this->get_template_args( $data ), $data );
		$data            = apply_filters( 'rtsb/general/team_member/args/' . $data['unique_name'], $data );
		if ( ! empty( $data['team_member_items'] ) ) {
			foreach ( $data['team_member_items'] as  $team ) {
				$data['team_member']        = $team;
				$data['member_designation'] = $team['member_designation'] ?? '';
				$data['member_name']        = $team['member_name'] ?? '';
				$data['member_bio']         = $team['member_bio'] ?? '';
				$data['image_link']         = $this->render_link_attributes( 'img_' . $team['_id'], $team['image_link'], 'member-image-link' );
				$data['member_name_link']   = $this->render_link_attributes( 'name_' . $team['_id'], $team['member_name_link'], 'member-name-link' );
				$data['grid_classes']       = $this->content_classes;
				$data['content']           .= Fns::load_template( $data['template'], $data, true );
			}

			return $this->addon_view( $data, $settings );
		} else {
			return '<p>' . esc_html__( 'Please add a team member.', 'shopbuilder' ) . '</p>';
		}
	}
	/**
	 * Retrieves template arguments based on widget settings.
	 *
	 * @param array $data Data to be passed to the template.
	 *
	 * @return array
	 */
	private function get_template_args( $data ) {
		if ( ! empty( $data['content_class'] ) ) {
			$this->content_classes[] = $data['content_class'];
		}

		if ( ! empty( $this->settings['activate_slider_item'] ) ) {
			$this->content_classes = array_merge(
				$this->content_classes,
				[
					'rtsb-team-member',
					'rtsb-slide-item',
					'swiper-slide',
					'animated',
					'rtFadeIn',
				]
			);
		}

		$this->content_classes = is_array( $this->content_classes ) ? implode( ' ', $this->content_classes ) : $this->content_classes;

		return [
			'team_member_items'      => $this->settings['team_member_items'] ?? [],
			'has_image_link'         => ! empty( $this->settings['image_linkable'] ),
			'has_member_name_link'   => ! empty( $this->settings['member_name_linkable'] ),
			'has_designation'        => ! empty( $this->settings['display_member_designation'] ),
			'has_description'        => ! empty( $this->settings['display_member_description'] ),
			'display_social_profile' => ! empty( $this->settings['display_social_icon'] ),
			'member_name_html_tag'   => $this->settings['member_name_html_tag'] ?? 'h2',
			'member_item_classes'    => $this->generate_team_member_item_class(),
			'instance'               => $this,
		];
	}
	/**
	 * Function to render social icon attributes.
	 *
	 * @param string $id Element ID.
	 *
	 * @return string
	 */
	public function render_social_icon_url_attributes( $id, $control_name ) {

		$this->add_attribute( $id, 'class', 'rtsb-social-link' );
		if ( ! empty( $control_name['url'] ) ) {
			$this->add_link_attributes( $id, $control_name );
		} else {
			$this->add_attribute( $id, 'role', 'button' );
		}
		return $this->get_attribute_string( $id );
	}
	/**
	 * Function to render member image.
	 *
	 * @param array $member_image_attr member image attributes.
	 *
	 * @return string
	 */
	public function render_member_image( $member_image_attr ) {
		$img_html = '';
		if ( ! empty( $member_image_attr['member_image'] ) ) {
			$c_image_size         = RenderHelpers::get_data( $this->settings, 'member_img_dimension', [] );
			$c_image_size['crop'] = RenderHelpers::get_data( $this->settings, 'member_img_crop', [] );
			$c_image_size         = ! empty( $c_image_size ) && is_array( $c_image_size ) ? $c_image_size : [];
			$image_id             = ! empty( $member_image_attr['member_image']['id'] ) ? $member_image_attr['member_image']['id'] : $member_image_attr['member_image'];
			$img_html            .= Fns::get_product_image_html(
				'',
				null,
				RenderHelpers::get_data( $this->settings, 'member_image_size', 'full' ),
				$image_id,
				$c_image_size
			);
		}

		return $img_html;
	}

	/**
	 * Function to render social profile icon.
	 *
	 * @param array $team_member team member field.
	 *
	 * @return void
	 */
	public function render_social_icon( $team_member ) {
		$item_id = $team_member['_id'];
		?>
		<div class="rtsb-team-social-area">
			<ul class="rtsb-social">
				<?php
				foreach ( ControlHelper::social_media_profile_list() as $key => $profile ) {
					if ( ! empty( $team_member[ $key . '_url' ]['url'] ) ) {
						?>
							<li class="rtsb-social-item">
								<a <?php Fns::print_html( $this->render_social_icon_url_attributes( $item_id . '_' . $key, $team_member[ $key . '_url' ] ) ); ?>>
								<?php Fns::print_html( $profile['icon'] ); ?>
								</a>
							</li>
						<?php
					}
				}
				?>
			</ul>
		</div>
		<?php
	}
	/**
	 * Function to generate item class.
	 *
	 * @return string
	 */
	public function generate_team_member_item_class() {
		$classes = 'rtsb-team-member-item ';
		if ( ! empty( $this->settings['image_hover_effect'] ) ) {
			$classes .= $this->settings['image_hover_effect'];
		}
		return $classes;
	}
}
