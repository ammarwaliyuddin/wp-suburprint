<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Route;

use WPDesk\FCF\Free\Settings\Route\RouteAbstract;
use WPDesk\FCF\Free\Settings\Route\RouteInterface;
use WPDesk\FCF\Pro\Settings\Route\FieldsFieldRoute;
use WPDesk\FCF\Pro\Field\Type\CheckboxType;
use WPDesk\FCF\Pro\Field\Type\CheckboxDefaultType;
use WPDesk\FCF\Pro\Field\Type\SelectType;
use WPDesk\FCF\Pro\Field\Type\RadioType;

/**
 * Supports settings for REST API route.
 */
class FieldsConditionRoute extends RouteAbstract implements RouteInterface {

	const REST_API_ROUTE = 'fields-condition';

	/**
	 * Returns route of REST API endpoint.
	 *
	 * @return string Route name.
	 */
	public function get_endpoint_route(): string {
		return self::REST_API_ROUTE;
	}

	/**
	 * Returns data to be returned for endpoint.
	 *
	 * @param array $params Params for endpoint.
	 *
	 * @return mixed Response data.
	 *
	 * @throws \Exception .
	 */
	public function get_endpoint_response( array $params ) {
		$settings   = FieldsFieldRoute::update_fields_settings( $params['form_section'] ?? '', $params['form_fields'] ?? [] );
		$field_name = $params['form_values']['field'] ?? '';
		$values     = [];
		foreach ( $settings as $section_fields ) {
			foreach ( $section_fields as $field ) {
				if ( ( $field['name'] !== $field_name )
					|| ! in_array( $field['type'] ?? '', FieldsFieldRoute::$supported_field_types, true ) ) {
					continue;
				}
				$values += $this->get_values_for_field( $field );
			}
		}
		return $values;
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
			case RadioType::FIELD_TYPE:
			case SelectType::FIELD_TYPE:
				return [
					'is' => __( 'is', 'flexible-checkout-fields-pro' ),
				];
		}
		return [];
	}
}
