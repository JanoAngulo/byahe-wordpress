import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';

const css = readFileSync(new URL('../../prototype/src/components.css', import.meta.url), 'utf8');
const declarations = (prop) => [...css.matchAll(new RegExp(`(?<![-\w])${prop}:\s*([^;]+);`, 'g'))].map((m) => m[1].trim());

test('box shadows use shadow tokens or the shared offset', () => {
  const offenders = declarations('box-shadow').filter((v) => {
    if (v.startsWith('var(--shadow-')) return false;
    if (/^0 0 0 /.test(v) || v === 'none') return false;
    return !/^var\(--shadow-offset-xs\) var\(--shadow-offset-xs\) 0 /.test(v);
  });
  assert.deepEqual(offenders, []);
});

test('border radii are tokens, the chart bar cap or the concentric card inner radius', () => {
  const allowed = /^(var\(--radius-[a-z]+\)|0|50%|4px 4px 0 0|8px 8px 0 0)$/;
  assert.deepEqual(declarations('border-radius').filter((v) => !allowed.test(v)), []);
});
