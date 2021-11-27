<?php
/**
 * Support fields types for Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing;

use WPDesk\FCF\Pro\Pricing\Field\FieldInterface;
use WPDesk\FCF\Pro\Pricing\Settings;
use WPDesk\FCF\Pro\Pricing\Type\TypeIntegration;
use WPDesk\FCF\Pro\Pricing\Type\TypeFixed;
use WPDesk\FCF\Pro\Pricing\Type\TypePercentSubtotalTaxed;
use WPDesk\FCF\Pro\Pricing\Type\TypePercentSubtotalUntaxed;
use WPDesk\FCF\Pro\Pricing\Type\TypePercentTotal;


/**
 * Fields class for Pricing.
 */
class Types {

	/**
	 * Class object for field type.
	 *
	 * @var FieldInterface
	 */
	private $field_object;

	/**
	 * Class constructor.
	 *
	 * @param FieldInterface $field_object Class object of field type.
	 */
	public function __construct( FieldInterface $field_object ) {
		$this->field_object = $field_object;
	}

	/**
	 * Initiates loading of pricing handling for fields with enabled option.
	 *
	 * @internal
	 */
	public function load_pricing_types() {
		if ( ! $this->field_object->is_pricing_enabled() ) {
			return;
		}

		foreach ( $this->field_object->get_field_pricing_types() as $value => $option ) {
			switch ( $option['type'] ) {
				case 'fixed':
					( new TypeIntegration( $this->field_object, new TypeFixed( $option, $value ) ) )->hooks();
					break;
				case 'percent_subtotal':
					( new TypeIntegration( $this->field_object, new TypePercentSubtotalUntaxed( $option, $value ) ) )->hooks();
					break;
				case 'percent_subtotal_tax':
					( new TypeIntegration( $this->field_object, new TypePercentSubtotalTaxed( $option, $value ) ) )->hooks();
					break;
				case 'percent_total':
					( new TypeIntegration( $this->field_object, new TypePercentTotal( $option, $value ) ) )->hooks();
					break;
			}
		}
	}
}
