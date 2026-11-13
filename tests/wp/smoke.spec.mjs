import { test, expect } from '@playwright/test';
import { PAGES } from './pages.mjs';

test('home responds with the byahe theme', async ({ page }) => {
  const res = await page.goto(PAGES.home);
  expect(res.status()).toBe(200);
  await expect(page.locator('body')).toHaveClass(/wp-theme-byahe/);
});

test('loads the compiled theme stylesheet', async ({ page }) => {
  await page.goto(PAGES.home);
  await expect(page.locator('link[rel="stylesheet"][href*="themes/byahe/assets/css/theme.css"]')).toHaveCount(1);
});
