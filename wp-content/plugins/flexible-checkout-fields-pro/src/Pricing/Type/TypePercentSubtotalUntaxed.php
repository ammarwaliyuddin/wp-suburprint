<?php
/**
 * Support "Percent Subtotal ex. VAT" type of Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing\Type;

use WPDesk\FCF\Pro\Pricing\Type\TypeAbstract;
use WPDesk\FCF\Pro\Pricing\Type\TypeInterface;

/**
 * TypePercentSubtotalUntaxed class for Pricing.
 */
class TypePercentSubtotalUntaxed extends TypeAbstract implements TypeInterface {

	/**
	 * Calculates price based on price settings.
	 *
	 * @return float Price value.
	 */
	public function get_calculated_price(): float {
		$totals     = \WC()->cart->get_totals();
		$base_price = ( $totals['subtotal'] );
		$percent    = $this->get_percentage_of_fee_net_value( $base_price );

		return ( $percent * ( $base_price * $this->get_option_data()['value'] / 100 ) );
	}

	/**
	 * Generates price as information for field label.
	 *
	 * @return string|null Price information.
	 */
	public function get_price_for_label() {
		$value = $this->get_option_data()['value'];
		$price = abs( $value );

		if ( wc_tax_enabled() && ( get_option( 'woocommerce_tax_display_cart' ) === 'excl' ) ) {
			/* translators: %s: percentage value */
			$pattern = __( '%s%% of Subtotal', 'flexible-checkout-fields-pro' );
		} else {
			/* translators: %s: percentage value */
			$pattern = __( '%s%% of Subtotal ex. VAT', 'flexible-checkout-fields-pro' );
		}

		$price = sprintf( $pattern, $price );
		return sprintf( ' (%s %s)', ( $value < 0 ) ? '-' : '+', $price );
	}
}
