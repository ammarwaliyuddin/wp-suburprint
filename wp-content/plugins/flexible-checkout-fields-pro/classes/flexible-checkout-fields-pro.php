<?php

require_once 'Conditional_Logic_Shipping_Fields_On_Checkout.php';
require_once 'Conditional_Logic_Fields_On_Checkout.php';

/**
 * Class Flexible_Checkout_Fields_Pro
 */
class Flexible_Checkout_Fields_Pro implements \FCFProVendor\WPDesk\PluginBuilder\Plugin\HookablePluginDependant {

	use \FCFProVendor\WPDesk\PluginBuilder\Plugin\PluginAccess;

    const FIELD_TYPE_NAME = 'name';
	const FIELD_TYPE_PLACEHOLDER_LABEL = 'placeholder_label';
	const FIELD_TYPE_LABEL_IS_REQUIRED = 'label_is_required';
	const FIELD_TYPE_HAS_DEFAULT_VALUE = 'has_default_value';
	const FIELD_TYPE_DISABLE_PLACEHOLDER = 'disable_placeholder';
	const FIELD_TYPE_HAS_OPTIONS = 'has_options';
	const FIELD_TYPE_HAS_REQUIRED = 'has_required';
	const FIELD_TYPE_EXCLUDE_IN_ADMIN = 'exclude_in_admin';
	const FIELD_TYPE_EXCLUDE_FOR_USER = 'exclude_for_user';

	/**
	 * Plugin.
	 *
	 * @var Flexible_Checkout_Fields_Pro_Plugin
	 */
	private $plugin;

	/**
	 * List of keys for unavailable field sections, used in actions woocommerce_{$section_name}.
	 *
	 * @var string[]
	 */
	private $unavailable_sections = [];

	/**
	 * Flexible_Checkout_Fields_Pro constructor.
	 *
	 * @param Flexible_Checkout_Fields_Pro_Plugin $plugin Plugin.
	 */
	public function __construct( Flexible_Checkout_Fields_Pro_Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Fires hooks
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'flexible_checkout_fields_fields', array( $this, 'flexible_checkout_fields_fields' ) );
		add_filter( 'flexible_checkout_fields_user_fields', array( $this, 'flexible_checkout_fields_user_fields' ), 10, 3 );
		add_filter( 'flexible_checkout_fields_custom_attributes', array( $this, 'flexible_checkout_fields_custom_attributes' ), 10, 2 );
		add_filter( 'flexible_checkout_fields_sections', array( $this, 'flexible_checkout_fields_sections' ), 10, 2 );
		add_filter( 'flexible_checkout_fields_all_sections', array( $this, 'flexible_checkout_fields_all_sections' ), 10, 2 );
		add_action( 'init', array( $this, 'init_sections' ) );
		add_action( 'flexible_checkout_fields_checkout_update_order_meta', array( $this, 'flexible_checkout_fields_checkout_update_order_meta' ) );
		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'woocommerce_admin_order_data_after_shipping_address' ), 9999999 );
		add_filter( 'woocommerce_checkout_fields', array( $this, 'conditional_logic_fields_hide'), 99999999, 1 );
		add_filter( 'woocommerce_checkout_fields', array( $this, 'conditional_logic_shipping_fields_hide'), 99999999, 1 );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
		add_action( 'wp_footer', array( $this, 'fcf_shipping_fields_wp_footer' ) );
		add_action( 'woocommerce_checkout_process', array( $this, 'set_unavailable_sections_for_checkout_process' ) );
	}

	/**
     * Settings must be always array.
     *
	 * @param mixed $settings
	 *
	 * @return array
	 */
	private function fallback_settings_to_array( $settings ) {
		if ( ! is_array( $settings ) ) {
			$settings = array();
		}
		return $settings;
    }

	public function get_settings() {
		return $this->fallback_settings_to_array( get_option( 'inspire_checkout_fields_settings', array() ) );
	}

	public function get_section_settings() {
		return $this->fallback_settings_to_array( get_option('inspire_checkout_fields_section_settings', array() ) );
	}

	public function init() {
		$settings = $this->get_settings();
		if ( get_option( 'flexible_checkout_fields_init_checkboxes', '0' ) == '0' ) {
			$checkboxes = get_option( 'inspire_checkout_fields_checkboxes', array() );
			foreach ( $checkboxes as $checkbox ) {
				if ( empty( $settings['after_customer_details'] ) ) {
					$settings['after_customer_details'] = array();
				}
				$settings['review_order_before_submit'][$checkbox['name']] = array(
					'custom_field' 	=> '1',
					'name'			=> $checkbox['name'],
					'visible'		=> '0',
					'required'		=> $checkbox['required'],
					'label'			=> $checkbox['label'],
					'placeholder'	=> __( 'Yes', 'flexible-checkout-fields-pro' ),
					'class'			=> $checkbox['class'],
					'type'			=> 'inspirecheckbox',
					'file_types'	=> '',
					'file_size'		=> '',
					'date_format'	=> 'dd.mm.yy',
					'days_before'	=> '0',
					'days_after'	=> '',
				);
				update_option( 'inspire_checkout_fields_settings', $settings );
				update_option( 'inspire_checkout_fields_review_order_before_submit', '1' );
			}
			update_option( 'flexible_checkout_fields_init_checkboxes', '1' );
		}
	}

	public function woocommerce_admin_order_data_after_shipping_address( $order ) {

		$additional_fields = false;

		$sections = $this->flexible_checkout_fields_sections( array(), false );
		$settings = $this->get_settings();

		$flexible_checkout_fields = flexible_checkout_fields();
		$checkout_field_type = $flexible_checkout_fields->get_fields();

		foreach ( $sections as $section => $section_data ) {
			if ( isset( $settings[$section_data['section']] ) && is_array( $settings[$section_data['section']] ) ) {
				foreach ( $settings[$section_data['section']] as $key => $field ) {
					$value = wpdesk_get_order_meta( $order, '_'.$key, true );
					$additional_fields = true;
				}
			}
		}

		if ( $additional_fields != false ) {
			include( 'views/order-additional-fields.php' );
		}
	}

	public function flexible_checkout_fields_checkout_update_order_meta( $order_id ) {
		$sections = $this->flexible_checkout_fields_sections( array(), false );
		$settings = $this->get_settings();
		foreach ( $sections as $section => $section_data ) {
			if ( isset( $settings[$section_data['section']] ) && is_array( $settings[$section_data['section']] ) ) {
				foreach ( $settings[$section_data['section']] as $key=>$field ) {
					if ( isset( $_POST[$key]) ) {
						$value = wp_unslash( $_POST[$key] );
						wpdesk_update_order_meta( $order_id, '_'.$key, $value );
					}
				}
			}
		}
	}

	public function checkout_form_action() {

		$sections = $this->flexible_checkout_fields_sections( array(), false );

		$settings = $this->get_settings();

		$section_settings = $this->get_section_settings();

		$current_filter = current_filter();

		$fields = array();

		$checkout = WC()->checkout();

		if ( empty( $section_settings[$sections[$current_filter]['section']] ) ) {
			$section_settings = array();
		}
		else {
			$section_settings = $section_settings[$sections[$current_filter]['section']];
		}

		if ( isset( $settings[$sections[$current_filter]['section']] ) ) {
			$fields = apply_filters( 'flexible_chekout_fields_fields', $settings[$sections[$current_filter]['section']], $sections[$current_filter]['section'] );
		}

		if ( !empty( $fields ) && is_array( $fields ) ) {
			$args = array(
				'fields' 	=> $fields,
				'checkout'	=> $checkout,
				'section_settings'	=> $section_settings
			);
			echo $this->plugin->load_template( $sections[$current_filter]['section'], 'checkout/flexible-checkout-fields', $args );
		}

	}

	public function init_sections( $checkout ) {
		$sections = $this->flexible_checkout_fields_sections( array(), false );

		foreach ( $sections as $section => $section_data ) {
			add_action( $section, array( $this, 'checkout_form_action' ), 100 );
		}
	}

	public function flexible_checkout_fields_all_sections( $sections, $get_disabled = true ) {
		return $this->flexible_checkout_fields_sections( $sections, $get_disabled );
	}

	public function flexible_checkout_fields_sections( $sections, $get_disabled = false ) {

		$sections_add = array();

		$sections_add['woocommerce_checkout_before_customer_details'] = array(
			'section'        => 'before_customer_details',
			'tab'            => 'fields_before_customer_details',
			'tab_title'      => __( 'Before Customer Details', 'flexible-checkout-fields-pro' ),
			'title'             => __( 'Before Customer Details Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => true,
		);

		$sections_add['woocommerce_checkout_after_customer_details'] = array(
			'section'        => 'after_customer_details',
			'tab'            => 'fields_after_customer_details',
			'tab_title'      => __( 'After Customer Details', 'flexible-checkout-fields-pro' ),
			'title'          => __( 'After Customer Details Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => true,
		);

		$sections_add['woocommerce_before_checkout_billing_form'] = array(
			'section'        => 'before_checkout_billing_form',
			'tab'            => 'fields_before_checkout_billing_form',
			'tab_title'      => __( 'Before Billing Form', 'flexible-checkout-fields-pro' ),
			'title'          => __( 'Before Billing Form Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => true,
		);

		$sections_add['woocommerce_after_checkout_billing_form'] = array(
			'section'        => 'after_checkout_billing_form',
			'tab'            => 'fields_after_checkout_billing_form',
			'tab_title'      => __( 'After Billing Form', 'flexible-checkout-fields-pro' ),
			'title'          => __( 'After Billing Form Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => true,
		);

		$sections_add['woocommerce_before_checkout_shipping_form'] = array(
			'section'        => 'before_checkout_shipping_form',
			'tab'            => 'fields_before_checkout_shipping_form',
			'tab_title'      => __( 'Before Shipping Form', 'flexible-checkout-fields-pro' ),
			'title'          => __( 'Before Shipping Form Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => true,
		);

		$sections_add['woocommerce_after_checkout_shipping_form'] = array(
			'section'        => 'after_checkout_shipping_form',
			'tab'            => 'fields_after_checkout_shipping_form',
			'tab_title'      => __( 'After Shipping Form', 'flexible-checkout-fields-pro' ),
			'title'          => __( 'After Shipping Form Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => true,
		);

		$sections_add['woocommerce_before_checkout_registration_form'] = array(
			'section'        => 'before_checkout_registration_form',
			'tab'            => 'fields_before_checkout_registration_form',
			'tab_title'      => __( 'Before Registration Form', 'flexible-checkout-fields-pro' ),
			'title'          => __( 'Before Registration Form Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => true,
		);

		$sections_add['woocommerce_after_checkout_registration_form'] = array(
			'section'        => 'after_checkout_registration_form',
			'tab'            => 'fields_after_checkout_registration_form',
			'tab_title'      => __( 'After Registration Form', 'flexible-checkout-fields-pro' ),
			'title'          => __( 'After Registration Form Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => true,
		);

		$sections_add['woocommerce_before_order_notes'] = array(
			'section'        => 'before_order_notes',
			'tab'            => 'fields_before_order_notes',
			'tab_title'      => __( 'Before Order Notes', 'flexible-checkout-fields-pro' ),
			'title'          => __( 'Before Order Notes Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => false,
		);

		$sections_add['woocommerce_after_order_notes'] = array(
			'section'        => 'after_order_notes',
			'tab'            => 'fields_after_order_notes',
			'tab_title'      => __( 'After Order Notes', 'flexible-checkout-fields-pro' ),
			'title'          => __( 'After Order Notes Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => false,
		);

		$sections_add['woocommerce_review_order_before_submit'] = array(
			'section'        => 'review_order_before_submit',
			'tab'            => 'fields_review_order_before_submit',
			'tab_title'      => __( 'Before Submit', 'flexible-checkout-fields-pro' ),
			'title'          => __( 'Before Submit Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => false,
		);

		$sections_add['woocommerce_review_order_after_submit'] = array(
			'section'        => 'review_order_after_submit',
			'tab'            => 'fields_review_order_after_submit',
			'tab_title'      => __( 'After Submit', 'flexible-checkout-fields-pro' ),
			'title'          => __( 'After Submit Fields', 'flexible-checkout-fields-pro' ),
			'custom_section' => true,
			'user_meta'      => false,
		);

		foreach ( $sections_add as $section => $section_data ) {
			if ( in_array( $section_data['section'], $this->unavailable_sections, true ) ) {
				continue;
			}

			if ( $get_disabled || get_option( 'inspire_checkout_fields_' . $section_data['section'] , '0' ) == '1' ) {
				$sections[$section] = $section_data;
			}
		}

		return $sections;
	}

	public function flexible_checkout_fields_custom_attributes( $attributes, $field ) {
		if ( isset( $field['type'] ) && $field['type'] == 'datepicker' ) {
			$attributes['date_format'] = '';
			if ( isset( $field['date_format'] ) ) {
				$attributes['date_format'] = $field['date_format'];
			}
			if ( isset( $field['days_before'] ) ) {
				$attributes['days_before'] = $field['days_before'];
				if ( $field['days_before'] === '0' ) {
					$attributes['days_before'] = '0.0';
				}
			}
			if ( isset( $field['days_after'] ) ) {
				$attributes['days_after'] = $field['days_after'];
				if ( $field['days_after'] === '0' ) {
					$attributes['days_after'] = '0.0';
				}
			}
		}
		return $attributes;
	}

	public function flexible_checkout_fields_user_fields( $return, $field, $user ) {
		switch ( $field['type'] ) {

			case 'heading':
				$return = '';
				break;

			case 'info':
				$return = '';
				break;

			case 'timepicker':
				$return = '
                                        <tr>
                                            <th><label for="'.$field['name'].'">'.stripslashes($field['label']).'</label></th>
                                            <td>
                                                <input type="text" name="'.$field['name'].'" id="'.$field['name'].'" value="'.esc_attr( get_the_author_meta( $field['name'], $user->ID ) ).'" class="regular-text load-timepicker" /><br /><span class="description"></span>
                                            </td>
                                        </tr>
                                    ';
				break;

			case 'colorpicker':
				$return = '
                                        <tr>
                                            <th><label for="'.$field['name'].'">'.$field['label'].'</label></th>
                                            <td>
                                                <input type="text" name="'.$field['name'].'" id="'.$field['name'].'" value="'.esc_attr( get_the_author_meta( $field['name'], $user->ID ) ).'" class="regular-text load-colorpicker" /><br /><span class="description"></span>
                                            </td>
                                        </tr>
                                    ';
				break;

			case 'datepicker':
				$return = '
                                        <tr>
                                            <th><label for="'.$field['name'].'">'.$field['label'].'</label></th>
                                            <td>
                                                <input type="text" name="'.$field['name'].'" id="'.$field['name'].'" value="'.esc_attr( get_the_author_meta( $field['name'], $user->ID ) ).'" class="regular-text load-datepicker" /><br /><span class="description"></span>
                                            </td>
                                        </tr>
                                    ';
				break;

			case 'select':
				$array_options = explode("\n", $field['option']);
				if(!empty($array_options)){
					foreach ($array_options as $option) {
						$tmp = explode(':', $option);
						$options[trim($tmp[0])] = trim($tmp[0]);
						if ( isset( $tmp[1] ) ) {
							$options[ trim( $tmp[0] ) ] = trim( $tmp[1] );
						}
						unset($tmp);
					}
				}

				$return = '<tr><th><label for="'.$field['name'].'">'.$field['label'].'</label></th><td><select name="'.$field['name'].'" id="'.$field['name'].'" class="regular-text" />';
				foreach ($options as $okey => $option) {
					$selected = '';
					if($okey == esc_attr( get_the_author_meta( $field['name'], $user->ID ) )) $selected = " selected";
					$return .= '<option value="'.$okey.'"'.$selected.'>'.$option.'</option>';
					unset($selected);
				}

				$return .= '</select><br /><span class="description"></span></td></tr>';

				unset($options);
				break;

			case 'inspireradio':
				$array_options = explode("\n", $field['option']);
				if(!empty($array_options)){
					foreach ($array_options as $option) {
						$tmp = explode(':', $option);
						$options[trim($tmp[0])] = trim($tmp[1]);
						unset($tmp);
					}
				}

				$return = '<tr><th>'.$field['label'].'</th><td>';
				foreach ($options as $okey => $option) {
					$checked = '';
					if($okey == esc_attr( get_the_author_meta( $field['name'], $user->ID ) )) $checked = " checked";
					$return .= '<input type="radio" name="'.$field['name'].'" id="'.$field['name'].$okey.'" value="'.$okey.'"'.$checked.'><label for="'.$field['name'].$okey.'">'.$option.'</label><br />';
					unset($checked);
				}

				$return .= '<br /><span class="description"></span></td></tr>';

				unset($options);
				break;

			case 'inspirecheckbox':
				$checked = '';
				$return = '<tr><th><label for="'.$field['name'].'">'.$field['label'].'</label></th><td>';
				if($field['placeholder'] == esc_attr( get_the_author_meta( $field['name'], $user->ID ) )) $checked = " checked";
				$return .= '<input type="checkbox" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$field['placeholder'].'"'.$checked.'><label for="'.$field['name'].'">'.$field['label'].'</label><br />';
				unset($checked);

				$return .= '<br /><span class="description"></span></td></tr>';

				unset($options);
				break;

			case 'file':
				$return = '';
				break;

		}

		return $return;
	}

	public function flexible_checkout_fields_fields( $fields ) {

	    $fields['text'][self::FIELD_TYPE_HAS_DEFAULT_VALUE] = true;
		$fields['textarea'][self::FIELD_TYPE_HAS_DEFAULT_VALUE] = true;

		$add_fields = array();

		$add_fields['inspirecheckbox'] = array(
			self::FIELD_TYPE_NAME              => __( 'Checkbox', 'flexible-checkout-fields-pro' ),
			self::FIELD_TYPE_PLACEHOLDER_LABEL => __( 'Value', 'flexible-checkout-fields-pro' ),
			self::FIELD_TYPE_LABEL_IS_REQUIRED => true,
			self::FIELD_TYPE_HAS_DEFAULT_VALUE => true,
		);

		$add_fields['inspireradio'] = array(
			self::FIELD_TYPE_NAME                => __( 'Radio button', 'flexible-checkout-fields-pro' ),
			self::FIELD_TYPE_DISABLE_PLACEHOLDER => true,
			self::FIELD_TYPE_HAS_OPTIONS         => true,
			self::FIELD_TYPE_HAS_DEFAULT_VALUE   => true,
        );

		$add_fields['select'] = array(
			self::FIELD_TYPE_NAME                => __( 'Select (Drop Down)', 'flexible-checkout-fields-pro' ),
			self::FIELD_TYPE_DISABLE_PLACEHOLDER => false,
			self::FIELD_TYPE_HAS_OPTIONS         => true,
            self::FIELD_TYPE_HAS_DEFAULT_VALUE   => true,
		);

		$add_fields['datepicker'] = array(
			self::FIELD_TYPE_NAME => __( 'Date', 'flexible-checkout-fields-pro' ),
		);

		$add_fields['timepicker'] = array(
			self::FIELD_TYPE_NAME => __( 'Time', 'flexible-checkout-fields-pro')
		);

		$add_fields['colorpicker'] = array(
			self::FIELD_TYPE_NAME              => __( 'Color Picker', 'flexible-checkout-fields-pro' ),
			self::FIELD_TYPE_HAS_DEFAULT_VALUE => true,
		);

		$add_fields['heading'] = array(
			self::FIELD_TYPE_NAME                => __( 'Headline', 'flexible-checkout-fields-pro' ),
			self::FIELD_TYPE_HAS_REQUIRED        => false,
			self::FIELD_TYPE_DISABLE_PLACEHOLDER => true,
			self::FIELD_TYPE_EXCLUDE_IN_ADMIN    => true,
			self::FIELD_TYPE_EXCLUDE_FOR_USER    => true,
		);

		$add_fields['info'] = array(
			self::FIELD_TYPE_NAME                => __( 'HTML', 'flexible-checkout-fields-pro' ),
			self::FIELD_TYPE_HAS_REQUIRED        => false,
			self::FIELD_TYPE_DISABLE_PLACEHOLDER => true,
			self::FIELD_TYPE_EXCLUDE_IN_ADMIN    => true,
			self::FIELD_TYPE_EXCLUDE_FOR_USER    => true,
		);

		$add_fields[ Flexible_Checkout_Fields_Pro_File_Field_Type::FIELD_TYPE_FILE ] = $this->plugin->get_file_field_type()->get_field_type_definition();

		foreach ( $add_fields as $key => $field ) {
			$fields[$key] = $field;
		}

		return $fields;
	}

	public function wp_footer() {
		if ( is_checkout() ) {
			$fcf_conditions = array();
			$sections = $this->fcf_settings_fields_sections();
			$settings = $this->get_settings();
			foreach ( $sections as $section => $section_data ) {
				if ( isset( $settings[$section_data['section']] ) && is_array( $settings[$section_data['section']] ) ) {
					foreach ( $settings[$section_data['section']] as $key => $field ) {
						if ( isset( $field['conditional_logic_fields'] ) && $field['conditional_logic_fields'] == '1' ) {
							$fcf_conditions[$key] = array(
								'conditional_logic_fields'              => $field['conditional_logic_fields'],
								'conditional_logic_fields_action'       => $field['conditional_logic_fields_action'],
								'conditional_logic_fields_operator'     => $field['conditional_logic_fields_operator'],
								'conditional_logic_fields_rules'        => isset( $field['conditional_logic_fields_rules'] ) ? $field['conditional_logic_fields_rules'] : array(),
							);
						}
					}
				}
			}
			?>
			<script type="text/javascript">
				/* FCF PRO */
				<?php /* ?>
                var fcf_sections = <?php echo json_encode( $sections, JSON_PRETTY_PRINT );  ?>;
                <?php */ ?>
				var fcf_conditions = <?php echo json_encode( $fcf_conditions );  ?>;
			</script>
			<?php
		}
	}

	public function fcf_shipping_fields_wp_footer() {
		if ( is_checkout() ) {
			$fcf_shipping_conditions = array();
			$sections = $this->fcf_settings_fields_sections();
			$settings = $this->get_settings();
			foreach ( $sections as $section => $section_data ) {
				if ( isset( $settings[$section_data['section']] ) && is_array( $settings[$section_data['section']] ) ) {
					foreach ( $settings[$section_data['section']] as $key => $field ) {
						if ( isset($field['conditional_logic_shipping_fields']) && $field['conditional_logic_shipping_fields'] === '1' ) {
							$fcf_shipping_conditions[$key] = array(
								'conditional_logic_shipping_fields'              => $field['conditional_logic_shipping_fields'],
								'conditional_logic_shipping_fields_action'       => $field['conditional_logic_shipping_fields_action'],
								'conditional_logic_shipping_fields_operator'     => $field['conditional_logic_shipping_fields_operator'],
								'conditional_logic_shipping_fields_rules'        => isset( $field['conditional_logic_shipping_fields_rules'] ) ? $field['conditional_logic_shipping_fields_rules'] : array(),
							);
						}
					}
				}
			}
			?>
			<script type="text/javascript">
				/* FCF PRO */
				var fcf_shipping_conditions = <?php echo json_encode( $fcf_shipping_conditions );  ?>;
			</script>
			<?php
		}
	}

	/**
	 * Flexible Checkout Fields Sections
	 *
	 * @return array of sections [ 'billing' => [ 'section' => 'billing' , 'tab' => 'fields_billing' ...] ]
	 */
	private function fcf_settings_fields_sections() {
		$flexible_checkout_fields = flexible_checkout_fields();

		return $flexible_checkout_fields->sections;
	}

	/**
	 * Hide fields on checkout page based on "Add new fields rule"
	 *
	 * @param array $checkout_fields
	 *
	 * @return array fields on checkout page [ 'billing' => 'billing_first_name' => [ 'label' => 'Name', 'required' => false ]... ]
	 */
	public function conditional_logic_fields_hide( $checkout_fields ) {

		if ( is_checkout() && !( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			return $checkout_fields;
		}
		$flexible_checkout_fields = flexible_checkout_fields();
		$sections = $flexible_checkout_fields->sections;
		$settings = $this->get_settings();
		$checkout = new Conditional_Logic_Fields_On_Checkout( $settings );

		return $checkout->conditional_logic_fields_hide_on_checkout_page( $checkout_fields, $sections );
	}

	/**
	 * Hide fields on checkout page based on "Add new shipping rule"
	 *
	 * @param array $checkout_fields
	 *
	 * @return array fields on checkout page [ 'billing' => 'billing_first_name' => [ 'label' => 'Name', 'required' => false ]... ]
	 */
	public function conditional_logic_shipping_fields_hide( $checkout_fields ) {

		if ( is_checkout() && !( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			return $checkout_fields;
		}
		$flexible_checkout_fields = flexible_checkout_fields();
		$sections = $flexible_checkout_fields->sections;
		$settings = $this->get_settings();
		$checkout = new Conditional_Logic_Shipping_Fields_On_Checkout( $settings );

		return $checkout->conditional_logic_shipping_fields_hide_on_checkout_page( $checkout_fields, $sections );
	}


	/**
	 * Get option as array from string.
	 *
	 * @param string $options_as_string Options as string.
	 *
	 * @return array
	 */
	public function get_options_as_array_from_string( $options_as_string ) {
		$options = array();
		$rows    = explode( "\n", $options_as_string );
		foreach ( $rows as $row ) {
			$row_option                = explode( ':', $row );
			$options[ $row_option[0] ] = isset( $row_option[1] ) ? $row_option[1] : $row_option[0];
		}
		return $options;
	}

	/**
	 * Sets keys of unavailable sections for checkout process.
	 *
	 * @internal
	 */
	public function set_unavailable_sections_for_checkout_process() {
		if ( is_user_logged_in() || ! WC()->checkout()->is_registration_enabled() ) {
			$this->unavailable_sections[] = 'before_checkout_registration_form';
			$this->unavailable_sections[] = 'after_checkout_registration_form';
		}
	}
}
