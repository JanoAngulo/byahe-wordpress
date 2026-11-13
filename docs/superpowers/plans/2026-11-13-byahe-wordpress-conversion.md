# BYAHE Plan B: WordPress Conversion Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Turn the five approved HTML prototypes (Home, Product, Pricing, Solutions, About) into the `byahe` block theme and `byahe-core` plugin, with demo content, a public Playground blueprint and a release workflow, so a reviewer can open one link and browse the site and its editor.

**Architecture:** The theme owns presentation: `theme.json` (generated settings from `tokens/tokens.json` plus hand-written styles), block styles, templates, parts and PHP patterns. The plugin owns behavior: seven dynamic blocks, the pricing data plus its Block Bindings source, and a `wp byahe seed` WP-CLI command that creates the five pages from the theme's page patterns. One Tailwind v4 build (no preflight) compiles the prototype's `components.css` plus WP glue CSS into one stylesheet loaded on the front end and in the editor. Patterns keep the prototype's section ids and component classes, so the prototype Playwright specs port to WordPress with path changes plus a few markup-specific edits.

**Tech Stack:** WordPress 6.8+ (latest stable in Playground), PHP 8.1+, `@wordpress/scripts` (blocks, `--experimental-modules`, `--blocks-manifest`), Interactivity API, Block Bindings API, `@tailwindcss/cli` v4, Composer + WPCS 3 (PHPCS), `@wp-playground/cli` 3.1.64, Playwright + axe (existing harness), `node:test`.

**Spec:** [`docs/superpowers/specs/2026-11-13-byahe-wordpress-design.md`](../specs/2026-11-13-byahe-wordpress-design.md). Read §6 (brand), §8 (page contents), §9 (architecture), §10 (quality bar). The approved prototype pages in `prototype/` are the design source of truth: copy, ids, classes and layout come from them.

## Global Constraints

Everything in the Global Constraints of [Plan A](2026-11-13-byahe-phase0-and-prototypes.md#global-constraints) still applies (brand, colors, fonts, type scale only, 4px spacing grid, concentric corners, headline phrase breaks, contrast, voice, demo-company footer, no third-party logos, no faces beside fictional people, `.num` on every figure, no jQuery, CTA → Libre → demo dialog, Phase 1 nav only, 360–1440px, reduced motion). Added for Plan B:

- PHP: minimum 8.1 in both headers. WordPress "Requires at least: 6.8" (needed for `wp_register_block_types_from_metadata_collection` and Interactivity derived state closures).
- Namespaces: theme `Byahe\Theme`, plugin `Byahe\Core`. Text domains: `byahe` (theme), `byahe-core` (plugin). Every user-facing string in PHP goes through `__()`/`esc_html__()`/`esc_html_e()` with the right domain; every output is escaped.
- PHPCS with WordPress Coding Standards: zero errors. `wp-scripts lint-js` and `lint-style`: clean.
- Blocks: `block.json` apiVersion 3, dynamic (`render.php`), `viewScriptModule` only on `pricing-toggle` and `demo-dialog`. No front-end JS on any other block.
- Page content is real post content created by `wp byahe seed` from the page patterns. Templates hold only header, footer and content area.
- Every internal URL in PHP is built with `home_url()` (Playground serves the site under a scope path, so `/pricing/` hard-coded breaks).
- Patterns keep the prototype's section ids (core `anchor`) and component classes (`className`). Section groups use `"tagName":"section"`.
- Built files are not committed: `theme/byahe/assets/{css,fonts,images}/`, `plugins/byahe-core/build/`, `plugins/byahe-core/assets/icons/`, `vendor/`.
- Commit messages: Conventional Commits. No AI attribution trailers or mentions anywhere in the repo.

## Decisions Plan B makes (calls the spec left open or that changed)

| Topic | Decision | Why |
|---|---|---|
| Demo content | `wp byahe seed` (plugin WP-CLI command) instead of `content/demo.xml`. It resolves each theme page pattern into blocks (`resolve_pattern_blocks()` + `serialize_blocks()`) and saves that as page content. | Spec §9.4 allows the switch. Saved content is real blocks, and URLs come out right for whatever site URL Playground uses. |
| Photos | Copied into `theme/byahe/assets/images/` at build time, referenced by core Image blocks in PHP patterns. A theme filter on `render_block_core/image` adds `srcset`, `sizes`, `width`, `height` from the 480/800/1200 variants. Alt text and `sizes` come from `scripts/photos.json`. | Media Library import in Playground would need attachment ids inside patterns. Same responsive output, deterministic demo. Deviation from §6.5 noted in TRACKER. |
| Duotone | `settings.color.duotone` preset `navy-yellow` = `["#13205C", "#FFC93C"]`, applied with the Image block's duotone setting. | Core generates the SVG filter; replaces the prototype's hand-written filter. |
| Buttons | Default button (theme.json `elements.button`) is the blue pill. Block styles `secondary` (white) and `accent` (yellow) on core/button. Status pills are a paragraph style `pill` with `pill-paid`/`pill-pending`/`pill-overdue` classes. | Spec's `is-style-pill` on Button is redundant when every button is a pill. |
| Other block styles | Group: `hard-shadow`, `hard-shadow-lg`. Separator: `stripe`. | Spec §9.2. |
| Navigation | Core Navigation block, `overlayMenu: "mobile"`, navigation-link items with real page ids (looked up with `get_page_by_path()` in the header pattern) so core sets `aria-current`. Without JS, the footer lists every page. | Spec §8.6. Core overlay cannot show links without JS. |
| Mobile header order | DOM and visual order match at every width: wordmark, menu toggle, CTA. | Review item: Tab order differed from visual order. |
| Icons | `byahe/icon` dynamic block, `icon` attribute from an allowlist; SVGs copied from `lucide-static` into `plugins/byahe-core/assets/icons/` at build. | Core blocks can't hold inline SVG cleanly; Custom HTML blocks are hostile to marketers. |
| Fees table | `byahe/fee-table` dynamic block reading the pricing data. | Bindings don't support core/table; spec §9.7 wants fees defined once. |
| Release zips in the blueprint | Public blueprint fetches `https://github.com/JanoAngulo/byahe-wordpress/releases/download/demo-latest/<zip>` through `https://playground.wordpress.net/cors-proxy.php?`. Fallback if the proxy refuses: the workflow also pushes built folders to a `demo-build` branch and the blueprint switches to `git:directory` resources. | GitHub release assets send no CORS headers. |

## Review Focus

1. **Editor round-trip:** opening any seeded page in the editor shows zero invalid blocks, and saving without edits leaves the front end unchanged. Pinned in Task 13 (`editor.spec.mjs`).
2. **Pricing without JS:** with JavaScript disabled, Pricing shows monthly prices server-rendered, the Libre button still goes to `about/#demo-note`, and the Home teaser shows the same numbers as the Pricing cards. Pinned in Task 9 and Task 6.
3. **One price edit:** changing a price through the `byahe_core_pricing` filter changes the Pricing card, the yearly value and the Home teaser together. Pinned in Task 3 (mu-plugin fixture test).
4. **Scoped site URL:** every internal link resolves when WordPress lives under a sub-path. Pinned in Task 13 (`links.spec.mjs` runs the link checker against all five pages, parsing ids from HTML; Task 15 manual Playground check covers the real scope path).
5. **Keyboard on mobile header:** at 360px Tab goes wordmark → menu toggle → CTA, the overlay opens with Enter, Esc closes it and focus returns to the toggle. Pinned in Task 4.

---

## File Structure

```
/
├─ composer.json  phpcs.xml.dist                       # Task 1
├─ playwright.wp.config.mjs                            # Task 1: WP e2e (port 9410)
├─ blueprint.local.json  blueprint.json                # generated, Task 14
├─ blueprints/steps.json                               # shared blueprint steps, Task 14
├─ .github/workflows/release.yml                       # Task 15
├─ scripts/
│  ├─ copy-assets.mjs                                  # fonts, photos, icons → theme/plugin
│  ├─ build-blueprints.mjs  lib/blueprints.mjs         # Task 15
│  ├─ build-tokens.mjs  lib/tokens.mjs                 # Task 2 changes
│  └─ photos.json                                      # + "sizes" per photo
├─ tests/
│  ├─ unit/tokens.test.mjs  unit/blueprints.test.mjs  unit/copy-assets.test.mjs
│  └─ wp/ pages.mjs  smoke.spec.mjs  shell.spec.mjs  seed.spec.mjs  pricing-data.spec.mjs
│         home.spec.mjs  product.spec.mjs  pricing.spec.mjs  solutions.spec.mjs  about.spec.mjs
│         editor.spec.mjs  links.spec.mjs  keyboard.spec.mjs  fixtures/mu-price-filter.php
├─ theme/byahe/
│  ├─ style.css  functions.php  theme.base.json  theme.json (generated)
│  ├─ src/theme.css                                    # Tailwind entry (no preflight)
│  ├─ src/wp.css                                       # glue: core block markup ↔ prototype look
│  ├─ inc/assets.php  inc/block-styles.php  inc/images.php  inc/patterns.php
│  ├─ templates/ index.html  page.html  404.html  search.html
│  ├─ parts/ header.html  footer.html
│  └─ patterns/ *.php                                  # see each page task
└─ plugins/byahe-core/
   ├─ byahe-core.php
   ├─ inc/ blocks.php  pricing.php  bindings.php  fields.php  icons.php  cli.php
   └─ src/
      ├─ shared/fields-panel.js
      └─ blocks/ icon/  hero-dashboard/  ui-mockup/  pricing-toggle/  pricing-card/  fee-table/  demo-dialog/
```

`tests/wp/pages.mjs` exports `PAGES = { home: '/', product: '/product/', pricing: '/pricing/', solutions: '/solutions/', about: '/about/' }`. WP specs import helpers from `../e2e/helpers.mjs` and call `runPageBasics(test, PAGES.x)`.

---

### Task 1: WordPress toolchain and WP test harness

**Files:**
- Modify: `package.json`, `.gitignore`
- Create: `composer.json`, `phpcs.xml.dist`, `playwright.wp.config.mjs`, `scripts/copy-assets.mjs`, `theme/byahe/src/theme.css`, `tests/wp/pages.mjs`, `tests/wp/smoke.spec.mjs`, `tests/unit/copy-assets.test.mjs`

**Interfaces:**
- Produces npm scripts: `build:assets` (`node scripts/copy-assets.mjs`), `build:css` (`tailwindcss -i theme/byahe/src/theme.css -o theme/byahe/assets/css/theme.css --minify`), `build:blocks` (`wp-scripts build --webpack-src-dir=plugins/byahe-core/src --output-path=plugins/byahe-core/build --experimental-modules --blocks-manifest`), `build:wp` (tokens → assets → css → blocks), `serve:wp` (Playground CLI on port 9410, no `--login`, mounts theme and plugin, `--blueprint=./blueprint.local.json`), `dev:wp` (unchanged but runs `build:wp` first), `test:wp` (`playwright test -c playwright.wp.config.mjs`), `lint:php` (`composer exec phpcs`), `lint:js` (`wp-scripts lint-js plugins/byahe-core/src`), `lint:css` (`wp-scripts lint-style "theme/byahe/src/**/*.css" "plugins/byahe-core/src/**/*.css"`), `lint` (all three).
- Produces `copyAssets({ root }): Promise<{ fonts: string[], images: string[], icons: string[] }>` exported from `scripts/copy-assets.mjs` (CLI runs it when executed directly). Copies `prototype/src/fonts/cubao-free-variable.woff2`, Inter and Space Grotesk `latin-wght-normal` WOFF2 from `@fontsource-variable/*` into `theme/byahe/assets/fonts/`; `prototype/public/images/*.webp` into `theme/byahe/assets/images/`; `scripts/photos.json` to `theme/byahe/assets/images/photos.json`; the allowlisted Lucide SVGs (list exported as `ICONS` from the same module, filled in Task 5) into `plugins/byahe-core/assets/icons/`.

- [ ] **Step 1: Install dev dependencies** — `npm i -D @wordpress/scripts @tailwindcss/cli` and `composer require --dev wp-coding-standards/wpcs:^3 dealerdirect/phpcodesniffer-composer-installer`. `phpcs.xml.dist` scans `theme/byahe` and `plugins/byahe-core` (excluding `build`, `assets`, `node_modules`), ruleset `WordPress`, `minimum_supported_wp_version` 6.8, `testVersion` 8.1-, text domains `byahe,byahe-core`, prefixes `byahe,Byahe`.
- [ ] **Step 2: Write the failing unit test** `tests/unit/copy-assets.test.mjs`: run `copyAssets({ root: tmpCopyOfFixture })` against a temp dir seeded with the real source files; assert `fonts` equals `['cubao-free-variable.woff2', 'inter-latin-wght-normal.woff2', 'space-grotesk-latin-wght-normal.woff2']`, every `prototype/public/images/*.webp` appears in `images`, and `photos.json` exists in the target. (Pass `root` so the test never writes into the real theme.)
- [ ] **Step 3: Run** `npm run test:unit` — expect FAIL (module missing).
- [ ] **Step 4: Implement `copy-assets.mjs`.** `ICONS` starts as `[]`.
- [ ] **Step 5: Create `theme/byahe/src/theme.css`:** `@import "tailwindcss/theme.css" layer(theme); @import "tailwindcss/utilities.css" layer(utilities);` (no preflight), `@import "../../../prototype/src/tokens.css"`, `@import "../../../prototype/src/components.css"`, `@source "../patterns"; @source "../parts"; @source "../templates"; @source "../../../plugins/byahe-core/src";`. Font faces come from theme.json (Task 2), not this file.
- [ ] **Step 6: Create `playwright.wp.config.mjs`:** `testDir: 'tests/wp'`, same `desktop`/`mobile` projects as `playwright.config.mjs`, `baseURL: 'http://127.0.0.1:9410'`, `webServer: { command: 'npm run build:wp && npm run serve:wp', url: 'http://127.0.0.1:9410/', timeout: 240_000, reuseExistingServer: !process.env.CI }`, `workers: 2` (Playground is single-process PHP).
- [ ] **Step 7: Write `tests/wp/smoke.spec.mjs`:** `GET /` returns 200; the page has a `link[rel=stylesheet][href*="themes/byahe/assets/css/theme.css"]` (fails until Task 4 enqueues it — mark that assertion `test.fixme` here and un-fixme it in Task 4); `body` has class `wp-theme-byahe`.
- [ ] **Step 8: Run** `npm run build:wp && npm run test:wp -- smoke` — expect PASS (fixme skipped). `npm run lint:php` — expect zero errors on the current stubs (fix any header doc issues).
- [ ] **Step 9: `.gitignore`** adds `vendor/`, `theme/byahe/assets/fonts/`, `theme/byahe/assets/images/`, `plugins/byahe-core/assets/icons/`. Commit: `build(wp): add block, css and php lint toolchain with a WP e2e harness`.

---

### Task 2: Tokens and prototype review fixes

Clears every "Carry into Plan B" item that lives in shared or prototype code, before conversion copies it.

**Files:**
- Create: `prototype/src/base.css`
- Modify: `tokens/tokens.json`, `scripts/lib/tokens.mjs`, `tests/unit/tokens.test.mjs`, `prototype/src/components.css`, `prototype/src/styles.css`, `prototype/src/dialog.js`, `prototype/pricing.html`, `prototype/index.html`, `tests/e2e/helpers.mjs`, `tests/e2e/links.spec.mjs`, `tests/e2e/components.spec.mjs`, `tests/e2e/pricing.spec.mjs`, `scripts/photos.json`

**Interfaces:**
- Produces in `tokens.json`: `font.<slug>.files: [{ src: "file:./assets/fonts/<name>.woff2", weight: "<range>", stretch?: "64% 177%" }]`; `shadow.hard-md: "6px 6px 0 #13205C"`; `shadowOffset.xs: "3px"`; `radius.sm: "6px"`.
- Produces `toThemeJsonSettings()` output with `typography.fontFamilies[].fontFace` (`fontFamily`, `fontWeight`, `fontStyle: "normal"`, `fontStretch` when given, `fontDisplay: "swap"`, `src: [file]`), font size names from this map: `sm→Small, base→Base, lg→Large, xl→Extra large, 2xl→2× large, 3xl→3× large, 4xl→4× large, 5xl→5× large, 6xl→6× large`; `settings.color.duotone: [{ slug: "navy-yellow", name: "Navy and yellow", colors: ["#13205C", "#FFC93C"] }]`; `radius` and `shadowOffset` exposed as `settings.custom.radius` and `settings.custom.shadowOffset` (theme.json v3 has no radius presets).
- Produces Tailwind `@theme`: `--shadow-hard-md`, `--radius-sm`, and `--shadow-offset-xs` (plain custom property).

- [ ] **Step 1: Write failing unit tests** in `tests/unit/tokens.test.mjs`: (a) `fontFamilies.find(f => f.slug === 'display').fontFace[0]` deep-equals `{ fontFamily: 'Cubao Free', fontWeight: '400', fontStyle: 'normal', fontStretch: '64% 177%', fontDisplay: 'swap', src: ['file:./assets/fonts/cubao-free-variable.woff2'] }`; body face weight `'100 900'`, heading `'300 700'`; (b) font size names are exactly the mapping above in order; (c) duotone preset present; (d) `toTailwindTheme` contains `--shadow-hard-md: 6px 6px 0 #13205C;` and `--radius-sm: 6px;`.
- [ ] **Step 2: Run** `npm run test:unit` — expect the four new tests FAIL.
- [ ] **Step 3: Implement** the token and lib changes; run `npm run build:tokens`.
- [ ] **Step 4: Run** `npm run test:unit` — PASS.
- [ ] **Step 5: Snap off-token values in `components.css`:** button hover `6px` and card hover `7px` → `var(--shadow-hard-md)`; colored `3px 3px 0 <color>` → `var(--shadow-offset-xs) var(--shadow-offset-xs) 0 <color>`; `border-radius: 6px` → `var(--radius-sm)`. Add a component test in `components.spec.mjs`: every computed `box-shadow` offset on `components.html` is one of `3px, 4px, 6px, 8px` and every `border-radius` is one of `0px, 6px, 10px, 999px` (ignoring elements inside `svg`).
- [ ] **Step 6: Reduced motion delays:** in the `prefers-reduced-motion` block of `styles.css` add `animation-delay: 0s !important; transition-delay: 0s !important;`. Extend `expectNoMotionWhenReduced` in `tests/e2e/helpers.mjs` to also flag any `animationDelay`/`transitionDelay` > 0.01s. Move the whole reduced-motion block plus `:focus-visible` and `td, th, .num` rules into a new `prototype/src/base.css` imported by `styles.css`, so Task 4 can import the same file into `theme.css`.
- [ ] **Step 7: Demo dialog backdrop:** in `dialog.js`, close on click only when the pointer is outside `dialog.getBoundingClientRect()`. Test in `components.spec.mjs`: clicking at the dialog's inner padding (5px inside its top-left corner) leaves it open; clicking at viewport (5, 5) closes it.
- [ ] **Step 8: Pricing plan names:** `<p class="tier__name">` → `<h3 class="tier__name">` on `pricing.html` and the Home teaser; keep the visual size by setting `font-size`/`line-height` on `.tier__name`. Test in `pricing.spec.mjs`: `page.getByRole('heading', { level: 3, name: 'Negosyo' })` is visible inside `#negosyo`.
- [ ] **Step 9: Home hero `sizes`:** change the hero photo `sizes` to `(min-width: 1024px) 45vw, 100vw` (measure the column at 1280px and match). Add `"sizes"` to every entry in `scripts/photos.json` with the value each photo uses in the prototype.
- [ ] **Step 10: Link checker parses ids:** in `links.spec.mjs` replace substring matching with a parse: `new Set([...html.matchAll(/\sid="([^"]+)"/g)].map(m => m[1]))`, and assert `ids.has(fragment)`. Add a negative test: a fragment `hero-title-x` against `index.html` is reported missing.
- [ ] **Step 11: Run** `npm test` — full suite PASS. Commit: `fix(prototype): clear review items before the WordPress build`.

---

### Task 3: Plugin foundation — blocks loader, pricing data, bindings, seed command

**Files:**
- Modify: `plugins/byahe-core/byahe-core.php`, `blueprint.local.json`, `package.json` (`serve:wp` mounts the fixture into `/wordpress/wp-content/mu-plugins/`)
- Create: `plugins/byahe-core/inc/{blocks,pricing,bindings,cli}.php`, `tests/wp/seed.spec.mjs`, `tests/wp/pricing-data.spec.mjs`, `tests/wp/fixtures/mu-price-filter.php`

**Interfaces:**
- Produces `Byahe\Core\pricing(): array` with shape `['tiers' => ['libre'|'negosyo'|'pro'|'enterprise' => ['name' => string, 'monthly' => ?int, 'yearly' => ?int, 'label' => ?string, 'period_monthly' => string, 'period_yearly' => string, 'badge' => ?string]], 'fees' => list<['method' => string, 'fee' => string]>]`, passed through `apply_filters( 'byahe_core_pricing', $defaults )`. Defaults (spec §8.3, prototype): libre 0/0 periods `forever`/`forever`; negosyo 499/4990 `per month`/`per year`, badge `Most popular`; pro 1499/14990; enterprise null/null, label `Let's talk`, periods `custom pricing`. Fees: `QR Ph` `1.0%`; `Credit and debit cards` `2.9% + ₱15`; `Bank transfer (InstaPay, PESONet)` `₱10 flat`.
- Produces `Byahe\Core\format_peso( int $amount ): string` → `₱1,499` (`number_format_i18n` is locale-dependent; use `'₱' . number_format( $amount )`).
- Produces `Byahe\Core\tier_field( string $tier, string $field, string $billing = 'monthly' ): string` — fields `name`, `price` (formatted amount or `label`), `period`, `badge`; unknown tier/field returns `''`.
- Produces Block Bindings source `byahe/pricing` (label "BYAHE pricing"), args `{ tier, field, billing? }`, value = `tier_field()`. Usable on core/paragraph and core/heading `content`.
- Produces WP-CLI command `wp byahe seed` (class `Byahe\Core\Seed_Command`, loaded only when `WP_CLI` is defined). Pages: `home`→`byahe/page-home` (title "Home"), `product`, `pricing`, `solutions`, `about` (titles capitalized). For each: find by path or create; content = `serialize_blocks( resolve_pattern_blocks( parse_blocks( '<!-- wp:pattern {"slug":"byahe/page-<slug>"} /-->' ) ) )` (empty string if the pattern isn't registered). Sets `show_on_front=page`, `page_on_front=<home id>`, `permalink_structure=/%postname%/`, flushes rewrites, trashes "Hello world!" and "Sample Page". Idempotent: running twice leaves exactly one page per slug. Filter `byahe_core_seed_pages` exposes the slug→title map.
- Produces `Byahe\Core\register_blocks()` on `init`: `wp_register_block_types_from_metadata_collection( __DIR__ . '/../build/blocks', __DIR__ . '/../build/blocks-manifest.php' )` — use the exact paths `build:blocks` writes; skip silently if the manifest is missing.

- [ ] **Step 1: Blueprint seeding** — `blueprint.local.json` steps after activation: `{ "step": "wp-cli", "command": "wp byahe seed" }`. (Task 15 later generates this file.)
- [ ] **Step 2: Write `tests/wp/seed.spec.mjs`** (desktop only): `/product/`, `/pricing/`, `/solutions/`, `/about/` return 200; `/` has body class `home` and `page`; `/sample-page/` returns 404; the REST endpoint `/?rest_route=/wp/v2/pages&per_page=100` lists exactly one page per slug.
- [ ] **Step 3: Write `tests/wp/pricing-data.spec.mjs`:** the test server mounts `tests/wp/fixtures/mu-price-filter.php` into `wp-content/mu-plugins/` (add the mount to `serve:wp`); the fixture registers a `byahe_core_pricing` filter that acts only when a `byahe_test_price` query arg is present, setting negosyo monthly to 555. It also adds a REST route `byahe-test/v1/field?tier=&field=&billing=` returning `tier_field()` — assert `negosyo/price/monthly` = `₱499`, `negosyo/price/yearly` = `₱4,990`, `enterprise/price` = `Let's talk`, `libre/period` = `forever`, `nope/price` = `''`, and with `byahe_test_price=1` → `₱555`. Task 6 and Task 9 add page-level assertions for the same filter.
- [ ] **Step 4: Run** `npm run test:wp -- seed pricing-data` — FAIL.
- [ ] **Step 5: Implement** the four `inc/` files and require them from `byahe-core.php`.
- [ ] **Step 6: Run** the two specs — PASS. `npm run lint:php` — zero errors.
- [ ] **Step 7: Commit:** `feat(core): add pricing data, bindings source and the seed command`.

---

### Task 4: Theme foundation — styles, block styles, templates, header, footer

**Files:**
- Modify: `theme/byahe/theme.base.json`, `theme/byahe/functions.php`, `theme/byahe/src/theme.css`, `theme/byahe/templates/index.html`, `tests/wp/smoke.spec.mjs`
- Create: `theme/byahe/inc/{assets,block-styles,images,patterns}.php`, `theme/byahe/src/wp.css`, `theme/byahe/templates/{page,404,search}.html`, `theme/byahe/parts/{header,footer}.html`, `theme/byahe/patterns/{header,footer,cta-band,page-home,page-product,page-pricing,page-solutions,page-about}.php`, `tests/wp/shell.spec.mjs`

**Interfaces:**
- Produces `Byahe\Theme\page_url( string $slug ): string` (in `inc/patterns.php`): `home_url( '/' . $slug . '/' )`, `home_url( '/' )` for `home`. Every pattern uses it for internal links, e.g. `page_url( 'pricing' ) . '#libre'`.
- Produces `Byahe\Theme\photo( string $slug, array $args = [] ): string` (in `inc/images.php`): returns core/image block markup for a photo from `assets/images/photos.json` (`url` = theme file URI of `<slug>.webp`, `alt` from JSON unless `$args['alt']`, `className` from `$args['class']`, duotone `navy-yellow` when `$args['duotone']`). A `render_block_core/image` filter adds `srcset` (`-480/-800/-1200.webp` variants that exist), `sizes` (from JSON), `width`, `height` (from the original file via `wp_getimagesize`), and `fetchpriority="high"` + no `loading` when `$args['priority']` produced the `is-priority` class; else `loading="lazy"`.
- Produces block styles: `core/group` `hard-shadow`, `hard-shadow-lg`; `core/button` `secondary`, `accent`; `core/separator` `stripe`; `core/paragraph` `pill`. CSS for each lives in `src/wp.css`, reusing the prototype rules by adding the WP selector to the existing selector list in `components.css` where the markup lines up (e.g. `.card-hard, .is-style-hard-shadow`) and writing WP-only rules in `wp.css` where it doesn't.
- Produces pattern category `byahe` ("BYAHE") and page patterns `byahe/page-<slug>` with `Block Types: core/post-content`, `Post Types: page`, `Inserter: no` — Task 4 makes them stubs (one `core/heading` level 1 with the page title, then `byahe/cta-band`); page tasks replace them.

- [ ] **Step 1: Write `tests/wp/shell.spec.mjs`** porting from `tests/e2e/components.spec.mjs` (paths via `PAGES`): skip link focuses `main`; header has wordmark link to `/`, primary nav has exactly Product, Solutions, Pricing, About; current page link has `aria-current="page"` on `/pricing/`; CTA text `Sakay na →` with href ending `/pricing/#libre`; mobile: Tab order is wordmark → toggle → CTA and their `x` positions increase in that order, Enter opens the overlay, Esc closes it and focus returns to the toggle; JS disabled: footer nav contains links to all four pages; footer contains the demo disclaimer and links to `/about/#credits` and the GitHub repo; stripe separator shows red, yellow and blue; h1/h2 use Cubao Free and `document.fonts.check('1em "Cubao Free"')` is true; h3 uses Space Grotesk; focus ring visible on a button; `link[rel=preload][as=font]` exists for Cubao and Inter; 404 page (`/no-such-page/`) returns 404 with one h1 and the shell. Plus `runPageBasics(test, PAGES.about)` on the stub page.
- [ ] **Step 2: Run** — FAIL.
- [ ] **Step 3: theme.base.json styles:** `styles.color` (text navy, background cream); `styles.typography` (body family, `base` size, line height 1.6); `styles.elements.h1/h2` (display family, weight 400, `fontSynthesis` via `wp.css` since theme.json lacks it), `h3/h4` (heading family, 700); `h1` size `5xl`, `h2` `4xl`, `h3` `xl`, `h4` `lg` with the token line heights; `elements.link` (navy, underline offset 3px); `elements.button` (blue bg, white text, 2px navy border, radius 999px, `hard-sm` shadow, heading font 700); `spacing.blockGap` `var(--wp--preset--spacing--40)`; `spacing.padding` left/right `var(--wp--preset--spacing--30)` with `useRootPaddingAwareAlignments: true`. `templateParts` header/footer and `customTemplates` none.
- [ ] **Step 4: `inc/assets.php`:** enqueue `assets/css/theme.css` (version = file mtime) on `wp_enqueue_scripts`; `add_editor_style( 'assets/css/theme.css' )`; preload links for `cubao-free-variable.woff2` and `inter-latin-wght-normal.woff2` on `wp_head` priority 1. `src/theme.css` also imports `prototype/src/base.css` and `./wp.css`.
- [ ] **Step 5: Templates and parts:** `index.html`/`page.html` = header part, `main` group (`tagName: main`, anchor `main`) holding `core/post-content` (layout `constrained` off: `{"layout":{"type":"default"}}` so full-width sections work), footer part. `404.html` and `search.html` keep header/footer and a short Taglish heading with plain-English body, search uses core/query + search block. Parts contain only `<!-- wp:pattern {"slug":"byahe/header"} /-->` / `byahe/footer` so links go through `page_url()`.
- [ ] **Step 6: Header pattern:** skip link is core's (block themes print it when `main` has an id; verify, else add one in `functions.php`); wordmark as a core/paragraph with link and `wordmark` classes; core/navigation with `overlayMenu: "mobile"`, `navigation-link` items with `id`, `kind: post-type`, `type: page` from `get_page_by_path()` (fall back to `url` only when the page doesn't exist); core/button CTA. Match the prototype header look at 360/768/1280.
- [ ] **Step 7: Footer and CTA band patterns:** copy structure, copy text and classes from `prototype/partials/footer.html` and `cta-band.html`; stripe = core/separator `is-style-stripe`.
- [ ] **Step 8: Run** `npm run test:wp -- shell smoke` (un-fixme the stylesheet assertion) — PASS. `npm run lint` — clean.
- [ ] **Step 9: Commit:** `feat(theme): add styles, block styles, templates, header and footer`.

---

### Task 5: Shared editor fields, `byahe/icon` and `byahe/hero-dashboard`

**Files:**
- Create: `plugins/byahe-core/inc/{fields,icons}.php`, `plugins/byahe-core/src/shared/fields-panel.js`, `plugins/byahe-core/src/blocks/icon/{block.json,index.js,edit.js,render.php}`, `plugins/byahe-core/src/blocks/hero-dashboard/{block.json,schema.json,index.js,edit.js,render.php}`
- Create: `tests/wp/helpers.mjs`, `tests/wp/home.spec.mjs`
- Modify: `scripts/copy-assets.mjs` (`ICONS`), `tests/unit/copy-assets.test.mjs`

**Interfaces:**
- Schema format (JSON, read by PHP with `wp_json_file_decode( $path, [ 'associative' => true ] )` and imported by JS): `{ "fields": { "<key>": { "label": string, "default": string } }, "lists": { "<key>": { "label": string, "columns": { "<col>": { "label": string, "options"?: string[] } }, "default": [ { "<col>": string } ] } }, "ariaLabel": string }`. `ariaLabel` may use `{field}` placeholders and `{list.count}`.
- Produces `Byahe\Core\resolve_fields( array $schema, array $values ): array` → `[ 'fields' => [key => string], 'lists' => [key => list<array>], 'aria' => string ]`, values override defaults key by key; list values replace the whole default list; unknown keys dropped; every string passed through `sanitize_text_field`.
- Produces `FieldsPanel({ schema, values, onChange })` React component: one `TextControl` per field; per list, rows of `TextControl`/`SelectControl` (when `options`) with Add/Remove row buttons. Block attribute for all schema blocks: `values` (`type: object`, default `{}`).
- Produces `Byahe\Core\icon_svg( string $name, string $class = '' ): string` — inline Lucide SVG with `aria-hidden="true" focusable="false"`, empty string for names outside `ICONS`.
- `byahe/icon`: attributes `icon` (string, enum = `ICONS`), `className`. Renders the SVG wrapped in the prototype's `card-sign` markup when the block has class `card-sign`; otherwise bare SVG. `ICONS` = the Lucide names whose path data match the inline SVGs in `prototype/index.html` and `prototype/product.html` (identify each by comparing against `node_modules/lucide-static/icons/*.svg`).
- `byahe/hero-dashboard`: schema fields `label` ("Collected this month"), `total` ("₱482,310"), `delta` ("+18%"), `deltaLabel` ("vs October"); lists `bars` (column `height`, default 38,52,46,64,71,92 — last bar gets `is-current`), `invoices` (columns `number`, `customer`, `amount`, `status` options `Paid|Pending|Overdue`), defaults from `prototype/partials/mockups/hero-dashboard.html`. `ariaLabel` reproduces the prototype's label from values. Output markup equals the prototype partial (same classes) apart from attribute order.

- [ ] **Step 1: Write the failing test** in `tests/wp/home.spec.mjs` (new file; Home content arrives in Task 6, so this uses a throwaway draft page): log in, create a draft page through the REST API containing `<!-- wp:byahe/hero-dashboard {"values":{"total":"₱1,000"}} /-->` and `<!-- wp:byahe/icon {"icon":"<first ICONS entry>"} /-->`, open its permalink: `.hero-dashboard__total` text `₱1,000`; three `.hero-dashboard__list li`; the root has `role="img"` and an `aria-label` containing `₱1,000`; `svg[aria-hidden="true"]` present for the icon; no `<script>` from `byahe-core` on the page. Delete the draft in `afterAll`. Add a `tests/wp/helpers.mjs` with `login(page)`, `createPage(request, { title, content, status })` (REST with nonce from `wpApiSettings` after login) and `deletePage(request, id)`; later tasks reuse them.
- [ ] **Step 2: Run** — FAIL.
- [ ] **Step 3: Implement** `fields.php`, `icons.php`, `fields-panel.js`, both blocks (editor preview via `@wordpress/server-side-render`; inspector holds `FieldsPanel` for hero-dashboard and a `SelectControl` for icon). Fill `ICONS` and extend its unit test to assert every name exists in `lucide-static`.
- [ ] **Step 4: Run** `npm run build:wp && npm run test:wp -- home && npm run test:unit` — PASS. `npm run lint` — clean.
- [ ] **Step 5: Commit:** `feat(core): add icon and hero dashboard blocks with schema-driven fields`.

---

### Task 6: Home page

**Files:**
- Create: `theme/byahe/patterns/{home-hero,proof-strip,how-it-works,feature-grid,payment-methods,testimonial,pricing-teaser,security-strip}.php`
- Modify: `theme/byahe/patterns/page-home.php`, `tests/wp/home.spec.mjs`

**Interfaces:**
- Consumes: `page_url()`, `photo()` (Task 4), `byahe/hero-dashboard`, `byahe/icon` (Task 5), bindings source `byahe/pricing` (Task 3).
- Produces section patterns, category `byahe`, `Viewport Width: 1280`. Section ids from `prototype/index.html`: `hero`, `proof`, `how-it-works`, `features`, `payment-methods`, `testimonial`, `pricing-teaser`, `security`. Feature cards link to `page_url( 'product' ) . '#<anchor>'` with the anchors `invoicing`, `payment-links`, `reminders`, `cash-flow`, `bir-receipts`, `team-access`.
- Pricing teaser: each tier's name (core/heading level 3, class `tier__name`) and price/period paragraphs bind to `byahe/pricing` (`field: name|price|period`, `billing: monthly`). The badge is static text.
- `page-home` = the eight section patterns then `byahe/cta-band`, as `core/pattern` references.

- [ ] **Step 1: Port `tests/e2e/home.spec.mjs` to `tests/wp/home.spec.mjs`** with `PAGES.home`; keep all content, anchor, layout and image assertions; replace `.html` hrefs with the WP paths; keep the Task 5 block test. Add: with the price fixture query arg set (Task 3), the teaser's Negosyo price reads `₱555`; the hero photo `img` has `fetchpriority="high"`, a `srcset` with three widths and the `sizes` from `photos.json`.
- [ ] **Step 2: Run** — FAIL.
- [ ] **Step 3: Build the patterns** from `prototype/index.html`, section by section, core blocks first; Tailwind classes only where core settings fall short.
- [ ] **Step 4: Re-seed and run:** restart the server (seed runs on boot) and `npm run test:wp -- home` — PASS on both projects. `npm run lint` — clean.
- [ ] **Step 5: Commit:** `feat(theme): build the Home page from patterns`.

---

### Task 7: `byahe/ui-mockup`

**Files:**
- Create: `plugins/byahe-core/src/blocks/ui-mockup/{block.json,variants.json,index.js,edit.js,render.php}`, `plugins/byahe-core/src/blocks/ui-mockup/templates/<variant>.php` ×11, `tests/wp/ui-mockup.spec.mjs`

**Interfaces:**
- Consumes: schema format, `resolve_fields()`, `FieldsPanel` (Task 5).
- Produces block `byahe/ui-mockup`: attributes `variant` (enum: `invoice`, `payment-qr`, `reminders`, `cashflow-chart`, `bir-receipt`, `team-roles`, `pos-sales`, `menu-orders`, `retainer-invoice`, `order-links`; default `invoice`), `values` (object). `variants.json` = `{ "<variant>": { "label": string, ...schema } }`, defaults copied from `prototype/partials/mockups/<variant>.html`. `render.php` resolves fields then includes `templates/<variant>.php` with `$f` (fields), `$l` (lists), `$aria`; unknown variant renders nothing. Root element: `<div class="ui-mockup ui-mockup--<variant>" role="img" aria-label="<aria>">`, inner markup identical to the partial. Editor: `SelectControl` for variant + `FieldsPanel`, ServerSideRender preview.

- [ ] **Step 1: Write `tests/wp/ui-mockup.spec.mjs`:** create one draft page with all ten variants at defaults plus one `invoice` with `{"values":{"total":"₱99"}}` and one `{"variant":"nope"}`; for each variant: `.ui-mockup--<variant>` exists, has `role="img"` and a non-empty `aria-label`; the default `invoice` label equals the prototype's label text exactly; the overridden one shows `₱99` in `.ui-total` and in its label; `nope` renders no `.ui-mockup`; `expectNoA11yViolations`; no horizontal scroll at 360px.
- [ ] **Step 2: Run** — FAIL.
- [ ] **Step 3: Implement** the block and templates.
- [ ] **Step 4: Run** — PASS. `npm run lint` — clean.
- [ ] **Step 5: Commit:** `feat(core): add the ui-mockup block with ten variants`.

---

### Task 8: Product page

**Files:**
- Create: `theme/byahe/inc/sections.php`, `theme/byahe/patterns/{product-hero,product-invoicing,product-payment-links,product-reminders,product-cash-flow,product-bir-receipts,product-team-access,product-integrations}.php`, `tests/wp/product.spec.mjs`
- Modify: `theme/byahe/patterns/page-product.php`, `theme/byahe/functions.php`

**Interfaces:**
- Produces `Byahe\Theme\feature_section( array $args ): string` returning block markup for the alternating feature section: `id`, `bg` (`yellow|cream`), `title`, `lead`, `bullets` (list<string>), `media` (block markup string, e.g. a `byahe/ui-mockup` comment), `reverse` (bool), `last` (bool, drops the bottom border). Solutions (Task 11) reuses it with an extra `quote` arg (`text`, `name`, `business`, `initials`).

- [ ] **Step 1: Port `tests/e2e/product.spec.mjs`** to `tests/wp/product.spec.mjs` (`PAGES.product`). Keep the anchor ids test and the "every Home feature link lands on an existing id" test against the WP pages.
- [ ] **Step 2: Run** — FAIL.
- [ ] **Step 3: Implement** `feature_section()`, the patterns (each feature pattern is a single `echo feature_section( [...] )`), and `page-product`.
- [ ] **Step 4: Run** — PASS. Lint clean.
- [ ] **Step 5: Commit:** `feat(theme): build the Product page`.

---

### Task 9: Pricing plans — toggle, cards, demo dialog

**Files:**
- Create: `plugins/byahe-core/src/blocks/pricing-toggle/{block.json,index.js,edit.js,render.php,view.js}`, `plugins/byahe-core/src/blocks/pricing-card/{block.json,index.js,edit.js,render.php}`, `plugins/byahe-core/src/blocks/demo-dialog/{block.json,index.js,edit.js,render.php,view.js}`, `theme/byahe/patterns/pricing-plans.php`, `tests/wp/pricing.spec.mjs`

**Interfaces:**
- Consumes: `pricing()`, `tier_field()`, `format_peso()` (Task 3).
- Interactivity store namespace `byahe/pricing`: state `isYearly` (bool, server default `false`), derived `state.price` and `state.period` (registered in PHP with `wp_interactivity_state( 'byahe/pricing', [ 'isYearly' => false, 'price' => fn, 'period' => fn ] )` reading `wp_interactivity_get_context()` keys `monthly`, `yearly`, `periodMonthly`, `periodYearly`; same getters in `view.js`), action `actions.toggle`, `state.statusMessage` (copy from `prototype/src/pricing.js` `MESSAGES`).
- `byahe/pricing-toggle`: no attributes. Renders the prototype `.billing-toggle` markup: `button.switch[role=switch]` with `data-wp-on--click="actions.toggle"` and `data-wp-bind--aria-checked="state.isYearly"`, the "2 months libre" pill, and the `#billing-status` live region bound to `state.statusMessage`. `viewScriptModule`.
- `byahe/pricing-card`: attribute `tier` (enum of tier slugs), `featured` (bool). Wrapper `li#<tier>.card-hard.tier` (+ `card-hard-lg` and yellow when `featured`), context `{ monthly, yearly, periodMonthly, periodYearly }` from `tier_field()`. Renders `h3.tier__name`, badge when set, `.price.num` with `data-wp-text="state.price"`, `.price-period` with `data-wp-text="state.period"` (enterprise: static `price-text`, no directives). InnerBlocks for the description, feature list and button (template from the prototype card; `templateLock: false`). Core Group can't render a `ul`, so the card's wrapper is `<div role="listitem" id="<tier>">` and the pattern's parent group has class `tier-list`; a theme `render_block_core/group` filter adds `role="list"` to groups with that class. Keep the `#<tier>` ids.
- `byahe/demo-dialog`: no attributes. Renders the prototype `#demo-dialog` markup. `view.js` wires every `.opens-demo-dialog a` (a core/button class) to `showModal()`, returns focus to the opener on close, closes on Esc (native), on `[data-close-dialog]`, and on a click outside `dialog.getBoundingClientRect()` only. Without JS the button href `page_url( 'about' ) . '#demo-note'` still works.

- [ ] **Step 1: Port the plans, toggle and dialog tests** from `tests/e2e/pricing.spec.mjs` to `tests/wp/pricing.spec.mjs` (`PAGES.pricing`): four tiers with ids; Negosyo "Most popular"; plan names are h3; defaults to monthly (`₱0`, `₱499`, `₱1,499`, `per month`); keyboard Space toggles to yearly (`₱4,990`, `₱14,990`, `per year`, status mentions yearly) and back; JS disabled → monthly prices visible and the Libre button href ends `/about/#demo-note`; dialog opens from Libre, traps focus, closes on Esc, on the close button and on a true backdrop click but not on a padding click, and returns focus; with the price fixture arg, Negosyo reads `₱555` monthly. The Pricing page needs `pricing-plans` in `page-pricing` for this task (fees/compare/FAQ come in Task 10).
- [ ] **Step 2: Run** — FAIL.
- [ ] **Step 3: Implement** the three blocks and `pricing-plans` (heading, toggle, four cards, VAT note, dialog block at the end); add `pricing-hero` + `pricing-plans` to `page-pricing`.
- [ ] **Step 4: Run** — PASS. `npm run lint` — clean. Confirm `view.js` modules load only on `/pricing/` (assert in the spec: Home has no `pricing-toggle` script module).
- [ ] **Step 5: Commit:** `feat(core): add the pricing toggle, pricing card and demo dialog blocks`.

---

### Task 10: Pricing page — fees, comparison, FAQ

**Files:**
- Create: `plugins/byahe-core/src/blocks/fee-table/{block.json,index.js,edit.js,render.php}`, `theme/byahe/patterns/{pricing-hero,pricing-fees,pricing-compare,pricing-faq}.php`
- Modify: `theme/byahe/patterns/page-pricing.php`, `tests/wp/pricing.spec.mjs`

**Interfaces:**
- `byahe/fee-table`: attribute `caption` (default "Transaction fees"). Renders the prototype `.table-scroll` region + `table.data-table` with `th[scope=row]` method and fee from `pricing()['fees']`.
- Comparison table: core/table with the prototype classes; included/not-included cells use the text "Yes"/"—" wrapped by the theme's `mark` styling via a `render_block_core/table` filter that maps `Yes` → `<span class="mark mark--yes"><span class="sr-only">Included</span></span>` and `—` → `<span class="mark">–<span class="sr-only">Not included</span></span>`, so marketers edit plain text.
- FAQ: core/details blocks, copy from the prototype.

- [ ] **Step 1: Port the remaining pricing tests** (fees table rows and caption, comparison table headers/scope and horizontal scroll inside its wrapper at 360px, FAQ opens with keyboard) plus `runPageBasics(test, PAGES.pricing)`.
- [ ] **Step 2: Run** — FAIL.
- [ ] **Step 3: Implement.** Full `page-pricing`: hero, plans, fees, compare, FAQ, CTA band.
- [ ] **Step 4: Run** — PASS. Lint clean.
- [ ] **Step 5: Commit:** `feat(theme): finish the Pricing page`.

---

### Task 11: Solutions page

**Files:**
- Create: `theme/byahe/patterns/{solutions-hero,solutions-retail,solutions-food,solutions-freelancers,solutions-online-sellers}.php`, `tests/wp/solutions.spec.mjs`
- Modify: `theme/byahe/patterns/page-solutions.php`, `theme/byahe/inc/sections.php` (`quote` arg)

**Interfaces:**
- Consumes: `feature_section()` (Task 8), `byahe/ui-mockup` variants `pos-sales`, `menu-orders`, `retainer-invoice`, `order-links`, `photo()`.
- Section ids: `retail`, `food`, `freelancers`, `online-sellers`; hero sub-nav links to them.

- [ ] **Step 1: Port** `tests/e2e/solutions.spec.mjs` → `tests/wp/solutions.spec.mjs`.
- [ ] **Step 2: Run** — FAIL.
- [ ] **Step 3: Implement.**
- [ ] **Step 4: Run** — PASS. Lint clean.
- [ ] **Step 5: Commit:** `feat(theme): build the Solutions page`.

---

### Task 12: About page

**Files:**
- Create: `theme/byahe/patterns/{about-hero,about-story,about-mission,about-values,about-team,about-numbers,about-demo-note,about-credits}.php`, `tests/wp/about.spec.mjs`
- Modify: `theme/byahe/patterns/page-about.php`

**Interfaces:**
- Section ids: `story`, `mission`, `values`, `team`, `numbers`, `demo-note`, `credits` (`demo-note` and `credits` are link targets from Pricing, the dialog and the footer).
- `about-credits` loops over `assets/images/photos.json` (photographer or "Pexels contributor", linked page URL, "Pexels License") so credits can't drift from the photos.

- [ ] **Step 1: Port** `tests/e2e/about.spec.mjs` → `tests/wp/about.spec.mjs`; add: credits list has one item per `photos.json` entry.
- [ ] **Step 2: Run** — FAIL.
- [ ] **Step 3: Implement.**
- [ ] **Step 4: Run** — PASS. Lint clean.
- [ ] **Step 5: Commit:** `feat(theme): build the About page`.

---

### Task 13: Site-wide WordPress QA

**Files:**
- Create: `tests/wp/{editor,links,keyboard}.spec.mjs`
- Modify: `TRACKER.md`

- [ ] **Step 1: `editor.spec.mjs`** (desktop, logged in): for each page, open `wp-admin/post.php?post=<id>&action=edit`, wait for the canvas, then `wp.data.select('core/block-editor').getBlocks()` walked recursively has no block with `isValid === false`; in the editor canvas iframe an `h1` computes `font-family` starting with `"Cubao Free"` and the body background is cream `rgb(255, 248, 231)`; save without edits and assert the front-end HTML of `main` is unchanged (compare text content and the list of element ids). Also: every `byahe/*` block appears in the inserter search for "BYAHE"; the `byahe` pattern category lists the section patterns.
- [ ] **Step 2: `links.spec.mjs`:** the Task 2 id-parsing link checker against all five WP pages (same-origin links only, `?`-free paths; `#fragment` must exist in the target's HTML ids).
- [ ] **Step 3: `keyboard.spec.mjs`:** port `tests/e2e/keyboard.spec.mjs` to the five WP paths.
- [ ] **Step 4: Run** `npm run test:wp` — whole WP suite PASS on both projects; `npm test` — prototype suite still PASS; `npm run lint` — clean. Fix what fails; for a fix in shared CSS rerun both suites.
- [ ] **Step 5: Lighthouse** (mobile preset) on `npm run serve:wp` Home: record accessibility, best practices, SEO in `TRACKER.md` Notes, performance and server response separately (spec §10). Targets: 100 / ≥95 / 100.
- [ ] **Step 6: REVIEW CHECKPOINT — John reviews** the WordPress site and editor locally (`npm run dev:wp`). Collect changes; fix before Task 14.
- [ ] **Step 7: Update `TRACKER.md`:** tick WP template, Content and QA per page, shared parts, custom blocks, bindings; log the decisions table deviations (seed command, theme photos). Commit: `test(wp): cover editor validity, links and keyboard across the site`.

---

### Task 14: Blueprints generator

**Files:**
- Create: `blueprints/steps.json`, `scripts/lib/blueprints.mjs`, `scripts/build-blueprints.mjs`, `tests/unit/blueprints.test.mjs`
- Modify: `package.json` (`build:blueprints`, part of `build:wp`), `blueprint.local.json`, create `blueprint.json`

**Interfaces:**
- Produces `buildBlueprints( steps: object[], { repo: string, tag: string } ): { local: object, public: object }`. Both share `steps` (activate theme, activate plugin, site options from Plan A, `wp byahe seed`). `local` has no `login` and no install steps (folders are mounted). `public` has `login: true`, `landingPage: "/"`, `preferredVersions { php: "8.2", wp: "latest" }`, and prepends `installTheme`/`installPlugin` with `{ resource: "url", url: "https://playground.wordpress.net/cors-proxy.php?https://github.com/<repo>/releases/download/<tag>/byahe-theme.zip" }` (and `byahe-core.zip`), each with `options.activate: true` (then the separate activate steps are dropped from `public`).

- [ ] **Step 1: Write unit tests:** `public.steps[0].themeData.url` equals the proxied theme URL for `repo = 'JanoAngulo/byahe-wordpress'`, `tag = 'demo-latest'`; the `wp byahe seed` step appears exactly once in each; `local` has no `install*` steps; both validate as JSON with `$schema` set.
- [ ] **Step 2: Run** — FAIL.
- [ ] **Step 3: Implement**; `build-blueprints.mjs` writes both files with 2-space JSON and a trailing newline.
- [ ] **Step 4: Run** `npm run test:unit` and `npm run test:wp -- seed` (local blueprint still seeds) — PASS.
- [ ] **Step 5: Commit:** `build(wp): generate local and public Playground blueprints from one step list`.

---

### Task 15: Release workflow and the public demo link

**Files:**
- Create: `.github/workflows/release.yml`
- Modify: `TRACKER.md`

**Interfaces:**
- Workflow on push to `main` (and `workflow_dispatch`): Node 26 `npm ci`; `composer install`; `npm run lint`; `npm run build:wp`; zip `theme/byahe` → `byahe-theme.zip` (root folder `byahe/`, excluding `src/`, `theme.base.json`) and `plugins/byahe-core` → `byahe-core.zip` (root `byahe-core/`, excluding `src/`, `node_modules/`); `gh release view demo-latest || gh release create demo-latest --title "Demo build" --notes "Rolling build used by the Playground demo." --prerelease`; `gh release upload demo-latest byahe-theme.zip byahe-core.zip --clobber`. `permissions: contents: write`.

- [ ] **Step 1: Write the workflow.** Validate locally: run its build and zip commands in Git Bash, unzip both into a scratch dir and check `byahe/style.css`, `byahe/assets/css/theme.css`, `byahe/assets/fonts/*.woff2`, `byahe-core/build/blocks-manifest.php` exist and no `src/` folder is included.
- [ ] **Step 2: Local install test from zips:** start Playground CLI on port 9410 with the zip folder mounted at `/zips` and a scratch blueprint that installs `{ "resource": "vfs", "path": "/zips/byahe-theme.zip" }` and the plugin zip the same way, then runs the shared steps; `npm run test:wp -- smoke seed` against it — PASS.
- [ ] **Step 3: Commit:** `ci: build and publish theme and plugin zips to a rolling release`.
- [ ] **Step 4: STOP — ask John before pushing.** Pushing publishes the release and makes the demo link live. After he pushes and the workflow succeeds, open `https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/JanoAngulo/byahe-wordpress/main/blueprint.json` and check: Home loads as front page, all nav links work under the scope path, the admin bar shows (logged in), Pricing toggle works, the editor opens a page with no invalid blocks. If the zip fetch fails through the CORS proxy, switch to the `demo-build` branch + `git:directory` fallback from the decisions table and repeat.
- [ ] **Step 5: Update `TRACKER.md`:** tick Playground blueprint, GitHub Action and blueprint-from-zips items; add the demo link. Commit: `docs: add the live Playground demo link`.
