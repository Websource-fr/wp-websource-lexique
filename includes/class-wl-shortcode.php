<?php
/**
 * Shortcode [websource_lexique] : alternative au template d'archive pour les
 * thèmes qui ne veulent pas dépendre de la page d'archive générée par le CPT.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WL_Shortcode {

	public static function init(): void {
		add_shortcode( 'websource_lexique', array( __CLASS__, 'render' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
	}

	public static function register_assets(): void {
		wp_register_style( 'wl-public', WL_PLUGIN_URL . 'assets/css/wl-public.css', array(), WL_VERSION );
	}

	public static function render(): string {
		wp_enqueue_style( 'wl-public' );
		return WL_Template::get_alphabetical_listing_html();
	}
}
