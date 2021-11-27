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

/**
 * Supports option settings for field.
 */
class LogicFieldsEnabledOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'conditional_logic_fields';

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
		return self::FIELD_TYPE_CHECKBOX;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		return __( 'Enable conditional logic based on FCF fields', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns content for label tooltip.
	 *
	 * @return string Tooltip content.
	 */
	public function get_label_tooltip(): string {
		return __( 'This logic supports fields with values added using FCF. Default WooCommerce fields are not taken into account.', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns URL for label tooltip.
	 *
	 * @return string Tooltip URL.
	 */
	public function get_label_tooltip_url(): string {
		return apply_filters( 'flexible_checkout_fields/short_url', '#', 'fcf-settings-field-tooltip-logic-fields' );
	}
}
