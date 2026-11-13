import { test, expect } from '@playwright/test';
import { runPageBasics } from './helpers.mjs';

runPageBasics(test, '/solutions.html');

const SECTIONS = ['retail', 'food', 'freelancers', 'online-sellers'];
const VARIANTS = ['pos-sales', 'menu-orders', 'retainer-invoice', 'order-links'];
const PHOTOS = ['sari-sari', 'cafe-counter', 'freelancer-desk', 'online-seller-packing'];

test.describe('solutions page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/solutions.html');
  });

  test('marks Solutions as the current page', async ({ page }) => {
    await expect(page.locator('#primary-menu a[data-nav="solutions"]')).toHaveAttribute('aria-current', 'page');
  });

  test('sub-nav links resolve to the four sections in order', async ({ page }) => {
    const nav = page.getByRole('navigation', { name: 'Solutions' });
    const hrefs = await nav.locator('a').evaluateAll((as) => as.map((a) => a.getAttribute('href')));
    expect(hrefs).toEqual(SECTIONS.map((id) => `#${id}`));
    const ids = await page.locator('main > section[id]').evaluateAll((els) => els.map((e) => e.id));
    expect(ids.filter((id) => SECTIONS.includes(id))).toEqual(SECTIONS);
  });

  test('each section names a pain point and three ways BYAHE helps', async ({ page }) => {
    for (const id of SECTIONS) {
      await expect(page.locator(`#${id} .pain`)).toHaveCount(1);
      await expect(page.locator(`#${id} .benefits > li`)).toHaveCount(3);
    }
  });

  test('each section has a quote with an initials avatar and no face photo', async ({ page }) => {
    for (const id of SECTIONS) {
      const quote = page.locator(`#${id} figure.quote`);
      await expect(quote.locator('blockquote')).toHaveCount(1);
      await expect(quote.locator('.avatar')).toHaveText(/^[A-Z]{2}$/);
      await expect(quote.locator('img')).toHaveCount(0);
    }
  });

  test('each section shows its scene photo with duotone and alt text', async ({ page }) => {
    for (const [i, id] of SECTIONS.entries()) {
      const img = page.locator(`#${id} .photo-duotone img`);
      await expect(img).toHaveCount(1);
      await expect(img).toHaveAttribute('src', `/images/${PHOTOS[i]}.webp`);
      expect((await img.getAttribute('alt')).length).toBeGreaterThan(10);
    }
  });

  test('four mockups follow the ui-mockup pattern', async ({ page }) => {
    for (const [i, id] of SECTIONS.entries()) {
      const m = page.locator(`#${id} .ui-mockup`);
      await expect(m).toHaveCount(1);
      await expect(m).toHaveClass(new RegExp(`ui-mockup--${VARIANTS[i]}`));
      await expect(m).toHaveAttribute('role', 'img');
      expect((await m.getAttribute('aria-label')).length).toBeGreaterThan(30);
      await expect(m.locator('img')).toHaveCount(0);
    }
  });

  test('money in mockups uses tabular digits', async ({ page }) => {
    const values = await page.locator('.ui-mockup .num').evaluateAll((els) => els.map((e) => getComputedStyle(e).fontVariantNumeric));
    expect(values.length).toBeGreaterThanOrEqual(8);
    for (const v of values) expect(v).toContain('tabular-nums');
  });

  test('ends with the CTA band', async ({ page }) => {
    await expect(page.locator('main > section#cta')).toHaveCount(1);
  });
});

test('sections alternate sides on desktop', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.goto('/solutions.html');
  const sides = [];
  for (const id of SECTIONS) {
    const section = await page.locator(`#${id}`).boundingBox();
    const visual = await page.locator(`#${id} .photo-duotone`).boundingBox();
    sides.push(visual.x + visual.width / 2 > section.x + section.width / 2 ? 'right' : 'left');
  }
  for (let i = 1; i < sides.length; i++) expect(sides[i], SECTIONS[i]).not.toBe(sides[i - 1]);
});

test('amounts line up in a right-hand column inside each mockup', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.goto('/solutions.html');
  for (const variant of ['menu-orders', 'order-links']) {
    const rights = await page.locator(`.ui-mockup--${variant} .ui-row > .num`).evaluateAll((els) => els.map((e) => Math.round(e.getBoundingClientRect().right)));
    expect(rights.length).toBeGreaterThanOrEqual(3);
    expect(new Set(rights).size, variant).toBe(1);
  }
});

test('desktop headline keeps "bawat negosyo" together', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.goto('/solutions.html');
  const tops = await page.locator('#solutions-title').evaluate((el) => {
    const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT);
    const text = [];
    while (walker.nextNode()) text.push(walker.currentNode);
    const r = document.createRange();
    const all = text.map((n) => n.textContent).join('');
    const node = text.find((n) => n.textContent.includes('bawat')) || text[text.length - 1];
    const i = node.textContent.indexOf('bawat');
    r.setStart(node, i); r.setEnd(node, i + 1);
    const bawat = r.getBoundingClientRect().top;
    const n2 = text.find((n) => n.textContent.includes('negosyo'));
    const j = n2.textContent.indexOf('negosyo');
    r.setStart(n2, j); r.setEnd(n2, j + 1);
    return { bawat: Math.round(bawat), negosyo: Math.round(r.getBoundingClientRect().top), all };
  });
  expect(tops.bawat).toBe(tops.negosyo);
});
