import { test, expect } from '@playwright/test';
import { runPageBasics } from './helpers.mjs';

runPageBasics(test, '/pricing.html');

const price = (page, tier) => page.locator(`#${tier} .price`);

test.describe('pricing page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/pricing.html');
  });

  test('marks Pricing as the current page', async ({ page }) => {
    await expect(page.locator('#primary-menu a[data-nav="pricing"]')).toHaveAttribute('aria-current', 'page');
  });

  test('has the four tiers with Libre anchored', async ({ page }) => {
    for (const id of ['libre', 'negosyo', 'pro', 'enterprise']) await expect(page.locator(`#${id}`)).toHaveCount(1);
    await expect(page.locator('#negosyo')).toContainText('Most popular');
  });

  test('defaults to monthly', async ({ page }) => {
    const toggle = page.getByRole('switch', { name: 'Bill yearly' });
    await expect(toggle).toHaveAttribute('aria-checked', 'false');
    await expect(price(page, 'libre')).toHaveText('₱0');
    await expect(price(page, 'negosyo')).toHaveText('₱499');
    await expect(price(page, 'pro')).toHaveText('₱1,499');
    await expect(page.locator('#negosyo .price-period')).toHaveText('per month');
  });

  test('toggle by keyboard switches to yearly and back', async ({ page }) => {
    const toggle = page.getByRole('switch', { name: 'Bill yearly' });
    await toggle.focus();
    await page.keyboard.press('Space');
    await expect(toggle).toHaveAttribute('aria-checked', 'true');
    await expect(price(page, 'negosyo')).toHaveText('₱4,990');
    await expect(price(page, 'pro')).toHaveText('₱14,990');
    await expect(price(page, 'libre')).toHaveText('₱0');
    await expect(page.locator('#negosyo .price-period')).toHaveText('per year');
    await expect(page.locator('#billing-status')).toContainText(/yearly/i);
    await page.keyboard.press('Enter');
    await expect(toggle).toHaveAttribute('aria-checked', 'false');
    await expect(price(page, 'negosyo')).toHaveText('₱499');
    await expect(page.locator('#billing-status')).toContainText(/monthly/i);
  });

  test('prices keep their width when the toggle changes them', async ({ page }) => {
    const style = await price(page, 'pro').evaluate((el) => getComputedStyle(el).fontVariantNumeric);
    expect(style).toContain('tabular-nums');
  });

  test('libre CTA opens the demo dialog', async ({ page }) => {
    await page.locator('#libre [data-open-demo-dialog]').click();
    await expect(page.locator('#demo-dialog')).toBeVisible();
  });

  test('fees table values', async ({ page }) => {
    const fees = page.locator('#fees table');
    await expect(fees).toContainText('1.0%');
    await expect(fees).toContainText('2.9% + ₱15');
    await expect(fees).toContainText('₱10');
  });

  test('comparison table has proper headers', async ({ page }) => {
    const table = page.locator('#compare table');
    expect(await table.locator('th[scope=col]').count()).toBeGreaterThanOrEqual(4);
    expect(await table.locator('th[scope=row]').count()).toBeGreaterThanOrEqual(5);
    await expect(table.locator('caption')).toHaveCount(1);
  });

  test('comparison marks are readable by screen readers', async ({ page }) => {
    const marks = page.locator('#compare td .mark');
    expect(await marks.count()).toBeGreaterThan(0);
    for (const m of await marks.all()) await expect(m.locator('.sr-only')).toHaveText(/^(Included|Not included)$/);
  });

  test('FAQ uses details/summary', async ({ page }) => {
    expect(await page.locator('#faq details').count()).toBeGreaterThanOrEqual(5);
    const first = page.locator('#faq details').first();
    await first.locator('summary').click();
    await expect(first).toHaveAttribute('open', '');
  });
});

test('comparison table scrolls inside its wrapper on mobile', async ({ page }, info) => {
  test.skip(info.project.name !== 'mobile', 'mobile only');
  await page.goto('/pricing.html');
  const wrap = page.locator('#compare .table-scroll');
  await expect(wrap).toHaveAttribute('tabindex', '0');
  await expect(wrap).toHaveAttribute('role', 'region');
  const { sw, cw } = await wrap.evaluate((el) => ({ sw: el.scrollWidth, cw: el.clientWidth }));
  expect(sw).toBeGreaterThan(cw);
});

test('monthly prices visible and toggle hidden without JS', async ({ browser }) => {
  const context = await browser.newContext({ javaScriptEnabled: false });
  const page = await context.newPage();
  await page.goto('http://localhost:4173/pricing.html');
  await expect(page.locator('#negosyo .price')).toHaveText('₱499');
  await expect(page.locator('#pro .price')).toHaveText('₱1,499');
  await expect(page.locator('.billing-toggle')).toBeHidden();
  await context.close();
});

test('header CTA lands on the Libre tier', async ({ page }) => {
  await page.goto('/');
  await page.locator('header .header-cta').click();
  await expect(page).toHaveURL(/pricing\.html#libre$/);
  await expect(page.locator('#libre')).toBeInViewport();
});

test('toggling billing never changes card sizes (no layout shift)', async ({ page }) => {
  await page.goto('/pricing.html');
  const sizes = () => page.locator('.tier').evaluateAll((els) => els.map((e) => `${Math.round(e.getBoundingClientRect().width)}x${Math.round(e.getBoundingClientRect().height)}`));
  const before = await sizes();
  await page.getByRole('switch', { name: 'Bill yearly' }).click();
  expect(await sizes()).toEqual(before);
});

test('pills never wrap onto two lines', async ({ page }) => {
  await page.goto('/pricing.html');
  for (const pill of await page.locator('.pill').all()) {
    const lines = await pill.evaluate((el) => {
      const r = document.createRange();
      r.selectNodeContents(el);
      return new Set([...r.getClientRects()].map((x) => Math.round(x.top))).size;
    });
    expect(lines).toBe(1);
  }
});

test('FAQ shares the left edge of the other sections', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.goto('/pricing.html');
  const faqX = (await page.locator('#faq-title').boundingBox()).x;
  const plansX = (await page.locator('#plans-title').boundingBox()).x;
  expect(Math.abs(faqX - plansX)).toBeLessThan(1);
});

test('plan names and prices line up across cards on desktop', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop', 'desktop only');
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.goto('/pricing.html');
  for (const sel of ['.tier__name', '.tier__price']) {
    const ys = await page.locator(sel).evaluateAll((els) => els.map((e) => Math.round(e.getBoundingClientRect().top)));
    expect(new Set(ys).size, sel).toBe(1);
  }
});

test('FAQ toggle icon is drawn, not a font glyph', async ({ page }) => {
  await page.goto('/pricing.html');
  const after = await page.locator('#faq summary').first().evaluate((el) => {
    const cs = getComputedStyle(el, '::after');
    return { content: cs.content, bg: cs.backgroundImage };
  });
  expect(after.content).toBe('""');
  expect(after.bg).toContain('svg');
});

test.describe('FAQ two-column layout', () => {
  test('questions sit beside the heading column on desktop, stacked on phones', async ({ page }, info) => {
    await page.goto('/pricing.html');
    const title = await page.locator('#faq-title').boundingBox();
    const list = await page.locator('#faq .faq').boundingBox();
    if (info.project.name === 'desktop') {
      expect(list.x).toBeGreaterThan(title.x + 300);
      expect(Math.abs(list.y - title.y)).toBeLessThan(40);
    } else {
      expect(list.y).toBeGreaterThan(title.y + title.height);
    }
  });

  test('side card offers to start on Libre', async ({ page }) => {
    await page.goto('/pricing.html');
    const aside = page.locator('#faq .faq-cta');
    await expect(aside).toContainText('Still deciding?');
    await aside.locator('[data-open-demo-dialog]').click();
    await expect(page.locator('#demo-dialog')).toBeVisible();
  });
});

test('plan names are level 3 headings', async ({ page }) => {
  await page.goto('/pricing.html');
  for (const [id, name] of [['libre', 'Libre'], ['negosyo', 'Negosyo'], ['pro', 'Pro'], ['enterprise', 'Enterprise']]) {
    await expect(page.locator(`#${id}`).getByRole('heading', { level: 3, name })).toBeVisible();
  }
});

test('home pricing teaser plan names are level 3 headings', async ({ page }) => {
  await page.goto('/index.html');
  for (const name of ['Negosyo', 'Pro', 'Enterprise']) {
    await expect(page.locator('#pricing-teaser').getByRole('heading', { level: 3, name })).toBeVisible();
  }
});
