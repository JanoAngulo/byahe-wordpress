import { test, expect } from '@playwright/test';

const PAGES = ['/components.html', '/', '/product.html', '/pricing.html', '/solutions.html', '/about.html'];
const WIDTHS = [360, 768, 1024, 1280];
const CARD = '.card-hard, .tier, .ui-mockup, .faq details';
const MIN_CARD_WIDTH = 260;

for (const path of PAGES) {
  for (const width of WIDTHS) {
    test(`cards hold up on ${path} at ${width}px`, async ({ page }, info) => {
      test.skip(info.project.name !== 'desktop', 'viewport set explicitly');
      await page.setViewportSize({ width, height: 900 });
      await page.goto(path);
      const problems = await page.evaluate(({ selector, minWidth }) => {
        const lineCount = (el) => {
          const r = document.createRange();
          r.selectNodeContents(el);
          return new Set([...r.getClientRects()].filter((x) => x.width > 0).map((x) => Math.round(x.top))).size;
        };
        const label = (el) => `${el.tagName.toLowerCase()}.${String(el.className).split(' ')[0]}${el.id ? `#${el.id}` : ''}`;
        const out = [];
        for (const card of document.querySelectorAll(selector)) {
          if (card.closest('dialog')) continue;
          const c = card.getBoundingClientRect();
          if (c.width === 0) continue;
          if (c.width < minWidth) out.push(`${label(card)} only ${Math.round(c.width)}px wide`);
          for (const el of card.querySelectorAll('.btn, .pill, .ui-btn, .price, .price-text, .ui-role')) {
            if (lineCount(el) > 1) out.push(`${label(el)} "${el.textContent.trim()}" wraps in ${label(card)}`);
          }
          for (const pill of card.querySelectorAll('.pill, .tier__badge')) {
            const p = pill.getBoundingClientRect();
            if (p.left < c.left - 0.5 || p.right > c.right + 0.5) out.push(`${label(pill)} spills out of ${label(card)}`);
          }
        }
        return [...new Set(out)];
      }, { selector: CARD, minWidth: MIN_CARD_WIDTH });
      expect(problems).toEqual([]);
    });
  }
}
