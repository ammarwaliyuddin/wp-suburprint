<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Option;

use WPDesk\FCF\Pro\Settings\Option\PricingValuesOption;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Option\LabelOption;

/**
 * Supports option settings for field.
 */
class PricingValueOption extends PricingValuesOption implements OptionInterface {

	/**
	 * Returns name of option whose value will create list of rows for Repeater field.
	 *
	 * @return string Option name.
	 */
	public function get_option_name_to_rows(): string {
		return LabelOption::FIELD_NAME;
	}

	/**
	 * Returns default value of option.
	 *
	 * @return string|array Default value.
	 */
	public function get_default_value() {
		return [
			'_value' => [
				'type'      => '',
				'value'     => 0,
				'tax_class' => '',
			],
		];
	}

	/**
	 * Returns updated settings of field contain submitted values.
	 *
	 * @param array $field_data Current settings of field.
	 * @param array $field_settings Settings of field.
	 *
	 * @return array Updated settings of field.
	 */
	public function save_field_data( array $field_data, array $field_settings ): array {
		return $this->update_field_data( $field_data, $field_settings );
	}
}
