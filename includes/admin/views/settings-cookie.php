<?php
/**
 * The cookie tab
 *
 * @package XcooBee/Cookie/Admin/Views
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$options   = xbee_cookie_get_options();
$connected = $options['connected'];

// Check API keys.
$test_keys = xbee_test_keys()->result;

// Is PECL APD installed?
$is_apd_installed = function_exists( 'override_function' ) ? true : false;
?>

<?php settings_fields( 'xbee_cookie' ); ?>
<div class="intro">
	<div class="right">
		<h2><?php _e( 'XcooBee Cookie Addon', 'xcoobee' ); ?></h2>
		<p><?php _e( 'The objective of the XcooBee cookie addon is to enable websites to manage their cookie consent more effectively and with less annoyance to their users. Correctly set cookies and remove cookies based on user choice. Even intercept and handle cookie setting for other plugins automatically. Manage cookie consent on XcooBee Privacy network with optional XcooBee account.', 'xcoobee' ); ?></p>
		<div class="activate-cookie">
			<input type="button" class="button button-primary<?php xbee_add_css_class( $options['active'], 'hide', true, true ); ?>" id="xbee-activate-cookie" value="<?php _e( 'Activate Cookie Plugin', 'xcoobee' ); ?>" />
			<input type="button" class="button button-secondary<?php xbee_add_css_class( ! $options['active'], 'hide', true, true ); ?>" id="xbee-deactivate-cookie" value="<?php _e( 'Deactivate Cookie Plugin', 'xcoobee' ); ?>" />
			<div class="xbee-notification" data-notification="activate-cookie"></div>
		</div>
	</div>
	<div class="left">
		<img src="<?php echo XBEE_DIR_URL . 'assets/dist/images/icon-xcoobee-cookie.svg'; ?>" />
	</div>
</div>

<!-- Section: Connect -->
<div class="section">
	<h2 class="headline"><?php _e( 'Connect to XcooBee (optional)', 'xcoobee' ); ?><span class="xbee-connect-indicator<?php xbee_add_css_class( $connected, 'connected', true, true ); ?>" title="<?php echo $connected ? __( 'Connected', 'xcoobee' ) : __( 'Disconnected', 'xcoobee' ); ?>"></span></h2>
	<table class="form-table connect-xbee<?php xbee_add_css_class( ( ! $connected && ! $test_keys ), 'hide', true, true ); ?>">
		<tr>
			<td>
				<p><?php echo sprintf( __( 'If you have not created a cookie campaign for this site, please go to your XcooBee account and <a href="%1$s" target="_blank">create a cookie campaign</a>. You will need to be a professional or higher subscriber.', 'xoobee' ), xbee_get_text( 'url_campaigns') ); ?></p>
				<span class="connect-actions<?php xbee_add_css_class( $connected, 'hide', true, true ); ?>">
					<input type="button" id="xbee-connect-campaign" class="button button-primary" value="<?php _e( 'Connect Campaign', 'xcoobee' ); ?>" />
				</span>

				<span class="disconnect-actions<?php xbee_add_css_class( ! $connected, 'hide', true, true ); ?>">
					<input type="button" id="xbee-disconnect-campaign" class="button button-primary" value="<?php _e( 'Disonnect Campaign', 'xcoobee' ); ?>" />
					<input type="button" id="xbee-refresh-campaign" class="button button-secondary" value="<?php _e( 'Refresh', 'xcoobee' ); ?>" />
					<span class="campaign-last-refresh"></span>
				</span>
				<div class="xbee-notification" data-notification="connect-campaign"></div>
			</td>
		</tr>
	</table>
	<p class="<?php xbee_add_css_class( ( $connected || $test_keys ), 'hide', false, true ); ?>">
		<?php _e( 'To connect to XcooBee you need to provide valid API credentials in the <em>General</em> tab.', 'xcoobee' ); ?>
	</p>
</div>
<!-- End Section: Connect -->

<div id="xbee-cookie-synced-options">
	<?php include_once('settings-cookie-synced-options.php' ); ?>
</div>

<!-- Section: Local Options -->
<div class="section">
	<h2 class="headline"><?php _e( 'Local Options', 'xcoobee' ); ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="xbee_cookie_enable_default_country"><?php _e( 'Default Country', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<input name="xbee_cookie_enable_default_country" type="checkbox" id="xbee_cookie_enable_default_country" <?php checked( $options['enable_default_country'], 'on' ); ?> />
				</fieldset>
				<p class="description"><?php _e( 'Enable default country.', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<tr data-xbee-show-if-checked="xbee_cookie_enable_default_country">
			<th>&nbsp;</th>
			<td>
				<select name="xbee_cookie_default_country" class="regular-text" id="xbee_cookie_default_country">
					<?php foreach( xbee_cookie_get_country_codes() as $cc ) : ?>
					<option value="<?php echo $cc; ?>" <?php selected( $options['default_country'], $cc, true ); ?>><?php echo $cc ?></option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php _e( 'Set default country to.', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_cookie_display_donotsell"><?php _e( 'Do Not Sell', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<input name="xbee_cookie_display_donotsell" type="checkbox" id="xbee_cookie_display_donotsell" <?php checked( $options['display_donotsell'], 'on' ); ?> />
				</fieldset>
				<p class="description"><?php _e( 'Enable do not sell.', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_cookie_test_mode"><?php _e( 'Test Mode', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<input name="xbee_cookie_test_mode" type="checkbox" id="xbee_cookie_test_mode" <?php checked( $options['test_mode'], 'on' ); ?> />
				</fieldset>
				<p class="description"><?php _e( 'Enable test mode.', 'xcoobee' ); ?></p>
			</td>
		</tr>
	</table>
</div>
<!-- End Section: Local Options -->

<!-- Section: Scripts -->
<div class="section">
	<h2 class="headline"><?php _e( 'Script Loading', 'xcoobee' ); ?></h2>
	<p><?php _e( 'We load these scripts based on user selection of consent. You can use the following JavaScript functions to easily set, update, get and delete cookies:' ); ?></p>
	<p><code>xckSetCookie(<strong>String</strong> name, <strong>String</strong> value, <strong>String</strong> days)</code> Set a cookie based on name and value.</p>
	<p><code>xckGetCookie(<strong>String</strong> name)</code> Get current cookie value.</p>
	<p><code>xckEraseCookie(<strong>String</strong> name)</code> Deletes an exiting cookie for the domain.</p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="xbee_cookie_scripts_application"><?php _e( 'Application Cookies', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php _e( 'Application Cookies', 'xcoobee' ); ?></span></legend>
					<textarea name="xbee_cookie_scripts_application" rows="8" id="xbee_cookie_scripts_application" class="large-text code"><?php echo esc_attr( $options['scripts_application'] ); ?></textarea>
				</fieldset>
				<p class="description"><?php _e( 'Insert scripts including the &lt;script&gt; tag.', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_cookie_scripts_usage"><?php _e( 'Personalization Cookies', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php _e( 'Personalizatin Cookies', 'xcoobee' ); ?></span></legend>
					<textarea name="xbee_cookie_scripts_usage" rows="8" id="xbee_cookie_scripts_usage" class="large-text code"><?php echo esc_attr( $options['scripts_usage'] ); ?></textarea>
					<p class="description"><?php _e( 'Insert scripts including the &lt;script&gt; tag.', 'xcoobee' ); ?></p>
				</fieldset>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_cookie_scripts_statistics"><?php _e( 'Statistics Cookies', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php _e( 'Statistics Cookies', 'xcoobee' ); ?></span></legend>
					<textarea name="xbee_cookie_scripts_statistics" rows="8" id="xbee_cookie_scripts_statistics" class="large-text code"><?php echo esc_attr( $options['scripts_statistics'] ); ?></textarea>
					<p class="description"><?php _e( 'Insert scripts including the &lt;script&gt; tag.', 'xcoobee' ); ?></p>
				</fieldset>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_cookie_scripts_advertising"><?php _e( 'Advertising and Marketing Cookies', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php _e( 'Advertising and Marketing Cookies', 'xcoobee' ); ?></span></legend>
					<textarea name="xbee_cookie_scripts_advertising" rows="8" id="xbee_cookie_scripts_advertising" class="large-text code"><?php echo esc_attr( $options['scripts_advertising'] ); ?></textarea>
					<p class="description"><?php _e( 'Insert scripts including the &lt;script&gt; tag.', 'xcoobee' ); ?></p>
				</fieldset>
			</td>
		</tr>
	</table>
</div>
<!-- End Section: Scripts -->

<?php if ( version_compare( PHP_VERSION, '6.0.0', '<=' ) ) : ?>
<!-- Section: Cookie Options -->
<div class="section">
	<h2 class="headline"><?php _e( 'Cookie Options', 'xcoobee' ); ?></h2>
	<?php if ( ! $is_apd_installed ) :?>
	<p class="warning"><?php _e( 'To allow intercepting PHP calls, you need the <a target="_blank" href="https://pecl.php.net/package/APD">PECL APD</a> package to be installed on your server.', 'xcoobee' ); ?></p>
	<?php endif; ?>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="xbee_cookie_intercept_php_calls"><?php _e( 'Allow Intercepting PHP Calls', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<input name="xbee_cookie_intercept_php_calls" type="checkbox" id="xbee_cookie_intercept_php_calls" <?php disabled( ! $is_apd_installed ); ?> <?php checked( $options['intercept_php_calls'], 'on' ); ?> />
				</fieldset>
				<p class="description"><?php _e( 'Allow catching all non-managed calls to create cookies for your site and assign them to a consent category and manage them.', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_cookie_default_php_calls"><?php _e( 'Default Category', 'xcoobee' ); ?></label></th>
			<td>
				<select name="xbee_cookie_default_php_calls" <?php disabled( ! $is_apd_installed ); ?>>
					<option value="application" <?php selected( $options['default_php_calls'], 'application' ); ?>><?php _e( 'Application', 'xcoobee' ); ?></option>
					<option value="usage" <?php selected( $options['default_php_calls'], 'usage' ); ?>><?php _e( 'Personalization', 'xcoobee' ); ?></option>
					<option value="statistics" <?php selected( $options['default_php_calls'], 'statistics' ); ?>><?php _e( 'Statistics', 'xcoobee' ); ?></option>
					<option value="advertising" <?php selected( $options['default_php_calls'], 'advertising' ); ?>><?php _e( 'Advertising and Marketing', 'xcoobee' ); ?></option>
				</select>
				<p class="description"><?php _e( 'Default category for the non-managed cookie calls.', 'xcoobee' ); ?></p>
			</td>
		</tr>
	</table>
</div>
<!-- End Section: Cookie Options -->
<?php endif; ?>

<p class="actions"><?php submit_button( 'Save Changes', 'primary', 'submit', false ); ?></p>