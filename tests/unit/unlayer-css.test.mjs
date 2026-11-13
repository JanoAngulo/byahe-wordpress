import { test } from 'node:test';
import assert from 'node:assert/strict';
import { unlayer } from '../../scripts/unlayer-css.mjs';

test('unwraps layer blocks and keeps their rules in order', async () => {
  const out = await unlayer('@layer theme{:root{--a:1}}@layer components{.card{color:red}}@layer utilities{.m-0{margin:0}}');
  assert.equal(out.replace(/\s+/g, ''), ':root{--a:1}.card{color:red}.m-0{margin:0}');
});

test('drops layer order statements', async () => {
  const out = await unlayer('@layer theme, base, components, utilities;.a{color:red}');
  assert.equal(out.replace(/\s+/g, ''), '.a{color:red}');
});

test('keeps rules nested inside media queries', async () => {
  const out = await unlayer('@layer utilities{@media (min-width:48rem){.md\:flex{display:flex}}}');
  assert.match(out, /@media \(min-width:48rem\)\{\.md\:flex\{display:flex\}\}/);
  assert.doesNotMatch(out, /@layer/);
});
