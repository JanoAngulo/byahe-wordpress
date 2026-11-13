# BYAHE — WordPress Portfolio Project Tracker

Fictional Philippine fintech: invoicing and payments for small businesses.
Built as static HTML + Tailwind CSS first, then converted into a custom WordPress block theme.

> Demo company. Not a real financial service.

---

## Decisions

| Area | Decision |
|---|---|
| Purpose | Portfolio piece for SaaS / fintech job applications |
| Focus | WordPress engineering (custom block theme, custom blocks, CPTs, code quality) |
| Product | Invoicing + payments (QR Ph, cards, bank transfer) for PH small businesses |
| Working name | BYAHE (can change) |
| Brand | **Full Jeepney**: loud yellow/blue everywhere, stripes, hard offset shadows |
| Palette | Yellow `#FFC93C` · Royal blue `#1D3FBB` · Navy `#13205C` · Red `#D62828` |
| Type | Cubao Free (display h1/h2, wordmark) · Space Grotesk (headings, figures) · Inter (body); fluid modular scale |
| Voice | English with Taglish punchlines ("Bayad na, tuloy ang byahe.") |
| Workflow | HTML + Tailwind prototype → WordPress block theme |
| Local dev | WordPress Playground CLI (no Docker) |
| Reviewer demo | Playground blueprint link + public GitHub repo |
| Accessibility | WCAG AA contrast on every page, even at full volume |
| Photos | Stock scenes from Pexels (Unsplash fallback), logged in `CREDITS.md`; no faces beside fictional testimonials/team |
| Not included | WooCommerce, page builders (Elementor etc.), jQuery |

---

## Phase 0 — Setup

- [x] Project folder structure (`prototype/`, `theme/byahe/`, `plugins/byahe-core/`)
- [x] Tailwind build pipeline (Vite + Tailwind v4)
- [x] Design tokens (`tokens/tokens.json` → Tailwind `@theme` + `theme.json`, contrast-checked)
- [x] WordPress Playground running locally (`npm run dev:wp` → http://127.0.0.1:9400)
- [ ] Playground blueprint (auto-installs theme, plugin, demo content)
- [x] Git repo + `.gitignore`

---

## Phase 1 — Core pages (priority)

Each page: build HTML prototype → **John reviews & approves** → convert to WordPress.

| Page | HTML prototype | John approved | WP template | Content | QA (a11y + Lighthouse) |
|---|---|---|---|---|---|
| Shared components | [x] | [x] | [ ] | — | [ ] |
| Home | [x] | [x] | [ ] | [ ] | [ ] |
| Product / Features | [x] | [x] | [ ] | [ ] | [ ] |
| Pricing | [x] | [x] | [ ] | [ ] | [ ] |
| Solutions (by business type) | [x] | [x] | [ ] | [ ] | [ ] |
| About | [x] | [x] | [ ] | [ ] | [ ] |

Shared parts for Phase 1:

- [ ] Header + navigation (mobile menu, keyboard accessible)
- [ ] Footer (with "demo company" note)
- [ ] Stripe divider, hard-shadow card, pill button components
- [ ] "Sakay na →" CTA → Pricing Libre tier → "demo company" dialog

Custom blocks for Phase 1:

- [ ] `byahe/hero-dashboard` (dynamic, editable figures)
- [ ] `byahe/pricing-toggle` + `byahe/pricing-card` (Interactivity API)
- [ ] Pricing data via Block Bindings source `byahe/pricing`
- [ ] `byahe/ui-mockup` with variants: invoice, payment-qr, reminders, cashflow-chart, bir-receipt, team-roles (+ Solutions variants)

Demo delivery:

- [ ] GitHub Action: build + publish theme/plugin zips to rolling release
- [ ] Blueprint installs from release zips + imports demo content

---

## Phase 2 — Remaining pages

| Page | HTML prototype | WP template | Content | QA (a11y + Lighthouse) |
|---|---|---|---|---|
| Calculators (fee savings) | [ ] | [ ] | [ ] | [ ] |
| Security & Compliance | [ ] | [ ] | [ ] | [ ] |
| Resources / Blog | [ ] | [ ] | [ ] | [ ] |
| Help Center | [ ] | [ ] | [ ] | [ ] |
| Careers | [ ] | [ ] | [ ] | [ ] |
| Contact / Book a demo | [ ] | [ ] | [ ] | [ ] |

Content types (in `byahe-core` plugin):

- [ ] Customer Stories (+ industry taxonomy)
- [ ] Help Articles (+ category taxonomy, search)
- [ ] Careers / Jobs
- [ ] Changelog

Blocks:

- [ ] Fee-savings calculator block (Interactivity API)
- [ ] Stats / count-up block
- [ ] Block patterns for marketers (hero, feature grid, CTA, testimonials)

---

## Phase 3 — Showcase & proof

- [ ] `/design-system` page (tokens, type, buttons, forms, cards)
- [ ] Case study page (brief → tokens → HTML → WordPress → results)
- [x] Lighthouse scores recorded (prototype; see Notes)
- [ ] axe accessibility audit, zero critical issues
- [ ] PHPCS (WordPress Coding Standards) passing
- [ ] ESLint passing
- [ ] GitHub Actions CI (lint on push)
- [ ] README with one-command setup + Playground demo link
- [ ] Swap the "View the code on GitHub" links (footer, About, demo dialog) from the profile URL to the repo URL once it is public
- [ ] 1–2 min screen recording of editing a page in WordPress
- [ ] Add to portfolio (jrla1219.web.app) + LinkedIn

---

## Notes

### Prototype scores

Lighthouse 13.6, mobile preset (simulated slow 4G, mid-range phone), on the production build served by `npm run preview:proto`. Measured 2026-11-13. Targets: performance 90+, accessibility 100, best practices 95+, SEO 100.

| Page | Performance | Accessibility | Best practices | SEO | LCP | CLS |
|---|---|---|---|---|---|---|
| Home | 96 | 100 | 100 | 100 | 2.7 s | 0 |
| Product | 100 | 100 | 100 | 100 | 0.8 s | 0 |
| Pricing | 100 | 100 | 100 | 100 | 0.8 s | 0 |
| Solutions | 100 | 100 | 100 | 100 | 0.8 s | 0 |
| About | 100 | 100 | 100 | 100 | 0.8 s | 0 |

What the first run caught, and the fixes:

- Home performance was 88 (LCP 3.9 s). The hero photo shipped at 1782 px to a 376 px slot. Every photo now has 480, 800 and 1200 px WebP versions with `srcset` and `sizes`.
- Best practices was 96 on every page because `/favicon.ico` returned 404. Added an SVG favicon.
- Solutions CLS was 0.15. Inter loading late rewrapped the section links in the hero from three rows to two. Cubao Free and Inter (latin) are now preloaded.

### Keyboard

`tests/e2e/keyboard.spec.mjs` tabs through each page at 1280 px and 360 px, from the skip link to the last footer link. Every stop is on screen and shows a focus ring, and nothing traps focus. All five pages pass at both widths. Breaking the global focus ring makes it fail.

### Carry into Plan B (deferred from the prototype review)

- Reduced motion: also zero `animation-delay` and `transition-delay` (hero bars still snap in over 300ms).
- Demo dialog: clicks inside its padding close it; close only on true backdrop clicks.
- Mobile header: Tab order (toggle, then CTA) differs from the visual order (CTA, then toggle).
- Collapse the mobile menu only after `nav.js` runs, not on the early `js` class.
- Off-token shadows (3, 6, 7px) and a 6px radius: add tokens or snap to existing ones.
- theme.json: register `fontFace` for the three families; fix "2XL"-style size names.
- Home hero `sizes` is 75vw; the column is about 45vw.
- Link checker matches ids by substring; parse the HTML instead.
- Pricing plan names are `<p>`; make them `h3`.
