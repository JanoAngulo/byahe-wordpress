#!/usr/bin/env node
/**
 * Zips the built theme and plugin for the rolling demo release:
 * dist/zips/byahe-theme.zip (root folder byahe/) and
 * dist/zips/byahe-core.zip (root folder byahe-core/), without sources.
 *
 * Run after `npm run build:wp`. Same script in CI and locally, so the zips
 * tested on a dev machine are the zips the release ships.
 */
import { mkdirSync, readdirSync, readFileSync, rmSync } from 'node:fs';
import { join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import AdmZip from 'adm-zip';

export const PACKAGES = [
  { dir: 'theme/byahe', zip: 'byahe-theme.zip', root: 'byahe', exclude: ['src', 'theme.base.json'] },
  { dir: 'plugins/byahe-core', zip: 'byahe-core.zip', root: 'byahe-core', exclude: ['src', 'node_modules'] },
];

const excluded = (rel, exclude) => exclude.some((ex) => rel === ex || rel.startsWith(`${ex}/`));

/**
 * Files to put in a package's zip, as { name, path } with zip entry names
 * under the package's root folder.
 */
export function zipEntries(dir, { root, exclude }) {
  const out = [];
  const walk = (rel) => {
    for (const d of readdirSync(join(dir, rel), { withFileTypes: true })) {
      const child = rel ? `${rel}/${d.name}` : d.name;
      if (excluded(child, exclude)) continue;
      if (d.isDirectory()) walk(child);
      else if (d.isFile()) out.push({ name: `${root}/${child}`, path: join(dir, child) });
    }
  };
  walk('');
  return out;
}

/** Writes one package's zip into outDir and returns its path. */
export function packageZip(dir, pkg, outDir) {
  const zip = new AdmZip();
  for (const entry of zipEntries(dir, pkg)) zip.addFile(entry.name, readFileSync(entry.path));
  mkdirSync(outDir, { recursive: true });
  const file = join(outDir, pkg.zip);
  rmSync(file, { force: true });
  zip.writeZip(file);
  return file;
}

if (process.argv[1] && resolve(process.argv[1]) === fileURLToPath(import.meta.url)) {
  const root = fileURLToPath(new URL('../', import.meta.url));
  const outDir = join(root, 'dist/zips');
  for (const pkg of PACKAGES) console.log(packageZip(join(root, pkg.dir), pkg, outDir));
}
