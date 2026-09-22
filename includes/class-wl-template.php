<?php
/**
 * Gestion des templates front-office : archive alphabétique et page de terme
 * avec liste "Voir aussi". Le thème actif peut surcharger ces templates en
 * fournissant ses propres fichiers archive-lexique_term.php / single-lexique_term.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WL_Template {

	public static function init(): void {
		add_filter( 'template_include', array( __CLASS__, 'maybe_override_template' ) );
		add_filter( 'the_content', array( __CLASS__, 'append_related_terms_to_content' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'maybe_enqueue_public_assets' ) );
	}

	public static function maybe_enqueue_public_assets(): void {
		if ( is_post_type_archive( WL_POST_TYPE ) || is_singular( WL_POST_TYPE ) ) {
			wp_enqueue_style( 'wl-public', WL_PLUGIN_URL . 'assets/css/wl-public.css', array(), WL_VERSION );
		}
	}

	/**
	 * Laisse le thème actif fournir son propre template ; sinon utilise celui du plugin.
	 */
	public static function maybe_override_template( string $template ): string {
		if ( is_post_type_archive( WL_POST_TYPE ) ) {
			$theme_template = locate_template( array( 'archive-' . WL_POST_TYPE . '.php' ) );
			if ( $theme_template ) {
				return $theme_template;
			}
			return WL_PLUGIN_DIR . 'templates/archive-lexique_term.php';
		}

		if ( is_singular( WL_POST_TYPE ) ) {
			$theme_template = locate_template( array( 'single-' . WL_POST_TYPE . '.php' ) );
			if ( $theme_template ) {
				return $theme_template;
			}
			return WL_PLUGIN_DIR . 'templates/single-lexique_term.php';
		}

		return $template;
	}

	/**
	 * Ajoute la liste "Voir aussi" à la suite du contenu sur la page single d'un terme,
	 * ce qui fonctionne même si le thème fournit son propre single-lexique_term.php
	 * tant qu'il utilise the_content().
	 */
	public static function append_related_terms_to_content( string $content ): string {
		if ( ! is_singular( WL_POST_TYPE ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		return $content . self::get_related_terms_html( get_the_ID() );
	}

	public static function get_related_terms_html( int $post_id ): string {
		$related = WL_Metabox::get_related_terms( $post_id );
		if ( empty( $related ) ) {
			return '';
		}

		ob_start();
		?>
		<div class="wl-related-terms">
			<h2 class="wl-related-terms-title"><?php esc_html_e( 'Voir aussi', 'websource-lexique' ); ?></h2>
			<ul class="wl-related-terms-list">
				<?php foreach ( $related as $term_post ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $term_post ) ); ?>"><?php echo esc_html( get_the_title( $term_post ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		return (string) ob_get_clean();
	}

	/**
	 * Construit le HTML de la liste alphabétique de tous les termes publiés,
	 * groupés par première lettre -- utilisé par le template d'archive et le shortcode.
	 */
	public static function get_alphabetical_listing_html(): string {
		$terms = get_posts(
			array(
				'post_type'      => WL_POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		if ( empty( $terms ) ) {
			return '<p class="wl-empty">' . esc_html__( 'Aucun terme n’a encore été publié.', 'websource-lexique' ) . '</p>';
		}

		$grouped = array();
		foreach ( $terms as $term_post ) {
			$title  = get_the_title( $term_post );
			$letter = mb_strtoupper( mb_substr( wp_strip_all_tags( $title ), 0, 1 ) );
			if ( ! preg_match( '/[A-Z]/u', $letter ) ) {
				$letter = '#';
			}
			$grouped[ $letter ][] = $term_post;
		}

		ksort( $grouped );

		ob_start();
		?>
		<div class="wl-lexique">
			<nav class="wl-lexique-nav" aria-label="<?php esc_attr_e( 'Navigation alphabétique du lexique', 'websource-lexique' ); ?>">
				<?php foreach ( array_keys( $grouped ) as $letter ) : ?>
					<a href="#wl-letter-<?php echo esc_attr( $letter ); ?>"><?php echo esc_html( $letter ); ?></a>
				<?php endforeach; ?>
			</nav>

			<?php foreach ( $grouped as $letter => $items ) : ?>
				<section class="wl-letter-group" id="wl-letter-<?php echo esc_attr( $letter ); ?>">
					<h2 class="wl-letter-heading"><?php echo esc_html( $letter ); ?></h2>
					<ul class="wl-letter-terms">
						<?php foreach ( $items as $term_post ) : ?>
							<li>
								<a href="<?php echo esc_url( get_permalink( $term_post ) ); ?>"><?php echo esc_html( get_the_title( $term_post ) ); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endforeach; ?>
		</div>
		<?php
		return (string) ob_get_clean();
	}
}
