<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Field\Type;

use WPDesk\FCF\Free\Field\Type\TextareaType as DefaultTextareaType;
use WPDesk\FCF\Free\Field\Type\TypeInterface;
use WPDesk\FCF\Free\Settings\Tab\GeneralTab;
use WPDesk\FCF\Free\Settings\Tab\LogicTab;
use WPDesk\FCF\Free\Settings\Tab\PricingTab;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Option\LabelOption;
use WPDesk\FCF\Pro\Settings\Option\DefaultOption;
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
use WPDesk\FCF\Pro\Settings\Option\PricingEnabledOption;
use WPDesk\FCF\Pro\Settings\Option\PricingValueOption;
use WPDesk\FCF\Pro\Settings\Option\PricingInfoOption;

/**
 * Supports field type settings.
 */
class TextareaType extends DefaultTextareaType implements TypeInterface {

	/**
	 * Returns list of options for field settings.
	 *
	 * @return OptionInterface[] List of option fields.
	 */
	public function get_options_objects(): array {
		$options = array_merge(
			parent::get_options_objects(),
			[
				LogicTab::TAB_NAME   => [
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
				PricingTab::TAB_NAME => [
					PricingEnabledOption::FIELD_NAME => new PricingEnabledOption(),
					PricingValueOption::FIELD_NAME   => new PricingValueOption(),
					PricingInfoOption::FIELD_NAME    => new PricingInfoOption(),
				],
			]
		);

		$index = array_search( LabelOption::FIELD_NAME, array_keys( $options[ GeneralTab::TAB_NAME ] ), true );
		$index = ( $index === false ) ? count( $options[ GeneralTab::TAB_NAME ] ) : ( $index + 1 );

		$options[ GeneralTab::TAB_NAME ] = array_merge(
			array_slice( $options[ GeneralTab::TAB_NAME ], 0, $index ),
			[ DefaultOption::FIELD_NAME => new DefaultOption() ],
			array_slice( $options[ GeneralTab::TAB_NAME ], $index )
		);

		return $options;
	}
}
