import { test } from 'node:test';
import assert from 'node:assert/strict';
import { mkdtempSync, mkdirSync, writeFileSync, rmSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import AdmZip from 'adm-zip';
import { zipEntries, packageZip, PACKAGES } from '../../scripts/package-zips.mjs';

function fixture(files) {
  const dir = mkdtempSync(join(tmpdir(), 'byahe-zip-'));
  for (const f of files) {
    mkdirSync(join(dir, f, '..'), { recursive: true });
    writeFileSync(join(dir, f), f);
  }
  return dir;
}

test('theme entries sit under byahe/ and leave out src/ and theme.base.json', () => {
  const dir = fixture(['style.css', 'theme.json', 'theme.base.json', 'src/theme.css', 'assets/css/theme.css', 'patterns/a.php']);
  try {
    const theme = PACKAGES.find((p) => p.zip === 'byahe-theme.zip');
    assert.deepEqual(zipEntries(dir, theme).map((e) => e.name).sort(), [
      'byahe/assets/css/theme.css',
      'byahe/patterns/a.php',
      'byahe/style.css',
      'byahe/theme.json',
    ]);
  } finally {
    rmSync(dir, { recursive: true, force: true });
  }
});

test('plugin entries sit under byahe-core/ and leave out src/ and node_modules/', () => {
  const dir = fixture(['byahe-core.php', 'src/blocks/icon/index.js', 'node_modules/x/index.js', 'build/blocks-manifest.php', 'inc/srcset.php']);
  try {
    const plugin = PACKAGES.find((p) => p.zip === 'byahe-core.zip');
    assert.deepEqual(zipEntries(dir, plugin).map((e) => e.name).sort(), [
      'byahe-core/build/blocks-manifest.php',
      'byahe-core/byahe-core.php',
      'byahe-core/inc/srcset.php',
    ]);
  } finally {
    rmSync(dir, { recursive: true, force: true });
  }
});

test('packageZip writes a zip whose entries use forward slashes and keep file contents', () => {
  const dir = fixture(['style.css', 'assets/fonts/a.woff2']);
  const out = mkdtempSync(join(tmpdir(), 'byahe-zip-out-'));
  try {
    const file = packageZip(dir, { zip: 'x.zip', root: 'byahe', exclude: [] }, out);
    const zip = new AdmZip(file);
    const names = zip.getEntries().map((e) => e.entryName).sort();
    assert.deepEqual(names, ['byahe/assets/fonts/a.woff2', 'byahe/style.css']);
    assert.equal(zip.readAsText('byahe/style.css'), 'style.css');
  } finally {
    rmSync(dir, { recursive: true, force: true });
    rmSync(out, { recursive: true, force: true });
  }
});
