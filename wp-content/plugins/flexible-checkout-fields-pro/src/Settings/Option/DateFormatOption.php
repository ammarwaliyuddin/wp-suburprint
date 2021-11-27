<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Option;

use WPDesk\FCF\Free\Settings\Option\OptionAbstract;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Tab\AdvancedTab;

/**
 * Supports option settings for field.
 */
class DateFormatOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'date_format';

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
		return AdvancedTab::TAB_NAME;
	}

	/**
	 * Returns type of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_type(): string {
		return self::FIELD_TYPE_TEXT;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		return __( 'Date format', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns content for label tooltip.
	 *
	 * @return string Tooltip content.
	 */
	public function get_label_tooltip(): string {
		return __( 'Enter the WordPress date format.', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns list of validation rules for field.
	 * Key is regular expression without delimiters, value is message of validation error.
	 *
	 * @return array Validation rules.
	 */
	public function get_validation_rules(): array {
		return [
			'^.{1,}$' => __( 'This field is required.', 'flexible-checkout-fields-pro' ),
		];
	}

	/**
	 * Returns default value of option.
	 *
	 * @return string|array Default value.
	 */
	public function get_default_value() {
		return 'dd.mm.yy';
	}
}
