<?php
/**
 * Vue : réglages du lexique.
 *
 * @var array $settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'WebsourceLexique — Réglages', 'websource-lexique' ); ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields( 'wl_settings_group' ); ?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="wl_archive_slug"><?php esc_html_e( 'Slug de l’archive du lexique', 'websource-lexique' ); ?></label></th>
				<td>
					<code><?php echo esc_html( home_url( '/' ) ); ?></code>
					<input type="text" id="wl_archive_slug" name="wl_settings[archive_slug]" value="<?php echo esc_attr( $settings['archive_slug'] ?? 'lexique' ); ?>" />
					<code>/</code>
					<p class="description"><?php esc_html_e( 'Par défaut : lexique. Les permaliens seront régénérés automatiquement lors de l’enregistrement.', 'websource-lexique' ); ?></p>
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>

	<h2><?php esc_html_e( 'Shortcode', 'websource-lexique' ); ?></h2>
	<p>
		<?php
		printf(
			/* translators: %s: shortcode tag */
			esc_html__( 'Utilisez %s pour afficher la liste alphabétique du lexique dans n’importe quelle page ou article.', 'websource-lexique' ),
			'<code>[websource_lexique]</code>'
		);
		?>
	</p>
</div>
