<?php
/**
 * .
 *
 * @package WPDesk\FCF\Free
 */

namespace WPDesk\FCF\Pro\Field;

use WPDesk\FCF\Free\Field\Type\TypeIntegration;
use WPDesk\FCF\Pro\Field\Type\TextType;
use WPDesk\FCF\Pro\Field\Type\TextareaType;
use WPDesk\FCF\Pro\Field\Type\CheckboxType;
use WPDesk\FCF\Pro\Field\Type\CheckboxDefaultType;
use WPDesk\FCF\Pro\Field\Type\RadioType;
use WPDesk\FCF\Pro\Field\Type\RadioDefaultType;
use WPDesk\FCF\Pro\Field\Type\SelectType;
use WPDesk\FCF\Pro\Field\Type\Multiselect;
use WPDesk\FCF\Pro\Field\Type\DateType;
use WPDesk\FCF\Pro\Field\Type\TimeType;
use WPDesk\FCF\Pro\Field\Type\ColorType;
use WPDesk\FCF\Pro\Field\Type\HeadingType;
use WPDesk\FCF\Pro\Field\Type\HtmlType;
use WPDesk\FCF\Pro\Field\Type\FileType;
use WPDesk\FCF\Pro\Field\Type\DefaultType;
use WPDesk\FCF\Pro\Field\Type\Wc\WcDefaultType;
use WPDesk\FCF\Pro\Field\Type\Wc\WcContactType;
use WPDesk\FCF\Pro\Field\Type\Wc\WcAddress2Type;
use WPDesk\FCF\Pro\Field\Type\Wc\WcCountryType;
use WPDesk\FCF\Pro\Field\Type\Wc\WcPostcodeType;
use WPDesk\FCF\Pro\Field\Type\Wc\WcStateType;
use WPDesk\FCF\Pro\Field\Type\Wc\WcNotesType;

/**
 * Supports management of field types.
 */
class Types {

	/**
	 * Initializes actions for class.
	 *
	 * @return void
	 */
	public function init() {
		( new TypeIntegration( new TextType() ) )->hooks();
		( new TypeIntegration( new TextareaType() ) )->hooks();
		( new TypeIntegration( new CheckboxType() ) )->hooks();
		if ( class_exists( 'WPDesk\FCF\Free\Field\Type\CheckboxDefaultType' ) ) {
			( new TypeIntegration( new CheckboxDefaultType() ) )->hooks();
		}
		( new TypeIntegration( new RadioType() ) )->hooks();
		if ( class_exists( 'WPDesk\FCF\Free\Field\Type\RadioDefaultType' ) ) {
			( new TypeIntegration( new RadioDefaultType() ) )->hooks();
		}
		( new TypeIntegration( new SelectType() ) )->hooks();
		( new TypeIntegration( new Multiselect() ) )->hooks();
		( new TypeIntegration( new DateType() ) )->hooks();
		( new TypeIntegration( new TimeType() ) )->hooks();
		( new TypeIntegration( new ColorType() ) )->hooks();
		( new TypeIntegration( new HeadingType() ) )->hooks();
		( new TypeIntegration( new HtmlType() ) )->hooks();
		( new TypeIntegration( new FileType() ) )->hooks();
		( new TypeIntegration( new DefaultType() ) )->hooks();
		( new TypeIntegration( new WcDefaultType() ) )->hooks();
		if ( class_exists( 'WPDesk\FCF\Free\Field\Type\Wc\WcContactType' ) ) {
			( new TypeIntegration( new WcContactType() ) )->hooks();
		}
		( new TypeIntegration( new WcAddress2Type() ) )->hooks();
		( new TypeIntegration( new WcCountryType() ) )->hooks();
		( new TypeIntegration( new WcPostcodeType() ) )->hooks();
		( new TypeIntegration( new WcStateType() ) )->hooks();
		( new TypeIntegration( new WcNotesType() ) )->hooks();
	}
}
