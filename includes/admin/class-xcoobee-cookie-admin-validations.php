<?php
/**
 * The XcooBee_Cookie_Admin_Validations class.
 *
 * @package XcooBee/Cookie/Admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Validations for the cookie settings.
 *
 * @since 1.2.0
 */
class XcooBee_Cookie_Admin_Validations {
	/**
	 * The constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_filter( 'pre_update_option_xbee_cookie_types', [ $this, 'validate_cookie_types' ], 10, 3 );
		add_filter( 'pre_update_option_xbee_cookie_expiration_time', [ $this, 'validate_expiration_time' ], 10, 3 );
		add_filter( 'pre_update_option_xbee_cookie_text_message', [ $this, 'validate_text_message' ], 10, 3 );

		// Network options should not be updated in connected mode.
		if ( 'on' === get_option( 'xbee_cookie_campaign', '' ) ) {
			$network_options = [
				'types',
				'types_dafult',
				'position',
				'theme',
				'expiration_time',
				'disable_outside_eu',
				'detect_country',
				'display_fingerprint',
				'hide_brand_tag',
				'text_message',
			];
	
			foreach( $network_options as $option ) {
				add_filter( 'pre_update_option_xbee_cookie_' . $option, [ $this, 'disable_option'], 999999, 3 );
			}
		}
	}

	/**
	 * Disable network options in connected mode.
	 */
	public function disable_option( $value, $old_value, $option ) {
		return $old_value;
	}

	/**
	 * Validates option: xbee_cookie_types.
	 *
	 * @since 1.2.0
	 */
	public function validate_cookie_types( $value, $old_value, $option ) {
		$campaign = 'on' === get_option( 'xbee_cookie_campaign', '' ) ? true : false;

		// One type at least must be selected.
		if ( ! $campaign && empty( $value ) ) {
			add_settings_error(
				'xbee_cookie_cookie_types',
				'xbee_error_cookie_types',
				__( 'Display Option: at least one cookie type must be selected.', 'xcoobee' ),
				'error'
			);

			return $old_value;
		}

		return $value;
	}

	/**
	 * Validates option: xbee_cookie_expiration_time.
	 *
	 * @since 1.2.0
	 */
	public function validate_expiration_time( $value, $old_value, $option ) {
		// Positive integer between 0 and 3600.
		if ( $value < 0 || $value > 3600 ) {
			add_settings_error(
				'xbee_cookie_expiration_time',
				'xbee_error_expiration_time',
				__( 'Remove Icon After: must be a positive integer between 0 and 3600.', 'xcoobee' ),
				'error'
			);

			return $old_value;
		}

		return $value;
	}

	/**
	 * Validates option: xbee_cookie_text_message.
	 *
	 * @since 1.2.0
	 */
	public function validate_text_message( $value, $old_value, $option ) {
		// Maximum length of 1000 characters.
		if ( strlen( $value ) > 1000 ) {
			add_settings_error(
				'xbee_cookie_text_message',
				'xbee_error_text_message',
				__( 'Cookie Notice: notice length cannot exceed 1000 characters.', 'xcoobee' ),
				'error'
			);

			return $old_value;
		}

		return $value;
	}
}

new XcooBee_Cookie_Admin_Validations;