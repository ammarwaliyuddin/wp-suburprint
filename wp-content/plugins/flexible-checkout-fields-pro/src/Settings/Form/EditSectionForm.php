<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Form;

use WPDesk\FCF\Free\Settings\Form\FormAbstract;
use WPDesk\FCF\Free\Settings\Form\FormInterface;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Option\OptionIntegration;
use WPDesk\FCF\Pro\Settings\Option\SectionTitleOption;
use WPDesk\FCF\Pro\Settings\Option\SectionTagOption;
use WPDesk\FCF\Pro\Settings\Option\SectionCssOption;

/**
 * Supports settings for form.
 */
class EditSectionForm extends FormAbstract implements FormInterface {

	const FORM_TYPE            = 'section';
	const SETTINGS_OPTION_NAME = 'inspire_checkout_fields_section_settings';

	/**
	 * Returns type of form.
	 *
	 * @return string Type of form.
	 */
	public function get_form_type(): string {
		return self::FORM_TYPE;
	}

	/**
	 * Returns basic settings for form.
	 *
	 * @param array  $form_data Default settings of form.
	 * @param string $form_key Key of form.
	 *
	 * @return array Settings of form.
	 */
	public function get_form_data( array $form_data, string $form_key = '' ): array {
		$settings       = get_option( self::SETTINGS_OPTION_NAME, [] );
		$section_fields = $settings[ $form_key ] ?? [];

		$option_objects = $this->get_options_list();
		foreach ( $option_objects as $field_option ) {
			$form_data = $field_option['update_field_callback'](
				$form_data,
				$section_fields
			);
		}

		return $form_data;
	}

	/**
	 * Returns list of option objects.
	 *
	 * @return OptionInterface[] List of options.
	 */
	public function get_options_list(): array {
		return [
			( new OptionIntegration( new SectionTitleOption() ) )->get_field_settings(),
			( new OptionIntegration( new SectionTagOption() ) )->get_field_settings(),
			( new OptionIntegration( new SectionCssOption() ) )->get_field_settings(),
		];
	}

	/**
	 * Saves settings for form.
	 *
	 * @param array $params Params for endpoint.
	 *
	 * @return bool Status of process.
	 *
	 * @throws \Exception .
	 */
	public function save_form_data( array $params ): bool {
		$section_settings = [];

		$option_objects = $this->get_options_list();
		foreach ( $option_objects as $field_option ) {
			$section_settings = $field_option['save_field_callback'](
				$section_settings,
				$params['form_fields']
			);
		}

		$settings                            = get_option( self::SETTINGS_OPTION_NAME, [] );
		$settings[ $params['form_section'] ] = $section_settings;
		update_option( self::SETTINGS_OPTION_NAME, $settings );

		return true;
	}
}
