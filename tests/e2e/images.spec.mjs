import { test, expect } from '@playwright/test';

const PAGES = ['/index.html', '/product.html', '/pricing.html', '/solutions.html', '/about.html'];

test.beforeEach(({}, info) => test.skip(info.project.name !== 'desktop', 'markup is the same at every width'));

for (const path of PAGES) {
  test(`photos on ${path} ship responsive sizes`, async ({ page, request }) => {
    await page.goto(path);
    const imgs = await page.locator('.photo-duotone img').evaluateAll((els) =>
      els.map((img) => ({ src: img.getAttribute('src'), srcset: img.getAttribute('srcset'), sizes: img.getAttribute('sizes') })),
    );
    for (const img of imgs) {
      expect(img.sizes, img.src).toBeTruthy();
      const candidates = (img.srcset ?? '').split(',').map((c) => c.trim()).filter(Boolean);
      expect(candidates.length, img.src).toBeGreaterThanOrEqual(3);
      for (const c of candidates) {
        const [url, descriptor] = c.split(/\s+/);
        expect(descriptor, c).toMatch(/^\d+w$/);
        expect((await request.get(url)).status(), url).toBe(200);
      }
    }
  });

  test(`photo sizes on ${path} match the rendered width at 1280px`, async ({ page }) => {
    await page.goto(path);
    const imgs = await page.locator('.photo-duotone img').evaluateAll((els) =>
      els.map((img) => ({ src: img.getAttribute('src'), sizes: img.getAttribute('sizes'), width: img.getBoundingClientRect().width })),
    );
    for (const img of imgs) {
      const vw = img.sizes.match(/\(min-width: 1024px\) (\d+)vw/);
      if (!vw) continue;
      const declared = (Number(vw[1]) / 100) * 1280;
      expect(declared / img.width, `${img.src}: sizes ${declared}px vs rendered ${img.width}px`).toBeGreaterThanOrEqual(1);
      expect(declared / img.width, `${img.src}: sizes ${declared}px vs rendered ${img.width}px`).toBeLessThan(1.35);
    }
  });

  test(`${path} declares a favicon that loads`, async ({ page, request }) => {
    await page.goto(path);
    const href = await page.locator('link[rel="icon"]').first().getAttribute('href');
    expect((await request.get(href)).status()).toBe(200);
  });
}

test('display and body fonts are preloaded, and each preload is the file the page uses', async ({ page }) => {
  await page.goto('/index.html');
  await page.evaluate(() => document.fonts.ready);
  const preloads = await page.locator('link[rel="preload"][as="font"]').evaluateAll((ls) => ls.map((l) => l.href));
  const fetched = await page.evaluate(() => performance.getEntriesByType('resource').map((e) => e.name).filter((n) => n.endsWith('.woff2')));
  for (const name of ['cubao', 'inter-latin-wght']) {
    const used = fetched.filter((n) => n.includes(name));
    expect(used, name).toHaveLength(1);
    expect(preloads, name).toContain(used[0]);
  }
});
