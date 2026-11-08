=== Dynific Addons for Elementor (formerly Anywhere Elementor) ===
Contributors: wpvibes, webtechstreet, tejwanihemant
Tags: elementor, elementor-addon, page-builder, elementor-widget, elementor-template
Requires at least: 5.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Build global Elementor templates and apply them as automatic post and page layouts, or embed them anywhere with a shortcode.

== Description ==

Previously known as **Anywhere Elementor**, Dynific Addons for Elementor lets you design global templates in Elementor and use them across your entire site. Set a template as the automatic layout for all your posts and pages, or embed any template anywhere on your site using the `[dynific_template]` shortcode.

**The "write once, update everywhere" solution for Elementor.**

= How It Works =

1. Go to **Dynific Templates** in your WordPress admin and create a new template.
2. Design it with the Elementor editor just like any other page.
3. Embed it anywhere using the shortcode:

`[dynific_template id="123"]`

Or in PHP templates:

`<?php echo do_shortcode('[dynific_template id="123"]'); ?>`

You can also pass a `post_id` to render the template in the context of a specific post:

`[dynific_template id="123" post_id="456"]`

= Free Features =

**Global Templates via Shortcode**
Create a template once and embed it anywhere using `[dynific_template id="123"]`. Works in pages, posts, sidebars, footers, widget areas, and custom PHP templates.

**Automatic Post and Page Layout Templates**
Assign a Dynific Template as the layout for your posts or pages and it applies automatically, site-wide. No shortcode needed. Build the layout once in Elementor and it reflects everywhere. Custom Post Types, archives, and WooCommerce templates are available in Pro.

**7 Dynamic Elementor Widgets**
All widgets pull data from the current post being displayed:

* Post Title: styled heading with HTML tag control and optional truncation
* Post Content: full content or excerpt with optional read-more / unfold
* Post Image: featured image with lightbox, overlay, aspect ratio, and fallback support
* Post Author: author name, avatar, and bio with link options
* Post Meta: date, comment count, author, taxonomy terms, and custom fields
* Post Navigation: previous and next post links
* Post Comments: comments list and submission form (Beta)

**WPML and Polylang Compatible** for multilingual sites.

---

= Unlock the Full Power with Dynific Addons for Elementor Pro =

The free version gives you the foundation. **[Dynific Addons for Elementor Pro](https://www.wpbuilderaddons.com/dynific-addons-elementor/)** turns it into a complete dynamic site-building platform with 30+ additional widgets, full WooCommerce support, deep ACF integration, conditional visibility rules, and automatic global layouts for every page type on your site.

**Global Template Types**
Apply Elementor templates automatically to any part of your site, no shortcode required:

* Custom Post Type singles
* Archive pages (category, tag, custom taxonomy)
* WooCommerce product pages
* WooCommerce shop and category archive pages
* Search results page
* 404 page

**Advanced Custom Fields (ACF) Integration**
ACF support is one of the core strengths of Dynific Addons Pro. If you build with ACF, Pro gives you a complete toolkit to display any field value inside Elementor:

* ACF Fields: display text, number, date, select, and other simple field types
* ACF Repeater: loop and style repeater rows with full Elementor layout control
* ACF Flexible Content: render flexible content layouts built in ACF directly in Elementor
* ACF Gallery: display ACF gallery fields as carousels, grids, or justified layouts
* ACF Dynamic Tags: use ACF field values (text, image, URL, number, gallery, repeater, group) as dynamic tag values inside any Elementor widget
* ACF Visibility Rules: show or hide Elementor sections based on ACF field values including text match, choices, date range, post relationship, taxonomy, and true/false fields

**30+ Pro Widgets**

* Post Blocks: query and display posts in a grid or list layout
* Post Blocks Advanced: post listing with accordion, tabs, and grid layout options
* Portfolio: filterable portfolio grid with sorting
* Dynamic Calendar: display posts or events in a calendar view
* Breadcrumb: dynamic breadcrumb trail with Yoast SEO and Rank Math support
* Search Form: Elementor-styled search input
* Taxonomy: display taxonomy terms with full style control
* Taxonomy Blocks: term-based post loops
* Post Read More: dynamic read-more link with custom label
* Trigger: conditional display trigger for sections
* Import / Export Templates: move templates between sites
* Duplicate Template: clone any template in one click
* ...and more

**WooCommerce Widgets**
Build fully custom WooCommerce product pages and shop archives with Elementor:

* Woo Products Grid
* Product Price, Rating, Title, SKU, Stock Status
* Product Image Gallery
* Add to Cart button
* Product Tabs
* Product Tags and Categories
* Product Short Description
* WooCommerce Notices
* Product Read More

**Dynamic Tags for Posts and Products**
Use live data as dynamic values in any Elementor widget:

* Post fields: title, URL, date, time, excerpt, featured image, gallery, terms, custom field
* WooCommerce product fields: price, rating, image, gallery, SKU, stock, sale badge, short description

**Pods Framework Support**
Display Pods-powered field data inside any Elementor template using the Pods Fields widget.

**Conditional Visibility Rules**
Show or hide any Elementor section based on real-time conditions:

* Post type, specific page, taxonomy, archive type, search results
* User login status and user role
* Date and time rules
* ACF field values (covered in the ACF section above)

**Dynamic Background Images**
Set Elementor section backgrounds dynamically from a post featured image or ACF image field.

**FacetWP Integration**
Combine FacetWP faceted search with Elementor templates for filtered post listings.

**[Get Dynific Addons for Elementor Pro](https://www.wpbuilderaddons.com/dynific-addons-elementor/)**

---

= Other Plugins by WPVibes =

* [Form Vibes](https://wordpress.org/plugins/form-vibes/): save and manage form submissions inside WordPress with analytics reporting
* [WP Mail Log](https://wordpress.org/plugins/wp-mail-log/): log and review every email sent from your WordPress site
* [Booster Blocks](https://wordpress.org/plugins/booster-blocks/): enhanced Gutenberg blocks with advanced design controls
* [Frontend Product Editor](https://wordpress.org/plugins/frontend-product-editor/): edit WooCommerce product details directly from the frontend
* [Map Engine](https://wordpress.org/plugins/map-engine/): create unlimited Google Maps and OpenStreetMaps

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install through the WordPress plugins screen directly.
2. Activate the plugin through the Plugins screen in WordPress.
3. Go to Dynific Templates in the admin sidebar and create your first global template.
4. Copy the shortcode shown in the template editor and paste it anywhere on your site.

== Frequently Asked Questions ==

= Do I need Elementor Pro to use this plugin? =

No. Dynific Addons for Elementor works with the free version of Elementor. Elementor Pro is not required.

= What is the shortcode format? =

`[dynific_template id="123"]`

Replace 123 with the ID of your Dynific Template. You can also pass `post_id="456"` to render the template in the context of a specific post.

= Is this the same plugin as Anywhere Elementor? =

Yes. Dynific Addons for Elementor was formerly known as Anywhere Elementor. The plugin has been rebranded, but all existing shortcodes and templates continue to work without any changes.

= What is the difference between the free and Pro versions? =

The free version gives you 7 dynamic Elementor widgets, the global template shortcode system, and automatic layout support for standard posts and pages.

Pro adds 30+ additional widgets, automatic global layouts for Custom Post Types, archives, WooCommerce, 404 and search pages, deep ACF integration, WooCommerce widgets, dynamic tags, conditional visibility rules, Pods support, FacetWP integration, and more.

= Can I use this in widget areas and theme builder areas? =

Yes. The `[dynific_template]` shortcode works anywhere WordPress processes shortcodes, including text widgets, theme builder templates, and custom PHP files via `do_shortcode()`.

= Is it compatible with WPML and Polylang? =

Yes. The plugin includes built-in WPML and Polylang compatibility for multilingual sites.

== Screenshots ==

1. Dynific Templates custom post type list where all global templates are managed.
2. Shortcode displayed in the template editor, ready to copy and paste.
3. A Dynific Template embedded on a page via shortcode.
4. The Elementor editor showing the free widgets in the AE Template Elements panel.

== Changelog ==

= 1.3 =
* Added support for creating Single Post Templates
* Shortcode context support: pass `post_id` attribute to render in a specific post context.
* Compatibility check with latest WordPress and Elementor.

= 1.2.14 =
* Plugin rebranding.
* Compatibility check.

= 1.2.13 =
* Tested with the latest WordPress and Elementor release.

= 1.2.12 =
* Fixed: Security issue.
* Tested with latest WordPress and Elementor release for compatibility.

= 1.2.11 =
* Fixed: Open AE templates without being logged in.
* Tested with latest WordPress and Elementor release for compatibility.

= 1.2.10 =
* Tested with latest WordPress and Elementor release for compatibility.

= 1.2.9 =
* Tested with latest WordPress and Elementor release.

= 1.2.8 =
* Tested with latest WordPress and Elementor for compatibility.
* Removed leftover files from removed Freemius integration.

= 1.2.7 =
* Updated compatibility with latest release of Elementor and WordPress.

= 1.2.6 =
* Updated compatibility with latest release of Elementor and WordPress.

= 1.2.5 =
* Updated compatibility with latest release of Elementor and WordPress.
* Security fix.

= 1.2.4 =
* Updated compatibility tags for latest Elementor release.

= 1.2.3 =
* Added compatibility fix for Elementor 3.1.

= 1.2.2 =
* Checked compatibility with latest versions of WordPress and Elementor.
* Fixed double shortcode box when this plugin is activated alongside AnyWhere Elementor Pro.

= 1.2.1 =
* Added filter `ae_multilingual_support` to manage WPML and Polylang compatibility.

= 1.2 =
* Added WPML compatibility.

= 1.1 =
* Option to view and copy shortcode from the All Templates list view.

= 1.0 =
* Updated compatibility with latest version of WordPress and Elementor.

= 0.5 =
* Compatibility with Elementor 1.5.

= 0.4 =
* Fixed issues after recent Elementor updates.

= 0.3 =
* Added Custom Post Type for Global Templates.
* Fixed overlay issue.

= 0.2 =
* Compatibility fix with GeneratePress hooks.
* Enable shortcode to work in widgets.

= 0.1 =
* Plugin initial release.

== Upgrade Notice ==

= 1.3 =
Rebranded from Anywhere Elementor to Dynific Addons for Elementor. All existing shortcodes and templates continue to work without any changes required.
