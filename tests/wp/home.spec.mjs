import { test, expect } from '@playwright/test';
import { withBlockPage } from './helpers.mjs';

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
