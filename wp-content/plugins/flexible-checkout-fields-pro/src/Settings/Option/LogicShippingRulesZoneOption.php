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
use WPDesk\FCF\Pro\Settings\Route\ShippingZonesRoute;

/**
 * Supports option settings for field.
 */
class LogicShippingRulesZoneOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'field';

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
		return self::FIELD_TYPE_SELECT;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		return __( 'Shipping zone', 'flexible-checkout-fields-pro' );
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
	 * Returns endpoint route to retrieve values.
	 *
	 * @return string Route name of endpoint.
	 */
	public function get_endpoint_route(): string {
		return ShippingZonesRoute::REST_API_ROUTE;
	}
}
