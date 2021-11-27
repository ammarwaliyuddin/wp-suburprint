<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Field\Type;

use WPDesk\FCF\Free\Field\Type\HtmlType as DefaultHtmlType;
use WPDesk\FCF\Free\Field\Type\TypeInterface;
use WPDesk\FCF\Free\Settings\Tab\AppearanceTab;
use WPDesk\FCF\Free\Settings\Tab\GeneralTab;
use WPDesk\FCF\Free\Settings\Tab\LogicTab;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Option\CssOption;
use WPDesk\FCF\Free\Settings\Option\CustomFieldOption;
use WPDesk\FCF\Free\Settings\Option\EnabledOption;
use WPDesk\FCF\Free\Settings\Option\FieldTypeOption;
use WPDesk\FCF\Free\Settings\Option\LabelOption;
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
use WPDesk\FCF\Free\Settings\Option\NameOption;
use WPDesk\FCF\Free\Settings\Option\PriorityOption;
use WPDesk\FCF\Free\Settings\Option\RequiredHiddenOption;

/**
 * Supports field type settings.
 */
class HtmlType extends DefaultHtmlType implements TypeInterface {

	/**
	 * Returns whether field type is available for plugin version.
	 *
	 * @return bool Status if field type is available.
	 */
	public function is_available(): bool {
		return true;
	}

	/**
	 * Returns list of options for field settings.
	 *
	 * @return OptionInterface[] List of option fields.
	 */
	public function get_options_objects(): array {
		return [
			GeneralTab::TAB_NAME    => [
				PriorityOption::FIELD_NAME       => new PriorityOption(),
				FieldTypeOption::FIELD_NAME      => new FieldTypeOption(),
				CustomFieldOption::FIELD_NAME    => new CustomFieldOption(),
				EnabledOption::FIELD_NAME        => new EnabledOption(),
				RequiredHiddenOption::FIELD_NAME => new RequiredHiddenOption(),
				LabelOption::FIELD_NAME          => new LabelOption(),
				NameOption::FIELD_NAME           => new NameOption(),
			],
			AppearanceTab::TAB_NAME => [
				CssOption::FIELD_NAME => new CssOption(),
			],
			LogicTab::TAB_NAME      => [
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
		];
	}
}
