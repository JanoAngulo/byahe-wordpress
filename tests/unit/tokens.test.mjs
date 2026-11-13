import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { toTailwindTheme, toThemeJsonSettings } from '../../scripts/lib/tokens.mjs';

const tokens = JSON.parse(readFileSync(new URL('../../tokens/tokens.json', import.meta.url)));

test('tailwind theme has every color', () => {
  const css = toTailwindTheme(tokens);
  for (const n of ['yellow', 'blue', 'navy', 'red', 'cream', 'white']) {
    assert.match(css, new RegExp(`--color-${n}:`));
  }
});

test('tailwind theme has hard shadows and radii', () => {
  const css = toTailwindTheme(tokens);
  assert.match(css, /--shadow-hard-sm: 4px 4px 0 #13205C/);
  assert.match(css, /--radius-pill: 999px/);
});

test('tailwind theme has font families with fallbacks', () => {
  const css = toTailwindTheme(tokens);
  assert.match(css, /--font-heading: "Space Grotesk Variable", system-ui, sans-serif/);
  assert.match(css, /--font-body: "Inter Variable", system-ui, sans-serif/);
});

test('theme.json palette disables core defaults', () => {
  assert.equal(toThemeJsonSettings(tokens).color.defaultPalette, false);
});

test('theme.json palette slugs match token names', () => {
  assert.deepEqual(
    toThemeJsonSettings(tokens).color.palette.map((p) => p.slug),
    Object.keys(tokens.color),
  );
});

test('theme.json shadow presets', () => {
  assert.deepEqual(
    toThemeJsonSettings(tokens).shadow.presets.map((p) => p.slug),
    ['hard-sm', 'hard-lg'],
  );
});

test('theme.json font sizes and spacing come from tokens', () => {
  const s = toThemeJsonSettings(tokens);
  assert.deepEqual(s.typography.fontSizes.map((f) => f.slug), Object.keys(tokens.fontSize));
  assert.deepEqual(s.spacing.spacingSizes.map((f) => f.slug), Object.keys(tokens.spacing));
});
