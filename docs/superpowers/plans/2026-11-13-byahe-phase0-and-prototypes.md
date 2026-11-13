# BYAHE Phase 0 + Phase 1 HTML Prototypes Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Set up the repo, design-token pipeline, HTML+Tailwind prototype tooling and a running WordPress Playground, then build the shared components and the five Phase 1 pages (Home, Product, Pricing, Solutions, About) as static HTML prototypes, each approved by John before the next plan converts it to WordPress.

**Architecture:** One `tokens/tokens.json` feeds a Node build script that writes Tailwind v4 `@theme` CSS for the prototype and the `settings` block of the WordPress `theme.json`. The prototype is a Vite multi-page site with a tiny include plugin for shared partials. Playwright + axe tests guard accessibility, layout and links on every page. A minimal theme and plugin run in WordPress Playground CLI to prove the dev loop. Converting to WordPress is a separate follow-up plan (Plan B).

**Tech Stack:** Node 26, one root `package.json` (no workspaces), Vite 7, Tailwind CSS v4 (`@tailwindcss/vite`), `@fontsource-variable/inter` + `@fontsource-variable/space-grotesk`, Lucide icons (inline SVG), Playwright + `@axe-core/playwright`, `node:test`, `sharp` (image conversion), `@wp-playground/cli`.

**Spec:** [`docs/superpowers/specs/2026-11-13-byahe-wordpress-design.md`](../specs/2026-11-13-byahe-wordpress-design.md). Read §6 (brand), §8 (page contents), §9.6 (CTA), §10 (quality bar) before any page task.

## Global Constraints

- Brand: **Full Jeepney**. Yellow and blue everywhere, red/yellow/blue stripe dividers, hard offset shadows on cards, pill buttons (spec §6.1).
- Colors: yellow `#FFC93C`, blue `#1D3FBB`, navy `#13205C`, red `#D62828`, cream `#FFF8E7`, white `#FFFFFF` (spec §6.2).
- Fonts: Space Grotesk 500/700 for headings, Inter 400/500/600 for body. Self-hosted, never loaded from Google at runtime.
- Shadows: `4px 4px 0 #13205C` (sm), `8px 8px 0 #13205C` (lg). Border `2px solid #13205C`. Radius `10px` for cards, `999px` for buttons and pills.
- Contrast: WCAG 2.2 AA on every pair. Red on yellow only for large text (≥24px, or ≥18.66px bold) and non-text. Never navy on blue (spec §6.4).
- Voice: English with short Taglish punchlines in headlines and CTAs. Body, legal and security text in plain English.
- Every page footer says BYAHE is a demo company. No third-party logos (GCash, Maya, banks, card networks). Payment methods are named in text only.
- Photos: scenes only, from Pexels (Unsplash fallback), logged in `CREDITS.md`. No identifiable faces beside fictional testimonials, team or customers. Testimonials and team use initials avatars.
- Product visuals are live HTML/CSS, never screenshots.
- No jQuery. No page builders. JS loads only on pages that need it.
- Primary CTA "Sakay na →" links to `pricing.html#libre`. The Libre card button opens a native `<dialog>` saying BYAHE is a demo company, with a repo link (spec §9.6).
- Nav: Product · Solutions · Pricing · About + CTA. No links to Phase 2 pages.
- Responsive 360px–1440px+. No horizontal page scroll except inside data tables. `prefers-reduced-motion` respected.
- Commit messages: Conventional Commits, no AI attribution trailers or mentions.

## Review Focus

1. **Keyboard-only visitor**: the mobile menu opens with Enter/Space, closes with Esc and returns focus to its toggle. The pricing toggle and dialog are fully operable, and the dialog returns focus on close. Pinned in Task 4 (menu, dialog) and Task 8 (toggle).
2. **360px phone**: nothing overflows horizontally. The comparison table scrolls inside its own wrapper. Pinned per page via the shared `expectNoHorizontalScroll` helper (Task 2) at the `mobile` project in every page task.
3. **JavaScript disabled**: all content is visible, monthly prices show, and nav links are reachable (menu degrades to a visible list). Pinned in Task 4 (nav) and Task 8 (prices).
4. **Reduced motion**: with `prefers-reduced-motion: reduce`, no element has a running CSS animation or transition longer than 0.01s. Pinned in Task 4 and re-run on every page by the shared page suite.
5. **Broken internal links/anchors**: every Home feature card links to an existing `id` on `product.html`, and every internal `href` across pages resolves. Pinned in Task 11 (site-wide link checker) and Task 6 (Home → Product anchors).

---

## File Structure

```
/
├─ .gitignore  .editorconfig  package.json  playwright.config.mjs
├─ CREDITS.md                              # photo credits (Task 5)
├─ blueprint.local.json                    # Playground dev blueprint (Task 3)
├─ tokens/tokens.json                      # single token source (Task 1)
├─ scripts/
│  ├─ build-tokens.mjs                     # CLI: writes tokens.css + theme.json
│  ├─ lib/tokens.mjs                       # toTailwindTheme(), toThemeJsonSettings()
│  ├─ lib/contrast.mjs                     # contrastRatio(), checkRules()
│  └─ fetch-photos.mjs                     # downloads + converts listed photos (Task 5)
├─ tests/
│  ├─ unit/tokens.test.mjs  unit/contrast.test.mjs  unit/include-plugin.test.mjs
│  └─ e2e/helpers.mjs  e2e/components.spec.mjs  e2e/<page>.spec.mjs  e2e/links.spec.mjs
├─ prototype/
│  ├─ vite.config.mjs  vite-plugin-include.mjs
│  ├─ components.html  index.html  product.html  pricing.html  solutions.html  about.html
│  ├─ partials/ header.html  footer.html  cta-band.html  demo-dialog.html  mockups/<variant>.html
│  ├─ public/images/                       # WebP photos (Task 5)
│  └─ src/ tokens.css (generated)  styles.css  components.css  nav.js  pricing.js  dialog.js
├─ theme/byahe/ style.css  theme.base.json  theme.json (generated)  functions.php  templates/index.html
└─ plugins/byahe-core/ byahe-core.php
```

`prototype/partials/mockups/<variant>.html` names match the future `byahe/ui-mockup` variants: `invoice`, `payment-qr`, `reminders`, `cashflow-chart`, `bir-receipt`, `team-roles`, plus Solutions variants `pos-sales`, `menu-orders`, `retainer-invoice`, `order-links`. Plan B converts each into a variant template.

---

### Task 1: Repo, tokens and contrast pipeline

**Files:**
- Create: `.gitignore`, `.editorconfig`, `package.json`, `tokens/tokens.json`, `scripts/lib/tokens.mjs`, `scripts/lib/contrast.mjs`, `scripts/build-tokens.mjs`, `theme/byahe/theme.base.json`
- Test: `tests/unit/tokens.test.mjs`, `tests/unit/contrast.test.mjs`

**Interfaces:**
- Produces:
  - `tokens.json` shape: `{ color: {name: hex}, font: {heading: {family, fallback}, body: {family, fallback}}, fontSize: {name: rem}, spacing: {name: rem}, shadow: {"hard-sm", "hard-lg"}, radius: {card, pill}, contrastRules: [{fg, bg, min, note?}] }`. Color names: `yellow blue navy red cream white`.
  - `contrastRatio(fgHex: string, bgHex: string): number` (WCAG 2.x relative luminance).
  - `checkRules(tokens): {pass: boolean, results: [{fg, bg, ratio, min, pass}]}`.
  - `toTailwindTheme(tokens): string` → CSS text `@theme { --color-yellow: #FFC93C; ... --font-heading: ...; --shadow-hard-sm: ...; --radius-card: 10px; --radius-pill: 999px; }`.
  - `toThemeJsonSettings(tokens): object` → `{ color: { palette: [{slug, name, color}], defaultPalette: false }, typography: { fontFamilies: [...], fontSizes: [...] }, spacing: { spacingSizes: [...] }, shadow: { presets: [{slug, name, shadow}] } }`.
  - npm scripts: `build:tokens`, `test:unit`.
  - Generated files: `prototype/src/tokens.css`, `theme/byahe/theme.json` (= `theme.base.json` with `settings` deep-merged from tokens).

- [ ] **Step 1: `git init`, add `.gitignore` (`node_modules/ dist/ prototype/dist/ test-results/ playwright-report/ .superpowers/ theme/byahe/assets/css/ plugins/*/build/`), `.editorconfig` (tabs for PHP per WPCS, 2 spaces elsewhere), and `package.json` with `"type": "module"`, `"private": true`.**

- [ ] **Step 2: Write `tokens/tokens.json`** with the Global Constraints values. `contrastRules`: navy/yellow min 4.5, white/blue 4.5, yellow/blue 4.5, navy/white 4.5, navy/cream 4.5, red/white 4.5, red/yellow min 3 `note: "large text only"`.

- [ ] **Step 3: Write failing tests**

`tests/unit/contrast.test.mjs`:
```js
test('navy on yellow is ~9.8:1', () => assert.ok(Math.abs(contrastRatio('#13205C','#FFC93C') - 9.85) < 0.1));
test('white on blue passes AA', () => assert.ok(contrastRatio('#FFFFFF','#1D3FBB') >= 8.4));
test('red on yellow is large-text only', () => { const r = contrastRatio('#D62828','#FFC93C'); assert.ok(r >= 3 && r < 4.5); });
test('navy on blue fails', () => assert.ok(contrastRatio('#13205C','#1D3FBB') < 2));
test('order of arguments does not matter', () => assert.equal(contrastRatio('#FFC93C','#13205C'), contrastRatio('#13205C','#FFC93C')));
test('all token contrast rules pass', () => assert.equal(checkRules(tokens).pass, true));
```
`tests/unit/tokens.test.mjs`:
```js
test('tailwind theme has every color', () => { for (const n of ['yellow','blue','navy','red','cream','white']) assert.match(toTailwindTheme(tokens), new RegExp(`--color-${n}:`)); });
test('tailwind theme has hard shadows and radii', () => { const css = toTailwindTheme(tokens); assert.match(css, /--shadow-hard-sm: 4px 4px 0 #13205C/); assert.match(css, /--radius-pill: 999px/); });
test('theme.json palette disables core defaults', () => assert.equal(toThemeJsonSettings(tokens).color.defaultPalette, false));
test('theme.json palette slugs match token names', () => assert.deepEqual(toThemeJsonSettings(tokens).color.palette.map(p => p.slug), Object.keys(tokens.color)));
test('theme.json shadow presets', () => assert.deepEqual(toThemeJsonSettings(tokens).shadow.presets.map(p => p.slug), ['hard-sm','hard-lg']));
```

- [ ] **Step 4: Run `node --test tests/unit/` → FAIL (modules not found).**

- [ ] **Step 5: Implement `scripts/lib/contrast.mjs`, `scripts/lib/tokens.mjs`, and the `scripts/build-tokens.mjs` CLI.** The CLI exits non-zero and prints failing pairs if `checkRules` fails, otherwise writes both generated files. `theme.base.json`: `$schema` `https://schemas.wp.org/trunk/theme.json`, `version: 3`, `settings.appearanceTools: true`, `settings.layout: {contentSize: "1200px", wideSize: "1440px"}`.

- [ ] **Step 6: Add scripts** `"test:unit": "node --test tests/unit/"`, `"build:tokens": "node scripts/build-tokens.mjs"`. Run `npm run test:unit` → all PASS. Run `npm run build:tokens` → both files written, and `theme/byahe/theme.json` parses as JSON.

- [ ] **Step 7: Commit** `chore: scaffold repo and design token pipeline`.

---

### Task 2: Prototype tooling (Vite, Tailwind, fonts, partials, test harness)

**Files:**
- Create: `prototype/vite.config.mjs`, `prototype/vite-plugin-include.mjs`, `prototype/src/styles.css`, `prototype/src/components.css`, `prototype/components.html` (placeholder), `playwright.config.mjs`, `tests/e2e/helpers.mjs`
- Test: `tests/unit/include-plugin.test.mjs`, `tests/e2e/smoke.spec.mjs`

**Interfaces:**
- Consumes: `prototype/src/tokens.css` (Task 1).
- Produces:
  - `includePartials({ root: string }): import('vite').Plugin`. Replaces `<!-- @include path/to/file.html -->` in HTML with that file's contents (relative to `root`, recursive, max depth 5, throws `Error('Partial not found: <path>')` if missing).
  - Vite multi-page inputs: every `prototype/*.html`.
  - CSS utility classes in `components.css` (`@layer components`): `.btn`, `.btn-primary` (blue bg, white text), `.btn-secondary` (white bg, navy border), `.card-hard` (white, 2px navy border, `shadow-hard-sm`, radius card), `.card-hard-lg`, `.stripe` (8px repeating red/yellow/blue), `.pill`, `.pill-paid` (navy text on yellow), `.pill-pending` (navy text on white, navy border), `.pill-overdue` (white text on red), `.photo-duotone` (grayscale photo blended over yellow/blue), `.skip-link`, `.focus-ring`. Later tasks use these names.
  - `tests/e2e/helpers.mjs` exports `expectNoA11yViolations(page)` (axe, tags `wcag2a wcag2aa wcag21aa wcag22aa`, fails on `serious`/`critical`), `expectNoHorizontalScroll(page)` (`document.documentElement.scrollWidth <= window.innerWidth`), `expectSingleH1(page)`, `expectLandmarks(page)` (header, nav, main, footer each present), `expectNoMotionWhenReduced(page)` (emulate `reducedMotion: 'reduce'`, assert every element's computed `animation-duration` and `transition-duration` ≤ 0.01s), and `runPageBasics(test, path)` which registers all five as tests for a page.
  - npm scripts: `dev:proto`, `build:proto`, `preview:proto` (port 4173), `test:e2e`.
  - Playwright projects: `desktop` (1280×800), `mobile` (360×800).

- [ ] **Step 1: Install** `vite @tailwindcss/vite tailwindcss @fontsource-variable/inter @fontsource-variable/space-grotesk lucide-static @playwright/test @axe-core/playwright` as devDependencies, then `npx playwright install chromium`.

- [ ] **Step 2: Write failing test** `tests/unit/include-plugin.test.mjs`:
```js
test('replaces include comment with partial contents', ...);   // fixture dir with a.html containing <!-- @include b.html -->, b.html "<p>B</p>" → output contains <p>B</p>, not the comment
test('resolves nested includes', ...);
test('throws on missing partial', () => assert.throws(() => run('<!-- @include nope.html -->'), /Partial not found: nope.html/));
test('stops at depth 5', ...);                                 // self-including partial throws /depth/
```
Run `npm run test:unit` → FAIL.

- [ ] **Step 3: Implement `includePartials`** using the `transformIndexHtml` hook and synchronous reads. Also add the partial files to Vite's watch list so edits hot-reload. Run → PASS.

- [ ] **Step 4: Configure Vite** (`root: 'prototype'`, plugins `[tailwindcss(), includePartials({root: 'prototype/partials'})]`, `build.rollupOptions.input` = all top-level HTML). `styles.css`: `@import "tailwindcss"; @import "./tokens.css"; @import "@fontsource-variable/inter"; @import "@fontsource-variable/space-grotesk"; @import "./components.css";`, base layer sets body font Inter and navy text, headings Space Grotesk.

- [ ] **Step 5: Write `helpers.mjs`, `playwright.config.mjs`** (webServer `npm run build:proto && npm run preview:proto`, baseURL `http://localhost:4173`) **and `tests/e2e/smoke.spec.mjs`** asserting `components.html` loads with status 200 and computed body `font-family` contains "Inter". Run `npm run test:e2e` → PASS on both projects.

- [ ] **Step 6: Commit** `chore: add vite, tailwind and playwright prototype tooling`.

---

### Task 3: WordPress Playground dev environment

**Files:**
- Create: `theme/byahe/style.css`, `theme/byahe/functions.php`, `theme/byahe/templates/index.html`, `plugins/byahe-core/byahe-core.php`, `blueprint.local.json`
- Modify: `package.json` (scripts)

**Interfaces:**
- Consumes: `theme/byahe/theme.json` (Task 1).
- Produces: `npm run dev:wp` → WordPress at `http://127.0.0.1:9400` with theme `byahe` active and plugin `byahe-core` active, logged in as admin. Text domains `byahe`, `byahe-core`. Plugin defines constant `BYAHE_CORE_VERSION`.

- [ ] **Step 1: Install `@wp-playground/cli`** (pin the exact current version).

- [ ] **Step 2: Write theme header** in `style.css` (Theme Name: BYAHE, Requires at least: latest stable major, Requires PHP: 8.1, Text Domain: byahe, License GPL-2.0-or-later). Add a minimal `templates/index.html` (`wp:template-part` header-less placeholder; a `wp:post-content` block is enough) and a `functions.php` that only declares the namespace `Byahe\Theme` and a version constant.

- [ ] **Step 3: Write plugin header** in `byahe-core.php` (Plugin Name: BYAHE Core, Requires PHP: 8.1, Text Domain: byahe-core), `defined('ABSPATH') || exit;`, `BYAHE_CORE_VERSION`.

- [ ] **Step 4: Write `blueprint.local.json`**: `landingPage: "/"`, `login: true`, `preferredVersions: {php: "8.2", wp: "latest"}`, steps `activateTheme byahe`, `activatePlugin byahe-core/byahe-core.php`, `setSiteOptions {blogname: "BYAHE"}`.

- [ ] **Step 5: Add script** `"dev:wp": "wp-playground-cli server --port=9400 --mount=./theme/byahe:/wordpress/wp-content/themes/byahe --mount=./plugins/byahe-core:/wordpress/wp-content/plugins/byahe-core --blueprint=./blueprint.local.json"` (adjust flag names to the pinned CLI version's `--help`).

- [ ] **Step 6: Verify.** Run `npm run dev:wp` in the background. `curl -s http://127.0.0.1:9400/ | grep -i "byahe"` → match. `curl -s "http://127.0.0.1:9400/wp-json/" | grep '"name":"BYAHE"'` → match. Stop the server.

- [ ] **Step 7: Commit** `chore: add minimal theme, plugin and playground dev env`. Mark Phase 0 items done in `TRACKER.md` in the same commit.

---

### Task 4: Shared components (prototype) — REVIEW CHECKPOINT

**Files:**
- Create: `prototype/partials/header.html`, `prototype/partials/footer.html`, `prototype/partials/cta-band.html`, `prototype/partials/demo-dialog.html`, `prototype/src/nav.js`, `prototype/src/dialog.js`
- Modify: `prototype/components.html`, `prototype/src/components.css`
- Test: `tests/e2e/components.spec.mjs`

**Interfaces:**
- Consumes: component classes and helpers (Task 2).
- Produces:
  - `header.html`: skip link to `#main`, wordmark "BYAHE" linking `index.html`, `<nav aria-label="Primary">` with Product · Solutions · Pricing · About, CTA "Sakay na →" `href="pricing.html#libre"`. Mobile: `<button aria-expanded aria-controls="primary-menu">`. Current page marked `aria-current="page"` via `data-page` on `<body>` set by `nav.js`.
  - `nav.js`: `initNav(root = document): void`. Without JS the menu list is visible (toggle is `hidden` until JS adds `.js` to `<html>`).
  - `demo-dialog.html`: `<dialog id="demo-dialog" aria-labelledby="demo-dialog-title">` with heading "BYAHE is a demo company", one-paragraph explanation, link "View the code on GitHub" (`href="#repo"` placeholder until the repo URL exists), close button.
  - `dialog.js`: `initDemoDialog(): void`. Any element with `data-open-demo-dialog` opens it with `showModal()`. Close returns focus to the opener.
  - `footer.html`: stripe, link columns (Phase 1 pages only), "BYAHE is a fictional company built as a portfolio project. Not a real financial service." and photo-credits link.
  - `cta-band.html`: blue band, yellow headline, primary CTA.
  - `components.html` shows: buttons (primary, secondary, focus state), cards (sm/lg hard shadow), stripe, pills (paid, pending, overdue), photo-duotone sample, header, footer, CTA band, dialog trigger.

- [ ] **Step 1: Write failing tests** in `components.spec.mjs`:
```js
runPageBasics(test, '/components.html');
test('mobile menu toggles with keyboard and Esc returns focus', ...);   // mobile project: focus toggle, press Enter → aria-expanded="true", menu visible; press Escape → aria-expanded="false", toggle focused
test('nav visible without JS', ...);                                    // javaScriptEnabled:false context, mobile: all 4 nav links visible
test('CTA links to pricing libre', ...);                                // header CTA href === 'pricing.html#libre'
test('demo dialog opens, traps and returns focus', ...);                // click [data-open-demo-dialog] → dialog open, focus inside; Escape → closed, focus back on trigger
test('footer contains demo disclaimer', ...);                           // text /fictional company/i
test('focus ring visible on buttons', ...);                             // focus .btn-primary → computed outline-width >= 2px or box-shadow != none
```
Run → FAIL.

- [ ] **Step 2: Build partials, CSS and JS** to the Interfaces above and spec §6. Lucide icons inline. Hard shadows lift on hover (translate −2px with larger shadow), disabled under reduced motion. Pill colors must satisfy the contrast rules: check any new pair with `contrastRatio` and add it to `tokens.contrastRules`.

- [ ] **Step 3: Run `npm run test:unit && npm run test:e2e` → all PASS.**

- [ ] **Step 4: Commit** `feat(prototype): add shared components`.

- [ ] **Step 5: CHECKPOINT. Stop and ask John to review** `components.html` (`npm run dev:proto`, open `/components.html`) on desktop and phone width. Apply his changes with tests still green, commit, then tick "Shared components → John approved" in `TRACKER.md`. Do not start page tasks before approval. (Task 5 may proceed in parallel.)

---

### Task 5: Photo sourcing

**Files:**
- Create: `scripts/fetch-photos.mjs`, `scripts/photos.json`, `CREDITS.md`, `prototype/public/images/*.webp`

**Interfaces:**
- Produces: `prototype/public/images/<slug>.webp` for slugs `hero-market`, `sari-sari`, `cafe-counter`, `qr-payment-hands`, `jeepney-street`, `online-seller-packing`, `freelancer-desk`, `office-bgc` (scene, no identifiable faces). `scripts/photos.json` entries: `{slug, source: "pexels"|"unsplash", pageUrl, downloadUrl, photographer, photographerUrl, alt}`. `alt` strings are what pages use.

- [ ] **Step 1: Find photos.** Search Pexels (then Unsplash) for each slug's scene. Reject any with an identifiable face as the main subject, visible third-party logos or brand signage that dominates the frame. Record the entries in `photos.json`.

- [ ] **Step 2: Install `sharp`. Implement `fetch-photos.mjs`:** for each entry, download, resize to max 2000px wide, encode WebP quality 78, write `prototype/public/images/<slug>.webp`, and regenerate `CREDITS.md` as a table (image, photographer with link, source page link, license "Pexels License" / "Unsplash License"). Skip files that already exist unless `--force`.

- [ ] **Step 3: Run `node scripts/fetch-photos.mjs`.** Verify every slug exists and each file is under 400 KB (`ls -la prototype/public/images`).

- [ ] **Step 4: Commit** `feat(prototype): add credited scene photography`.

---

### Task 6: Home prototype — REVIEW CHECKPOINT

**Files:**
- Create: `prototype/index.html`, `prototype/partials/mockups/hero-dashboard.html`
- Test: `tests/e2e/home.spec.mjs`

**Interfaces:**
- Consumes: partials and classes (Task 4), photos (Task 5).
- Produces: section `id`s `hero, proof, how-it-works, features, payment-methods, testimonial, pricing-teaser, security, cta`. Feature card links `product.html#invoicing`, `#payment-links`, `#reminders`, `#cash-flow`, `#bir-receipts`, `#team-access` (Task 7 must create these ids). Pricing teaser lists Negosyo ₱499, Pro ₱1,499, Enterprise "Let's talk".

- [ ] **Step 1: Write failing tests:**
```js
runPageBasics(test, '/');
test('hero headline', ...);                       // h1 text "Bayad na, tuloy ang byahe."
test('hero dashboard is live HTML', ...);         // #hero contains no <img> inside .hero-dashboard; contains text "₱"
test('six feature links point at product anchors', ...); // hrefs equal the six listed above
test('no third-party logos', ...);                // no img/svg with alt or aria-label matching /gcash|maya|visa|mastercard|bpi|bdo/i
test('pricing teaser prices', ...);               // contains "₱499" and "₱1,499" and "Let's talk"
test('testimonial uses initials avatar, not photo', ...); // #testimonial has no <img>
```
Run → FAIL.

- [ ] **Step 2: Build the page** following spec §8.1 sections 1–10 in order, using partials and photos. Hero dashboard markup lives in `partials/mockups/hero-dashboard.html`.

- [ ] **Step 3: Run tests → PASS. Commit** `feat(prototype): add home page`.

- [ ] **Step 4: CHECKPOINT.** Ask John to review `/` on desktop and phone. Apply changes, keep tests green, commit, tick "Home → John approved" in `TRACKER.md`. Task 7 may be built while he reviews.

---

### Task 7: Product prototype — REVIEW CHECKPOINT

**Files:**
- Create: `prototype/product.html`, `prototype/partials/mockups/{invoice,payment-qr,reminders,cashflow-chart,bir-receipt,team-roles}.html`
- Test: `tests/e2e/product.spec.mjs`

**Interfaces:**
- Produces: section ids `invoicing, payment-links, reminders, cash-flow, bir-receipts, team-access, integrations`. Each mockup root element: `<div class="ui-mockup ui-mockup--<variant>" role="img" aria-label="<one-sentence summary>">`. Plan B relies on this class and attribute pattern.

- [ ] **Step 1: Write failing tests:**
```js
runPageBasics(test, '/product.html');
test('all six feature anchors exist', ...);           // each id present
test('each mockup has role img and aria-label', ...); // 6 × .ui-mockup with non-empty aria-label
test('mockups are not images', ...);                  // .ui-mockup contains no <img>
test('sections alternate layout on desktop', ...);    // desktop: mockup x-position alternates left/right for consecutive feature sections
```
Run → FAIL.

- [ ] **Step 2: Build page and six mockup partials** per spec §8.2. Run tests → PASS. Commit `feat(prototype): add product page and ui mockups`.

- [ ] **Step 3: CHECKPOINT.** John reviews `/product.html`. Apply, commit, tick in `TRACKER.md`.

---

### Task 8: Pricing prototype — REVIEW CHECKPOINT

**Files:**
- Create: `prototype/pricing.html`, `prototype/src/pricing.js`
- Test: `tests/e2e/pricing.spec.mjs`

**Interfaces:**
- Produces:
  - Pricing data in the HTML as `data-monthly`/`data-yearly` on each `.price` element. Yearly = monthly × 10 ("2 months libre"): Negosyo ₱4,990/yr, Pro ₱14,990/yr. Libre `id="libre"`. Plan B moves this data into the `byahe/pricing` binding source.
  - `pricing.js`: `initPricingToggle(root = document): void`. Toggle is a `<button role="switch" aria-checked>` labelled "Bill yearly". Updates every `.price` and its period label and announces the change via an `aria-live="polite"` region.
  - Fees table (QR Ph 1.0%, cards 2.9% + ₱15, bank transfer ₱10 flat), comparison `<table>` inside `<div class="table-scroll" tabindex="0" role="region" aria-label="Plan comparison">`, FAQ with `<details>/<summary>`. Libre CTA has `data-open-demo-dialog`.

- [ ] **Step 1: Write failing tests:**
```js
runPageBasics(test, '/pricing.html');
test('defaults to monthly', ...);                       // Negosyo shows "₱499", switch aria-checked="false"
test('toggle by keyboard switches to yearly', ...);     // focus switch, press Space → aria-checked="true", Negosyo "₱4,990", Pro "₱14,990", live region text mentions yearly
test('monthly prices visible without JS', ...);         // JS disabled: "₱499" and "₱1,499" visible
test('libre anchor exists and its CTA opens demo dialog', ...);
test('fees table values', ...);                         // "1.0%", "2.9% + ₱15", "₱10"
test('comparison table scrolls inside its wrapper on mobile', ...); // mobile: page has no horizontal scroll; .table-scroll scrollWidth > clientWidth
test('table has proper headers', ...);                  // th[scope=col] count >= 4, th[scope=row] count >= 5
test('FAQ uses details/summary', ...);                  // >= 5 details elements
```
Run → FAIL.

- [ ] **Step 2: Build page and `pricing.js`** per spec §8.3. Run → PASS. Commit `feat(prototype): add pricing page with billing toggle`.

- [ ] **Step 3: CHECKPOINT.** John reviews `/pricing.html`. Apply, commit, tick in `TRACKER.md`.

---

### Task 9: Solutions prototype — REVIEW CHECKPOINT

**Files:**
- Create: `prototype/solutions.html`, `prototype/partials/mockups/{pos-sales,menu-orders,retainer-invoice,order-links}.html`
- Test: `tests/e2e/solutions.spec.mjs`

**Interfaces:**
- Produces: section ids `retail, food, freelancers, online-sellers`. In-page sub-nav `<nav aria-label="Solutions">` linking to them. Four mockups follow the Task 7 `.ui-mockup` pattern.

- [ ] **Step 1: Write failing tests:**
```js
runPageBasics(test, '/solutions.html');
test('sub-nav links resolve to the four sections', ...);
test('each section has a quote with initials avatar and no face photo', ...); // blockquote present; any <img> in section has alt not describing a person (photos only from the scene slugs)
test('four mockups follow ui-mockup pattern', ...);
```
Run → FAIL.

- [ ] **Step 2: Build** per spec §8.4. Run → PASS. Commit `feat(prototype): add solutions page`.

- [ ] **Step 3: CHECKPOINT.** John reviews `/solutions.html`. Apply, commit, tick in `TRACKER.md`.

---

### Task 10: About prototype — REVIEW CHECKPOINT

**Files:**
- Create: `prototype/about.html`
- Test: `tests/e2e/about.spec.mjs`

**Interfaces:**
- Produces: section ids `story, mission, values, team, numbers, demo-note`. Team: 6 fictional members, initials avatars, no `<img>`. `#demo-note` links to the repo (placeholder `#repo`) and is visually prominent (hard-shadow card on yellow or blue).

- [ ] **Step 1: Write failing tests:**
```js
runPageBasics(test, '/about.html');
test('team grid has six members with no photos', ...);
test('numbers are labelled as demo figures', ...);   // #numbers text matches /demo/i
test('demo note explains portfolio project', ...);   // #demo-note text matches /portfolio/i and contains a link
```
Run → FAIL.

- [ ] **Step 2: Build** per spec §8.5. Run → PASS. Commit `feat(prototype): add about page`.

- [ ] **Step 3: CHECKPOINT.** John reviews `/about.html`. Apply, commit, tick in `TRACKER.md`.

---

### Task 11: Prototype-wide QA

**Files:**
- Create: `tests/e2e/links.spec.mjs`
- Modify: `TRACKER.md`, `docs/superpowers/specs/2026-11-13-byahe-wordpress-design.md` (only if a measurement contradicts a §10 target; record the finding, don't silently lower the target)

- [ ] **Step 1: Write `links.spec.mjs`:** crawl the five pages plus `components.html`. Every internal `href` (excluding `#repo`) loads with status 200, and every `#fragment` exists on its target page. Run → PASS (fix any failures in the owning page).

- [ ] **Step 2: Lighthouse** on `npm run preview:proto` for all five pages, mobile preset: `npx lighthouse http://localhost:4173/<page> --preset=perf --form-factor=mobile --output=json --output-path=./test-results/lh-<page>.json` and the full default categories. Targets (spec §10): performance ≥ 90, accessibility 100, best practices ≥ 95, SEO 100. Fix regressions. Record scores in a "Prototype scores" table under Notes in `TRACKER.md`.

- [ ] **Step 3: Manual keyboard pass** on each page at both widths (Tab through everything, visible focus everywhere, no traps). Note results in `TRACKER.md`.

- [ ] **Step 4: Run** `npm run test:unit && npm run test:e2e` → all PASS. **Commit** `test(prototype): add link checks and record audit scores`.

- [ ] **Step 5: Hand-off.** Tell John the prototypes are complete and approved, and that Plan B (WordPress conversion: theme.json + block styles, templates and parts, patterns, `hero-dashboard`, `pricing-toggle`/`pricing-card`, Block Bindings source, `ui-mockup` variants, demo content, blueprint, build-and-release Action) gets written next against the approved markup.
