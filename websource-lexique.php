<?php
/**
 * Plugin Name:       WebsourceLexique
 * Plugin URI:        https://www.websource.fr/modules-wordpress/module-lexique-seo-wordpress
 * Description:       Lexique / glossaire de termes en front-office avec liste alphabétique, maillage interne "Voir aussi" et shortcode.
 * Version:           1.1.0
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Author:            Websource
 * Author URI:        https://www.websource.fr/
 * License:            GPL v2 or later
 * License URI:        https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:        websource-lexique
 * Domain Path:        /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WL_VERSION', '1.1.0' );
define( 'WL_PLUGIN_FILE', __FILE__ );
define( 'WL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WL_POST_TYPE', 'lexique_term' );

require_once WL_PLUGIN_DIR . 'includes/class-wl-cpt.php';
require_once WL_PLUGIN_DIR . 'includes/class-wl-metabox.php';
require_once WL_PLUGIN_DIR . 'includes/class-wl-shortcode.php';
require_once WL_PLUGIN_DIR . 'includes/class-wl-template.php';

register_activation_hook(
	__FILE__,
	function () {
		WL_CPT::register();
		flush_rewrite_rules();
	}
);

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

function wl_load_textdomain(): void {
	load_plugin_textdomain( 'websource-lexique', false, dirname( plugin_basename( WL_PLUGIN_FILE ) ) . '/languages' );
}
add_action( 'init', 'wl_load_textdomain' );

function wl_init_plugin(): void {
	WL_CPT::init();
	WL_Metabox::init();
	WL_Shortcode::init();
	WL_Template::init();

	if ( is_admin() ) {
		require_once WL_PLUGIN_DIR . 'admin/class-wl-admin.php';
		WL_Admin::init();
		require_once WL_PLUGIN_DIR . 'admin/class-wl-support-box.php';
		WL_Support_Box::init();
	}
}
add_action( 'plugins_loaded', 'wl_init_plugin' );
