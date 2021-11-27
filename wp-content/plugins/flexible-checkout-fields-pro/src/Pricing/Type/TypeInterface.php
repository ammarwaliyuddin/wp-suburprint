<?php
/**
 * Support type of Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing\Type;

use WPDesk\FCF\Pro\Pricing\Field\FieldInterface;

/**
 * TypeInterface class for Pricing.
 */
interface TypeInterface {

	/**
	 * Returns settings of pricing option.
	 *
	 * @return array Option settings.
	 */
	public function get_option_data();

	/**
	 * Returns field value of pricing option.
	 *
	 * @return string Option value.
	 */
	public function get_option_value();

	/**
	 * Returns tax class of pricing option.
	 *
	 * @return string Tax class (empty string means standard tax class).
	 */
	public function get_tax_class();

	/**
	 * Generates price as information for field label.
	 *
	 * @return string|null Price information.
	 */
	public function get_price_for_label();

	/**
	 * Calculates price based on price settings.
	 *
	 * @return float Price value.
	 */
	public function get_calculated_price(): float;

	/**
	 * Calculates percentage of net value for fee.
	 *
	 * @param float $base_price Gross amount of base price to calculation.
	 *
	 * @return float Percentage of net value.
	 */
	public function get_percentage_of_fee_net_value( float $base_price ): float;
}
