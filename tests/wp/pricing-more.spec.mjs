import { test, expect } from '@playwright/test';
import { PAGES } from './pages.mjs';

test.describe('fees, comparison and FAQ', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto(PAGES.pricing);
  });

  test('sections appear in order', async ({ page }) => {
    const ids = await page.locator('main .wp-block-post-content > section[id]').evaluateAll((els) => els.map((e) => e.id));
    expect(ids).toEqual(['pricing-hero', 'plans', 'fees', 'compare', 'faq', 'cta']);
  });

  test('fees table values come from the pricing data', async ({ page }) => {
    const fees = page.locator('#fees table.data-table');
    await expect(fees.locator('caption')).toHaveText('Transaction fees');
    await expect(fees.locator('th[scope=row]')).toHaveCount(3);
    await expect(fees).toContainText('1.0%');
    await expect(fees).toContainText('2.9% + ₱15');
    await expect(fees).toContainText('₱10 flat');
    await expect(page.locator('#fees .table-scroll')).toHaveAttribute('role', 'region');
  });

  test('comparison table has proper headers and a caption', async ({ page }) => {
    const table = page.locator('#compare table.data-table.data-table--compare');
    expect(await table.locator('th[scope=col]').count()).toBeGreaterThanOrEqual(4);
    expect(await table.locator('th[scope=row]').count()).toBeGreaterThanOrEqual(5);
    await expect(table.locator('caption')).toHaveCount(1);
    await expect(page.locator('#compare figcaption')).toHaveCount(0);
  });

  test('comparison marks are readable by screen readers', async ({ page }) => {
    const marks = page.locator('#compare td .mark');
    expect(await marks.count()).toBeGreaterThan(0);
    for (const m of await marks.all()) await expect(m.locator('.sr-only')).toHaveText(/^(Included|Not included)$/);
  });

  test('the Negosyo column is highlighted', async ({ page }) => {
    await expect(page.locator('#compare thead th.is-featured')).toHaveText('Negosyo');
    expect(await page.locator('#compare tbody .is-featured').count()).toBe(8);
  });

  test('FAQ uses details/summary and opens', async ({ page }) => {
    expect(await page.locator('#faq details').count()).toBeGreaterThanOrEqual(5);
    const first = page.locator('#faq details').first();
    await first.locator('summary').click();
    await expect(first).toHaveAttribute('open', '');
  });

  test('FAQ toggle icon is drawn, not a font glyph', async ({ page }) => {
    const after = await page.locator('#faq summary').first().evaluate((el) => {
      const cs = getComputedStyle(el, '::after');
      return { content: cs.content, bg: cs.backgroundImage };
    });
    expect(after.content).toBe('""');
    expect(after.bg).toContain('svg');
  });

  test('side card offers to start on Libre', async ({ page }) => {
    const aside = page.locator('#faq .faq-cta');
    await expect(aside).toContainText('Still deciding?');
    await aside.locator('.opens-demo-dialog a').click();
    await expect(page.locator('#demo-dialog')).toBeVisible();
  });
});

test('comparison table scrolls inside its wrapper on mobile', async ({ page }, info) => {
  test.skip(info.project.name !== 'mobile', 'mobile only');
  await page.goto(PAGES.pricing);
  const wrap = page.locator('#compare .table-scroll');
  await expect(wrap).toHaveAttribute('tabindex', '0');
  await expect(wrap).toHaveAttribute('role', 'region');
  const { sw, cw } = await wrap.evaluate((el) => ({ sw: el.scrollWidth, cw: el.clientWidth }));
  expect(sw).toBeGreaterThan(cw);
});

test('FAQ shares the left edge of the other sections', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.goto(PAGES.pricing);
  const faqX = (await page.locator('#faq-title').boundingBox()).x;
  const plansX = (await page.locator('#plans-title').boundingBox()).x;
  expect(Math.abs(faqX - plansX)).toBeLessThan(1);
});

test('FAQ questions sit beside the heading on desktop, stacked on phones', async ({ page }, info) => {
  await page.goto(PAGES.pricing);
  const title = await page.locator('#faq-title').boundingBox();
  const list = await page.locator('#faq .faq').boundingBox();
  if (info.project.name === 'desktop') {
    expect(list.x).toBeGreaterThan(title.x + 300);
    expect(Math.abs(list.y - title.y)).toBeLessThan(40);
  } else {
    expect(list.y).toBeGreaterThan(title.y + title.height);
  }
});

test('the fee filter changes the fees table', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'data is the same at every width');
  await page.goto(`${PAGES.pricing}?byahe_test_price=1`);
  await expect(page.locator('#fees table')).toContainText('0.5%');
});

test('plan cards stay inside their grid and the VAT note sits below them', async ({ page }) => {
  await page.goto(PAGES.pricing);
  const list = await page.locator('.tier-list').boundingBox();
  for (const card of await page.locator('.tier-list > .tier').all()) {
    const c = await card.boundingBox();
    expect(c.y + c.height).toBeLessThanOrEqual(list.y + list.height + 1);
  }
  const note = await page.locator('#plans p', { hasText: 'Prices include VAT' }).boundingBox();
  expect(note.y).toBeGreaterThan(list.y + list.height);
});
