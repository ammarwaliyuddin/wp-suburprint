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
use WPDesk\FCF\Pro\Settings\Option\LogicProductsRulesConditionOption;
use WPDesk\FCF\Pro\Settings\Option\LogicProductsRulesWhatOption;
use WPDesk\FCF\Pro\Settings\Route\ProductsCatsRoute;

/**
 * Supports option settings for field.
 */
class LogicProductsRulesValueCatsOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'product_categories';

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
		return self::FIELD_TYPE_SELECT_MULTI;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		return __( 'Categories', 'flexible-checkout-fields-pro' );
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
			LogicProductsRulesConditionOption::FIELD_NAME => '^cart_contains$',
			LogicProductsRulesWhatOption::FIELD_NAME      => '^product_category$',
		];
	}

	/**
	 * Returns endpoint route to retrieve values.
	 *
	 * @return string Route name of endpoint.
	 */
	public function get_endpoint_route(): string {
		return ProductsCatsRoute::REST_API_ROUTE;
	}

	/**
	 * Returns option names passed to REST API to retrieve values.
	 *
	 * @return array Option keys.
	 */
	public function get_endpoint_option_names(): array {
		return [
			LogicProductsRulesConditionOption::FIELD_NAME,
			LogicProductsRulesWhatOption::FIELD_NAME,
		];
	}
}
