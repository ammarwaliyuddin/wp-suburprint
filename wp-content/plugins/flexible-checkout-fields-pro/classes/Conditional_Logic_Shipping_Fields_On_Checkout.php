<?php

/**
 * Class Conditional_Logic_Shipping_Fields_On_Checkout
 *
 * Hide fields on checkout page based on "Add new shipping rule"
 */
class Conditional_Logic_Shipping_Fields_On_Checkout extends Conditional_Logic_Rules {

	const CONDITIONAL_LOGIC_FIELDS_OPERATOR = 'conditional_logic_shipping_fields_operator';
	const CONDITIONAL_LOGIC_FIELDS_ACTION = 'conditional_logic_shipping_fields_action';
	const CONDITIONAL_LOGIC_FIELDS_RULES = 'conditional_logic_shipping_fields_rules';
	const CONDITIONAL_LOGIC_FIELDS = 'conditional_logic_shipping_fields';
	/**
	 * @param array $checkout_fields Order fields on checkout page
	 * @param array $sections Checkout fields sections
	 *
	 * @return array fields on checkout page [ 'billing' => 'billing_first_name' => [ 'label' => 'Name', 'required' => false ] ]
	 */
	public function conditional_logic_shipping_fields_hide_on_checkout_page( array $checkout_fields, $sections ) {

		$hidden_fields = $this->create_hidden_fields_on_checkout_page( $sections );

		return $this->remove_required_rule_from_hidden_fields( $checkout_fields, $hidden_fields );
	}
}
