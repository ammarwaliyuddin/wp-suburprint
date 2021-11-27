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
use WPDesk\FCF\Pro\Settings\Option\PricingValuesValueOption;

/**
 * Supports option settings for field.
 */
class PricingValuesTaxesOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'tax_class';

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
		return self::FIELD_TYPE_SELECT;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		return __( 'Tax class', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns name of option and regex for its value that must be true to display this field.
	 * Key is name of field, value is regular expression without delimiters.
	 *
	 * @return array Option names with regexes.
	 */
	public function get_options_regexes_to_display(): array {
		return [
			PricingValuesValueOption::FIELD_NAME => '^([1-9]|0\.[0-9])',
		];
	}

	/**
	 * Returns available values of option, if exists.
	 *
	 * @return array List of option values.
	 */
	public function get_values(): array {
		$values = [];
		if ( ! wc_tax_enabled() ) {
			return $values;
		}

		return array_merge(
			$values,
			[
				'standard' => __( 'Standard rates', 'flexible-checkout-fields-pro' ),
			],
			array_combine( \WC_Tax::get_tax_class_slugs(), \WC_Tax::get_tax_classes() )
		);
	}
}
