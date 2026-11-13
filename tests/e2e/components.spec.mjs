import { test, expect } from '@playwright/test';
import { runPageBasics } from './helpers.mjs';

runPageBasics(test, '/components.html');

test.describe('header navigation', () => {
  test('mobile menu toggles with keyboard and Esc returns focus', async ({ page }, info) => {
    test.skip(info.project.name !== 'mobile', 'mobile only');
    await page.goto('/components.html');
    const toggle = page.locator('[aria-controls="primary-menu"]');
    const menu = page.locator('#primary-menu');
    await expect(menu).toBeHidden();
    await toggle.focus();
    await page.keyboard.press('Enter');
    await expect(toggle).toHaveAttribute('aria-expanded', 'true');
    await expect(menu).toBeVisible();
    await page.keyboard.press('Escape');
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await expect(menu).toBeHidden();
    await expect(toggle).toBeFocused();
  });

  test('desktop shows nav links without a toggle', async ({ page }, info) => {
    test.skip(info.project.name !== 'desktop', 'desktop only');
    await page.goto('/components.html');
    await expect(page.locator('[aria-controls="primary-menu"]')).toBeHidden();
    await expect(page.locator('#primary-menu')).toBeVisible();
  });

  test('nav links are visible without JS', async ({ browser }, info) => {
    const context = await browser.newContext({ javaScriptEnabled: false, viewport: info.project.use.viewport });
    const page = await context.newPage();
    await page.goto('http://localhost:4173/components.html');
    for (const name of ['Product', 'Solutions', 'Pricing', 'About']) {
      await expect(page.getByRole('navigation', { name: 'Primary' }).getByRole('link', { name, exact: true })).toBeVisible();
    }
    await context.close();
  });

  test('primary nav has exactly the four Phase 1 links', async ({ page }) => {
    await page.goto('/components.html');
    const hrefs = await page.locator('#primary-menu a[data-nav]').evaluateAll((as) => as.map((a) => a.getAttribute('href')));
    expect(hrefs).toEqual(['product.html', 'solutions.html', 'pricing.html', 'about.html']);
  });

  test('CTA links to pricing libre', async ({ page }) => {
    await page.goto('/components.html');
    await expect(page.locator('header .header-cta')).toHaveAttribute('href', 'pricing.html#libre');
    await expect(page.locator('header .header-cta')).toHaveText(/Sakay na/);
  });

  test('skip link moves focus to main', async ({ page }) => {
    await page.goto('/components.html');
    await page.keyboard.press('Tab');
    const skip = page.locator('.skip-link');
    await expect(skip).toBeFocused();
    await expect(skip).toBeVisible();
    await expect(skip).toHaveAttribute('href', '#main');
  });
});

test.describe('demo dialog', () => {
  test('opens, keeps focus inside and returns focus', async ({ page }) => {
    await page.goto('/components.html');
    const trigger = page.locator('[data-open-demo-dialog]').first();
    await trigger.focus();
    await page.keyboard.press('Enter');
    const dialog = page.locator('#demo-dialog');
    await expect(dialog).toBeVisible();
    await expect(dialog).toHaveAttribute('aria-labelledby', 'demo-dialog-title');
    await expect(page.locator('#demo-dialog-title')).toHaveText('BYAHE is a demo company');
    const focusInside = await page.evaluate(() => document.getElementById('demo-dialog').contains(document.activeElement));
    expect(focusInside).toBe(true);
    await page.keyboard.press('Escape');
    await expect(dialog).toBeHidden();
    await expect(trigger).toBeFocused();
  });

  test('close button closes and returns focus', async ({ page }) => {
    await page.goto('/components.html');
    const trigger = page.locator('[data-open-demo-dialog]').first();
    await trigger.click();
    await page.locator('#demo-dialog [data-close-dialog]').click();
    await expect(page.locator('#demo-dialog')).toBeHidden();
    await expect(trigger).toBeFocused();
  });
});

test.describe('footer and components', () => {
  test('footer contains demo disclaimer', async ({ page }) => {
    await page.goto('/components.html');
    await expect(page.locator('footer')).toContainText(/fictional company/i);
    await expect(page.locator('footer')).toContainText(/not a real financial service/i);
  });

  test('footer links to photo credits', async ({ page }) => {
    await page.goto('/components.html');
    await expect(page.locator('footer a', { hasText: 'Photo credits' })).toHaveAttribute('href', 'about.html#credits');
  });

  test('focus ring visible on primary button', async ({ page }) => {
    await page.goto('/components.html');
    const btn = page.locator('main .btn-primary').first();
    await btn.focus();
    await page.keyboard.press('Shift+Tab');
    await page.keyboard.press('Tab');
    const width = await btn.evaluate((el) => parseFloat(getComputedStyle(el).outlineWidth));
    expect(width).toBeGreaterThanOrEqual(2);
  });

  test('cards use the hard navy shadow', async ({ page }) => {
    await page.goto('/components.html');
    const shadow = await page.locator('.card-hard').first().evaluate((el) => getComputedStyle(el).boxShadow);
    expect(shadow).toContain('rgb(19, 32, 92) 4px 4px 0px');
  });

  test('stripe shows red, yellow and blue', async ({ page }) => {
    await page.goto('/components.html');
    const bg = await page.locator('.stripe').first().evaluate((el) => getComputedStyle(el).backgroundImage);
    for (const c of ['rgb(214, 40, 40)', 'rgb(255, 201, 60)', 'rgb(29, 63, 187)']) expect(bg).toContain(c);
  });
});

test('duotone photos use the shared navy/yellow SVG filter', async ({ page }) => {
  await page.goto('/components.html');
  await expect(page.locator('filter#byahe-duotone')).toHaveCount(1);
  const filter = await page.locator('.photo-duotone img').first().evaluate((el) => getComputedStyle(el).filter);
  expect(filter).toContain('#byahe-duotone');
});

test.describe('tabular numbers', () => {
  const digitWidths = (page, font) => page.evaluate(async (fontVar) => {
    await document.fonts.ready;
    const probe = (text) => {
      const el = document.createElement('span');
      el.className = 'num';
      el.style.fontFamily = `var(${fontVar})`;
      el.style.fontSize = '40px';
      el.textContent = text;
      document.body.append(el);
      const w = el.getBoundingClientRect().width;
      el.remove();
      return w;
    };
    return { ones: probe('1111111111'), zeros: probe('0000000000') };
  }, font);

  for (const font of ['--font-body', '--font-heading']) {
    test(`.num digits are equal width in ${font}`, async ({ page }) => {
      await page.goto('/components.html');
      const { ones, zeros } = await digitWidths(page, font);
      expect(Math.abs(ones - zeros)).toBeLessThan(0.5);
    });
  }

  test('money figures in the kit use .num', async ({ page }) => {
    await page.goto('/components.html');
    const value = await page.getByText('₱482,310').evaluate((el) => getComputedStyle(el).fontVariantNumeric);
    expect(value).toContain('tabular-nums');
  });

  test('table cells use tabular numbers', async ({ page }) => {
    await page.goto('/components.html');
    const value = await page.evaluate(() => {
      const td = document.createElement('td');
      const table = document.createElement('table');
      table.append(document.createElement('tr'));
      table.firstChild.append(td);
      document.body.append(table);
      const v = getComputedStyle(td).fontVariantNumeric;
      table.remove();
      return v;
    });
    expect(value).toContain('tabular-nums');
  });
});

test('header is off-white so it separates from the yellow hero', async ({ page }, info) => {
  await page.goto('/components.html');
  const cream = 'rgb(255, 248, 231)';
  expect(await page.locator('.site-header').evaluate((el) => getComputedStyle(el).backgroundColor)).toBe(cream);
  if (info.project.name === 'mobile') {
    await page.locator('[aria-controls="primary-menu"]').click();
    expect(await page.locator('#primary-menu').evaluate((el) => getComputedStyle(el).backgroundColor)).toBe(cream);
  }
});
