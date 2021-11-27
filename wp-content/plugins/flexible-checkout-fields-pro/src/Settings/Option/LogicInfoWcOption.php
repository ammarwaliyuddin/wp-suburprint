<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Option;

use WPDesk\FCF\Pro\Settings\Option\LogicInfoOption;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Tab\LogicTab;

/**
 * Supports option settings for field.
 */
class LogicInfoWcOption extends LogicInfoOption implements OptionInterface {

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		$url_read     = esc_url( apply_filters( 'flexible_checkout_fields/short_url', '#', 'fcf-settings-field-tab-logic-docs' ) );
		$url_required = esc_url( apply_filters( 'flexible_checkout_fields/short_url', '#', 'fcf-settings-field-tab-logic-wc-docs' ) );
		return sprintf(
			/* translators:  %1$s: anchor opening tag, %2$s: anchor closing tag, %3$s: anchor opening tag, %4$s: anchor closing tag */
			__( 'You can combine rules from different logic groups. In this case the logic will work when the conditions from each of the active groups are met. WooCommerce forces this field to be %1$srequired%2$s. Make sure hiding is possible and will not cause a validation error. %3$sRead more%4$s', 'flexible-checkout-fields-pro' ),
			'<a href="' . $url_required . '" target="_blank">',
			'</a>',
			'<a href="' . $url_read . '" target="_blank" class="fcfArrowLink">',
			'</a>'
		);
	}
}
