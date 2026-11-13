import { test, expect } from '@playwright/test';
import { PAGES } from './pages.mjs';

test.beforeEach(({}, info) => test.skip(info.project.name !== 'desktop', 'server state is the same at every width'));

test('seeded pages respond', async ({ request }) => {
  for (const path of [PAGES.product, PAGES.pricing, PAGES.solutions, PAGES.about]) {
    expect((await request.get(path)).status(), path).toBe(200);
  }
});

test('the front page is the seeded Home page', async ({ page }) => {
  await page.goto(PAGES.home);
  await expect(page.locator('body')).toHaveClass(/\bhome\b/);
  await expect(page.locator('body')).toHaveClass(/\bpage\b/);
});

test('WordPress sample content is gone', async ({ request }) => {
  expect((await request.get('/sample-page/')).status()).toBe(404);
  expect((await request.get('/hello-world/')).status()).toBe(404);
});

test('seeding leaves exactly one page per slug', async ({ request }) => {
  const res = await request.get('/?rest_route=/wp/v2/pages&per_page=100&_fields=slug');
  const slugs = (await res.json()).map((p) => p.slug).sort();
  expect(slugs).toEqual(['about', 'home', 'pricing', 'product', 'solutions']);
});
