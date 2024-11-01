<?php
/**
 * Uninstall actions
 *
 * Remove plugin settings from the database.
 *
 * @package XcooBee/Cookie
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if uninstall not called from WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/*
 * Only remove plugin data if the XBEE_REMOVE_ALL_DATA constant is set to true in
 * user's wp-config.php. This is to prevent data loss when deleting the plugin from
 * the back-end and to ensure only the site owner can perform this action.
 */
if ( defined( 'XBEE_REMOVE_ALL_DATA' ) && true === XBEE_REMOVE_ALL_DATA ) {
	// Delete plugin options.
	delete_option( 'xbee_cookie_active' );
	delete_option( 'xbee_cookie_campaign' );
	delete_option( 'xbee_cookie_position' );
	delete_option( 'xbee_cookie_expiration_time' );
	delete_option( 'xbee_cookie_disable_outside_eu' );
	delete_option( 'xbee_cookie_text_message' );
	delete_option( 'xbee_cookie_privacy_url' );
	delete_option( 'xbee_cookie_terms_url' );
	delete_option( 'xbee_cookie_scripts_usage' );
	delete_option( 'xbee_cookie_scripts_application' );
	delete_option( 'xbee_cookie_scripts_statistics' );
	delete_option( 'xbee_cookie_scripts_advertising' );
}