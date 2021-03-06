<?php

namespace WPDesk\FCF\Free\Field\Type;

/**
 * {@inheritdoc}
 */
class FileType extends TypeAbstract {

	const FIELD_TYPE = 'file';

	/**
	 * {@inheritdoc}
	 */
	public function get_field_type(): string {
		return self::FIELD_TYPE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_field_type_label(): string {
		return __( 'File Upload', 'flexible-checkout-fields' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_field_type_icon(): string {
		return 'icon-upload';
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_available(): bool {
		return false;
	}
}
