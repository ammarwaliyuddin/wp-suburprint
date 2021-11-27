<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Field\Type;

use WPDesk\FCF\Free\Field\Type\SelectType as DefaultSelectType;
use WPDesk\FCF\Free\Field\Type\TypeInterface;
use WPDesk\FCF\Free\Settings\Tab\AdvancedTab;
use WPDesk\FCF\Free\Settings\Tab\AppearanceTab;
use WPDesk\FCF\Free\Settings\Tab\DisplayTab;
use WPDesk\FCF\Free\Settings\Tab\GeneralTab;
use WPDesk\FCF\Free\Settings\Tab\LogicTab;
use WPDesk\FCF\Free\Settings\Tab\PricingTab;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Pro\Settings\Option\CssSelect2Option;
use WPDesk\FCF\Free\Settings\Option\CustomFieldOption;
use WPDesk\FCF\Pro\Settings\Option\DefaultOptionsOption;
use WPDesk\FCF\Free\Settings\Option\DisplayOnOption;
use WPDesk\FCF\Free\Settings\Option\EnabledOption;
use WPDesk\FCF\Free\Settings\Option\ExternalFieldOption;
use WPDesk\FCF\Free\Settings\Option\ExternalFieldInfoOption;
use WPDesk\FCF\Free\Settings\Option\FieldTypeOption;
use WPDesk\FCF\Free\Settings\Option\FormattingOption;
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
use WPDesk\FCF\Pro\Settings\Option\OptionsOption;
use WPDesk\FCF\Free\Settings\Option\PlaceholderOption;
use WPDesk\FCF\Pro\Settings\Option\PricingEnabledOption;
use WPDesk\FCF\Pro\Settings\Option\PricingValuesOption;
use WPDesk\FCF\Pro\Settings\Option\PricingInfoOption;
use WPDesk\FCF\Free\Settings\Option\PriorityOption;
use WPDesk\FCF\Free\Settings\Option\RequiredOption;
use WPDesk\FCF\Free\Settings\Option\ValidationOption;
use WPDesk\FCF\Free\Settings\Option\ValidationInfoOption;

/**
 * Supports field type settings.
 */
class SelectType extends DefaultSelectType implements TypeInterface {

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
				ExternalFieldInfoOption::FIELD_NAME => new ExternalFieldInfoOption(),
				PriorityOption::FIELD_NAME          => new PriorityOption(),
				FieldTypeOption::FIELD_NAME         => new FieldTypeOption(),
				CustomFieldOption::FIELD_NAME       => new CustomFieldOption(),
				ExternalFieldOption::FIELD_NAME     => new ExternalFieldOption(),
				EnabledOption::FIELD_NAME           => new EnabledOption(),
				RequiredOption::FIELD_NAME          => new RequiredOption(),
				LabelOption::FIELD_NAME             => new LabelOption(),
				OptionsOption::FIELD_NAME           => new OptionsOption(),
				DefaultOptionsOption::FIELD_NAME    => new DefaultOptionsOption(),
				NameOption::FIELD_NAME              => new NameOption(),
			],
			AdvancedTab::TAB_NAME   => [
				ValidationOption::FIELD_NAME     => new ValidationOption(),
				ValidationInfoOption::FIELD_NAME => new ValidationInfoOption(),
			],
			AppearanceTab::TAB_NAME => [
				PlaceholderOption::FIELD_NAME => new PlaceholderOption(),
				CssSelect2Option::FIELD_NAME  => new CssSelect2Option(),
			],
			DisplayTab::TAB_NAME    => [
				DisplayOnOption::FIELD_NAME  => new DisplayOnOption(),
				FormattingOption::FIELD_NAME => new FormattingOption(),
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
			PricingTab::TAB_NAME    => [
				PricingEnabledOption::FIELD_NAME => new PricingEnabledOption(),
				PricingValuesOption::FIELD_NAME  => new PricingValuesOption(),
				PricingInfoOption::FIELD_NAME    => new PricingInfoOption(),
			],
		];
	}
}
