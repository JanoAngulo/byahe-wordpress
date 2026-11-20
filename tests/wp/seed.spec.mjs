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

test('seeded content stores apostrophes as the editor would save them, so wptexturize curls them', async ({ browser, baseURL }) => {
  const { adminContext, restNonce, pageId } = await import('./helpers.mjs');
  const ctx = await adminContext(browser, baseURL);
  const page = await ctx.newPage();
  await page.goto('/wp-admin/');
  const nonce = await restNonce(page);
  for (const slug of ['home', 'product', 'pricing', 'solutions', 'about']) {
    const id = await pageId(page, slug);
    const res = await page.request.get(`/?rest_route=/wp/v2/pages/${id}&context=edit&_fields=content`, { headers: { 'X-WP-Nonce': nonce } });
    const raw = (await res.json()).content.raw;
    expect(raw, slug).not.toMatch(/&#0?39;/);
  }
  await ctx.close();
});

test('every page has the prototype meta description, kept in the page excerpt', async ({ page }) => {
  const { readFileSync } = await import('node:fs');
  const files = { home: 'index', product: 'product', pricing: 'pricing', solutions: 'solutions', about: 'about' };
  for (const [key, file] of Object.entries(files)) {
    const html = readFileSync(new URL(`../../prototype/${file}.html`, import.meta.url), 'utf8');
    const wanted = html.match(/<meta name="description" content="([^"]+)"/)[1];
    await page.goto(PAGES[key]);
    await expect(page.locator('meta[name="description"]'), key).toHaveCount(1);
    await expect(page.locator('meta[name="description"]'), key).toHaveAttribute('content', wanted);
  }
});
