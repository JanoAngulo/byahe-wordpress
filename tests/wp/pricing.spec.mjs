import { test, expect } from '@playwright/test';
import { runPageBasics } from '../e2e/helpers.mjs';
import { PAGES } from './pages.mjs';
import { adminContext, invalidBlocks, pageId, pathOf } from './helpers.mjs';

runPageBasics(test, PAGES.pricing);

const price = (page, tier) => page.locator(`#${tier} .price`);
const toggleOf = (page) => page.getByRole('switch', { name: 'Bill yearly' });

test.describe('plans and billing toggle', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto(PAGES.pricing);
  });

  test('has the four tiers with Libre anchored and plan names as h3', async ({ page }) => {
    for (const [id, name] of [['libre', 'Libre'], ['negosyo', 'Negosyo'], ['pro', 'Pro'], ['enterprise', 'Enterprise']]) {
      await expect(page.locator(`#${id}`)).toHaveCount(1);
      await expect(page.locator(`#${id}`).getByRole('heading', { level: 3, name })).toBeVisible();
    }
    await expect(page.locator('#negosyo')).toContainText('Most popular');
    await expect(page.getByRole('list').filter({ has: page.locator('#libre') })).toHaveCount(1);
  });

  test('defaults to monthly', async ({ page }) => {
    await expect(toggleOf(page)).toHaveAttribute('aria-checked', 'false');
    await expect(price(page, 'libre')).toHaveText('₱0');
    await expect(price(page, 'negosyo')).toHaveText('₱499');
    await expect(price(page, 'pro')).toHaveText('₱1,499');
    await expect(page.locator('#negosyo .price-period')).toHaveText('per month');
    await expect(page.locator('#enterprise .price-text')).toHaveText(/Let['’]s talk/);
  });

  test('toggle by keyboard switches to yearly and back', async ({ page }) => {
    const toggle = toggleOf(page);
    await toggle.focus();
    await page.keyboard.press('Space');
    await expect(toggle).toHaveAttribute('aria-checked', 'true');
    await expect(price(page, 'negosyo')).toHaveText('₱4,990');
    await expect(price(page, 'pro')).toHaveText('₱14,990');
    await expect(price(page, 'libre')).toHaveText('₱0');
    await expect(page.locator('#negosyo .price-period')).toHaveText('per year');
    await expect(page.locator('#billing-status')).toContainText(/yearly/i);
    await page.keyboard.press('Enter');
    await expect(toggle).toHaveAttribute('aria-checked', 'false');
    await expect(price(page, 'negosyo')).toHaveText('₱499');
    await expect(page.locator('#billing-status')).toContainText(/monthly/i);
  });

  test('prices keep their width when the toggle changes them', async ({ page }) => {
    expect(await price(page, 'pro').evaluate((el) => getComputedStyle(el).fontVariantNumeric)).toContain('tabular-nums');
  });

  test('toggling billing never changes card sizes', async ({ page }) => {
    const sizes = () => page.locator('.tier').evaluateAll((els) => els.map((e) => `${Math.round(e.getBoundingClientRect().width)}x${Math.round(e.getBoundingClientRect().height)}`));
    const before = await sizes();
    await toggleOf(page).click();
    await expect(price(page, 'negosyo')).toHaveText('₱4,990');
    expect(await sizes()).toEqual(before);
  });

  test('pills never wrap onto two lines', async ({ page }) => {
    for (const pill of await page.locator('#plans .pill').all()) {
      const lines = await pill.evaluate((el) => {
        const r = document.createRange();
        r.selectNodeContents(el);
        return new Set([...r.getClientRects()].map((x) => Math.round(x.top))).size;
      });
      expect(lines).toBe(1);
    }
  });
});

test('plan names and prices line up across cards on desktop', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.goto(PAGES.pricing);
  for (const sel of ['.tier__name', '.tier__price']) {
    const ys = await page.locator(sel).evaluateAll((els) => els.map((e) => Math.round(e.getBoundingClientRect().top)));
    expect(new Set(ys).size, sel).toBe(1);
  }
});

test('without JavaScript: monthly prices, no toggle, Libre button still goes somewhere', async ({ browser, baseURL }, info) => {
  const context = await browser.newContext({ baseURL, javaScriptEnabled: false, viewport: info.project.use.viewport });
  const page = await context.newPage();
  await page.goto(PAGES.pricing);
  await expect(page.locator('#negosyo .price')).toHaveText('₱499');
  await expect(page.locator('#pro .price')).toHaveText('₱1,499');
  await expect(page.locator('.billing-toggle')).toBeHidden();
  expect(pathOf(await page.locator('#libre .opens-demo-dialog a').evaluate((a) => a.href))).toBe(`${PAGES.about}#demo-note`);
  await context.close();
});

test('the pricing filter changes the card price', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'data is the same at every width');
  await page.goto(`${PAGES.pricing}?byahe_test_price=1`);
  await expect(price(page, 'negosyo')).toHaveText('₱555');
});

test('header CTA lands on the Libre tier', async ({ page }) => {
  await page.goto(PAGES.home);
  await page.locator('header .header-cta a').click();
  await expect(page).toHaveURL(/\/pricing\/#libre$/);
  await expect(page.locator('#libre')).toBeInViewport();
});

test('interactive scripts load only on Pricing', async ({ page }) => {
  await page.goto(PAGES.home);
  const modules = await page.locator('script[type="module"], script[type="importmap"]').evaluateAll((els) => els.map((e) => e.src || e.textContent).join(' '));
  expect(modules).not.toMatch(/pricing-toggle|demo-dialog/);
});

test.describe('demo dialog', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto(PAGES.pricing);
  });

  test('opens from Libre, keeps focus inside, Esc closes and returns focus', async ({ page }) => {
    const trigger = page.locator('#libre .opens-demo-dialog a');
    await trigger.focus();
    await page.keyboard.press('Enter');
    const dialog = page.locator('#demo-dialog');
    await expect(dialog).toBeVisible();
    await expect(page.locator('#demo-dialog-title')).toHaveText('BYAHE is a demo company');
    expect(await page.evaluate(() => document.getElementById('demo-dialog').contains(document.activeElement))).toBe(true);
    await page.keyboard.press('Escape');
    await expect(dialog).toBeHidden();
    await expect(trigger).toBeFocused();
  });

  test('close button closes and returns focus', async ({ page }) => {
    const trigger = page.locator('#negosyo .opens-demo-dialog a');
    await trigger.click();
    await page.locator('#demo-dialog [data-close-dialog]').click();
    await expect(page.locator('#demo-dialog')).toBeHidden();
    await expect(trigger).toBeFocused();
  });

  test('padding clicks keep it open, backdrop clicks close it', async ({ page }) => {
    await page.locator('#libre .opens-demo-dialog a').click();
    const box = await page.locator('#demo-dialog').boundingBox();
    await page.mouse.click(box.x + 5, box.y + 5);
    await expect(page.locator('#demo-dialog')).toBeVisible();
    await page.mouse.click(2, 2);
    await expect(page.locator('#demo-dialog')).toBeHidden();
  });
});

test('opens in the editor with every block valid', async ({ browser, baseURL }, info) => {
  test.skip(info.project.name !== 'desktop', 'editor check runs once');
  const ctx = await adminContext(browser, baseURL);
  const page = await ctx.newPage();
  expect(await invalidBlocks(page, await pageId(page, 'pricing'))).toEqual([]);
  await ctx.close();
});
