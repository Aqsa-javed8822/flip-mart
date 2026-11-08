jQuery(document).ready(function($){

    var wrapper = $('#aep_config_box');
    wrapper.find('#ae-config-general').attr('aria-hidden', false);
    wrapper.find(".f-row").attr('aria-hidden', true);

    var modules = aepro.modules;
    var lockedRenderModes = aepro.locked_render_modes || [];
    var isProActive = !!aepro.is_pro_active;
    var renderModeFields = aepro.render_mode_fields || {};

    initialLoad();

    activate_post_load();
    activate_term_load();
    activate_acf_repeater_fields_load();
    ae_modules();

    jQuery(document).on('change',
            '[name="ae_apply_global"], ' +
            '[name="ae_render_mode"], ' +
            '[name="ae_hook_apply_on[]"], ' +
            '[name="ae_usage"], ' +
            '[name="ae_repeater_loc"], ' +
            '[name="ae_acf_field_type"]',
        function(){
            wrapper.find(".f-row").attr('aria-hidden', true);
            initialLoad();

    });

    $(".ae-config-wrapper").on('click', '.ae-config-nav a', function(e){
        e.preventDefault();

        setActiveConfigTab($(this).attr('href'));
    });





    function activate_post_load(){
        jQuery('#ae_preview_post_ID').aeselect2({
            width: '100%',
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                data: function (params) {
                    post_type = jQuery('#ae_rule_post_type').val() || 'any';

                    return {
                        q: params.term,
                        action: 'ae_prev_post',
                        post_type: post_type,
						nonce: aepro.aep_nonce
                    }
                },
                processResults: function (res) {
                    return {
                        results: res.data
                    }
                }
            },
            minimumInputLength: 2
        });
    }

    function activate_term_load(){
        jQuery('#ae_preview_term').aeselect2({
            width: '100%',
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                data: function (params) {
                    taxonomy = jQuery('#ae_rule_taxonomy').val();
                    return {
                        q: params.term,
                        action: 'ae_prev_term',
                        taxonomy: taxonomy,
						nonce: aepro.aep_nonce
                    }
                },
                processResults: function (res) {
                    return {
                        results: res.data
                    }
                }
            },
            minimumInputLength: 2
        });
    }

    function activate_acf_repeater_fields_load(){
        jQuery('#ae_preview_post_ID, #ae_repeater_loc, #ae_acf_field_type').on('change',function () {


            render_mode = $('[name="ae_render_mode"]').val();
            if(render_mode != 'acf_repeater_layout'){
                return;
            }

            id = jQuery('#ae_preview_post_ID').val();
            repeater_loc = jQuery('#ae_repeater_loc').val();
            field_type = jQuery('#ae_acf_field_type').val();
            if (field_type == 'repeater' || field_type == '') {
                jQuery.ajax({
                    url: ajaxurl,
                    dataType: 'json',
                    data: {
                        action: 'ae_acf_repeater_fields',
                        post_id: id,
                        repeater_loc: repeater_loc,
                        nonce: aepro.aep_nonce
                    },
                    success: function (res) {
                        jQuery("#ae_acf_repeater_name").find('option').remove().end();
                        if (res.data.length) {
                            jQuery.each(res.data, function (i, d) {
                                jQuery("#ae_acf_repeater_name").append(jQuery("<option/>", {
                                    value: d.id,
                                    text: d.text
                                }));
                            });
                        }                 
                    }
                });
            }

            if (field_type == 'flexible_content') {
                
                jQuery.ajax({
                url: ajaxurl,
                dataType: 'json',
                data: {
                    action: 'ae_acf_flexible_fields',
                    post_id: id,
                    repeater_loc: repeater_loc,
                    field_type: field_type,
					nonce: aepro.aep_nonce
                },
                success: function (res) {
                    jQuery("#ae_acf_flexible_name").find('option').remove().end();
                        if(res.data.length){
                            jQuery.each(res.data, function(i, d) {
                                jQuery("#ae_acf_flexible_name").append(jQuery("<option/>", {
                                    value: d.id,
                                    text: d.text
                                }));
                            });
                        }                    
                    }
                });
            }
        });

    }

    function isLockedRenderMode() {
        var mode = $('[name="ae_render_mode"]').val();
        return !isProActive && lockedRenderModes.indexOf(mode) >= 0;
    }

    function toggleProRenderModeNotice() {
        var notice = $('#aep-render-mode-pro-notice');
        if (!notice.length) {
            return;
        }

        if (isLockedRenderMode()) {
            notice.removeAttr('hidden').show();
        } else {
            notice.attr('hidden', true).hide();
        }
    }

    function ae_modules() {

        const aep_wrap  = document.querySelector('.aep-wrap');
        if(aep_wrap === null){
            // Not on settings page
            return;
        }

        const selectAll = document.querySelector('#aep-select-all');
        const moduleCtas = document.querySelectorAll('.aep-module-action');
        const applyAll = document.querySelector("#aep-apply");
        //const saveLicense = document.querySelector('#save-license');
        const saveConfig = document.querySelector('#save-config');

        const tabs = document.querySelectorAll('.aep-tabs .aep-title a');


        // Settings Tab
        tabs.forEach( tab => {
            tab.addEventListener('click', function(e){

                e.preventDefault();

                const tab_anchors = document.querySelectorAll('.aep-tabs .aep-title');
                const tab_id = e.target.dataset.tabid;

                tab_anchors.forEach( tab_anchor => tab_anchor.classList.remove('active') );
                e.target.parentElement.classList.add('active');

                document.querySelectorAll('.aep-tab-content').forEach( tab_content => tab_content.classList.remove('active') );
                document.querySelector(`#${tab_id}`).classList.add('active');

            });
        });



        // Select All for Bulk Action
        selectAll.addEventListener('change', function(e){

            const modules = document.querySelectorAll('.aep-module-item');
            if(this.checked){
                modules.forEach(function(module){
                    module.checked = true;
                });
            }else{
                modules.forEach(function(module){
                    module.checked = false;
                });
            }
        });


        // Bind event for Activate/Deactivate button
        moduleCtas.forEach(function(moduleAction){

            moduleAction.addEventListener('click', function(e){

                e.stopPropagation();
                e.preventDefault();

                const cta = e.target;
                const moduleKey = cta.dataset.moduleid;
                const moduleAction = cta.dataset.action;

                let moduleData = {};
                moduleData[moduleKey] = moduleAction;

                cta.classList.add('updating');

                $.ajax({
                    url: ajaxurl,
                    method: 'post',
                    data: {
                        action : 'aep_module',
                        moduleData
                    },
                    success: function(res){

                        const modules = res.modules;

                        for(module in modules){
                            if (modules.hasOwnProperty(module)) {
                                let status = modules[module];

                                let module_anchor = document.querySelector(`[data-moduleid='${module}']`);

								if(module_anchor === null){
									continue;
								}

                                if(status === false){
                                    module_anchor.textContent = 'Activate';
                                    module_anchor.dataset.action = 'activate';
                                    module_anchor.parentElement.parentElement.classList.remove('aep-enabled');
                                    module_anchor.parentElement.parentElement.classList.add('aep-disabled');
                                }else{
                                    module_anchor.textContent = 'Deactivate';
                                    module_anchor.dataset.action = 'deactivate';
                                    module_anchor.parentElement.parentElement.classList.add('aep-enabled');
                                    module_anchor.parentElement.parentElement.classList.remove('aep-disabled');
                                }

                                module_anchor.classList.remove('updating');
                                // uncheck all checkboxes
                                moduleCBs = document.querySelectorAll('.aep-module-item');
                                moduleCBs.forEach( modulecb => modulecb.checked = false );
                            }
                        }

                    }
                });

            });
        });

        // Apply all button
        applyAll.addEventListener('click', function(e){

            const bulkAction = document.querySelector('[name="aep-bulk-action"]').value;
            const moduleData = {};
            if(bulkAction === ''){
                alert('Please select an action');
                return;
            }


            modules = document.querySelectorAll('.aep-module-item');
            modules.forEach(function(module){

                if(module.checked){
                    moduleData[module.value] = bulkAction;
					if(module.nextSibling.nextSibling.children[0].hasAttribute('data-moduleid')){
                    	module.nextSibling.nextSibling.children[0].classList.add('updating');
					}
                }

            });

            if(Object.keys(moduleData).length === 0){
                alert('Please select atleast one module');
                return;
            }

            // all set - now call the ajax and update modules.
            $.ajax({
                url: ajaxurl,
                method: 'post',
                data: {
                    action : 'aep_module',
                    moduleData
                },
                success: function(res){

                    const modules = res.modules;

                    for(module in modules){
                        if (modules.hasOwnProperty(module)) {

                            const status = modules[module];

                            const module_anchor = document.querySelector(`[data-moduleid='${module}']`);

							if(module_anchor === null){
								continue;
							}

                            if(status === false){
                                module_anchor.textContent = 'Activate';
                                module_anchor.dataset.action = 'activate';
                                module_anchor.parentElement.parentElement.classList.remove('aep-enabled');
                                module_anchor.parentElement.parentElement.classList.add('aep-disabled');
                            }else{
                                module_anchor.textContent = 'Deactivate';
                                module_anchor.dataset.action = 'deactivate';
                                module_anchor.parentElement.parentElement.classList.remove('aep-disabled');
                                module_anchor.parentElement.parentElement.classList.add('aep-enabled');
                            }

                            module_anchor.classList.remove('updating');
                            // uncheck all checkboxes
                            moduleCBs = document.querySelectorAll('.aep-module-item');
                            moduleCBs.forEach( modulecb => modulecb.checked = false );
                        }
                    }

                }
            });
        });

        // Activate License
      /*   saveLicense.addEventListener('click', function(e){

            const license_key = document.querySelector('#aep-license').value;
            const license_action = this.dataset.action;
            const license_box = document.querySelector('.aep-license-box');

            const license_msg_box = document.querySelector('.aep-license-msg');

            const nonce = document.querySelector('#aep_license_nonce').value;

            const button = this;

            license_msg_box.textContent = '';

            if(license_key === ''){
                alert('Please enter the license key');
                return;
            }

            button.classList.add('loading');

            // run fetch
            const data = {
                action : 'ae_activate_license',
                license_key,
                license_action,
                nonce
            }

            $.ajax({
                method: 'POST',
                url: ajaxurl,
                dataType: 'json',
                data,
                success: function(data){


                    if(data.action === false){
                        license_msg_box.textContent = data.message;
                    }else{

                        if(license_action == 'activate'){
                            // set active class to wrapper
                            license_box.classList.add('aep-active');

                            // change button text
                            document.querySelector('.aep-license-box .aep-action-text').textContent = 'Deactivate';

                            // change button data-action
                            button.dataset.action = 'deactivate';

                            // set license field disabled
                            document.querySelector('#aep-license').disabled = true;

                            const errorbox = document.querySelector('.aep-license-error');

                            if(errorbox){
                                errorbox.remove();
                            }


                        }else{
                            // remove active class from wrapper
                            license_box.classList.remove('aep-active');

                            // change button text
                            document.querySelector('.aep-license-box .aep-action-text').textContent = 'Activate';

                            // change button data-action
                            button.dataset.action = 'activate';

                            // make license key blank and remove disabled.
                            document.querySelector('#aep-license').value = '';
                            document.querySelector('#aep-license').disabled = false;
                        }


                    }
                    button.classList.remove('loading');

                }
            });

        });
 */
        saveConfig.addEventListener('click', function(e){

            const ae_pro_gmap_api = document.querySelector('#ae_pro_gmap_api').value;
            const templateDebugControl = document.querySelector('#ae_pro_template_debug');
            const ae_pro_template_debug = templateDebugControl && templateDebugControl.checked ? '1' : '';
            const btn = this;

            btn.classList.add('loading');

            $.ajax({
                url: ajaxurl,
                method: 'post',
                data: {
                    action: 'aep_save_config',
                    config: {
                        ae_pro_gmap_api,
                        ae_pro_template_debug
                    },
                    nonce: aepro.aep_nonce

                },
                success: function(res){
                    btn.classList.remove('loading');
                }
            })
        })

        async function postData(url = '', data = {}) {

            // Prepare form data
            let formData = new FormData();
            for (let [key, value] of Object.entries(data)) {
                formData.append(key, value);
            }
            // Default options are marked with *
            const response = await fetch(url, {
              method: 'POST', // *GET, POST, PUT, DELETE, etc.
              mode: 'cors', // no-cors, *cors, same-origin
              cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
              credentials: 'same-origin', // include, *same-origin, omit
              headers: {
                  //'Content-Type': 'application/json'
                  // 'Content-Type': 'application/x-www-form-urlencoded',
                  'Content-Type': 'multipart/form-data'
              },
              redirect: 'follow', // manual, *follow, error
              referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
              body: formData // body data type must match "Content-Type" header
            });
            return response.json(); // parses JSON response into native JavaScript objects
          }

    }

    function initialLoad(){
        showfield('ae_render_mode');
        setActiveConfigTab('#ae-config-general');
        showRulesTab(false);

        if (isLockedRenderMode()) {
            wrapper.find(".f-row").attr('aria-hidden', true);
            showfield('ae_render_mode');
            setActiveConfigTab('#ae-config-general');
            showRulesTab(false);
            toggleProRenderModeNotice();
            return;
        }

        var render_mode = $('[name="ae_render_mode"]').val();

        switch(render_mode){
            case 'post_type_archive_template'   :    pt_archive();
                                                     break;

            case 'post_template'                :    post_template();
                                                     break;

            case 'archive_template'             :    archive_template();
                                                     break;

            case 'block_layout'                 :    block_layout();
                                                     break;

            case 'normal'                       :    normal();
                                                     break;

            case '404'                          :    _404();
                                                     break;

            case 'search'                       :    _search();
                                                     break;

            case 'author_template'              :   _author();
                                                     break;

            case 'date_template'                :   _date();
                                                    break;

            case 'acf_repeater_layout'          :   acf_repeater_layout();
                                                    break;
        }

        toggleProRenderModeNotice();
    }

    function showfield(field) {
        $('[name="' + field +'"]').closest('.f-row').attr('aria-hidden', false);
    }

    function showModeFields(mode) {
        var fields = renderModeFields[mode] || [];
        if (!Array.isArray(fields)) {
            return;
        }

        fields.forEach(function(field){
            showfield(field);
        });
    }

    function _404(){
        showModeFields('404');
    }

    function _search(){
        showModeFields('search');
    }

    function archive_template(){
        showModeFields('archive_template');
    }

    function block_layout(){
        showModeFields('block_layout');
    }

    function acf_repeater_layout(){
        showModeFields('acf_repeater_layout');

        repeater_loc = $('[name="ae_repeater_loc"]').val();

        if(repeater_loc == 'post'){
            showfield('ae_preview_post_ID');
        }

        $field_type = $('[name="ae_acf_field_type"]').val();

        if ($field_type === 'repeater' || $field_type === '') {
            showfield('ae_acf_repeater_name');    
        }

        if ($field_type === 'flexible_content') {
            showfield('ae_acf_flexible_name');    
        }
        

    }

    function normal(){
        showModeFields('normal');

        usage_area = $('[name="ae_usage"]').val();

        if(usage_area == 'custom'){
            showfield('ae_custom_usage_area');
        }

        if(usage_area != ''){
            showfield('ae_apply_global');
            auto_apply = $('[name="ae_apply_global"]').is(":checked");

            if(!auto_apply){
                showRulesTab(true);
                showfield('ae_hook_apply_on[]');

                page_types = $("input[name='ae_hook_apply_on[]']:checked").map(function () {return this.value;}).get();

                if(page_types.indexOf('single') >= 0){
                    showfield('ae_hook_post_types[]');
                    showfield('ae_hook_posts_selected');
                    showfield('ae_hook_posts_excluded');
                }

                if(page_types.indexOf('archive') >= 0){
                    showfield('ae_hook_taxonomies[]');
                    showfield('ae_hook_terms_selected');
                    showfield('ae_hook_terms_excluded');
                }
            }
        }
    }

    function showRulesTab(show){
        var $rulesTab = $('li.ae-rules');
        if(show){
            $rulesTab.show();
            return;
        }

        $rulesTab.hide().attr('aria-selected', false);
        if($('#ae-config-rules').attr('aria-hidden') === 'false'){
            setActiveConfigTab('#ae-config-general');
        }
    }

    function setActiveConfigTab(tabSelector){
        var $targetTab = $(tabSelector);
        if(!$targetTab.length){
            return;
        }

        $('.ae-config-nav li').attr('aria-selected', false);
        $('.ae-config-nav a[href="' + tabSelector + '"]').closest('li').attr('aria-selected', true);

        $('.ae-config-content')
            .attr('aria-hidden', true)
            .hide();

        $targetTab
            .attr('aria-hidden', false)
            .show();
    }

    function post_template(){
        showModeFields('post_template');
    }

    function pt_archive(){
        showModeFields('post_type_archive_template');
    }

    function _author() {
        showModeFields('author_template');
    }

    function _date() {
        showModeFields('date_template');
    }


    //Import Template section.
    jQuery('#elementor-hidden-area.ae-import-form').show();
    const queryString = window.location.search;
    let currentUrlParams = new URLSearchParams(queryString);
    if (currentUrlParams.get('ae_import_template')) {
        jQuery('nav.ae-nav ul li').removeClass('ae-nav-active');
        jQuery('.ae-import-template-btn').parent().addClass('ae-nav-active');
        jQuery('.ae-import-form #elementor-import-template-area').toggleClass('show');
    }
    jQuery('.ae-import-template-btn').on('click', function (e) {
        let urlParams = new URLSearchParams(jQuery(this).attr('href'));
        if (currentUrlParams.get('ae_import_template') && urlParams.get('ae_import_template')) {
            e.preventDefault();
            jQuery('nav.ae-nav ul li').removeClass('ae-nav-active');
            jQuery('.ae-import-template-btn').parent().addClass('ae-nav-active');
            jQuery('.ae-import-form #elementor-import-template-area').toggleClass('show');
        }
	});

});

jQuery(document).ready(function($){
    var $helpTooltip = $('#aep-help-tooltip');
    var helpTooltipHideTimer = null;
    var helpTriggerSelector = '#aep_config_box .aep-help-dot, .aep-locked';

    if (!$helpTooltip.length) {
        $helpTooltip = $(
            '<div id="aep-help-tooltip" class="aep-help-tooltip" role="tooltip" hidden>' +
                '<p class="aep-help-tooltip__text"></p>' +
                '<a class="aep-help-tooltip__link" href="#" target="_blank" rel="noopener"></a>' +
            '</div>'
        ).appendTo('body');
    }

    function clearHelpTooltipTimer() {
        if (helpTooltipHideTimer) {
            window.clearTimeout(helpTooltipHideTimer);
            helpTooltipHideTimer = null;
        }
    }

    function hideHelpTooltip() {
        clearHelpTooltipTimer();
        if ($helpTooltip && $helpTooltip.length) {
            $helpTooltip.attr('hidden', true).removeClass('is-below').removeData('trigger');
        }
    }

    function scheduleHelpTooltipHide() {
        clearHelpTooltipTimer();
        helpTooltipHideTimer = window.setTimeout(function () {
            hideHelpTooltip();
        }, 120);
    }

    function positionHelpTooltip($trigger) {
        if (!$helpTooltip.length || !$trigger.length) {
            return;
        }

        var triggerRect = $trigger.get(0).getBoundingClientRect();
        var tooltipRect = $helpTooltip.get(0).getBoundingClientRect();
        var gap = 12;
        var viewportPadding = 12;
        var left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2);
        var maxLeft = window.innerWidth - tooltipRect.width - viewportPadding;
        var arrowLeft;
        var top = triggerRect.top - tooltipRect.height - gap;
        var placeBelow = false;

        if (top < viewportPadding) {
            top = triggerRect.bottom + gap;
            placeBelow = true;
        }

        left = Math.max(viewportPadding, Math.min(left, Math.max(viewportPadding, maxLeft)));
        arrowLeft = triggerRect.left + (triggerRect.width / 2) - left;

        $helpTooltip
            .toggleClass('is-below', placeBelow)
            .css({
                top: top + 'px',
                left: left + 'px',
                '--aep-help-arrow-left': arrowLeft + 'px'
            });
    }

    function showHelpTooltip($trigger) {
        if (!$helpTooltip.length || !$trigger.length) {
            return;
        }

        var message = $trigger.data('tooltip') || '';
        var link = $trigger.data('tooltip-link') || '';
        var linkLabel = $trigger.data('tooltip-link-label') || 'Learn More';
        var $text = $helpTooltip.find('.aep-help-tooltip__text');
        var $link = $helpTooltip.find('.aep-help-tooltip__link');

        if (!message) {
            hideHelpTooltip();
            return;
        }

        clearHelpTooltipTimer();
        $text.text(message);

        if (link) {
            $link.attr('href', link).text(linkLabel).removeAttr('hidden');
        } else {
            $link.attr('href', '#').text('').attr('hidden', true);
        }

        $helpTooltip.attr('hidden', false).data('trigger', $trigger);
        positionHelpTooltip($trigger);
    }

    $(document).on('click', '.aep-locked', function () {
        var link = $(this).data('tooltip-link');
        if (link) {
            window.open(link, '_blank', 'noopener');
        }
    });

    $(document).on('mouseenter focusin', helpTriggerSelector, function () {
        showHelpTooltip($(this));
    });

    $(document).on('mouseleave focusout', helpTriggerSelector, function () {
        scheduleHelpTooltipHide();
    });

    $(document).on('click', helpTriggerSelector, function (event) {
        if ($(this).hasClass('aep-locked')) {
            return;
        }
        event.preventDefault();
        event.stopPropagation();
        showHelpTooltip($(this));
    });

    $(document).on('keydown', helpTriggerSelector, function (event) {
        if (event.key !== 'Enter' && event.key !== ' ') {
            return;
        }

        if ($(this).hasClass('aep-locked')) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        showHelpTooltip($(this));
    });

    $helpTooltip.on('mouseenter focusin', function () {
        clearHelpTooltipTimer();
    });

    $helpTooltip.on('mouseleave focusout', function () {
        scheduleHelpTooltipHide();
    });

    $(document).on('click', function (event) {
        if ($(event.target).closest(helpTriggerSelector + ', #aep-help-tooltip').length) {
            return;
        }
        hideHelpTooltip();
    });

    $(document).on('keydown', function (event) {
        if (event.key === 'Escape') {
            hideHelpTooltip();
        }
    });

    $(window).on('resize scroll', function () {
        if ($helpTooltip.attr('hidden')) {
            return;
        }

        var $trigger = $helpTooltip.data('trigger');
        if (!$trigger || !$trigger.length) {
            hideHelpTooltip();
            return;
        }

        positionHelpTooltip($trigger);
    });
});


( function( $ ) {

    $(window).on('load', function(){
		// Write all new code here
		const queryString = window.location.search;
		const urlParams = new URLSearchParams(queryString);

		const param = urlParams.get('activate-ae');

		if(param == 1){ 
			jQuery('.activate-license.anywhere-elementor a').click();
		}

	});

} )( jQuery );
