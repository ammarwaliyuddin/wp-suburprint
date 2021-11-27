<?php

/**
 * Class Conditional_logic_Rules
 *
 * Rules based in settings for FCF PRO
 */
class Conditional_Logic_Rules {

	/**@var array $settings Flexible checkout fields settings*/
	protected $settings;
	/**@var array $hidden_fields*/
	protected $hidden_fields = array();

	public function __construct( array $settings ) {
		$this->settings = $settings;
	}

	/**
	 * .
	 *
	 * @param array $rule_field .
	 * @param string $field_value .
	 *
	 * @return bool
	 */
	private function value_match( $rule_field, $field_value ) {
		return ( isset( $rule_field['value'] ) ? $rule_field['value'] : '' ) === $field_value;
	}

	/**
	 * Conditional logic settings "All rules match (and)"
	 *
	 * @param array $rule_field [ 'key' => 'value' ]
	 * @param string $field_value
	 * @param bool   $matched
	 *
	 * @return bool
	 */
	protected function conditional_logic_operator_and( array $rule_field, $field_value, $matched ) {
		return $this->value_match( $rule_field, $field_value ) && $matched;
	}

	/**
	 * Conditional logic settings "One or more rules match (or)"
	 *
	 * @param array $rule_field [ 'key' => 'value' ]
	 * @param string $field_value
	 * @param bool   $matched
	 *
	 * @return bool
	 */
	protected function conditional_logic_operator_or( array $rule_field, $field_value, $matched ) {
		return $this->value_match( $rule_field, $field_value ) || $matched;
	}

	/**
	 * Conditional logic fields rules
	 *
	 * @param array $field
	 * @param $fields_operator
	 * @param $fields_rules
	 *
	 * @return bool
	 */
	protected function conditional_logic_rules( array $field, $fields_operator, $fields_rules ) {
		$matched = true;
		if ( $field[$fields_operator] === 'or' ) {
			$matched = false;
		}

		foreach ( $field[$fields_rules] as $rule_field )  {
			$field_value = '';
			if ( isset( $rule_field['field'] ) && isset( $_POST[$rule_field['field']] ) ) {
				$field_value = $_POST[$rule_field['field']];
			}
			if ( isset( $rule_field['value'] ) && isset( $_POST['shipping_method'][0] ) && $rule_field['value'] === $_POST['shipping_method'][0] ) {
				$field_value = $_POST['shipping_method'][0];
			}
			if ( isset( $rule_field['no_zone_value'] ) && isset( $_POST['shipping_method'][0] )) {
				$result = explode( ':', $_POST['shipping_method'][0] );
				$field_value = $result[0];
				$rule_field['value'] = $rule_field['no_zone_value'];
			}
			if ( isset( $rule_field['value'] ) && $rule_field['value'] === 'unchecked' && $field_value === '' ) {
				$field_value = 'unchecked';
			}
			if ( isset( $rule_field['value'] ) && $rule_field['value'] === 'checked' && $field_value !== '' ) {
				$field_value = 'checked';
			}

			if ( $field[$fields_operator] === 'and' ) {
				$matched = $this->conditional_logic_operator_and( $rule_field, $field_value, $matched );
			}
			if ( $field[$fields_operator] === 'or' ) {
				$matched = $this->conditional_logic_operator_or( $rule_field, $field_value, $matched );
			}
		}

		return $matched;
	}
	/**
	 * Remove required rule from hidden fields
	 *
	 * @param array $checkout_fields
	 *
	 * @param array $hidden_fields
	 *
	 * @return array Order fields on checkout page
	 */
	protected function remove_required_rule_from_hidden_fields( array $checkout_fields, array $hidden_fields) {
		foreach ( $checkout_fields as $section => $fields ) {
			foreach ( $fields as $field_name => $field ) {
				if ( in_array( $field_name, $hidden_fields )) {
					if ( ! empty( $_POST ) ) {
						$checkout_fields[$section][$field_name]['required'] = false;
					}
				}
			}
		}

		return $checkout_fields;
	}

	/**
	 * Create Hidden Fields on checkout page for Shipping Conditional Logic
	 *
	 * @param array $sections
	 *
	 * @return array "Hidden Fields" on checkout page [ 0 => 'billing_first_name' ]
	 */
	public function create_hidden_fields_on_checkout_page( array $sections ) {
		foreach ( $sections as $section => $section_data ) {
			if ( isset( $this->settings[$section_data['section']] ) && is_array( $this->settings[$section_data['section']] ) ) {
				foreach ( $this->settings[$section_data['section']] as $key => $field ) {
					if ( isset( $field[static::CONDITIONAL_LOGIC_FIELDS] ) && $field[static::CONDITIONAL_LOGIC_FIELDS] === '1' ) {
						$hidden = false;
						$matched = $this->conditional_logic_rules(
							$field,
							static::CONDITIONAL_LOGIC_FIELDS_OPERATOR,
							static::CONDITIONAL_LOGIC_FIELDS_RULES
						);
						if ( $matched ) {
							if ( $field[ static::CONDITIONAL_LOGIC_FIELDS_ACTION ] === 'show') {
								$hidden = false;
							}
							if ( $field[ static::CONDITIONAL_LOGIC_FIELDS_ACTION ] === 'hide') {
								$hidden = true;
							}
						}
						else {
							if ( $field[ static::CONDITIONAL_LOGIC_FIELDS_ACTION ] === 'show') {
								$hidden = true;
							}
							if ( $field[ static::CONDITIONAL_LOGIC_FIELDS_ACTION ] === 'hide') {
								$hidden = false;
							}
						}
						if ( $hidden ) {
							$this->hidden_fields[] = $key;
						}
					}
				}
			}
		}

		return $this->hidden_fields;
	}
}
