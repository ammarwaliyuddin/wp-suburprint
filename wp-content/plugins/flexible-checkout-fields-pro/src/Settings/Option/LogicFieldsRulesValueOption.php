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
use WPDesk\FCF\Pro\Settings\Option\LogicFieldsRulesFieldOption;
use WPDesk\FCF\Pro\Settings\Option\LogicFieldsRulesConditionOption;
use WPDesk\FCF\Pro\Settings\Route\FieldsValueRoute;

/**
 * Supports option settings for field.
 */
class LogicFieldsRulesValueOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'value';

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
		return __( 'Value', 'flexible-checkout-fields-pro' );
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
	 * Returns name of option and regex for its value that must be true to display this field.
	 * Key is name of field, value is regular expression without delimiters.
	 *
	 * @return array Option names with regexes.
	 */
	public function get_options_regexes_to_display(): array {
		return [
			LogicFieldsRulesFieldOption::FIELD_NAME     => '^.{1,}$',
			LogicFieldsRulesConditionOption::FIELD_NAME => '^.{1,}$',
		];
	}

	/**
	 * Returns endpoint route to retrieve values.
	 *
	 * @return string Route name of endpoint.
	 */
	public function get_endpoint_route(): string {
		return FieldsValueRoute::REST_API_ROUTE;
	}

	/**
	 * Returns option names passed to REST API to retrieve values.
	 *
	 * @return array Option keys.
	 */
	public function get_endpoint_option_names(): array {
		return [
			LogicFieldsRulesFieldOption::FIELD_NAME,
			LogicFieldsRulesConditionOption::FIELD_NAME,
		];
	}

	/**
	 * Returns status if values from endpoint should be refreshed automatically (triggered by refresh event).
	 *
	 * @return bool Status of auto-refreshed values.
	 */
	public function is_endpoint_autorefreshed(): bool {
		return true;
	}
}
