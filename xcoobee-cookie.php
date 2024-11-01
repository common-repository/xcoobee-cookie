<?php
/**
 * Plugin Name: XcooBee GDPR Cookie Manager
 * Plugin URI:  https://wordpress.org/plugins/xcoobee-cookie/
 * Author URI:  https://www.xcoobee.com/
 * Description: Easy and transparent GDPR and EU E-Directive cookie consent management for your site.
 * Version:     1.3.3
 * Author:      XcooBee
 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * Text Domain: xcoobee
 * Domain Path: /languages
 *
 * Requires at least: 4.4.0
 * Tested up to: 5.3.2
 *
 * @package XcooBee/Cookie
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Globals constants.
 */
define( 'XBEE_COOKIE_ABSPATH', plugin_dir_path( __FILE__ ) ); // With trailing slash.
define( 'XBEE_COOKIE_DIR_URL', plugin_dir_url( __FILE__ ) );  // With trailing slash.
define( 'XBEE_COOKIE_PLUGIN_BASENAME', plugin_basename(__FILE__) );

/**
 * The main class.
 *
 * @since 1.0.0
 */
class XcooBee_Cookie {
	/**
	 * The singleton instance of XcooBee_Cookie.
	 *
	 * @since 1.0.0
	 * @var XcooBee_Cookie
	 */
	private static $instance = null;

	/**
	 * Returns the singleton instance of XcooBee_Cookie.
	 *
	 * Ensures only one instance of XcooBee_Cookie is/can be loaded.
	 *
	 * @since 1.0.0
	 * @return XcooBee_Cookie
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * The constructor.
	 *
	 * Private constructor to make sure it cannot be called directly from outside the class.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		// Exit if XcooBee for WordPress is not installed and active.
		if ( ! in_array( 'xcoobee/xcoobee.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			add_action( 'admin_notices', [ $this, 'xcoobee_missing_notice' ] );
			return;
		}

		// Register text strings.
		add_filter( 'xbee_text_strings', [ $this, 'register_text_strings' ], 10, 1 );

		// Include required files.
		$this->includes();

		// Register hooks.
		$this->hooks();

		/**
		 * Fires after the plugin is completely loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'xcoobee_cookie_loaded' );
	}

	/**
	 * XcooBee fallback notice.
	 *
	 * @since 1.0.0
	 */
	public function xcoobee_missing_notice() {
		echo '<div class="notice notice-warning"><p><strong>' . sprintf( esc_html__( 'XcooBee Cookie requires XcooBee for WordPress to be installed and active. You can download %s here.', 'xcoobee' ), '<a href="https://wordpress.org/plugins/xcoobee" target="_blank">XcooBee for WordPress</a>' ) . '</strong></p></div>';
	}

	/**
	 * Includes plugin files.
	 *
	 * @since 1.0.0
	 */
	public function includes() {
		// Global includes.
		include_once XBEE_COOKIE_ABSPATH . 'includes/functions.php';

		// Back-end includes.
		if ( is_admin() ) {
			include_once XBEE_COOKIE_ABSPATH . 'includes/admin/class-xcoobee-cookie-admin.php';
			include_once XBEE_COOKIE_ABSPATH . 'includes/admin/class-xcoobee-cookie-admin-validations.php';
		}

		// Front-end includes.
		if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
			// Nothing to include for now.
		}
	}

	/**
	 * Plugin hooks.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {
		add_filter( 'plugin_action_links_' . XBEE_COOKIE_PLUGIN_BASENAME, [ $this, 'action_links' ], 10, 1 );
		add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'styles' ] );

		// Load front-end scripts only if the add-on is active.
		if ( 'on' === get_option( 'xbee_cookie_active' ) ) {
			add_action( 'wp_footer', [ $this, 'cookie_kit' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'styles' ] );
		}
	}


	/** Returns or print out the cookie kit URI
	 *
	 * @since 1.2.1
	 * @param bool $echo If true echo the URI. 
	 */
	private function get_cookie_kit_uri( $echo = false ) {
		if ( 'test' === xbee_get_env() ) {
			$uri = 'https://testapp.xcoobee.net/scripts/kit/xcoobee-cookie-kit.min.js';
		} else {
			$uri = 'https://app.xcoobee.net/scripts/kit/xcoobee-cookie-kit.min.js';
		}

		if ( ! $echo ) {
			return $uri;
		}

		echo $uri;
	}

	/**
	 * Adds plugin action links.
	 *
	 * @since 1.2.0
	 */
	public function action_links( $links ) {
		$action_links = [
			'settings' => '<a href="' . admin_url( 'admin.php?page=xcoobee&tab=cookie' ) . '" aria-label="' . esc_attr__( 'View XcooBee GDPR Cookie Manager settings', 'xcoobee' ) . '">' . esc_html__( 'Settings', 'xcoobee' ) . '</a>',
		];

		return array_merge( $action_links, $links );
	}

	/**
	 * Loads plugin scripts.
	 *
	 * @since 1.0.0
	 */
	public function scripts() {
		// Back-end scripts.
		if ( 'admin_enqueue_scripts' === current_action() ) {
			wp_enqueue_script( 'xbee-cookie-admin-scripts', XBEE_COOKIE_DIR_URL . 'assets/dist/js/admin/scripts.min.js', [ 'jquery', 'xbee-admin-scripts' ], null, true );
			wp_localize_script( 'xbee-cookie-admin-scripts', 'xbeeCookieAdminParams', [
				'ajaxURL'    => admin_url( 'admin-ajax.php' ),
				'messages'   => [
					'successCookieActivate'        => xbee_get_text( 'message_success_cookie_activate' ),
					'errorCookieActivate'          => xbee_get_text( 'message_error_cookie_activate' ),
					'successCookieDectivate'       => xbee_get_text( 'message_success_cookie_deactivate' ),
					'errorCookieDectivate'         => xbee_get_text( 'message_error_cookie_deactivate' ),
					'successCampaignConnect'       => xbee_get_text( 'message_success_campaign_connect' ),
					'errorCampaignConnect'         => xbee_get_text( 'message_error_campaign_connect' ),
					'successCampaignDisconnect'    => xbee_get_text( 'message_success_campaign_disconnect' ),
					'errorCampaignDisconnect'      => xbee_get_text( 'message_error_campaign_disconnect' ),
					'errorMultipleCampaigns'       => xbee_get_text( 'message_error_multiple_campaigns' ),
					'successCampaignUpdate'        => xbee_get_text( 'message_success_campaign_update' ),
					'errorCampaignUpdate'          => xbee_get_text( 'message_error_campaign_update' ),
					'infoCampaignUpdateNotChanged' => xbee_get_text( 'message_info_campaign_update_not_changed' ),
				]
			 ] );
		}
		// Front-end scripts.
		else {
			wp_enqueue_script( 'xbee-cookie-scripts', XBEE_COOKIE_DIR_URL . 'assets/dist/js/scripts.min.js', [ 'jquery' ], null, false );
		}
	}

	/**
	 * Enqueue plugin styles.
	 *
	 * @since 1.0.0
	 */
	public function styles() {
		// Back-end styles.
		if ( 'admin_enqueue_scripts' === current_action() ) {
			wp_enqueue_style( 'xbee-cookie-admin-styles', XBEE_COOKIE_DIR_URL . 'assets/dist/css/admin/main.min.css', [], false, 'all' );
		}
		// Front-end styles.
		else {
			wp_enqueue_style( 'xbee-cookie-styles', XBEE_COOKIE_DIR_URL . 'assets/dist/css/main.min.css', [], false, 'all' );
		}
	}

	/**
	 * Defines and registers text strings.
	 *
	 * Use `url_name_of_the_url` for URL keys and `message_type_the_message` for message keys.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $strings Text strings array.
	 * @return array The updated text strings array.
	 */
	public function register_text_strings( $strings ) {
		return array_merge( $strings, [
			// Messages.
			'message_success_cookie_activate'          => __( 'Cookie plugin activated!', 'xcoobee' ),
			'message_error_cookie_activate'            => __( 'Could not activate cookie plugin.', 'xcoobee' ),
			'message_success_cookie_deactivate'        => __( 'Cookie plugin deactivated!', 'xcoobee' ),
			'message_error_cookie_deactivate'          => __( 'Could not deactivate cookie plugin.', 'xcoobee' ),
			'message_success_campaign_connect'         => __( 'Campaign connected!', 'xcoobee' ),
			'message_success_campaign_disconnect'      => __( 'Campaign disconnected!.', 'xcoobee' ),
			'message_error_campaign_connect'           => __( 'Could not connect campaign.', 'xcoobee' ),
			'message_error_campaign_disconnect'        => __( 'Could not disconnect campaign.', 'xcoobee' ),
			'message_error_campaign_connected'         => __( 'Campaign already connected.' ),
			'message_error_invalid_campaign'           => __( 'Invalid campaign Id.', 'xcoobee' ),
			'message_error_campaign_data'              => __( 'We could not find a campaign that matches this domain. Please go to your XcooBee account and create a cookie campaign for this site. You will need to be a professional or higher subscriber.', 'xcoobee' ),
			'message_error_multiple_campaigns'         => __( 'Found multiple campaigns for this site.' ),
			'message_success_campaign_update'          => __( 'Campaign data updated!', 'xcoobee' ),
			'message_error_campaign_update'            => __( 'Could not update campaign data.', 'xcoobee' ),
			'message_info_campaign_update_not_changed' => __( 'Campaign data has not changed.' ),
		] );
	}

	/**
	 * Initializes XcooBee Cookie Kit.
	 *
	 * @since 1.0.0
	 */
	public function cookie_kit() {
		$options   = xbee_cookie_get_options();
		$connected = $options['connected'];

		// Prepare option values for the JS kit.
		$campaignReference   = $options['campaign_reference'];
		$companyLogo         = $options['company_logo'];
		$displayOnlyForEU    = 'on' !== $options['disable_outside_eu'] ? 'false' : 'true';
		$expirationTime      = $options['expiration_time'];
		$position            = $options['position'];
		$privacyUrl          = $options['privacy_url'];
		$termsUrl            = $options['terms_url'];
		$requestDataTypes    = json_encode( $options['types'] );
		$checkByDefaultTypes = json_encode( $options['types_default'] );
		$textMessage         = json_encode( $options['text_message'] );
		$displayDoNotSell    = 'on' !== $options['display_donotsell'] ? 'false' : 'true';
		$displayFingerprint  = 'on' !== $options['display_fingerprint'] ? 'false' : 'true';
		$hideBrandTag        = 'on' !== $options['hide_brand_tag'] ? 'false' : 'true';
		$theme               = $options['theme'];
		$detectCountry       = 'on' !== $options['detect_country'] ? 'false' : 'true';
		$defaultCountryCode  = 'on' !== $options['enable_default_country'] ? '' : $options['default_country'];
		$testMode            = 'on' !== $options['test_mode'] ? 'false' : 'true';

		if ( ! $connected ) {
			$privacyUrl = $privacyUrl && get_permalink( $privacyUrl ) ? esc_url( get_permalink( $privacyUrl ) ) : '/';
			$termsUrl   = $termsUrl && get_permalink( $termsUrl ) ? esc_url( get_permalink( $termsUrl ) ) : '/';
		}

		// Scripts registred by the plugin. 
		$scripts = [
			'application' => get_option( 'xbee_cookie_scripts_application', '' ),
			'usage'       => get_option( 'xbee_cookie_scripts_usage', '' ),
			'statistics'  => get_option( 'xbee_cookie_scripts_statistics', '' ),
			'advertising' => get_option( 'xbee_cookie_scripts_advertising', '' ),
		];

		// Cookies registered by `xbee_cookie()`.
		$php_cookies = json_encode( [
			'application' => isset( $GLOBALS['xbee_cookies']['application'] ) ? $GLOBALS['xbee_cookies']['application'] : [],
			'usage'       => isset( $GLOBALS['xbee_cookies']['usage'] ) ? $GLOBALS['xbee_cookies']['usage'] : [],
			'statistics'  => isset( $GLOBALS['xbee_cookies']['statistics'] ) ? $GLOBALS['xbee_cookies']['statistics'] : [],
			'advertising' => isset( $GLOBALS['xbee_cookies']['advertising'] ) ? $GLOBALS['xbee_cookies']['advertising'] : [],
		] );
	?>
		<script type="text/javascript" id="xbee-cookie-kit" src="<?php $this->get_cookie_kit_uri( true ); ?>"></script>
		<script type="text/javascript">
			XcooBee.kit.initialize({
				cookieHandler: 'xckCookieHandler',
				campaignReference: '<?php echo $campaignReference; ?>',
				companyLogo: "<?php echo $companyLogo; ?>",
				displayOnlyForEU: <?php echo $displayOnlyForEU; ?>,
				expirationTime: <?php echo $expirationTime; ?>,
				position: '<?php echo $position; ?>',
				privacyUrl: '<?php echo $privacyUrl; ?>',
				termsUrl: '<?php echo $termsUrl; ?>',
				requestDataTypes: <?php echo $requestDataTypes; ?>,
				checkByDefaultTypes: <?php echo $checkByDefaultTypes; ?>,
				textMessage: <?php echo $textMessage; ?>,
				displayDoNotSell: <?php echo $displayDoNotSell; ?>,
				displayFingerprint: <?php echo $displayFingerprint; ?>,
				hideBrandTag: <?php echo $hideBrandTag; ?>,
				theme: '<?php echo $theme; ?>',
				detectCountry: <?php echo $detectCountry; ?>,
				defaultCountryCode: '<?php echo $defaultCountryCode; ?>',
				testMode: <?php echo $testMode; ?>,
			});

			// Scripts registred by the plugin.
			var pluginScripts = {};
			pluginScripts.application = "<?php echo htmlentities( preg_replace( "/\r|\n/", "", $scripts['application'] ) ); ?>";
			pluginScripts.usage = "<?php echo htmlentities( preg_replace( "/\r|\n/", "", $scripts['usage'] ) ); ?>";
			pluginScripts.statistics = "<?php echo htmlentities( preg_replace( "/\r|\n/", "", $scripts['statistics'] ) ); ?>";
			pluginScripts.advertising = "<?php echo htmlentities( preg_replace( "/\r|\n/", "", $scripts['advertising'] ) ); ?>";

			['application', 'usage', 'statistics', 'advertising'].forEach(function(category) {
				// Parse scripts.
				var parsedScripts = xckParseHtml(pluginScripts[category]);
				var scriptTags = parsedScripts.getElementsByTagName('script');

				// Load scripts as `<xbee-script>` tags.
				xckLoadXbeeJs(scriptTags, category);
			});

			// Cookies registered by `xbee_cookie()`.
			var phpCookies = '<?php echo $php_cookies; ?>';
			phpCookies = JSON.parse(phpCookies);

			function xckCookieHandler(cookieObject) {
				['application', 'usage', 'statistics', 'advertising'].forEach(function(category) {
					if (cookieObject[category]) {
						xckSetCookies(phpCookies[category].set);
					} else {
						xckEraseCookies(phpCookies[category].unset);
					}
				});
			}
		</script>
	<?php
	}

	/**
	 * Activation hooks.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		// Nothing to do for now.
	}
	
	/**
	 * Deactivation hooks.
	 *
	 * @since 1.0.0
	 */
	public static function deactivate() {
		// Nothing to do for now.
	}

	/**
	 * Uninstall hooks.
	 *
	 * @since 1.0.0
	 */
	public static function uninstall() {
		include_once XBEE_COOKIE_ABSPATH . 'uninstall.php';
	}
}

function init_xcoobee_cookie() {
	XcooBee_Cookie::get_instance();
}

add_action( 'plugins_loaded', 'init_xcoobee_cookie' );

// Plugin hooks.
register_activation_hook( __FILE__, [ 'XcooBee_Cookie', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'XcooBee_Cookie', 'deactivate' ] );
register_uninstall_hook( __FILE__, [ 'XcooBee_Cookie', 'uninstall' ] );