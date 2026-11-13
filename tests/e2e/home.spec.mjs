import { test, expect } from '@playwright/test';
import { runPageBasics } from './helpers.mjs';

runPageBasics(test, '/');

test.describe('home page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('hero headline', async ({ page }) => {
    await expect(page.locator('h1')).toHaveText('Bayad na, tuloy ang byahe.');
  });

  test('sections appear in spec order', async ({ page }) => {
    const ids = await page.locator('main > section[id]').evaluateAll((els) => els.map((e) => e.id));
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
    await expect(page.locator('#hero a', { hasText: 'Sakay na' })).toHaveAttribute('href', 'pricing.html#libre');
    await expect(page.locator('#hero a', { hasText: 'See pricing' })).toHaveAttribute('href', 'pricing.html');
  });

  test('six feature links point at product anchors', async ({ page }) => {
    const hrefs = await page.locator('#features a').evaluateAll((as) => as.map((a) => a.getAttribute('href')));
    expect(hrefs).toEqual([
      'product.html#invoicing',
      'product.html#payment-links',
      'product.html#reminders',
      'product.html#cash-flow',
      'product.html#bir-receipts',
      'product.html#team-access',
    ]);
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
    await expect(teaser).toContainText("Let's talk");
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
