import { test, expect } from '@playwright/test';

test('components page loads with self-hosted Inter', async ({ page }) => {
  const response = await page.goto('/components.html');
  expect(response.status()).toBe(200);
  const family = await page.evaluate(() => getComputedStyle(document.body).fontFamily);
  expect(family).toContain('Inter');
});

test('no requests go to Google Fonts', async ({ page }) => {
  const external = [];
  page.on('request', (r) => { if (/fonts\.(googleapis|gstatic)\.com/.test(r.url())) external.push(r.url()); });
  await page.goto('/components.html', { waitUntil: 'networkidle' });
  expect(external).toEqual([]);
});
