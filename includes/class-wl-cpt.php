<?php
/**
 * Custom post type "lexique_term" : un terme de glossaire.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WL_CPT {

	public static function init(): void {
		add_action( 'init', array( __CLASS__, 'register' ) );
	}

	public static function get_slug(): string {
		$settings = get_option( 'wl_settings', array() );
		$slug     = $settings['archive_slug'] ?? 'lexique';
		return sanitize_title( $slug ?: 'lexique' );
	}

	public static function register(): void {
		$slug = self::get_slug();

		$labels = array(
			'name'               => __( 'Lexique', 'websource-lexique' ),
			'singular_name'      => __( 'Terme du lexique', 'websource-lexique' ),
			'add_new'            => __( 'Ajouter un terme', 'websource-lexique' ),
			'add_new_item'       => __( 'Ajouter un terme', 'websource-lexique' ),
			'edit_item'          => __( 'Modifier le terme', 'websource-lexique' ),
			'new_item'           => __( 'Nouveau terme', 'websource-lexique' ),
			'view_item'          => __( 'Voir le terme', 'websource-lexique' ),
			'search_items'       => __( 'Rechercher un terme', 'websource-lexique' ),
			'not_found'          => __( 'Aucun terme trouvé', 'websource-lexique' ),
			'not_found_in_trash' => __( 'Aucun terme dans la corbeille', 'websource-lexique' ),
			'all_items'          => __( 'Tous les termes', 'websource-lexique' ),
			'menu_name'          => __( 'Lexique', 'websource-lexique' ),
		);

		register_post_type(
			WL_POST_TYPE,
			array(
				'labels'        => $labels,
				'public'        => true,
				'has_archive'   => $slug,
				'rewrite'       => array(
					'slug'       => $slug,
					'with_front' => false,
				),
				'menu_icon'     => 'dashicons-book-alt',
				'menu_position' => 25,
				'supports'      => array( 'title', 'editor', 'custom-fields', 'revisions' ),
				'show_in_rest'  => true,
			)
		);
	}
}
