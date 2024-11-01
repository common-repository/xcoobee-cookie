<?php
/**
 * The XcooBee_Cookie_Admin class.
 *
 * @package XcooBee/Cookie/Admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Controls cookie settings.
 *
 * @since 1.0.0
 */
class XcooBee_Cookie_Admin {
	/**
	 * The constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_page' ] );
		add_action( 'admin_init', [ $this, 'settings' ] );
		add_action( 'wp_ajax_xbee_cookie_activate', [ $this, 'activate' ] );
		add_action( 'wp_ajax_xbee_cookie_deactivate', [ $this, 'deactivate' ] );
		add_action( 'wp_ajax_xbee_cookie_connect_campaign', [ $this, 'connect_campaign' ] );
		add_action( 'wp_ajax_xbee_cookie_disconnect_campaign', [ $this, 'disconnect_campaign' ] );
		add_action( 'wp_ajax_xbee_cookie_refresh_campaign', [ $this, 'refresh_campaign' ] );
	}

	/**
	 * Activates the cookie plugin.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		// Activate the cookie plugin.
		update_option( 'xbee_cookie_active', 'on' );

		$result = ( object ) [
			'result'  => true,
			'status'  => 'success',
			'code'    => 'success_cookie_activate',
			'errors'  => [],
		];

		// Send response, and die.
		wp_send_json( json_encode( $result ) );
	}

	/**
	 * Deactivates cookie campaign.
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {
		// Deactivate the cookie plugin.
		update_option( 'xbee_cookie_active', '' );

		$result = ( object ) [
			'result'  => true,
			'status'  => 'success',
			'code'    => 'success_cookie_deactivate',
			'errors'  => [],
		];

		// Send response, and die.
		wp_send_json( json_encode( $result ) );
	}

	/**
	 * Connects to XcooBee and looks for a cookie campaign for the current domain.
	 *
	 * @since 1.0.0
	 */
	public function connect_campaign() {
		// Check campaign status.
		$campaign = get_option( 'xbee_cookie_campaign', '' );
		$test_keys = xbee_test_keys();
		
		// Exit if already connected to XcooBee.
		if ( 'on' === $campaign ) {
			$result = ( object ) [
				'result' => false,
				'status' => 'error',
				'code'   => 'error_campaign_connected',
				'errors' => [
					xbee_get_text( 'message_error_campaign_connected' )
				],
			];
		// Check API keys.
		} elseif ( ! $test_keys->result ) {
			$result = $test_keys;
		} else {
			// Try to connect and update campaign data.
			$result = $this->update_campaign();

			if ( $result->result || 'info_campaign_update_not_changed' === $result->code ) {
				// Update connection status.
				update_option( 'xbee_cookie_campaign', 'on' );

				// We need to return a different response here.
				$result = ( object ) [
					'result'  => true,
					'status'  => 'success',
					'code'    => 'success_campaign_connect',
					'html'    => $this->get_synced_options_html(),
					'errors'  => [],
				];
			}
		}

		// Send response, and die.
		wp_send_json( json_encode( $result ) );
	}

	/**
	 * Disconnects cookie campaign.
	 *
	 * @since 1.0.0
	 */
	public function disconnect_campaign() {
		// Update connection status.
		$disconnect = update_option( 'xbee_cookie_campaign', '' );

		if ( $disconnect ) {
			$result = ( object ) [
				'result'  => true,
				'status'  => 'success',
				'code'    => 'success_campaign_disconnect',
				'html'    => $this->get_synced_options_html(),
				'errors'  => [],
			];
		} else {
			$result = ( object ) [
				'result' => false,
				'status'  => 'error',
				'code'    => 'error_campaign_disconnect',
				'errors'  => [
					xbee_get_text( 'message_error_campaign_disconnect' )
				],
			];
		}

		// Send response, and die.
		wp_send_json( json_encode( $result ) );
	}

	/**
	 * Updates the connected campaign data.
	 *
	 * @since 1.0.0
	 */
	public function refresh_campaign() {
		// Try to update campaign data.
		$result = $this->update_campaign();
		
		// Send response, and die.
		wp_send_json( json_encode( $result ) );
	}

	/**
	 * Updates campaign data.
	 *
	 * @since 1.0.0
	 * @return object Result object.
	 */
	private function update_campaign() {
		$xcoobee = XcooBee::get_xcoobee( true );
		$xcoobee_api = XcooBee::get_xcoobee_api( $xcoobee );

		// Find matched campaign.
		$site_url = wp_parse_url( get_site_url() );
		$site = $site_url['scheme'] . '://' . $site_url['host'];
		$find_campaign = $this->filter_find_campaign( $site, $xcoobee, $xcoobee_api );

		// Could not get campaign info.
		if ( 200 !== $find_campaign->code || empty( $find_campaign->result->campaigns->data ) ) {
			$result = ( object ) [
				'result' => false,
				'status' => 'error',
				'code'   => 'error_campaign_data',
				'errors' => [
					xbee_get_text( 'message_error_campaign_data' )
				],
			];
		// Found multiple campaigns for this site.
		} elseif ( 1 < count( $find_campaign->result->campaigns->data ) ) {
			$result = ( object ) [
				'result' => false,
				'status' => 'error',
				'code'   => 'error_multiple_campaigns',
				'errors' => [
					xbee_get_text( 'message_error_multiple_campaigns' )
				],
				'campaigns' => $find_campaign->result->campaigns->data,
			];
		// If OK.
		} else {
			// Get campaign data.
			$campaign_id = $find_campaign->result->campaigns->data[0]->campaign_cursor;
			$campaign = $xcoobee_api->getCampaignInfo( $campaign_id );

			$campaign_data['campaign_reference']  = $campaign->result->campaign->campaign_reference;
			$campaign_data['position']            = $campaign->result->campaign->campaign_params->display_position;
			$campaign_data['privacy_url']         = $campaign->result->campaign->campaign_params->privacy_policy_url;
			$campaign_data['terms_url']           = $campaign->result->campaign->campaign_params->terms_of_service_url;
			$campaign_data['expiration_time']     = $campaign->result->campaign->campaign_params->remove_after_n_sec;
			$campaign_data['display_only_for_eu'] = $campaign->result->campaign->campaign_params->do_not_show_outside_eu;
			$campaign_data['detect_country']      = $campaign->result->campaign->campaign_params->detect_country;
			$campaign_data['theme']               = $campaign->result->campaign->campaign_params->theme;
			$campaign_data['hide_brand_tag']      = $campaign->result->campaign->campaign_params->hide_brand_tag;

			$text_message = $campaign->result->campaign->campaign_description;
			foreach ( $text_message as $text ) {
				$campaign_data['text_message'][ $text->locale ] = $text->text; 
			}

			$campaign_data['request_data_types']     = $campaign->result->campaign->requests->data[0]->request_data_types;
			$campaign_data['check_by_default_types'] = $campaign->result->campaign->campaign_params->check_by_default_types;

			// Remove the cookie _cookie suffix from the types.
			$campaign_data['request_data_types'] = array_map( function( $type ) {
				return str_replace( '_cookie', '', $type );
			}, $campaign_data['request_data_types'] );

			// Remove the _cookie suffix from the checked by default types.
			$campaign_data['check_by_default_types'] = array_map( function( $type ) {
				return str_replace( '_cookie', '', $type );
			}, $campaign_data['check_by_default_types'] );

			// Set the value of displayFingerprint (device_identifiers).
			$campaign_data['display_fingerprint'] = in_array( 'device_identifiers', $campaign_data['request_data_types'] ) ? 1 : 0;
			$campaign_data['request_data_types'] = array_diff( $campaign_data['request_data_types'], [ 'device_identifiers' ] );
			$campaign_data['check_by_default_types'] = array_diff( $campaign_data['check_by_default_types'], [ 'device_identifiers' ] );

			// Get company logo.
			$campaign_data['company_logo'] = $xcoobee_api->getCompanyLogo()->result->user->settings->campaign->logo;

			$old_campaign_data = get_option( 'xbee_cookie_campaign_data', '' );

			if ( $old_campaign_data !== $campaign_data ) {
				// Save campaign data into the database.
				$update = update_option( 'xbee_cookie_campaign_data', $campaign_data );
				
				if ( $update ) {
					$result = ( object ) [
						'result' => true,
						'status' => 'success',
						'code'   => 'success_campaign_update',
						'html'   => $this->get_synced_options_html(),
						'errors' => [],
					];
				} else {
					$result = ( object ) [
						'result' => false,
						'status' => 'error',
						'code'   => 'error_campaign_update',
						'errors' => [
							xbee_get_text( 'message_error_campaign_update' )
						],
					];
				}
			} else {
				$result = ( object ) [
					'result' => false,
					'status' => 'info',
					'code'   => 'info_campaign_update_not_changed',
					'errors' => [],
				];
			}
		}

		return $result;
	}

	/**
	 * Filters the results of findCampaign().
	 *
	 * @since 1.0.0
	 *
	 * @param string $site
	 * @param object $xcoobee
	 * @param object $xcoobee_api
	 * @return object
	 */
	protected function filter_find_campaign( $site, $xcoobee, $xcoobee_api ) {
		$find_campaign = $xcoobee_api->findCampaign( $site );

		if ( isset( $find_campaign->result->campaigns->data ) && ! empty( $find_campaign->result->campaigns->data ) ) {
			foreach( $find_campaign->result->campaigns->data as $key => $campaign ) {
				if ( $site !== $campaign->campaign_name  || 'cookie' !== $campaign->campaign_type ) {
					unset( $find_campaign->result->campaigns->data[$key] );
				}
			}
		}

		// Reset array index.
		$find_campaign->result->campaigns->data = array_values( $find_campaign->result->campaigns->data );

		return $find_campaign;
	}

	/**
	 * Registers cookie setting page.
	 *
	 * @since 1.0.0
	 */
	public function add_page() {
		add_submenu_page(
			'xcoobee',
			__( 'Cookie', 'xcoobee' ),
			__( 'Cookie', 'xcoobee' ),
			'manage_options',
			'admin.php?page=xcoobee&tab=cookie'
		);
	}

	/**
	 * Registers the cookie setting fields.
	 *
	 * @since 1.0.0
	 */
	public function settings() {
		// Cookie settings.
		register_setting( 'xbee_cookie', 'xbee_cookie_types' );
		register_setting( 'xbee_cookie', 'xbee_cookie_types_default' );
		register_setting( 'xbee_cookie', 'xbee_cookie_position' );
		register_setting( 'xbee_cookie', 'xbee_cookie_theme' );
		register_setting( 'xbee_cookie', 'xbee_cookie_expiration_time' );
		register_setting( 'xbee_cookie', 'xbee_cookie_disable_outside_eu' );
		register_setting( 'xbee_cookie', 'xbee_cookie_detect_country' );
		register_setting( 'xbee_cookie', 'xbee_cookie_enable_default_country' );
		register_setting( 'xbee_cookie', 'xbee_cookie_default_country' );
		register_setting( 'xbee_cookie', 'xbee_cookie_display_donotsell' );
		register_setting( 'xbee_cookie', 'xbee_cookie_display_fingerprint' );
		register_setting( 'xbee_cookie', 'xbee_cookie_hide_brand_tag' );
		register_setting( 'xbee_cookie', 'xbee_cookie_test_mode' );
		register_setting( 'xbee_cookie', 'xbee_cookie_text_message' );
		register_setting( 'xbee_cookie', 'xbee_cookie_privacy_url' );
		register_setting( 'xbee_cookie', 'xbee_cookie_terms_url' );
		register_setting( 'xbee_cookie', 'xbee_cookie_scripts_usage' );
		register_setting( 'xbee_cookie', 'xbee_cookie_scripts_application' );
		register_setting( 'xbee_cookie', 'xbee_cookie_scripts_statistics' );
		register_setting( 'xbee_cookie', 'xbee_cookie_scripts_advertising' );
		register_setting( 'xbee_cookie', 'xbee_cookie_intercept_php_calls' );
		register_setting( 'xbee_cookie', 'xbee_cookie_default_php_calls' );
	}

	/**
	 * Returns the HTML output of the sycned fields.
	 *
	 * @return string
	 */
	protected function get_synced_options_html() {
		ob_start();

		include_once XBEE_COOKIE_ABSPATH . 'includes/admin/views/settings-cookie-synced-options.php';

		return ob_get_clean();
	}
}

new XcooBee_Cookie_Admin();