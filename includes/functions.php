<?php
/**
 * General-purpose and helper functions
 *
 * @package XcooBee/Cookie
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers a cookie to be set by XCK.
 *
 * @since 1.0.0
 *
 * @param string $source   Cookie source.
 * @param string $category Cookie category (application, usage, statistics or advertising).
 * @param array  $cookie   Cookie details (name, value).
 * @return bool True on success and false on failure.
 */
function xbee_cookie( $action, $category, array $cookie ) {
	// Exit if unknown category.
	if ( ! in_array( $category, ['application', 'usage', 'statistics', 'advertising'] ) ) {
		return false;
	}

	// Exit if unknown action.
	if ( ! in_array( $action, ['set', 'unset'] ) ) {
		return false;
	}

	// Check required parameters.
	if ( ! isset( $cookie['name'] ) || ( 'set' === $action && ! isset( $cookie['value'] ) ) ) {
		return false;
	}

	if ( ! isset( $GLOBALS['xbee_cookies'][ $category ][ $action ] ) ) {
		$GLOBALS['xbee_cookies'][ $category ][ $action ] = [];
	}

	array_push( $GLOBALS['xbee_cookies'][ $category ][ $action ], $cookie );

	return true;
}

/**
 * Returns a list of supported country codes.
 *
 * @return array
 */
function xbee_cookie_get_country_codes() {
	return [ 'AD', 'AE', 'AF', 'AG', 'AI', 'AL', 'AM', 'AO', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AW', 'AX', 'AZ', 'BA', 'BB', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BL', 'BM', 'BN', 'BO', 'BQ', 'BR', 'BS', 'BT', 'BV', 'BW', 'BY', 'BZ', 'CA', 'CC', 'CD', 'CF', 'CG', 'CH', 'CI', 'CK', 'CL', 'CM', 'CN', 'CO', 'CR', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DE', 'DJ', 'DK', 'DM', 'DO', 'DZ', 'EC', 'EE', 'EG', 'EH', 'ER', 'ES', 'ET', 'FI', 'FJ', 'FK', 'FM', 'FO', 'FR', 'GA', 'GB', 'GD', 'GE', 'GF', 'GG', 'GH', 'GI', 'GL', 'GM', 'GN', 'GP', 'GQ', 'GR', 'GS', 'GT', 'GU', 'GW', 'GY', 'HK', 'HM', 'HN', 'HR', 'HT', 'HU', 'ID', 'IE', 'IL', 'IM', 'IN', 'IO', 'IQ', 'IR', 'IS', 'IT', 'JE', 'JM', 'JO', 'JP', 'KE', 'KG', 'KH', 'KI', 'KM', 'KN', 'KP', 'KR', 'KW', 'KY', 'KZ', 'LA', 'LB', 'LC', 'LI', 'LK', 'LR', 'LS', 'LT', 'LU', 'LV', 'LY', 'MA', 'MC', 'MD', 'ME', 'MF', 'MG', 'MH', 'MK', 'ML', 'MM', 'MN', 'MO', 'MP', 'MQ', 'MR', 'MS', 'MT', 'MU', 'MV', 'MW', 'MX', 'MY', 'MZ', 'NA', 'NC', 'NE', 'NF', 'NG', 'NI', 'NL', 'NO', 'NP', 'NR', 'NU', 'NZ', 'OM', 'PA', 'PE', 'PF', 'PG', 'PH', 'PK', 'PL', 'PM', 'PN', 'PR', 'PS', 'PT', 'PW', 'PY', 'QA', 'RE', 'RO', 'RS', 'RU', 'RW', 'SA', 'SB', 'SC', 'SD', 'SE', 'SG', 'SH', 'SI', 'SJ', 'SK', 'SL', 'SM', 'SN', 'SO', 'SR', 'SS', 'ST', 'SV', 'SX', 'SY', 'SZ', 'TC', 'TD', 'TF', 'TG', 'TH', 'TJ', 'TK', 'TL', 'TM', 'TN', 'TO', 'TR', 'TT', 'TV', 'TW', 'TZ', 'UA', 'UG', 'UM', 'US', 'UY', 'UZ', 'VA', 'VC', 'VE', 'VG', 'VI', 'VN', 'VU', 'WF', 'WS', 'YE', 'YT', 'ZA', 'ZM', 'ZW' ];
}

/**
 * Get plugin options.
 *
 * @return array
 */
function xbee_cookie_get_options() {
	$options = [];

	$options['connected']              =  false;
	$options['active']                 = 'on' === get_option( 'xbee_cookie_active', '' );
	$options['types']                  = empty( get_option( 'xbee_cookie_types', [] ) ) ? [] : get_option( 'xbee_cookie_types' );
	$options['types_default']          = empty( get_option( 'xbee_cookie_types_default', [] ) ) ? [] : get_option( 'xbee_cookie_types_default' );
	$options['position']               = get_option( 'xbee_cookie_position', 'left_top' );
	$options['theme']                  = get_option( 'xbee_cookie_theme', 'popup' );
	$options['expiration_time']        = intval( get_option( 'xbee_cookie_expiration_time', 0 ) );
	$options['disable_outside_eu']     = get_option( 'xbee_cookie_disable_outside_eu', '' );
	$options['detect_country']         = get_option( 'xbee_cookie_detect_country', '' );
	$options['enable_default_country'] = get_option( 'xbee_cookie_enable_default_country', '' );
	$options['default_country']        = get_option( 'xbee_cookie_default_country', '' );
	$options['display_donotsell']      = get_option( 'xbee_cookie_display_donotsell', '' );
	$options['display_fingerprint']    = get_option( 'xbee_cookie_display_fingerprint', '' );
	$options['hide_brand_tag']         = get_option( 'xbee_cookie_hide_brand_tag', '' );
	$options['test_mode']              = get_option( 'xbee_cookie_test_mode', '' );
	$options['privacy_url']            = intval( get_option( 'xbee_cookie_privacy_url', 0 ) );
	$options['terms_url']              = intval( get_option( 'xbee_cookie_terms_url', 0 ) );
	$options['text_message']           = get_option( 'xbee_cookie_text_message', false ) ? get_option( 'xbee_cookie_text_message' ) : [ 'en-us' => '' ];
	$options['scripts_usage']          = get_option( 'xbee_cookie_scripts_usage', '' );
	$options['scripts_application']    = get_option( 'xbee_cookie_scripts_application', '' );
	$options['scripts_statistics']     = get_option( 'xbee_cookie_scripts_statistics', '' );
	$options['scripts_advertising']    = get_option( 'xbee_cookie_scripts_advertising', '' );
	$options['intercept_php_calls']    = get_option( 'xbee_cookie_intercept_php_calls', '' );
	$options['default_php_calls']      = get_option( 'xbee_cookie_default_php_calls', '' );
	$options['campaign_reference']     = ''; // Only available in connected mode.
	$options['company_logo']           = ''; // Only available in connected mode.

	/*
	 * Connected mode.
	 */
	if ( 'on' === get_option( 'xbee_cookie_campaign', '' ) ) {
		$campaign_data = get_option( 'xbee_cookie_campaign_data' );

		$options['connected']           = true;
		$options['campaign_reference']  = isset( $campaign_data['campaign_reference'] ) ? $campaign_data['campaign_reference'] : '';
		$options['company_logo']        = isset( $campaign_data['company_logo'] ) ? $campaign_data['company_logo'] : '';
		$options['position']            = isset( $campaign_data['position'] ) ? $campaign_data['position'] : 'left_top';
		$options['expiration_time']     = isset( $campaign_data['expiration_time'] ) ? $campaign_data['expiration_time'] : 0;
		$options['disable_outside_eu']  = $campaign_data['display_only_for_eu'] ? 'on' : '';
		$options['privacy_url']         = isset( $campaign_data['privacy_url'] ) ? $campaign_data['privacy_url'] : '';
		$options['terms_url']           = isset( $campaign_data['terms_url'] ) ? $campaign_data['terms_url'] : '';
		$options['text_message']        = isset( $campaign_data['text_message'] ) ? $campaign_data['text_message'] : [ 'en-us' => '' ];
		$options['types']               = isset( $campaign_data['request_data_types'] ) ? $campaign_data['request_data_types'] : [];
		$options['types_default']       = isset( $campaign_data['check_by_default_types'] ) ? $campaign_data['check_by_default_types'] : [];
		$options['theme']               = isset( $campaign_data['theme'] ) ? $campaign_data['theme'] : 'popup';
		$options['detect_country']      = $campaign_data['detect_country'] ? 'on' : '';
		$options['display_fingerprint'] = $campaign_data['display_fingerprint'] ? 'on' : '';
		$options['hide_brand_tag']      = '';   // Always off in connected mode.
	}

	return $options;
}
