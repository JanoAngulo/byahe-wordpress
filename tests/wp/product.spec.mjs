import { test, expect } from '@playwright/test';
import { runPageBasics } from '../e2e/helpers.mjs';
import { PAGES } from './pages.mjs';
import { adminContext, invalidBlocks, pageId } from './helpers.mjs';

runPageBasics(test, PAGES.product);

const FEATURES = ['invoicing', 'payment-links', 'reminders', 'cash-flow', 'bir-receipts', 'team-access'];
const VARIANTS = ['invoice', 'payment-qr', 'reminders', 'cashflow-chart', 'bir-receipt', 'team-roles'];

test.describe('product page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto(PAGES.product);
  });

  test('marks Product as the current page', async ({ page }) => {
    await expect(page.locator(`nav[aria-label="Primary"] a[href$="${PAGES.product}"]`)).toHaveAttribute('aria-current', 'page');
  });

  test('all six feature anchors exist, in order, followed by integrations', async ({ page }) => {
    const ids = await page.locator('main .wp-block-post-content > section[id]').evaluateAll((els) => els.map((e) => e.id));
    expect(ids.filter((id) => FEATURES.includes(id) || id === 'integrations')).toEqual([...FEATURES, 'integrations']);
  });

  test('hero jump links point at every feature section', async ({ page }) => {
    const hrefs = await page.locator('.feature-jump a').evaluateAll((as) => as.map((a) => a.getAttribute('href')));
    expect(hrefs).toEqual(FEATURES.map((id) => `#${id}`));
  });

  test('each feature section has one mockup of the matching variant', async ({ page }) => {
    for (const [i, id] of FEATURES.entries()) {
      await expect(page.locator(`#${id} .ui-mockup`)).toHaveCount(1);
      await expect(page.locator(`#${id} .ui-mockup--${VARIANTS[i]}`)).toHaveCount(1);
    }
  });

  test('each mockup has role img and a descriptive aria-label', async ({ page }) => {
    const mockups = page.locator('.ui-mockup');
    await expect(mockups).toHaveCount(6);
    for (const m of await mockups.all()) {
      await expect(m).toHaveAttribute('role', 'img');
      expect((await m.getAttribute('aria-label')).length).toBeGreaterThan(30);
    }
  });

  test('mockups are live HTML, not images', async ({ page }) => {
    await expect(page.locator('.ui-mockup img')).toHaveCount(0);
  });

  test('each feature section lists three benefits', async ({ page }) => {
    for (const id of FEATURES) {
      await expect(page.locator(`#${id} .benefits > li`)).toHaveCount(3);
    }
  });

  test('money in mockups uses tabular digits', async ({ page }) => {
    const values = await page.locator('.ui-mockup .num').evaluateAll((els) => els.map((e) => getComputedStyle(e).fontVariantNumeric));
    expect(values.length).toBeGreaterThanOrEqual(10);
    for (const v of values) expect(v).toContain('tabular-nums');
  });

  test('BIR receipt mockup says it is not a valid receipt', async ({ page }) => {
    await expect(page.locator('.ui-mockup--bir-receipt')).toHaveAttribute('aria-label', /demo/i);
    await expect(page.locator('#bir-receipts')).toContainText(/not a valid/i);
  });

  test('integrations has three cards and a CTA follows', async ({ page }) => {
    await expect(page.locator('#integrations .card-hard')).toHaveCount(3);
    await expect(page.locator('main .wp-block-post-content > section#cta')).toHaveCount(1);
  });
});

test('feature sections alternate layout on desktop', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.goto(PAGES.product);
  const sides = [];
  for (const id of FEATURES) {
    const section = await page.locator(`#${id}`).boundingBox();
    const mock = await page.locator(`#${id} .ui-mockup`).boundingBox();
    sides.push(mock.x + mock.width / 2 > section.x + section.width / 2 ? 'right' : 'left');
  }
  for (let i = 1; i < sides.length; i++) expect(sides[i], `section ${FEATURES[i]}`).not.toBe(sides[i - 1]);
});

test('home feature links land on product sections', async ({ page }) => {
  await page.goto(PAGES.home);
  const hrefs = await page.locator('#features a').evaluateAll((as) => as.map((a) => a.href));
  for (const href of hrefs) {
    await page.goto(href);
    const id = href.split('#')[1];
    await expect(page.locator(`#${id}`)).toBeAttached();
  }
});

test('hero headline breaks only at the comma, into two lines', async ({ page }) => {
  await page.goto(PAGES.product);
  const lines = await page.locator('#product-title').evaluate((el) => {
    const range = document.createRange();
    range.selectNodeContents(el);
    return new Set([...range.getClientRects()].map((r) => Math.round(r.top))).size;
  });
  expect(lines).toBe(2);
});

test.describe('payment QR mockup spacing', () => {
  test('QR code is centered in its box and the caption clears the edge', async ({ page }) => {
    await page.goto(PAGES.product);
    const box = await page.locator('.ui-qr').boundingBox();
    const code = await page.locator('.ui-qr__code').boundingBox();
    const label = await page.locator('.ui-qr__label').boundingBox();
    const leftGap = code.x - box.x;
    const rightGap = box.x + box.width - (code.x + code.width);
    expect(Math.abs(leftGap - rightGap)).toBeLessThan(1);
    expect(box.y + box.height - (label.y + label.height)).toBeGreaterThanOrEqual(12);
    expect(label.y - (code.y + code.height)).toBeGreaterThanOrEqual(8);
  });

  test('payment pills sit clear of the order text', async ({ page }) => {
    await page.goto(PAGES.product);
    const order = await page.locator('.ui-mockup--payment-qr .ui-order').boundingBox();
    const methods = await page.locator('.ui-mockup--payment-qr .ui-methods').boundingBox();
    expect(methods.y - (order.y + order.height)).toBeGreaterThanOrEqual(16);
  });
});

test('every product screen is a white card as wide as its column', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.goto(PAGES.product);
  for (const id of FEATURES) {
    const mock = page.locator(`#${id} .ui-mockup`);
    expect(await mock.evaluate((el) => getComputedStyle(el).backgroundColor), id).toBe('rgb(255, 255, 255)');
    const textCol = await page.locator(`#${id} h2`).evaluate((el) => el.closest('.container > div').getBoundingClientRect().width);
    const mockWidth = (await mock.boundingBox()).width;
    expect(Math.abs(mockWidth - textCol), id).toBeLessThan(2);
  }
});

test('invoice line items keep names left-aligned next to the quantity', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.goto(PAGES.product);
  const rows = page.locator('.ui-mockup--invoice .ui-lines .ui-row');
  for (const row of await rows.all()) {
    const gap = await row.evaluate((el) => {
      const textRect = (node) => { const r = document.createRange(); r.selectNodeContents(node); return r.getBoundingClientRect(); };
      const qty = textRect(el.children[0]);
      const name = textRect(el.children[1]);
      return name.left - qty.right;
    });
    expect(gap).toBeLessThan(32);
  }
});

test('Cubao headlines use real spaces, not wide non-breaking ones', async ({ page }) => {
  for (const path of Object.values(PAGES)) {
    await page.goto(path);
    const text = await page.locator('h1').evaluate((el) => el.textContent);
    expect(text, path).not.toContain('\u00a0');
  }
});

test('opens in the editor with every block valid', async ({ browser, baseURL }, info) => {
  test.skip(info.project.name !== 'desktop', 'editor check runs once');
  const ctx = await adminContext(browser, baseURL);
  const page = await ctx.newPage();
  expect(await invalidBlocks(page, await pageId(page, 'product'))).toEqual([]);
  await ctx.close();
});
