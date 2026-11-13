import { expect } from '@playwright/test';
import { readFileSync } from 'node:fs';
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

export async function expectSpacingOnGrid(page, grid = 4) {
  const offenders = await page.evaluate((base) => {
    const props = ['paddingTop', 'paddingRight', 'paddingBottom', 'paddingLeft', 'marginTop', 'marginRight', 'marginBottom', 'marginLeft', 'rowGap', 'columnGap'];
    const out = new Set();
    for (const el of document.querySelectorAll('body *')) {
      if (el.closest('svg') || el.closest('.sr-only') || el.matches('script, style, br')) continue;
      const cs = getComputedStyle(el);
      if (cs.display === 'none') continue;
      for (const prop of props) {
        const value = parseFloat(cs[prop]);
        if (Number.isNaN(value) || value === 0) continue;
        if (prop.startsWith('margin')) {
          // Skip margins set to `auto` (centering, push-right): their pixel value is layout-derived.
          const original = cs[prop];
          const before = el.style[prop];
          el.style[prop] = 'auto';
          const isAuto = getComputedStyle(el)[prop] === original;
          el.style[prop] = before;
          if (isAuto) continue;
        }
        const rest = Math.abs(value) % base;
        if (rest > 0.01 && base - rest > 0.01) {
          const cls = typeof el.className === 'string' && el.className.trim() ? `.${el.className.trim().split(/\s+/)[0]}` : '';
          out.add(`${el.tagName.toLowerCase()}${cls} ${prop}=${cs[prop]}`);
        }
      }
    }
    return [...out];
  }, grid);
  expect(offenders, `spacing values off the ${grid}px grid`).toEqual([]);
}

const TYPE_STEPS = Object.keys(JSON.parse(readFileSync(new URL('../../tokens/tokens.json', import.meta.url))).typeScale.steps);

export async function expectTypeOnScale(page) {
  const offenders = await page.evaluate((steps) => {
    const allowed = steps.map((name) => {
      const probe = document.createElement('span');
      probe.style.fontSize = `var(--text-${name})`;
      document.body.append(probe);
      const px = parseFloat(getComputedStyle(probe).fontSize);
      probe.remove();
      return px;
    });
    const out = new Set();
    for (const el of document.querySelectorAll('body *')) {
      if (el.closest('svg') || el.closest('.sr-only') || el.matches('script, style, br')) continue;
      const hasText = [...el.childNodes].some((n) => n.nodeType === Node.TEXT_NODE && n.textContent.trim());
      if (!hasText) continue;
      const cs = getComputedStyle(el);
      if (cs.display === 'none' || cs.visibility === 'hidden') continue;
      const px = parseFloat(cs.fontSize);
      if (!allowed.some((a) => Math.abs(a - px) < 0.1)) {
        const cls = typeof el.className === 'string' && el.className.trim() ? `.${el.className.trim().split(/\s+/)[0]}` : '';
        out.add(`${el.tagName.toLowerCase()}${cls} ${cs.fontSize}`);
      }
    }
    return [...out];
  }, TYPE_STEPS);
  expect(offenders, 'font sizes off the type scale').toEqual([]);
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
    test('keeps padding, margin and gap on a 4px grid', async ({ page }) => expectSpacingOnGrid(page));
    test('uses only type scale font sizes', async ({ page }) => expectTypeOnScale(page));
  });
}
