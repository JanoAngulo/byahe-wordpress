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
  assert.deepEqual(s.typography.fontSizes.map((f) => f.slug), Object.keys(tokens.typeScale.steps));
  assert.match(s.typography.fontSizes[0].size, /^clamp\(/);
  assert.equal(s.typography.fluid, false);
  assert.deepEqual(s.spacing.spacingSizes.map((f) => f.slug), Object.keys(tokens.spacing));
});

import { typeScale } from '../../scripts/lib/tokens.mjs';

test('type scale has the nine named steps in order', () => {
  assert.deepEqual(typeScale(tokens).map((s) => s.name), ['sm', 'base', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl']);
});

test('base step is 16px on phones and 18px on desktop', () => {
  const base = typeScale(tokens).find((s) => s.name === 'base');
  assert.equal(base.minPx, 16);
  assert.equal(base.maxPx, 18);
});

test('each step is the previous times the ratio', () => {
  const steps = typeScale(tokens);
  for (let i = 1; i < steps.length; i++) {
    assert.ok(Math.abs(steps[i].minPx / steps[i - 1].minPx - 1.2) < 0.01, `${steps[i].name} min ratio`);
    assert.ok(Math.abs(steps[i].maxPx / steps[i - 1].maxPx - 1.25) < 0.01, `${steps[i].name} max ratio`);
  }
});

test('fluid clamp hits min at 360px and max at 1280px', () => {
  for (const s of typeScale(tokens)) {
    const m = s.clamp.match(/^clamp\(([\d.]+)rem, ([\d.-]+)rem \+ ([\d.-]+)vw, ([\d.]+)rem\)$/);
    assert.ok(m, `${s.name} clamp format: ${s.clamp}`);
    const at = (vw) => (parseFloat(m[2]) * 16) + (parseFloat(m[3]) * vw / 100);
    assert.ok(Math.abs(at(360) - s.minPx) < 0.1, `${s.name} at 360`);
    assert.ok(Math.abs(at(1280) - s.maxPx) < 0.1, `${s.name} at 1280`);
  }
});

test('tailwind theme resets default sizes and emits the scale with line heights', () => {
  const css = toTailwindTheme(tokens);
  assert.match(css, /--text-\*: initial;/);
  assert.match(css, /--text-6xl: clamp\(/);
  assert.match(css, /--text-6xl--line-height: 0\.95;/);
  assert.doesNotMatch(css, /--text-xs:/);
});
