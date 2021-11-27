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
 * TypeAbstract class for Pricing.
 */
abstract class TypeAbstract implements TypeInterface {

	/**
	 * Settings of pricing option.
	 *
	 * @var array
	 */
	private $option_data;

	/**
	 * Field value of pricing option.
	 *
	 * @var string
	 */
	private $option_value;

	/**
	 * Class constructor.
	 *
	 * @param array  $option_data Settings of pricing option.
	 * @param string $option_value Field value of pricing option.
	 */
	public function __construct( array $option_data, string $option_value ) {
		$this->option_data  = $option_data;
		$this->option_value = $option_value;
	}

	/**
	 * Returns settings of pricing option.
	 *
	 * @return array Option settings.
	 */
	public function get_option_data() {
		return $this->option_data;
	}

	/**
	 * Returns field value of pricing option.
	 *
	 * @return string Option value.
	 */
	public function get_option_value() {
		return $this->option_value;
	}

	/**
	 * Returns tax class of pricing option.
	 *
	 * @return string Tax class (empty string means standard tax class).
	 */
	public function get_tax_class() {
		$option = $this->get_option_data();
		if ( ( $option['tax_class'] === '' ) || ( $option['value'] < 0 ) ) {
			return 'non-taxable';
		}
		return ( $option['tax_class'] === 'standard' ) ? '' : $option['tax_class'];
	}

	/**
	 * Calculates percentage of net value for fee.
	 *
	 * @param float $base_price Gross amount of base price to calculation.
	 *
	 * @return float Percentage of net value.
	 */
	public function get_percentage_of_fee_net_value( float $base_price ): float {
		if ( $this->get_option_data()['value'] < 0 ) {
			$totals      = \WC()->cart->get_totals();
			$total_net   = ( $totals['cart_contents_total'] + $totals['shipping_total'] );
			$total_taxes = ( $totals['cart_contents_tax'] + $this->get_taxes_for_shipping( $totals ) );
			$fees_net    = $this->get_nontaxable_fees_net();
			$fees_taxes  = $this->get_nontaxable_fees_taxes();
			return ( ( $total_net + $fees_net ) / ( $total_net + $fees_net + $total_taxes + $fees_taxes ) );
		} elseif ( $this->get_tax_class() !== 'non-taxable' ) {
			$tax_rates = \WC_Tax::get_rates( $this->get_tax_class(), \WC()->customer );
			$tax_value = array_sum( \WC_Tax::calc_tax( $base_price, $tax_rates, false ) );
			return ( $base_price / ( $base_price + $tax_value ) );
		}
		return 1;
	}

	/**
	 * Calculates amount of taxes for shipping.
	 *
	 * @param array $totals All calculated totals for Cart.
	 *
	 * @return float Taxes value for shipping.
	 */
	private function get_taxes_for_shipping( array $totals ): float {
		if ( $totals['shipping_tax'] > 0 ) {
			return $totals['shipping_tax'];
		}

		$tax_class = get_option( 'woocommerce_shipping_tax_class' );
		if ( ( $tax_class === 'inherit' ) && ( $cart = \WC()->cart->get_cart() ) ) {
			$tax_class = reset( $cart )['data']->get_tax_class();
		}
		$tax_rates = \WC_Tax::get_rates( $tax_class, \WC()->customer );
		return array_sum( \WC_Tax::calc_tax( $totals['shipping_total'], $tax_rates, false ) );
	}

	/**
	 * Calculate sum of net value for all positive fees.
	 *
	 * @return float Sum of net value for fees.
	 */
	private function get_nontaxable_fees_net(): float {
		$value = 0;
		foreach ( \WC()->cart->get_fees() as $fee ) {
			if ( $fee->amount > 0 ) {
				$value += $fee->amount;
			}
		}
		return $value;
	}

	/**
	 * Calculate sum of taxes value for all positive taxable fees.
	 *
	 * @return float Sum of taxes value for fees.
	 */
	private function get_nontaxable_fees_taxes(): float {
		$value = 0;
		foreach ( \WC()->cart->get_fees() as $fee ) {
			if ( ( $fee->amount > 0 ) && $fee->taxable ) {
				$tax_rates = \WC_Tax::get_rates( $fee->tax_class, \WC()->customer );
				$tax_value = array_sum( \WC_Tax::calc_tax( $fee->amount, $tax_rates, false ) );
				$value    += $tax_value;
			}
		}
		return $value;
	}
}
