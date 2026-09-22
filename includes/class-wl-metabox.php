<?php
/**
 * Meta box "Termes liés" : sélection multiple, recherchable (via <select multiple>
 * amélioré en JS natif, sans dépendance externe), d'autres termes du lexique.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WL_Metabox {

	const META_KEY = '_wl_related_terms';
	const NONCE    = 'wl_related_terms_nonce';

	public static function init(): void {
		add_action( 'add_meta_boxes', array( __CLASS__, 'register_metabox' ) );
		add_action( 'save_post_' . WL_POST_TYPE, array( __CLASS__, 'save' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
	}

	public static function register_metabox(): void {
		add_meta_box(
			'wl_related_terms',
			__( 'Termes liés (Voir aussi)', 'websource-lexique' ),
			array( __CLASS__, 'render' ),
			WL_POST_TYPE,
			'side',
			'default'
		);
	}

	public static function enqueue_assets( string $hook ): void {
		global $post_type;
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) && WL_POST_TYPE === $post_type ) {
			wp_enqueue_style( 'wl-admin', WL_PLUGIN_URL . 'assets/css/wl-admin.css', array(), WL_VERSION );
		}
	}

	public static function render( WP_Post $post ): void {
		wp_nonce_field( 'wl_save_related_terms', self::NONCE );

		$related_ids = get_post_meta( $post->ID, self::META_KEY, true );
		$related_ids = is_array( $related_ids ) ? array_map( 'intval', $related_ids ) : array();

		$all_terms = get_posts(
			array(
				'post_type'      => WL_POST_TYPE,
				'post_status'    => array( 'publish', 'draft', 'pending' ),
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'exclude'        => array( $post->ID ),
			)
		);
		?>
		<p>
			<input type="text" class="wl-related-filter" placeholder="<?php esc_attr_e( 'Filtrer les termes…', 'websource-lexique' ); ?>" />
		</p>
		<select name="wl_related_terms[]" multiple="multiple" size="10" class="wl-related-select" style="width:100%;">
			<?php foreach ( $all_terms as $term_post ) : ?>
				<option value="<?php echo esc_attr( $term_post->ID ); ?>" <?php selected( in_array( $term_post->ID, $related_ids, true ) ); ?>>
					<?php echo esc_html( get_the_title( $term_post ) ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<p class="description"><?php esc_html_e( 'Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs termes.', 'websource-lexique' ); ?></p>
		<script>
		( function () {
			var input = document.currentScript.previousElementSibling;
			// input pointe déjà vers le <select> ; on récupère plutôt le filtre par classe.
			var wrap = document.currentScript.closest( '#wl_related_terms' );
			if ( ! wrap ) { return; }
			var filter = wrap.querySelector( '.wl-related-filter' );
			var select = wrap.querySelector( '.wl-related-select' );
			if ( ! filter || ! select ) { return; }
			filter.addEventListener( 'input', function () {
				var q = filter.value.toLowerCase();
				Array.prototype.forEach.call( select.options, function ( option ) {
					option.style.display = option.text.toLowerCase().indexOf( q ) === -1 ? 'none' : '';
				} );
			} );
		} )();
		</script>
		<?php
	}

	public static function save( int $post_id ): void {
		if ( ! isset( $_POST[ self::NONCE ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE ] ) ), 'wl_save_related_terms' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$related = isset( $_POST['wl_related_terms'] ) ? array_map( 'intval', (array) $_POST['wl_related_terms'] ) : array();
		$related = array_filter( $related, static fn( $id ) => WL_POST_TYPE === get_post_type( $id ) );

		update_post_meta( $post_id, self::META_KEY, array_values( $related ) );
	}

	/**
	 * Retourne les objets WP_Post liés (publiés uniquement) pour un terme donné.
	 */
	public static function get_related_terms( int $post_id ): array {
		$related_ids = get_post_meta( $post_id, self::META_KEY, true );
		$related_ids = is_array( $related_ids ) ? array_map( 'intval', $related_ids ) : array();

		if ( empty( $related_ids ) ) {
			return array();
		}

		return get_posts(
			array(
				'post_type'      => WL_POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'post__in'       => $related_ids,
				'orderby'        => 'post__in',
			)
		);
	}
}
