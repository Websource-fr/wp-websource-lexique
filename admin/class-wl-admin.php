<?php
/**
 * Réglages du lexique : slug de l'archive (rewrite), configurable.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WL_Admin {

	public static function init(): void {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	public static function register_menu(): void {
		add_submenu_page(
			'edit.php?post_type=' . WL_POST_TYPE,
			__( 'Réglages du lexique', 'websource-lexique' ),
			__( 'Réglages', 'websource-lexique' ),
			'manage_options',
			'websource-lexique-settings',
			array( __CLASS__, 'render_settings_page' )
		);
	}

	public static function register_settings(): void {
		register_setting( 'wl_settings_group', 'wl_settings', array( __CLASS__, 'sanitize_settings' ) );
	}

	public static function sanitize_settings( array $input ): array {
		$existing = get_option( 'wl_settings', array() );
		$output   = $existing;

		$output['archive_slug'] = sanitize_title( $input['archive_slug'] ?? 'lexique' ) ?: 'lexique';

		if ( ( $existing['archive_slug'] ?? 'lexique' ) !== $output['archive_slug'] ) {
			add_action( 'shutdown', 'flush_rewrite_rules' );
		}

		return $output;
	}

	public static function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$settings = get_option( 'wl_settings', array( 'archive_slug' => 'lexique' ) );
		include WL_PLUGIN_DIR . 'admin/views/settings-page.php';
	}
}
