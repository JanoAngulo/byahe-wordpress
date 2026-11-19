import { test, expect } from '@playwright/test';
import { runPageBasics } from '../e2e/helpers.mjs';
import { PAGES } from './pages.mjs';
import { adminContext, invalidBlocks, pageId, pathOf, withBlockPage } from './helpers.mjs';

runPageBasics(test, PAGES.home);

test.describe('home page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto(PAGES.home);
  });

  test('hero headline', async ({ page }) => {
    await expect(page.locator('h1')).toHaveText('Bayad na, tuloy ang byahe.');
  });

  test('sections appear in spec order', async ({ page }) => {
    const ids = await page.locator('main .wp-block-post-content > section[id]').evaluateAll((els) => els.map((e) => e.id));
    expect(ids).toEqual(['hero', 'proof', 'how-it-works', 'features', 'payment-methods', 'testimonial', 'pricing-teaser', 'security', 'cta']);
  });

  test('hero dashboard is live HTML', async ({ page }) => {
    const dash = page.locator('#hero .hero-dashboard');
    await expect(dash).toBeVisible();
    await expect(dash.locator('img')).toHaveCount(0);
    await expect(dash).toContainText('₱');
    await expect(dash).toHaveAttribute('role', 'img');
    expect((await dash.getAttribute('aria-label')).length).toBeGreaterThan(20);
  });

  test('hero CTAs', async ({ page }) => {
    await expect(page.locator('#hero a', { hasText: 'Sakay na' })).toHaveCount(1);
    expect(pathOf(await page.locator('#hero a', { hasText: 'Sakay na' }).evaluate((a) => a.href))).toBe(`${PAGES.pricing}#libre`);
    expect(pathOf(await page.locator('#hero a', { hasText: 'See pricing' }).evaluate((a) => a.href))).toBe(PAGES.pricing);
  });

  test('six feature links point at product anchors', async ({ page }) => {
    const hrefs = await page.locator('#features a').evaluateAll((as) => as.map((a) => a.href));
    expect(hrefs.map(pathOf)).toEqual(
      ['invoicing', 'payment-links', 'reminders', 'cash-flow', 'bir-receipts', 'team-access'].map((id) => `${PAGES.product}#${id}`),
    );
  });

  test('how it works is an ordered list of three steps', async ({ page }) => {
    await expect(page.locator('#how-it-works ol > li')).toHaveCount(3);
  });

  test('no third-party logos', async ({ page }) => {
    const labelled = await page.locator('img, svg').evaluateAll((els) =>
      els.map((e) => `${e.getAttribute('alt') ?? ''} ${e.getAttribute('aria-label') ?? ''}`).join(' '));
    expect(labelled).not.toMatch(/gcash|maya|visa|mastercard|bpi|bdo/i);
  });

  test('payment methods are text chips', async ({ page }) => {
    const chips = page.locator('#payment-methods li');
    expect(await chips.count()).toBeGreaterThanOrEqual(5);
    await expect(page.locator('#payment-methods .method-chips img, #payment-methods .method-chips svg')).toHaveCount(0);
    await expect(page.locator('#payment-methods')).toContainText('QR Ph');
  });

  test('pricing teaser prices', async ({ page }) => {
    const teaser = page.locator('#pricing-teaser');
    await expect(teaser).toContainText('₱499');
    await expect(teaser).toContainText('₱1,499');
    await expect(teaser).toContainText(/Let['’]s talk/);
  });

  test('pricing teaser figures use tabular digits', async ({ page }) => {
    const values = await page.locator('#pricing-teaser .num').evaluateAll((els) => els.map((e) => getComputedStyle(e).fontVariantNumeric));
    expect(values.length).toBeGreaterThanOrEqual(2);
    for (const v of values) expect(v).toContain('tabular-nums');
  });

  test('testimonial uses initials avatar, not photo', async ({ page }) => {
    await expect(page.locator('#testimonial img')).toHaveCount(0);
    await expect(page.locator('#testimonial blockquote')).toHaveCount(1);
    await expect(page.locator('#testimonial .avatar')).toHaveText(/^[A-Z]{2}$/);
  });

  test('security marks the BSP claim as demo', async ({ page }) => {
    await expect(page.locator('#security')).toContainText(/demo company/i);
  });

  test('photos have alt text and use the duotone treatment', async ({ page }) => {
    const imgs = page.locator('main img');
    expect(await imgs.count()).toBeGreaterThanOrEqual(1);
    for (const img of await imgs.all()) {
      expect((await img.getAttribute('alt')).length).toBeGreaterThan(10);
      await expect(img.locator('xpath=ancestor::*[contains(@class,"photo-duotone")]')).toHaveCount(1);
    }
  });
});

test.describe('card signboards', () => {
  const bands = { '#features': 'rgb(29, 63, 187)', '#security': 'rgb(255, 201, 60)' };
  for (const section of ['#features', '#security']) {
    test(`${section} icons sit in a signboard band, aligned with the title`, async ({ page }) => {
      await page.goto(PAGES.home);
      const cards = page.locator(`${section} .card-hard`);
      expect(await cards.count()).toBeGreaterThanOrEqual(3);
      for (const card of await cards.all()) {
        const sign = card.locator('.card-sign');
        await expect(sign).toHaveCount(1);
        expect(await sign.evaluate((el) => getComputedStyle(el).backgroundColor)).toBe(bands[section]);
        const iconX = (await sign.locator('svg').boundingBox()).x;
        const titleX = (await card.locator('h3').boundingBox()).x;
        expect(Math.abs(iconX - titleX)).toBeLessThan(1);
        const signBox = await sign.boundingBox();
        const cardBox = await card.boundingBox();
        expect(Math.abs(signBox.y - (cardBox.y + 2))).toBeLessThan(1);
      }
    });
  }
});

test('signboard band contrasts with its section background', async ({ page }) => {
  await page.goto(PAGES.home);
  for (const section of ['#features', '#security']) {
    const [sectionBg, bandBg] = await page.locator(section).evaluate((el) => [
      getComputedStyle(el).backgroundColor,
      getComputedStyle(el.querySelector('.card-sign')).backgroundColor,
    ]);
    expect(bandBg, section).not.toBe(sectionBg);
  }
});

test.describe('pricing teaser holds up at every width', () => {
  for (const width of [360, 768, 1024, 1280]) {
    test(`at ${width}px nothing wraps or spills`, async ({ page }, info) => {
      test.skip(info.project.name !== 'desktop', 'viewport set explicitly');
      await page.setViewportSize({ width, height: 900 });
      await page.goto(PAGES.home);
      const lines = (loc) => loc.evaluate((el) => {
        const r = document.createRange();
        r.selectNodeContents(el);
        return new Set([...r.getClientRects()].map((x) => Math.round(x.top))).size;
      });
      for (const btn of await page.locator('#pricing-teaser .wp-block-button__link').all()) expect(await lines(btn), 'button label').toBe(1);
      for (const p of await page.locator('#pricing-teaser .price, #pricing-teaser .price-text').all()) expect(await lines(p), 'price').toBe(1);
      for (const card of await page.locator('#pricing-teaser .tier').all()) {
        const c = await card.boundingBox();
        for (const pill of await card.locator('.pill').all()) {
          const b = await pill.boundingBox();
          expect(b.x >= c.x && b.x + b.width <= c.x + c.width, 'badge inside card').toBe(true);
        }
      }
    });
  }
});

test.describe('WordPress specifics', () => {
  test('the hero photo loads first with responsive sources', async ({ page }) => {
    await page.goto(PAGES.home);
    const img = page.locator('#hero img');
    await expect(img).toHaveAttribute('fetchpriority', 'high');
    await expect(img).not.toHaveAttribute('loading', 'lazy');
    await expect(img).toHaveAttribute('sizes', '(min-width: 1024px) 40vw, 100vw');
    const widths = (await img.getAttribute('srcset')).split(',').map((c) => c.trim().split(/\s+/)[1]);
    expect(widths).toEqual(['480w', '800w', '1200w']);
    await expect(img).toHaveAttribute('width', /\d+/);
  });

  test('teaser prices follow the pricing data', async ({ page }, info) => {
    test.skip(info.project.name !== 'desktop', 'data is the same at every width');
    await page.goto('/?byahe_test_price=1');
    await expect(page.locator('#pricing-teaser .price').first()).toHaveText('₱555');
  });

  test('teaser plan names are level 3 headings', async ({ page }) => {
    await page.goto(PAGES.home);
    for (const name of ['Negosyo', 'Pro', 'Enterprise']) {
      await expect(page.locator('#pricing-teaser').getByRole('heading', { level: 3, name })).toBeVisible();
    }
  });

  test('opens in the editor with every block valid', async ({ browser, baseURL }, info) => {
    test.skip(info.project.name !== 'desktop', 'editor check runs once');
    const ctx = await adminContext(browser, baseURL);
    const page = await ctx.newPage();
    expect(await invalidBlocks(page, await pageId(page, 'home'))).toEqual([]);
    await ctx.close();
  });
});

test.describe('hero dashboard and icon blocks', () => {
  test('render editable figures and an inline icon without scripts', async ({ browser, baseURL }, info) => {
    const content = [
      '<!-- wp:byahe/hero-dashboard {"values":{"total":"₱1,000"}} /-->',
      '<!-- wp:byahe/icon {"icon":"file-text","className":"card-sign"} /-->',
      '<!-- wp:byahe/icon {"icon":"not-an-icon"} /-->',
    ].join('\n');
    await withBlockPage(browser, baseURL, info.project.use.viewport, content, async (page) => {
      const dash = page.locator('.hero-dashboard');
      await expect(dash.locator('.hero-dashboard__total')).toHaveText('₱1,000');
      await expect(dash.locator('.hero-dashboard__list li')).toHaveCount(3);
      await expect(dash.locator('.hero-dashboard__chart span')).toHaveCount(6);
      await expect(dash.locator('.hero-dashboard__chart span.is-current')).toHaveCount(1);
      await expect(dash.locator('.pill-overdue')).toHaveText('Overdue');
      await expect(dash).toHaveAttribute('role', 'img');
      await expect(dash).toHaveAttribute('aria-label', /₱1,000/);
      await expect(dash).toHaveAttribute('aria-label', /Studio Bahaghari/);

      const sign = page.locator('.card-sign');
      await expect(sign.locator('svg.card-sign__icon[aria-hidden="true"]')).toHaveCount(1);
      await expect(page.locator('.wp-block-byahe-icon svg')).toHaveCount(1);

      const scripts = await page.locator('script[src*="byahe-core"]').count();
      expect(scripts).toBe(0);
    });
  });
});
