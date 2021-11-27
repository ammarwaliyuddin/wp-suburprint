<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Field\Type\Wc;

use WPDesk\FCF\Free\Field\Type\Wc\WcNotesType as DefaultWcNotesType;
use WPDesk\FCF\Free\Field\Type\TypeInterface;
use WPDesk\FCF\Free\Settings\Tab\LogicTab;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Pro\Settings\Option\LogicFieldsEnabledOption;
use WPDesk\FCF\Pro\Settings\Option\LogicFieldsGroupOption;
use WPDesk\FCF\Pro\Settings\Option\LogicFieldsRulesOption;
use WPDesk\FCF\Pro\Settings\Option\LogicProductsEnabledOption;
use WPDesk\FCF\Pro\Settings\Option\LogicProductsGroupOption;
use WPDesk\FCF\Pro\Settings\Option\LogicProductsRulesOption;
use WPDesk\FCF\Pro\Settings\Option\LogicShippingEnabledOption;
use WPDesk\FCF\Pro\Settings\Option\LogicShippingGroupOption;
use WPDesk\FCF\Pro\Settings\Option\LogicShippingRulesOption;
use WPDesk\FCF\Pro\Settings\Option\LogicInfoOption;

/**
 * Supports field type settings.
 */
class WcNotesType extends DefaultWcNotesType implements TypeInterface {

	/**
	 * Returns list of options for field settings.
	 *
	 * @return OptionInterface[] List of option fields.
	 */
	public function get_options_objects(): array {
		return array_merge(
			parent::get_options_objects(),
			[
				LogicTab::TAB_NAME => [
					LogicProductsEnabledOption::FIELD_NAME => new LogicProductsEnabledOption(),
					LogicProductsGroupOption::FIELD_NAME   => new LogicProductsGroupOption(),
					LogicProductsRulesOption::FIELD_NAME   => new LogicProductsRulesOption(),
					LogicFieldsEnabledOption::FIELD_NAME   => new LogicFieldsEnabledOption(),
					LogicFieldsGroupOption::FIELD_NAME     => new LogicFieldsGroupOption(),
					LogicFieldsRulesOption::FIELD_NAME     => new LogicFieldsRulesOption(),
					LogicShippingEnabledOption::FIELD_NAME => new LogicShippingEnabledOption(),
					LogicShippingGroupOption::FIELD_NAME   => new LogicShippingGroupOption(),
					LogicShippingRulesOption::FIELD_NAME   => new LogicShippingRulesOption(),
					LogicInfoOption::FIELD_NAME            => new LogicInfoOption(),
				],
			]
		);
	}
}
