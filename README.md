# BYAHE

A custom WordPress block theme and plugin for a made-up Philippine fintech: invoicing and payments for small businesses, paid by QR Ph, card or bank transfer.

> **Demo company.** BYAHE is not a real company or financial service. It's a portfolio project by [John Russel Angulo](https://jrla1219.web.app). Every customer, team member, figure and quote is made up.

**[Open the live demo in WordPress Playground →](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/JanoAngulo/byahe-wordpress/main/blueprint.json)**

The demo runs a full WordPress site in your browser, with nothing to install. It loads the latest build, creates the five pages and logs you in as an admin, so you can open any page in the editor and change it. Nothing you change is saved after you close the tab.

## What's in it

- **Five pages:** Home, Product, Pricing, Solutions and About. Each one was designed as an HTML and Tailwind CSS prototype first, then rebuilt as WordPress patterns.
- **A block theme** (`theme/byahe`): `theme.json` generated from one token file, block styles, templates, header and footer parts, and a pattern for every page section.
- **A plugin** (`plugins/byahe-core`) with seven dynamic blocks:
  - hero dashboard
  - UI mockups (ten product screens built in HTML, not screenshots)
  - icon
  - billing toggle and pricing cards (Interactivity API)
  - fee table
  - demo notice dialog
- **One source for prices.** Plans and fees live in the plugin and reach the page through a Block Bindings source (`byahe/pricing`), so changing a price updates the Pricing cards, the yearly price and the Home teaser together.
- **Editable content.** The pages are ordinary WordPress pages built from core blocks. A marketer can change copy, swap sections or insert any section from the BYAHE pattern category.
- **Accessibility.** WCAG AA contrast, a keyboard path through every page with a visible focus ring, screen reader labels on every mockup, and reduced-motion support. Lighthouse scores 100 for accessibility, best practices and SEO on every page (mobile).

## Run it locally

Needs Node.js 26, PHP 8.1+ and Composer. No Docker: WordPress runs in [Playground CLI](https://wordpress.github.io/wordpress-playground/developers/local-development/wp-playground-cli/).

```sh
npm install
composer install
npm run dev:wp
```

Then open http://127.0.0.1:9400. You're logged in as `admin` (password `password`). The theme and plugin folders are mounted, so edits to their PHP show up on reload. Changes under `src/` need `npm run build:wp`. Each start resets the five pages from their patterns.

To work on the HTML prototype instead, run `npm run dev:proto`.

## Scripts

| Command | What it does |
|---|---|
| `npm run dev:wp` | Build, then start WordPress on port 9400 |
| `npm run build:wp` | Build tokens, fonts and photos, CSS, blocks and blueprints |
| `npm run lint` | PHPCS (WordPress Coding Standards), ESLint and Stylelint |
| `npm run test:unit` | Unit tests for the build scripts |
| `npm run test:wp` | Playwright tests against WordPress, at 1280 px and 360 px |
| `npm test` | Unit tests plus the prototype's Playwright tests |
| `npm run package:wp` | Zip the built theme and plugin into `dist/zips/` |

`npm run test:wp` starts its own WordPress on port 9410 if one isn't running. The tests cover:

- axe accessibility checks
- keyboard navigation
- every internal link and `#anchor`
- pricing with JavaScript turned off
- every page opening in the editor with no invalid blocks
- saving a page without changes and confirming the front end stays the same

## How it's built

```
tokens/tokens.json      design tokens → Tailwind theme and theme.json
prototype/              HTML + Tailwind prototype (Vite)
theme/byahe/            block theme: theme.json, templates, parts, patterns, CSS
plugins/byahe-core/     blocks, pricing data and bindings, `wp byahe seed`
blueprints/steps.json   shared Playground steps → blueprint.local.json, blueprint.json
scripts/                build scripts (tokens, assets, CSS, blueprints, zips)
tests/                  unit tests, prototype tests (e2e/) and WordPress tests (wp/)
```

- **Demo content:** pages are created by a WP-CLI command, `wp byahe seed`. It saves each page pattern as real block content, so internal links work at any site address.
- **CSS:** one Tailwind v4 build serves the front end and the editor. It shares a component stylesheet with the prototype.
- **Release:** every push to `main` runs a GitHub Action that lints, builds and uploads `byahe-theme.zip` and `byahe-core.zip` to the `demo-latest` release. The public blueprint installs those zips.

## Credits

Photos from Pexels, fonts (Cubao Free, Inter, Space Grotesk) and icons (Lucide) are listed with their licenses in [CREDITS.md](CREDITS.md) and on the site's About page.

## License

GPL-2.0-or-later, the same as WordPress.
