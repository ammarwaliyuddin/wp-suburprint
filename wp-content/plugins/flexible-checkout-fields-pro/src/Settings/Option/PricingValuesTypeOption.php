<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Option;

use WPDesk\FCF\Free\Settings\Option\OptionAbstract;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Tab\PricingTab;

/**
 * Supports option settings for field.
 */
class PricingValuesTypeOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'type';

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
		return PricingTab::TAB_NAME;
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
		return __( 'Price basis', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns available values of option, if exists.
	 *
	 * @return array List of option values.
	 */
	public function get_values(): array {
		return [
			'fixed'                => __( 'Fixed', 'flexible-checkout-fields-pro' ),
			'percent_subtotal'     => __( 'Percentage of Subtotal (ex. VAT)', 'flexible-checkout-fields-pro' ),
			'percent_subtotal_tax' => __( 'Percentage of Subtotal (incl. VAT)', 'flexible-checkout-fields-pro' ),
			'percent_total'        => __( 'Percentage of Total', 'flexible-checkout-fields-pro' ),
		];
	}
}
