<?php
/**
 * Synced Options
 *
 * The cookie kit options that will be synced in connected mode.
 * 
 * @package XcooBee/Cookie/Admin/Views
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$options   = xbee_cookie_get_options();
$connected = $options['connected'];

// Site pages.
$pages = (bool) get_posts( [
	'post_type'      => 'page',
	'posts_per_page' => 1,
	'post_status'    => [ 'publish', 'draft' ],
] );
?>

<!-- Section: Layout -->
<div class="section<?php xbee_add_css_class( $connected, 'disabled', true, true ); ?>">
	<h2 class="headline"><?php _e( 'Layout and Type', 'xcoobee' ); ?></h2>
	<p><?php _e( 'The type of cookies you will use:', 'xcoobee' ); ?></p>
	<table class="form-table condensed">
		<tr>
			<th scope="col"><?php _e( 'Display Option', 'xcoobee' ); ?></th>
			<th scope="col"><?php _e( 'Check by Default', 'xcoobee' ); ?></th>
			<th scope="col"><?php _e( 'Display Cookie Icon &amp; Popup in Corner', 'xcoobee' ); ?></th>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php _e( 'Required Cookies', 'xcoobee' ); ?></span></legend>
					<label for="xbee_cookie_types">
						<input name="xbee_cookie_types[]" type="checkbox" id="xbee_cookie_types" value="application" <?php disabled( $connected ); ?> <?php checked( in_array( 'application', $options['types'] ) || empty( $options['types'] ) ); ?>> <?php _e( 'Required Cookies', 'xcoobee' ); ?>
					</label>
				</fieldset>
			</td>
			<td>
				<input name="xbee_cookie_types_default[]" type="checkbox" value="application" <?php disabled( $connected ); ?> <?php checked( in_array( 'application', $options['types_default'] ) ); ?>>
			</td>
			<td rowspan="4">
				<div class="cookie-position">
					<input type="hidden" name="xbee_cookie_position" value="<?php echo $options['position']; ?>"/>
					<div class="position left-top" data-position="left_top"></div>
					<div class="position right-top" data-position="right_top"></div>
					<div class="position right-bottom" data-position="right_bottom"></div>
					<div class="position left-bottom" data-position="left_bottom"></div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php _e( 'Personalization Cookies', 'xcoobee' ); ?></span></legend>
					<label for="xbee_cookie_usage">
						<input name="xbee_cookie_types[]" type="checkbox" id="xbee_cookie_usage" value="usage" <?php disabled( $connected ); ?> <?php checked( in_array( 'usage', $options['types'] ) ); ?>> <?php _e( 'Personalization Cookies', 'xcoobee' ); ?>
					</label>
				</fieldset>
			</td>
			<td>
				<input name="xbee_cookie_types_default[]" type="checkbox" value="usage" <?php disabled( $connected ); ?> <?php checked( in_array( 'usage', $options['types_default'] ) ); ?>>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php _e( 'Statistics Cookies', 'xcoobee' ); ?></span></legend>
					<label for="xbee_cookie_statistics">
						<input name="xbee_cookie_types[]" type="checkbox" id="xbee_cookie_statistics" value="statistics" <?php disabled( $connected ); ?> <?php checked( in_array( 'statistics', $options['types'] ) ); ?>> <?php _e( 'Statistics Cookies', 'xcoobee' ); ?>
					</label>
				</fieldset>
			</td>
			<td>
				<input name="xbee_cookie_types_default[]" type="checkbox" value="statistics" <?php disabled( $connected ); ?> <?php checked( in_array( 'statistics', $options['types_default'] ) ); ?>>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php _e( 'Advertising and Marketing Cookies', 'xcoobee' ); ?></span></legend>
					<label for="xbee_cookie_advertising">
						<input name="xbee_cookie_types[]" type="checkbox" id="xbee_cookie_advertising" value="advertising" <?php disabled( $connected ); ?> <?php checked( in_array( 'advertising', $options['types'] ) ); ?>> <?php _e( 'Advertising and Marketing Cookies', 'xcoobee' ); ?>
					</label>
				</fieldset>
			</td>
			<td>
				<input name="xbee_cookie_types_default[]" type="checkbox" value="advertising" <?php disabled( $connected ); ?> <?php checked( in_array( 'advertising', $options['types_default'] ) ); ?>>
			</td>
		</tr>
	</table>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="xbee_cookie_theme"><?php _e( 'Theme', 'xcoobee' ); ?></label></th>
			<td>
				<select name="xbee_cookie_theme" class="regular-text" id="xbee_cookie_theme" <?php disabled( $connected ); ?>>
					<option value="popup" <?php selected( $options['theme'], 'popup', true ); ?>><?php _e( 'Popup', 'xcoobee' ); ?></option>
					<option value="overlay" <?php selected( $options['theme'], 'overlay', true ); ?>><?php _e( 'Overlay', 'xcoobee' ); ?></option>
				</select>
				<p class="description"><?php _e( 'Select the theme.', 'xcoobee' ); ?></p>
			</td>
		</tr>
	</table>
</div>
<!-- End Section: Layout -->

<!-- Section: Privacy -->
<div class="section<?php xbee_add_css_class( $connected, 'disabled', true, true ); ?>">
	<h2 class="headline"><?php _e( 'Privacy &amp; Terms', 'xcoobee' ); ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="xbee_cookie_privacy_url"><?php _e( 'Privacy Policy Page', 'xcoobee' ); ?></label></th>
			<td>
				<?php if ( $connected ) : ?>
					<input name="xbee_cookie_privacy_url" type="text" id="xbee_cookie_privacy_url" value="<?php echo esc_attr( $options['privacy_url'] ); ?>" <?php disabled( $connected ); ?> />
				<?php else : ?>
					<?php
					if ( $pages ) :
						wp_dropdown_pages(
							[
								'name'              => 'xbee_cookie_privacy_url',
								'show_option_none'  => __( '&mdash; Select &mdash;' ),
								'option_none_value' => '0',
								'selected'          => esc_attr( $options['privacy_url'] ),
								'post_status'       => [ 'draft', 'publish' ],
								'class'             => 'regular-text',
							]
						);
					?>
					<p class="description"><?php echo sprintf( __( 'Select an existing page or <a href="%1$s">create a new page</a>.', 'xcoobee'), admin_url( 'post-new.php?post_type=page' ) ); ?></p>
					<?php else : ?>
					<p class="description"><?php _e( 'No pages found. <a href="">Create a new page</a>.', 'xcoobee'); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_cookie_terms_url"><?php _e( 'Terms of Service Page', 'xcoobee' ); ?></label></th>
			<td>
				<?php if ( $connected ) : ?>
					<input name="xbee_cookie_terms_url" type="text" id="xbee_cookie_terms_url" value="<?php echo esc_attr( $options['terms_url'] ); ?>" <?php disabled( $connected ); ?> />
				<?php else : ?>
					<?php
					if ( $pages ) :
						wp_dropdown_pages(
							[
								'name'              => 'xbee_cookie_terms_url',
								'show_option_none'  => __( '&mdash; Select &mdash;' ),
								'option_none_value' => '0',
								'selected'          => esc_attr( $options['terms_url'] ),
								'post_status'       => [ 'draft', 'publish' ],
								'class'             => 'regular-text',
							]
						);
					?>
					<p class="description"><?php echo sprintf( __( 'Select an existing page or <a href="%1$s">create a new page</a>.', 'xcoobee'), admin_url( 'post-new.php?post_type=page' ) ); ?></p>
					<?php else : ?>
					<p class="description"><?php _e( 'No pages found. <a href="">Create a new page</a>.', 'xcoobee'); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</td>
		</tr>
	</table>
</div>
<!-- End Section: Privacy -->

<!-- Section: Network Options -->
<div class="section<?php xbee_add_css_class( $connected, 'disabled', true, true ); ?>">
	<h2 class="headline"><?php _e( 'Network Options', 'xcoobee' ); ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="xbee_cookie_expiration_time"><?php _e( 'Remove Icon After', 'xcoobee' ); ?></label></th>
			<td>
				<input name="xbee_cookie_expiration_time" type="number" data-xbee-disallow-chars="eE.-" min="0" max="3600" id="xbee_cookie_expiration_time" value="<?php echo esc_attr( $options['expiration_time'] ); ?>" <?php disabled( $connected ); ?> class="small-text" />
				<p class="description"><?php _e( 'Remove cookie icon after seconds (or 0 to keep).', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_cookie_disable_outside_eu"><?php _e( 'Disable Outside EU', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<input name="xbee_cookie_disable_outside_eu" type="checkbox" id="xbee_cookie_disable_outside_eu" <?php disabled( $connected ); ?> <?php checked( $options['disable_outside_eu'], 'on' ); ?> />
				</fieldset>
				<p class="description"><?php _e( 'Do not display cookie notice outside EU countries.', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_cookie_detect_country"><?php _e( 'Country Detection', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<input name="xbee_cookie_detect_country" type="checkbox" id="xbee_cookie_detect_country" <?php disabled( $connected ); ?> <?php checked( $options['detect_country'], 'on' ); ?> />
				</fieldset>
				<p class="description"><?php _e( 'Allow country detection.', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_cookie_display_fingerprint"><?php _e( 'Fingerprint Consent', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<input name="xbee_cookie_display_fingerprint" type="checkbox" id="xbee_cookie_display_fingerprint" <?php disabled( $connected ); ?> <?php checked( $options['display_fingerprint'], 'on' ); ?> />
				</fieldset>
				<p class="description"><?php _e( 'Ask for fingerprint consent.', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<?php if ( ! $connected ) : ?>
		<tr>
			<th scope="row"><label for="xbee_cookie_hide_brand_tag"><?php _e( 'Hide Brand Tag', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<input name="xbee_cookie_hide_brand_tag" type="checkbox" id="xbee_cookie_hide_brand_tag" <?php disabled( $connected ); ?> <?php checked( $options['hide_brand_tag'], 'on' ); ?> />
				</fieldset>
				<p class="description"><?php _e( 'Check this box to remove the XcooBee branding.', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th scope="row"><label for="xbee_cookie_text_message"><?php _e( 'Cookie Notice', 'xcoobee' ); ?></label></th>
			<td>
				<textarea class="large-text" rows="8" maxlength="1000" name="xbee_cookie_text_message[en-us]" id="xbee_cookie_text_message" <?php disabled( $connected ); ?>><?php echo $options['text_message']['en-us']; ?></textarea>
				<p class="description"><?php _e( 'Cookie notice text.', 'xcoobee' ); ?></p>
			</td>
		</tr>
	</table>
</div>
<!-- End Section: Network Options -->

<script>
	var position = jQuery('#xbee-settings-cookie .cookie-position [name="xbee_cookie_position"]').val();
	if (undefined !== position) {
		jQuery('#xbee-settings-cookie .cookie-position .position.' + position.replace(/_/g, '-')).addClass('clicked');
	}
</script>
