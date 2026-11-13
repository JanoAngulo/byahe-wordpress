import { test, expect } from '@playwright/test';

const PAGES = ['/index.html', '/product.html', '/pricing.html', '/solutions.html', '/about.html'];

// Reduced motion turns smooth scrolling off, so a focused element is on screen when measured.
test.use({ reducedMotion: 'reduce' });

for (const path of PAGES) {
  test(`Tab reaches the footer on ${path} with a visible focus ring at every stop`, async ({ page }) => {
    await page.goto(path);
    const lastFooterLink = await page.locator('footer a, footer button').last().evaluate((el) => el.outerHTML);
    const seen = [];
    for (let i = 0; i < 300; i++) {
      await page.keyboard.press('Tab');
      const stop = await page.evaluate(async () => {
        // The reduced-motion reset leaves a 0.01ms transition, so let focus styles settle for a frame.
        await new Promise((done) => requestAnimationFrame(() => requestAnimationFrame(done)));
        const el = document.activeElement;
        if (!el || el === document.body) return null;
        const s = getComputedStyle(el);
        const r = el.getBoundingClientRect();
        const ring = (s.outlineStyle !== 'none' && parseFloat(s.outlineWidth) >= 2) || (s.boxShadow && s.boxShadow !== 'none');
        return {
          html: el.outerHTML,
          label: `${el.tagName.toLowerCase()} "${(el.textContent || el.getAttribute('aria-label') || '').trim().slice(0, 40)}"`,
          ring,
          onScreen: r.width > 0 && r.height > 0 && r.bottom > 0 && r.top < innerHeight,
        };
      });
      if (!stop) break;
      expect(stop.ring, `focus ring on ${stop.label}`).toBe(true);
      expect(stop.onScreen, `${stop.label} is visible when focused`).toBe(true);
      seen.push(stop.html);
      if (stop.html === lastFooterLink) break;
    }
    expect(seen.at(-1), 'Tab reaches the last footer link').toBe(lastFooterLink);
  });
}
