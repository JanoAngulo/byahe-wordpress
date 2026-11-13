import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { contrastRatio, checkRules } from '../../scripts/lib/contrast.mjs';

const tokens = JSON.parse(readFileSync(new URL('../../tokens/tokens.json', import.meta.url)));

test('navy on yellow is ~9.8:1', () => {
  assert.ok(Math.abs(contrastRatio('#13205C', '#FFC93C') - 9.85) < 0.1);
});

test('white on blue passes AA', () => {
  assert.ok(contrastRatio('#FFFFFF', '#1D3FBB') >= 8.4);
});

test('red on yellow is large-text only', () => {
  const r = contrastRatio('#D62828', '#FFC93C');
  assert.ok(r >= 3 && r < 4.5);
});

test('navy on blue fails', () => {
  assert.ok(contrastRatio('#13205C', '#1D3FBB') < 2);
});

test('order of arguments does not matter', () => {
  assert.equal(contrastRatio('#FFC93C', '#13205C'), contrastRatio('#13205C', '#FFC93C'));
});

test('all token contrast rules pass', () => {
  assert.equal(checkRules(tokens).pass, true);
});

test('a failing rule is reported', () => {
  const bad = { ...tokens, contrastRules: [{ fg: 'navy', bg: 'blue', min: 4.5 }] };
  const result = checkRules(bad);
  assert.equal(result.pass, false);
  assert.equal(result.results[0].pass, false);
});
