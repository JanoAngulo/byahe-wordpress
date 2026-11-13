import { test, expect } from '@playwright/test';
import { readFileSync } from 'node:fs';
import { runPageBasics } from './helpers.mjs';

runPageBasics(test, '/about.html');

const photos = JSON.parse(readFileSync(new URL('../../scripts/photos.json', import.meta.url)));

test.describe('about page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/about.html');
  });

  test('marks About as the current page', async ({ page }) => {
    await expect(page.locator('#primary-menu a[data-nav="about"]')).toHaveAttribute('aria-current', 'page');
  });

  test('sections appear in order', async ({ page }) => {
    const ids = await page.locator('main > section[id]').evaluateAll((els) => els.map((e) => e.id));
    const wanted = ['story', 'mission', 'values', 'team', 'numbers', 'demo-note', 'credits'];
    expect(ids.filter((id) => wanted.includes(id))).toEqual(wanted);
  });

  test('team grid has six members with initials and no photos', async ({ page }) => {
    const members = page.locator('#team .member');
    await expect(members).toHaveCount(6);
    await expect(page.locator('#team img')).toHaveCount(0);
    for (const m of await members.all()) await expect(m.locator('.avatar')).toHaveText(/^[A-Z]{2}$/);
  });

  test('four values', async ({ page }) => {
    await expect(page.locator('#values .card-hard')).toHaveCount(4);
  });

  test('numbers are labelled as demo figures and use tabular digits', async ({ page }) => {
    await expect(page.locator('#numbers')).toContainText(/demo/i);
    const figures = page.locator('#numbers .num');
    expect(await figures.count()).toBeGreaterThanOrEqual(4);
    for (const f of await figures.all()) expect(await f.evaluate((el) => getComputedStyle(el).fontVariantNumeric)).toContain('tabular-nums');
  });

  test('demo note explains the portfolio project with links', async ({ page }) => {
    const note = page.locator('#demo-note');
    await expect(note).toContainText(/portfolio/i);
    await expect(note).toContainText(/not a real/i);
    expect(await note.locator('a').count()).toBeGreaterThanOrEqual(1);
  });

  test('footer photo-credits link lands on the credits section', async ({ page }) => {
    await page.locator('footer a', { hasText: 'Photo credits' }).click();
    await expect(page).toHaveURL(/about\.html#credits$/);
    await expect(page.locator('#credits')).toBeInViewport();
  });

  test('credits list every photo with its source link and the three fonts', async ({ page }) => {
    const credits = page.locator('#credits');
    for (const p of photos) await expect(credits.locator(`a[href="${p.pageUrl}"]`)).toHaveCount(1);
    for (const font of ['Cubao Free', 'Inter', 'Space Grotesk']) await expect(credits).toContainText(font);
  });
});

test('mission and demo note fill the width on desktop', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.goto('/about.html');
  for (const id of ['mission', 'demo-note']) {
    const container = await page.locator(`#${id} > .container`).boundingBox();
    const rightmost = await page.locator(`#${id} > .container`).evaluate((root) => {
      const rights = [];
      const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
      const r = document.createRange();
      while (walker.nextNode()) {
        if (!walker.currentNode.textContent.trim()) continue;
        r.selectNodeContents(walker.currentNode);
        rights.push(r.getBoundingClientRect().right);
      }
      root.querySelectorAll('img, .card-hard').forEach((el) => rights.push(el.getBoundingClientRect().right));
      return Math.max(...rights);
    });
    const empty = container.x + container.width - rightmost;
    expect(empty, id).toBeLessThan(container.width / 3);
  }
});

test('demo note lists how the site was built', async ({ page }) => {
  await page.goto('/about.html');
  expect(await page.locator('#demo-note .built-list li').count()).toBeGreaterThanOrEqual(4);
});
