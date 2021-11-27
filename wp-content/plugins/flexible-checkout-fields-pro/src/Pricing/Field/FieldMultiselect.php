<?php
/**
 * Supports "Multi-select" field type for Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing\Field;

use WPDesk\FCF\Pro\Pricing\Field\FieldRadio;

/**
 * FieldMultiselect class for Pricing.
 */
class FieldMultiselect extends FieldRadio {

	/**
	 * Returns field label for WooCommerce Fees.
	 *
	 * @param string|array $field_value Posted field value.
	 * @param string       $default_value Default field value.
	 *
	 * @return string Label of field with optionally field value.
	 */
	public function get_field_label_for_fee( $field_value, string $default_value ) {
		$options = $this->get_field_options();
		return sprintf( '%s: %s', $this->get_field_label(), $options[ $default_value ] ?? $field_value );
	}

	/**
	 * Checks if current field value matches
	 * required value.
	 *
	 * @param string       $option_value Required field value.
	 * @param string|array $current_value Current value of field.
	 *
	 * @return bool Status of field value.
	 */
	public function is_valid_field_value( string $option_value, $current_value ) {
		if ( ! is_array( $current_value ) ) {
			return false;
		}
		return ( in_array( $option_value, $current_value, true ) );
	}

}
