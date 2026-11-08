<?php
/**
 * Main ElementorWidgetBase class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Abstracts;

use Elementor\Group_Control_Css_Filter;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use RadiusTheme\SB\Controllers\Hooks\FilterHooks;
use RadiusTheme\SB\Helpers\BuilderFns;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use RadiusTheme\SB\Elementor\Helper\ControlSelectors;
use RadiusTheme\SB\Helpers\Fns;


// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Abstract ElementorWidgetBase Class
 *
 * Implemented by classes for elementor addons development.
 *
 * @version  1.0.0
 * @package  RadiusTheme\SB
 */
abstract class ElementorWidgetBase extends Widget_Base {

	/**
	 * Widget Title.
	 *
	 * @var String
	 */
	public $rtsb_name;

	/**
	 * Widget name.
	 *
	 * @var String
	 */
	public $rtsb_base;

	/**
	 * Widget categories.
	 *
	 * @var String
	 */
	public $rtsb_category;

	/**
	 * Widget icon class
	 *
	 * @var String
	 */
	public $rtsb_icon;

	/**
	 * PRO Label HTML.
	 *
	 * @var String
	 */
	public $pro_label = '';

	/**
	 * PRO content tab.
	 *
	 * @var String
	 */
	public $pro_tab;

	/**
	 * Widget prefix.
	 *
	 * @var String
	 */
	public $el_prefix;

	/**
	 * Widget controls.
	 *
	 * @var array
	 */
	public $selectors = [];

	/**
	 * Class Constructor
	 *
	 * @param array $data default data.
	 * @param array $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->apply_hooks_before_render();
		parent::__construct( $data, $args );
		$this->rtsb_category = 'rtsb-shopbuilder';
		$this->rtsb_icon     = 'rtsb-el-custom rtsb-element icon-' . $this->rtsb_base;
		$this->el_prefix     = 'rtsb_el_';
		$this->pro_label     = sprintf(
			'<span class="rtsb-pro-label">%s</span>',
			esc_html__( 'Pro', 'shopbuilder' )
		);
		$this->selectors     = ControlSelectors::get_widget_selectors( $this );
	}


	/**
	 * Elementor controls marge all settings
	 *
	 * @return array
	 */
	abstract public function widget_fields();

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return $this->rtsb_base;
	}

	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'woocommerce', 'shopbuilder' ];
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return $this->rtsb_name;
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {
		return $this->rtsb_icon;
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories widget belongs to.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_categories() {
		return [ $this->rtsb_category ];
	}

	/**
	 * Elementor Promotional section controls.
	 *
	 * @return array
	 */
	public function promo_content() {
		if ( rtsb()->has_pro() || $this->widget_exceptions() ) {
			return [];
		}

		$promo_fields = [];

		$promo_fields['pro_alert'] = [
			'mode'  => 'section_start',
			'label' => sprintf(
				'<span style="color: #f54">%s</span>',
				esc_html__( 'Go Premium for More Features', 'shopbuilder' )
			),
		];

		if ( $this->pro_tab ) {
			$promo_fields['pro_alert']['tab'] = $this->pro_tab;
		}

		$promo_fields['get_pro'] = [
			'type'      => 'html',
			'raw'       => '<div class="elementor-nerd-box rtsb-promo"><div class="elementor-nerd-box-title" style="margin-top: 0; margin-bottom: 20px;">Unlock more possibilities</div><div class="elementor-nerd-box-message"><span class="pro-feature" style="font-size: 13px;"> Get the <a href="' . esc_url( rtsb()->pro_version_link() ) . '" target="_blank" style="color: #f54">Pro version</a> for more stunning layouts and customization options.</span></div><a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-button-go-pro" href="' . esc_url( rtsb()->pro_version_link() ) . '" target="_blank">Get Pro</a></div>',
			'separator' => 'default',
		];

		$promo_fields['pro_alert_end'] = [
			'mode' => 'section_end',
		];

		return $promo_fields;
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->theme_support( 'widget_controls' );
		$the_widget = $this->widget_fields();
		if ( ! is_array( $the_widget ) ) {
			return;
		}
		$fields = array_merge( $the_widget, $this->promo_content() );

		$fields = apply_filters( 'rtsb/elements/elementor/widgets/controls/' . $this->rtsb_base, $fields, $this );

		foreach ( $fields as $id => $field ) {
			$field['classes'] = ! empty( $field['classes'] ) ? $field['classes'] . ' elementor-control-rtsb_el' : ' elementor-control-rtsb_el';

			if ( ! empty( $field['type'] ) ) {
				$field['type'] = self::el_fields( $field['type'] );
			}

			if ( ! empty( $field['tab'] ) ) {
				$field['tab'] = self::el_tabs( $field['tab'] );
			}

			if ( isset( $field['mode'] ) && 'section_start' === $field['mode'] ) {
				unset( $field['mode'] );
				unset( $field['separator'] );

				$this->start_controls_section( $id, $field );
			} elseif ( isset( $field['mode'] ) && 'section_end' === $field['mode'] ) {
				$this->end_controls_section();
			} elseif ( isset( $field['mode'] ) && 'tabs_start' === $field['mode'] ) {
				unset( $field['mode'] );
				unset( $field['separator'] );

				$this->start_controls_tabs( $id );
			} elseif ( isset( $field['mode'] ) && 'tabs_end' === $field['mode'] ) {
				$this->end_controls_tabs();
			} elseif ( isset( $field['mode'] ) && 'tab_start' === $field['mode'] ) {
				unset( $field['mode'] );
				unset( $field['separator'] );

				$this->start_controls_tab( $id, $field );
			} elseif ( isset( $field['mode'] ) && 'tab_end' === $field['mode'] ) {
				$this->end_controls_tab();
			} elseif ( isset( $field['mode'] ) && 'group' === $field['mode'] ) {
				$type          = $field['type'];
				$field['name'] = $id;
				unset( $field['mode'] );
				unset( $field['type'] );
				$this->add_group_control( $type, $field );
			} elseif ( isset( $field['mode'] ) && 'responsive' === $field['mode'] ) {
				unset( $field['mode'] );

				$this->add_responsive_control( $id, $field );
			} elseif ( isset( $field['mode'] ) && 'popover_start' === $field['mode'] ) {
				unset( $field['mode'] );
				unset( $field['separator'] );

				$this->add_control( $id, $field );
				$this->start_popover();
			} elseif ( isset( $field['mode'] ) && 'popover_end' === $field['mode'] ) {
				$this->end_popover();
			} elseif ( isset( $field['mode'] ) && 'repeater' === $field['mode'] ) {
				$repeater        = new Repeater();
				$repeater_fields = $field['fields'];

				foreach ( $repeater_fields as $rf_id => $value ) {
					if ( ! empty( $value['type'] ) ) {
						$value['type'] = self::el_fields( $value['type'] );
					}
					if ( isset( $value['mode'] ) && 'responsive' === $value['mode'] ) {
						unset( $value['mode'] );
						$repeater->add_responsive_control( $rf_id, $value );
					} elseif ( isset( $value['mode'] ) && 'group' === $value['mode'] ) {
						$type          = $value['type'];
						$value['name'] = $rf_id;
						unset( $value['mode'] );
						unset( $value['type'] );
						$repeater->add_group_control( $type, $value );
					} else {
						$repeater->add_control( $rf_id, $value );
					}
				}

				$field['fields'] = $repeater->get_controls();

				$this->add_control( $id, $field );
			} else {
				$this->add_control( $id, $field );
			}
		}

		do_action( 'rtsb/after/register/controls' );
	}

	/**
	 * Elementor Edit mode.
	 *
	 * @return mixed
	 */
	public function is_edit_mode() {
		return Plugin::$instance->preview->is_preview_mode() || Plugin::$instance->editor->is_edit_mode();
	}

	/**
	 * Elementor Edit preview.
	 *
	 * @return mixed
	 */
	public function is_builder_mode() {
		 return BuilderFns::is_builder_preview() || $this->is_edit_mode();
	}

	/**
	 * Render a shared editor-only notice.
	 *
	 * Any widget can surface an editor placeholder (out of stock, no active
	 * campaign, empty result, etc.) with one consistent design. Renders only in
	 * edit/preview mode and prints the notice CSS inline once per request.
	 *
	 * The notice lives in the Elementor preview iframe, which loads only frontend
	 * styles — so the rule is printed here instead of the editor panel stylesheet,
	 * and kept out of the compiled frontend bundle.
	 *
	 * @param string $message Notice text (already translated).
	 * @param string $extra_class Optional extra class for per-widget targeting.
	 * @param string $title Optional hover tooltip; defaults to the editor-only hint.
	 * @return void
	 */
	public function editor_notice( $message, $extra_class = '', $title = '' ) {
		if ( ! $this->is_edit_mode() || '' === $message ) {
			return;
		}
		$this->editor_notice_style();
		$classes = trim( 'rtsb-editor-notice ' . $extra_class );
		$title   = '' !== $title ? $title : esc_html__( 'This notice is only visible in the Elementor editor.', 'shopbuilder' );
		?>
		<div class="<?php echo esc_attr( $classes ); ?>" title="<?php echo esc_attr( $title ); ?>">
			<span class="rtsb-editor-notice__label"><?php echo esc_html__( 'Editor Notice:', 'shopbuilder' ); ?></span>
			<?php echo esc_html( $message ); ?>
		</div>
		<?php
	}

	/**
	 * Print the shared editor-notice CSS inline, once per request.
	 *
	 * @return void
	 */
	protected function editor_notice_style() {
		static $printed = false;
		if ( $printed ) {
			return;
		}
		$printed = true;
		?>
		<style>
			.rtsb-editor-notice {
				position: relative;
				max-height: 500px;
				padding: 12px 16px 12px 44px;
				border: 1px solid #f0b849;
				border-left: 4px solid #f0b849;
				border-radius: 4px;
				background: #fff8e5;
				color: #8a6d3b;
				font-size: 13px;
				font-weight: 500;
				line-height: 1.5;
				box-shadow: 0 1px 4px rgba(138, 109, 59, 0.2);
				cursor: help;
				overflow: hidden;
				opacity: 1;
				transition: opacity 0.3s ease, max-height 0.3s ease, margin 0.3s ease, padding 0.3s ease, border-width 0.3s ease;
			}
			.rtsb-editor-notice::before {
				content: "\26A0";
				position: absolute;
				top: 50%;
				left: 16px;
				transform: translateY(-50%);
				font-size: 18px;
				line-height: 1;
			}
			.rtsb-editor-notice__label {
				font-weight: 700;
				margin-right: 4px;
			}
			/* Fade + collapse the notice when the editor panel is hidden (preview toggle). */
			.elementor-editor-preview .rtsb-editor-notice {
				max-height: 0;
				margin-top: 0;
				padding-top: 0;
				padding-bottom: 0;
				border-top-width: 0;
				border-bottom-width: 0;
				opacity: 0;
			}
		</style>
		<?php
	}

	/**
	 * Elementor Edit mode need some extra js for isotop reinitialize
	 *
	 * @return void
	 */
	public function editor_slider_script() {
		if ( ! empty( \Elementor\Plugin::$instance->editor ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$selector = $this->get_unique_selector() . ' .rtsb-carousel-slider';
			?>
			<script>
				jQuery('<?php echo esc_attr( $selector ); ?>').rtsb_slider();
			</script>
			<?php
		}
	}

	/**
	 * Elementor Edit mode need some extra js for isotop reinitialize
	 *
	 * @return void
	 */
	public function editor_cart_icon_script() {
		if ( $this->is_edit_mode() ) {
			$selector = $this->get_unique_selector() . ' .has-cart-button';
			?>
			<script type="text/javascript">
				// Product Archive Catelog
				(function ($) {
					setTimeout( function (){
						var cartWrpper = $('body').find('<?php echo esc_attr( $selector ); ?>');
						var cartIcon = cartWrpper.attr('data-cart-icon');
						var cartButton = cartWrpper.find(
							'.product_type_grouped, .add_to_cart_button, .single_add_to_cart_button '
						);
						if (cartIcon) {
							cartButton.find('i').remove();
							cartButton.find('svg').remove();
							cartButton.find('img').remove();
							cartButton.prepend(cartIcon);
						}
					}, 2000);
				})(jQuery);
			</script>
			<?php
		}
	}

	/**
	 * Elementor Edit mode script
	 *
	 * @return void
	 */
	public function edit_mode_script() {
		if ( ! $this->is_edit_mode() ) {
			return;
		}
		$ajaxurl = admin_url( 'admin-ajax.php' );

		// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		if ( in_array( 'sitepress-multilingual-cms/sitepress.php', get_option( 'active_plugins' ) ) ) {
			$ajaxurl = admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
		}
		?>
		<script>
			var rtsb = {
				ajaxurl: '<?php echo esc_url( $ajaxurl ); ?>',
				nonce: '<?php echo esc_attr( wp_create_nonce( rtsb()->nonceId ) ); ?>',
				is_pro: '<?php echo esc_attr( rtsb()->has_pro() ? 'true' : 'false' ); ?>',
				isOptimizationEnabled: '<?php echo esc_attr( Fns::is_optimization_enabled() ); ?>'
			};

			if (!rtsb.isOptimizationEnabled) {
				rtsbElFrontend();

				setTimeout(function() {
					rtsbProductPageInit();
				}, 1000);
			} else {
				if (typeof elementorFrontend !== 'undefined') {
					window.waitForRTSB((RTSB) => {
						RTSB.modules.get('elementorInit')?.refresh();
						RTSB.modules.get('singleProductTabs')?.refresh();
						RTSB.modules.get('reviewFormStar')?.refresh();
						RTSB.modules.get('addCartIcon')?.refresh();
						RTSB.modules.get('productImage')?.refresh();
						RTSB.modules.get('CartQuantityHandler')?.refresh();
					});
				}
			}

			<?php if ( rtsb()->has_pro() ) { ?>
				var isMasonry = jQuery('.rtsb-elementor-container .rtsb-masonry');

				if(isMasonry.length > 0) {
					isMasonry.masonry();
				}

				if (!'<?php echo esc_attr( Fns::is_optimization_enabled() ); ?>') {
					<?php if ( 'rtsb-ajax-product-filters' === $this->rtsb_base ) { ?>
						if (typeof rtsbFilters === 'function') {
							rtsbFilters();
						}
					<?php } ?>

					setTimeout( function (){
						window.rtsbCountdownApply();
					}, 1000 );
				} else {
					if (typeof elementorFrontend !== 'undefined') {
						window.waitForRTSB((RTSB) => {
							RTSB.modules.get('countdownPro')?.refresh();
							RTSB.modules.get('elementorInitPro')?.refresh();
							<?php if ( 'rtsb-ajax-product-filters' === $this->rtsb_base ) { ?>
								RTSB.modules.get('ajaxProductFiltersPro')?.refresh();
							<?php } ?>
						});
					}
				}
			<?php } ?>
		</script>

		<?php
	}

	/**
	 * Starts an Elementor Section
	 *
	 * @param string $label Section label.
	 * @param object $tab Tab ID.
	 * @param array  $conditions Section Condition.
	 * @param array  $condition Section Conditions.
	 * @return array
	 */
	public function start_section( $label, $tab, $conditions = [], $condition = [] ) {
		$start = [
			'mode'  => 'section_start',
			'tab'   => $tab,
			'label' => $label,
		];
		if ( ! empty( $condition ) ) {
			$start['condition'] = $condition;
		}
		if ( ! empty( $conditions ) ) {
			$start['conditions'] = $conditions;
		}
		return $start;
	}

	/**
	 * Ends an Elementor Section
	 *
	 * @return array
	 */
	public function end_section() {
		 return [
			 'mode' => 'section_end',
		 ];
	}

	/**
	 * Theme Support for render/widget_controls
	 *
	 * @param string $support Support for render/widget_controls.
	 * @return void
	 */
	public function theme_support( $support = 'render' ) {

		if ( empty( $support ) ) {
			return;
		}
		$theme = ucwords( str_replace( [ '_', '-', ' ' ], '', rtsb()->current_theme ) );

		$supportClass = apply_filters(
			'radiustheme/sb/widgetsupport/class',
			[
				'ShopbuilderPlugin' => 'RadiusTheme\SB\Controllers\ThemesSupport\\' . $theme . '\\WidgetsSupport',
				'TheTheme'          => \WidgetSupport::class,
			]
		);

		foreach ( $supportClass as $key => $theClass ) {
			$themeWidgetSupport = get_theme_file_path( '/shopbuilder/WidgetSupport.php' );
			if ( 'TheTheme' === $key && file_exists( $themeWidgetSupport ) ) {
				require_once $themeWidgetSupport;
			}
			if ( class_exists( $theClass ) ) {
				$method = $support . '_' . str_replace( [ '-', ' ' ], '_', $this->rtsb_base );
				if ( method_exists( $theClass, $method ) ) {
					$theClass::instance( $this )->$method();
				}
			}
		}
	}

	/**
	 * Starts an Elementor tab group.
	 *
	 * @param array $conditions Tab condition.
	 * @param array $condition Tab condition.
	 * @return array
	 */
	public function start_tab_group( $conditions = [], $condition = [] ) {
		return [
			'mode'       => 'tabs_start',
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}

	/**
	 * Ends an Elementor tab group.
	 *
	 * @param array $conditions Tab condition.
	 * @param array $condition Tab condition.
	 * @return array
	 */
	public function end_tab_group( $conditions = [], $condition = [] ) {
		return [
			'mode'       => 'tabs_end',
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}

	/**
	 * Starts an Elementor tab
	 *
	 * @param string $label Section label.
	 * @param array  $conditions Tab condition.
	 * @param array  $condition Tab condition.
	 * @return array
	 */
	public function start_tab( $label, $conditions = [], $condition = [] ) {
		return [
			'mode'       => 'tab_start',
			'label'      => $label,
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}

	/**
	 * Ends an Elementor tab.
	 *
	 * @param array $conditions Tab condition.
	 * @param array $condition Tab condition.
	 * @return array
	 */
	public function end_tab( $conditions = [], $condition = [] ) {
		return [
			'mode'       => 'tab_end',
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}

	/**
	 * Starts an Elementor Section Heading
	 *
	 * @param string $label Heading label.
	 * @param string $separator Section separator.
	 * @param array  $conditions Section Condition.
	 * @param array  $condition Section Conditions.
	 * @return array
	 */
	public function el_heading( $label, $separator = null, $conditions = [], $condition = [] ) {
		return [
			'type'            => 'html',
			'raw'             => sprintf(
				'<h3 class="rtsb-elementor-group-heading">%s</h3>',
				$label
			),
			'separator'       => $separator,
			'content_classes' => 'elementor-panel-heading-title',
			'conditions'      => $conditions,
			'condition'       => $condition,
		];
	}

	/**
	 * Prints a control notification.
	 *
	 * @param string $label Field label.
	 * @return string
	 */
	public function is_pro_control( $label ) {
		if ( rtsb()->has_pro() ) {
			return $label;
		}

		return $label . $this->pro_label;
	}

	/**
	 * Adds a class.
	 *
	 * @return string
	 */
	public function pro_class() {
		return ! rtsb()->has_pro() ? 'rtsb-pro-field' : '';
	}

	/**
	 * Elementor Fields.
	 *
	 * @param string $type Control type.
	 *
	 * @return string
	 */
	private static function el_fields( $type ) {

		$controls = Controls_Manager::class;

		switch ( $type ) {
			case 'link':
				$type = $controls::URL;
				break;

			case 'image-dimensions':
				$type = $controls::IMAGE_DIMENSIONS;
				break;

			case 'html':
				$type = $controls::RAW_HTML;
				break;

			case 'switch':
				$type = $controls::SWITCHER;
				break;

			case 'popover':
				$type = $controls::POPOVER_TOGGLE;
				break;

			case 'rtsb-image-selector':
				$type = \RadiusTheme\SB\Elementor\Controls\ImageSelectorControl::$controlName;
				break;

			case 'rt-select2':
				$type = \RadiusTheme\SB\Elementor\Controls\Select2AjaxControl::$controlName;
				break;

			case 'typography':
				$type = Group_Control_Typography::get_type();
				break;

			case 'border':
				$type = Group_Control_Border::get_type();
				break;

			case 'background':
				$type = Group_Control_Background::get_type();
				break;

			case 'box-shadow':
				$type = Group_Control_Box_Shadow::get_type();
				break;

			case 'text-shadow':
				$type = Group_Control_Text_Shadow::get_type();
				break;

			case 'text-stroke':
				$type = Group_Control_Text_Stroke::get_type();
				break;

			case 'css-filter':
				$type = Group_Control_Css_Filter::get_type();
				break;
			default:
				$type = constant( 'Elementor\Controls_Manager::' . strtoupper( $type ) );

		}
		return $type;
	}

	/**
	 * Elementor Fields.
	 *
	 * @param string $tab Tab.
	 *
	 * @return string
	 */
	private static function el_tabs( $tab ) {
		return constant( 'Elementor\Controls_Manager::TAB_' . strtoupper( $tab ) );
	}


	/**
	 * Init render hooks & functions.
	 *
	 * @return void
	 */
	protected function render_start() {
		add_filter( 'rtsb/countdown/campaign/parent/class', [ $this, 'countdown_campaign_parent_class' ], 15 );
		do_action( 'rtsb/custom/products/before/template/load', $this );
		$this->theme_support();

		if ( $this->is_builder_mode() ) {
			add_filter( 'wp_kses_allowed_html', [ FilterHooks::class, 'custom_wpkses_post_tags' ], 10, 2 );
		}
	}
	/**
	 * Archive Custom Layout Widget Rendering.
	 *
	 * @param array $classes class list.
	 *
	 * @return array
	 */
	public function countdown_campaign_parent_class( $classes ) {
		$settings         = $this->get_settings_for_display();
		$layout           = $settings['countdown_layout'] ?? 'horizontal';
		$v_position       = $settings['countdown_vertical_position'] ?? '';
		$countdown_preset = $settings['countdown_preset'] ?? '';
		$vertical_center  = $settings['countdown_vertical_center'] ?? false;

		$remove     = [
			'rtsb-countdown-horizontal',
			'rtsb-countdown-vertical',
			'rtsb-vertical-position-top-left',
			'rtsb-vertical-position-top-right',
		];
		$classes    = array_filter(
			$classes,
			function ( $item ) use ( $remove ) {
				return ! in_array( $item, $remove ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			}
		);
		$new_class  = 'rtsb-countdown-' . esc_attr( $countdown_preset );
		$new_class .= ' rtsb-countdown-' . esc_attr( $layout );

		if ( $v_position && 'horizontal' !== $layout ) {
			$vertical_class = 'rtsb-vertical-position-top-' . esc_attr( $v_position );
			$classes[]      = $vertical_class;
			if ( 'yes' === $vertical_center ) {
				$classes[] = 'rtsb-countdown-vertical-middle';
			}
		}

		$classes[] = $new_class;

		return $classes;
	}
	/**
	 * End render hooks & functions.
	 *
	 * @return void
	 */
	protected function render_end() {
		$this->edit_mode_script();
		do_action( 'rtsb/custom/products/after/template/load', $this );
		$this->theme_support( 'render_reset' );
		remove_filter( 'rtsb/countdown/campaign/parent/class', [ $this, 'countdown_campaign_parent_class' ], 15 );
	}

	/**
	 * Elementor controls marge all settings
	 *
	 * @return void
	 */
	protected function apply_hooks_before_render() {
		if ( $this->is_edit_mode() ) {
			add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );
		}
	}

	/**
	 * If checkout registration is disabled and not logged in,
	 * the user cannot check out.
	 *
	 * @return bool
	 */
	protected function has_checkout_restriction() {
		$checkout = WC()->checkout();

		return ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in();
	}

	/**
	 * Widget Exceptions.
	 *
	 * @return bool
	 */
	private function widget_exceptions() {
		$exceptions = [
			'rtsb-product-filters',
			'rtsb-wishlist',
			'rtsb-product-breadcrumbs',
		];

		return in_array( $this->rtsb_base, $exceptions, true );
	}
}
