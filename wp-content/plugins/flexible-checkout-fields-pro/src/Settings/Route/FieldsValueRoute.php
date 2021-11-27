<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Route;

use WPDesk\FCF\Pro\Settings\Route\FieldsConditionRoute;
use WPDesk\FCF\Free\Settings\Route\RouteInterface;
use WPDesk\FCF\Pro\Field\Type\CheckboxType;
use WPDesk\FCF\Pro\Field\Type\CheckboxDefaultType;
use WPDesk\FCF\Pro\Field\Type\SelectType;
use WPDesk\FCF\Pro\Field\Type\RadioType;
use WPDesk\FCF\Free\Field\FieldData;

/**
 * Supports settings for REST API route.
 */
class FieldsValueRoute extends FieldsConditionRoute implements RouteInterface {

	const REST_API_ROUTE = 'fields-value';

	/**
	 * Returns route of REST API endpoint.
	 *
	 * @return string Route name.
	 */
	public function get_endpoint_route(): string {
		return self::REST_API_ROUTE;
	}

	/**
	 * Returns list of field options.
	 *
	 * @param array $field_data Data of field.
	 *
	 * @return array Field options.
	 */
	protected function get_values_for_field( array $field_data ): array {
		switch ( $field_data['type'] ) {
			case CheckboxType::FIELD_TYPE:
			case CheckboxDefaultType::FIELD_TYPE:
				return [
					'checked'   => __( 'checked', 'flexible-checkout-fields-pro' ),
					'unchecked' => __( 'unchecked', 'flexible-checkout-fields-pro' ),
				];
			case RadioType::FIELD_TYPE:
			case SelectType::FIELD_TYPE:
				if ( ! ( $field_args = FieldData::get_field_data( $field_data ) )
					|| ! ( $options = $field_args['option'] ?? [] ) ) {
					return [];
				}
				return array_combine(
					array_column( $options, 'key' ),
					array_column( $options, 'value' )
				);
		}
		return [];
	}
}
