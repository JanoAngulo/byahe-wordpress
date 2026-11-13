import { test, expect } from '@playwright/test';

const PAGES = ['/index.html', '/product.html', '/pricing.html', '/solutions.html', '/about.html', '/components.html'];
const SKIP = new Set(['#repo']);

test.beforeEach(({}, info) => test.skip(info.project.name !== 'desktop', 'links are the same at every width'));

test('a missing page returns 404, so status checks mean something', async ({ request }) => {
  const res = await request.get('/no-such-page.html');
  expect(res.status()).toBe(404);
});

for (const path of PAGES) {
  test(`internal links on ${path} resolve`, async ({ page, request, baseURL }) => {
    await page.goto(path);
    const hrefs = await page.locator('a[href]').evaluateAll((as) => as.map((a) => a.getAttribute('href')));
    const origin = new URL(baseURL).origin;
    const targets = new Map();
    for (const href of new Set(hrefs)) {
      if (SKIP.has(href) || /^(mailto:|tel:|javascript:)/.test(href)) continue;
      const url = new URL(href, new URL(path, baseURL));
      if (url.origin !== origin) continue;
      const key = url.pathname === '/' ? '/index.html' : url.pathname;
      if (!targets.has(key)) targets.set(key, new Set());
      if (url.hash) targets.get(key).add(url.hash.slice(1));
    }

    for (const [target, fragments] of targets) {
      const res = await request.get(target);
      expect(res.status(), `${path} → ${target}`).toBe(200);
      if (!fragments.size) continue;
      const html = await res.text();
      for (const id of fragments) {
        expect(html.includes(`id="${id}"`), `${path} → ${target}#${id}`).toBe(true);
      }
    }
  });
}

test('demo dialog triggers are links that still go somewhere without JavaScript', async ({ page }) => {
  for (const path of ['/pricing.html', '/components.html']) {
    await page.goto(path);
    const triggers = page.locator('[data-open-demo-dialog]');
    expect(await triggers.count(), path).toBeGreaterThan(0);
    for (const t of await triggers.all()) {
      expect(await t.evaluate((el) => el.tagName), path).toBe('A');
      await expect(t).toHaveAttribute('href', 'about.html#demo-note');
    }
  }
});
