jQuery( function ( $ ) {
	if ( typeof aeTemplateCreateModal === 'undefined' ) {
		return;
	}

	var config = aeTemplateCreateModal;
	var $body = $( 'body' );
	var $modal = $( '#ae-template-create-modal' );
	var $modalDialog = $modal.find( '.ae-template-create-modal__dialog' );

	var $rulePostType = $( '#ae-modal-rule-post-type' );
	var $previewPost = $( '#ae-modal-preview-post' );
	var $ruleTaxonomy = $( '#ae-modal-rule-taxonomy' );
	var $previewTerm = $( '#ae-modal-preview-term' );
	var $usageArea = $( '#ae-modal-usage-area' );
	var $customUsageArea = $( '#ae-modal-custom-usage-area' );
	var $hookPostsSelected = $( '#ae-modal-hook-posts-selected' );
	var $hookPostsExcluded = $( '#ae-modal-hook-posts-excluded' );
	var $hookTermsSelected = $( '#ae-modal-hook-terms-selected' );
	var $hookTermsExcluded = $( '#ae-modal-hook-terms-excluded' );
	var $repeaterLoc = $( '#ae-modal-repeater-loc' );
	var $acfFieldType = $( '#ae-modal-acf-field-type' );
	var $acfRepeater = $( '#ae-modal-acf-repeater-name' );
	var $acfFlexible = $( '#ae-modal-acf-flexible-name' );
	var $previewPostDummyLink = $( '#ae-modal-preview-post-dummy-link' );
	var $previewTermDummyLink = $( '#ae-modal-preview-term-dummy-link' );
	var $tooltip = $( '#ae-template-create-modal-tooltip' );
	var $tooltipText = $tooltip.find( '.ae-template-create-modal__tooltip-text' );
	var $tooltipLink = $tooltip.find( '.ae-template-create-modal__tooltip-link' );

	var currentType = '';
	var select2Method = null;
	var tooltipHideTimer = null;
	var stepAnimationTimer = null;

	function clearTooltipHideTimer() {
		if ( tooltipHideTimer ) {
			window.clearTimeout( tooltipHideTimer );
			tooltipHideTimer = null;
		}
	}

	function hideTooltip() {
		clearTooltipHideTimer();
		if ( ! $tooltip.length ) {
			return;
		}
		$tooltip.attr( 'hidden', true ).removeClass( 'is-below is-cta' ).removeData( 'trigger' );
	}

	function scheduleTooltipHide() {
		clearTooltipHideTimer();
		tooltipHideTimer = window.setTimeout( function () {
			hideTooltip();
		}, 120 );
	}

	function positionTooltip( $trigger ) {
		if ( ! $tooltip.length || ! $trigger.length ) {
			return;
		}

		var triggerRect = $trigger.get( 0 ).getBoundingClientRect();
		var tooltipRect = $tooltip.get( 0 ).getBoundingClientRect();
		var gap = 12;
		var viewportPadding = 12;
		var left = triggerRect.left + ( triggerRect.width / 2 ) - ( tooltipRect.width / 2 );
		var maxLeft = window.innerWidth - tooltipRect.width - viewportPadding;
		var arrowLeft;
		var top = triggerRect.top - tooltipRect.height - gap;
		var placeBelow = false;

		if ( top < viewportPadding ) {
			top = triggerRect.bottom + gap;
			placeBelow = true;
		}

		left = Math.max( viewportPadding, Math.min( left, Math.max( viewportPadding, maxLeft ) ) );
		arrowLeft = triggerRect.left + ( triggerRect.width / 2 ) - left;

		$tooltip.toggleClass( 'is-below', placeBelow );
		$tooltip.css( {
			top: top + 'px',
			left: left + 'px',
			'--ae-tooltip-arrow-left': arrowLeft + 'px'
		} );
	}

	function showTooltip( $trigger, options ) {
		if ( ! $tooltip.length || ! $trigger.length ) {
			return;
		}

		var tooltipOptions = options || {};
		var message = tooltipOptions.message || $trigger.data( 'tooltip' ) || '';
		var link = tooltipOptions.link || $trigger.data( 'tooltip-link' ) || '';
		var linkLabel = tooltipOptions.linkLabel || $trigger.data( 'tooltip-link-label' ) || 'Learn More';
		var asCta = !! tooltipOptions.asCta;

		if ( ! message ) {
			hideTooltip();
			return;
		}

		clearTooltipHideTimer();
		$tooltipText.text( message );

		if ( link ) {
			$tooltipLink.attr( 'href', link ).text( linkLabel ).removeAttr( 'hidden' );
		} else {
			$tooltipLink.attr( 'href', '#' ).text( '' ).attr( 'hidden', true );
		}

		$tooltip.toggleClass( 'is-cta', asCta );
		$tooltip.attr( 'hidden', false ).data( 'trigger', $trigger );
		positionTooltip( $trigger );
	}

	function showLockedTemplateTooltip( $trigger ) {
		var $anchor = $trigger;
		if ( ! $anchor.hasClass( 'ae-template-create-modal__pro-badge' ) ) {
			var $badge = $trigger.find( '.ae-template-create-modal__pro-badge' ).first();
			if ( $badge.length ) {
				$anchor = $badge;
			}
		}

		var message = ( config.labels && config.labels.pro_only_feature ) ? config.labels.pro_only_feature : 'Pro-only feature';
		var linkLabel = ( config.labels && config.labels.upgrade ) ? config.labels.upgrade : 'Upgrade';
		showTooltip(
			$anchor,
			{
				message: message,
				link: config.upgrade_url || '',
				linkLabel: linkLabel,
				asCta: true
			}
		);
	}

	function detectSelect2Method() {
		if ( $.fn.aeselect2 ) {
			return 'aeselect2';
		}
		if ( $.fn.select2 ) {
			return 'select2';
		}
		return null;
	}

	function destroySelect2IfApplied( $element ) {
		if ( ! select2Method ) {
			return;
		}
		if ( $element.hasClass( 'select2-hidden-accessible' ) ) {
			$element[ select2Method ]( 'destroy' );
		}
	}

	function resetSelectOptions( $element, placeholder ) {
		$element.find( 'option' ).remove();
		$element.append( $( '<option />', { value: '', text: placeholder } ) );
		$element.val( '' ).trigger( 'change' );
	}

	function initAjaxSelect( $element, action, placeholder, extraDataFn ) {
		if ( ! select2Method ) {
			return;
		}

		destroySelect2IfApplied( $element );

		$element[ select2Method ]( {
			ajax: {
				url: config.ajax_url,
				dataType: 'json',
				delay: 200,
				data: function ( params ) {
					var data = {
						action: action,
						q: params.term || '',
						nonce: config.nonce
					};

					if ( typeof extraDataFn === 'function' ) {
						$.extend( data, extraDataFn() );
					}

					return data;
				},
				processResults: function ( response ) {
					return {
						results: ( response && response.data ) ? response.data : []
					};
				}
			},
			placeholder: placeholder,
			minimumInputLength: 1,
			width: '100%',
			dropdownParent: $modalDialog
		} );
	}

	function getOptionConfig( type ) {
		if ( ! config.template_options || ! config.template_options[ type ] ) {
			return null;
		}
		return config.template_options[ type ];
	}

	function showStep( step ) {
		var $steps = $modal.find( '[data-step]' );
		var $targetStep = $modal.find( '[data-step="' + step + '"]' );

		if ( ! $targetStep.length ) {
			return;
		}

		hideTooltip();
		if ( stepAnimationTimer ) {
			window.clearTimeout( stepAnimationTimer );
			stepAnimationTimer = null;
		}

		$steps.attr( 'hidden', true ).removeClass( 'is-animating-in' );
		$targetStep.removeAttr( 'hidden' ).addClass( 'is-animating-in' );
		stepAnimationTimer = window.setTimeout( function () {
			$targetStep.removeClass( 'is-animating-in' );
		}, 220 );

		$modal.toggleClass( 'is-config-step', step === 'config' );
	}

	function openModal() {
		$modal.attr( 'aria-hidden', 'false' ).addClass( 'is-open' );
		$body.addClass( 'ae-template-create-modal-open' );
		showStep( 'types' );
		resetConfigForm();
	}

	function closeModal() {
		hideTooltip();
		$modal.attr( 'aria-hidden', 'true' ).removeClass( 'is-open' );
		$body.removeClass( 'ae-template-create-modal-open' );
		$( '#ae-template-modal-create' ).prop( 'disabled', false ).text( config.labels.edit_with_element );
		currentType = '';
	}

	function maybeOpenFromQuery() {
		var params = new URLSearchParams( window.location.search );
		if ( params.get( 'ae-open-create' ) !== '1' ) {
			return;
		}

		openModal();

		if ( window.history && window.history.replaceState ) {
			params.delete( 'ae-open-create' );
			var query = params.toString();
			var url = window.location.pathname + ( query ? '?' + query : '' ) + window.location.hash;
			window.history.replaceState( {}, document.title, url );
		}
	}

	function applyFieldVisibility( optionConfig ) {
		var fields = optionConfig.fields || {};
		$modal.find( '[data-field]' ).each( function () {
			var fieldKey = $( this ).data( 'field' );
			$( this ).toggleClass( 'is-hidden', ! fields[ fieldKey ] );
		} );
	}

	function toggleModalField( fieldKey, shouldShow ) {
		$modal.find( '[data-field="' + fieldKey + '"]' ).toggleClass( 'is-hidden', ! shouldShow );
	}

	function getCheckedValues( selector ) {
		return $( selector ).filter( ':checked' ).map( function () {
			return this.value;
		} ).get();
	}

	function setConfigHeader( optionConfig ) {
		$( '#ae-template-config-title' ).text( optionConfig.label || '' );
		$( '#ae-template-config-description' ).text( optionConfig.description || '' );
		$( '#ae-modal-template-name' ).val( optionConfig.label || config.labels.default_title );
	}

	function updateAcfBlocksFieldVisibility() {
		if ( currentType !== 'acf_blocks' ) {
			return;
		}

		var isPostLocation = $repeaterLoc.val() === 'post';
		$modal.find( '[data-field="preview_post"]' ).toggleClass( 'is-hidden', ! isPostLocation );

		var isRepeater = $acfFieldType.val() === 'repeater';
		$modal.find( '[data-field="acf_repeater"]' ).toggleClass( 'is-hidden', ! isRepeater );
		$modal.find( '[data-field="acf_flexible"]' ).toggleClass( 'is-hidden', isRepeater );
	}

	function updateNormalFieldVisibility() {
		if ( currentType !== 'normal' ) {
			return;
		}

		var usageArea = $usageArea.val() || '';
		var isGlobal = $( '#ae-modal-apply-global' ).is( ':checked' );
		var applyOn = getCheckedValues( 'input[name="ae-modal-hook-apply-on[]"]' );
		var showRules = usageArea !== '' && ! isGlobal;
		var showSingle = showRules && applyOn.indexOf( 'single' ) >= 0;
		var showArchive = showRules && applyOn.indexOf( 'archive' ) >= 0;

		toggleModalField( 'custom_usage_area', usageArea === 'custom' );
		toggleModalField( 'apply_global', usageArea !== '' );
		toggleModalField( 'hook_apply_on', showRules );
		toggleModalField( 'hook_post_types', showSingle );
		toggleModalField( 'hook_posts_selected', showSingle );
		toggleModalField( 'hook_posts_excluded', showSingle );
		toggleModalField( 'hook_taxonomies', showArchive );
		toggleModalField( 'hook_terms_selected', showArchive );
		toggleModalField( 'hook_terms_excluded', showArchive );
	}

	function loadAcfFields() {
		if ( currentType !== 'acf_blocks' ) {
			return;
		}

		var location = $repeaterLoc.val() || 'post';
		var postId = $previewPost.val() || '';
		var fieldType = $acfFieldType.val() || 'repeater';

		if ( location === 'post' && ! postId ) {
			resetSelectOptions( $acfRepeater, 'Select Repeater Field' );
			resetSelectOptions( $acfFlexible, 'Select Flexible Field' );
			return;
		}

		if ( fieldType === 'repeater' ) {
			resetSelectOptions( $acfRepeater, 'Select Repeater Field' );
			$.ajax( {
				url: config.ajax_url,
				method: 'POST',
				dataType: 'json',
				data: {
					action: 'ae_acf_repeater_fields',
					nonce: config.nonce,
					repeater_loc: location,
					post_id: postId
				}
			} ).done( function ( response ) {
				var rows = ( response && response.data ) ? response.data : [];
				$.each( rows, function ( index, row ) {
					$acfRepeater.append( $( '<option />', { value: row.id, text: row.text } ) );
				} );
			} );
		} else {
			resetSelectOptions( $acfFlexible, 'Select Flexible Field' );
			$.ajax( {
				url: config.ajax_url,
				method: 'POST',
				dataType: 'json',
				data: {
					action: 'ae_acf_flexible_fields',
					nonce: config.nonce,
					repeater_loc: location,
					field_type: fieldType,
					post_id: postId
				}
			} ).done( function ( response ) {
				var rows = ( response && response.data ) ? response.data : [];
				$.each( rows, function ( index, row ) {
					$acfFlexible.append( $( '<option />', { value: row.id, text: row.text } ) );
				} );
			} );
		}
	}

	function initAjaxInputs() {
		select2Method = detectSelect2Method();
		initAjaxSelect(
			$previewPost,
			'ae_prev_post',
			'Select Post',
			function () {
				return {
					post_type: $rulePostType.val() || 'post'
				};
			}
		);

		initAjaxSelect(
			$previewTerm,
			'ae_prev_term',
			'Select Term',
			function () {
				return {
					taxonomy: $ruleTaxonomy.val() || 'category'
				};
			}
		);
	}

	function updateDummyLinks() {
		var selectedPostType = $rulePostType.val() || 'post';
		var selectedTaxonomy = $ruleTaxonomy.val() || 'category';

		if ( $previewPostDummyLink.length ) {
			$previewPostDummyLink.attr( 'href', config.new_post_base_url + encodeURIComponent( selectedPostType ) );
		}

		if ( $previewTermDummyLink.length ) {
			$previewTermDummyLink.attr( 'href', config.new_term_base_url + encodeURIComponent( selectedTaxonomy ) );
		}
	}

	function resetConfigForm() {
		$rulePostType.prop( 'selectedIndex', 0 ).trigger( 'change' );
		$ruleTaxonomy.prop( 'selectedIndex', 0 ).trigger( 'change' );
		$usageArea.prop( 'selectedIndex', 0 );
		$customUsageArea.val( '' );
		$repeaterLoc.val( 'post' );
		$acfFieldType.val( 'repeater' );
		$( 'input[name="ae-modal-hook-apply-on[]"]' ).prop( 'checked', false );
		$( 'input[name="ae-modal-hook-post-types[]"]' ).prop( 'checked', false );
		$( 'input[name="ae-modal-hook-taxonomies[]"]' ).prop( 'checked', false );
		$hookPostsSelected.val( '' );
		$hookPostsExcluded.val( '' );
		$hookTermsSelected.val( '' );
		$hookTermsExcluded.val( '' );
		resetSelectOptions( $previewPost, 'Select Post' );
		resetSelectOptions( $previewTerm, 'Select Term' );
		resetSelectOptions( $acfRepeater, 'Select Repeater Field' );
		resetSelectOptions( $acfFlexible, 'Select Flexible Field' );
		$( '#ae-modal-apply-global' ).prop( 'checked', false );
		$( '#ae-modal-page-layout' ).val( '' );
		$( '#ae-modal-template-name' ).val( config.labels.default_title );
	}

	function goToConfigStep( type ) {
		var optionConfig = getOptionConfig( type );
		if ( ! optionConfig ) {
			return;
		}
		if ( optionConfig.is_locked ) {
			return;
		}

		currentType = type;
		setConfigHeader( optionConfig );
		applyFieldVisibility( optionConfig );
		updateNormalFieldVisibility();
		updateAcfBlocksFieldVisibility();
		loadAcfFields();
		showStep( 'config' );
	}

	function createTemplate() {
		var $createButton = $( '#ae-template-modal-create' );
		var requestData = {
			action: config.create_action,
			nonce: config.nonce,
			template_type: currentType,
			template_name: $( '#ae-modal-template-name' ).val(),
			usage_area: $usageArea.val(),
			custom_usage_area: $customUsageArea.val(),
			rule_post_type: $rulePostType.val(),
			preview_post_id: $previewPost.val(),
			rule_taxonomy: $ruleTaxonomy.val(),
			preview_term: $previewTerm.val(),
			hook_apply_on: getCheckedValues( 'input[name="ae-modal-hook-apply-on[]"]' ),
			hook_post_types: getCheckedValues( 'input[name="ae-modal-hook-post-types[]"]' ),
			hook_posts_selected: $hookPostsSelected.val(),
			hook_posts_excluded: $hookPostsExcluded.val(),
			hook_taxonomies: getCheckedValues( 'input[name="ae-modal-hook-taxonomies[]"]' ),
			hook_terms_selected: $hookTermsSelected.val(),
			hook_terms_excluded: $hookTermsExcluded.val(),
			repeater_loc: $repeaterLoc.val(),
			acf_field_type: $acfFieldType.val(),
			acf_repeater_name: $acfRepeater.val(),
			acf_flexible_name: $acfFlexible.val(),
			apply_global: $( '#ae-modal-apply-global' ).is( ':checked' ) ? '1' : '',
			page_layout: $( '#ae-modal-page-layout' ).val()
		};

		$createButton.prop( 'disabled', true ).text( config.labels.creating );

		$.ajax( {
			url: config.ajax_url,
			method: 'POST',
			dataType: 'json',
			data: requestData
		} ).done( function ( response ) {
			if ( response && response.success && response.data && response.data.redirect_url ) {
				window.location.href = response.data.redirect_url;
				return;
			}

			var message = ( response && response.data && response.data.message ) ? response.data.message : config.labels.create_failed;
			alert( message );
		} ).fail( function () {
			alert( config.labels.create_failed );
		} ).always( function () {
			$createButton.prop( 'disabled', false ).text( config.labels.edit_with_element );
		} );
	}

	var isSupportedScreen = ( $body.hasClass( 'edit-php' ) || $body.hasClass( 'post-php' ) ) && $body.hasClass( 'post-type-' + config.post_type );
	if ( ! $modal.length || ! isSupportedScreen ) {
		return;
	}

	initAjaxInputs();
	resetConfigForm();
	updateDummyLinks();
	maybeOpenFromQuery();

	$( document ).on( 'click', '.page-title-action', function ( event ) {
		var href = $( this ).attr( 'href' ) || '';
		if ( href.indexOf( 'post-new.php' ) === -1 || href.indexOf( 'post_type=' + config.post_type ) === -1 ) {
			return;
		}

		event.preventDefault();
		openModal();
	} );

	$( document ).on( 'click', '[data-ae-modal-close]', function () {
		closeModal();
	} );

	$( document ).on( 'mouseenter focusin', '.ae-template-create-modal__help, .ae-template-create-modal__type-help', function () {
		showTooltip( $( this ) );
	} );

	$( document ).on( 'mouseleave focusout', '.ae-template-create-modal__help, .ae-template-create-modal__type-help', function () {
		scheduleTooltipHide();
	} );

	$( document ).on( 'click', '.ae-template-create-modal__help, .ae-template-create-modal__type-help', function ( event ) {
		event.preventDefault();
		event.stopPropagation();
		showTooltip( $( this ) );
	} );

	$( document ).on( 'keydown', '.ae-template-create-modal__help, .ae-template-create-modal__type-help', function ( event ) {
		if ( event.key !== 'Enter' && event.key !== ' ' ) {
			return;
		}

		event.preventDefault();
		event.stopPropagation();
		showTooltip( $( this ) );
	} );

	$tooltip.on( 'mouseenter focusin', function () {
		clearTooltipHideTimer();
	} );

	$tooltip.on( 'mouseleave focusout', function () {
		scheduleTooltipHide();
	} );

	$( document ).on( 'click', function ( event ) {
		if ( $( event.target ).closest( '.ae-template-create-modal__help, .ae-template-create-modal__type-help, #ae-template-create-modal-tooltip' ).length ) {
			return;
		}

		hideTooltip();
	} );

	$( document ).on( 'keydown', function ( event ) {
		if ( event.key !== 'Escape' ) {
			return;
		}

		if ( $modal.hasClass( 'is-open' ) ) {
			if ( $modal.hasClass( 'is-config-step' ) ) {
				hideTooltip();
				return;
			}

			closeModal();
			return;
		}

		hideTooltip();
	} );

	$( document ).on( 'click', '.ae-template-create-modal__option', function ( event ) {
		var $option = $( this );
		if ( String( $option.data( 'locked' ) ) === '1' || $option.hasClass( 'is-locked' ) ) {
			event.preventDefault();
			event.stopPropagation();
			showLockedTemplateTooltip( $option );
			return;
		}

		goToConfigStep( $option.data( 'template-type' ) );
	} );

	$( document ).on( 'mouseenter focusin click', '.ae-template-create-modal__option.is-locked .ae-template-create-modal__pro-badge', function ( event ) {
		event.preventDefault();
		event.stopPropagation();
		showLockedTemplateTooltip( $( this ).closest( '.ae-template-create-modal__option' ) );
	} );

	$( document ).on( 'mouseleave focusout', '.ae-template-create-modal__option.is-locked .ae-template-create-modal__pro-badge', function () {
		scheduleTooltipHide();
	} );

	$rulePostType.on( 'change', function () {
		resetSelectOptions( $previewPost, 'Select Post' );
		updateDummyLinks();
		loadAcfFields();
	} );

	$ruleTaxonomy.on( 'change', function () {
		resetSelectOptions( $previewTerm, 'Select Term' );
		updateDummyLinks();
	} );

	$usageArea.on( 'change', function () {
		updateNormalFieldVisibility();
	} );

	$( '#ae-modal-apply-global' ).on( 'change', function () {
		updateNormalFieldVisibility();
	} );

	$( document ).on( 'change', 'input[name="ae-modal-hook-apply-on[]"]', function () {
		updateNormalFieldVisibility();
	} );

	$previewPost.on( 'change', function () {
		loadAcfFields();
	} );

	$repeaterLoc.on( 'change', function () {
		updateAcfBlocksFieldVisibility();
		loadAcfFields();
	} );

	$acfFieldType.on( 'change', function () {
		updateAcfBlocksFieldVisibility();
		loadAcfFields();
	} );

	$( '#ae-template-modal-back' ).on( 'click', function () {
		showStep( 'types' );
	} );

	$( '#ae-template-modal-create' ).on( 'click', function () {
		if ( ! currentType ) {
			return;
		}
		createTemplate();
	} );

	$( window ).on( 'resize scroll', function () {
		if ( $tooltip.attr( 'hidden' ) ) {
			return;
		}

		var $trigger = $tooltip.data( 'trigger' );
		if ( ! $trigger || ! $trigger.length ) {
			hideTooltip();
			return;
		}

		positionTooltip( $trigger );
	} );
} );
