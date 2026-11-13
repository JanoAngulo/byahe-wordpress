import { test, expect } from '@playwright/test';
import { runPageBasics } from '../e2e/helpers.mjs';
import { PAGES } from './pages.mjs';

const REPO = 'https://github.com/JanoAngulo/byahe-wordpress';
const PAGE_NAMES = ['Product', 'Solutions', 'Pricing', 'About'];

runPageBasics(test, PAGES.about);

test.describe('header', () => {
  test('skip link moves focus to main', async ({ page }) => {
    await page.goto(PAGES.about);
    await page.keyboard.press('Tab');
    const skip = page.getByRole('link', { name: 'Skip to content' });
    await expect(skip).toBeFocused();
    await expect(skip).toBeVisible();
    await expect(skip).toHaveAttribute('href', '#main');
    await expect(page.locator('main#main')).toHaveCount(1);
    await expect(page.getByRole('link', { name: 'Skip to content' })).toHaveCount(1);
  });

  test('wordmark links home', async ({ page }) => {
    await page.goto(PAGES.about);
    const href = await page.locator('header a.wordmark').getAttribute('href');
    expect(new URL(href).pathname).toBe('/');
  });

  test('primary nav has exactly the four Phase 1 links', async ({ page }) => {
    await page.goto(PAGES.about);
    const links = page.locator('nav[aria-label="Primary"] a');
    const hrefs = await links.evaluateAll((as) => as.map((a) => new URL(a.href).pathname));
    expect(hrefs).toEqual([PAGES.product, PAGES.solutions, PAGES.pricing, PAGES.about]);
  });

  test('marks the current page', async ({ page }) => {
    await page.goto(PAGES.pricing);
    const nav = page.getByRole('navigation', { name: 'Primary' });
    await expect(nav.locator('a[aria-current="page"]')).toHaveCount(1);
    expect(new URL(await nav.locator('a[aria-current="page"]').evaluate((a) => a.href)).pathname).toBe(PAGES.pricing);
  });

  test('CTA links to the Libre plan', async ({ page }) => {
    await page.goto(PAGES.about);
    const cta = page.locator('header .header-cta a');
    await expect(cta).toHaveText('Sakay na →');
    const url = new URL(await cta.evaluate((a) => a.href));
    expect(url.pathname + url.hash).toBe(`${PAGES.pricing}#libre`);
  });

  test('header is off-white so it separates from yellow heroes', async ({ page }) => {
    await page.goto(PAGES.about);
    expect(await page.locator('.site-header').evaluate((el) => getComputedStyle(el).backgroundColor)).toBe('rgb(255, 248, 231)');
  });

  test('desktop shows the links without a menu button', async ({ page }, info) => {
    test.skip(info.project.name !== 'desktop', 'desktop only');
    await page.goto(PAGES.about);
    await expect(page.getByRole('button', { name: 'Open menu' })).toBeHidden();
    for (const name of PAGE_NAMES) {
      await expect(page.getByRole('navigation', { name: 'Primary' }).getByRole('link', { name, exact: true })).toBeVisible();
    }
  });

  test('mobile Tab order matches the visual order: wordmark, menu, CTA', async ({ page }, info) => {
    test.skip(info.project.name !== 'mobile', 'mobile only');
    await page.goto(PAGES.about);
    const stops = [];
    for (let i = 0; i < 4; i++) {
      await page.keyboard.press('Tab');
      stops.push(await page.evaluate(() => {
        const el = document.activeElement;
        return { label: el.textContent.trim() || el.getAttribute('aria-label'), x: el.getBoundingClientRect().left };
      }));
    }
    const [, wordmark, toggle, cta] = stops;
    expect(wordmark.label).toContain('BYAHE');
    expect(toggle.label).toBe('Open menu');
    expect(cta.label).toBe('Sakay na →');
    expect(wordmark.x).toBeLessThan(toggle.x);
    expect(toggle.x).toBeLessThan(cta.x);
  });

  test('mobile menu opens with Enter, closes with Esc and returns focus', async ({ page }, info) => {
    test.skip(info.project.name !== 'mobile', 'mobile only');
    await page.goto(PAGES.about);
    const toggle = page.getByRole('button', { name: 'Open menu' });
    const overlay = page.locator('.wp-block-navigation__responsive-container');
    await toggle.focus();
    await page.keyboard.press('Enter');
    await expect(overlay).toHaveClass(/is-menu-open/);
    for (const name of PAGE_NAMES) await expect(overlay.getByRole('link', { name, exact: true })).toBeVisible();
    expect(await overlay.evaluate((el) => getComputedStyle(el).backgroundColor)).toBe('rgb(255, 248, 231)');
    await page.keyboard.press('Escape');
    await expect(overlay).not.toHaveClass(/is-menu-open/);
    await expect(toggle).toBeFocused();
  });
});

test.describe('footer', () => {
  test('says BYAHE is a demo company', async ({ page }) => {
    await page.goto(PAGES.about);
    await expect(page.locator('footer')).toContainText(/fictional company/i);
    await expect(page.locator('footer')).toContainText(/not a real financial service/i);
  });

  test('links to photo credits and the repo', async ({ page }) => {
    await page.goto(PAGES.about);
    const credits = new URL(await page.locator('footer a', { hasText: 'Photo credits' }).evaluate((a) => a.href));
    expect(credits.pathname + credits.hash).toBe(`${PAGES.about}#credits`);
    await expect(page.locator(`footer a[href="${REPO}"]`)).toHaveCount(1);
  });

  test('stripe shows red, yellow and blue', async ({ page }) => {
    await page.goto(PAGES.about);
    const bg = await page.locator('footer .is-style-stripe').first().evaluate((el) => getComputedStyle(el).backgroundImage);
    for (const c of ['rgb(214, 40, 40)', 'rgb(255, 201, 60)', 'rgb(29, 63, 187)']) expect(bg).toContain(c);
  });

  test('lists every page when JavaScript is off', async ({ browser, baseURL }, info) => {
    const context = await browser.newContext({ javaScriptEnabled: false, viewport: info.project.use.viewport });
    const page = await context.newPage();
    await page.goto(new URL(PAGES.about, baseURL).href);
    const footerNav = page.getByRole('navigation', { name: 'Footer' });
    for (const name of ['Features', 'Solutions', 'Pricing', 'About']) {
      await expect(footerNav.getByRole('link', { name, exact: true })).toBeVisible();
    }
    await context.close();
  });
});

test.describe('type and focus', () => {
  test('h1 and h2 use self-hosted Cubao and it loads', async ({ page }) => {
    await page.goto(PAGES.about);
    await page.evaluate(() => document.fonts.ready);
    for (const sel of ['main h1', 'main h2']) {
      const family = await page.locator(sel).first().evaluate((el) => getComputedStyle(el).fontFamily);
      expect(family.startsWith('"Cubao Free"'), `${sel}: ${family}`).toBe(true);
    }
    expect(await page.evaluate(() => document.fonts.check('40px "Cubao Free"'))).toBe(true);
    expect(await page.locator('main h1').evaluate((el) => getComputedStyle(el).fontSynthesisWeight)).toBe('none');
  });

  test('h3 stays on Space Grotesk', async ({ page }) => {
    await page.goto(PAGES.about);
    const family = await page.evaluate(() => {
      const h = document.createElement('h3');
      document.querySelector('main').append(h);
      const f = getComputedStyle(h).fontFamily;
      h.remove();
      return f;
    });
    expect(family.startsWith('"Space Grotesk Variable"')).toBe(true);
  });

  test('display and body fonts are preloaded from the theme', async ({ page }) => {
    await page.goto(PAGES.about);
    const preloads = await page.locator('link[rel="preload"][as="font"]').evaluateAll((ls) => ls.map((l) => l.href));
    expect(preloads.some((h) => h.endsWith('/themes/byahe/assets/fonts/cubao-free-variable.woff2'))).toBe(true);
    expect(preloads.some((h) => h.endsWith('/themes/byahe/assets/fonts/inter-latin-wght-normal.woff2'))).toBe(true);
  });

  test('buttons show a focus ring', async ({ page }) => {
    await page.goto(PAGES.about);
    const btn = page.locator('header .header-cta a');
    await btn.focus();
    expect(await btn.evaluate((el) => parseFloat(getComputedStyle(el).outlineWidth))).toBeGreaterThanOrEqual(2);
  });
});

test('a missing page returns 404 inside the site shell', async ({ page }) => {
  const res = await page.goto('/no-such-page/');
  expect(res.status()).toBe(404);
  await expect(page.locator('h1')).toHaveCount(1);
  await expect(page.locator('header.site-header')).toHaveCount(1);
  await expect(page.locator('footer')).toContainText(/fictional company/i);
});
