import { test } from 'node:test';
import assert from 'node:assert/strict';
import { cpSync, existsSync, mkdtempSync, readdirSync, rmSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join, resolve } from 'node:path';
import { copyAssets } from '../../scripts/copy-assets.mjs';

const repo = resolve(import.meta.dirname, '../..');

function fixtureRoot() {
  const root = mkdtempSync(join(tmpdir(), 'byahe-assets-'));
  for (const p of [
    'prototype/src/fonts',
    'prototype/public/images',
    'scripts/photos.json',
    'node_modules/@fontsource-variable/inter/files/inter-latin-wght-normal.woff2',
    'node_modules/@fontsource-variable/space-grotesk/files/space-grotesk-latin-wght-normal.woff2',
    'node_modules/lucide-static/icons',
  ]) {
    cpSync(join(repo, p), join(root, p), { recursive: true });
  }
  return root;
}

test('copyAssets copies fonts, photos and photo data into the theme', async () => {
  const root = fixtureRoot();
  try {
    const out = await copyAssets({ root });
    assert.deepEqual(out.fonts, [
      'cubao-free-variable.woff2',
      'inter-latin-wght-normal.woff2',
      'space-grotesk-latin-wght-normal.woff2',
    ]);
    const sourceImages = readdirSync(join(repo, 'prototype/public/images')).filter((f) => f.endsWith('.webp'));
    for (const img of sourceImages) assert.ok(out.images.includes(img), `missing ${img}`);
    assert.ok(existsSync(join(root, 'theme/byahe/assets/images/photos.json')));
    assert.ok(existsSync(join(root, 'theme/byahe/assets/fonts/cubao-free-variable.woff2')));
  } finally {
    rmSync(root, { recursive: true, force: true });
  }
});
