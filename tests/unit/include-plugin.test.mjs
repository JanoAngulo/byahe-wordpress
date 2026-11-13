import { test } from 'node:test';
import assert from 'node:assert/strict';
import { fileURLToPath } from 'node:url';
import { includePartials, resolveIncludes } from '../../prototype/vite-plugin-include.mjs';

const root = fileURLToPath(new URL('./fixtures/partials', import.meta.url));
const run = (html) => resolveIncludes(html, root);

test('replaces include comment with partial contents', () => {
  const out = run('<main><!-- @include b.html --></main>');
  assert.match(out, /<p>B<\/p>/);
  assert.doesNotMatch(out, /@include/);
});

test('resolves nested includes', () => {
  const out = run('<!-- @include with-nested.html -->');
  assert.match(out, /<div><span>C<\/span>/);
});

test('throws on missing partial', () => {
  assert.throws(() => run('<!-- @include nope.html -->'), /Partial not found: nope.html/);
});

test('stops at depth 5', () => {
  assert.throws(() => run('<!-- @include loop.html -->'), /depth/);
});

test('vite plugin exposes transformIndexHtml', () => {
  const plugin = includePartials({ root });
  assert.equal(plugin.name, 'byahe-include-partials');
  const hook = plugin.transformIndexHtml;
  const handler = typeof hook === 'function' ? hook : hook.handler;
  assert.match(handler('<!-- @include b.html -->'), /<p>B<\/p>/);
});
