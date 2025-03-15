jQuery(window).on("elementor:init", function () {
	"use strict";
	function dl_custom_css_loading(css, view) {
		if (!view) {
			return;
		}
		var model = view.model,
			customCSS = model.get('settings').get('_droit_custom_css');
		if (customCSS) {
			css += customCSS.replace(/selector/g, '.elementor-element.elementor-element-' + view.model.id);
		}
		return css;
	}

	function dl_custom_page_loading() {
		var customCSS = elementor.settings.page.model.get('_droit_custom_css');
		if (customCSS) {
			customCSS = customCSS.replace(/selector/g, elementor.config.settings.page.cssWrapperSelector);
			elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append(customCSS);
		}
	}
	elementor.hooks.addFilter('editor/style/styleText', dl_custom_css_loading);
	elementor.settings.page.model.on('change', dl_custom_page_loading);
	elementor.on('preview:loaded', dl_custom_page_loading);
});
