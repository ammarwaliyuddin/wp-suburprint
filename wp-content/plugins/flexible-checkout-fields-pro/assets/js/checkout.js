(function ($) {
	$(document).ready(function () {
		function fcf_fields_conditions() {
			$.each(fcf_conditions, function (index, value) {
				var user_chosen_shipping_method = '';
				var conditional_logic_logic_shipping_fields_action = '';
				var fcf_selected_shipping_method = '';
				var fcf_selected_shipping_method_no_zone = '';
				var conditional_logic_shipping_fields = '';

				var match = false;
				if (value.conditional_logic_fields_operator === 'and') {
					match = true;
				}
				$.each(value.conditional_logic_fields_rules, function (index_rule, value_rule) {
					var conditional_logic_fields_operator = value.conditional_logic_fields_operator;
					match = is_rule_matched(match, value_rule, conditional_logic_fields_operator);
				});

				$.each(fcf_shipping_conditions, function (shipping_index, shipping_value) {
					conditional_logic_shipping_fields = shipping_value.conditional_logic_shipping_fields;
					conditional_logic_logic_shipping_fields_action = shipping_value.conditional_logic_shipping_fields_action;
					var fcf_shipping_rules = shipping_value.conditional_logic_shipping_fields_rules;

					if (fcf_shipping_rules.length !== 0) {
						$.each(fcf_shipping_rules, function conditional_logic_shipping_fields_rules(index_rule, value_rule) {
							fcf_selected_shipping_method = value_rule.value;
							fcf_selected_shipping_method_no_zone = value_rule.no_zone_value;

							$.each($('input[name^="shipping_method"]:checked'), function () {
								user_chosen_shipping_method = $(this).val();
							});
							if (user_chosen_shipping_method.toLowerCase().indexOf(':fallback') >= 0) {
								user_chosen_shipping_method = user_chosen_shipping_method.split(':fallback')[0];
							}
						});
					}
				});
				var hidden = conditional_logic_fields_show_or_hide(
					conditional_logic_shipping_fields, user_chosen_shipping_method, fcf_selected_shipping_method,
					conditional_logic_logic_shipping_fields_action, value, match);
				var field_to_hide = $('#' + index + '_field');
				if (hidden) {
					$(field_to_hide).hide();
					$(field_to_hide).removeClass('validate-required');
					$(field_to_hide).find('input,select').attr('disabled', true);
					$(field_to_hide).addClass('fcf-hidden');
				} else {
					$(field_to_hide).show();
					$(field_to_hide).find('input,select').attr('disabled', false);
					$(field_to_hide).removeClass('fcf-hidden');
				}
				$(field_to_hide).trigger('change');
			});
		}

		function conditional_logic_fields_operator_value(value, match, field_matched ) {
			if ( field_matched === undefined ) {
				field_matched = false;
			}
			if (field_matched === true && value === 'or') {
				return true;
			}
			if (field_matched !== true && value === 'and') {
				return false;
			}

			return match = match || false;
		}

		function is_rule_matched(match, value_rule, value) {
			let field_value = fcf_field_value(value_rule.field);
			if (value_rule.condition === 'is') {
				if (field_value === value_rule.value) {
					match = conditional_logic_fields_operator_value(value, match, true);
				} else {
					match = conditional_logic_fields_operator_value(value, match, false);
				}
			}

			return match;
		}

		function conditional_logic_fields_show_or_hide(
			conditional_logic_shipping_fields, user_chosen_shipping_method, fcf_selected_shipping_method,
			conditional_logic_logic_shipping_fields_action, value, match) {

			var hidden = true;

			if (user_chosen_shipping_method.match(fcf_selected_shipping_method) && conditional_logic_logic_shipping_fields_action === 'hide') {
					if (value.conditional_logic_fields_action === 'hide') {
						hidden = match;
					}
				}
				if (user_chosen_shipping_method.match(fcf_selected_shipping_method) && conditional_logic_logic_shipping_fields_action === 'show') {
					if (value.conditional_logic_fields_action === 'show') {
						hidden = !match;
					}
				}

				if (value.conditional_logic_fields_action === 'hide') {
					hidden = match;
				}
				if (value.conditional_logic_fields_action === 'show') {
					hidden = !match;
				}


			return hidden;
		}

		$(document).on('change', 'input', function () {
			fcf_fields_conditions();
		});
		$(document).on('change', 'select', function () {
			fcf_fields_conditions();
		});
		$('body').on('updated_checkout', function () {
			fcf_fields_conditions();
		});

		fcf_fields_conditions();
	});
})(jQuery);


/**
 * Returns field value.
 * Hidden fields has empty value.
 *
 * @param {string} field_name
 * @returns {string}
 */
function fcf_field_value( field_name ) {
	let field_value = '';
	let input_field = jQuery('input[name=' + field_name + ']');
	let select_field = jQuery('select[name=' + field_name + ']');
	if ( jQuery(input_field).is(':visible') || jQuery(select_field).is(':visible') ) {
		if (jQuery(input_field).attr('type') === 'radio') {
			field_value = jQuery('input[name=' + field_name + ']:checked').val();
		} else if (jQuery(input_field).attr('type') === 'checkbox') {
			field_value = 'unchecked';
			if (jQuery(input_field).is(':checked')) {
				field_value = 'checked';
			}
		} else {
			field_value = jQuery(input_field).val();
			if (typeof field_value === 'undefined') {
				field_value = jQuery(select_field).val();
			}
		}
	}
	return field_value;
}