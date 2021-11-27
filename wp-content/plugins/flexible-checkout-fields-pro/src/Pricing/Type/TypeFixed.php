<?php
/**
 * Support "Fixed" type of Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing\Type;

use WPDesk\FCF\Pro\Pricing\Type\TypeAbstract;
use WPDesk\FCF\Pro\Pricing\Type\TypeInterface;

/**
 * TypeFixed class for Pricing.
 */
class TypeFixed extends TypeAbstract implements TypeInterface {

	/**
	 * Generates price as information for field label.
	 *
	 * @return string|null Price information.
	 */
	public function get_price_for_label() {
		$value   = $this->get_option_data()['value'];
		$price   = abs( $value );
		$pattern = '(%1$s %2$s)';
		if ( wc_tax_enabled() && ( get_option( 'woocommerce_tax_display_shop' ) === 'excl' ) ) {
			$price_taxes = $this->get_taxes_for_price( $this->get_option_data() );
			$price      *= ( $price / ( $price + $price_taxes ) );
			if ( $price_taxes > 0 ) {
				/* translators: %$1s: price operator (+ or -), %$2s: formatted price */
				$pattern = __( '(%1$s %2$s ex. VAT)', 'flexible-checkout-fields-pro' );
			}
		}
		$price = wp_strip_all_tags( wc_price( $price ) );
		return sprintf( ' ' . $pattern, ( $value < 0 ) ? '-' : '+', $price );
	}

	/**
	 * Calculates tax value for price.
	 *
	 * @param array $price Settings of pricing for field.
	 *
	 * @return float Tax value.
	 */
	private function get_taxes_for_price( array $price ): float {
		if ( ( $price['value'] <= 0 ) || ( $price['tax_class'] === '' ) ) {
			return 0;
		}

		$tax_class = ( $price['tax_class'] === 'standard' ) ? '' : $price['tax_class'];
		$tax_rates = \WC_Tax::get_rates( $tax_class );

		return array_sum( \WC_Tax::calc_tax( $price['value'], $tax_rates ) );
	}

	/**
	 * Calculates price based on price settings.
	 *
	 * @return float Price value.
	 */
	public function get_calculated_price(): float {
		$totals     = \WC()->cart->get_totals();
		$base_price = ( $totals['cart_contents_total'] + $totals['shipping_total'] );
		$percent    = $this->get_percentage_of_fee_net_value( $base_price );

		return ( $percent * $this->get_option_data()['value'] );
	}
}
