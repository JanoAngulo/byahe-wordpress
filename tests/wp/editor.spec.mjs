import { test, expect } from '@playwright/test';
import { readdirSync, readFileSync } from 'node:fs';
import { PAGES } from './pages.mjs';
import { adminContext, invalidBlocks, pageId, restNonce } from './helpers.mjs';

const SLUGS = { home: 'home', product: 'product', pricing: 'pricing', solutions: 'solutions', about: 'about' };
const PATTERN_DIR = new URL('../../theme/byahe/patterns/', import.meta.url);

/** Section patterns a marketer can insert: in the byahe category and not hidden from the inserter. */
const SECTION_PATTERNS = readdirSync(PATTERN_DIR)
  .filter((f) => f.endsWith('.php'))
  .map((f) => readFileSync(new URL(f, PATTERN_DIR), 'utf8'))
  .filter((src) => /^\s*\*\s*Categories:.*\bbyahe\b/m.test(src) && !/^\s*\*\s*Inserter:\s*no/m.test(src))
  .map((src) => src.match(/^\s*\*\s*Slug:\s*(\S+)/m)[1])
  .sort();

/** Visible text and element ids of the front-end main, to compare before and after a save. */
async function frontMain(page, path) {
  await page.goto(path);
  return page.locator('main').evaluate((main) => ({
    text: main.innerText.replace(/\s+/g, ' ').trim(),
    ids: [...main.querySelectorAll('[id]')].map((el) => el.id),
  }));
}

test.beforeEach(({}, info) => test.skip(info.project.name !== 'desktop', 'the editor is checked once, at desktop width'));

for (const [key, slug] of Object.entries(SLUGS)) {
  test(`${key}: every block is valid, the canvas uses the theme, and a save round-trips`, async ({ browser, baseURL }) => {
    const ctx = await adminContext(browser, baseURL);
    const page = await ctx.newPage();
    const visitor = await browser.newContext({ baseURL });
    const front = await visitor.newPage();
    try {
      const id = await pageId(page, slug);
      const before = await frontMain(front, PAGES[key]);

      expect(await invalidBlocks(page, id)).toEqual([]);

      const canvas = page.frameLocator('iframe[name="editor-canvas"]');
      const heading = canvas.locator('.is-root-container h1').first();
      await expect(heading).toBeVisible();
      expect(await heading.evaluate((el) => getComputedStyle(el).fontFamily)).toMatch(/^"?Cubao Free/);
      expect(await canvas.locator('body').evaluate((el) => getComputedStyle(el).backgroundColor)).toBe('rgb(255, 248, 231)');

      // Re-serialize the parsed blocks and save, as any edit would.
      await page.evaluate(async () => {
        const { data, blocks } = window.wp;
        data.dispatch('core/editor').editPost({ content: blocks.serialize(data.select('core/block-editor').getBlocks()) });
        await data.dispatch('core/editor').savePost();
      });
      await expect.poll(() => page.evaluate(() => window.wp.data.select('core/editor').isSavingPost())).toBe(false);
      expect(await page.evaluate(() => window.wp.data.select('core/editor').didPostSaveRequestSucceed())).toBe(true);

      expect(await frontMain(front, PAGES[key])).toEqual(before);
    } finally {
      await visitor.close();
      await ctx.close();
    }
  });
}

test('every BYAHE block shows up in the inserter search', async ({ browser, baseURL }) => {
  const ctx = await adminContext(browser, baseURL);
  const page = await ctx.newPage();
  try {
    await invalidBlocks(page, await pageId(page, 'home'));
    const titles = await page.evaluate(() =>
      window.wp.blocks.getBlockTypes().filter((b) => b.name.startsWith('byahe/')).map((b) => b.title),
    );
    expect(titles.length).toBe(7);
    await page.getByRole('button', { name: /block inserter/i }).first().click();
    await page.getByRole('searchbox', { name: /search/i }).first().fill('BYAHE');
    const options = page.getByRole('listbox', { name: 'Blocks' }).getByRole('option');
    await expect.poll(async () => (await options.allTextContents()).map((t) => t.trim()).sort()).toEqual([...titles].sort());
  } finally {
    await ctx.close();
  }
});

test('the BYAHE pattern category lists every section pattern', async ({ browser, baseURL }) => {
  const ctx = await adminContext(browser, baseURL);
  const page = await ctx.newPage();
  try {
    await page.goto('/wp-admin/');
    const nonce = await restNonce(page);
    const headers = { 'X-WP-Nonce': nonce };
    const categories = await (await page.request.get('/?rest_route=/wp/v2/block-patterns/categories', { headers })).json();
    expect(categories.map((c) => c.name)).toContain('byahe');
    const patterns = await (await page.request.get('/?rest_route=/wp/v2/block-patterns/patterns', { headers })).json();
    const listed = patterns
      .filter((p) => (p.categories ?? []).includes('byahe') && p.inserter !== false)
      .map((p) => p.name)
      .sort();
    expect(SECTION_PATTERNS.length).toBeGreaterThan(20);
    expect(listed).toEqual(SECTION_PATTERNS);
  } finally {
    await ctx.close();
  }
});
