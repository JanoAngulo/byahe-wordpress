import { expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

export async function expectNoA11yViolations(page) {
  const results = await new AxeBuilder({ page })
    .withTags(['wcag2a', 'wcag2aa', 'wcag21aa', 'wcag22aa'])
    .analyze();
  const serious = results.violations.filter((v) => ['serious', 'critical'].includes(v.impact));
  const summary = serious.map((v) => `${v.id}: ${v.nodes.map((n) => n.target.join(' ')).join(', ')}`);
  expect(summary, 'serious/critical axe violations').toEqual([]);
}

export async function expectNoHorizontalScroll(page) {
  const { scrollWidth, innerWidth } = await page.evaluate(() => ({
    scrollWidth: document.documentElement.scrollWidth,
    innerWidth: window.innerWidth,
  }));
  expect(scrollWidth, 'page scroll width').toBeLessThanOrEqual(innerWidth);
}

export async function expectSingleH1(page) {
  await expect(page.locator('h1')).toHaveCount(1);
}

export async function expectLandmarks(page) {
  for (const selector of ['header', 'nav', 'main', 'footer']) {
    await expect(page.locator(selector).first(), `${selector} landmark`).toBeAttached();
  }
}

export async function expectNoMotionWhenReduced(page) {
  await page.emulateMedia({ reducedMotion: 'reduce' });
  const offenders = await page.evaluate(() => {
    const toSeconds = (v) => v.split(',').map((s) => {
      const t = s.trim();
      return t.endsWith('ms') ? parseFloat(t) / 1000 : parseFloat(t);
    });
    const bad = [];
    for (const el of document.querySelectorAll('*')) {
      const cs = getComputedStyle(el);
      const durations = [...toSeconds(cs.animationDuration), ...toSeconds(cs.transitionDuration)];
      if (durations.some((d) => d > 0.01)) bad.push(el.tagName.toLowerCase() + (el.className ? `.${String(el.className).split(' ')[0]}` : ''));
    }
    return bad.slice(0, 10);
  });
  expect(offenders, 'elements still animating with reduced motion').toEqual([]);
}

export function runPageBasics(test, path) {
  test.describe(`page basics: ${path}`, () => {
    test.beforeEach(async ({ page }) => {
      await page.goto(path);
    });
    test('has no serious accessibility violations', async ({ page }) => expectNoA11yViolations(page));
    test('has no horizontal scroll', async ({ page }) => expectNoHorizontalScroll(page));
    test('has exactly one h1', async ({ page }) => expectSingleH1(page));
    test('has header, nav, main and footer landmarks', async ({ page }) => expectLandmarks(page));
    test('stops motion when reduced motion is preferred', async ({ page }) => expectNoMotionWhenReduced(page));
  });
}
