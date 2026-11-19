import { test, expect } from '@playwright/test';
import { readFileSync } from 'node:fs';
import { expectNoA11yViolations, expectNoHorizontalScroll } from '../e2e/helpers.mjs';
import { withBlockPage } from './helpers.mjs';

const VARIANTS = ['invoice', 'payment-qr', 'reminders', 'cashflow-chart', 'bir-receipt', 'team-roles', 'pos-sales', 'menu-orders', 'retainer-invoice', 'order-links'];

const prototypeLabel = (variant) =>
  readFileSync(`prototype/partials/mockups/${variant}.html`, 'utf8').match(/aria-label="([^"]+)"/)[1];

const content = [
  ...VARIANTS.map((v) => `<!-- wp:byahe/ui-mockup {"variant":"${v}"} /-->`),
  '<!-- wp:byahe/ui-mockup {"variant":"invoice","values":{"total":"₱99","summary":"Invoice for ₱99."},"className":"is-override"} /-->',
  '<!-- wp:byahe/ui-mockup {"variant":"nope"} /-->',
].join('\n');

test('every variant renders live HTML with its prototype summary, values override, unknown variants render nothing', async ({ browser, baseURL }, info) => {
  await withBlockPage(browser, baseURL, info.project.use.viewport, content, async (page) => {
    for (const v of VARIANTS) {
      const el = page.locator(`.ui-mockup--${v}:not(.is-override)`);
      await expect(el, v).toHaveCount(1);
      await expect(el).toHaveAttribute('role', 'img');
      await expect(el).toHaveAttribute('aria-label', prototypeLabel(v));
      await expect(el.locator('img')).toHaveCount(0);
    }
    const override = page.locator('.ui-mockup--invoice.is-override');
    await expect(override.locator('.ui-total')).toHaveText('₱99');
    await expect(override).toHaveAttribute('aria-label', 'Invoice for ₱99.');
    await expect(page.locator('.wp-block-byahe-ui-mockup')).toHaveCount(VARIANTS.length + 1);
    await expect(page.locator('.ui-mockup--invoice .ui-lines li')).toHaveCount(6);
    await expect(page.locator('.ui-mockup--reminders .ui-timeline li')).toHaveCount(4);
    await expect(page.locator('.ui-mockup--cashflow-chart .ui-bars > div')).toHaveCount(6);
    await expect(page.locator('.ui-mockup--team-roles .ui-team li')).toHaveCount(3);
    await expect(page.locator('.ui-mockup--order-links .pill-overdue')).toHaveText('Expired');
    await expectNoA11yViolations(page);
    await expectNoHorizontalScroll(page);
  });
});
