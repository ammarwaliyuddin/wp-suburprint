<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Option;

use WPDesk\FCF\Free\Settings\Option\OptionAbstract;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Tab\PricingTab;
use WPDesk\FCF\Pro\Settings\Option\OptionsOption;
use WPDesk\FCF\Pro\Settings\Option\PricingEnabledOption;
use WPDesk\FCF\Pro\Settings\Option\PricingValuesTypeOption;
use WPDesk\FCF\Pro\Settings\Option\PricingValuesTaxesOption;
use WPDesk\FCF\Pro\Settings\Option\PricingValuesValueOption;

/**
 * Supports option settings for field.
 */
class PricingValuesOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'pricing_values';

	/**
	 * Returns name of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_name(): string {
		return self::FIELD_NAME;
	}

	/**
	 * Returns name of option tab.
	 *
	 * @return string Tab name.
	 */
	public function get_option_tab(): string {
		return PricingTab::TAB_NAME;
	}

	/**
	 * Returns type of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_type(): string {
		return self::FIELD_TYPE_REPEATER;
	}

	/**
	 * Returns label of option row (for Repeater field).
	 *
	 * @return string Option row label.
	 */
	public function get_option_row_label(): string {
		/* translators: %s: option value */
		return __( 'Pricing options for "%s"', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns name of option whose value will create list of rows for Repeater field.
	 *
	 * @return string Option name.
	 */
	public function get_option_name_to_rows(): string {
		return OptionsOption::FIELD_NAME;
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
	 * Returns name of option and regex for its value that must be true to display this field.
	 * Key is name of field, value is regular expression without delimiters.
	 *
	 * @return array Option names with regexes.
	 */
	public function get_options_regexes_to_display(): array {
		return [
			PricingEnabledOption::FIELD_NAME => '^1$',
		];
	}

	/**
	 * Returns subfields of option, if exists.
	 *
	 * @return OptionInterface[] List of option children.
	 */
	public function get_children(): array {
		return [
			PricingValuesTypeOption::FIELD_NAME  => new PricingValuesTypeOption(),
			PricingValuesValueOption::FIELD_NAME => new PricingValuesValueOption(),
			PricingValuesTaxesOption::FIELD_NAME => new PricingValuesTaxesOption(),
		];
	}

	/**
	 * Filters option value from all unsafe strings.
	 *
	 * @param string|array $field_value Original option value.
	 *
	 * @return string|array Updated value of option.
	 */
	public function sanitize_option_value( $field_value ) {
		if ( ! $field_value ) {
			return [];
		}

		foreach ( $field_value as $row_index => $row ) {
			if ( ! ( $type = $row['type'] ?? '' )
				|| ! ( $value = $row['value'] ?? '' ) ) {
				unset( $field_value[ $row_index ] );
			}
		}
		return $field_value;
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
		if ( intval( $field_settings[ PricingEnabledOption::FIELD_NAME ] ?? 0 ) !== 1 ) {
			return $field_data;
		}

		$field_data  = $this->update_field_data( $field_data, $field_settings );
		$option_name = $this->get_option_name();
		if ( ! ( $options_values = $field_data[ $option_name ] ?? [] ) ) {
			return $field_data;
		}

		$options      = array_filter( $field_settings[ OptionsOption::FIELD_NAME ] ?? [] );
		$options_keys = array_column( $options, 'key' );

		foreach ( $options_values as $option_index => $option ) {
			if ( ( $option_index === '' ) || ! in_array( $option_index, $options_keys ) ) {
				unset( $options_values[ $option_index ] );
			}
		}

		$field_data[ $option_name ] = $options_values;
		return $field_data;
	}
}
