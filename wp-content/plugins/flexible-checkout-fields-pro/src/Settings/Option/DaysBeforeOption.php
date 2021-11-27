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
class DaysBeforeOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'days_before';

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
		return self::FIELD_TYPE_NUMBER;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		return __( 'Days before', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns content for label tooltip.
	 *
	 * @return string Tooltip content.
	 */
	public function get_label_tooltip(): string {
		return __( 'Enter the range of days available in the calendar before the current date. Leave blank if the choice is not to be limited. The setting will not skip weekends and holidays.', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Filters option value from all unsafe strings.
	 *
	 * @param string|array $field_value Original option value.
	 *
	 * @return string|array Updated value of option.
	 */
	public function sanitize_option_value( $field_value ) {
		if ( $field_value === '' ) {
			return '';
		}
		return intval( $field_value );
	}
}
