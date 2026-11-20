import { test, expect } from '@playwright/test';
import { readFileSync } from 'node:fs';
import { runPageBasics } from '../e2e/helpers.mjs';
import { PAGES } from './pages.mjs';
import { adminContext, invalidBlocks, pageId } from './helpers.mjs';

runPageBasics(test, PAGES.about);

const photos = JSON.parse(readFileSync(new URL('../../scripts/photos.json', import.meta.url)));

test.describe('about page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto(PAGES.about);
  });

  test('marks About as the current page', async ({ page }) => {
    await expect(page.locator(`nav[aria-label="Primary"] a[href$="${PAGES.about}"]`)).toHaveAttribute('aria-current', 'page');
  });

  test('sections appear in order', async ({ page }) => {
    const ids = await page.locator('main .wp-block-post-content > section[id]').evaluateAll((els) => els.map((e) => e.id));
    const wanted = ['story', 'mission', 'values', 'team', 'numbers', 'demo-note', 'credits'];
    expect(ids.filter((id) => wanted.includes(id))).toEqual(wanted);
  });

  test('team grid has six members with initials and no photos', async ({ page }) => {
    const members = page.locator('#team .member');
    await expect(members).toHaveCount(6);
    await expect(page.locator('#team img')).toHaveCount(0);
    for (const m of await members.all()) {
      await expect(m.locator('.avatar')).toHaveText(/^[A-Z]{2}$/);
      await expect(m.locator('.avatar')).toHaveAttribute('aria-hidden', 'true');
      await expect(m.locator('h3')).toHaveCount(1);
      await expect(m.locator('.member__role')).toHaveCount(1);
    }
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

  test('photos have alt text and use the duotone treatment', async ({ page }) => {
    for (const id of ['story', 'mission']) {
      const img = page.locator(`#${id} .photo-duotone img`);
      await expect(img).toHaveCount(1);
      expect((await img.getAttribute('alt')).length, id).toBeGreaterThan(10);
    }
  });

  test('footer photo-credits link lands on the credits section', async ({ page }) => {
    await page.locator('footer a', { hasText: 'Photo credits' }).click();
    await expect(page).toHaveURL(/\/about\/#credits$/);
    await expect(page.locator('#credits')).toBeInViewport();
  });

  test('credits list every photo with its source link and the three fonts', async ({ page }) => {
    const credits = page.locator('#credits');
    for (const p of photos) await expect(credits.locator(`a[href="${p.pageUrl}"]`)).toHaveCount(1);
    for (const font of ['Cubao Free', 'Inter', 'Space Grotesk']) await expect(credits).toContainText(font);
  });

  test('photo credits have one item per photos.json entry, each naming a photographer and the license', async ({ page }) => {
    const items = page.locator('#credits .credits-list').first().locator('> li');
    await expect(items).toHaveCount(photos.length);
    for (const [i, p] of photos.entries()) {
      await expect(items.nth(i)).toContainText(p.photographer ?? 'Pexels contributor');
      await expect(items.nth(i)).toContainText('Pexels License');
    }
  });
});

test('mission and demo note fill the width on desktop', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.goto(PAGES.about);
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
  await page.goto(PAGES.about);
  expect(await page.locator('#demo-note .built-list li').count()).toBeGreaterThanOrEqual(4);
});

test('opens in the editor with every block valid', async ({ browser, baseURL }, info) => {
  test.skip(info.project.name !== 'desktop', 'editor check runs once');
  const ctx = await adminContext(browser, baseURL);
  const page = await ctx.newPage();
  expect(await invalidBlocks(page, await pageId(page, 'about'))).toEqual([]);
  await ctx.close();
});
