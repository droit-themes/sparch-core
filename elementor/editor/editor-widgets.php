<?php 
namespace DROIT_ELEMENTOR_PRO;

defined( 'ABSPATH' ) || exit;

class DL_Editor_Widgets extends \Elementor\Base_Data_Control {
	
	public function get_type() {
		return 'dleditor';
	}

	public function enqueue() {
        wp_enqueue_script('dlpro-editor-refresh', Dl_Editor::url() . 'js/editor-refresh.js', ['elementor-editor'], Dl_Editor::version(), true);
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div style="display:none" class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<input id="<?php echo esc_attr($control_uid); ?>" type="text" data-setting="{{ data.name }}" data-checking-dl="true" />
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'show_edit_button' => false,
		];
	}
}