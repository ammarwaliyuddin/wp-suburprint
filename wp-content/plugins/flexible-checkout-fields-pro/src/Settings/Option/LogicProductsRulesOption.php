<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Option;

use WPDesk\FCF\Free\Settings\Option\OptionAbstract;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Tab\LogicTab;
use WPDesk\FCF\Pro\Settings\Option\LogicProductsEnabledOption;
use WPDesk\FCF\Pro\Settings\Option\LogicProductsRulesConditionOption;
use WPDesk\FCF\Pro\Settings\Option\LogicProductsRulesWhatOption;
use WPDesk\FCF\Pro\Settings\Option\LogicProductsRulesValueProductsOption;
use WPDesk\FCF\Pro\Settings\Option\LogicProductsRulesValueCatsOption;

/**
 * Supports option settings for field.
 */
class LogicProductsRulesOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'conditional_logic_rules';

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
		return LogicTab::TAB_NAME;
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
		/* translators: %s: rule index */
		return __( 'Rule #%s', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns name of option and regex for its value that must be true to display this field.
	 * Key is name of field, value is regular expression without delimiters.
	 *
	 * @return array Option names with regexes.
	 */
	public function get_options_regexes_to_display(): array {
		return [
			LogicProductsEnabledOption::FIELD_NAME => '^1$',
		];
	}

	/**
	 * Returns default value of option.
	 *
	 * @return string|array Default value.
	 */
	public function get_default_value() {
		return [
			[
				'condition'          => '',
				'what'               => '',
				'products'           => [],
				'product_categories' => [],
			],
		];
	}

	/**
	 * Returns subfields of option, if exists.
	 *
	 * @return OptionInterface[] List of option children.
	 */
	public function get_children(): array {
		return [
			LogicProductsRulesConditionOption::FIELD_NAME => new LogicProductsRulesConditionOption(),
			LogicProductsRulesWhatOption::FIELD_NAME      => new LogicProductsRulesWhatOption(),
			LogicProductsRulesValueProductsOption::FIELD_NAME => new LogicProductsRulesValueProductsOption(),
			LogicProductsRulesValueCatsOption::FIELD_NAME => new LogicProductsRulesValueCatsOption(),
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
			if ( ! ( $condition = $row['condition'] ?? '' )
				|| ! ( $what = $row['what'] ?? '' )
				|| ( ! ( $products = $row['products'] ?? '' ) && ! ( $categories = $row['product_categories'] ?? '' ) ) ) {
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
		if ( intval( $field_settings[ LogicProductsEnabledOption::FIELD_NAME ] ?? 0 ) !== 1 ) {
			return $field_data;
		}

		return $this->update_field_data( $field_data, $field_settings );
	}
}
