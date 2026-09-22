<?php
/**
 * Template single par défaut du lexique (utilisé si le thème actif ne fournit
 * pas son propre single-lexique_term.php). Le contenu + "Voir aussi" est géré
 * par le filtre the_content, on se contente donc d'appeler the_content() normalement.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<main id="primary" class="site-main wl-single-main">
		<article <?php post_class( 'wl-term' ); ?>>
			<header class="entry-header">
				<h1 class="entry-title wl-term-title"><?php the_title(); ?></h1>
			</header>
			<div class="entry-content wl-term-content">
				<?php the_content(); ?>
			</div>
		</article>
	</main>
	<?php
endwhile;

get_footer();
