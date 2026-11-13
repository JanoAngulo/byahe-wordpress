import { test, expect } from '@playwright/test';
import { expectNoHorizontalScroll } from './helpers.mjs';

// WCAG 1.4.10 Reflow is measured at 320 CSS px (1280 px at 400% zoom).
const PAGES = ['/index.html', '/product.html', '/pricing.html', '/solutions.html', '/about.html', '/components.html'];

test.beforeEach(({}, info) => test.skip(info.project.name !== 'mobile', 'one run at 320px is enough'));

for (const javaScriptEnabled of [true, false]) {
  test.describe(`at 320px, JavaScript ${javaScriptEnabled ? 'on' : 'off'}`, () => {
    test.use({ viewport: { width: 320, height: 640 }, javaScriptEnabled });
    for (const path of PAGES) {
      test(`${path} has no horizontal scroll`, async ({ page }) => {
        await page.goto(path);
        await expectNoHorizontalScroll(page);
      });
    }
  });
}

test.describe('at 320px', () => {
  test.use({ viewport: { width: 320, height: 640 } });
  for (const path of ['/index.html', '/product.html', '/solutions.html', '/components.html']) {
    test(`mockup contents on ${path} stay inside their card`, async ({ page }) => {
      await page.goto(path);
      const escapes = await page.locator('.ui-mockup').evaluateAll((mockups) =>
        mockups.flatMap((m) => {
          const box = m.getBoundingClientRect();
          return [...m.querySelectorAll('*')]
            .filter((el) => el.getBoundingClientRect().right > box.right + 0.5)
            .map((el) => `${m.className.split(' ').pop()} › ${el.className || el.tagName}`);
        }),
      );
      expect(escapes).toEqual([]);
    });
  }
});
