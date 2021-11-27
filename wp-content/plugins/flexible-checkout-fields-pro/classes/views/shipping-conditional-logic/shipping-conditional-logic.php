<?php
/**
 * @var $shipping_zones array
 * @var $shipping_methods_no_zone array array
 */
?>
<div class="shipping-fields-enabled">
	<?php
	$checked = '';
	$style = 'style=display:none';
	if ( isset( $settings[$key][$name]['conditional_logic_shipping_fields'] ) && $settings[$key][$name]['conditional_logic_shipping_fields'] === '1' ) {
		$checked = 'checked';
		$style = '';
	}
	$flexible_shipping_methods = Shipping_Zones_Repository::get_flexible_shipping_methods();
	$no_shipping_zones = Shipping_Zones_Repository::get_active_shipping_methods_with_no_zone();
	?>
	<label>
		<input data-qa-id="conditional-logic-shipping-fields" class="conditional-logic-shipping-fields conditional-logic-shipping-fields-enabled shipping-fields_<?php echo $key?>_<?php echo $name ?>" type="checkbox" data-class="<?php echo $key ?>_<?php echo $name ?>" name="inspire_checkout_fields[settings][<?php echo $key ?>][<?php echo $name ?>][conditional_logic_shipping_fields]" value="1" <?php echo $checked; ?>>
		<?php echo __( 'Enable Shipping Methods Logic', 'flexible-checkout-fields-pro' ) ?>
	</label>
</div>

<div class="fields-conditional-logic-shipping-fields" <?php echo $style; ?>>
	<div class="options">
		<select data-qa-id="conditional-logic-fields-shipping-action" name="inspire_checkout_fields[settings][<?php echo $key ?>][<?php echo $name ?>][conditional_logic_shipping_fields_action]">
			<?php
			$selected = '';
			if ( isset($settings[$key][$name]['conditional_logic_shipping_fields_action']) && $settings[$key][$name]['conditional_logic_shipping_fields_action'] == 'show' ) {
				$selected = ' selected';
			}
			?>
			<option value="show" <?php echo $selected; ?>><?php echo __( 'Show this field if', 'flexible-checkout-fields-pro' ); ?></option>
			<?php
			$selected = '';
			if ( isset($settings[$key][$name]['conditional_logic_shipping_fields_action']) && $settings[$key][$name]['conditional_logic_shipping_fields_action'] == 'hide' ) {
				$selected = ' selected';
			}
			?>
			<option value="hide" <?php echo $selected; ?>><?php echo __( 'Hide this field if', 'flexible-checkout-fields-pro' ); ?></option>
		</select>
		<select disabled="disabled" data-qa-id="field-conditional-logic-shipping-fields-operator" name="inspire_checkout_fields[settings][<?php echo $key ?>][<?php echo $name ?>][conditional_logic_shipping_fields_operator]">
			<?php
			$selected = '';
			if ( isset($settings[$key][$name]['conditional_logic_shipping_fields_operator']) && $settings[$key][$name]['conditional_logic_shipping_fields_operator'] == 'or' ) {
				$selected = ' selected';
			}
			?>
			<option value="or" <?php echo $selected; ?>><?php echo __( 'One or more rules match (or)', 'flexible-checkout-fields-pro' ); ?></option>
		</select>
	</div>
	<div class="rules">
		<?php if ( isset( $settings[$key][$name]['conditional_logic_shipping_fields_rules'] ) ) : ?>
			<?php $count = 0; ?>
			<?php foreach ( $settings[$key][$name]['conditional_logic_shipping_fields_rules'] as $rule ) : ?>
				<?php $count++; ?>
				<div class="rule">
					<fieldset>
						<legend><?php printf( __( 'Rule #%s', 'flexible-checkout-fields-pro' ), $count ); ?></legend>
						<div class="zones">
							<div>
								<div class="rule-title"><p><?php echo __('Shipping Zone', 'flexible-checkout-fields-pro') ?></p></div>
								<select required="required" class="zone-rule zone-rules_<?php echo $count; ?> <?php echo $name ?>_<?php echo $count; ?>" data-qa-id="field-conditional-logic-shipping-fields-rules-field" data-zone-class="<?php echo $name ?>_<?php echo $count; ?>" name="inspire_checkout_fields[settings][<?php echo $key ?>][<?php echo $name ?>][conditional_logic_shipping_fields_rules][<?php echo $count; ?>][field]">
									<?php $selected = ''; ?>
									<?php $selected_zone_id = '' ?>
									<?php if ( ! isset( $rule['field'] ) || $rule['field'] === '' ) $selected = 'selected'; ?>
									<option <?php echo $selected; ?> value="" disabled><?php echo __('No shipping zone selected', 'flexible-checkout-fields-pro') ?></option>
									<?php if ( count( $no_shipping_zones ) > 0 ) : ?>
										<option <?php echo $selected; ?> value="no_shipping_zones"><?php echo __('No Shipping Zones or Global Methods', 'flexible-checkout-fields-pro') ?></option>
									<?php endif; ?>
									<!--Shipping Zones-->
									<?php foreach ( $shipping_zones as $zone_id => $zone ) : ?>
										<?php if ( $zone_id !== $name ) : ?>
											<?php $selected = ''; ?>
											<?php if ( isset( $rule['field'] ) && $rule['field'] == $zone_id ) $selected = 'selected'; ?>
											<?php if ( '' !== $selected ) $selected_zone_id = $zone_id; ?>
											<option class="zone" value="<?php echo esc_attr($zone_id); ?>" <?php echo $selected; ?> ><?php echo $zone; ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="methods">
							<div>
								<?php $shipping_methods = Shipping_Zones_Repository::get_list_of_shipping_methods_name_by_zone( (int) $selected_zone_id ); ?>
								<div class="rule-title"><p><?php echo __('Shipping Method', 'flexible-checkout-fields-pro') ?></p></div>
								<select required="required" class="fcf_options methods-rules_<?php echo $count ?> methods shipping_methods method_<?php echo $name ?>_<?php echo $count; ?>" data-methods-class="<?php echo $name ?>_<?php echo $count; ?>" data-methods-count="<?php echo $count; ?>" data-qa-id="field-conditional-logic-shipping-fields-methods-field" name="inspire_checkout_fields[settings][<?php echo $key ?>][<?php echo $name ?>][conditional_logic_shipping_fields_rules][<?php echo $count; ?>][value]">
									<?php if ( empty( $shipping_methods ) ) : ?>
									<?php $selected = ''; ?><?php $no_method_selected = ''; ?><?php $disabled = 'disabled' ?>
									<?php if ( ! isset( $rule['value'] ) || $rule['value'] === '' ) $selected = 'selected'; $no_method_selected = __('No Shipping Method was selected', 'flexible-checkout-fields-pro'); $disabled = '' ?>
									<option <?php echo $selected; ?> value="" <?php echo $disabled ?>><?php echo  $no_method_selected?></option>
									<?php endif; ?>
									<?php $flexible_shipping_methods_id = array(); ?>
									<!--Shipping Methods-->
									<?php if ( ! empty( $shipping_methods ) ) : ?>
										<?php foreach ( $shipping_methods as $shipping_method_id => $method_name ) : ?>
											<?php if ( $shipping_method_id !== $name ) : ?>
												<?php $selected = ''; ?>
												<?php if ( strstr($shipping_method_id, 'flexible_shipping' ) ) : ?>
													<?php $method_id = str_replace(':', '_', $shipping_method_id);
													$flexible_shipping_methods_id[ $shipping_method_id ] = $method_id; ?>
												<?php endif; ?>
												<?php if ( isset( $rule['value'] ) && $rule['value'] == $shipping_method_id ) $selected = 'selected'; ?>
												<?php if (strtolower($method_name) !== 'flexible shipping') : ?>
													<?php if ( isset( $method_name ) ) : ?>
														<option class="zone" value="<?php echo $shipping_method_id; ?>" <?php echo $selected; ?> ><?php echo $method_name; ?></option>
													<?php else : ?>
														<option class="no-shipping-method" value="no_shipping_method" selected ><?php echo __('Shipping Method was disabled or deleted', 'flexible-checkout-fields-pro'); ?></option>
													<?php endif; ?>
												<?php endif; ?>
											<?php else : ?>
												<option value="no_methods" class="no-methods"><?php echo __('No methods found for this zone', 'flexible-checkout-fields-pro') ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
									<?php endif; ?>
									<!--Shipping methods with no zone-->
									<?php if ( '' === $selected_zone_id ) : ?>
									<?php if( ! empty( $no_shipping_zones ) ) : ?>
										<?php foreach($no_shipping_zones as $no_shipping_zone_methods => $no_shipping_zone_method) : ?>
											<?php if ( $no_shipping_zone_methods !== $name ) : ?>
												<?php $selected = ''; ?>
												<?php if ( isset( $rule['value'] ) && $rule['value'] == $no_shipping_zone_methods) $selected = 'selected'; ?>
												<?php if ( $no_shipping_zone_methods !== 'flexible_shipping_info' ) : ?>
												<option class="no-zone-method" value="<?php echo $no_shipping_zone_methods ?>" <?php echo $selected; ?> ><?php echo $no_shipping_zone_method; ?></option>
												<?php endif; ?>
										<?php endif; ?>
										<?php endforeach; ?>
										<?php endif; ?>
									<?php endif; ?>
									<!--Flexible Shipping Methods-->
									<?php if ( ! empty ( $flexible_shipping_methods ) ) : ?>
										<?php foreach ( $flexible_shipping_methods as $flexible_shipping_method_id => $flexible_shipping_method ) : ?>
											<?php foreach ( $flexible_shipping_methods_id as $ids => $id ) : ?>
												<?php $method_selected = ''; ?>
												<?php if ( isset( $rule['value'] ) && $rule['value'] == $flexible_shipping_method_id ) $method_selected = 'selected'; ?>
												<?php $ids_compare = explode( '_', $flexible_shipping_method_id ); ?>
												<?php if ( strstr( $id, $ids_compare[2] ) ) : ?>
													<option class="zone" value="<?php echo $flexible_shipping_method_id; ?>" <?php echo $method_selected; ?> ><?php echo $flexible_shipping_method; ?></option>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>

						</div>

						<div class="delete_rule">
							<a class="delete_rule" href="#delete_rule"><?php echo __( 'Delete rule', 'flexible-checkout-fields-pro' ); ?></a>
						</div>
					</fieldset>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php $count++; ?>
		<div class="add_rule_button shipping_methods">
			<a class="button add_rule_shipping_fields_button" href="#add_rule" data-count="<?php echo $count; ?>" data-key="<?php echo $key; ?>" data-name="<?php echo $name; ?>"><?php echo __( 'Add rule', 'flexible-checkout-fields-pro' ); ?></a>
		</div>
	</div>

</div>
