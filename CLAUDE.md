# CLAUDE.md

Guidance for working in the **LGL Shortcodes** WordPress plugin.

## What this is

`lgl-shortcodes` is an OOP WordPress plugin (v4.7.6, by Digitally Disruptive – Donald
Raymundo) that renders front-end UI for a **leisure-vehicle dealership** site —
caravans, motorhomes, and campervans. It provides shortcodes + Elementor widgets for
search, listing grids, single-vehicle pages, wishlist, compare, finance/enquiry/reserve
forms, and the emails those forms send.

### Critical dependency: the `lgl-import` companion plugin
This plugin does **not** create the vehicle data model. A separate `lgl-import` plugin
is expected to be installed and active; it registers:
- The vehicle **CPTs**: `caravan`, `motorhome`, `campervan`.
- The **taxonomy** `listing-make-model` (hierarchical: top-level term = *make*, child term = *model*).
- The post **meta** (`price`, `year`, `condition`, `berth`, `mileage`, `axles`, `warranty`, `_listing_gallery_ids`, …).
- The options `LGL_IMPORT_OPT_ENABLE_CARAVAN` / `_MOTORHOME` / `_CAMPERVAN`, which toggle each vehicle type on/off.

When reasoning about where a CPT or meta key is *defined*, look in `lgl-import`, not here.
This plugin only *reads* and *displays* that data. The constants above are referenced in
`lgl-shortcodes.php` but defined externally — grep won't find their `define()`.

## Repository layout

```
lgl-shortcodes.php                  # Main class LGL_Shortcodes (~3600 lines): bootstrap, shortcodes,
                                     #   template routing, search AJAX, admin settings, single-template override
includes/
  class-lgl-forms.php               # LGL_Forms: finance/enquiry/reserve forms, form builder UI,
                                     #   submission CPTs (lgl_enquiry_sub, lgl_reserve_sub), submit AJAX
  class-lgl-email-builder.php       # LGL_Email_Builder: templated emails + merge-tag system for form notifications;
                                     #   custom recipient field accepts multiple addresses (comma/semicolon/newline-separated)
  class-lgl-elementor.php           # LGL_Elementor_Integration: registers the "LGL Leisure Vehicles" widget category
  class-lgl-elementor-widgets.php   # One Elementor widget class per shortcode (thin wrappers that output the shortcode)
templates/
  single-lgl.php                    # Single-vehicle page (forced for the vehicle CPTs)
  shortcodes/<shortcode_tag>.php    # One template per shortcode — filename === shortcode tag
  partials/lgl-*.php                # Shared fragments (grid card, meta, modals, form bodies, wishlist/compare buttons)
  admin/lgl-documentation.php       # Content of the admin Documentation page
assets/
  js/main.js                        # Front-end behavior: search, wishlist, sliders, tabs, fancybox gallery
  js/lgl-forms.js                   # Form submit + validation
  css/main.scss -> main.css(.map)   # SCSS source compiled to CSS (see Build below)
  css/lgl-forms.css                 # Hand-written forms CSS
  libs/                             # Bundled vendor libs: select2, choices, slick, tomselect, zoom-master
  svg/                              # Spec icons (berth, mtplm, axles, payload, …) used in vehicle meta display
```

There is **no `package.json`, `composer.json`, or build/test tooling committed.** No
autoloader — classes load via `require_once` at the top of `lgl-shortcodes.php`. The plugin
boots with `new LGL_Shortcodes();` at the bottom of the main file, which in turn does
`new LGL_Forms();` and `new LGL_Email_Builder();` inside its constructor.

## Core pattern: theme-overridable template routing

Every shortcode is registered to the **same** callback, `render_shortcode()`
(`lgl-shortcodes.php`, see `register_shortcodes()` ~line 1206). That method:
1. Sets per-shortcode default attributes (a `$attributes_arr` switch on `$shortcode_tag`).
2. Runs `shortcode_atts()`, then hands off to `load_template($shortcode_tag, $attributes, $content)`.

`load_template()` (~line 1635) is the routing heart:
- Looks for a **theme override** first: `locate_template('lgl-shortcodes/<tag>.php')`
  (i.e. a child/parent theme can override any template by dropping a file in
  `your-theme/lgl-shortcodes/`).
- Falls back to the plugin's `templates/shortcodes/<tag>.php`.
- `extract()`s the attributes into scope and `include`s the file inside an output buffer.

**Implication:** to change what a shortcode renders, edit the matching file in
`templates/shortcodes/`. The shortcode tag, the template filename, and the Elementor widget
all line up 1:1. To add a shortcode: register it in `register_shortcodes()`, add defaults in
`render_shortcode()` if needed, create `templates/shortcodes/<tag>.php`, and (optionally) add
a widget in `class-lgl-elementor-widgets.php` + register it in `class-lgl-elementor.php`.

Single-vehicle pages bypass shortcodes: `force_plugin_single_template()` (hooked at
priority `99999`) forces `templates/single-lgl.php` for the vehicle CPTs, overriding the theme.

`single-lgl.php` exposes two extension points around the `#lgl-primary` wrapper:
- **Before**: fires `do_action('lgl_before_single_primary', $post_id)`, then renders the
  `single_before_primary` setting (HTML/shortcodes) in a `.lgl-before-primary` div.
- **After**: renders the `single_after_primary` setting in `.lgl-after-primary`, then fires
  `do_action('lgl_after_single_primary', $post_id)`.
  Pair the before/after hooks to wrap the entire vehicle block from a plugin/theme.

## Shortcodes (all routed through `render_shortcode`)

`lgl_search`, `lgl_search_results`, `lgl_listing`, `lgl_type_tabs`, `lgl_related_vehicles`,
`lgl_breadcrumbs`, `lgl_compare`, `lgl_compare_duo`, `lgl_mini_compare`, `lgl_wishlist`,
`lgl_mini_wishlist`, `lgl_my_account`, `lgl_mini_account`, `lgl_finance_form`,
`lgl_enquiry_form`, `lgl_reserve_form`.

Notable default attributes (set in `render_shortcode`): `lgl_listing` →
`limit=9, style=style-1, is_carousel=false, is_featured=false`; `lgl_search` →
`layout=horizontal, live_search=true, show_all_filters=true`; `lgl_breadcrumbs` → `style=dark`.

## Data model quick reference

- **Vehicle CPTs** (from `lgl-import`): `caravan`, `motorhome`, `campervan`.
- **Submission CPTs** (registered *here* in `LGL_Forms::register_post_types`): `lgl_enquiry_sub`, `lgl_reserve_sub`.
- **Taxonomy**: `listing-make-model` — hierarchical. AJAX make/model dropdowns walk child
  terms up to their parent to derive makes (`ajax_get_makes` / `ajax_get_models`).
- **Display meta** (read via `get_post_meta`): `price`, `year`, `condition`, `berth`,
  `mileage`, `axles`, `warranty`, `feature`, `sub_title`, `is_featured`,
  `_listing_gallery_ids` (comma-string of attachment IDs → `convertStringToIntArray()`; in
  `single-lgl.php` the resolved array is then filtered with `wp_attachment_is_image()` to
  drop stale/deleted media — without this, a missing attachment renders a blank slider slide.
  The IDs are also scrubbed at the source on the `delete_attachment` hook via
  `scrub_deleted_attachment_from_galleries()`, so deleting a Media Library item removes it
  from every vehicle's gallery/interior meta automatically),
  `_listing_interior_image_id`.
- **Reserve/enquiry meta** (written by `LGL_Forms`): `_lgl_is_reserved`, `_lgl_reserve_mode`,
  `_lgl_reserve_mode_sub`, `_lgl_reserve_status`, `_lgl_reserved_at`, `_lgl_form_data`, `_lgl_product_id`.
- **Wishlist**: stored in user meta `lgl_wishlists` (array of post IDs).
  `get_valid_wishlist()` self-heals — it drops IDs that are no longer `publish` and persists the cleaned list.

## Settings & admin UI

All plugin settings live in a **single option array** `lgl_settings` (registered under
`lgl_settings_group`) to avoid DB bloat. Read it with `get_option('lgl_settings', [])`.

Admin menu structure:
- Top-level **LGL Settings** (`lgl-settings`) → tabbed settings page. Tabs: General,
  Design (typography/colors), Single Page (`single_before_primary`, `single_vehicle_content`,
  `single_after_primary` — all textarea, support shortcodes), Contact Information,
  Field Visibility & Ordering (drag-sortable), LGL Pages & Active Vehicles.
- Submenu **Documentation** (`lgl-documentation`).
- `LGL_Forms` adds a **Form Builder** submenu + links to Enquiry/Reserve Submissions list tables.
- `LGL_Email_Builder` adds an **Email Builder** submenu (Global / Enquiry / Reserve templates).

Two patterns worth knowing:
- **Contact fields are a single source of truth**: `get_contact_fields_definition()` feeds
  both the Contact settings tab *and* the email merge-tag toolbar. Add a contact field there
  and it appears in both places automatically.
- **Design settings → CSS variables**: `inject_dynamic_css()` (hooked to `wp_head`) emits the
  Design-tab fonts/colors as CSS custom properties. `main.scss` consumes those variables, so
  theme colors are configurable from the admin without recompiling SCSS.

## AJAX endpoints

All front-end AJAX uses the nonce action `lgl_search_nonce`, localized to JS as
`lgl_ajax_obj` (`{ ajax_url, nonce }`). Both `wp_ajax_` and `wp_ajax_nopriv_` are registered
for public-facing actions. Handlers:
- Search: `lgl_get_makes`, `lgl_get_models`, `lgl_get_filter_options`, `lgl_fetch_results`.
  `lgl_get_filter_options` uses **faceted self-exclusion**: each facet's available options
  are computed by a query that omits that facet's own constraint, so sibling options remain
  selectable without the user first resetting the dropdown. The shared query logic lives in
  the private helper `build_filter_matching_ids($post_type, $form_data, $all_possible_meta,
  $skip_meta_keys, $exclude_keys)` — pass the facet key(s) to exclude in `$exclude_keys`.
- Wishlist: `lgl_add_to_wishlist`, `lgl_refresh_mini_wishlist`.
- Compare: `lgl_search_vehicles_for_compare`, `lgl_get_compare_table`.
- Account: `lgl_update_account`.
- Forms (in `LGL_Forms`): `lgl_submit_enquiry`, `lgl_submit_reserve`.
- Admin only: `lgl_toggle_featured_status` (list-table star), `lgl_send_test_email`.

## Caching

Model dropdowns and search results use transients (e.g. models cached 12h, keyed per
make + post type; admins bypass the cache). Invalidation hooks:
`save_post_{caravan,motorhome,campervan}` → `clear_lgl_search_cache()`, and
`saved_term` / `delete_term` → `clear_lgl_taxonomy_cache()`. If you add a cached query,
wire it into these clearers so stale data doesn't survive a vehicle/term edit.

## Front-end assets & vendor libs

`enqueue_assets()` loads (front end): select2 + choices.js (dropdowns), swiper (reused from
Elementor if active, else CDN fallback), fancybox v5 (CDN, galleries), then `main.css`,
`main.js`, `lgl-forms.css`, `lgl-forms.js`. `slick`, `tomselect`, and `zoom-master` are
bundled in `assets/libs/` and used in specific spots. `main.js` is jQuery-based; its
`$(document).ready` wires up search, wishlist, sliders, tabs, type tabs, share, and the
fancybox gallery.

## Build & local workflow

- **No automated build/test.** This is a runtime WordPress plugin; you validate by running it
  in a WordPress install with `lgl-import` active and the vehicle CPTs populated.
- **CSS is the only "build" step.** Edit `assets/css/main.scss`, then compile to
  `assets/css/main.css`. No compiler config is committed (`.gitignore` ignores `.sass-cache`,
  implying a Sass CLI / editor "Live Sass Compiler" workflow). **Do not hand-edit `main.css`** —
  it is generated; edit the `.scss` and recompile, or your change will be lost. `lgl-forms.css`
  is hand-written (no SCSS source) — edit it directly.
- PHP must run inside WordPress (depends on `ABSPATH`, the Settings/Shortcode/CPT APIs, `$wpdb`).

## Conventions & gotchas

- **Bump the version in two places** on every release: the plugin header `Version:`
  (`lgl-shortcodes.php` line ~7) *and* the `LGL_SHORTCODES_VERSION` constant (line ~20).
  That constant is the cache-buster passed to `wp_enqueue_*`, so bumping it busts stale
  CSS/JS caches. Keep them in sync.
- **Output is escaped at render time** (`esc_html`, `esc_attr`, `esc_url`) and dynamic SQL
  uses `$wpdb->prepare`. Match that — never echo raw post meta or `$_POST`. AJAX handlers
  start with `check_ajax_referer('lgl_search_nonce', 'nonce')`.
- **SVG uploads are enabled** via `allow_svg_uploads` (upstream of the spec-icon system).
- **Use the static helpers** rather than re-implementing: `LGL_Shortcodes::format_price()`,
  `::convertStringToIntArray()`, `::get_unique_meta_values($post_type, $meta_key)`,
  `::render_html_list_from_string()`. Form/reserve state has its own helpers on `LGL_Forms`
  (`get_finance_settings`, `get_enquiry_settings`, `get_reserve_settings`,
  `get_current_reserve_mode`, `is_reserved`).
- **Vehicle-type-gated features**: list-table "featured" columns and the LGL Pages settings
  only appear for vehicle types whose `LGL_IMPORT_OPT_ENABLE_*` toggle is on. When adding
  per-type admin behavior, iterate `$this->cpt_toggles` and skip disabled types, as the
  existing code does.
- **Theme override before plugin edit**: if a site has `your-theme/lgl-shortcodes/<tag>.php`,
  that file wins over `templates/shortcodes/<tag>.php`. Check for an override before assuming
  the plugin template is what renders.
- **`lgl_search` dropdown layout requires vehicle type before submit**: when `layout=dropdown`
  (global/header search), `main.js` blocks form submission if `#lgl_post_type` is empty —
  it adds `lgl-field-error` and opens the Choices.js dropdown automatically. Without a type,
  the redirect has no destination and would just reload the current page.
- Git history messages are terse (e.g. "css", "cs"); don't rely on them for context.
