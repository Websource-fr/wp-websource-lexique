<?php
/**
 * Template d'archive par défaut du lexique (utilisé si le thème actif ne fournit
 * pas son propre archive-lexique_term.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main wl-archive-main">
	<header class="page-header">
		<h1 class="page-title"><?php post_type_archive_title(); ?></h1>
	</header>

	<?php echo WL_Template::get_alphabetical_listing_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- déjà échappé dans get_alphabetical_listing_html(). ?>
</main>
<?php
get_footer();
