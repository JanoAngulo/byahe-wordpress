import { test } from 'node:test';
import assert from 'node:assert/strict';
import { htmlIds } from '../lib/html-ids.mjs';

test('htmlIds finds real id attributes', () => {
  const ids = htmlIds('<section id="hero"><h1 id=\'hero-title\'>x</h1><p\nid="multi">y</p></section>');
  assert.deepEqual([...ids].sort(), ['hero', 'hero-title', 'multi']);
});

test('htmlIds ignores attributes that only end in id', () => {
  const ids = htmlIds('<div data-id="pricing" aria-describedby="x" grid="y"></div>');
  assert.equal(ids.has('pricing'), false);
});

test('htmlIds does not match a longer id by prefix', () => {
  assert.equal(htmlIds('<h1 id="hero-title-x"></h1>').has('hero-title'), false);
});
