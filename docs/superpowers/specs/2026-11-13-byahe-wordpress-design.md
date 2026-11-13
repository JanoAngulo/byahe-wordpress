# BYAHE — WordPress Portfolio Site: Design

Date: 2026-11-13
Status: Approved 2026-11-13
Progress tracking: [`TRACKER.md`](../../../TRACKER.md)

Decisions are marked **(owner)** when John decided them and **(call)** when they are a default chosen during design that the builder or John may revisit.

---

## 1. Intent

John is applying to companies that build SaaS and fintech products. He needs a portfolio piece that proves he can ship a **production-quality WordPress site the way a product company would build its marketing site**, not a theme-customization demo.

The site is the public marketing website for **BYAHE**, a fictional Philippine fintech that lets small businesses send invoices and get paid by QR Ph, card and bank transfer. **(owner)**

The thing being showcased is **WordPress engineering** **(owner)**: a custom block theme, custom blocks, custom content types, clean code that passes standards checks, and an editor experience a marketer could use. Design quality and accessibility come along as proof of craft.

## 2. Goals

1. A hiring manager can open a single link and browse the live site **and** its WordPress admin with no setup (WordPress Playground blueprint).
2. A developer reviewer can clone the public GitHub repo and run it locally with one command.
3. The code reads as professional: WordPress Coding Standards, escaping and sanitizing, translation-ready strings, linting in CI.
4. The brand is loud and memorable ("Full Jeepney") and still passes WCAG 2.2 AA.
5. Phase 1 pages are complete and polished before anything in Phase 2 starts. **(owner)**

## 3. Non-goals

- A working product. No accounts, no real payments, no backend beyond WordPress.
- WooCommerce or any e-commerce. **(owner)**
- Multilingual support (strings are translation-ready, but only English/Taglish ships).
- Production hosting. The Playground link is the live demo.

## 4. Anti-goals (would make this a failure even if it works)

- **Page builders** (Elementor, Divi, WPBakery) or a purchased or starter theme as the base.
- **jQuery**, or scripts loaded on pages that don't use them.
- Anything that looks like it impersonates a real company: no GCash, Maya, BPI or other third-party **logos**, and no copy that implies BYAHE is real. Payment methods can be named in text only. Every page footer says it's a demo company.
- Loud design that fails contrast checks or traps keyboard users.
- A theme where the editor view looks nothing like the front end.
- Content locked in PHP templates that a marketer couldn't edit.

## 5. Constraints

- **Machine:** Windows 11, Node 26, npm 11, PHP 8.2 CLI, Composer 2.8, Git. **No Docker.**
- **Local WordPress:** WordPress Playground CLI (`@wp-playground/cli`) **(owner)**. It uses SQLite, not MySQL, so nothing may depend on MySQL-specific behavior.
- **WordPress version:** latest stable; minimum PHP 8.1 declared in theme and plugin headers. **(call)**
- **Workflow:** every page is first built as static HTML + Tailwind CSS, then converted to WordPress. **(owner)** The HTML prototype is the design source of truth; there is no Figma file.

## 6. Brand system

### 6.1 Direction: Full Jeepney **(owner)**

Yellow and blue everywhere, red/yellow/blue stripe dividers, hard offset shadows on cards, pill buttons. Personality on every page, including Security and Pricing. Only the hard rule from §6.4 keeps it in check.

### 6.2 Tokens **(owner for colors/fonts, call for the rest)**

| Token | Value | Use |
|---|---|---|
| `yellow` | `#FFC93C` | primary background, highlights |
| `blue` | `#1D3FBB` | CTAs, secondary backgrounds |
| `navy` | `#13205C` | text, borders, hard shadows |
| `red` | `#D62828` | accents, alerts, stripe |
| `cream` | `#FFF8E7` | light section background (call) |
| `white` | `#FFFFFF` | cards |
| Display | Cubao Free (variable width), by Aaron Amar | h1, h2, wordmark |
| Headings | Space Grotesk 500/700 | h3/h4, UI headings, all figures |
| Body | Inter 400/500/600 | |
| Shadow `hard` | `4px 4px 0 navy` (sm), `8px 8px 0 navy` (lg) | |
| Border | `2px solid navy` | cards, inputs |
| Radius | `10px` cards, `999px` buttons/pills | |

Fonts are **self-hosted** (WOFF2, registered through `theme.json` `fontFace`), not loaded from Google. **(call)** Better for performance and privacy.

### 6.3 Voice

English, with short Taglish punchlines in headlines and CTAs ("Bayad na, tuloy ang byahe.", "Sakay na →"). Body copy, legal and security text stay in plain English. **(owner/call)**

### 6.4 Contrast rules (hard rule)

| Pair | Ratio | Allowed |
|---|---|---|
| navy on yellow | ~9.7:1 | any text |
| white on blue | ~8.5:1 | any text |
| yellow on blue | ~5.5:1 | any text |
| navy on white / cream | >13:1 | any text |
| red on white | ~5.0:1 | any text |
| red on yellow | ~3.3:1 | **large text (≥24px, or ≥18.66px bold) and non-text only** |
| navy on blue | <2:1 | **never** |

The builder verifies the actual ratios with a checker. Any new pair must pass AA before use.

### 6.5 Imagery

- **Stock photos: yes (owner).** Used for **scenes**: storefronts, sari-sari stores, market stalls, cafés, jeepneys, hands paying by QR on a phone. They add local warmth next to the product UI.
- **Source (call, delegated by owner):** **Pexels** first (strongest Philippine/Southeast Asian content, free commercial license, no attribution required), **Unsplash** as fallback. Every image is logged in `CREDITS.md` with source URL, photographer and license, and credited in the footer or on the About page.
- **Faces (call):** no identifiable faces next to fictional testimonials, team members or customer stories, because that would imply a real person endorses or works at a fictional company. Testimonials and the team grid use initials avatars. Crowds, hands and people seen from behind or at a distance are fine.
- **Treatment (call):** photos get a consistent Full Jeepney treatment (yellow/blue duotone or a hard-shadow navy frame) so mixed stock sources look like one brand. Exported as WebP, max 2000px wide, uploaded to the Media Library so WordPress generates responsive sizes. Every content image has written alt text. Decorative images use empty alt.
- **Product visuals** stay HTML/CSS (`byahe/hero-dashboard`, `byahe/ui-mockup`), never screenshots.
- **Icons:** Lucide (ISC license), inlined as SVG.

## 7. Information architecture

### Phase 1 (build first) **(owner)**

Home · Product · Pricing · Solutions · About

### Phase 2

Calculators · Security & Compliance · Resources/Blog · Help Center · Careers · Contact/Book a demo, plus the content types in §9.3.

### Phase 3

Design System page · Case Study page · audits · CI · README · demo video.

Primary nav (Phase 1): Product · Solutions · Pricing · About, plus a "Sakay na →" (Start free) CTA. Phase 2 adds Resources and Security. Links to pages that don't exist yet are left out of the nav, not stubbed.

## 8. Phase 1 page contents

Copy below is direction, not final. The builder writes final copy in the voice from §6.3. Numbers are fictional and labeled as such where they could read as claims.

### 8.1 Home

1. **Hero** (yellow): headline "Bayad na, tuloy ang byahe.", subline about invoices and payments for PH small businesses, primary CTA "Sakay na →" and secondary "See pricing". Right side: **hero dashboard** (live HTML/CSS: collected this month, recent invoices with PAID/PENDING pills, a small bar chart).
2. **Stripe divider.**
3. **Social proof strip:** 5–6 fictional customer wordmarks (text-based, e.g. "Kape't Tinapay Co.").
4. **How it works:** 3 steps (Create invoice → Customer pays by QR Ph/card/bank → Money lands, books update).
5. **Feature grid:** 6 hard-shadow cards (Invoicing, Payment links & QR, Auto reminders, Cash-flow dashboard, BIR-ready receipts, Team access). Each links to its section on Product.
6. **Payment methods:** text chips (QR Ph, Visa/Mastercard, InstaPay, PESONet, e-wallets). No third-party logos.
7. **Testimonial:** one large quote from a fictional customer.
8. **Pricing teaser:** the 3 paid tiers, compact, linking to Pricing.
9. **Security strip:** 3 cards (encryption, PCI DSS, "BSP-registered*" with "*demo company" note), Full Jeepney style on blue.
10. **Final CTA band.**

### 8.2 Product

Hero, then one section per feature from the Home grid (anchor IDs match the Home links). Each section has a heading, 2–3 benefit bullets and a UI mockup (invoice editor, payment link with QR, reminder timeline, cash-flow chart, BIR receipt, team roles). Alternate left/right layout. Ends with an integrations row (fictional or generic: "Accounting", "Spreadsheets", "Webhooks/API") and a CTA.

### 8.3 Pricing

- **Pricing toggle block:** Monthly / Yearly ("2 months libre").
- **Tiers (fictional, call):**
  - **Libre:** ₱0, up to 10 invoices per month
  - **Negosyo:** ₱499/mo, unlimited invoices, reminders. Marked "Most popular."
  - **Pro:** ₱1,499/mo, team access, cash-flow dashboard, API
  - **Enterprise:** "Let's talk"
- **Transaction fees table** (fictional, call): QR Ph 1.0%, cards 2.9% + ₱15, bank transfer ₱10 flat.
- **Feature comparison table:** accessible `<table>` with proper headers, scrolls horizontally on mobile.
- **FAQ:** accordion using native `<details>`/`<summary>` (core Details block).
- CTA.

### 8.4 Solutions

One page **(call)** with an in-page sub-nav and four sections: **Sari-sari & retail**, **Food & cafés**, **Freelancers & agencies**, **Online sellers**. Each section covers the pain point, how BYAHE helps (3 bullets), a fictional mini customer quote and a relevant mockup. Splitting into child pages or a CPT is left for Phase 2 if needed.

### 8.5 About

Origin story (founders from Bulacan, office in BGC, all fictional), mission, values (3–4 cards), team grid (6 fictional people, initials avatars, roles), a "by the numbers" stats row (labeled demo figures), and a prominent **"This is a demo company"** callout explaining it is a portfolio project, linking to the GitHub repo and later the Case Study page.

### 8.6 Global

- **Header:** logo wordmark, nav, CTA. On mobile, a menu toggle using the core Navigation block's overlay. Must be fully keyboard operable with visible focus.
- **Footer:** stripe divider, link columns (Phase 1 pages only for now), demo-company disclaimer, link to repo.
- **404 page** and a basic **search results** template in the theme (core requirement for a complete block theme).

## 9. Architecture

### 9.1 Repo layout **(call)**

```
/
├─ TRACKER.md
├─ README.md
├─ blueprint.json                 # Playground: installs theme + plugin + demo content
├─ package.json                   # root scripts (dev, build, lint), npm workspaces
├─ composer.json                  # PHPCS + WPCS
├─ tokens/tokens.json             # single source of design tokens
├─ scripts/build-tokens.mjs       # tokens.json → theme.json settings + Tailwind @theme CSS
├─ prototype/                     # Static HTML + Tailwind (Vite multi-page)
│  ├─ index.html, product.html, pricing.html, solutions.html, about.html
│  └─ src/styles.css
├─ theme/byahe/                   # Block theme
│  ├─ style.css, theme.json, functions.php
│  ├─ templates/  parts/  patterns/  styles/
│  ├─ assets/fonts/  assets/css/ (compiled)
│  └─ inc/ (block styles, asset loading)
├─ plugins/byahe-core/            # Content + custom blocks
│  ├─ byahe-core.php
│  ├─ src/blocks/<block>/ (block.json, edit.js, render.php, view.js, style.css)
│  └─ inc/ (post types, taxonomies — Phase 2)
├─ content/demo.xml               # WXR export of demo pages (call)
└─ .github/workflows/ci.yml       # Phase 3
```

**Theme vs. plugin split:** the theme owns presentation (templates, patterns, styles, block styles). The plugin owns functionality that should survive a theme switch (custom blocks, post types, taxonomies). This is standard WordPress practice and a talking point in interviews.

### 9.2 Tailwind in WordPress (the key technical decision) **(call)**

- **Tokens live once** in `tokens/tokens.json`. A build script writes them into `theme.json` (`settings.color.palette`, `typography.fontSizes`, `spacing.spacingSizes`, `shadow.presets`) **and** into a Tailwind v4 `@theme` block. Prototype, editor and front end all share the same values.
- **Core blocks** are styled through `theme.json` (global and per-block styles) and **block styles** registered in PHP (`is-style-hard-shadow` on Group, `is-style-pill` on Button, `is-style-stripe` on Separator). Users pick these in the editor.
- **Tailwind utilities** are used in custom block markup (`render.php`) and pattern markup where core block settings fall short. Tailwind scans `theme/` and `plugins/` sources. **Preflight is disabled** in the WordPress build, because `theme.json` and core styles handle the reset and preflight breaks the editor.
- The compiled CSS loads on the front end and in the editor (`add_editor_style` / block `editorStyle`), so the editor matches the front end.
- **Converting a prototype to WordPress:** each prototype section becomes either (a) a block pattern built from core blocks plus block styles, or (b) a custom block when it needs data or interaction. Default to (a).

### 9.3 Custom blocks

Phase 1:

| Block | Why custom | Tech |
|---|---|---|
| `byahe/pricing-toggle` + `byahe/pricing-card` | monthly/yearly state shared across cards | Interactivity API (`data-wp-interactive`, store with `isYearly`), prices as block attributes |
| `byahe/hero-dashboard` | editable figures, complex UI markup | dynamic block (`render.php`), attributes for amounts and invoice rows, no front-end JS |
| `byahe/ui-mockup` **(owner)** | the ~10 product UI mockups on Product and Solutions are too dense for core blocks; must stay live HTML/CSS and editable | one dynamic block with a `variant` attribute (`invoice`, `payment-qr`, `reminders`, `cashflow-chart`, `bir-receipt`, `team-roles`, plus Solutions variants as needed). Each variant has its own editable attributes (amounts, names, labels, statuses) shown in the inspector. Shared `render.php` dispatches to one template file per variant. Decorative: wrapped with `role="img"` and an `aria-label` summary, or `aria-hidden` with a visible caption. No front-end JS. |

Phase 2: `byahe/fee-calculator` (Interactivity API), `byahe/stats` (count-up, respects reduced motion). CPTs: Customer Stories (+ `industry` taxonomy), Help Articles (+ `help_category`), Jobs, Changelog.

All blocks are built with `@wordpress/scripts`, have `block.json` (apiVersion 3), and load `viewScriptModule` only where interactive.

### 9.4 Editor experience

- Every Phase 1 page is assembled from **patterns** registered in `theme/byahe/patterns/` (Hero, Feature grid, How it works, Testimonial, Pricing teaser, Security strip, CTA band, Team grid, …), so a marketer can rebuild or add pages from the inserter.
- **Page content** is real, editable post content. It is not hardcoded in templates. Templates only provide header, footer and the content area.
- Demo content ships as `content/demo.xml` and is imported by the Playground blueprint. **(call)** The builder may switch to blueprint `wp-cli` steps if WXR import of block markup proves unreliable.

### 9.5 Local dev & demo

- `npm run dev:proto`: Vite dev server for the HTML prototype.
- `npm run dev:wp`: Playground CLI with the theme and plugin mounted and the blueprint applied.
- `npm run build`: tokens → Tailwind → blocks.
- The public demo link is `https://playground.wordpress.net/?blueprint-url=<raw GitHub URL of blueprint.json>`. The blueprint installs theme and plugin, imports demo content, activates the theme and logs the visitor in as admin.
- **Build output and the demo (call):** compiled CSS and block `build/` folders are **not committed**. A small GitHub Action (moved forward from Phase 3 into Phase 1) runs `npm run build` on each push to `main` and publishes `byahe-theme.zip` and `byahe-core.zip` to a rolling GitHub Release. The blueprint installs from those zip URLs. Locally, `npm run dev:wp` mounts the built folders directly.

### 9.6 Primary CTA in Phase 1 (call)

"Sakay na →" / "Start free" links to the **Libre tier on Pricing** (`/pricing/#libre`). The Libre card's button opens a small native `<dialog>` that says "BYAHE is a demo company — no real signup" and links to the GitHub repo. No dead links, and nothing implies BYAHE is real. Phase 2 can repoint it to Contact/Book a demo.

### 9.7 Pricing data source (call)

Tier names, prices and fees are defined **once** in the plugin (a filterable PHP array) and exposed through a custom **Block Bindings** source (`byahe/pricing`). The Pricing page cards and the Home teaser both read from it, so a price change is one edit. Editors still control layout and copy around the prices.

## 10. Quality bar

- **Accessibility:** WCAG 2.2 AA. Semantic landmarks, one `h1` per page, skip link, visible focus (a navy or yellow focus ring that contrasts with its background), keyboard-only test of nav, toggle and accordion. `prefers-reduced-motion` respected. Zero critical/serious axe issues.
- **Performance targets (call):** Lighthouse mobile on Home ≥ 90 performance, 100 accessibility, ≥ 95 best practices, 100 SEO. No render-blocking third-party requests.
- **Where they're measured (call):** Playground runs PHP in WebAssembly, so server response time is not representative. Performance is measured on the **static HTML prototype** (front-end weight, LCP, CLS) and on a **local Playground CLI** run with server response time reported separately. Accessibility, best-practices and SEO scores are measured on the WordPress build. The case study states this method openly.
- **Responsive:** 360px to 1440px+, no horizontal scroll except inside data tables.
- **Code:** PHPCS with WordPress Coding Standards, zero errors. `wp-scripts lint-js` and `lint-style` clean. All output escaped, all input sanitized, strings wrapped in `__()` with text domain `byahe` / `byahe-core`.
- **Browsers:** current Chrome, Edge, Firefox, Safari.

## 11. Order of work

1. **Phase 0:** repo, tokens pipeline, Tailwind, prototype scaffold, Playground running with an empty theme and plugin.
2. **Phase 1 prototype:** shared components first (header, footer, buttons, cards, stripe), then Home → Product → Pricing → Solutions → About as static HTML.
   **Review checkpoint (owner):** John reviews each prototype page, and the shared components before the first page. A page is converted to WordPress only after John approves its prototype. Prototype work on the next page continues while John reviews.
3. **Phase 1 WordPress:** theme.json + block styles, templates and parts, then per approved page: its patterns and any custom blocks it needs (`hero-dashboard`, `pricing-toggle`/`pricing-card`, `ui-mockup` variants). Then demo content, blueprint and the build-and-release GitHub Action.
4. **Phase 1 QA:** a11y and Lighthouse pass, then fixes. Update `TRACKER.md`.
5. Phases 2 and 3 get their own short design pass before building.

## 12. Left to the builder

- Final copy (within §6.3 voice), section spacing and exact layouts within each section.
- Vite configuration details and whether the prototype shares header/footer via a partials plugin or duplication.
- Exact font size, spacing and shadow scales beyond the values in §6.2.
- Pattern granularity and naming.
- Which fictional customer names, team names and figures to use.
