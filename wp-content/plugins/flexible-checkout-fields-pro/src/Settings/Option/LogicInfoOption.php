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
class LogicInfoOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'conditional_logic_info';

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
		return self::FIELD_TYPE_INFO;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		$url = esc_url( apply_filters( 'flexible_checkout_fields/short_url', '#', 'fcf-settings-field-tab-logic-docs' ) );
		return sprintf(
			/* translators: %1$s: anchor opening tag, %2$s: anchor closing tag */
			__( 'You can combine rules from different logic groups. In this case the logic will work when the conditions from each of the active groups are met. %1$sRead more%2$s', 'flexible-checkout-fields-pro' ),
			'<a href="' . $url . '" target="_blank" class="fcfArrowLink">',
			'</a>'
		);
	}
}
