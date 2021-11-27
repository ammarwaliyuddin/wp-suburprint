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
use WPDesk\FCF\Pro\Settings\Option\LogicShippingEnabledOption;
use WPDesk\FCF\Pro\Settings\Option\LogicShippingGroupActionOption;
use WPDesk\FCF\Pro\Settings\Option\LogicShippingGroupOperatorOption;

/**
 * Supports option settings for field.
 */
class LogicShippingGroupOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'conditional_logic_shipping_fields_group';

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
		return self::FIELD_TYPE_GROUP;
	}

	/**
	 * Returns name of option and regex for its value that must be true to display this field.
	 * Key is name of field, value is regular expression without delimiters.
	 *
	 * @return array Option names with regexes.
	 */
	public function get_options_regexes_to_display(): array {
		return [
			LogicShippingEnabledOption::FIELD_NAME => '^1$',
		];
	}

	/**
	 * Returns subfields of option, if exists.
	 *
	 * @return OptionInterface[] List of option children.
	 */
	public function get_children(): array {
		return [
			LogicShippingGroupActionOption::FIELD_NAME   => new LogicShippingGroupActionOption(),
			LogicShippingGroupOperatorOption::FIELD_NAME => new LogicShippingGroupOperatorOption(),
		];
	}
}
