<?php
/**
 * Support fields type for Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing\Field;

use WPDesk\FCF\Pro\Pricing\Settings;

/**
 * FieldInterface class for Pricing.
 */
interface FieldInterface {

	/**
	 * Checks if pricing is enabled for field.
	 *
	 * @return bool Status of pricing for field.
	 */
	public function is_pricing_enabled();

	/**
	 * Returns field name.
	 *
	 * @return string Key of field.
	 */
	public function get_field_name();

	/**
	 * Returns field label.
	 *
	 * @return string Label of field.
	 */
	public function get_field_label();

	/**
	 * Returns options for field.
	 *
	 * @return array List of options (array keys are values, array values are labels).
	 */
	public function get_field_options();

	/**
	 * Returns pricing types for field.
	 *
	 * @return array Types of pricing.
	 */
	public function get_field_pricing_types();

	/**
	 * Returns available options of price for field.
	 *
	 * @return array List of options.
	 */
	public function get_options_for_price_values();

	/**
	 * Returns field label for field settings.
	 *
	 * @return string Format for sprintf() function.
	 */
	public function get_field_label_for_settings();

	/**
	 * Returns field label for WooCommerce Fees.
	 *
	 * @param string|array $field_value Posted field value.
	 * @param string       $default_value Default field value.
	 *
	 * @return string Label of field with optionally field value.
	 */
	public function get_field_label_for_fee( $field_value, string $default_value );

	/**
	 * Adds information about price in field args.
	 *
	 * @param array  $args Settings of field.
	 * @param string $value Value of field.
	 * @param string $label Label of field.
	 *
	 * @return array Data of field.
	 */
	public function add_price_in_field_label( array $args, string $value, string $label );

	/**
	 * Checks if current field value matches required value.
	 *
	 * @param string       $option_value Required field value.
	 * @param string|array $current_value Current value of field.
	 *
	 * @return bool Status of field value.
	 */
	public function is_valid_field_value( string $option_value, $current_value );
}
