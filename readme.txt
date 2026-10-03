=== WebsourceLexique ===
Contributors: websource
Tags: glossaire, lexique, dictionnaire, cpt, maillage interne
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 8.1
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Un glossaire/lexique de termes en front-office, avec liste alphabétique et maillage interne "Voir aussi".

== Description ==

WebsourceLexique ajoute un custom post type `lexique_term` pour gérer un glossaire de termes métier directement dans WordPress, avec l'éditeur de blocs (ou classique) natif pour la définition.

**Fonctionnalités :**

* Custom post type `lexique_term` public, avec éditeur WYSIWYG standard, archive et slug personnalisables (par défaut `/lexique/`).
* Une meta box "Termes liés" (dans l'écran d'édition d'un terme) permet de sélectionner d'autres termes du lexique via une liste filtrable, pour construire un maillage interne. Ces termes liés s'affichent en "Voir aussi" en bas de la page du terme.
* Page d'archive listant tous les termes triés par ordre alphabétique et regroupés par première lettre, avec une navigation A-Z.
* Template single dédié affichant la définition complète et la liste "Voir aussi".
* Shortcode `[websource_lexique]` pour afficher la liste alphabétique complète dans n'importe quelle page ou article, en alternative au template d'archive.
* Le thème actif peut surcharger les templates en fournissant ses propres `archive-lexique_term.php` / `single-lexique_term.php`.

== Installation ==

1. Copiez le dossier `websource-lexique` dans `wp-content/plugins/` et activez le plugin.
2. Allez dans **Lexique > Ajouter un terme** pour créer vos premiers termes (titre = le terme, contenu = la définition).
3. Dans la meta box "Termes liés", sélectionnez les autres termes à afficher en "Voir aussi".
4. Consultez `/lexique/` (ou le slug personnalisé défini dans **Lexique > Réglages**) pour voir l'archive alphabétique.

== Frequently Asked Questions ==

= Comment personnaliser l'apparence des pages du lexique ? =

Ajoutez simplement `archive-lexique_term.php` et/ou `single-lexique_term.php` dans votre thème actif : ils seront automatiquement utilisés à la place des templates fournis par le plugin.

= Le maillage "Voir aussi" fonctionne-t-il avec un template de thème personnalisé ? =

Oui : la liste "Voir aussi" est injectée via le filtre `the_content`, donc elle apparaît dès que votre template appelle `the_content()`, qu'il s'agisse du template par défaut du plugin ou d'un template de thème.

= Que fait l'encart « Besoin d'aller plus loin ? » dans l'administration ? =

Il propose aux administrateurs (capacité `manage_options`), uniquement sur l'écran principal du plugin, de contacter Websource, l'agence éditrice, pour un accompagnement sur mesure. Il n'apparaît jamais sur le site public ni dans les e-mails, se masque pour 30 jours par utilisateur (bouton « Masquer ») et ne fait aucune requête externe. Seul un clic sur « Nous contacter » ou « Prendre rendez-vous » ouvre le site websource.fr, avec dans l'adresse le nom du plugin, sa version, la version de WordPress et l'adresse de votre site (domaine uniquement) pour faciliter la réponse de Websource.

== Changelog ==

= 1.1.0 =
* Nouveau : encart « Besoin d'aller plus loin ? » (accompagnement Websource) sur l'écran principal du plugin, réservé aux administrateurs, masquable 30 jours par utilisateur. Au clic sur « Nous contacter » / « Prendre rendez-vous », le nom et la version du plugin, la version de WordPress et le domaine du site sont transmis à Websource via l'URL (paramètres utm_* et ws_*). Aucune requête externe automatique.

= 1.0.0 =
* Version initiale.
