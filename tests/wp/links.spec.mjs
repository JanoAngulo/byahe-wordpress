import { test, expect } from '@playwright/test';
import { htmlIds } from '../lib/html-ids.mjs';
import { PAGES } from './pages.mjs';

const REPO = 'https://github.com/JanoAngulo/byahe-wordpress';

test.beforeEach(({}, info) => test.skip(info.project.name !== 'desktop', 'links are the same at every width'));

test('a missing page returns 404, so status checks mean something', async ({ request }) => {
  const res = await request.get('/no-such-page/');
  expect(res.status()).toBe(404);
});

for (const [name, path] of Object.entries(PAGES)) {
  test(`internal links on ${name} resolve`, async ({ page, request, baseURL }) => {
    await page.goto(path);
    const hrefs = await page.locator('a[href]').evaluateAll((as) => as.map((a) => a.getAttribute('href')));
    const origin = new URL(baseURL).origin;
    const targets = new Map();
    for (const href of new Set(hrefs)) {
      if (/^(mailto:|tel:|javascript:)/.test(href)) continue;
      const url = new URL(href, new URL(path, baseURL));
      if (url.origin !== origin) continue;
      expect(url.search, `${name} → ${href} uses a pretty permalink`).toBe('');
      if (!targets.has(url.pathname)) targets.set(url.pathname, new Set());
      if (url.hash) targets.get(url.pathname).add(url.hash.slice(1));
    }
    expect(targets.size, `${name} links to other pages`).toBeGreaterThan(1);

    for (const [target, fragments] of targets) {
      const res = await request.get(target);
      expect(res.status(), `${name} → ${target}`).toBe(200);
      if (!fragments.size) continue;
      const ids = htmlIds(await res.text());
      for (const id of fragments) {
        expect(ids.has(id), `${name} → ${target}#${id}`).toBe(true);
      }
    }
  });
}

test('every page footer and the About demo note point at the GitHub repo', async ({ page }) => {
  for (const path of Object.values(PAGES)) {
    await page.goto(path);
    await expect(page.locator(`footer a[href="${REPO}"]`), path).toHaveCount(1);
  }
  await page.goto(PAGES.about);
  await expect(page.locator('#demo-note a', { hasText: 'GitHub' })).toHaveAttribute('href', REPO);
  await page.goto(PAGES.pricing);
  await expect(page.locator('#demo-dialog a', { hasText: 'GitHub' })).toHaveAttribute('href', REPO);
});
