import { test, expect } from '@playwright/test';
import { runPageBasics } from './helpers.mjs';

runPageBasics(test, '/product.html');

const FEATURES = ['invoicing', 'payment-links', 'reminders', 'cash-flow', 'bir-receipts', 'team-access'];
const VARIANTS = ['invoice', 'payment-qr', 'reminders', 'cashflow-chart', 'bir-receipt', 'team-roles'];

test.describe('product page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/product.html');
  });

  test('marks Product as the current page', async ({ page }) => {
    await expect(page.locator('#primary-menu a[data-nav="product"]')).toHaveAttribute('aria-current', 'page');
  });

  test('all six feature anchors exist, in order, followed by integrations', async ({ page }) => {
    const ids = await page.locator('main > section[id]').evaluateAll((els) => els.map((e) => e.id));
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
    await expect(page.locator('main > section#cta')).toHaveCount(1);
  });
});

test('feature sections alternate layout on desktop', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.goto('/product.html');
  const sides = [];
  for (const id of FEATURES) {
    const section = await page.locator(`#${id}`).boundingBox();
    const mock = await page.locator(`#${id} .ui-mockup`).boundingBox();
    sides.push(mock.x + mock.width / 2 > section.x + section.width / 2 ? 'right' : 'left');
  }
  for (let i = 1; i < sides.length; i++) expect(sides[i], `section ${FEATURES[i]}`).not.toBe(sides[i - 1]);
});

test('home feature links land on product sections', async ({ page }) => {
  await page.goto('/');
  const hrefs = await page.locator('#features a').evaluateAll((as) => as.map((a) => a.getAttribute('href')));
  for (const href of hrefs) {
    await page.goto(`/${href}`);
    const id = href.split('#')[1];
    await expect(page.locator(`#${id}`)).toBeAttached();
  }
});
