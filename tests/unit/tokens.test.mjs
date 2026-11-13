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
    ['hard-sm', 'hard-md', 'hard-lg'],
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

test('theme.json registers self-hosted font faces', () => {
  const families = toThemeJsonSettings(tokens).typography.fontFamilies;
  const face = (slug) => families.find((f) => f.slug === slug).fontFace;
  assert.deepEqual(face('display')[0], {
    fontFamily: 'Cubao Free',
    fontWeight: '400',
    fontStyle: 'normal',
    fontStretch: '64% 177%',
    fontDisplay: 'swap',
    src: ['file:./assets/fonts/cubao-free-variable.woff2'],
  });
  assert.equal(face('body')[0].fontWeight, '100 900');
  assert.equal(face('body')[0].fontFamily, 'Inter Variable');
  assert.deepEqual(face('body')[0].src, ['file:./assets/fonts/inter-latin-wght-normal.woff2']);
  assert.equal(face('heading')[0].fontWeight, '300 700');
  assert.equal('fontStretch' in face('heading')[0], false);
});

test('theme.json font size names read like words', () => {
  assert.deepEqual(
    toThemeJsonSettings(tokens).typography.fontSizes.map((f) => f.name),
    ['Small', 'Base', 'Large', 'Extra large', '2× large', '3× large', '4× large', '5× large', '6× large'],
  );
});

test('theme.json has the navy and yellow duotone preset', () => {
  assert.deepEqual(toThemeJsonSettings(tokens).color.duotone, [
    { slug: 'navy-yellow', name: 'Navy and yellow', colors: ['#13205C', '#FFC93C'] },
  ]);
});

test('tailwind theme has the medium shadow, small radius and shadow offset', () => {
  const css = toTailwindTheme(tokens);
  assert.match(css, /--shadow-hard-md: 6px 6px 0 #13205C;/);
  assert.match(css, /--radius-sm: 6px;/);
  assert.match(css, /--shadow-offset-xs: 3px;/);
});

test('theme.json exposes radius and shadow offset as custom properties', () => {
  const s = toThemeJsonSettings(tokens);
  assert.equal(s.custom.radius.sm, '6px');
  assert.equal(s.custom.shadowOffset.xs, '3px');
});
