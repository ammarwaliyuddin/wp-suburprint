<?php
/**
 * Support type of Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing\Type;

use WPDesk\FCF\Pro\Pricing\Field\FieldInterface;
use WPDesk\FCF\Pro\Pricing\Type\TypeInterface;
use WPDesk\FCF\Pro\Pricing\Session;

/**
 * TypeIntegration class for Pricing.
 */
class TypeIntegration {

	/**
	 * Class object for field type.
	 *
	 * @var FieldInterface
	 */
	private $field_object;

	/**
	 * Class object for field type.
	 *
	 * @var TypeInterface
	 */
	private $type_object;

	/**
	 * Class constructor.
	 *
	 * @param FieldInterface $field_object Class object of field type.
	 * @param TypeInterface  $type_object Class object of price type.
	 */
	public function __construct( FieldInterface $field_object, TypeInterface $type_object ) {
		$this->field_object = $field_object;
		$this->type_object  = $type_object;

	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_price_in_field_label' ], 10, 2 );
		add_action( 'woocommerce_cart_calculate_fees', [ $this, 'add_fees_positive' ], 10 );
		add_action( 'woocommerce_cart_calculate_fees', [ $this, 'add_fees_negative' ], 11 );
	}

	/**
	 * Adds information about price in field label.
	 *
	 * @param array  $args Settings of field.
	 * @param string $key Name of field.
	 *
	 * @return array Data of field.
	 *
	 * @internal
	 */
	public function add_price_in_field_label( array $args, string $key ) {
		if ( $args['id'] !== $this->field_object->get_field_name() ) {
			return $args;
		}

		return $this->field_object->add_price_in_field_label(
			$args,
			$this->type_object->get_option_value(),
			$this->type_object->get_price_for_label()
		);
	}

	/**
	 * Adds positive fees to cart based on fields with pricing.
	 *
	 * @param \WC_Cart $wc_cart Class object.
	 *
	 * @internal
	 */
	public function add_fees_positive( \WC_Cart $wc_cart ) {
		if ( $this->type_object->get_option_data()['value'] <= 0 ) {
			return;
		}

		$this->add_fees( $wc_cart );
	}

	/**
	 * Adds negative fees to cart based on fields with pricing.
	 *
	 * @param \WC_Cart $wc_cart Class object.
	 *
	 * @internal
	 */
	public function add_fees_negative( \WC_Cart $wc_cart ) {
		if ( $this->type_object->get_option_data()['value'] >= 0 ) {
			return;
		}

		$this->add_fees( $wc_cart );
	}

	/**
	 * Adds fees to cart based on fields with pricing.
	 *
	 * @param \WC_Cart $wc_cart Class object.
	 *
	 * @internal
	 */
	private function add_fees( \WC_Cart $wc_cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		$post_data   = \WC()->session->get( Session::SESSION_POST_DATA_KEY, [] );
		$field_value = $post_data[ $this->field_object->get_field_name() ] ?? '';
		if ( ! $this->field_object->is_valid_field_value( $this->type_object->get_option_value(), $field_value ) ) {
			return;
		}

		$wc_cart->add_fee(
			$this->field_object->get_field_label_for_fee( $field_value, $this->type_object->get_option_value() ),
			$this->type_object->get_calculated_price(),
			( $this->type_object->get_tax_class() !== 'non-taxable' ),
			$this->type_object->get_tax_class()
		);
	}
}
