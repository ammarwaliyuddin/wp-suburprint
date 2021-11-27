<?php

/**
 * Validation for Datepicker field.
 *
 * Class Flexible_Checkout_Fields_Pro_Datepicker_Validation
 */
class Flexible_Checkout_Fields_Pro_Datepicker_Validation implements \FCFProVendor\WPDesk\PluginBuilder\Plugin\HookablePluginDependant {

	use \FCFProVendor\WPDesk\PluginBuilder\Plugin\PluginAccess;

	const FIELD_TYPE = 'datepicker';

	const DATE_FORMAT_PARTS = array(
		'dd' => 'd',
		'mm' => 'm',
		'yy' => 'Y',
	);

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'flexible_checkout_fields_validate_' . self::FIELD_TYPE, array( $this, 'validate_field_type' ), 10, 2 );
	}

	/**
	 * Validates field value and shows error notices.
	 *
	 * @param string $value Posted field value.
	 * @param array  $field_data Field settings.
	 */
	public function validate_field_type( $value, $field_data = array() ) {
		if ( ! $this->validate_date_format( $value, $field_data ) ) {
			wc_add_notice(
				sprintf(
					/* translators: %s: field label */
					__( '%s is not a valid date.', 'flexible-checkout-fields-pro' ),
					sprintf( '<strong>%s</strong>', esc_html( $field_data['label'] ) )
				),
				'error'
			);
		} else if ( ! $this->validate_days_before( $value, $field_data ) ) {
			wc_add_notice(
				sprintf(
					/* translators: %s: field label */
					__( '%s is too early.', 'flexible-checkout-fields-pro' ),
					sprintf( '<strong>%s</strong>', esc_html( $field_data['label'] ) )
				),
				'error'
			);
		} else if ( ! $this->validate_days_after( $value, $field_data ) ) {
			wc_add_notice(
				sprintf(
					/* translators: %s: field label */
					__( '%s is too late.', 'flexible-checkout-fields-pro' ),
					sprintf( '<strong>%s</strong>', esc_html( $field_data['label'] ) )
				),
				'error'
			);
		}
	}

	/**
	 * Checks if date format is valid.
	 *
	 * @param  string $field_value Posted field value.
	 * @param  array  $field_data Field settings.
	 *
	 * @return bool Whether value is valid.
	 */
	private function validate_date_format( $field_value, $field_data ) {
		if ( ! isset( $field_data['date_format'] ) || empty( $field_data['date_format'] ) ) {
			return true;
		}

		$date_format = str_replace(
			array_keys( self::DATE_FORMAT_PARTS ),
			array_values( self::DATE_FORMAT_PARTS ),
			$field_data['date_format']
		);
		return gmdate( $date_format, strtotime( $field_value ) ) === $field_value;
	}


	/**
	 * Checks if date is not earlier than allowed.
	 *
	 * @param  string $field_value Posted field value.
	 * @param  array  $field_data Field settings.
	 *
	 * @return bool Whether value is valid.
	 */
	private function validate_days_before( $field_value, $field_data ) {
		if ( ! isset( $field_data['days_before'] ) || ( $field_data['days_before'] === '' ) ) {
			return true;
		}

		$date_min = strtotime( sprintf( '%s -%d days', gmdate( 'Y-m-d' ), $field_data['days_before'] ) );
		return strtotime( $field_value ) >= $date_min;
	}


	/**
	 * Checks that date is not later than allowed.
	 *
	 * @param  string $field_value Posted field value.
	 * @param  array  $field_data Field settings.
	 *
	 * @return bool Whether value is valid.
	 */
	private function validate_days_after( $field_value, $field_data ) {
		if ( ! isset( $field_data['days_after'] ) || ( $field_data['days_after'] === '' ) ) {
			return true;
		}

		$date_max = strtotime( sprintf( '%s +%d days', gmdate( 'Y-m-d' ), $field_data['days_after'] ) );
		return strtotime( $field_value ) <= $date_max;
	}

}
